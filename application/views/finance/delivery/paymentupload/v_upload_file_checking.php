

<table class="table table-bordered table-sm">
  <tbody>
    <?php

      function check_is_date_format($date){
          $date_temp = explode("-",$date);
          $year   = $date_temp[0];
          $month  = $date_temp[1];
          $day    = $date_temp[2];

          return checkdate((int)$month, (int)$day, (int)$year);
          return 1;
      }
      //---

      //---
      $i=0;
      $first = 1;
      $status_all = 1;
      foreach($tables as $row){
          if($i == 0){
            echo "<tr style='font-size:12px;' class='table-secondary'>";
              echo "<th>DOC NO</th>";
              echo "<th>SUBTOTAL</th>";
              echo "<th>TOTAL</th>";
              echo "<th>INVOICE VENDOR NO</th>";
              echo "<th>INVOICE VENDOR DATE</th>";
              echo "<th>PAYMENT DATE</th>";
              echo "<th>UUID</th>";
              echo "<th>REMARKS</th>";
              echo "<th>STATUS</th>";
              echo "<th>ERROR MESSAGE</th>";
            echo "</tr>";
          }
          else{
            $status = 1; $error = "";

            echo "<tr class='table-".$table_color."' style='font-size:12px;'>";
              echo "<td id='tbl_doc_no_".$i."'>".$row[0]."</td>";
              echo "<td id='tbl_subtotal_".$i."'>".$row[1]."</td>";
              echo "<td id='tbl_total_".$i."'>".$row[2]."</td>";
              echo "<td id='tbl_invoice_vendor_no_".$i."'>".$row[3]."</td>";
              echo "<td id='tbl_invoice_vendor_date_".$i."'>".$row[4]."</td>";
              echo "<td id='tbl_payment_date_".$i."'>".$row[5]."</td>";
              echo "<td id='tbl_uuid_".$i."'>".$row[6]."</td>";
              echo "<td id='tbl_remarks_".$i."'>".$row[7]."</td>";

              if(!check_is_date_format($row[4])){
                  $status = 0; $error = "Format INVOICE VENDOR DATE is not correct";
              }

              if($status == 1){
                if(!check_is_date_format($row[5])){
                    $status = 0; $error = "Format PAYMENT DATE is not correct";
                }
              }

              // end status
              if($status == 1) echo "<td><i class='bi bi-check2' style='font-size:20px; color:green;'></i></td>";
              else{
                 echo "<td><i class='bi bi-x-lg' style='font-size:20px; color:red;'></i></td>";
                 $status_all = 0;
              }

              echo "<td>".$error."</td>";

            echo "</tr>";

            $first++;
          }

          $i++;
      }
    ?>
  </tbody>
</table>

<?php
  if($status_all == 1) $disabled_status_all = "";
  else $disabled_status_all = "disabled";

  echo "<input type='hidden' id='total_row' value='".count($tables)."'>";
?>

<div class="container-fluid text-right">
  <button class="btn btn-success" id="btn_process" <?php echo $disabled_status_all; ?> >PROCESS</button>
</div>

<script>

  $("#btn_process").click(function(){
      var total_row = parseInt($("#total_row").val());

      var doc_no = [];
      var subtotal = [];
      var total = [];
      var invoice_vendor_no = [];
      var invoice_vendor_date = [];
      var payment_date = [];
      var uuid = [];
      var remarks = [];
      var count = 0;

      for(i=1;i<total_row;i++){
        doc_no[count] = $("#tbl_doc_no_"+i).text();
        subtotal[count] = $("#tbl_subtotal_"+i).text();
        total[count] = $("#tbl_total_"+i).text();
        invoice_vendor_no[count] = $("#tbl_invoice_vendor_no_"+i).text();
        invoice_vendor_date[count] = $("#tbl_invoice_vendor_date_"+i).text();
        payment_date[count] = $("#tbl_payment_date_"+i).text();
        uuid[count] = $("#tbl_uuid_"+i).text();
        remarks[count] = $("#tbl_remarks_"+i).text();
        count++;
      }

      // is everything ok, proceed
      swal({
        title: "Are you sure ?",
        html: "Proceed this Payment Update",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
          if(result.value){

              $("#loading_text").text("Updating Payment Information, Please wait...");
              $('#loading_body').show();

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/finance/delivery/payment/upload_file_process",
                  type : "post",
                  dataType  : 'html',
                  data : {doc_no:JSON.stringify(doc_no), subtotal:JSON.stringify(subtotal), total:JSON.stringify(total), invoice_vendor_no:JSON.stringify(invoice_vendor_no), invoice_vendor_date:JSON.stringify(invoice_vendor_date), payment_date:JSON.stringify(payment_date),uuid:JSON.stringify(uuid), remarks:JSON.stringify(remarks) },
                  success: function(data){
                      var responsedata = $.parseJSON(data);

                      if(responsedata.status == 1){
                            swal({
                               title: responsedata.msg,
                               type: "success", confirmButtonText: "OK",
                            }).then(function(){
                              setTimeout(function(){
                                $('#loading_body').hide();
                                location.reload();
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
  })
  //---

</script>
