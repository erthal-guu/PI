
CREATE DATABASE selfmed_db;
USE selfmed_db;


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    sobrenome VARCHAR(80) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATE NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL
);

CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    sintomas TEXT NOT NULL,
    duracao_sintomas VARCHAR(60) NOT NULL,
    intensidade INT NOT NULL,
    resposta_ja VARCHAR(255),
    data_consulta DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);