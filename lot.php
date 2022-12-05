<?php
require_once('init.php');
require_once('helpers.php');
require_once('models.php');
require_once('state.php');

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$categories = get_categories($link);
$lot_data = [];
$is_state_on = true;
$final_lot_bets = [];

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

if ($lot_id) {
    $lot_data = get_lot($link, $lot_id);
} else {
    $error_content = include_template('404.php', [
        'navigation' => $navigation
    ]);
}

if ($lot_data) {
    [$lot] = $lot_data;
    $lot_bets = get_lot_bets($link, $lot_id);
    $final_lot_bets = format_data($lot_bets);
    $last_bet = current($final_lot_bets);

    if (
        !$is_auth
        or $lot['user_id'] == $user_id
        or is_time_up($remain_time)
        or isset($last_bet['user_name']) and $last_bet['user_name'] == $user_name
    ) {
        $is_state_on = false;
    }
} else {
    $error_content = include_template('404.php', [
        'navigation' => $navigation
    ]);
}

$bet_history = empty($final_lot_bets)
    ? ''
    : include_template('bet-history.php', [
        'bets' => $final_lot_bets
    ]);

$main_content = $error_content
    ? $error_content
    : include_template('lot-page.php', [
        'lot' => $lot,
        'navigation' => $navigation,
        'is_state_on' => $is_state_on,
        'state' => $state,
        'history' => $bet_history,
        'is_auth' => $is_auth
    ]);

$layout_content = include_template('layout.php', [
    'title' => isset($lot['title']) ? $lot['title'] : '404',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
