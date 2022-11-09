PRAGMA foreign_keys=OFF; 

CREATE TABLE cidade( 
      id  INTEGER    NOT NULL  , 
      nome_cidade varchar  (50)   , 
      uf char  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   , 
      cpf char  (14)   , 
      celular char  (14)   , 
      telefone char  (14)   , 
      email varchar  (50)   , 
      sexo char  (10)   , 
      estado_civil char  (20)   , 
      data_nascimento date   , 
      cep char  (9)   , 
      endereco varchar  (100)   , 
      numero char  (5)   , 
      complemento char  (20)   , 
      bairro varchar  (50)   , 
      cidade_id int   NOT NULL  , 
      uf char  (2)   , 
      situacao char  (10)   , 
 PRIMARY KEY (id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id)) ; 

CREATE TABLE fornecedor( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (50)   , 
      cnpj char  (14)   , 
      celular char  (14)   , 
      telefone char  (14)   , 
      email varchar  (50)   , 
      cep char  (9)   , 
      endereco varchar  (100)   , 
      numero char  (5)   , 
      complemento char  (20)   , 
      bairro varchar  (50)   , 
      cidade_id int   NOT NULL  , 
      uf char  (2)   , 
      situacao char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (100)   , 
      preco double   , 
      estoque int   , 
      fornecedor_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(fornecedor_id) REFERENCES fornecedor(id)) ; 

 
 
  
