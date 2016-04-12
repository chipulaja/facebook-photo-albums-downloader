<?php

class Facebook
{

    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getImages($account)
    {
        $images = array();
        $albums = $this->getAlbums($account);

        foreach ($albums as $album) {
            $image  = $this->getPhotos($album);
            $images = array_merge($images, $image);
        }

        return $images;
    }

    protected function getAlbums($account)
    {
        $url = "https://graph.facebook.com/v2.5/$account/albums?limit=1000&access_token=$this->token";
        $albumsJson = file_get_contents($url);
        $albums     = json_decode($albumsJson, true);

        return (isset($albums["data"]) && $albums["data"]) ? $albums["data"] : array();
    }

    protected function getPhotos($album)
    {
        $url = "https://graph.facebook.com/v2.5/$album[id]/photos?fields=images&limit=9999&access_token=$this->token";
        $photosJson = file_get_contents($url);
        $photos     = json_decode($photosJson, true);

        $data = (isset($photos["data"]) && $photos["data"]) ? $photos["data"] : array();

        $photoTemp = array();
        foreach ($data as $d) {
            $source   = $d["images"][0]["source"];
            $filename = explode("?", $source);
            $filename = basename($filename[0]);
            $filename = basename($filename);

            $photoTemp[] = array(
                "albumName" => $album["name"],
                "source"    => $source,
                "filename"  => $filename
            );
        }

        return $photoTemp;
    }
}