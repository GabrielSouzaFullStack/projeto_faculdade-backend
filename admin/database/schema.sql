-- Tabela de animais para adoção
CREATE TABLE IF NOT EXISTS animais (
    id_animal INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('cachorro', 'gato', 'coelho', 'outro') NOT NULL,
    raca VARCHAR(100),
    idade_anos INT,
    idade_meses INT,
    sexo ENUM('macho', 'femea') NOT NULL,
    porte ENUM('pequeno', 'medio', 'grande') NOT NULL,
    cor VARCHAR(50),
    descricao TEXT,
    foto VARCHAR(255),
    castrado BOOLEAN DEFAULT FALSE,
    vacinado BOOLEAN DEFAULT FALSE,
    vermifugado BOOLEAN DEFAULT FALSE,
    status ENUM('disponivel', 'adotado', 'em_processo') DEFAULT 'disponivel',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para armazenar formulários de adoção
CREATE TABLE IF NOT EXISTS formularios_adocao (
    id_formulario INT PRIMARY KEY AUTO_INCREMENT,
    nome_completo VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    cpf VARCHAR(14),
    data_nascimento DATE,
    estado_civil VARCHAR(50),
    profissao VARCHAR(100),
    renda_familiar DECIMAL(10,2),
    
    -- Endereço
    cep VARCHAR(10),
    endereco VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    
    -- Informações da residência
    tipo_residencia ENUM('casa', 'apartamento', 'outro'),
    residencia_propria BOOLEAN,
    tamanho_residencia VARCHAR(50),
    tem_quintal BOOLEAN,
    quintal_cercado BOOLEAN,
    
    -- Pessoas na casa
    qtd_adultos INT,
    qtd_criancas INT,
    idade_criancas VARCHAR(100),
    tem_criancas BOOLEAN,
    
    -- Animais existentes
    tem_outros_animais BOOLEAN,
    quais_animais TEXT,
    animais_castrados BOOLEAN,
    
    -- Experiência e preferências
    experiencia_animais TEXT,
    animal_preferencia VARCHAR(100),
    motivo_adocao TEXT,
    tempo_dedicacao VARCHAR(100),
    onde_animal_fica TEXT,
    responsavel_cuidados VARCHAR(100),
    gastos_estimados VARCHAR(50),
    situacao_mudanca TEXT,
    autorizacao_visita BOOLEAN,
    
    -- Outros
    termo_responsabilidade BOOLEAN DEFAULT FALSE,
    status ENUM('pendente', 'em_analise', 'aprovado', 'recusado') DEFAULT 'pendente',
    observacoes TEXT,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela para formulários de lar temporário
CREATE TABLE IF NOT EXISTS formularios_lar_temporario (
    id_formulario INT PRIMARY KEY AUTO_INCREMENT,
    nome_completo VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    cpf VARCHAR(14),
    data_nascimento DATE,
    profissao VARCHAR(100),
    
    -- Endereço
    cep VARCHAR(10),
    endereco VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    
    -- Informações da residência
    tipo_residencia ENUM('casa', 'apartamento', 'outro'),
    tem_quintal BOOLEAN,
    quintal_cercado BOOLEAN,
    local_seguro BOOLEAN,
    
    -- Disponibilidade
    tempo_disponivel VARCHAR(100),
    periodo_disponivel VARCHAR(100),
    pode_levar_veterinario BOOLEAN,
    tem_transporte BOOLEAN,
    
    -- Experiência
    experiencia_animais TEXT,
    tem_outros_animais BOOLEAN,
    quais_animais TEXT,
    tipo_animal_aceita VARCHAR(100),
    
    -- Outros
    observacoes TEXT,
    termo_responsabilidade BOOLEAN DEFAULT FALSE,
    status ENUM('pendente', 'em_analise', 'aprovado', 'recusado') DEFAULT 'pendente',
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
