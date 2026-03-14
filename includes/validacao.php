<?php

/**
 * Funções de validação e sanitização para segurança
 */

/**
 * Sanitiza string removendo tags HTML e caracteres especiais
 * Proteção contra XSS
 */
function sanitizarString($string, $maxLength = 255)
{
    if (empty($string)) {
        return null;
    }

    // Remove tags HTML e PHP
    $string = strip_tags($string);

    // Remove espaços extras
    $string = trim($string);

    // Limita o tamanho
    if (strlen($string) > $maxLength) {
        $string = substr($string, 0, $maxLength);
    }

    return $string;
}

/**
 * Valida e sanitiza email
 */
function validarEmail($email)
{
    if (empty($email)) {
        return null;
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }

    // Limita tamanho
    if (strlen($email) > 150) {
        throw new Exception('Email muito longo');
    }

    return $email;
}

/**
 * Valida e sanitiza telefone
 * Aceita: (11) 98765-4321, 11987654321, (11)987654321, etc
 */
function validarTelefone($telefone)
{
    if (empty($telefone)) {
        throw new Exception('Telefone é obrigatório');
    }

    // Remove tudo exceto números
    $telefone = preg_replace('/[^0-9]/', '', $telefone);

    // Valida tamanho (10 ou 11 dígitos)
    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
        throw new Exception('Telefone inválido. Use formato: (11) 98765-4321');
    }

    // Formata para armazenar: (11) 98765-4321
    if (strlen($telefone) == 11) {
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7);
    } else {
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6);
    }
}

/**
 * Valida CPF
 */
function validarCPF($cpf)
{
    if (empty($cpf)) {
        return null; // CPF é opcional em alguns formulários
    }

    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica se tem 11 dígitos
    if (strlen($cpf) != 11) {
        throw new Exception('CPF inválido. Use formato: 123.456.789-00');
    }

    // Verifica se não é uma sequência de números iguais
    if (preg_match('/^(\d)\1{10}$/', $cpf)) {
        throw new Exception('CPF inválido');
    }

    // Validação do dígito verificador
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            throw new Exception('CPF inválido');
        }
    }

    // Formata: 123.456.789-00
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

/**
 * Valida nome completo
 */
function validarNome($nome)
{
    if (empty($nome)) {
        throw new Exception('Nome é obrigatório');
    }

    $nome = sanitizarString($nome, 200);

    // Verifica se tem pelo menos 3 caracteres
    if (strlen($nome) < 3) {
        throw new Exception('Nome muito curto. Digite seu nome completo');
    }

    // Verifica se contém apenas letras, espaços e acentos
    if (!preg_match('/^[a-záàâãéèêíïóôõöúçñ\s]+$/iu', $nome)) {
        throw new Exception('Nome contém caracteres inválidos');
    }

    return $nome;
}

/**
 * Valida endereço
 */
function validarEndereco($endereco)
{
    if (empty($endereco)) {
        return null;
    }

    $endereco = sanitizarString($endereco, 255);

    if (strlen($endereco) < 5) {
        throw new Exception('Endereço muito curto');
    }

    return $endereco;
}

/**
 * Valida valor booleano (Sim/Não)
 */
function validarBooleano($valor)
{
    if (empty($valor)) {
        return null;
    }

    if ($valor === 'Sim' || $valor === 'sim' || $valor === '1' || $valor === 1 || $valor === true) {
        return 1;
    }

    if ($valor === 'Não' || $valor === 'Nao' || $valor === 'não' || $valor === 'nao' || $valor === '0' || $valor === 0 || $valor === false) {
        return 0;
    }

    return null;
}

/**
 * Valida e sanitiza texto livre (observações, etc)
 */
function validarTextoLivre($texto, $maxLength = 1000)
{
    if (empty($texto)) {
        return null;
    }

    $texto = sanitizarString($texto, $maxLength);

    return $texto;
}

/**
 * Valida array de checkboxes (previne valores maliciosos)
 */
function validarCheckboxArray($valores, $valoresPermitidos)
{
    if (empty($valores) || !is_array($valores)) {
        return null;
    }

    // Filtra apenas valores permitidos
    $valoresFiltrados = array_filter($valores, function ($valor) use ($valoresPermitidos) {
        return in_array($valor, $valoresPermitidos);
    });

    if (empty($valoresFiltrados)) {
        return null;
    }

    // Sanitiza cada valor
    $valoresFiltrados = array_map(function ($valor) {
        return sanitizarString($valor, 50);
    }, $valoresFiltrados);

    return implode(', ', $valoresFiltrados);
}

/**
 * Valida radio button (garante que seja um valor permitido)
 */
function validarRadio($valor, $valoresPermitidos)
{
    if (empty($valor)) {
        return null;
    }

    if (!in_array($valor, $valoresPermitidos)) {
        return null;
    }

    return sanitizarString($valor, 50);
}

/**
 * Proteção básica contra spam/flood
 * Limita submissões por IP
 */
function verificarRateLimit()
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $tempoLimite = 60; // 60 segundos entre submissões

    // Cria diretório de cache se não existir
    $cacheDir = __DIR__ . '/../cache';
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    $cacheFile = $cacheDir . '/rate_limit_' . md5($ip) . '.txt';

    if (file_exists($cacheFile)) {
        $ultimaSubmissao = (int)file_get_contents($cacheFile);
        $tempoDecorrido = time() - $ultimaSubmissao;

        // if ($tempoDecorrido < $tempoLimite) {
        //     $tempoRestante = $tempoLimite - $tempoDecorrido;
        //     throw new Exception("Por favor, aguarde {$tempoRestante} segundos antes de enviar novamente");
        // }
    }

    // Registra nova submissão
    file_put_contents($cacheFile, time());

    // Limpa arquivos antigos (mais de 1 hora)
    $arquivos = glob($cacheDir . '/rate_limit_*.txt');
    foreach ($arquivos as $arquivo) {
        if (time() - filemtime($arquivo) > 3600) {
            @unlink($arquivo);
        }
    }
}

/**
 * Valida tamanho total do POST para prevenir ataques
 */
function validarTamanhoPost()
{
    $tamanhoMax = 1024 * 1024; // 1MB
    $tamanhoAtual = strlen(file_get_contents('php://input'));

    if ($tamanhoAtual > $tamanhoMax) {
        throw new Exception('Dados enviados são muito grandes');
    }
}

/**
 * Registra tentativas suspeitas em log
 */
function registrarAtividadeSuspeita($mensagem)
{
    $logDir = __DIR__ . '/../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/seguranca_' . date('Y-m-d') . '.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $timestamp = date('Y-m-d H:i:s');
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    $linha = "[{$timestamp}] IP: {$ip} | UA: {$userAgent} | {$mensagem}\n";

    file_put_contents($logFile, $linha, FILE_APPEND);
}
