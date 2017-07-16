<?php

class Application_Model_ConnectionYgl
{
    private $dbConn;
    public $xml1;
    public $xml2;
    public $currencies = array("USD","EUR","JPY", "BGN", "CZK", "DKK", "GBP", 
    "LTL", "LVL", "PLN", "RON", "SEK", "CHF", "HRK", 
     "RUB", "CAD", "PHP", "NZD", "THB", "RSD");


    public function getData() {

        $string1 = "";
        $string2 = "";

        $length = count($this->currencies);
        for ($i = 0; $i < $length; $i++)
        {
            for ($j = 0; $j < $length/2; $j++)
            {
                 $string1 .= "'".$this->currencies[$i]."".$this->currencies[$j]."',";
            }
        }

        for ($i = 0; $i < $length; $i++)
        {
            for ($j = $length/2; $j < $length; $j++)
            {
                 $string2 .= "'".$this->currencies[$i]."".$this->currencies[$j]."',";
            }
        }
        
        $string1 = substr($string1, 0, -1);
        $this->xml1 = simplexml_load_file("http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(".$string1.")&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys");
        //var_dump($xml1);
        
        $string2 = substr($string2, 0, -1);
        $this->xml2 = simplexml_load_file("http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(".$string2.")&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys");
        $this->addRatesToDatabase();
    }


    private function addRatesToDatabase()
    {
        $this->dbConn = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => 'localhost',
            'username' => 'root',
            'password' => '0000',
            'dbname'   => 'CurrencyConverter'
        ));

        $d = $this->dbConn->delete("CurrencyRates");

        for ($i = 0; $i < 200; $i++)
        {
            $from = explode("/",$this->xml1->results[0]->rate[$i]->Name)[0];
            $to = explode("/",$this->xml1->results[0]->rate[$i]->Name)[1];
            $rate = $this->xml1->results[0]->rate[$i]->Rate;
           
            $data = array(
                'Currency_In' => $from,
                'Currency_Out' => $to,
                'Rate' => $rate
            );
            
            $this->dbConn->insert('CurrencyRates', $data);

            $from1 = explode("/",$this->xml2->results[0]->rate[$i]->Name)[0];
            $to1 = explode("/",$this->xml2->results[0]->rate[$i]->Name)[1];
            $rate1 = $this->xml2->results[0]->rate[$i]->Rate;
           
            $data1 = array(
                'Currency_In' => $from1,
                'Currency_Out' => $to1,
                'Rate' => $rate1
            );
            
            $this->dbConn->insert('CurrencyRates', $data1);
        }
        
    }

}