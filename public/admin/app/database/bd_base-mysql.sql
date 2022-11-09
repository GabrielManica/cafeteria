CREATE TABLE cidade( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome_cidade` varchar  (50)   , 
      `uf` char  (2)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` varchar  (50)   , 
      `cpf` char  (14)   , 
      `celular` char  (14)   , 
      `telefone` char  (14)   , 
      `email` varchar  (50)   , 
      `sexo` char  (10)   , 
      `estado_civil` char  (20)   , 
      `data_nascimento` date   , 
      `cep` char  (9)   , 
      `endereco` varchar  (100)   , 
      `numero` char  (5)   , 
      `complemento` char  (20)   , 
      `bairro` varchar  (50)   , 
      `cidade_id` int   NOT NULL  , 
      `uf` char  (2)   , 
      `situacao` char  (10)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE fornecedor( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` varchar  (50)   , 
      `cnpj` char  (14)   , 
      `celular` char  (14)   , 
      `telefone` char  (14)   , 
      `email` varchar  (50)   , 
      `cep` char  (9)   , 
      `endereco` varchar  (100)   , 
      `numero` char  (5)   , 
      `complemento` char  (20)   , 
      `bairro` varchar  (50)   , 
      `cidade_id` int   NOT NULL  , 
      `uf` char  (2)   , 
      `situacao` char  (10)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE produto( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `descricao` varchar  (100)   , 
      `preco` double   , 
      `estoque` int   , 
      `fornecedor_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

 
  
 ALTER TABLE cliente ADD CONSTRAINT fk_cliente_1 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE produto ADD CONSTRAINT fk_produto_1 FOREIGN KEY (fornecedor_id) references fornecedor(id); 

  
