<?php

require "Facebook.php";
require "Downloader.php";

/*
  get your token from 
  https://developers.facebook.com/tools/explorer/
*/
$token       = "change-with-your-token";

/* 
  target eg : chipul.aja or me (your self) 
  https://www.facebook.com/chipul.aja
  https://www.facebook.com/me
  https://www.facebook.com/(account)
*/
$account     = "me";

$apiFacebook = new Facebook($token);
$images      = $apiFacebook->getImages($account);

foreach ($images as $image) {
    new Downloader($image, "php");
}