<?php
class Database {
    private static $database_name = 'projeto_final_pw2_timeto';
    private static $database_host = '127.0.0.1';
    private static $database_user = 'jackson';
    private static $database_user_password = '!123ABCabc';

    private static $connection_status = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect() {
        if(self::$connection_status == null)
        try {
           self::$connection_status = new PDO('mysql:host='.self::$database_host.';port=3306;dbname='.self::$database_name.'', self::$database_user, self::$database_user_password);
            
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return self::$connection_status;
    }

    public static function disconnect() {
        self::$connection_status = null;
    }
}

?>