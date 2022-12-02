<?php
require_once('helpers.php');
require_once('init.php');
require_once('models.php');

//Отправить победителю на email письмо — поздравление с победой.
// require_once('getwinner.php');

$categories = get_categories($link);
$lots = get_lots($link, MAIN_PAGE_ITEMS);
$final_lots = format_data($lots);

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

$main_content = $error_content
    ? $error_content
    : include_template('main-page.php', [
        'lots' => $final_lots,
        'categories' => $categories,
    ]);

$layout_content = include_template('layout.php', [
    'title' => 'YetiCave',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
