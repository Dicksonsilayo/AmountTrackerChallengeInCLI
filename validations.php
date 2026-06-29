<?php
function validateDescription($description){
    if(empty(trim($description))){
        return false;
    }
    return true;


}
function validateAmount($amount){

    if ($amount===null && $amount===''){
        echo "amout must not be null";
        return false;
        
    }
    elseif(!is_numeric($amount)){
        echo "must be number";
        return false;
       

        }
         elseif($amount < 0){ 
            echo "invalid amount should be greater than zero";
            return false;
    }
    else 
        return true;
    
}