<?php
header('Content-Type: application/json');
include 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
if(!isset($input['id']) || !is_numeric($input['id'])){
    http_response_code(400);
    echo json_encode(['success'=>false,'error'=>'Invalid id']);
    exit;
}

$id = (int)$input['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = :id");
    $stmt->execute([':id'=>$id]);
    echo json_encode(['success'=>true]);
} catch (Exception $e){
    http_response_code(500);
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}
-
