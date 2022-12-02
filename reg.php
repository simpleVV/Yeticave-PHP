<?php

require_once('init.php');
require_once('models.php');
require_once('helpers.php');

$categories = get_categories($link);
$user_data = [];
$errors = [];

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

if ($is_auth) {
    $error_content = include_template('403.php', [
        'navigation' => $navigation,
        'description' => ERRORS_DESCRIPTION['auth_user']
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_field = ['email', 'password', 'name', 'message'];

    $rules = [
        'email' => function ($value) {
            return validate_email($value);
        }
    ];

    $user_form = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'name' => FILTER_DEFAULT,
        'message' => FILTER_DEFAULT
    ], true);

    foreach ($user_form as $input => $value) {
        if (isset($rules[$input])) {
            $rule = $rules[$input];
            $errors[$input] = $rule($value);
        }

        if (in_array($input, $required_field) && empty($value)) {
            $errors[$input] = EMPTY_FIELD_ERRORS[$input];
        }
    }

    $errors = array_filter($errors);
    $is_mail_exist = check_mail_in_bd($link, $user_form['email']);
    $user_data = $user_form;

    if ($is_mail_exist) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    }

    if (empty($errors)) {
        $pass = password_hash($user_form['password'], PASSWORD_DEFAULT);
        $user_data['password'] = $pass;

        $res = create_user($link, $user_data);

        if ($res && empty($errors)) {
            header('Location: /login.php');
            exit();
        }
    }
}

$main_content = $error_content
    ? $error_content
    : include_template('registration.php', [
        'navigation' => $navigation,
        'user_data' => $user_data,
        'errors' => $errors
    ]);

$layout_content = include_template('layout.php', [
    'title' => 'Регистрация',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
