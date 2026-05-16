<?php

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    
    // Check if user_id column exists
    $result = $pdo->query("PRAGMA table_info(collections)");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    $hasUserId = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'user_id') {
            $hasUserId = true;
            break;
        }
    }
    
    if (!$hasUserId) {
        $pdo->exec('ALTER TABLE collections ADD COLUMN user_id INTEGER NOT NULL DEFAULT 1 REFERENCES users(id) ON DELETE CASCADE;');
        echo "Column user_id added successfully to collections table.\n";
    } else {
        echo "Column user_id already exists.\n";
    }
    
    // Show all columns
    echo "Collections table columns:\n";
    $result = $pdo->query("PRAGMA table_info(collections)");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['name'] . " (" . $row['type'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}