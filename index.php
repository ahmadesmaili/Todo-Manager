<?php
include "bootstrap/init.php";

if (isset($_GET['delete_folder']) && is_numeric($_GET['delete_folder'])) {
    $deletedCount = deleteFolder($_GET['delete_folder']);
//    echo $_GET['folder_name'] . '  was deleted';
}
if (isset($_GET['delete_task']) && is_numeric($_GET['delete_task'])) {
    $deletedCount = deleteTask($_GET['delete_task']);
//    echo $_GET['task_name'] . '  was deleted';
}

$folders = getFolders();

$tasks = getTasks();

include "tpl/tpl-index.php";