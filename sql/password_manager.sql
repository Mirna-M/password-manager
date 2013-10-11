CREATE DATABASE password_manager;

USE password_manager;

CREATE TABLE users (
    user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_login VARCHAR(50) NOT NULL,
    user_pass CHAR(32) NOT NULL,
    PRIMARY KEY(user_id)
) DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;

CREATE TABLE user_data (
    user_data_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_data_user_id INT UNSIGNED NOT NULL,
    user_data_for VARCHAR(50) NOT NULL,
    user_data_name VARCHAR(50) NOT NULL,
    user_data_password TEXT NOT NULL,
    PRIMARY KEY(user_data_id),
    FOREIGN KEY(user_data_user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
) DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
