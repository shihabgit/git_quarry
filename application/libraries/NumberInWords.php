<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class NumberInWords
{
function amount_in_text($amount){  
    
    // Added by shihab.
    $sign = ($amount < 0) ? 'Negative ':'';
    $amount = abs($amount);
            
    $div_crore = 10000000; // this is how you define parts of the number  
    $div_lac = 100000;  
    $div_thousand = 1000;  
    $div_hundred = 100;  
    $text_amount = ""; // this variable will hold the words  
    $length = strlen($amount); //get the initial length of the amount  
    if($length>=8){  
        $crore = floor($amount/$div_crore);  
        $amount = $amount % $div_crore; // amount is being updated  
        if(strlen($crore)>=3){  
            //if crore figure is bigger than 99, a recursive call is made  
            $text_amount = $text_amount.' '.$this->amount_in_text($crore).' Crore';  
        }  
        else{  
            // this part will be sent to digit_replacer for conversion  
            $text_amount = $text_amount.' '.$this->digit_replacer($crore).' Crore';  
        }  
        $length = strlen($amount);  
    }  
    if($length>=6){  
        $lac = floor($amount/$div_lac);  
        $amount = $amount % $div_lac;  
        $text_amount = $text_amount.' '.$this->digit_replacer($lac).' Lac';  
        $length = strlen($amount);  
    }  
    if($length>=4){  
        $thousand = floor($amount/$div_thousand);  
        $amount = $amount % $div_thousand;  
        $text_amount = $text_amount.' '.$this->digit_replacer($thousand).' Thousand';  
        $length = strlen($amount);  
    }  
    if($length==3){  
        $hundred = floor($amount/$div_hundred);  
        $amount = $amount % $div_hundred;  
        $text_amount = $text_amount.' '.$this->digit_replacer($hundred).' Hundred';  
        $length = strlen($amount);  
    }  
    if($length<=2){  
        $text_amount = $text_amount.' '.$this->digit_replacer($amount);  
    }  
  
    // Hide by shihab
    //return $text_amount;  
    
    // Add by shihab
    return $sign.$text_amount;  
}  
  
function digit_replacer($number){  
    $len = strlen($number);  
  
    $digits = array();  
    $digits[1]="One";  
    $digits[2]="Two";  
    $digits[3]="Three";  
    $digits[4]="Four";  
    $digits[5]="Five";  
    $digits[6]="Six";  
    $digits[7]="Seven";  
    $digits[8]="Eight";  
    $digits[9]="Nine";  
    $digits[10]="Ten";  
    $digits[11]="Eleven";  
    $digits[12]="Twelve";  
    $digits[13]="Thirteen";  
    $digits[14]="Fourteen";  
    $digits[15]="Fifteen";  
    $digits[16]="Sixteen";  
    $digits[17]="Seventeen";  
    $digits[18]="Eighteen";  
    $digits[19]="Nineteen";  
    $digits[20]="Twenty";  
    $digits[30]="Thirty";  
    $digits[40]="Forty";  
    $digits[50]="Fifty";  
    $digits[60]="Sixty";  
    $digits[70]="Seventy";  
    $digits[80]="Eighty";  
    $digits[90]="Ninety"; 
    
    // Added by shihab.
    $digitext = '';
    
    if($len==1){  
        if(isset($digits[$number]))// Added by shihab.
            $digitext = $digits[$number];  
    }  
  
    else if($len==2){  
        if($number<=20){  
            if(isset($digits[$number]))// Added by shihab.
                $digitext = $digits[$number];  
        }  
        else{  
            $oneth_pos = $number % 10;  
            $tenth_pos = floor($number/10)*10;  
            $digitext = $digits[$tenth_pos].' '.$digits[$oneth_pos];  
        }  
    }  
  
    return $digitext;  
}  

    
 
}

?>