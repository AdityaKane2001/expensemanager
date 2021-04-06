<?php
require_once 'pdo.php';

$curr_stmt = $pdo->prepare('SELECT * FROM current_balance ORDER BY transaction_id DESC LIMIT 2');
$curr_stmt->execute();
$curr_row = $curr_stmt->fetchAll();

$amounts=array('gpay_bal'=>0,'cash_bal'=>0);
if($curr_row){
$amounts[$curr_row[0]['account'].'_bal'] = $curr_row[0]['amount'];
$amounts[$curr_row[1]['account'].'_bal'] = $curr_row[1]['amount'];
}
else{
  $amounts['gpay_bal'] = 0;
  $amounts['cash_bal'] = 0;

}

?>

<html lang="en">
<style media="screen">
table {
  table-layout: fixed;
  word-wrap: break-word;
}
</style>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <title>Expense Tracker</title>
  </head>
  <body >
    <div class="container">
      <div style='text-align:center;'>
          <p class='lead' >  <h1>   Expense Tracker</h1> </p><br>
      </div>


  <button class="btn btn-light" type="button" name="button"> <a href="record.php" style="text-decoration:none;">Past Records</a> </button><br><br>
    <div class="row">
      <div class="col-md-6">


  <div class="card">
  <div class="card-header">
  Income
  </div>
  <div class="card-body">
    <div id='istatus'> <br> </div>
    <div class="row">
      <div class="col-md">

        <label class="col-form-label" > Amount</label> <br><br>
        <label  class="col-form-label"> From  </label> <br><br>
        <label  class="col-form-label"> Mode  </label> <br><br>
      </div>
      <div class="col-md">


        <input type="text" class="form-control" name="iamount" id="iamount" value="" placeholder="Enter amount here"> <br>
        <input type="text" class="form-control" name="details" value="" id='from' placeholder="Enter from here"> <br>
        <input hidden type="text" name="mode" value="" id='mode' placeholder="Must be of (acc_transfer,cash)">
        <button type="button" name="mode_gpay" class='btn btn-primary' onclick='document.getElementById("mode").value="acc_transfer";record_income();'>GPay </button>
        <button type="button" name="mode_cash" class='btn btn-primary' onclick='document.getElementById("mode").value="cash";record_income();'>Cash </button><br><br>

      </div>
    </div>
  </div>
</div>



      </div>
      <div class="col-md">



          <div class="card">
          <div class="card-header">
          Expense
          </div>
          <div class="card-body">
            <div id='estatus'> <br> </div>
            <div class="row">
              <div class="col-md">

                <label  class="col-form-label" > Amount</label> <br> <br>
                <label class="col-form-label" > To  </label> <br><br>
                  <label  class="col-form-label" > Account  </label> <br><br>
              </div>
              <div class="col-md">


                <input type="text" class="form-control" name="eamount" id='eamount' value="" placeholder="Enter amount here"><br>
                <input type="text" class="form-control" name="details" value="" id='to' placeholder="Enter to here"><br>
                <input type="text" name="account" value="" id='account' placeholder="Enter account here" hidden>
                <button type="button" name="mode_gpay" class='btn btn-primary' onclick='document.getElementById("account").value="gpay";record_expense();'>GPay </button>
                <button type="button" name="mode_cash" class='btn btn-primary' onclick='document.getElementById("account").value="cash";record_expense();'>Cash </button><br><br>


              </div>
            </div>
          </div>
        </div>





      </div>
    </div>
<br>
    <div style='text-align:center;' class="container">
      <p > <h2>Current Balance</h2> </p>




      <div class="row justify-content-center">
        <div class="col-md-2">
          Gpay :
        </div>
        <div class="col-md-2" id='gpay_bal'>
           <?= $amounts['gpay_bal'] ?>
        </div>

      </div>
      <div class="row justify-content-center">
        <div class="col-md-2">
          Cash :
        </div>
        <div id='cash_bal' class="col-md-2">
           <?= $amounts['cash_bal'] ?>
        </div>

      </div>



</div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="index.js" charset="utf-8"></script>

  </body>
</html>
