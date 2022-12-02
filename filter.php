<?php

require_once('init.php');
require_once('helpers.php');
require_once('models.php');

$lots_filter = filter_input(INPUT_GET, 'filter', FILTER_DEFAULT);
$categories = get_categories($link);
$lots = filter_lots($link, $lots_filter, SEARCH_PAGE_ITEMS, $offset);
$final_lots = format_data($lots);
$lots_number = get_filter_lot_num($link, $lots_filter);
$link_path = "filter.php?filter=$lots_filter";
$pagination_data = get_pagination_data($current_page, $lots_number, $link_path);

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

$pagination = include_template('pagination-list.php', $pagination_data);

$main_content = include_template('all-lots.php', [
    'navigation' => $navigation,
    'category' => $lots_filter,
    'lots' => $final_lots,
    'lots_num' => $lots_number,
    'pagination' => $pagination
]);

$layout_content = include_template('layout.php', [
    'title' => 'Все лоты',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
