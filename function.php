<?php
// Database connection
require_once('db.php');

// Function to execute a generic select query
function selectFromTable($tableName, $columns, $whereConditions) {
    global $db;
    try {
        $sql = "SELECT " . implode(', ', $columns) . " FROM $tableName";
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(function ($col, $val) {
                if (is_array($val)) {
                    return "$col IN (:" . implode(", :", array_keys($val)) . ")";
                } else {
                    return "$col = :$col";
                }
            }, array_keys($whereConditions), $whereConditions));
        }
        $stmt = $db->prepare($sql);
        foreach ($whereConditions as $col => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $val) {
                    $stmt->bindValue(":$key", $val);
                }
            } else {
                $stmt->bindValue(":$col", $value);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

// Function to insert data into any table
function insertIntoTable($tableName, $data) {
    global $db;
    $columns = implode(", ", array_keys($data));
    $values = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
    try {
        $stmt = $db->prepare($sql);
        foreach ($data as $key => &$val) {
            $stmt->bindParam(":$key", $val);
        }
        $stmt->execute();
        return $db->lastInsertId();
    } catch (PDOException $e) {
       return false;
    }
}

// Function to delete data from any table
function deleteFromTable($tableName, $whereConditions) {
    global $db;
    try {
        $sql = "DELETE FROM $tableName";
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(function ($col) {
                return "$col = :$col";
            }, array_keys($whereConditions)));
        }
        $stmt = $db->prepare($sql);
        foreach ($whereConditions as $col => $value) {
            $stmt->bindValue(":$col", $value);
        }
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to update data from any table
function updateTable($tableName, $data, $conditions) {
    global $db;
    $updates = [];
    foreach ($data as $key => $value) {
        $updates[] = "$key = :$key";
    }
    $conditionString = implode(' AND ', array_map(function ($key) {
        return "$key = :$key";
    }, array_keys($conditions)));

    $sql = "UPDATE $tableName SET " . implode(', ', $updates) . " WHERE $conditionString";
    $stmt = $db->prepare($sql);
    foreach ($data as $key => &$value) {
        $stmt->bindParam(":$key", $value);
    }
    foreach ($conditions as $key => &$value) {
        $stmt->bindParam(":$key", $value);
    }
    return $stmt->execute();
}

function ordinal($number) {
    $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

// AJAX response function
function ajaxResponse($success, $data = [], $message = '') {
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
    exit;
}

// Add this function to handle complex queries
function executeQuery($sql, $params = []) {
    global $db;
    try {
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}
?>