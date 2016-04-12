<?php

class Downloader
{
    public function __construct(array $image, $type = "php")
    {
        $erros = $this->imageValidate($image);

        if (!empty($erros)) {
            echo $erros[0];
        }

        $image = $this->pathBuilder($image);

        if ($type == "wget") {
            echo $this->wget($image);
        } else {
            echo $this->php($image);
        }
    }

    protected function php($image)
    {
        if (!is_dir($image['albumName'])) {
            mkdir($image['albumName']);
        }
        file_put_contents($image["fullPath"], file_get_contents($image["source"]));
        
        return  "$image[source] -> downloaded".PHP_EOL;
    }

    protected function wget($image)
    {       
        $execString = "wget -c '$image[source]' -O '$image[fullPath]'"; 
        exec("mkdir -p '$image[albumName]'");
        exec($execString, $output, $return_var);
        
        return $execString;
    }

    protected function imageValidate($image)
    {
        $errors = array();

        if (empty($image["albumName"])) {
            $errors[] = "nama album tidak boleh kosong";
        }

        if (empty($image["source"])) {
            $errors[] = "source atau alamat url tidak di temukan";
        }

        if (empty($image["filename"])) {
            $errors[] = "filename tidak boleh kosong";
        }

        return $errors;
    }

    protected function pathBuilder($image)
    {
        $albumName = $image["albumName"];
        $filename  = $image["filename"];
        $source    = $image["source"];

        return array(
            "albumName"=> $albumName,
            "fullPath" => "./$albumName/$filename",
            "source"   => $source
        );
    }
}
