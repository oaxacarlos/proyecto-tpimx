<style>
  tr{
    font-size:12px;
  }
</style>

<div class="container-fluid text-right" style="margin-bottom:10px;">
  <button class="btn btn-primary" id="btn_apply">Apply</button>
</div>

<table class="table table-bordered">
  <thead>
    <th></th>
    <th>Doc No</th>
    <th>Date</th>
    <th>Dest No</th>
    <th>Name</th>
    <th>Contact</th>
    <th>Addr</th>
    <th>Addr2</th>
    <th>County</th>
    <th>Post Code</th>
    <th>City</th>
    <th>Country</th>
  </thead>
  <tbody>
    <?php
      function print_data($dest_no, $name, $contact, $addr, $addr2, $county, $post_code, $city, $country, $doc_no, $doc_date, $i){
          $text = "";
          $text.= "<tr>";
            $text.= "<td><input type='checkbox' id='subconsole_".$i."' name='subconsole_".$i."' value='".$doc_no."'></td>";
            $text.= "<td id='subconsole_doc_no_".$i."'>".$doc_no."</td>";
            $text.= "<td id='subconsole_doc_date_".$i."'>".$doc_date."</td>";
            $text.= "<td id='subconsole_dest_no_".$i."'>".$dest_no."</td>";
            $text.= "<td id='subconsole_name_".$i."'>".$name."</td>";
            $text.= "<td id='subconsole_contact_".$i."'>".$contact."</td>";
            $text.= "<td id='subconsole_addr_".$i."'>".$addr."</td>";
            $text.= "<td id='subconsole_addr2_".$i."'>".$addr2."</td>";
            $text.= "<td id='subconsole_county_".$i."'>".$county."</td>";
            $text.= "<td id='subconsole_post_code_".$i."'>".$post_code."</td>";
            $text.= "<td id='subconsole_city_".$i."'>".$city."</td>";
            $text.= "<td id='subconsole_country_".$i."'>".$country."</td>";
          $text.="</tr>";

          return $text;
      }
      //---

      function print_console($i){
          $text = "";
          $text.="<tr><td colspan='11'>Console $i</td></tr>";
          return $text;
      }
      //----

      function check_is_not_same($dest_no, $name, $contact, $addr, $addrr, $county, $post_code, $city, $country,
      $dest_no2, $name2, $contact2, $addr2, $addrr2, $county2, $post_code2, $city2, $country2){
          if($dest_no != $dest_no2 || $name!=$name2 || $contact!=$contact2 || $addr!=$addr2 ||
             $addrr!=$addrr2 || $county!=$county2 || $post_code!=$post_code2 || $city!=$city2 || $country!=$country2
          )
          return true;
          else return false;
      }

      //---
      $i=0;
      $console = 1;
      foreach($var_packing_no_console as $row){
        if($i == 0){
            echo print_console($console, $i);
            $dest_no = $row["dest_no"];
            $name = $row["dest_name"];
            $contact = $row["dest_contact"];
            $addr = $row["dest_addr"];
            $addr2 = $row["dest_addr2"];
            $county = $row["dest_county"];
            $post_code = $row["dest_post_code"];
            $city = $row["dest_city"];
            $country = $row["dest_country"];
            echo print_data($dest_no, $name, $contact, $addr, $addr2, $county, $post_code, $city, $country, $row["doc_no"], $row["doc_date"], $i);

        }
        else{
            if(check_is_not_same($dest_no, $name, $contact, $addr, $addr2, $county, $post_code, $city, $country,
            $row["dest_no"], $row["dest_name"], $row["dest_contact"], $row["dest_addr"], $row["dest_addr2"], $row["dest_county"], $row["dest_post_code"], $row["dest_city"],$row["dest_country"])){
                $dest_no = $row["dest_no"];
                $name = $row["dest_name"];
                $contact = $row["dest_contact"];
                $addr = $row["dest_addr"];
                $addr2 = $row["dest_addr2"];
                $county = $row["dest_county"];
                $post_code = $row["dest_post_code"];
                $city = $row["dest_city"];
                $country = $row["dest_country"];
                echo print_console($console,$i);
            }

            echo print_data($dest_no, $name, $contact, $addr, $addr2, $county, $post_code, $city, $country, $row["doc_no"], $row["doc_date"], $i);
        }

        $i++;
      }
    ?>
  </tbody>
</table>

<input type="hidden" value="<?php echo $i ?>" id="total_row" name="total_row">

<script>

$("#btn_apply").click(function(){

  var total_row = parseInt($("#total_row").val());

  if(!check_is_not_null()){
      show_error("You have not checked");
  }

  else if(!check_console_but_be_same_address()){
      show_error("You choosing address not same");
  }
  else{
      swal({
        input: 'textarea',
        inputPlaceholder: 'Type your message here',
        showCancelButton: true,
        confirmButtonText: 'OK'
      }).then(function (result) {
            if(result.dismiss == "cancel"){}
            else{
              if(result.value == ""){ show_error("You have to type message");}
              else{
                  var message = result.value;

                  swal({
                    title: "Are you sure ?",
                    html: "To Consolidate this Packing",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                  }).then(function (result) {
                      if(result.value){
                          $("#loading_text").text("Creating New Consolidate Document, Please wait...");
                          $('#loading_body').show();

                          // get the data
                          var doc_no = [];
                          var destno = "";
                          var name = "";
                          var contact = "";
                          var addr = "";
                          var addr2 = "";
                          var county = "";
                          var post_code = "";
                          var city = "";
                          var country = "";
                          var ii = 0;

                          for(i=0;i<total_row;i++){
                              if($('#subconsole_'+i).is(":checked")){
                                  doc_no[ii] = $("#subconsole_doc_no_"+i).text();
                                  dest_no = $("#subconsole_dest_no_"+i).text();
                                  name =  $("#subconsole_name_"+i).text();
                                  contact = $("#subconsole_contact_"+i).text();
                                  addr = $("#subconsole_addr_"+i).text();
                                  addr2 = $("#subconsole_addr2_"+i).text();
                                  county = $("#subconsole_county_"+i).text();
                                  post_code = $("#subconsole_post_code_"+i).text();
                                  city = $("#subconsole_city_"+i).text();
                                  country = $("#subconsole_country_"+i).text();
                                  ii++;
                              }
                          }
                          //---

                          $.ajax({
                              url  : "<?php echo base_url();?>index.php/wms/outbound/consolidate/create_new",
                              type : "post",
                              dataType  : 'html',
                              data : {doc_no:JSON.stringify(doc_no), dest_no:dest_no, name:name, contact:contact, addr:addr, addr2:addr2, county:county, post_code:post_code, city:city, country:country,message:message },
                              success: function(data){
                                  var responsedata = $.parseJSON(data);

                                  if(responsedata.status == 1){
                                        swal({
                                           title: responsedata.msg,
                                           type: "success", confirmButtonText: "OK",
                                        }).then(function(){
                                          setTimeout(function(){
                                            $('#loading_body').hide();
                                            f_show_add();
                                          },100)
                                        });
                                  }
                                  else if(responsedata.status == 0){
                                      Swal('Error!',responsedata.msg,'error');
                                      $('#loading_body').hide();
                                  }
                              }
                          })

                      }
                  })
              }
            }
      })
  }

})
//---

function check_console_but_be_same_address(){
    var total_row = parseInt($("#total_row").val());
    var destno = "";
    var name = "";
    var contact = "";
    var addr = "";
    var addr2 = "";
    var county = "";
    var post_code = "";
    var city = "";
    var country = "";
    var check = 1;
    //var data = [];
    //var ii = 0;

    for(i=0;i<total_row;i++){
        if($('#subconsole_'+i).is(":checked")){
            if(destno=="" && name=="" && contact=="" && addr=="" && addr2=="" && county=="" && post_code=="" && city=="" && country==""){
                destno = $("#subconsole_dest_no_"+i).text();
                name = $("#subconsole_name_"+i).text();
                contact = $("#subconsole_contact_"+i).text();
                addr = $("#subconsole_addr_"+i).text();
                addr2 = $("#subconsole_addr2_"+i).text();
                county = $("#subconsole_county_"+i).text();
                post_code = $("#subconsole_post_code_"+i).text();
                city = $("#subconsole_city_"+i).text();
                country = $("#subconsole_country_"+i).text();
            }
            else{
              if(destno==$("#subconsole_dest_no_"+i).text() &&
              name==$("#subconsole_name_"+i).text() &&
              contact==$("#subconsole_contact_"+i).text() &&
              addr==$("#subconsole_addr_"+i).text() &&
              addr2==$("#subconsole_addr2_"+i).text() &&
              county==$("#subconsole_county_"+i).text() &&
              post_code==$("#subconsole_post_code_"+i).text() &&
              city==$("#subconsole_city_"+i).text() &&
              country==$("#subconsole_country_"+i).text()){}
              else{
                check = 0;
                break;
              }
            }

            /*data[ii].destno = $("#subconsole_dest_no").text();
            data[ii].name = $("#subconsole_name").text();
            data[ii].contact = $("#subconsole_contact").text();
            data[ii].addr = $("#subconsole_addr").text();
            data[ii].addr2 = $("#subconsole_addr2").text();
            data[ii].county = $("#subconsole_county").text();
            data[ii].post_code = $("#subconsole_post_code").text();
            data[ii].city = $("#subconsole_city").text();
            data[ii].country = $("#subconsole_country").text();
            ii++;*/
        }
    }

    if(check == 0) return false;
    else return true;

}
//---

function check_is_not_null(){
    var total_row = parseInt($("#total_row").val());
    var checked = 0;

    for(i=0;i<total_row;i++){
        if($('#subconsole_'+i).is(":checked")){
            checked = 1;
        }
    }

    if(checked == 1) return true;
    else false;
}

</script>
