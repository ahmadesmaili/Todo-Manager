<?php
defined('BASE_PATH') or die("Permision Denied");


function getCurrentUrl()
{
    return 1;
}

function dd($value)
{
    echo "<pre style='color: #a30202; position: relative; z-index: 999; background: #fff; padding: 30px; margin: 10px; border-radius: 5px; border-left: 5px solid #a30202;'>";
    var_dump($value);
    echo "</pre>";
}

function isAjaxRequest()
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }
    return false;
}

function diePage($msg)
{
    echo "<div style='color: red;width: 80%;margin: 50px auto;background: #fac0c0;padding: 38px;border-radius: 5px;
font-family: sans-serif;border: 1px solid #bf5353;'>$msg</div>";
    die();
}
