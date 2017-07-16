<?php

class IndexController extends Zend_Controller_Action
{
    public $db;
    public $currencies = array("USD","EUR","JPY", "BGN", "CZK", "DKK", "GBP", 
    "LTL", "LVL", "PLN", "RON", "SEK", "CHF", "HRK", 
     "RUB", "CAD", "PHP", "NZD", "THB", "RSD");

    public function init()
    {
         $ajaxContext = $this->_helper->getHelper('AjaxContext');
         $ajaxContext->addActionContext('getevents', array('json', 'html'))->initContext();
    }

    public function indexAction()
    {
        $this->getResponse()->setHeader('Expires', '2018-01-22', true);
        $this->getResponse()->setHeader('Cache-Control', 'public', true);
        $this->getResponse()->setHeader('Cache-Control', 'max-age=3800');
        $this->getResponse()->setHeader('Pragma', '', true);
        $this->db = new Application_Model_Currency();//Application_Model_Currency::getInstance();
        $this->db->connect();
        $this->db->getHistory();
        $this->view->currencies = $this->currencies;
    }
    
    public function calculateAction()
    {
      //  $this->getResponse()->setHeader('Expires', new DateTime('2018-01-22'), true);
        $this->getResponse()->setHeader('Cache-Control', 'public', true);
        $this->getResponse()->setHeader('Cache-Control', 'max-age=3800');
        $this->getResponse()->setHeader('Pragma', '', true);

        $db = new Application_Model_Currency();
        $from = $this->getRequest()->getParams()['from'];
        $to = $this->getRequest()->getParams()['to'];
        $amount = $this->getRequest()->getParams()['amount'];
        $db->addNewConversion($amount, $from, $to);

        $result = array(
          'conversion' => $db->getConversion($amount, $from, $to),
          'history' => $db->getHistory()
        );
        
        $this->_helper->json($result); 
    }    

    
    public function getxmlratesAction()
    {
        $a = new Application_Model_ConnectionYgl();
        $a->getData();

        $results = "";

        $this->_helper->json($results);
    }  

    public function gethistoryAction()
    {
        $db = new Application_Model_Currency();
        
        $result = $db->getHistory();
    
        $this->_helper->json($result);
    }  

    



}

