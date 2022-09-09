<?php
/*
 |-------------------------------------------------------------
 | Create Thumbnails
 |-------------------------------------------------------------
 | Default resolution for a preview image is 300px (max-width)
 | ** Not yet implemented **
 |-------------------------------------------------------------
*/
function make_thumbnail($image_path, $thumbnail_path, $resolution) {
    try {
        $thumbnail = new Imagick($image_path);
        $thumbnail->resizeImage($resolution,null,null,1,null);
        $thumbnail->writeImage($thumbnail_path);

        return "success";
    } catch (Exception $e) {
        return $e;
    }
}