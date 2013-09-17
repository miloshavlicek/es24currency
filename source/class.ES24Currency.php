<?
/* 
* © Copyright 2013, Miloš Havlíček
* All rights reserved. 
* 
*/ 

include_once __DIR__.'/class.ES24CurrencyValue.php';

class ES24Currency {
    
    private $abbreviation;
    private $symbol;
    private $symbolPosition;
    private $ratesIn = array();
    private $ratesOut = array();
    
    public function __construct($abbrev)
    {
        $this->setCurrency($abbrev);
    }
    
    private function setCurrency($abbrev) 
    {
        $this->abbreviation = $abbrev;
        
        $q = dibi::query('
            SELECT `id`,`symbol`,`symbolPosition`
            FROM `shop_currency`
            WHERE `abbrev` = %s
            LIMIT 1
            ',$this->abbreviation);
        $d = $q->fetch();
        
        if(!empty($d))
        {
            $this->symbol = $d->symbol;
            $this->symbolPosition = $d->symbolPosition;
        }
    }
    
    public function getRateIn($currency,$refresh = false)
    {
        return $this->getRate(1,$currency,$refresh);
    }
    
    public function getRateOut($currency,$refresh = false)
    {
        return $this->getRate(2,$currency,$refresh);
    }
    
    public function setRateIn($currency,$value)
    {
        return $this->setRate(1,$currency,$value);
    }
    
    public function setRateOut($currency,$value)
    {
        return $this->setRate(2,$currency,$value);
    }
    
    private function setRate($way,$currency,$value)
    {
        $currency = strtoupper($currency);
        
        if($way==1)
        { // reteIn
            $storedRate =& $this->ratesIn[$currency];
        }
        elseif($way==2)
        { // reteOut
            $storedRate =& $this->ratesOut[$currency];
        }
        else
        {
            die('err');
        }
        
        $storedRate = (real)$value;
    }
    
    private function getRate($way,$currency,$refresh = false)
    { // set $refresh = true for disable lazy mode;
        $currency = strtoupper($currency);
        
        if($way==1)
        { // reteIn
            $currencyA = $currency;
            $currencyB = $this->abbreviation;
            $storedRate =& $this->ratesIn[$currency];
        }
        elseif($way==2)
        { // reteOut
            $currencyA = $this->abbreviation;
            $currencyB = $currency;
            $storedRate =& $this->ratesOut[$currency];
        }
        else
        {
            die('err');
        }
        
        if($refresh === false AND isset($storedRate))
            return $storedRate;
        
        $q = dibi::query('
            SELECT `rate`
            FROM `shop_currency_rate`
            WHERE `from` = %s AND `to` = %s
            LIMIT 1
            ',$currencyA,$currencyB);
        $d = $q->fetch();
        
        if(!empty($d))
        {
            if($d->rate!==null)
            {
                $storedRate = $d->rate;
                return $storedRate;
            }
        }
        
        $storedRate = null;
        return $storedRate;
    }
    
}