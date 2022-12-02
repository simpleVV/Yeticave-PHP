<?php
require_once('init.php');
require_once('helpers.php');
require_once('models.php');

$categories = get_categories($link);
$cats_ids = array_column($categories, 'id');
$errors = [];

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

if (!$is_auth) {
    $error_content = include_template('403.php', [
        'navigation' => $navigation,
        'description' => ERRORS_DESCRIPTION['not_auth_user']
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_field = [
        'lot-name',
        'category',
        'description',
        'lot-rate',
        'lot-step',
        'lot-date'
    ];

    $rules = [
        'category' => function ($value) use ($cats_ids) {
            return validate_category($value, $cats_ids);
        },
        'lot-rate' => function ($value) {
            return validate_num($value, false, true);
        },
        'lot-step' => function ($value) {
            return validate_num($value, true, true);
        },
        'lot-date' => function ($value) {
            return validate_date($value);
        }
    ];

    $lot = filter_input_array(INPUT_POST, [
        'lot-name' => FILTER_DEFAULT,
        'description' => FILTER_DEFAULT,
        'lot-rate' => FILTER_DEFAULT,
        'lot-date' => FILTER_DEFAULT,
        'lot-step' => FILTER_DEFAULT,
        'category' => FILTER_DEFAULT
    ], true);

    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required_field) && empty($value)) {
            $errors[$key] = EMPTY_FIELD_ERRORS[$key];
        }
    }

    $errors = array_filter($errors);

    if (!empty($_FILES['lot-img']['name']) && empty($errors)) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $path = $_FILES['lot-img']['name'];
        $file_name_arr = explode('.', $_FILES['lot-img']['name']);
        $file_exten = array_pop($file_name_arr);
        $filename = uniqid() . ".$file_exten";
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $tmp_name);
        $allowed_type = ['image/png', 'image/jpeg'];

        if (!in_array($file_type, $allowed_type)) {
            $errors['file'] = 'Изображение должно быть в формате jpeg/png';
        } else {
            move_uploaded_file($tmp_name, UPLOAD_PATH . "/$filename");
            $lot['image'] =  UPLOAD_PATH . "/$filename";
        }
    } else {
        $errors['file'] = 'Необходимо загрузить файл';
    }

    if (empty($errors)) {
        $lot['user_id'] = $user_id;
        $lot['winner_id'] = $lot['user_id'];

        $sql_res = create_lot($link, $lot);

        if ($sql_res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?id=$lot_id");
            exit();
        }
    }
}

$main_content = $error_content
    ? $error_content
    : include_template('add-lot.php', [
        'navigation' => $navigation,
        'categories' => $categories,
        'errors' => $errors
    ]);

$layout_content = include_template('layout.php', [
    'title' => 'Добавление лота',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
