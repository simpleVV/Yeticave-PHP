USE yeticave;

/*Добавление данных в таблицу category*/
INSERT INTO `category` (category_name, character_code)
     VALUES
      ('Доски и лыжи', 'boards'),
      ('Крепления', 'attachment'),
      ('Ботинки', 'boots'),
      ('Одежда', 'clothing'),
      ('Инструменты', 'tools'),
      ('Разное', 'other');

/*Добавление данных в таблицу users*/
INSERT INTO `users` (date_registration, email, user_name, password, contacts)
     VALUES
      ('2022-05-09 10:12:00', 'super_bob@mail.ru', 'Bob', 'verystrognpass123', 'Приморский край г. Владивосток у. Русская'),
      ('2020-03-07 12:32:04', 'shlang@yandex.ru', 'Василий', 'vasiya1998', 'г. Бобруйск у. Первая');

/*Добавление данных в таблицу lots*/
INSERT INTO lots (date_creation, title, description, image, start_price, date_end, bet_step, user_id, winner_id, category_id)
     VALUES
      (NOW(), '2014 Rossignol District Snowboard', 'Хорошая доска по доступной цене', 'img/lot-1.jpg', 10999, '2022-08-10', 100, 1, 2, 1),
      ('2021-08-08 17:32:21','DC Ply Mens 2016/2017 Snowboard', 'Доска для тех кто шарит', 'img/lot-2.jpg', 159999, '2022-08-12', 1000, 1, 2, 1),
      ('2022-08-09 10:12:00','Крепления Union Contact Pro 2015 года размер L/XL', 'Надежные как китайские часы', 'img/lot-3.jpg', 8000, '2022-08-10', 50, 2, 1, 2),
      ('2022-08-08 22:01:12','Ботинки для сноуборда DC Mutiny Charocal', 'Боты без заботы', 'img/lot-4.jpg', 10999, '2022-08-11', 500, 1, 2, 3),
      (NOW(),'Куртка для сноуборда DC Mutiny Charocal', 'Стильная мужская куртка, 54 размера, для занятия зимним спортом', 'img/lot-5.jpg', 7500, '2022-08-12', 300, 2, 1, 4),
      ('2020-08-08 12:32:04','Маска Oakley Canopy', 'Не бэтмана но тоже крутая', 'img/lot-6.jpg', 5400, '2022-08-14', 500, 2, 1, 6);

/*Добавление данных в таблицу bets*/
INSERT INTO `bets` (bet_date, price, user_id, lot_id)
     VALUES
      (NOW(), 11499, 2, 4),
      ('2022-08-08 22:01:12', 8400, 1, 5);

/*Получить все категории;*/
SELECT category_name FROM category;

/*Получить самые новые, открытые лоты (название, стартовую цену, ссылку на изображение, цену, название категории)*/
SELECT title, start_price, image, category.category_name AS category, date_creation FROM lots
     JOIN category ON lots.category_id = category.id
     ORDER BY date_creation DESC;

/*Показать лот по его ID,а также название категории, к которой принадлежит лот*/
SELECT lots.*, category.category_name AS category FROM lots
     JOIN category ON lots.category_id = category.id
     WHERE lots.id = 1;

/*Обновить название лота по его идентификатору;*/
UPDATE lots SET title = 'DC Ply Mens 2016/2017 Snowboard'
    WHERE lots.id = 2;

/*Получить список ставок для лота по его идентификатору с сортировкой по дате.*/
SELECT * FROM bets
    LEFT JOIN lots ON lot_id = lots.id
    WHERE lots.id = 5
    ORDER BY bet_date ASC;
