<?php defined('BASE_PATH') or die("Permision Denied"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/fontawesome/css/brands.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/fontawesome/css/solid.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/fontawesome/css/v4-shims.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">

</head>

<body>

<div class="page">
    <div class="pageHeader">
        <div class="title">Dashboard</div>
        <div class="userPanel"><i class="fa fa-chevron-down"></i><span class="username">Ahmad Esmaili</span><img
                    src="https://s.gravatar.com/avatar/6038b7331c0297c5ae169d69cf218407?s=80" width="40" height="40"/>
        </div>
    </div>
    <div class="main">
        <div class="nav">
            <div class="searchbox">
                <div><i class="fa fa-search"></i>
                    <input type="search" placeholder="Search"/>
                </div>
            </div>
            <div class="menu">
                <div class="title">Folders</div>
                <ul class="folder-list">
                    <li class="<?php echo (!isset($_GET['folder_id'])) ? 'active' : ''; ?>">
                        <a href="/"><i class="fa fa-folder"></i>All</a>
                    </li>
                    <?php foreach ($folders as $folder): ?>
                        <li class="<?php echo (isset($_GET['folder_id']) && $_GET['folder_id'] === $folder->id) ? 'active' : ''; ?>">
                            <a href="?folder_id=<?php echo $folder->id; ?>">
                                <i class="fa fa-folder"></i><?php echo $folder->name; ?>
                            </a>
                            <a href="?delete_folder=<?php echo $folder->id; ?>&folder_name=<?php echo $folder->name; ?>"
                               class="remove" onclick="return confirm('Are you sure to delete this item?')">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <input type="text" id="addFolderInput" name="newFolderInput" style="width: 65%; margin-left: 3%"
                       placeholder="Add New Folder"/>
                <button id="addFolderBtn" class="btn clickable">+</button>
            </div>
        </div>
        <div class="view">
            <div class="viewHeader">
                <div class="title" style="width: 50%;">
                    <input type="text" id="addTaskInput" name="newTaskInput" style="width: 100%; line-height: 30px"
                           placeholder="Add New Task"/>
                </div>
                <div class="functions">
                    <div class="button active">Add New Task</div>
                    <div class="button">Completed</div>
                </div>
            </div>
            <div class="content">
                <div class="list">
                    <div class="title">Today</div>
                    <ul>
                        <?php if (sizeof($tasks) > 0): ?>
                            <?php foreach ($tasks as $task): ?>
                                <li class="<?php echo ($task->is_done) ? 'checked' : ''; ?>">
                                    <i data-taskId="<?php echo $task->id; ?>"
                                       class="isDone clickable fa <?php echo ($task->is_done) ? 'fa-check-square-o' : 'fa-square-o'; ?>"></i>
                                    <span><?php echo $task->title; ?></span>
                                    <div class="info">
                                        <span>Created at <?php echo $task->created_at; ?></span>
                                        <?php if (!isset($_GET['folder_id'])) : ?>
                                            <a href="?delete_task=<?php echo $task->id; ?>&task_name=<?php echo $task->title; ?>"
                                               class="remove"
                                               onclick="return confirm('Are you sure to delete this item?')">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?delete_task=<?php echo $task->id; ?>&task_name=<?php echo $task->title; ?>&folder_id=<?php echo $_GET['folder_id']; ?>"
                                               class="remove"
                                               onclick="return confirm('Are you sure to delete this item?')">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No Task</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='<?php echo BASE_URL; ?>assets/js/jquery-3.6.1.min.js'></script>
<script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
<script>
    $(document).ready(function () {
        $("#addFolderBtn").click(function (e) {
            var input = $("input#addFolderInput");
            $.ajax({
                url: "process/ajax-handler.php",
                method: "post",
                data: {
                    action: "addFolder",
                    folderName: input.val()
                },
                success: function (response) {
                    var responseObj = $.parseJSON(response);
                    if (responseObj['name'] === input.val() && responseObj['error'] !== 0) {
                        $('<li> <a href="?folder_id=' + responseObj['id'] + '"> <i class="fa fa-folder"></i>' + input.val() + '</a> <a href="?delete_folder=' + responseObj['id'] + '&amp;folder_name=' + input.val() + '" class="remove"> <i class="fa fa-trash-o"></i> </a> </li>').appendTo('ul.folder-list');
                    } else {
                        alert(responseObj['error']);
                    }
                }
            });
        })

        $("#addTaskInput").on("keypress", function (e) {
            if (e.which === 13) {
                var input = $("input#addTaskInput");
                $.ajax({
                    url: "process/ajax-handler.php",
                    method: "post",
                    data: {
                        action: "addTask",
                        taskTitle: input.val(),
                        folderId: "<?php echo $_GET['folder_id'] ?? 0;?>"
                    },
                    success: function (response) {
                        if (response === '1') {
                            location.reload();
                        } else {
                            alert(response);
                        }
                    }
                });
            }
        })

        $(".isDone").click(function (e) {
            var tid = $(this).attr('data-taskId');
            $.ajax({
                url: "process/ajax-handler.php",
                method: "post",
                data: {
                    action: "doneSwitch",
                    taskId: tid
                },
                success: function (response) {
                    location.reload();
                }
            });
        })

        $("#addTaskInput").focus();
    })
</script>
</body>

</html>