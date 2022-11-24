<?php

include_once "../bootstrap/init.php";
if (!isAjaxRequest()) {
    diePage("Invalid Request");
}

if (!isset($_POST['action']) || empty($_POST['action'])) {
    diePage("Invalid Action");
}

switch ($_POST['action']) {
    case "addFolder":
        if (!isset($_POST['folderName']) || strlen($_POST['folderName']) < 3) {
            $msg = (object)["error" => "اسم فایل باید بزرگتر از 2 حرف باشد"];
            echo json_encode($msg);
            die();
        }

        if (isFolder($_POST['folderName']) > 0) {
            $msg = (object)["error" => "نام پوشه تکراری است"];
            echo json_encode($msg);
            die();
        }
        $result = json_encode(addFolder($_POST['folderName']));
        echo $result;
        break;

    case "addTask":
        $task_title = $_POST['taskTitle'];
        $folder_id = $_POST['folderId'];

        if (!isset($folder_id) || empty($folder_id)) {
            echo "ابندا پوشه مورد نظر را انتخاب کنید";
            die();
        }

        if (!isset($task_title) || strlen($task_title) < 3) {
            echo "عنوان تسک باید بزرگتر از 2 حرف باشد";
            die();
        }

        echo addTask($task_title, $folder_id);
        break;

    case "doneSwitch":
        $task_id = $_POST['taskId'];

        if (!isset($task_id) || !is_numeric($task_id)) {
            echo "آیدی تسک معتبر نمی باشد";
            die();
        }

        doneSwitch($task_id);
        break;
    default:
        diePage("Invalid Action");
}
