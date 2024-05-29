<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "rensdb";


    // Create a new PDO instance
    $conn = new PDO("mysql:host=$servername", $username, $password);
    
    //  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL query to create database
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $database";
    
    // Execute database creation query
    $conn->exec($sql_create_db);

    // Select the database
    $conn->exec("USE $database");

    // SQL query to create table
    $sql_create_table = "CREATE TABLE IF NOT EXISTS voters (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        officer VARCHAR(50) NOT NULL
    )";
    
    // Execute table creation query
    $conn->exec($sql_create_table);
    

?>
