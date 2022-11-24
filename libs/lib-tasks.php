<?php
defined('BASE_PATH') or die("Permision Denied");

#----- Folder Functions-------
function deleteFolder($folder_id): int
{
    global $pdo;
    $sql = "delete from folders where id=$folder_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

function isFolder($folder_name): int
{
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "SELECT COUNT(name) as cont FROM Folders WHERE name=:folder_name and user_id=:user_id ;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":folder_name" => $folder_name, ":user_id" => $current_user_id]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    return $result->cont;
}

function addFolder($folder_name): object|array
{
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "insert into Folders  (name,user_id) values (:folder_name,:user_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":folder_name" => $folder_name, ":user_id" => $current_user_id]);
    $id = $pdo->lastInsertId();

    $sql2 = "select * from folders where id=:id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute(["id" => $id]);
    return $stmt2->fetch(PDO::FETCH_OBJ);

}


function getFolders(): object|array
{
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "select * from folders where user_id=$current_user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

#----- Task Functions-------

function deleteTask($task_id): int
{
    global $pdo;
    $sql = "delete from tasks where id=$task_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

function addTask($task_title, $folder_id): int
{
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "insert into tasks  (title,folder_id,user_id) values (:task_title,:folder_id,:user_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":task_title" => $task_title, ":folder_id" => $folder_id, ":user_id" => $current_user_id]);
    return $stmt->rowCount();
}

function getTasks(): object|array
{
    global $pdo;
    $folder_id = $_GET['folder_id'] ?? null;
    $folder_condition = '';
    if (isset($folder_id) && is_numeric($folder_id)) {
        $folder_condition = "and folder_id=$folder_id";
    }
    $current_user_id = getCurrentUserId();
    $sql = "select * from tasks where user_id=$current_user_id $folder_condition";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

#----- Done Switch-------

function doneSwitch($task_id): int
{
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "UPDATE tasks SET is_done = 1-is_done WHERE user_id=:userId and id = :task_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':task_id' => $task_id, ':userId' => $current_user_id]);
    return $stmt->rowCount();
}