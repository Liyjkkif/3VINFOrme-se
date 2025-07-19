-- Cria o banco
CREATE DATABASE IF NOT EXISTS turma_db;
USE turma_db;

-- Cria a tabela de usuários
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);
