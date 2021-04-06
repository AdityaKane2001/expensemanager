
function fade_out(divname) {
  $("#".concat(divname)).fadeOut().empty();
}

function record_income(){

  var data = new FormData();
  data.append('type','income');
  data.append('mode',document.getElementById('mode').value);
  data.append('from',document.getElementById('from').value)
  data.append('amount',document.getElementById('iamount').value);
  document.getElementById('istatus').innerHTML ="<br><br>";
  if(isNaN(document.getElementById('iamount').value)){
    document.getElementById('istatus').innerHTML = "<p style='color:red;'> Enter integer amount  </p>";


  }
  else if(document.getElementById('mode').value!='acc_transfer' && document.getElementById('mode').value!='cash'){
      document.getElementById('istatus').innerHTML = '<p style="color:red;" > Enter mode any one of: (acc_transfer,cash)  </p>';

  }
  else{


  var xmlHttp = new XMLHttpRequest();                                                      //initialize AJAX request
   xmlHttp.onreadystatechange = function(){                                                 //shoot when ready
       if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
           {
              document.getElementById('istatus').innerHTML = xmlHttp.responseText;

              refresh_data();
              setTimeout(function  (){

                document.getElementById('istatus').innerHTML = "<br><br>";
              }, 3000);

           }


     }
 xmlHttp.open("post", "tracker.php");
 xmlHttp.send(data);
}

}

function record_expense(){
  var data = new FormData();
  data.append('type','expense');
  data.append('details',document.getElementById('to').value);
  data.append('amount',document.getElementById('eamount').value);
  data.append('account',document.getElementById('account').value);

  if(isNaN(document.getElementById('eamount').value)){
    document.getElementById('estatus').innerHTML = "<p style='color:red;'> Enter integer amount  </p>";

  }
    else if(document.getElementById('account').value!='gpay' && document.getElementById('account').value!='cash'){
    document.getElementById('estatus').innerHTML = '<p style="color:red;" > Enter account any one of: (gpay,cash)  </p>';
  }

  else{

  var xmlHttp = new XMLHttpRequest();                                                      //initialize AJAX request
   xmlHttp.onreadystatechange = function(){                                                 //shoot when ready
       if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
           {
              document.getElementById('estatus').innerHTML = xmlHttp.responseText;
              setTimeout(function () {

                  document.getElementById('estatus').innerHTML = "<br><br>";
              }, 3000);


              refresh_data();
           }


     }
 xmlHttp.open("post", "tracker.php");
 xmlHttp.send(data);
}

}


function refresh_data(){

  var data = new FormData();
  data.append('type','refresh');
  var xmlHttp = new XMLHttpRequest();                                                      //initialize AJAX request
   xmlHttp.onreadystatechange = function(){                                                 //shoot when ready
       if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
           {
              var amounts = JSON.parse(xmlHttp.responseText);
              //console.log(amounts);
              document.getElementById('gpay_bal').innerHTML = amounts['gpay'];
              document.getElementById('cash_bal').innerHTML = amounts['cash'];

              document.getElementById('account').value = "";
              document.getElementById('mode').value = "";
              document.getElementById('from').value = "";
              document.getElementById('to').value = "";
              document.getElementById('eamount').value = "";
              document.getElementById('iamount').value = "";

           }


     }
 xmlHttp.open("post", "tracker.php");
 xmlHttp.send(data);


}


  //
  //   var data= new FormData();
  //   data.append('send','send');
  //   data.append('type','pass_res');
  //
  //   var xmlHttp = new XMLHttpRequest();                                                      //initialize AJAX request
  //   xmlHttp.onreadystatechange = function(){                                                 //shoot when ready
  //       if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
  //           {
  //           document.getElementById(id).innerHTML=xmlHttp.responseText;                          //clear the div
  //           setTimeout(function(){
  //             document.getElementById(id).innerHTML=' <button type="button" class="btn btn-outline-danger" onclick="send_mail(\'mail_mesg\',\'pass_res\');">Send Mail</button>';  }, 10000);
  //
  //           }
  //
  //
  //     }
  // xmlHttp.open("post", "mailer.php");
  // xmlHttp.send(data);
