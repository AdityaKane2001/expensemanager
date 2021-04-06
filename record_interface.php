<?php
require_once 'pdo.php';

function get_balances($rows){
  $balances = array('gpay'=>0,'cash'=>0);
  if($rows[0]['account']=='gpay'){
    $balances['gpay']=$rows[0]['amount'];
    $balances['cash']=$rows[1]['amount'];
  }
  else{
    $balances['gpay']=$rows[1]['amount'];
    $balances['cash']=$rows[0]['amount'];
  }
  return ($balances);
}



function print_table(){
  include 'pdo.php';
  $stmt = $pdo->prepare('SELECT * FROM transactions ORDER By transaction_id DESC');
  $stmt->execute();
  $rows = $stmt->fetchAll();
  if($rows){
  echo('  <table class="table table-bordered table-responsive">
      <tr>
        <th>Transaction ID</th>
        <th>From</th>
        <th>To</th>
        <th>Amount</th>
        <th colspan="2">Balance after transaction</th>
        <th style="widht:10%;"></th>
      </tr>');




  foreach ($rows as $entry ) {
    $trans_id_stmt = $pdo->prepare('SELECT * FROM transactions where transaction_id=:tid');
    $trans_id_stmt->execute(array(':tid'=>$entry['transaction_id']));
    $transaction_row = $trans_id_stmt->fetch();

    if($entry['type']==1){
        $amount_stmt = $pdo->prepare('SELECT * FROM income where transaction_id=:tid');
        $amount_stmt->execute(array(':tid'=>$entry['transaction_id']));
        $row =  $amount_stmt->fetch();
        $amount = $row['income_amount'];
        $account = $transaction_row['transaction_to'];


      }
    else{
      $amount_stmt = $pdo->prepare('SELECT * FROM expense where transaction_id=:tid');
      $amount_stmt->execute(array(':tid'=>$entry['transaction_id']));
      $amount = $amount_stmt->fetch( )['expense_amount'];
      $account = $transaction_row['transaction_from'];
    }

    if($entry['type']==1){
      $amount_str = "<p style='color:green;font-weight:bold;'>+".$amount."</p>";
    }
    else{


    $amount_str = "<p style='color:red;font-weight:bold;'>-".$amount."</p>";
    }

    $curr_stmt = $pdo->prepare('SELECT * FROM current_balance WHERE transaction_id=:tid');
    $curr_stmt->execute(array(':tid'=>$entry["transaction_id"]));
    $curr_rows = $curr_stmt->fetchAll();
    $balance_after = get_balances($curr_rows);

     echo('<tr>
      <td> <a style="text-decoration:none;" href="transaction.php?tid='.urlencode($entry["transaction_id"]).'">'.$entry["transaction_id"].'</a> </td>
       <td>'.$entry['transaction_from'].'</td>
       <td>'.$entry['transaction_to'].'</td>
       <td>'.$amount_str.'</td>
       <td style="width:20%;"><b>Gpay: </b>'.$balance_after["gpay"].'</td>
       <td style="width:20%;"><b>Cash: </b>'.$balance_after["cash"].'</td>
        <td style="widht:10%;"> <button class="btn btn-outline-danger"  onclick = "delete_entry('.$entry["transaction_id"].');" >Delete</button> </td>
     </tr>');




  }

  echo('</table>');
}

else{
  echo("<h2> No transactions found. </h2>");
}
}

if(isset($_POST['type'])){
  if($_POST['type']=='refresh'){
    print_table();
  }

  if ($_POST['type']=='delete') {
    $del_stmt = $pdo->prepare('DELETE FROM transactions WHERE transaction_id=:tid');
    $del_stmt->execute(array(':tid'=>$_POST['tid']));

    print_table();
  }

  unset($_POST['type']);


}


 ?>
