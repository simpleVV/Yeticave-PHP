<?php
session_start();

define('MAIN_PAGE_ITEMS', 6);
define('SEARCH_PAGE_ITEMS', 9);
define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));
define('DEFAULT_CURRENT_PAGE', 1);
define('SEC_IN_DAY', 86400);
define('SEC_IN_HOUR', 3600);
define('SEC_IN_MIN', 60);
define('ERRORS_DESCRIPTION', [
    'auth_user' => 'Авторизованным пользователям доступ к регистрации запрещен.',
    'not_auth_user' => 'Доступ не авторизованным пользователям запрещён.'
]);
define('EMPTY_FIELD_ERRORS', [
    'email' => 'Введите e-mail',
    'password' => 'Введите пароль',
    'name' => 'Введите имя',
    'message' => 'Напишите как с вами связаться',
    'lot-name' => 'Введите наименование лота',
    'category' => 'Выберите категорию',
    'description' => 'Напишите описание лота',
    'lot-rate' => 'Введите начальную цену',
    'lot-step' => 'Введите шаг ставки',
    'lot-date' => 'Введите дату завершения торгов',
    'cost' => 'Введите вашу ставку'
]);

$db = require_once("config/db.php");

date_default_timezone_set("Asia/Vladivostok");

$categories = [];
$lots = [];
$lot = [];
$error_content = '';
$current_page = $_GET['page'] ?? DEFAULT_CURRENT_PAGE;
$offset = ($current_page - DEFAULT_CURRENT_PAGE) * SEARCH_PAGE_ITEMS;

$user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : '';
$user_name = isset($_SESSION['user']) ? $_SESSION['user']['name'] : '';
$is_auth = isset($_SESSION['user']);

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');

if (mysqli_connect_errno()) {
    $error = mysqli_connect_error();
    $error_content = include_template('error.php', ['error' => $error]);
}

if (mysqli_error($link)) {
    try {
        throw new Exception("Error No: $msqli_errno <br> MySQL error message: $mysqli_error($link)");
    } catch (Exception $error) {
        $error_content = include_template('error.php', ['error' => $error]);
    }
}
