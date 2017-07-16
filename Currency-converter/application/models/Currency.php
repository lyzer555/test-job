<?php

class Application_Model_Currency
{
    public $sveBre;
    private static $instance;
    private $dbConn;

    public function __construct()
    {
        $this->connect();
    }

/*
    public static function getInstance(){
        if (self::$instance == null)
        {
            self::$instance = new Application_Model_Currency();
        }
        return self::$instance;
    }*/

    public function connect()
    {
        $this->dbConn = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => 'localhost',
            'username' => 'root',
            'password' => '0000',
            'dbname'   => 'CurrencyConverter'
        ));
    }

    public function getHistory()
    {
        $sql = 'SELECT * FROM ConversionHistory ORDER BY id DESC LIMIT 5';
        $result = $this->dbConn->fetchAll($sql, 2);
        return $result;
    }

    public function addNewConversion($amount, $currency_in, $currency_out)
    {    
        $data = array(
            'Amount' => $amount,
            'Currency_in' => $currency_in,
            'Currency_out' => $currency_out,
            'Result' => $this->getConversion($amount, $currency_in, $currency_out),
            'DateC' => date("Y/m/d")
        );
        $this->dbConn->insert('ConversionHistory', $data);
    }

    public function getConversion($amount, $currency_in, $currency_out)
    {
        $sql = 'SELECT Rate FROM CurrencyRates WHERE Currency_In = "'.$currency_in.'" and Currency_Out = "'.$currency_out.'"';
        $result = $this->dbConn->fetchAll($sql, 2);

        return $result[0]['Rate'] * $amount;
    }

}
