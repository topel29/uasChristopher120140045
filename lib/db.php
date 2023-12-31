<?php
/*
 * Mysql database class - only one connection alowed
 */
class Database
{
    private $_connection;
    private static $_instance; //The single instance
    private $_host = "localhost";
    private $_username = "root";
    private $_password = "";
    private $_database = "uaspemweb";
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private function __construct()
    {
        $this->_connection = new mysqli($this->_host, $this->_username,
            $this->_password, $this->_database);


        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
                E_USER_ERROR);
        }
    }
    private function __clone()
    {
    }
    public function getConnection()
    {
        return $this->_connection;
    }
}
?>