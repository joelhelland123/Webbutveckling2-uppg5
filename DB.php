<?php
class DB extends PDO
{
    private static $instance;

    private function __construct($dbname)
    {
        try {
            parent::__construct("mysql:host=localhost;dbname=$dbname;charset=utf8", "root", "mysql");
        } catch (Exception $e) {
            echo "<pre>" . print_r($e, 1) . "</pre>";
        }
    }

    public static function getInstance($dbname)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($dbname);
        }
        return self::$instance;
    }
}
