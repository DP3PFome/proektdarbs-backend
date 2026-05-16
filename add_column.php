<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->exec('ALTER TABLE collections ADD COLUMN user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE;');
    echo "Column user_id added successfully to collections table.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}