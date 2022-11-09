CREATE TABLE cidade( 
      id  SERIAL    NOT NULL  , 
      nome_cidade varchar  (50)   , 
      uf char  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente( 
      id  SERIAL    NOT NULL  , 
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
      cidade_id integer   NOT NULL  , 
      uf char  (2)   , 
      situacao char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fornecedor( 
      id  SERIAL    NOT NULL  , 
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
      cidade_id integer   NOT NULL  , 
      uf char  (2)   , 
      situacao char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (100)   , 
      preco float   , 
      estoque integer   , 
      fornecedor_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE cliente ADD CONSTRAINT fk_cliente_1 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_1 FOREIGN KEY (fornecedor_id) references fornecedor(id); 

  
