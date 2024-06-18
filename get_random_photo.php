<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("funcs.php");
$pdo = db_conn();

// セッションに保存された取得済みIDのリストを取得
if (!isset($_SESSION['retrieved_ids'])) {
    $_SESSION['retrieved_ids'] = [];
}

// 投票回数をセッションに保存
if (!isset($_SESSION['vote_count'])) {
    $_SESSION['vote_count'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTリクエストの場合、投票結果を保存
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $vote = $data['vote'];

    $stmt = $pdo->prepare("INSERT INTO likes (photo_id, vote) VALUES (?, ?)");
    $stmt->execute([$id, $vote]);

    // 投票回数をインクリメント
    $_SESSION['vote_count']++;

    // 投票回数が10に達した場合、リセット
    if ($_SESSION['vote_count'] >= 10) {
        $_SESSION['retrieved_ids'] = [];
        $_SESSION['vote_count'] = 0;
    }

    echo json_encode(['status' => 'success']);
    exit;
}

// 取得済みIDを元にクエリを生成
$retrieved_ids = $_SESSION['retrieved_ids'];
$id_placeholders = implode(',', array_fill(0, count($retrieved_ids), '?'));

// クエリを作成
$sql = "SELECT id, url, location, latitude, longitude FROM photos";
if (count($retrieved_ids) > 0) {
    $sql .= " WHERE id NOT IN ($id_placeholders)";
}
$sql .= " ORDER BY RAND() LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute($retrieved_ids);

// クエリの実行と結果の取得
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    // 取得したIDをセッションに保存
    $_SESSION['retrieved_ids'][] = $row['id'];
    
    // "uploads" フォルダへのパスを必要に応じて追加
    if (strpos($row['url'], 'uploads/') === false) {
        $row['url'] = 'uploads/' . $row['url'];
    }
    echo json_encode($row);
} else {
    // すべての写真を取得済みの場合、セッションをリセット
    $_SESSION['retrieved_ids'] = [];
    echo json_encode([]);
}
?>