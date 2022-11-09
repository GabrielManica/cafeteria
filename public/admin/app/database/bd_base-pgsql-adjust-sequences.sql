SELECT setval('cidade_id_seq', coalesce(max(id),0) + 1, false) FROM cidade;
SELECT setval('cliente_id_seq', coalesce(max(id),0) + 1, false) FROM cliente;
SELECT setval('fornecedor_id_seq', coalesce(max(id),0) + 1, false) FROM fornecedor;
SELECT setval('produto_id_seq', coalesce(max(id),0) + 1, false) FROM produto;