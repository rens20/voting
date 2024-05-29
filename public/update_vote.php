<?php
session_start();
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voter_id = $_POST['voter_id'];

    try {
        // Update the vote counter for the selected candidate
        $stmt = $conn->prepare("UPDATE voters SET vote_counter = vote_counter + 1 WHERE id = :id");
        $stmt->bindParam(':id', $voter_id);
        $stmt->execute();

        echo "Vote cast successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}