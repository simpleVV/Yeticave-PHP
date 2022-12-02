<?php
require_once('helpers.php');

/**
 * Формирует sql запрос для получения списка категорий
 *
 * @param object $connect mysqli Ресурс соединения
 * @return array $categories - массив категорий
 */
function get_categories(object $connect)
{
    $sql = "SELECT category_name as name, character_code as code,
                   category.id as id
              FROM category";
    $sql_result = mysqli_query($connect, $sql);
    $categories = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $categories;
};

/**
 * Формирует sql запрос для получения списка лотов
 *
 * @param object $connect mysqli Ресурс соединения
 * @param int $lots_number - количество запрашиваемых лотов
 * @return array $lots - массив лотов
 */
function get_lots(object $connect, int $lots_number)
{
    $sql_lots = "SELECT lots.id, title, image AS img, start_price AS price,
                        date_end AS duration_time, category_name AS category
                   FROM lots
                   JOIN category ON lots.category_id = category.id
                  WHERE date_end > now()
               ORDER BY date_creation DESC
                  LIMIT $lots_number";
    $sql_result = mysqli_query($connect, $sql_lots);
    $lots = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $lots;
};

/**
 * Формирует sql запрос для получения лота по указанному идентификатору
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $id идентификатор запрашиваемого лота
 * @return array $lot_data - массив данных лота
 */
function get_lot(object $connect, string $id)
{
    $sql_lot = "SELECT lots.id, title, description, image AS img,
                       category_name AS category, bet_step, user_id
                  FROM lots
                  JOIN category ON lots.category_id = category.id
                 WHERE lots.id = $id";
    $sql_result = mysqli_query($connect, $sql_lot);
    $lot_data = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $lot_data;
};

/**
 * Формирует sql запрос для получения списка лотов с помощью поиска
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $search_option - строка запроса (что ищем)
 * @param int $lots_number - количество запрашиваемых лотов
 * @param int|float $offset - смещение
 * @return array $lots - массив лотов
 */
function find_lots(
    object $connect,
    string $search_option,
    int $lots_number,
    int|float $offset
) {
    $sql_data = [$search_option, $lots_number, $offset];
    $sql_search = "SELECT lots.id, title, image AS img, start_price AS price,
                          date_end AS duration_time, category_name AS category
                     FROM lots
                     JOIN category ON lots.category_id = category.id
                    WHERE MATCH(title, description) AGAINST(?)
                          AND date_end > now()
                 ORDER BY date_creation DESC
                    LIMIT ? OFFSET ?";

    $stmt = db_get_prepare_stmt($connect, $sql_search, $sql_data);

    mysqli_stmt_execute($stmt);

    $sql_result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $lots;
}

/**
 * Формирует sql запрос для получения списка лотов по категории
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $filter - строка по которой идет фильтрация лотов
 * @param int $lots_number - количество запрашиваемых лотов
 * @param int|float $offset - смещение
 * @return array $lots - массив лотов
 */
function filter_lots(
    object $connect,
    string $filter,
    int $lots_number,
    int|float $offset
) {
    $sql_data = [$filter, $lots_number, $offset];
    $sql_search = "SELECT lots.id, title, image AS img, start_price AS price,
                          date_end AS duration_time, category_name AS category
                     FROM lots
                     JOIN category ON lots.category_id = category.id
                    WHERE category_name = ? AND date_end > now()
                 ORDER BY date_creation DESC
                    LIMIT ? OFFSET ?";
    $stmt = db_get_prepare_stmt($connect, $sql_search, $sql_data);

    mysqli_stmt_execute($stmt);

    $sql_result = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $lots;
}

/**
 * Формирует sql запрос для получения количества лотов подходящих под параметры
 * поиска
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $search_option - строка запроса (что ищем)
 * @return string $lots_number - строка. Количество лотов
 */
function get_search_lot_num(object $connect, string $search_option)
{
    $sql_data = [$search_option];
    $sql = "SELECT COUNT(*) as count FROM lots
             WHERE MATCH(title, description) AGAINST(?)";
    $stmt = db_get_prepare_stmt($connect, $sql, $sql_data);

    mysqli_stmt_execute($stmt);

    $sql_result = mysqli_stmt_get_result($stmt);
    $lots_number = mysqli_fetch_assoc($sql_result)['count'];

    return $lots_number;
}

/**
 * Формирует sql запрос для получения количества лотов определенной категории
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $filter - строка запроса (что ищем)
 * @return string $lots_number - строка. Количество лотов
 */
function get_filter_lot_num(object $connect, string $filter)
{
    $sql_data = [$filter];
    $sql = "SELECT COUNT(*) as count FROM lots
              JOIN category ON lots.category_id = category.id
             WHERE category_name = ? ";
    $stmt = db_get_prepare_stmt($connect, $sql, $sql_data);

    mysqli_stmt_execute($stmt);

    $sql_result = mysqli_stmt_get_result($stmt);
    $lots_number = mysqli_fetch_assoc($sql_result)['count'];

    return $lots_number;
}

/**
 * Формирует sql Запрос для получения всех ставок текущего лота
 *
 * @param object $connect mysqli Ресурс соединения
 * @param $lot_id - идентификатор текущего лота
 * @return array $bets - массив. Все сделанные ставки текущего лота
 */
function get_lot_bets(object $connect, string $lot_id)
{
    $sql_bets = "SELECT bet_date as time, price, user_name
                   FROM bets
                   JOIN users ON bets.user_id = users.id
                  WHERE lot_id = $lot_id
               ORDER BY bet_date DESC";
    $sql_result = mysqli_query($connect, $sql_bets);
    $bets = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $bets;
}

/**
 * Формирует sql Запрос для получения стартовой цены лота и цены лота с шагом
 * ставки
 * @param object $connect mysqli Ресурс соединения
 * @param $lot_id - идентификатор текущего лота
 * @return array $lot_price - массив. Стартовой цены лота и цены лота с шагом
 * ставки
 */
function get_lot_price(object $connect, string $lot_id)
{
    $sql_lot = "SELECT start_price AS price, date_end AS duration_time,
                       bet_step, start_price + bet_step AS start_bet, user_id
                  FROM lots
                 WHERE lots.id = $lot_id";
    $sql_result = mysqli_query($connect, $sql_lot);
    $lot_price = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $lot_price;
}

/**
 * Формирует подготовленный sql запрос на добавление нового лота
 *
 * @param object $connect mysqli Ресурс соединения
 * @param array $lot_data массив данных добавляемого лота
 * @return bool true если запрос выполнен успешно
 */
function create_lot(object $connect, array $lot_data)
{
    $sql_lot = "INSERT INTO lots (date_creation, title, description,
                            start_price, date_end, bet_step, category_id, image, user_id, winner_id)
                     VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($connect, $sql_lot, $lot_data);
    $sql_result = mysqli_stmt_execute($stmt);

    return $sql_result;
}

/**
 * Формирует подготовленный sql запрос на добавление нового пользователя
 *
 * @param object $connect mysqli Ресурс соединения
 * @param array $user_data массив данных добавляемого пользователя
 * @return bool true если запрос выполнен успешно
 */
function create_user(object $connect, array $user_data)
{
    $sql_user = "INSERT INTO users (date_registration, email, password,
                             user_name, contacts)
                       VALUE (NOW(), ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($connect, $sql_user, $user_data);
    $sql_result = mysqli_stmt_execute($stmt);

    return $sql_result;
}

/**
 * Формирует подготовленный sql запрос на добавление новой ставки
 *
 * @param object $connect mysqli Ресурс соединения
 * @param array $bet_data массив данных добавляемой ставки
 * @return bool true если запрос выполнен успешно
 */
function create_bet(object $connect, array $bet_data)
{
    $sql_bet = "INSERT INTO bets (bet_date, price, user_id, lot_id)
                      VALUE (NOW(), ?, ?, ?)";
    $stmt = db_get_prepare_stmt($connect, $sql_bet, $bet_data);
    $sql_result = mysqli_stmt_execute($stmt);

    return $sql_result;
}

/**
 * Формирует подготовленный sql запрос для проверки наличия переданного email в
 * базе данных
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $email email пользователя в виде строкия
 * @return bool true - если такой email найден в бд, иначе false
 */
function check_mail_in_bd(object $connect, string $email)
{
    $sql_email = mysqli_real_escape_string($connect, $email);
    $sql = "SELECT id FROM users WHERE email = '$sql_email'";
    $sql_result = mysqli_query($connect, $sql);

    return mysqli_num_rows($sql_result) > 0 ? true : false;
}

/**
 * Формирует подготовленный sql запрос для получения данных пользователя из бд
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $email email пользователя в виде строкия
 * @return object массив с данными пользователя
 */
function get_user(object $connect, string $email)
{
    $user = [];
    $sql_email = mysqli_real_escape_string($connect, $email);
    $sql = "SELECT users.id as id, user_name as name, password
              FROM users
             WHERE email = '$sql_email'";
    $sql_result = mysqli_query($connect, $sql);
    $user_data = $sql_result ? mysqli_fetch_all($sql_result, MYSQLI_ASSOC) : null;

    if ($user_data) {
        [$user] = $user_data;
    }

    return $user;
}

/**
 * Формирует sql Запрос для получения всех ставок текущего пользователя
 *
 * @param object $connect mysqli Ресурс соединения
 * @param $user_id - идентификатор текущего пользователя
 * @return array $bets - массив. Все сделанные ставки текущего пользователя
 */
function get_user_bets(object $connect, string $user_id)
{
    $sql_bets = "SELECT MAX(bet_date) as time, MAX(price) as price, title,
                        image as image, lots.id as lot_id, winner_id,
                        category_name as category, MAX(lots.date_end) as
                        duration_time,
                        (SELECT contacts FROM users WHERE lots.user_id = users.id) as contacts
                   FROM bets
                   JOIN users ON bets.user_id = users.id
                   JOIN lots ON bets.lot_id = lots.id
                   JOIN category ON lots.category_id = category.id
                  WHERE bets.user_id = $user_id
               GROUP BY lot_id
               ORDER BY time DESC;";

    $sql_result = mysqli_query($connect, $sql_bets);
    $bets = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $bets;
}

/**
 * Формирует sql запрос для получения максимальной цены ставки
 *
 * @param object $connect mysqli Ресурс соединения
 * @param $lot_id - идентификатор текущего лота
 * @return string $bet['max_price'] - строка. Максимальная цена ставка
 */
function get_last_bet_price(object $connect, string $lot_id)
{
    $sql_bet_price = "SELECT MAX(price) as max_price
                        FROM bets
                       WHERE lot_id = $lot_id";
    $sql_result = mysqli_query($connect, $sql_bet_price);
    [$bet] = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);

    return $bet['max_price'];
}

/**
 * Формирует подготовленный sql запрос на обновления победителя указанного лота
 *
 * @param object $connect mysqli Ресурс соединения
 * @param string $lot_id строка, идентификатор лота
 * @param string $user_id строка идентификатор пользователя
 * @return bool true если запрос выполнен успешно
 */
function update_lot_winner(object $connect, string $lot_id, string $user_id)
{
    $sql_lot = "UPDATE lots SET winner_id = $user_id
                 WHERE lots.id = $lot_id";
    $sql_result = mysqli_query($connect, $sql_lot);

    return $sql_result;
}

/**
 * Формирует sql запрос для получения массива победителей
 *
 * @param object $connect mysqli Ресурс соединения
 * @return array|bool массив победителей в торгах если запрос выполнен успешно
 * иначе false
 */
function get_winners(object $connect)
{
    $sql_win = "SELECT lots.id as id, title, email, user_name as name FROM lots
                  JOIN users ON lots.winner_id = users.id
                 WHERE user_id != winner_id  AND date_end <= now()";

    $sql_result = mysqli_query($connect, $sql_win);

    if ($sql_result && mysqli_num_rows($sql_result)) {
        $winners = mysqli_fetch_all($sql_result, MYSQLI_ASSOC);
        return $winners;
    }

    return false;
}
