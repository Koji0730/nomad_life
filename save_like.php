<?php
include("funcs.php");
$pdo = db_conn();
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $input = json_decode(file_get_contents('php://input'), true);
    $photoId = $input['photoId'];

    $stmt = $pdo->prepare('INSERT INTO likes (photo_id) VALUES (:photo_id)');
    $stmt->execute(['photo_id' => $photoId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>