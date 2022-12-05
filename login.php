<?php

require('init.php');
require_once('helpers.php');
require_once('models.php');

$categories = get_categories($link);
$errors = [];
$enter_form = [];

$navigation = include_template('navigation.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_field = ['email', 'password'];
    $enter_form = $_POST;

    $rules = [
        'email' => function ($value) {
            return validate_email($value);
        }
    ];

    $enter_form = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT
    ], true);

    foreach ($enter_form as $input => $value) {
        if (isset($rules[$input])) {
            $rule = $rules[$input];
            $errors[$input] = $rule($value);
        }

        if (in_array($input, $required_field) && empty($value)) {
            $errors[$input] = EMPTY_FIELD_ERRORS[$input];
        }
    }

    $errors = array_filter($errors);
    $user = get_user($link, $enter_form['email']);

    if (empty($user)) {
        $errors['login'] = 'Вы ввели неверный email/пароль';
    }

    if (empty($errors) && $user) {
        if (password_verify($enter_form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['login'] = 'Вы ввели неверный email/пароль';
        }
    }

    if (empty($errors)) {
        header('Location: /index.php');
    }
}

if ($is_auth) {
    header('Location: /index.php');
    exit();
}

$main_content = include_template('login-page.php', [
    'navigation' => $navigation,
    'errors' => $errors,
    'form' => $enter_form
]);

$layout_content = include_template('layout.php', [
    'title' => 'Вход',
    'content' => $main_content,
    'navigation' => $navigation,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
