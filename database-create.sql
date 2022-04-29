CREATE DATABASE projeto_final_pw2_timeto;
use projeto_final_pw2_timeto;

CREATE TABLE user (
  id int(11) NOT NULL primary key auto_increment,
  username varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE events (
  id int(11) NOT NULL primary key auto_increment,
  name varchar(100) NOT NULL,
  description text NOT NULL,
  initialDatetime datetime NOT NULL,
  finalDatetime datetime NOT NULL,
  idUser int(11) NOT NULL,
  CONSTRAINT idUser FOREIGN KEY (idUser) REFERENCES user (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

