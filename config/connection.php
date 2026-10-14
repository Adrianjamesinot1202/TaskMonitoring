<?php
declare(strict_types=1);

if (
    isset($_SERVER['SCRIPT_FILENAME'])
    && realpath((string)$_SERVER['SCRIPT_FILENAME']) === __FILE__
) {
    http_response_code(404);
    exit;
}

/**
 * Return the shared PDO connection for the current PHP request.
 */
function task_monitoring_database_connection(): PDO
{
    static $taskMonitoringConnection = null;

    if ($taskMonitoringConnection instanceof PDO) {
        return $taskMonitoringConnection;
    }

    if (!extension_loaded('pdo_pgsql')) {
        throw new RuntimeException(
            'The pdo_pgsql PHP extension must be enabled before connecting to Supabase.'
        );
    }

    $taskMonitoringLocalConfigPath = __DIR__ . '/connection.local.php';
    $taskMonitoringLocalConfig = [];

    if (is_file($taskMonitoringLocalConfigPath)) {
        $taskMonitoringLoadedConfig = require $taskMonitoringLocalConfigPath;

        if (is_array($taskMonitoringLoadedConfig)) {
            $taskMonitoringLocalConfig = $taskMonitoringLoadedConfig;
        }
    }

    $taskMonitoringConfig = [
        'host' => task_monitoring_database_setting(
            'SUPABASE_DB_HOST',
            (string)($taskMonitoringLocalConfig['host'] ?? '')
        ),
        'port' => task_monitoring_database_setting(
            'SUPABASE_DB_PORT',
            (string)($taskMonitoringLocalConfig['port'] ?? '5432')
        ),
        'database' => task_monitoring_database_setting(
            'SUPABASE_DB_NAME',
            (string)($taskMonitoringLocalConfig['database'] ?? 'postgres')
        ),
        'username' => task_monitoring_database_setting(
            'SUPABASE_DB_USER',
            (string)($taskMonitoringLocalConfig['username'] ?? '')
        ),
        'password' => task_monitoring_database_setting(
            'SUPABASE_DB_PASSWORD',
            (string)($taskMonitoringLocalConfig['password'] ?? '')
        ),
        'sslmode' => task_monitoring_database_setting(
            'SUPABASE_DB_SSLMODE',
            (string)($taskMonitoringLocalConfig['sslmode'] ?? 'require')
        ),
    ];

    $taskMonitoringRequiredSettings = [
        'host' => 'SUPABASE_DB_HOST',
        'username' => 'SUPABASE_DB_USER',
        'password' => 'SUPABASE_DB_PASSWORD',
    ];
    $taskMonitoringMissingSettings = [];

    foreach ($taskMonitoringRequiredSettings as $taskMonitoringKey => $taskMonitoringSettingName) {
        if ($taskMonitoringConfig[$taskMonitoringKey] === '') {
            $taskMonitoringMissingSettings[] = $taskMonitoringSettingName;
        }
    }

    if ($taskMonitoringMissingSettings !== []) {
        throw new RuntimeException(
            'Missing database settings: ' . implode(', ', $taskMonitoringMissingSettings) . '.'
        );
    }

    $taskMonitoringAllowedSslModes = ['disable', 'allow', 'prefer', 'require', 'verify-ca', 'verify-full'];

    if (!in_array($taskMonitoringConfig['sslmode'], $taskMonitoringAllowedSslModes, true)) {
        throw new RuntimeException('SUPABASE_DB_SSLMODE contains an unsupported value.');
    }

    $taskMonitoringPort = filter_var(
        $taskMonitoringConfig['port'],
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 65535]]
    );

    if ($taskMonitoringPort === false) {
        throw new RuntimeException('SUPABASE_DB_PORT must be a valid port number.');
    }

    $taskMonitoringDsn = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
        $taskMonitoringConfig['host'],
        $taskMonitoringPort,
        $taskMonitoringConfig['database'],
        $taskMonitoringConfig['sslmode']
    );

    try {
        $taskMonitoringConnection = new PDO(
            $taskMonitoringDsn,
            $taskMonitoringConfig['username'],
            $taskMonitoringConfig['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]
        );
    } catch (PDOException $taskMonitoringException) {
        throw new RuntimeException(
            'Unable to connect to the Supabase database. Check the private database configuration.',
            0,
            $taskMonitoringException
        );
    }

    return $taskMonitoringConnection;
}

/**
 * Prefer a server environment variable and fall back to the ignored local file.
 */
function task_monitoring_database_setting(string $settingName, string $fallback): string
{
    $settingValue = getenv($settingName);

    if ($settingValue === false || trim($settingValue) === '') {
        return trim($fallback);
    }

    return trim($settingValue);
}

