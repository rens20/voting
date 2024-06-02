<?php












function resizeImage($file, $w, $h, $path) {
    list($width, $height) = getimagesize($file);
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($w, $h);

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
    imagejpeg($dst, $path, 90);

    return $path;
}
?>
