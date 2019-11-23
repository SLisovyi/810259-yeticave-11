-- добавим категорию в таблицу categorie
INSERT INTO category
SET name = 'Доски и лыжи', character_code = 'boards';
INSERT INTO category
SET name = 'Крепления', character_code = 'attachment';
INSERT INTO category
SET name = 'Ботинки', character_code = 'boots';
INSERT INTO category
SET name = 'Одежда', character_code = 'clothing';
INSERT INTO category
SET name = 'Инструменты', character_code = 'tools';
INSERT INTO category
SET name = 'Разное', character_code = 'other';

-- добавим нового пользователя в таблицу users
INSERT INTO user
SET 
email = 'stepa@gmail.com', 
date_add = NOW(), 
name = 'Stepa', 
password = 'aGKJFagdjf1', 
contact = 'Moskov';

INSERT INTO user
SET email = 'vova@gmail.com', 
date_add = NOW(), 
name = 'Vova', 
password = 'aGKJFagdjf1', 
contact = 'Piter';

INSERT INTO user
SET email = 'tom@gmail.com', 
date_add = NOW(), 
name = 'Tomas', 
password = 'hrjdfhjhfh556', 
contact = 'Kharkiv';

-- добавим lot в таблицу lot
INSERT INTO lot
SET
date_add = NOW(),
name = '2014 Rossignol District Snowboard',
description = 'Сегодня вы напишите SQL-запросы для выполнения основных действий: вставка данных, чтение и поиск, обновление. В дальнейшем вы задействуете эти запросы в своих PHP-сценариях для интеграции с базой данных. Запросы должны работать с таблицами, которые вы сделали в прошлом задании.',
img_url = 'img/lot-1.jpg',
first_price = 10999,
end_date = '2019-11-14',
bid_step = 200,
user_id = 2,
category_id = 1,
winner_id = 1;

INSERT INTO lot
SET
date_add = NOW(),
name = 'DC Ply Mens 2016/2017 Snowboard',
description = 'Сегодня вы напишите SQL-запросы для выполнения основных действий: вставка данных, чтение и поиск, обновление.',
first_price = 159999,
img_url = 'img/lot-2.jpg',
end_date = '2019-12-18',
bid_step = 500,
user_id = 3,
category_id = 1,
winner_id = 2;

INSERT INTO lot
SET
date_add = NOW(),
name = 'Крепления Union Contact Pro 2015 года размер L/XL',
description = 'Сегодня напишите SQL-запросы для выполнения основных действий: вставка данных, чтение и поиск, обновление.',
first_price = 8000,
img_url = 'img/lot-4.jpg',
end_date = '2019-11-17',
bid_step = 50,
user_id = 2,
category_id = 2;

INSERT INTO lot
SET
date_add = NOW(),
name = 'Ботинки для сноуборда DC Mutiny Charocal',
description = 'Сегодня для выполнения основных действий: вставка данных, чтение и поиск, обновление.',
first_price = 10999,
img_url = 'img/lot-4.jpg',
end_date = '2019-12-09',
bid_step = 100,
user_id = 3,
category_id = 3;

INSERT INTO lot
SET
date_add = NOW(),
name = 'Куртка для сноуборда DC Mutiny Charocal',
description = 'Сегодня для выполнения основных действий: вставка данных, чтение и поиск, обновление.',
first_price = 7500,
img_url = 'img/lot-5.jpg',
end_date = '2019-11-19',
bid_step = 50,
user_id = 1,
category_id = 4;

INSERT INTO lot
SET
date_add = NOW(),
name = 'Маска Oakley Canopy',
description = 'Сегодня для выполнения основных действий: вставка данных, чтение и поиск, обновление.',
first_price = 5400,
img_url = 'img/lot-5.jpg',
end_date = '2019-11-19',
bid_step = 50,
user_id = 1,
category_id = 6;

-- добавим bid в таблицу bid
INSERT INTO bid
SET
date_add = NOW(),
price = 11199,
user_id = 2,
lot_id = 1;

INSERT INTO bid
SET
date_add = NOW(),
price = 11399,
user_id = 1,
lot_id = 1;

INSERT INTO bid
SET
date_add = NOW(),
price = 160499,
user_id = 3,
lot_id = 2;

INSERT INTO bid
SET
date_add = NOW(),
price = 160999,
user_id = 1,
lot_id = 2;

INSERT INTO bid
SET
date_add = NOW(),
price = 8050,
user_id = 2,
lot_id = 3;

INSERT INTO bid
SET
date_add = NOW(),
price = 8100,
user_id = 1,
lot_id = 3;

-- получить все категории
SELECT * FROM category;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, текущую цену, название категории
SELECT l.name, l.first_price, l.img_url, c.name AS category_name, l.end_date,
(SELECT b.price FROM bid b WHERE b.lot_id = l.id ORDER BY b.price ASC LIMIT 1) as last_price,
l.category_id
FROM lot l INNER JOIN category c ON c.id = l.category_id
WHERE l.end_date > NOW();

-- показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT l.*, u.name FROM lot l
INNER JOIN user u ON l.user_id = u.id;

-- обновить название лота по его идентификатору
UPDATE lot SET name = 'Regular Snowboard' WHERE id = 2;

-- получить список ставок для лота по его идентификатору с сортировкой по дате
SELECT b.*, l.name FROM bid b
INNER JOIN lot l ON l.id = b.lot_id WHERE b.lot_id = 2 ORDER BY b.date_add ASC;
