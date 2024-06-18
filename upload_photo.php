<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start(); // 出力バッファリングを開始

include("funcs.php");
$pdo = db_conn();

$response = array(); // レスポンスを格納する配列

// Check if required POST data is set
if (!isset($_POST["location"], $_POST['latitude'], $_POST['longitude'], $_FILES['photo'])) {
    $response['error'] = 'Missing required data';
    $response['debug'] = [
        'location' => isset($_POST["location"]),
        'latitude' => isset($_POST['latitude']),
        'longitude' => isset($_POST['longitude']),
        'photo' => isset($_FILES['photo'])
    ];
    ob_end_clean();
    echo json_encode($response);
    exit();
}

$location  = $_POST["location"];
$latitude  = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Photo upload directory
$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    if (!mkdir($target_dir, 0755, true)) {
        $response['error'] = 'Failed to create directory: ' . $target_dir;
        $response['debug'] = error_get_last();
        ob_end_clean();
        echo json_encode($response);
        exit();
    }
}

$target_file = $target_dir . basename($_FILES["photo"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if (isset($_FILES["photo"]["tmp_name"])) {
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $response['error'] = 'File is not an image.';
        ob_end_clean();
        echo json_encode($response);
        exit();
    }
} else {
    $response['error'] = 'No file uploaded or file is too large.';
    ob_end_clean();
    echo json_encode($response);
    exit();
}

// Check file size
if ($_FILES["photo"]["size"] > 5000000) {
    $response['error'] = 'Sorry, your file is too large.';
    ob_end_clean();
    echo json_encode($response);
    exit();
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    $response['error'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
    ob_end_clean();
    echo json_encode($response);
    exit();
}

if ($uploadOk == 0) {
    $response['error'] = 'Sorry, your file was not uploaded.';
    ob_end_clean();
    echo json_encode($response);
    exit();
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare('INSERT INTO photos (url, location, latitude, longitude) VALUES (:url, :location, :latitude, :longitude)');
        try {
            if ($stmt->execute([
                'url' => $target_file,
                'location' => $location,
                'latitude' => $latitude,
                'longitude' => $longitude
            ])) {
                $response['success'] = true;
            } else {
                $response['error'] = 'Failed to save photo details in database.';
            }
        } catch (Exception $e) {
            var_dump($e);
        }
    } else {
        $error = error_get_last();
        $response['error'] = 'Sorry, there was an error uploading your file.';
        $response['debug'] = [
            'tmp_name' => $_FILES["photo"]["tmp_name"],
            'target_file' => $target_file,
            'error' => $_FILES["photo"]["error"],
            'last_error' => $error
        ];
    }
}

ob_end_clean();
echo json_encode($response);
exit();