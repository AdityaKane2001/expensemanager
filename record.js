function delete_entry(id){

  var data = new FormData();
  data.append('type','delete');
  data.append('tid',id);

  xmlHttp= new XMLHttpRequest();
  xmlHttp.onreadystatechange = function(){                                                 //shoot when ready
      if(xmlHttp.readyState == 4 && xmlHttp.status == 200){

        make_table();
      }

        }

        xmlHttp.open("post", "record_interface.php");
        xmlHttp.send(data);


}

function make_table(){
  var data = new FormData();
  data.append('type','refresh');

  xmlHttp= new XMLHttpRequest();
  xmlHttp.onreadystatechange = function(){                                                 //shoot when ready
      if(xmlHttp.readyState == 4 && xmlHttp.status == 200){

        document.getElementById('all_rows').innerHTML = xmlHttp.responseText;
      }

        }

        xmlHttp.open("post", "record_interface.php");
        xmlHttp.send(data);



}
