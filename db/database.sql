/* scripts para criação do banco de dados */
DROP DATABASE IF EXISTS teste;

CREATE DATABASE teste;

use teste;

CREATE TABLE contacts (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  content TEXT NOT NULL,
  created_at TIMESTAMP
);

insert into contacts (name, email, content, created_at) values ('admin', 'admin@admin.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', CURRENT_TIMESTAMP);

show tables;