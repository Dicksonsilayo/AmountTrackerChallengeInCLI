<?php
// require_once 'validations.php';
class ExpenseTracker{
private $file="storage.json";
public function __construct()
{
   if(!file_exists($this->file)){
    $data=file_put_contents($this->file,json_encode([],JSON_PRETTY_PRINT));
   }
}
private function readExpense(){
    $data=file_get_contents($this->file);
    return json_decode($data,true);

}
private function saveExpense($expenses){
    
file_put_contents($this->file,json_encode($expenses,JSON_PRETTY_PRINT));
}
private function getNextExpense($expenses){
    if(empty($expenses)){
        return 1;

    }
    $ids=array_column($expenses,'id');
    if(empty($ids)){
        return 1;
    }
    return max(array_column($expenses,'id'))+1;

}
public function addExpense($description,$amount){
    $expenses=$this->readExpense();
$expense=[
    'id'=>$this->getNextExpense($expenses),
    'date'=>date('Y-m-d H:i:s'),
    'description'=>$description,
    'expense'=>(float)$amount,
];
$expenses[]=$expense;
$this->saveExpense($expenses);
echo "new expense with {$expense['id']} has been saved successfully";

}
public function updateExpense($id,$description,$amount)
{
    $expenses=$this->readExpense();
    foreach($expenses as $expense){
        if($expense['id']==$id){
            $expense['description']=$description;
            $expense['expense']=(float)$amount;
           $this->saveExpense($expense);
           echo " the expense with {id} has been updated successfully";
           return;

            
        }
    }

    }


public function deleteExpense($id){
    $expenses=$this->readExpense();
    foreach($expenses as $keys=>$expense){
if($expense['id']==$id){
    unset($expenses[$keys]);
    $this->saveExpense($expenses);
    echo "the expense has been deleted successfully";
    return;

}
    }


}
public function viewAllExpenses(){
    $expenses=$this->readExpense();
      echo "belew are list of all the expenses \n \n";
    foreach($expenses as $expense){
      
        echo "'id' => {$expense['id']} \n";
         echo "'date' => {$expense['date']} \n";
          echo "'description' => {$expense['description']} \n";
           echo "'amount' => {$expense['amount']} \n";
           echo"\n\n-------------------\n\n";
    }

}
public function getmonthlyExpenses($month,$year){
    $expenses=$this->readExpense();
    $total=0;
    $filtered=[];

    
   foreach($expenses as $expense){
    $expenseDate=strtotime($expense['date']);
    $expenseMonth=date('m',$expenseDate);
    $expenseYear=date('Y',$expenseDate);
if($expenseMonth==$month && $expenseYear==$year){
    $filtered=$expense;
    $total+=$expense['expense'];

}

   }
echo "this monthly total moth expenses is {$total} ";

}

public function filteredExpenses($startDate,$endDate=null){
    $expenses=$this->readExpense();
  
        $start=strtotime($startDate);
        $end=$endDate ? strtotime($endDate):$start;
        $found=false;
        $total=0;
        foreach($expenses as $expense){
            $expenseTime=strtotime($expense['date']);
            if($expenseTime >= $start && $expenseTime<=$end){
            $found=true;
            $total+=$expense['expense'];
           

            }
             echo " id {$expense['id']} \n";
              echo " expense {$expense['expense']} \n";
              echo " date {$expense['date']} \n \n \n ";
        



    }
    echo "the total expenses from {$startDate} to {$endDate} is \t\t {$total}\n ";


}
}

$tracker=new ExpenseTracker;
$command = $argv['1']?? null;
switch($command){
    case "add":
          if(!isset($argv[2])){
                    echo "usage php expenses.php add 'description' 'expense' ";
                }
        $tracker->addExpense($argv[2],$argv[3]);
        exit;
        case "update":
              if(!isset($argv[2])){
                    echo "usage php expenses.php update 'id to update' 'new description' 'new expense'";
                }
            $tracker->updateExpense($argv[1],$argv[2],$argv[3]);
            exit();
            case "delete":
                if(!isset($argv[2])){
                    echo "usage php expenses.php delete 'id to delete eg 1'";
                }
                $tracker->deleteExpense($argv[2]);
                exit;
                case "viewAll":
                    $tracker->viewAllExpenses($argv[2]);
                    exit;
                    case "month":
                        if(!isset($argv[2])){
                            echo "usage : php expenses.php month '07'\n";
                        }
                        $tracker->getmonthlyExpenses($argv[2],$argv[3]);
                        exit;
case "filterFrom":
    if(!isset($argv[2])){
        echo "usage :php expenses.php viewAll \n";
    }
    $tracker->filteredExpenses($argv[2],$argv[3]);
    exit;
    default:
    echo "invalid command";

}
?>