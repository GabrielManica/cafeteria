CREATE TABLE cidade( 
      id number(10)    NOT NULL , 
      nome_cidade varchar  (50)   , 
      uf char  (2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cliente( 
      id number(10)    NOT NULL , 
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
      cidade_id number(10)    NOT NULL , 
      uf char  (2)   , 
      situacao char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE fornecedor( 
      id number(10)    NOT NULL , 
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
      cidade_id number(10)    NOT NULL , 
      uf char  (2)   , 
      situacao char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE produto( 
      id number(10)    NOT NULL , 
      descricao varchar  (100)   , 
      preco binary_double   , 
      estoque number(10)   , 
      fornecedor_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE cliente ADD CONSTRAINT fk_cliente_1 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_1 FOREIGN KEY (fornecedor_id) references fornecedor(id); 
 CREATE SEQUENCE cidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cidade_id_seq_tr 

BEFORE INSERT ON cidade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT cidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cliente_id_seq_tr 

BEFORE INSERT ON cliente FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE fornecedor_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER fornecedor_id_seq_tr 

BEFORE INSERT ON fornecedor FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT fornecedor_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER produto_id_seq_tr 

BEFORE INSERT ON produto FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 
  
