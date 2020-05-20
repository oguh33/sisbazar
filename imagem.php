<?php


include_once "module/Thumbnails.php";


// Image
$src = $_GET['img'];

$ext = pathinfo($src, PATHINFO_EXTENSION);
var_dump($ext); die;

// Begin
$img = new Thumbnails;
$img->set_img($src);
$img->set_quality(80);

// Small thumbnail
$img->set_size(200);
$img->save_img("C:\wamp\www\abazaria/upload/pecas/small_000026_0.jpg");

// Baby thumbnail
//$img->set_size(50);
//$img->save_img("baby_" . $src);

// Finalize
$img->clear_cache();
