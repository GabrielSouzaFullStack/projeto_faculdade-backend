<?php

/**
 * Funções de segurança para senhas
 * Utiliza password_hash() - Padrão PHP para senhas com bcrypt
 */

/**
 * Gera hash seguro da senha usando bcrypt
 * @param string $senha Senha em texto plano
 * @return string Hash da senha
 */
function gerar_hash_senha(string $senha): string
{
    // PASSWORD_DEFAULT usa bcrypt (ou argon2 se disponível)
    // Salt é gerado automaticamente
    // Custo é ajustado automaticamente
    return password_hash($senha, PASSWORD_DEFAULT);
}

/**
 * Valida senha informada contra hash armazenado
 * @param string $senhaDigitada Senha em texto plano
 * @param string $senhaBanco Hash armazenado no banco
 * @return bool True se a senha está correta
 */
function validar_senha(string $senhaDigitada, string $senhaBanco): bool
{
    // Verifica se a senha corresponde ao hash
    return password_verify($senhaDigitada, $senhaBanco);
}

/**
 * Verifica se o hash precisa ser atualizado (rehash)
 * Útil quando você muda o algoritmo ou custo
 * @param string $hash Hash atual
 * @return bool True se precisa atualizar
 */
function senha_precisa_rehash(string $hash): bool
{
    return password_needs_rehash($hash, PASSWORD_DEFAULT);
}
