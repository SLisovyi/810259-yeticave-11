CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

  USE yeticave;

CREATE TABLE user (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  date_add          DATETIME,
  email             VARCHAR(128) NOT NULL UNIQUE,
  user_name         VARCHAR(128),
  password          CHAR(64),
  contact           TEXT,
  user_id           INT
);

CREATE TABLE lot (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  date_add        DATETIME,
  lot_name        VARCHAR(255),
  description     TEXT,
  img_url         TEXT,
  price           INT,
  end_date        DATETIME,
  bid_step        INT,
  lot_id          INT
);

CREATE TABLE bid (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  date_add        DATETIME,
  price           INT,
  bid_id          INT
);

CREATE TABLE categorie (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  name            VARCHAR(64),
  character_code  INT,
  categorie_id    INT
);