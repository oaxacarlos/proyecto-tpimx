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
          <th>Qty Requested</th>
          <th>Qty Edited</th>
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
                echo "<td id='table_detail_qty_".$i."'>".$row["qty"]."</td>";
                echo "<td>".$row["qty_edited"]."</td>";
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
    <button class="btn btn-primary" id="btn_approve" style="margin-left:20px;">APPROVE</button>
  </div>
</div>

<input type="hidden" id="inp_total_row" value="<?php echo count($var_in_out_d); ?>">
<input type="hidden" id="inp_idx_row" value="<?php echo $idx_row; ?>">

<?php echo loading_body_full() ?>

<script>

  $("#btn_approve").click(function(){

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

                          $.ajax({
                              url  : "<?php echo base_url();?>index.php/sales/internal/requestitem/approve_detail_process",
                              type : "post",
                              dataType  : 'html',
                              data : { doc_no:doc_no,status:status, message:message},
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
