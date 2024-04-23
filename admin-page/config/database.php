<?php

if (!defined('DB_SERVER')) {
    define('DB_SERVER', 'samryvn.com');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'samryvnc_admin');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', 'Hoangquoc@318');
}

if (!defined('DB_DATABASE')) {
    define('DB_DATABASE', 'samryvnc_samryshell');
}
if (!defined('_WEB_HOST')) {
    define('_WEB_HOST', 'http://'.$_SERVER['HTTP_HOST'].'/');
}

$conn = mysqli_connect(DB_SERVER, DB_USERNAME ,DB_PASSWORD, DB_DATABASE);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!function_exists('num_rowsCount_item')) {
    function num_rowsCount_item($sql, $conn) {
        $query = $conn->query($sql);
        if ($query !== false) {
            $count = $query->num_rows;
            return $count;
        } else {
            return 0;
        }
    }
}
?>