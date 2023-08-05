<?php

$url = 'http://contentmarketingpro.ru/creation/20-luchshix-foto-sajtov-besplatnyx-izobrazhenij/';

$pathSave = dirname(__FILE__) . '/download';

$external = true;

$html = file_get_contents($url);

preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $html, $images, PREG_SET_ORDER);

$url = parse_url($url);
$pathSave = rtrim($pathSave, '/');

foreach ($images as $image) {
    if (strpos($image[1], 'data:image/') !== false) {
        continue;
    }

    if (substr($image[1], 0, 2) === '//') {
        $image[1] = 'http:' . $image[1];
    }

    $ext = strtolower(substr(strrchr($image[1], '.'), 1));

    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $img = parse_url($image['1']);

        if (is_file($pathSave . $img['path'])) {
            continue;
        }

        $path_img = $pathSave . '/' . dirname($img['path']);

        if (!is_dir($path_img)) {
            mkdir($path_img, 0777, true);
        }

        if (empty($img['host']) && !empty($img['path'])) {
            copy($url['scheme'] . '://' . $url['host'] . $img['path'], $pathSave . $img['path']);
        } else if ($external || ($external == false && $img['host'] == $url['host'])) {
            copy($image[1], $pathSave . $img['path']);
        }
    }
}