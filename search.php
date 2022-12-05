<?php

require_once('init.php');
require_once('helpers.php');
require_once('models.php');

$categories = get_categories($link);

$search = empty($_GET['search']) ? '' : htmlspecialchars(trim($_GET['search']));
$option = $search ?? '';
$final_lots = [];
$lots_number = '';
$pagination_data = [];

if ($search) {
    $lots_number = get_search_lot_num($link, $search);
    $lots = find_lots($link, $search, SEARCH_PAGE_ITEMS, $offset);
    $final_lots = format_data($lots);
    $link_path = "search.php?search=$search";
    $pagination_data = get_pagination_data($current_page, $lots_number, $link_path);
}

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

$pagination = include_template('pagination-list.php', $pagination_data);

$main_content = include_template('search-page.php', [
    'navigation' => $navigation,
    'option' => $option,
    'lots' => $final_lots,
    'lots_num' => $lots_number,
    'pagination' => $pagination
]);

$layout_content = include_template('layout.php', [
    'title' => 'Результаты поиска',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
