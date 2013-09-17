<?
/* 
* © Copyright 2013, Miloš Havlíček
* All rights reserved. 
* 
*/ 

class ES24CurrencyValue {
    
    private $symbol;
    private $symbolPosition;
    private $currencyAbbrev;
    private $value;
    private $delimiter;
    private $delimiterDef = '.';
    private $decimalNums;
    private $decimalNumsDef = 2;
    private $decimalRound;
    private $decimalRoundDef = 2;
    private $symbolShow;
    private $symbolShowDef = false;
    private $decimalPrecisionPlus;
    private $decimalPrecisionPlusDef = 0;
    private $thousandsSep;
    private $thousandsSepDef = '';
    private $strikeDecimal;
    private $strikeDecimalDef = false;
    
    public function __construct()
    {
        $this->symbol = null;
        $this->symbolPosition = null;
        $this->currencyAbbrev = null;
        $this->value = null;
        
        $this->delimiter = $this->delimiterDef;
        $this->decimalNums = $this->decimalNumsDef;
        $this->decimalRound = $this->decimalRoundDef;
        $this->symbolShow = $this->symbolShowDef;
        $this->decimalPrecisionPlus = $this->decimalPrecisionPlusDef;
        $this->thousandsSep = $this->thousandsSepDef;
        $this->strikeDecimal = $this->strikeDecimalDef;
    }
    
    public function setMaxDec()
    { // Set maximal precision for value (equals to number of decimal numbers)
        $this->decimalRound = $this->decimalNums;
    }
    
    public function setThousandsSep($in)
    {
        $this->thousandsSep = (string)$in;
    }
    
    public function setValue($in)
    {
        $in = $this->inValueToFloat($in);
            
        $this->value = (float)$in;
    }
    
    private function inValueToFloat($in)
    {
        $out = $in;
        $out = str_replace(',','.',$out);
        $out = str_replace(' ','',$out);
    
        return (float)$out;
    }
    
    public function getValue()
    {
        return (float)$this->value;
    }
    
    public function setCurrencyAbbrev($in)
    {
        $t = strtoupper((string)$in);
        
        $this->currencyAbbrev = $t;
        
        if($t=='EUR')
        {
            $this->symbol = '€';
            $this->symbolPosition = 'after';
            $this->delimiter = '.';
            $this->decimalNums = 2;
            $this->decimalRound = 2;
            
        }
        elseif($t=='CZK')
        {
            $this->symbol = 'Kč';
            $this->symbolPosition = 'after';
            $this->delimiter = ',';
            $this->decimalNums = 2;
            $this->decimalRound = 0;
        }
        else
        {
            $this->symbol = null;
            $this->symbolPosition = null;
            $this->delimiter = $this->delimiterDef;
            $this->decimalNums = $this->decimalNumsDef;
            $this->decimalRound = $this->decimalRoundDef;
        }
        
        $this->symbolShow = $this->symbolShowDef;
    }
    
    public function setDelimiter($in)
    {
        $this->delimiter = (string)$in;
    }
    
    public function setDecimalPrecisionPlus($in)
    {
        if(!is_numeric($in) OR $in<0)
        {
            die('err');
        }
        else
        {
            $in = floor($in);
            $this->decimalPrecisionPlus = (int)$in;
        }
    }
    
    public function getDecimalPrecionPlus()
    {
        return $this->decimalPrecisionPlus;
    }
    
    public function getDecimalNums()
    {
        return $this->countDecimal('nums');
    }
    
    public function getDecimalRound()
    {
        return $this->countDecimal('round');
    }
    
    private function countDecimal($in)
    { 
        if($in=='round' || $in=='nums');
        else
            return null;
        
        if($this->decimalPrecisionPlus>0)
        {
            if($this->decimalNums<$this->decimalRound)
            {
                $position = 1;
                $valLower = $this->decimalNums;
                $valHigher = $this->decimalRound;
            }
            else
            { // also if equals
                $position = 2;
                $valLower = $this->decimalRound;
                $valHigher = $this->decimalNums;
            }
            
            $valLowerAfter = $valLower + $this->decimalPrecisionPlus;
            
            if($valHigher<$valLowerAfter)
            { 
                if($position==1)
                { 
                    if($in=='round')
                        return $valHigher;
                    elseif($in=='nums')
                        return $valLowerAfter;
                }
                elseif($position==2)
                { 
                    return $valLowerAfter; // the same as return $valHigher;
                }
            }
            
            else
            {
                if($position==1)
                {
                    if($in=='nums')
                        return $valHigher;
                    elseif($in=='round')
                        return $valLowerAfter;
                }
                elseif($position==2)
                {
                    return $valLowerAfter; // the same as return $valHigher;
                }
            }
            
            return null; // for certainty only
        }
        else
        {
            if($in=='nums')
                return $this->decimalNums;
            elseif($in=='round')
                return $this->decimalRound;
        }
    }
    
    public function getFormatted()
    {
        $out = null;
        
        // Get value
        if($this->value!==null)
        {
            $out = $this->value;
        }
        
        // Round
        if($this->decimalRound >= 0)
        {
            if($this->getDecimalRound()>$this->getDecimalNums())
            {
                $out = round($out,$this->getDecimalNums()); // for certainty only
            }
            else
            {
                $out = round($out,$this->getDecimalRound());
            }
        }
        
        // Strike decimal if required
        $strikeRequired = false;
        if($this->strikeDecimal === true)
        {
            if(round($out) == $out)
            {
                $out = round($out); // no decimal value
                $strikeRequired = true;
            }
        }
        
        // Set number format
            // Set count of decimal nums
            $p = array();
            if($this->decimalNums >= 0)
            {
                $p[0] = $this->getDecimalNums();
            }
            else
            {
                $p[0] = 0;
            }
        
            // Set decimal delimiter
            if($this->delimiter!==null)
            {
                $p[1] = $this->delimiter;
            }
            else
            {
                $p[1] = $this->delimiterDef;
            }
            
            // Set thousands separator
            if($this->thousandsSep!==null)
            {
                $p[2] = $this->thousandsSep;
            }
            else
            {
                $p[2] = $this->thousandsSepDef;
            }
            
            if($strikeRequired===true)
            {
                $p[0] = 0;
            }
            
            $out = number_format($out,$p[0],$p[1],$p[2]);
            
            // Strike decimal if required
            if($strikeRequired===true)
            {
                $out = $out.$p[1].'-';
            }
            unset($p);
            
        // Add symbol
        if($this->symbolShow===true)
        {
            if($this->symbol!==null)
            {
                if($this->symbolPosition=='before')
                    $out = $this->symbol.' '.$out;
                elseif($this->symbolPosition=='after' OR $this->symbolPosition===null)
                    $out = $out.' '.$this->symbol;
                else
                    die('err');
            }
        }
        
        return (string)$out;
    }
    
    public function setSymbolShow($in)
    {
        $this->symbolShow = (bool)$in;
    }
    
    public function setStrikeDecimal($in)
    {
        $this->strikeDecimal = (bool)$in;
    }
    
}