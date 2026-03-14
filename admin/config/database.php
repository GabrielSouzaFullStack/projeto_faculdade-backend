<?php

$projectRoot = dirname(__DIR__, 2);
$composerAutoload = $projectRoot . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if (is_readable($composerAutoload)) {
    require_once $composerAutoload;
}

if (!function_exists('loadDotenv')) {
    /**
     * Carrega variaveis do .env via vlucas/phpdotenv.
     */
    function loadDotenv()
    {
        static $loaded = false;

        if ($loaded) {
            return;
        }

        $loaded = true;
        $rootPath = dirname(__DIR__, 2);
        $envPath = $rootPath . DIRECTORY_SEPARATOR . '.env';

        if (!is_readable($envPath)) {
            throw new RuntimeException('Arquivo .env nao encontrado ou sem permissao de leitura.');
        }

        if (!class_exists('Dotenv\\Dotenv')) {
            throw new RuntimeException('Biblioteca vlucas/phpdotenv nao encontrada. Rode: composer install');
        }

        // createMutable garante que o valor atual do .env prevaleca na carga.
        $dotenv = Dotenv\Dotenv::createMutable($rootPath);
        $dotenv->safeLoad();
        $dotenv->required([
            'DB_DRIVER',
            'DB_HOST',
            'DB_PORT',
            'DB_NAME',
            'DB_USER',
            'DB_CHARSET',
        ])->notEmpty();
        $dotenv->required(['DB_PASS']);
    }
}

if (!function_exists('env')) {
    /**
     * Le uma variavel de ambiente com fallback.
     */
    function env($key, $default = null)
    {
        loadDotenv();

        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }

        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        return $default;
    }
}

if (!function_exists('envRequired')) {
    /**
     * Le variavel obrigatoria do ambiente.
     */
    function envRequired($key, $allowEmpty = false)
    {
        $value = env($key, null);

        if ($value === null) {
            throw new RuntimeException('Variavel obrigatoria ausente no .env: ' . $key);
        }

        if (!$allowEmpty && trim((string) $value) === '') {
            throw new RuntimeException('Variavel obrigatoria vazia no .env: ' . $key);
        }

        return (string) $value;
    }
}

/**
 * Provider de conexao com padrao hibrido:
 * - Factory para criar conexoes por nome
 * - Cache estatico para reutilizar instancias na mesma request
 */
if (!class_exists('ConnectionProvider')) {
    class ConnectionProvider
    {
        private static $connections = [];
        private static $factories = [];

        private function __construct() {}

        public static function getConnection($connectionName = 'default')
        {
            if (isset(self::$connections[$connectionName])) {
                return self::$connections[$connectionName];
            }

            $factory = self::getFactory($connectionName);
            self::$connections[$connectionName] = $factory();

            return self::$connections[$connectionName];
        }

        public static function clearConnection($connectionName = null)
        {
            if ($connectionName === null) {
                self::$connections = [];
                return;
            }

            unset(self::$connections[$connectionName]);
        }

        private static function getConfig($connectionName = 'default')
        {
            $prefix = self::getEnvPrefix($connectionName);

            return [
                'driver' => self::getConfigValue($prefix . '_DRIVER', 'DB_DRIVER'),
                'host' => self::getConfigValue($prefix . '_HOST', 'DB_HOST'),
                'port' => self::getConfigValue($prefix . '_PORT', 'DB_PORT'),
                'database' => self::getConfigValue($prefix . '_NAME', 'DB_NAME'),
                'user' => self::getConfigValue($prefix . '_USER', 'DB_USER'),
                'password' => self::getConfigValue($prefix . '_PASS', 'DB_PASS', true),
                'charset' => self::getConfigValue($prefix . '_CHARSET', 'DB_CHARSET'),
            ];
        }

        private static function getConfigValue($primaryKey, $fallbackKey, $allowEmpty = false)
        {
            $value = env($primaryKey, null);

            if ($value === null || (!$allowEmpty && trim((string) $value) === '')) {
                $value = env($fallbackKey, null);
            }

            if ($value === null) {
                throw new RuntimeException('Variavel obrigatoria ausente no .env: ' . $primaryKey);
            }

            if (!$allowEmpty && trim((string) $value) === '') {
                throw new RuntimeException('Variavel obrigatoria vazia no .env: ' . $primaryKey);
            }

            return (string) $value;
        }

        private static function getFactory($connectionName)
        {
            if (!isset(self::$factories[$connectionName])) {
                $config = self::getConfig($connectionName);

                self::$factories[$connectionName] = function () use ($config) {
                    $dsn = $config['driver'] . ':host=' . $config['host']
                        . ';dbname=' . $config['database']
                        . ';charset=' . $config['charset'];

                    if ($config['port'] !== '') {
                        $dsn .= ';port=' . $config['port'];
                    }

                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];

                    return new PDO($dsn, $config['user'], $config['password'], $options);
                };
            }

            return self::$factories[$connectionName];
        }

        private static function getEnvPrefix($connectionName)
        {
            if ($connectionName === 'default') {
                return 'DB';
            }

            return 'DB_' . strtoupper((string) $connectionName);
        }
    }
}

if (!function_exists('getConnection')) {
    function getConnection($connectionName = 'default')
    {
        return ConnectionProvider::getConnection($connectionName);
    }
}

if (!function_exists('conectar_db')) {
    function conectar_db()
    {
        try {
            return getConnection('default');
        } catch (PDOException $e) {
            error_log('Erro ao conectar com o banco: ' . $e->getMessage());
            echo 'Erro ao conectar com o banco de dados.';
            return null;
        }
    }
}

defined('DSN') || define('DSN', envRequired('DB_DRIVER'));
defined('SERVER') || define('SERVER', envRequired('DB_HOST'));
defined('USER') || define('USER', envRequired('DB_USER'));
defined('PASSWORD') || define('PASSWORD', envRequired('DB_PASS', true));
defined('DATA') || define('DATA', envRequired('DB_NAME'));

if (!isset($conn)) {
    try {
        $conn = ConnectionProvider::getConnection('default');
    } catch (PDOException $e) {
        die('Erro de conexao com o banco de dados.');
    }
}
