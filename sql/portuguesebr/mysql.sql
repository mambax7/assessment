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
  (1, 1, 'Qual a capital do Brasil?', '2007-03-15', '2007-03-15', '1'),
  (2, 1, 'Qual a capital da Inglaterra?', '2007-03-15', '2007-03-15', '1'),
  (3, 1, 'Qual a capital da Italia', '2007-03-15', '2007-03-15', '1'),
  (4, 1, 'Qual a capital da França', '2007-03-15', '2007-03-15', '1'),
  (5, 1, 'Qual a capital da Suiça', '2007-03-15', '2007-03-15', '1'),
  (6, 1, 'Qual a capital de Portugal', '2007-03-15', '2007-03-15', '1'),
  (7, 1, 'Qual a capital da Espanha?', '2007-03-15', '2007-03-15', '1'),
  (8, 1, 'Qual a capital da Argentina', '2007-03-15', '2007-03-15', '1'),
  (9, 1, 'Qual a capital dos Estados Unidos?', '2007-03-15', '2007-03-15', '1'),
  (10, 1, 'Qual a capital da Grécia', '2007-03-15', '2007-03-15', '1');

-- Extraindo dados da tabela `assessment_provas`


INSERT INTO `assessment_provas` (`cod_prova`, `data_criacao`, `data_update`, `titulo`, `descricao`, `instrucoes`, `tempo`, `acesso`, `uid_elaboradores`, `data_inicio`, `data_fim`)
VALUES
  (1, '2007-03-15', '2007-03-15', 'PROVA DE GEOGRAFIA MARÇO 2007', 'Prova de geografia sobre as capitais dos países do mundo',
      'Esta prova é composta de 10 questões, cada uma terá 5 respostas sendo que apenas uma está correta. Preste atenção aos peguinhas. Quando terminar aguarde o resultado que lhe será enviado por email e poderá ser consultado aqui na página de assessment.\r\n\r\nVocê tem 10 minutos para terminar a prova , após este tempo o programa encerrará sozinho a sua prova.\r\n\r\nBoa Sorte',
      '600', '1,2', '1', '1999-11-30 00:00:00', '2015-12-31 00:00:00');

-- Extraindo dados da tabela `assessment_respostas`


INSERT INTO `assessment_respostas` (`cod_resposta`, `cod_pergunta`, `titulo`, `iscerta`, `data_criacao`, `data_update`, `uid_elaboradores`, `isativa`)
VALUES
  (1, 1, 'Brasília', 1, '2007-03-15', '2007-03-15', '', 0),
  (2, 1, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (3, 1, 'Tokio', 0, '2007-03-15', '2007-03-15', '', 0),
  (4, 1, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (5, 1, 'Londres', 0, '2007-03-15', '2007-03-15', '', 0),
  (6, 2, 'Londres', 1, '2007-03-15', '2007-03-15', '', 0),
  (7, 2, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (8, 2, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (9, 2, 'Assuncion', 0, '2007-03-15', '2007-03-15', '', 0),
  (10, 2, 'Africa', 0, '2007-03-15', '2007-03-15', '', 0),
  (11, 3, 'Roma', 1, '2007-03-15', '2007-03-15', '', 0),
  (12, 3, 'PAris', 0, '2007-03-15', '2007-03-15', '', 0),
  (13, 3, 'Berlin', 0, '2007-03-15', '2007-03-15', '', 0),
  (14, 3, 'Washington', 0, '2007-03-15', '2007-03-15', '', 0),
  (15, 3, 'Camberra', 0, '2007-03-15', '2007-03-15', '', 0),
  (16, 4, 'Paris', 1, '2007-03-15', '2007-03-15', '', 0),
  (17, 4, 'Londres', 0, '2007-03-15', '2007-03-15', '', 0),
  (18, 4, 'Brasília', 0, '2007-03-15', '2007-03-15', '', 0),
  (19, 4, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (20, 4, 'Camberra', 0, '2007-03-15', '2007-03-15', '', 0),
  (21, 5, 'Berna', 1, '2007-03-15', '2007-03-15', '', 0),
  (22, 5, 'Viena', 0, '2007-03-15', '2007-03-15', '', 0),
  (23, 5, 'Madrid', 0, '2007-03-15', '2007-03-15', '', 0),
  (24, 5, 'Lisboa', 0, '2007-03-15', '2007-03-15', '', 0),
  (25, 5, 'Praga', 0, '2007-03-15', '2007-03-15', '', 0),
  (26, 6, 'Lisboa', 1, '2007-03-15', '2007-03-15', '', 0),
  (27, 6, 'Madrid', 0, '2007-03-15', '2007-03-15', '', 0),
  (28, 6, 'Porto', 0, '2007-03-15', '2007-03-15', '', 0),
  (29, 6, 'Europa', 0, '2007-03-15', '2007-03-15', '', 0),
  (30, 6, 'Brasília', 0, '2007-03-15', '2007-03-15', '', 0),
  (31, 7, 'Madrid', 1, '2007-03-15', '2007-03-15', '', 0),
  (32, 7, 'Berna', 0, '2007-03-15', '2007-03-15', '', 0),
  (33, 7, 'Roma', 0, '2007-03-15', '2007-03-15', '', 0),
  (34, 7, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (35, 7, 'Buenos Aires', 0, '2007-03-15', '2007-03-15', '', 0),
  (36, 8, 'Buenos Aires', 1, '2007-03-15', '2007-03-15', '1', 0),
  (37, 8, 'Londres', 0, '2007-03-15', '2007-03-15', '1', 0),
  (38, 8, 'Macau', 0, '2007-03-15', '2007-03-15', '1', 0),
  (39, 8, 'Beijing', 0, '2007-03-15', '2007-03-15', '1', 0),
  (40, 8, 'Milão', 0, '2007-03-15', '2007-03-15', '1', 0),
  (41, 9, 'Washington', 1, '2007-03-15', '2007-03-15', '', 0),
  (42, 9, 'Londres', 0, '2007-03-15', '2007-03-15', '', 0),
  (43, 9, 'Paris', 0, '2007-03-15', '2007-03-15', '', 0),
  (44, 9, 'Sydney', 0, '2007-03-15', '2007-03-15', '', 0),
  (45, 9, 'Nova York', 0, '2007-03-15', '2007-03-15', '', 0),
  (46, 10, 'Atenas', 1, '2007-03-15', '2007-03-15', '', 0),
  (47, 10, 'Riad', 0, '2007-03-15', '2007-03-15', '', 0),
  (48, 10, 'Madrid', 0, '2007-03-15', '2007-03-15', '', 0),
  (49, 10, 'Londres', 0, '2007-03-15', '2007-03-15', '', 0),
  (50, 10, 'Jamaica', 0, '2007-03-15', '2007-03-15', '', 0);


