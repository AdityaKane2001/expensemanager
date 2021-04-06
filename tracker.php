<?php
include 'pdo.php';

function get_balance(){
  include 'pdo.php';
  $get_records_stmt = $pdo->prepare('SELECT account,amount FROM current_balance ORDER BY transaction_id DESC LIMIT 2');
  $get_records_stmt->execute();
  $last_rows = $get_records_stmt->fetchAll();

  if($last_rows){
  //print_r($last_rows);
  $amounts = array($last_rows[0]['account']=>$last_rows[0]['amount'],$last_rows[1]['account']=>$last_rows[1]['amount']);
  return($amounts);}
  else{
    return(array('gpay'=>0,'cash'=>0));
  }
}

function insert_current($amount, $tid ){
  include 'pdo.php';
  $checker = $pdo->prepare('SELECT * FROM current_balance');
  $checker->execute();
  $curr_rows = $checker->fetchAll();

  $trans_stmt = $pdo->prepare('SELECT * from transactions where transaction_id=:tid');
  $trans_stmt->execute(array(':tid'=>$tid));
  $trans_row = $trans_stmt->fetch();

  $type = intval($trans_row['type']);
  $from = $trans_row['transaction_from'];
  $to = $trans_row['transaction_to'];

  if($curr_rows){

      $get_records_stmt = $pdo->prepare('SELECT account,amount FROM current_balance ORDER BY transaction_id DESC LIMIT 2');
      $get_records_stmt->execute();
      $last_rows = $get_records_stmt->fetchAll();
      //print_r($last_rows);
      $amounts = array($last_rows[0]['account']=>$last_rows[0]['amount'],$last_rows[1]['account']=>$last_rows[1]['amount']);

      $new_amounts = array($last_rows[0]['account']=>$last_rows[0]['amount'],$last_rows[1]['account']=>$last_rows[1]['amount']);

      if($type==1){
        $new_amounts[$to] = $new_amounts[$to] + $amount;
      }
      else if($type==-1){
        $new_amounts[$from] = $new_amounts[$from] - $amount;
      }


      foreach ($new_amounts as $key => $value) {
        $add_record_stmt = $pdo->prepare('INSERT INTO current_balance(account,	amount,	transaction_id) VALUES (:act,:amt,:tid)');
        $add_record_stmt->execute(array(':act'=>$key,':amt'=>$value,':tid'=>$tid));
      }


  }
  else {
    $new_amounts = array('gpay'=>0,'cash'=>0);
    if($type==1){
    $new_amounts[$to] += $amount;}
    else{
      $new_amounts[$from] -= $amount;
    }
  //  echo $amount;
    //print_r($new_amounts);

    foreach ($new_amounts as $key => $value) {
      $add_record_stmt = $pdo->prepare('INSERT INTO current_balance(account,	amount,	transaction_id) VALUES (:act,:amt,:tid)');
      $to_exec=array(':act'=>$key,':amt'=>$value,':tid'=>$tid);
      //print_r($to_exec);
      $add_record_stmt->execute($to_exec);
    }


  }



}


if(isset($_POST['type'])){

  if($_POST['type']=='income'){
    $amount = $_POST['amount'];
    $GLOBALS['amount'] = $amount;
    $mode = $_POST['mode'];
    $from = $_POST['from'];
    if($mode=='acc_transfer'){
      $to = 'gpay';
    }
    else{
      $to='cash';
    }

    $curtime = time()+(210 * 60);
    $newtime = date('Y-m-d H:i:s',$curtime);

    $stmt=$pdo->prepare('INSERT INTO transactions(transaction_from,	transaction_to,	transaction_time,	type) values (:mode,:to,:trans_time,1)');
    $stmt->execute(array(':mode'=>$from,':to'=>$to,':trans_time'=>$newtime ));
    $last_ins_id=$pdo->lastInsertId();
    $GLOBALS['last_ins_id'] = $last_ins_id;

    $income_stmt = $pdo->prepare('INSERT INTO income VALUES (:mode,:amount,:trans_id)');
    $income_stmt->execute(array(':mode'=>$from,':amount'=>$amount,':trans_id'=>$last_ins_id));


insert_current($GLOBALS['amount'],$GLOBALS['last_ins_id']);

    echo "<p style='color:green;'>Income recorded,transaction id:". '<a style="text-decoration:none;" href="transaction.php?tid='.urlencode($last_ins_id).'">'.strval($last_ins_id).'</a>' ."</p>";


  }
  elseif ($_POST['type']=='expense') {

  $amount = $_POST['amount'];


  $GLOBALS['amount'] = $amount;

  if($amount==-1){
    $from = $_POST['account'];
    $to = $_POST['details'];
    $stmt=$pdo->prepare('INSERT INTO transactions(transaction_from,	transaction_to,	transaction_time,	type) values (:fromm,:to,:trans_time,-1)');

    $curtime = time()+(210 * 60);
    $newtime = date('Y-m-d H:i:s',$curtime);


    $stmt->execute(array(':fromm'=>$from,':to'=>$to,':trans_time'=>$newtime));
    $last_ins_id=$pdo->lastInsertId();
    $GLOBALS['last_ins_id'] = $last_ins_id;
  $balances = get_balance();
    $expense_stmt = $pdo->prepare('INSERT INTO expense(transaction_id,	expense_details,	expense_amount) VALUES (:trans_id,:details,:amount)');
    $expense_stmt->execute(array(':details'=>strval($from.' to '.$to),':amount'=>$balances[$from],':trans_id'=>$last_ins_id));
    insert_current($balances[$from],$GLOBALS['last_ins_id']);
    echo "<p style='color:green;'>Expense recorded,transaction_id:". '<a style="text-decoration:none;" href="transaction.php?tid='.urlencode($last_ins_id).'">'.strval($last_ins_id).'</a>' ."</p>";


  }
  else{
    $from = $_POST['account'];
    $to = $_POST['details'];
    $stmt=$pdo->prepare('INSERT INTO transactions(transaction_from,	transaction_to,	transaction_time,	type) values (:fromm,:to,:trans_time,-1)');

    $curtime = time()+(270 * 60);
    $newtime = date('Y-m-d H:i:s',$curtime);


    $stmt->execute(array(':fromm'=>$from,':to'=>$to,':trans_time'=>$newtime));
    $last_ins_id=$pdo->lastInsertId();
    $GLOBALS['last_ins_id'] = $last_ins_id;

    $expense_stmt = $pdo->prepare('INSERT INTO expense(transaction_id,	expense_details,	expense_amount) VALUES (:trans_id,:details,:amount)');
    $expense_stmt->execute(array(':details'=>strval($from.' to '.$to),':amount'=>$amount,':trans_id'=>$last_ins_id));
    insert_current($GLOBALS['amount'],$GLOBALS['last_ins_id']);
    echo "<p style='color:green;'>Expense recorded,transaction_id:". '<a style="text-decoration:none;" href="transaction.php?tid='.urlencode($last_ins_id).'">'.strval($last_ins_id).'</a>' ."</p>";
  }
}


  elseif ($_POST['type']=='refresh') {
    $get_records_stmt = $pdo->prepare('SELECT account,amount FROM current_balance ORDER BY transaction_id DESC LIMIT 2');
    $get_records_stmt->execute();
    $last_rows = $get_records_stmt->fetchAll();
    //print_r($last_rows);
    $amounts = array($last_rows[0]['account']=>$last_rows[0]['amount'],$last_rows[1]['account']=>$last_rows[1]['amount']);
    echo json_encode($amounts);
  }
  else{
    echo "Request Invalid";
  }

    unset($_POST['type']);



}

?>
