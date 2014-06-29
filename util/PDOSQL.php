<?php

/**
 * Static class for PDO-MySQL implementations
 */
final class PDOSQL {
    
    /**
     * Connects to MySQL via PDO
     * @return \PDO
     */
    public static function Connect() {
        $pdo_config = parse_ini_file('config/database.ini');
        $dsn = 'mysql:host=' . $pdo_config['host'] . ';dbname=' . $pdo_config['db'] . ';port=' . $pdo_config['port'];
        $pdo = new PDO($dsn, $pdo_config['user'], $pdo_config['password']);
        return $pdo;
    }
    
    public static function __GetDriverInfo() {
        return pdo_drivers();
    }
    
}

?>