<?php

$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$errors = [];
$bet = [];
$lot_price_data = [];

if ($lot_id) {
    $lot_price_data = get_lot_price($link, $lot_id);
}

if ($lot_price_data) {
    [$lot_price] = $lot_price_data;
    $bet_last_price = get_last_bet_price($link, $lot_id);
    $remain_time = get_diff_time($lot_price['duration_time']);
    $price = empty($bet_last_price)
        ? $lot_price['price']
        : $bet_last_price;
    $min_bet = $price + $lot_price['bet_step'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_field = ['cost'];
    $rules = [
        'cost' => function ($value, $price) {
            return validate_cost($value, $price);
        }
    ];

    $bet_value = filter_input(INPUT_POST, 'cost', FILTER_DEFAULT);

    if (isset($rules['cost'])) {
        $rule = $rules['cost'];
        $errors['cost'] = empty($errors['cost'])
            ? $rule($bet_value, $min_bet)
            : $errors['cost'];
    }

    if (empty($bet_value)) {
        $errors['cost'] = EMPTY_FIELD_ERRORS['cost'];
    }

    if (empty($errors['cost'])) {
        $bet['price'] = $bet_value;
        $bet['user_id'] = $user_id;
        $bet['lot_id'] = $lot_id;

        mysqli_query($link, 'START TRANSACTION');

        $sql_res_bet = create_bet($link, $bet);
        $sql_res_lot = update_lot_winner($link, $lot_id, $user_id);

        if ($sql_res_bet && $sql_res_lot) {
            mysqli_query($link, 'COMMIT');
        } else {
            mysqli_query($link, 'ROLLBACK');
        }

        header("Location: lot.php?id=$lot_id");
        exit();
    }
}

$state = empty($lot_price)
    ? ''
    : include_template('lot-state.php', [
        'min_bet' => format_price($min_bet),
        'price' => format_price($price),
        'remain_time' => $remain_time,
        'lot_id' => $lot_id,
        'errors' => $errors
    ]);
