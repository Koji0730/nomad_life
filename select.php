<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXfmxHI2P_icXXsrbLZXz6pfQfHN92vDM&libraries=places"></script>    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="container">
        <div class="photo-container" id="photo-container"></div>
        <div class="buttons">
            <button id="not-like" class="btn">Like</button>
            <button id="like" class="btn">Not Like</button>
            <button id="show-route" class="btn">ルート表示</button>
        </div>
    </div>

    <script src="./script.js"></script>
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start(); // 出力バッファリングを開始

include("funcs.php");
$pdo = db_conn();


?>
