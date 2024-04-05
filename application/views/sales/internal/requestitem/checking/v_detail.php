<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      Doc No
      <input type="text" value="<?php echo $doc_no; ?>" class="form-control" disabled id="inp_doc_no">
    </div>
    <div class="col-md-1">
      Cust No
      <input type="text" value="<?php echo $cust_no; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      Cust Name
      <input type="text" value="<?php echo $cust_name; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      Contact
      <input type="text" value="<?php echo $contact; ?>" class="form-control" disabled>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      Address
      <input type="text" value="<?php echo $address; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      Address 2
      <input type="text" value="<?php echo $address2; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-2">
      City
      <input type="text" value="<?php echo $city; ?>" class="form-control" disabled>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      Post Code
      <input type="text" value="<?php echo $post_code; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      County
      <input type="text" value="<?php echo $county; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-2">
      Country
      <input type="text" value="<?php echo $country_region_code; ?>" class="form-control" disabled>
    </div>
  </div>
</div>

<input type="hidden" value="<?php echo $status; ?>" class="form-control" id="inp_status">

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Line</th>
          <th>Item</th>
          <th>Desc</th>
          <th>Uom</th>
          <th>Qty Available</th>
          <th>Qty Requested</th>
          <th>Qty Edited</th>
          <th>Action 1</th>
          <th>Action 2</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $i=0;
          foreach($var_in_out_d as $row){
              echo "<tr id='table_detail_row_".$i."'>";
                echo "<td id='table_detail_line_".$i."'>".$row["line_no"]."</td>";
                echo "<td id='table_detail_item_code_".$i."'>".$row["item_code"]."</td>";
                echo "<td>".$row["description"]."</td>";
                echo "<td>".$row["uom"]."</td>";
                echo "<td>".$row["qty_stock"]."</td>";
                echo "<td id='table_detail_qty_".$i."'>".$row["qty"]."</td>";
                echo "<td><input type='number' value='".$row["qty_edited"]."' min='0' onkeydown='return false;' id='table_detail_qty_edited_".$i."'></td>";
                echo "<td><button class='btn btn-warning' onclick=f_make_zero('".$i."')>ZERO</button></td>";
                echo "<td><button class='btn btn-info' onclick=f_make_original('".$i."')>ORIGINAL</button></td>";
              echo "</tr>";
              $i++;
          }
        ?>
      </tbody>
    </table>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <button class="btn btn-danger" id="btn_canceled">CANCELED</button>
    <button class="btn btn-primary" id="btn_process" style="margin-left:20px;">PROCESS</button>
  </div>
</div>

<input type="hidden" id="inp_total_row" value="<?php echo count($var_in_out_d); ?>">
<input type="hidden" id="inp_idx_row" value="<?php echo $idx_row; ?>">

<?php echo loading_body_full() ?>

<script>

  $("#btn_process").click(function(){

      // check if qty edited > 0
      if(!f_check_qty_edited_more_than_zero()){
          show_error("Total Quantity is 0, not allow");
          return false;
      }

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
                    html: "Process this Item Request",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                  }).then(function (result) {
                      if(result.value){

                        // if stock available.. create picking
                        $("#loading_text").text("Processing the Document, Please wait...");
                        $('#loading_body').show();
                        //---

                          // get variable
                          var doc_no = $("#inp_doc_no").val();
                          var status = $("#inp_status").val();
                          var total_row = $("#inp_total_row").val();

                          // get picking data
                          var item_code = [];
                          var line = [];
                          var qty = [];
                          var qty_edited = [];

                          var counter = 0;
                          for(i=0;i<total_row;i++){
                              if(check_if_id_exist("#table_detail_row_"+i)){
                                  item_code[counter] = $("#table_detail_item_code_"+i).text();
                                  line[counter] = $("#table_detail_line_"+i).text();
                                  qty[counter] = $("#table_detail_qty_"+i).text();
                                  qty_edited[counter] = $("#table_detail_qty_edited_"+i).val();
                                  counter++;
                              }
                          }
                          //---

                          $.ajax({
                              url  : "<?php echo base_url();?>index.php/sales/internal/requestitem/checking_detail_process",
                              type : "post",
                              dataType  : 'html',
                              data : {item_code:JSON.stringify(item_code),line:JSON.stringify(line), qty:JSON.stringify(qty), qty_edited:JSON.stringify(qty_edited), doc_no:doc_no, message:message, status:status},
                              success: function(data){
                                  var responsedata = $.parseJSON(data);

                                  if(responsedata.status == 1){
                                        swal({
                                           title: responsedata.msg,
                                           type: "success", confirmButtonText: "OK",
                                        }).then(function(){
                                          setTimeout(function(){
                                            $('#loading_body').hide();
                                            $("#myModalDetail").modal("toggle");
                                            idx_row = $("#inp_idx_row").val();
                                            $("#row_"+idx_row).remove();
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

  })


  function f_check_qty_edited_more_than_zero(){
      var total_row = $("#inp_total_row").val();
      var qty = 0;

      for(i=0;i<total_row;i++){
          qty = qty + parseInt($("#table_detail_qty_edited_"+i).val());
      }

      if(qty > 0) return true; else return false;
  }
  //---

  function f_make_zero(idx){
      $("#table_detail_qty_edited_"+idx).val('0');
  }
  //---

  function f_make_original(idx){
      $("#table_detail_qty_edited_"+idx).val($("#table_detail_qty_"+idx).text());
  }
  //--

  $("#btn_canceled").click(function(){

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
                    html: "Cancel this Item Request",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false
                  }).then(function (result) {
                      if(result.value){

                        $("#loading_text").text("Processing the Document, Please wait...");
                        $('#loading_body').show();
                        //---

                          // get variable
                          var doc_no = $("#inp_doc_no").val();
                          var status = $("#inp_status").val();

                          $.ajax({
                              url  : "<?php echo base_url();?>index.php/sales/internal/requestitem/cancel_doc",
                              type : "post",
                              dataType  : 'html',
                              data : { doc_no:doc_no, message:message},
                              success: function(data){
                                  var responsedata = $.parseJSON(data);

                                  if(responsedata.status == 1){
                                        swal({
                                           title: responsedata.msg,
                                           type: "success", confirmButtonText: "OK",
                                        }).then(function(){
                                          setTimeout(function(){
                                            $('#loading_body').hide();
                                            $("#myModalDetail").modal("toggle");
                                            idx_row = $("#inp_idx_row").val();
                                            $("#row_"+idx_row).remove();
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

  })
  //---

</script>
