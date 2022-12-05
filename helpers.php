<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Проверяет является ли переданное значение числом
 * Если указан параметр $is_num_int, проверяет является ли переданное значение целым числом
 * Если указан параметр $is_num_pos, проверяет является ли переданное значение положительным числом
 *
 * @param mixed $value любое значение
 * @param bool $is_num_int флаг проверки на целое число
 * @param bool $is_num_pos флаг проверки на то что число является положительным
 * @return string|null описание ошибки если переданное значение не проходит
 * проверку или null если значение прошло проверку.
 */
function validate_num(
    mixed $value,
    bool $is_num_int = false,
    bool $is_num_pos = false
) {

    if (!is_numeric($value)) {
        return 'Значение должно быть указана в числовом формате';
    }

    if ($is_num_int && !is_int(+$value)) {
        return 'Значение должно быть целым числом';
    }

    if ($is_num_pos && $value <= 0) {
        return 'Значение должно быть больше 0';
    }

    return null;
}

/**
 * Проверяет ставку на соответствие формата и
 * что указанная ставка больше или равна чем текущая цена лота
 *
 * @param string $value новая ставка лота
 * @param string $lot_price - текущая цена лота
 * @return string|null описание ошибки если значение ставки прошло проверку или * null если значение прошло проверку.
 */
function validate_cost(mixed $value, string $lot_price)
{
    if (validate_num($value, true, true)) {
        return validate_num($value, true, true);
    }

    if ($value < $lot_price) {
        return 'Значение не должно быть меньше, чем текущая цена лота + шаг ставки.';
    }
}

/**
 * Проверяет дату на соответствие формата и
 * что указанная дата больше текущей даты, хотя бы на один день
 *
 * @param string $date Дата в виде строки
 * @return string|null описание ошибки если дата не прошли проверку или null
 * если дата прошла проверку.
 */
function validate_date(string $date)
{
    if (!is_date_valid($date)) {
        return 'Не верно указан формат даты';
    }

    return (strtotime($date) - strtotime('now')) < SEC_IN_DAY
        ? 'Дата завершения аукциона должна быть больше одного дня'
        : null;
}

/**
 * Проверяет наличие выбранной категории в списке категорий
 *
 * @param string $id id выбранной категории в виде строки
 * @param array $allowed_list список существующих категорий в виде массива
 * @return string|null описание ошибки если id категории не найден в списке
 * категорий или null если такая категория найдена
 */
function validate_category(string $id, array $allowed_list)
{
    return (!in_array($id, $allowed_list))
        ? 'Не найдена данная категория'
        : null;
}

/**
 * Проверяет переданное значение на то что оно соответствует целому
 * числовому формату и что оно больше 0
 *
 * @param string $value значение в виде строки
 * @return string|null описание ошибки если переданное значение не является
 * валидным E-mail адресом или null если значение проходит проверку.
 */
function validate_email(string $value)
{
    return (!filter_var($value, FILTER_VALIDATE_EMAIL))
        ? 'Просьба указать существующий email'
        : null;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных * данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа,
 * минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 *
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирует цену.
 *
 * @param int|float $price - цена товара
 * @return string Итоговая цена после форматирования
 */
function format_price(int|float $price)
{
    $final_price = ceil($price);
    $final_price = number_format(ceil($final_price), 0, "", " ");

    return $final_price;
}

/**
 * Форматирует дату и время ставки. Добавляет форму множественного числа к
 * минутам и часам если ставка была сделана в течении дня
 *
 * @param string $bet_time - время создания ставки
 * @return string Дата и время после форматирования
 */
function format_time(string $bet_time)
{
    $time_diff = time() - strtotime($bet_time);
    $hours = floor($time_diff / SEC_IN_HOUR);
    $min = floor(($time_diff / SEC_IN_MIN) - $hours * SEC_IN_MIN);
    $min_plural_form = get_noun_plural_form($min, 'минута', 'минуты', 'минут');
    $hour_plural_form = get_noun_plural_form($hours, 'час', 'часа', 'часов');

    switch ($time_diff) {
        case ($time_diff < SEC_IN_HOUR):
            return "$min $min_plural_form назад";
        case ($time_diff < SEC_IN_DAY && $time_diff >= SEC_IN_HOUR):
            return "$hours $hour_plural_form  назад";
        case ($time_diff > SEC_IN_DAY):
            return date('d.m.Y в H:i', strtotime($bet_time));
    }
}

/**
 * Возвращает разницу во времени в часах и минутах между заданным временем и
 * настоящим.
 *
 * @param string $end_date - дата наступления события
 * @return array - Массив с двумя значениями времени (часы и минуты) до
 * наступления события
 */
function get_diff_time(string $end_date)
{
    $time_diff = strtotime($end_date) - time();
    $hours = floor($time_diff / SEC_IN_HOUR) < 0
        ? 0
        : floor($time_diff / SEC_IN_HOUR);
    $min = floor(($time_diff / SEC_IN_MIN) - $hours * SEC_IN_MIN) < 0
        ? 0
        : floor(($time_diff / SEC_IN_MIN) - $hours * SEC_IN_MIN);

    $time_array = array_map(function ($time) {
        return str_pad($time, 2, '0', STR_PAD_LEFT);
    }, [$hours, $min]);

    return $time_array;
}

/**
 * Приводит цену, время и время до к соответствующим значениям
 *
 * @param array $data - массив данных (пример: лоты) в исходном состоянии
 * @return array - Массив данных с отформатированным временем и ценой.
 */
function format_data(array $data)
{
    return array_map(function ($item) {
        $item['price'] = format_price($item['price']);

        isset($item['duration_time'])
            ? $item['duration_time'] = get_diff_time($item['duration_time'])
            : '';

        isset($item['time'])
            ? $item['time'] = format_time($item['time'])
            : '';

        return $item;
    }, $data);
}

/**
 * Функция проверяет остался ли час до конца события
 *
 * @param array $time - массив с данных времени (первый индекс массива - часы,  * второй индекс - минуты)
 * @return bool - true - если есть часы в переданном массиве, иначе false
 */
function is_hour_left(array $time)
{
    return (int)$time[0] === 0
        ? false
        : true;
}

/**
 * Функция проверяет вышло ли время до события
 *
 * @param array $time - массив с данных времени (первый индекс массива - часы,  * второй индекс - минуты)
 * @return bool - true - если время вышло, иначе false
 */
function is_time_up(array $time)
{
    return (int)$time[0] === 0 && (int)$time[1] === 0
        ? true
        : false;
}

/**
 * Функция проверяет сыграла ставка или нет
 *
 * @param string $user_id -идентификатор текущего пользователя
 * @param string $winner_id - идентификатор победителя на текущий момент
 * @return bool - true - если ставка сыграла, иначе false
 */
function is_bet_win(string $user_id, string $winner_id)
{
    return $user_id == $winner_id
        ? true
        : false;
}

/**
 * Формирует массив данных для шаблона списка пагинации
 *
 * @param int $current_page - номер текущей страницы
 * @param int $item_num - количество лотов на одной странице
 * @param string $path - путь для ссылки(переход на другую страницу пагинации)
 * @return array - массив данных для шаблона списка пагинации
 */
function get_pagination_data(int $current_page, int $item_num, string $path)
{
    $pages_count = ceil($item_num / SEARCH_PAGE_ITEMS);
    $pages = range(DEFAULT_CURRENT_PAGE, $pages_count);
    $next_page = $current_page + 1;
    $prev_page = $current_page - 1;

    return [
        'pages' => $pages,
        'current_page' => $current_page,
        'path' => $path,
        'prev_page' => $prev_page,
        'next_page' => $next_page,
    ];
}

/**
 * Получает значение выбранного поля
 *
 * @param string $name - путь для ссылки(переход на другую страницу пагинации)
 * @return mixed - Значение запрашиваемой переменной в случае успешного
 * выполнения иначе false
 */
function get_post_value(string $name)
{
    return filter_input(INPUT_POST, $name);
}
