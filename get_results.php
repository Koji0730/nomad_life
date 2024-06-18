<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("funcs.php");
$pdo = db_conn();

// Fetch the last 10 votes
$sql = "SELECT photo_id, vote FROM likes ORDER BY id DESC LIMIT 10";
$stmt = $pdo->query($sql);
$votes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filter to get only "Like" results
$likePhotoIds = [];
foreach ($votes as $vote) {
        if ($vote['vote'] === 'like') {
        $likePhotoIds[] = $vote['photo_id'];
        }
}

if (count($likePhotoIds) > 0) {
    $placeholders = implode(',', array_fill(0, count($likePhotoIds), '?'));
    $sql = "SELECT id, url, location, latitude, longitude FROM photos WHERE id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($likePhotoIds);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}

echo json_encode($results);
?>