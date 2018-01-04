-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net

CREATE TABLE assessment_perguntas (
  cod_pergunta   INT(11)      NOT NULL AUTO_INCREMENT,
  cod_prova      INT(11)      NOT NULL,
  titulo         VARCHAR(255) NOT NULL,
  data_criacao   DATE         NOT NULL,
  data_update    DATE         NOT NULL,
  uid_elaborador VARCHAR(50)  NOT NULL,
  ordem          INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (cod_pergunta)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 1;

CREATE TABLE `assessment_provas` (
  `cod_prova`        INT(11)      NOT NULL AUTO_INCREMENT
  COMMENT 'Chave Primária',
  `data_criacao`     DATE         NOT NULL,
  `data_update`      DATE         NOT NULL,
  `titulo`           VARCHAR(255) NOT NULL,
  `descricao`        TEXT         NOT NULL,
  `instrucoes`       TEXT         NOT NULL,
  `tempo`            VARCHAR(10)  NOT NULL,
  `acesso`           VARCHAR(250) NOT NULL,
  `uid_elaboradores` VARCHAR(50)  NOT NULL,
  `data_inicio`      DATETIME     NOT NULL,
  `data_fim`         DATETIME     NOT NULL,
  PRIMARY KEY (`cod_prova`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 1;

CREATE TABLE `assessment_respostas` (
  `cod_resposta`     INT(11)      NOT NULL AUTO_INCREMENT,
  `cod_pergunta`     INT(11)      NOT NULL,
  `titulo`           VARCHAR(255) NOT NULL,
  `iscerta`          SMALLINT(1)  NOT NULL,
  `data_criacao`     DATE         NOT NULL,
  `data_update`      DATE         NOT NULL,
  `uid_elaboradores` VARCHAR(50)  NOT NULL,
  `isativa`          TINYINT(1)   NOT NULL,
  PRIMARY KEY (`cod_resposta`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 1;


CREATE TABLE `assessment_resultados` (
  `cod_resultado` INT(11)     NOT NULL AUTO_INCREMENT,
  `cod_prova`     INT(11)     NOT NULL,
  `uid_aluno`     INT(11)     NOT NULL,
  `data_inicio`   DATETIME    NOT NULL,
  `data_fim`      DATETIME    NOT NULL,
  `resp_certas`   TEXT        NOT NULL,
  `resp_erradas`  TEXT        NOT NULL,
  `nota_final`    INT(11)     NOT NULL,
  `nivel`         VARCHAR(50) NOT NULL,
  `obs`           TEXT        NOT NULL,
  `terminou`      INT(1)      NOT NULL,
  `fechada`       INT(1)      NOT NULL,
  PRIMARY KEY (`cod_resultado`)
)
  ENGINE = MyISAM
  DEFAULT CHARACTER SET utf8
  AUTO_INCREMENT = 1;

CREATE TABLE `assessment_documentos` (
  `cod_documento`  INT(11)      NOT NULL AUTO_INCREMENT,
  `titulo`         VARCHAR(255) NOT NULL,
  `tipo`           TINYINT(4)   NOT NULL,
  `cod_prova`      INT(11)      NOT NULL,
  `cods_perguntas` TEXT         NOT NULL,
  `documento`      TEXT         NOT NULL,
  `uid_elaborador` INT(11)      NOT NULL,
  `fonte`          VARCHAR(255) NOT NULL,
  `html`           INT(11)      NOT NULL,
  PRIMARY KEY (`cod_documento`)
)
  ENGINE = MyISAM
  DEFAULT CHARACTER SET utf8
  AUTO_INCREMENT = 3;


INSERT INTO `assessment_perguntas` (`cod_pergunta`, `cod_prova`, `titulo`, `data_criacao`, `data_update`, `uid_elaborador`) VALUES
  (1, 1, 'What is the capital of Brazil?', '2007-03-15', '2007-03-15', '1'),
  (2, 1, 'What is the capital of England?', '2007-03-15', '2007-03-15', '1'),
  (3, 1, 'What is the capital of Italy', '2007-03-15', '2007-03-15', '1'),
  (4, 1, 'What is the capital of France', '2007-03-15', '2007-03-15', '1'),
  (5, 1, 'What is the capital of Switzerland', '2007-03-15', '2007-03-15', '1'),
  (6, 1, 'What is the capital of Portugal', '2007-03-15', '2007-03-15', '1'),
  (7, 1, 'What is the capital of Spain?', '2007-03-15', '2007-03-15', '1'),
  (8, 1, 'What is the capital of Argentina', '2007-03-15', '2007-03-15', '1'),
  (9, 1, 'What is the capital of USA?', '2007-03-15', '2007-03-15', '1'),
  (10, 1, 'What is the capital of Greece', '2007-03-15', '2007-03-15', '1');

-- Extraindo dados da tabela `assessment_provas`


INSERT INTO `assessment_provas` (`cod_prova`, `data_criacao`, `data_update`, `titulo`, `descricao`, `instrucoes`, `tempo`, `acesso`, `uid_elaboradores`, `data_inicio`, `data_fim`)
VALUES
  (1, '2007-03-15', '2007-03-15', 'GEOGRAPHY TEST MARCH 2007', 'Geography test on the capitals of the countries in the world',
      'This test consists of 10 questions, each will have 5 answers of which only one is correct. Pay attention to details and don\t rush. When done, wait for the results - they will be sent to you by email or they can be found here on the Assessment webpage.\r\n\r\n
      You have 10 minutes to finish the test, after that time the program will finish your test.\r\n\r\n
      Good Luck',
      '600', '1,2', '1', '1999-11-30 00:00:00', '2020-12-31 00:00:00');

-- Extraindo dados da tabela `assessment_respostas`


INSERT INTO `assessment_respostas` (`cod_resposta`, `cod_pergunta`, `titulo`, `iscerta`, `data_criacao`, `data_update`, `uid_elaboradores`, `isativa`)
VALUES
  (1, 1, 'Brasilia', 1, '2007-03-15', '2007-03-15', '', 0),
  (2, 1, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (3, 1, 'Tokyo', 0, '2007-03-15', '2007-03-15', '', 0),
  (4, 1, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (5, 1, 'London', 0, '2007-03-15', '2007-03-15', '', 0),
  (6, 2, 'London', 1, '2007-03-15', '2007-03-15', '', 0),
  (7, 2, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (8, 2, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (9, 2, 'Assuncion', 0, '2007-03-15', '2007-03-15', '', 0),
  (10, 2, 'Africa', 0, '2007-03-15', '2007-03-15', '', 0),
  (11, 3, 'Rome', 1, '2007-03-15', '2007-03-15', '', 0),
  (12, 3, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (13, 3, 'Berlin', 0, '2007-03-15', '2007-03-15', '', 0),
  (14, 3, 'Washington', 0, '2007-03-15', '2007-03-15', '', 0),
  (15, 3, 'Canberra', 0, '2007-03-15', '2007-03-15', '', 0),
  (16, 4, 'Paris', 1, '2007-03-15', '2007-03-15', '', 0),
  (17, 4, 'London', 0, '2007-03-15', '2007-03-15', '', 0),
  (18, 4, 'Brasilia', 0, '2007-03-15', '2007-03-15', '', 0),
  (19, 4, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (20, 4, 'Canberra', 0, '2007-03-15', '2007-03-15', '', 0),
  (21, 5, 'Bern', 1, '2007-03-15', '2007-03-15', '', 0),
  (22, 5, 'Vienna', 0, '2007-03-15', '2007-03-15', '', 0),
  (23, 5, 'Madrid', 0, '2007-03-15', '2007-03-15', '', 0),
  (24, 5, 'Lisbon', 0, '2007-03-15', '2007-03-15', '', 0),
  (25, 5, 'Prague', 0, '2007-03-15', '2007-03-15', '', 0),
  (26, 6, 'Lisbon', 1, '2007-03-15', '2007-03-15', '', 0),
  (27, 6, 'Madrid', 0, '2007-03-15', '2007-03-15', '', 0),
  (28, 6, 'Port', 0, '2007-03-15', '2007-03-15', '', 0),
  (29, 6, 'Europe', 0, '2007-03-15', '2007-03-15', '', 0),
  (30, 6, 'Brasilia', 0, '2007-03-15', '2007-03-15', '', 0),
  (31, 7, 'Madrid', 1, '2007-03-15', '2007-03-15', '', 0),
  (32, 7, 'Bern', 0, '2007-03-15', '2007-03-15', '', 0),
  (33, 7, 'Rome', 0, '2007-03-15', '2007-03-15', '', 0),
  (34, 7, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (35, 7, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (36, 8, 'Buenos Aires', 1, '2007-03-15', '2007-03-15', '1', 0),
  (37, 8, 'London', 0, '2007-03-15', '2007-03-15', '1', 0),
  (38, 8, 'Macau', 0, '2007-03-15', '2007-03-15', '1', 0),
  (39, 8, 'Beijing', 0, '2007-03-15', '2007-03-15', '1', 0),
  (40, 8, 'Milan', 0, '2007-03-15', '2007-03-15', '1', 0),
  (41, 9, 'Washington', 1, '2007-03-15', '2007-03-15', '', 0),
  (42, 9, 'London', 0, '2007-03-15', '2007-03-15', '', 0),
  (43, 9, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (44, 9, 'Sydney', 0, '2007-03-15', '2007-03-15', '', 0),
  (45, 9, 'New York', 0, '2007-03-15', '2007-03-15', '', 0),
  (46, 10, 'Athens', 1, '2007-03-15', '2007-03-15', '', 0),
  (47, 10, 'Riyadh', 0, '2007-03-15', '2007-03-15', '', 0),
  (48, 10, 'Madrid', 0, '2007-03-15', '2007-03-15', '', 0),
  (49, 10, 'London', 0, '2007-03-15', '2007-03-15', '', 0),
  (50, 10, 'Jamaica', 0, '2007-03-15', '2007-03-15', '', 0);
