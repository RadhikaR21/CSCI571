<?php
//
//$http_origin = $_SERVER['HTTP_ORIGIN'];
//
header('Access-Control-Allow-Origin:*');
$access_token='EAADpMJeeTo8BAE87CHIEieqY0dHDZAVSBZCoY65pxyNA5HvnWHMbhKpbccDQfrJH2EXZAkk8bWqMrcUx6RrT2AGCZAqQjYm35ZBfZBicTVH8VKj4YrAZAAPjwBIj7vQBK8T2a0kRneAXB1XV4Ldbz85w0e4WOZBRDj4ZD';

if($_GET['type'] == "user")
{  
    
     $file=file_get_contents("https://graph.facebook.com/v2.8/search?q=".$_GET['q']."&type=user&fields=id,name,picture.width(700).height(700)&access_token=".$access_token);
 echo $file;
}

if($_GET['type'] == "page")
{   
    $file=file_get_contents("https://graph.facebook.com/v2.8/search?q=".$_GET['q']."&type=page&fields=id,name,picture.width(700).height(700)&access_token=".$access_token);
    echo $file;
}

if($_GET['type'] == "event")
{   
    $file=file_get_contents("https://graph.facebook.com/v2.8/search?q=".$_GET['q']."&type=event&fields=id,name,picture.width(700).height(700)&access_token=".$access_token);
    echo $file;
}

if($_GET['type'] == "place")
{   
    $file=file_get_contents("https://graph.facebook.com/v2.8/search?q=".$_GET['q']."&type=place&fields=id,name,picture.width(700).height(700)&access_token=".$access_token);
    echo $file;
}

if($_GET['type'] == "group")
{   
    $file=file_get_contents("https://graph.facebook.com/v2.8/search?q=".$_GET['q']."&type=group&fields=id,name,picture.width(700).height(700)&access_token=".$access_token);
    echo $file;
}

//userdetails page

if($_GET['type']=="user2")
{
$file=file_get_contents("https://graph.facebook.com/v2.8/".$_GET['userid']."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){id,name,images}},posts.limit(5)&access_token=".$access_token);
echo $file;
}

//pagedetails page
if($_GET['type']=="page2")
{
$file=file_get_contents("https://graph.facebook.com/v2.8/".$_GET['userid']."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){id,name,images}},posts.limit(5)&access_token=".$access_token);
echo $file;
}

//eventdetails page
if($_GET['type']=="event2")
{
$file=file_get_contents("https://graph.facebook.com/v2.8/".$_GET['userid']."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){id,name,images}},posts.limit(5)&access_token=".$access_token);
echo $file;
}

//placedetails page
if($_GET['type']=="place2")
{
$file=file_get_contents("https://graph.facebook.com/v2.8/".$_GET['userid']."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){id,name,images}},posts.limit(5)&access_token=".$access_token);
echo $file;
}

//groupdetails page
if($_GET['type']=="group2")
{
$file=file_get_contents("https://graph.facebook.com/v2.8/".$_GET['userid']."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){id,name,images}},posts.limit(5)&access_token=".$access_token);
echo $file;
}

if($_GET['type']=="favo2")
{
$file=file_get_contents("https://graph.facebook.com/v2.8/".$_GET['userid']."?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){id,name,images}},posts.limit(5)&access_token=".$access_token);
echo $file;
}

//pagination
if($_GET['type'] == "next")
{  
    
     $file=file_get_contents("https://graph.facebook.com/v2.8/search?q=".$_GET['q']."&type=user&fields=id,name,picture.width(700).height(700)&access_token=".$access_token);
 echo $file;
}

//fb post
if($_GET['type']=="fb")
{  
    
     $file=file_get_contents("https://graph.facebook.com/v2.8/".$_GET['userid']."?fields=id,name,picture.width(700).height(700)&access_token=".$access_token);
 echo $file;
}
?>