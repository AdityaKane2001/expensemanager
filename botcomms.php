<?php
session_start();
<<<<<<< HEAD
require_once 'pdo.php';
$_SESSION['token'] = '1751648833:AAGwoBQhbU6TXvLcfGX-EP6Yvk6CYXyF9Vw';
$_SESSION['ak_telegram_id']=1237164334;
$_SESSION['last_message_code'] = 0;

function get_last_message(){

  $url='https://api.telegram.org/bot1751648833:AAGwoBQhbU6TXvLcfGX-EP6Yvk6CYXyF9Vw/getUpdates';
  $site = json_decode(file_get_contents($url),true);
  $results = array_reverse($site['result'],true);
  var_dump(file_get_contents($url));
  $last_mesg = false;

  foreach ($results as $value) {
    if($value['message']['chat']['id']==$_SESSION['ak_telegram_id'] && !$value['message']['from']['is_bot']){

      $last_mesg = $value['message']['text'];

      break;
    }
  }
  unset($results);
  unset($site);

  return $last_mesg;

}

function send_message( $message) {
    //echo "sending message to " . $chatID . "\n";

    $url = "https://api.telegram.org/bot" . $_SESSION['token'] . "/sendMessage?chat_id=" . $_SESSION['ak_telegram_id'];
    $url = $url . "&text=" . urlencode($message);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function parse($message){
  //expected message: <cost>__space__<transaction code: i/e>__space__<comment>

  $pieces = explode(" ",$message);

  $reqarr = array();
  $reqarr['amount'] = intval($pieces[0]);
  $reqarr['type'] = ($pieces[1]=='i') ? 'income' : 'expense' ;
  if ($reqarr['type']=='income') {
    $reqarr['from'] = $pieces[2];
  } else {
    $reqarr['details'] = $pieces[2];
  }

  $req = new HttpRequest('/tracker.php', HttpRequest::METH_POST, $reqarr);
  $req->send();


}

function commit($transaction_arr){

}

echo(get_last_message());
//echo(send_message('Thank you for using Expense Manager.'));
=======
//require_once 'pdo.php';
$HTTP_TOKEN = '1751648833:AAGwoBQhbU6TXvLcfGX-EP6Yvk6CYXyF9Vw';

$url='https://api.telegram.org/bot1751648833:AAGwoBQhbU6TXvLcfGX-EP6Yvk6CYXyF9Vw/getUpdates';
$site = file_get_contents($url);
echo($site);
echo("<br><br><br>");
var_dump(json_decode($site,true));
>>>>>>> 32ce4c8d8146f738befae56ad6616a1c1ea6d0f9

 ?>
