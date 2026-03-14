-- Configurar usuário READ-ONLY para o banco ong_amigos_rua

-- Conceder privilégio SELECT no banco
GRANT SELECT ON ong_amigos_rua.* TO 'leitor'@'localhost';

-- Verificar privilégios
SHOW GRANTS FOR 'leitor'@'localhost';
