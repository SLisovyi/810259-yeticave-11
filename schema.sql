CREATE DATABASE `810259-yeticave-11`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

  USE `810259-yeticave-11`;

CREATE TABLE user (
  id                INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  date_add          DATETIME NOT NULL,
  email             VARCHAR(128) NOT NULL UNIQUE,
  name              VARCHAR(128) NOT NULL,
  password          CHAR(64) NOT NULL,
  contact           TEXT NOT NULL
);

CREATE TABLE lot (
  id              INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  date_add        DATETIME NOT NULL,
  name            VARCHAR(128) NOT NULL,
  description     TEXT NOT NULL,
  img_url         VARCHAR(128) NOT NULL,
  first_price     INT NOT NULL,
  end_date        DATETIME NOT NULL,
  bid_step        INT NOT NULL,
  user_id         INT NOT NULL,
  category_id     INT NOT NULL,
  winner_id       INT DEFAULT NULL
);

CREATE TABLE bid (
  id              INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  date_add        DATETIME NOT NULL,
  price           INT NOT NULL,
  user_id         INT NOT NULL,
  lot_id          INT NOT NULL
);

CREATE TABLE category (
  id              INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name            VARCHAR(64) NOT NULL,
  character_code  VARCHAR(64) NOT NULL
);
