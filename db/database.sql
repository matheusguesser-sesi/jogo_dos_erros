CREATE DATABASE IF NOT EXISTS jogo_erros_matheus;
use jogo_erros_matheus;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);
