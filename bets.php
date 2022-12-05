<?php

require_once('init.php');
require_once('helpers.php');
require_once('models.php');

$categories = get_categories($link);
$fin_bets = [];

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

if ($user_id) {
    $bets = get_user_bets($link, $user_id);
    $fin_bets = format_data($bets);
} else {
    $error_content = include_template('403.php', [
        'navigation' => $navigation,
        'description' => ERRORS_DESCRIPTION['not_auth_user']
    ]);
}

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

$main_content = $error_content
    ? $error_content
    : include_template('my-bets.php', [
        'navigation' => $navigation,
        'bets' => $fin_bets,
        'owner' => $user_id
    ]);

$layout_content = include_template('layout.php', [
    'title' => 'Мои ставки',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
