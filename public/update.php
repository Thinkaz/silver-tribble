<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(0);

ob_start();

$token = 'XTrq2dqqq7CL5N7f6sQa';

if ($_GET['token'] !== $token) {
    http_response_code(401);
    die();
}

$addonId = "03fbca0c-67b5-4932-91c9-d07c1b9492f4";
$version = $_GET['version'];
$step = $_GET['step'] ?? null;
$downloadPath = __DIR__ . "/../update-$version.zip";
$extracted = __DIR__ . "/../update-$version/";

$requestScheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';

$url = $requestScheme . '://' . $_SERVER['HTTP_HOST'] . '/update.php?token=' . $_GET['token'] . '&version=' . $version . '&step=';

$env = file_get_contents(__DIR__ . '/../.env');
preg_match_all('/GMS_API="(.+)"/m', $env, $matches, PREG_SET_ORDER);

$gmsToken = null;
if (count($matches) === 1) {
    $gmsToken = $matches[0][1];
}

if (!$gmsToken) {
    http_response_code(400);
    die('Missing gmodstore token. Learn more here https://docs.tbdscripts.com/docs/faq-and-troubleshooting/updating');
}

if (empty($step)) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.gmodstore.com/api/v3/products/$addonId/versions/$version/download",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $gmsToken",
        ],
    ]);

    $download = curl_exec($curl);
    $err = curl_error($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($err) {
        die("cURL Error #:" . $err);
    }

    if ($statusCode !== 200) {
        die("GMS download failed, status code: $statusCode");
    }

    $download = json_decode($download, true, 512, JSON_THROW_ON_ERROR);

    $ch = curl_init();

    $downloadWrite = fopen($downloadPath, 'wb');
    curl_setopt_array($ch, [
        CURLOPT_URL => $download['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0) Gecko/20100101 Firefox/91.0',
    ]);

    $downloadData = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    if ($err) {
        die("cURL Error #:" . $err);
    }

    fwrite($downloadWrite, $downloadData);
    fclose($downloadWrite);

    header('Location: ' . $url . 'unzip');
}

if ($step === 'unzip') {
    $zip = new ZipArchive();
    $res = $zip->open($downloadPath, ZipArchive::RDONLY);

    if ($res !== true) {
        throw new Exception('failed, code:' . $res);
    }

    $zip->extractTo($extracted);
    $zip->close();

    unlink($downloadPath);

    header('Location: ' . $url . 'finish');
}

if ($step === 'finish') {
    $web = "{$extracted}cosmo_web";
    $dir = __DIR__ . "/../";

    if (function_exists('exec')) {
        exec("cp -rf $web/* $dir");
        exec("rm -R $extracted");
    } else {
        function recurse_copy($src, $dst)
        {
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src . '/' . $file)) {
                        recurse_copy($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        }

        function rrmdir($dir)
        {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                            rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                        else
                            @unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
                }
                @rmdir($dir);
            }
        }

        recurse_copy($web, $dir);
        rrmdir($extracted);
    }

    echo 'All updated, thank you for using cosmo :)';
}

ob_end_flush();
