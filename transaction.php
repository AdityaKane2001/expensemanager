<?php
require_once 'pdo.php';


if(isset($_GET['tid'])){
  $tid = $_GET['tid'];
  $stmt = $pdo->prepare('SELECT * FROM transactions WHERE transaction_id=:tid');
  $stmt->execute(array(':tid'=>$_GET['tid']));
  $transaction = $stmt->fetch();

  if($transaction['type']==1){
      $amount_stmt = $pdo->prepare('SELECT * FROM income where transaction_id=:tid');
      $amount_stmt->execute(array(':tid'=>$transaction['transaction_id']));
      $amount = $amount_stmt->fetch()['income_amount'];
    }
  else{
    $amount_stmt = $pdo->prepare('SELECT * FROM expense where transaction_id=:tid');
    $amount_stmt->execute(array(':tid'=>$transaction['transaction_id']));
    $amount = $amount_stmt->fetch()['expense_amount'];
  }

  if($transaction['type']==1){
    $amount_str = "<p style='color:green;font-weight:bold;'>+".$amount."</p>";
    $type ="<p style='color:green;font-weight:bold;'>Income</p>";
  }
  else{
  $amount_str = "<p style='color:red;font-weight:bold;'>-".$amount."</p>";
  $type = "<p style='color:red;font-weight:bold;'>Expense</p>";
  }

  $curr_stmt = $pdo->prepare('SELECT * FROM current_balance WHERE transaction_id=:tid');
  $curr_stmt->execute(array(':tid'=>$tid));
  $curr_row = $curr_stmt->fetchAll();

  $amounts=array('gpay_bal'=>0,'cash_bal'=>0);

  $amounts[$curr_row[0]['account'].'_bal'] = $curr_row[0]['amount'];
  $amounts[$curr_row[1]['account'].'_bal'] = $curr_row[1]['amount'];




}




 ?>


 <!doctype html>
 <html lang="en">
   <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">


     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

     <title>Transaction Details</title>
   </head>
   <body>
     <div class="container">


    <p class='lead'><h1>   Transaction ID <?= $tid ?></h1> </p>  <br>
      <p> <a href="index.php" style="text-decoration:none;">Back to Home</a> </p>
      <table class='table table-bordered table-responsive'>


        <tr>
          <th> Account </th>
          <td colspan="2"> <?= $transaction['transaction_from']; ?> </td>
        </tr>

        <tr>
          <th> Type </th>
          <td  colspan="2"> <?= $type ?> </td>
        </tr>

        <tr>
          <th>Amount</th>
          <td  colspan="2"> <?= $amount_str ?> </td>
        </tr>

        <tr>
          <th> Time of transaction</th>
          <td  colspan="2"> <?= $transaction['transaction_time'] ?> </td>
        </tr>

        <tr>
          <th>Balance after transaction</th>

          <td> <b>Gpay: </b> <?= $amounts['gpay_bal'] ?> </td>
          <td> <b>Cash: </b> <?= $amounts['cash_bal'] ?> </td>
        </tr>



      </table>


   </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>


   </body>
 </html>
