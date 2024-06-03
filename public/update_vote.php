<?php
session_start();
require_once('connection.php');
// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $voted_officers = $_POST;
    try {
        $conn->beginTransaction();
        $stmt_update = $conn->prepare("UPDATE voters SET vote_counter = vote_counter + 1 WHERE id = :id");
        foreach ($voted_officers as $officer => $candidate_id) {
            $stmt_update->bindParam(':id', $candidate_id, PDO::PARAM_INT);
            $stmt_update->execute();
        }
        $stmt_delete = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt_delete->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt_delete->execute();
        $conn->commit();
        echo json_encode(['success' => 'Votes submitted successfully and user deleted']);
    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to submit votes: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); 
    echo json_encode(['error' => 'Method not allowed']);
}
?>
