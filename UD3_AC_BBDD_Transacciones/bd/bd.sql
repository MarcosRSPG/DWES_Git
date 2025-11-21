CREATE DATABASE dws_bbdd_ud3_ac
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dws_bbdd_ud3_ac;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS videojuegos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    genero VARCHAR(100),
    plataforma VARCHAR(100),
    fecha_lanzamiento DATE,
    precio DECIMAL(10,2),
    jugado TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;
