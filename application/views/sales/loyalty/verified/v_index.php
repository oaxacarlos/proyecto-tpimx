<style>
tr{
  font-size: 12px;
}
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Loyalty Verification
</div>

<div class="container-fluid">
  <table class="table table-bordered table-sm table-striped">
      <thead>
        <tr>
          <th>Doc No</th>
          <th>Date</th>
          <th>Invc No</th>
          <th>Customer</th>
          <th>Remark</th>
          <th>Where they Buy?</th>
          <th>No</th>
          <th>Item</th>
          <th>Desc</th>
          <th>Uom</th>
          <th>Qty</th>
          <th>Point per Qty</th>
          <th>Customer's Point</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $colspan = "13";

          if($var_data == 0){
              echo "<tr><td colspan='".$colspan."'>No Data</td></tr>";
          }
          else{
            $target_file  = $this->config->item('loyalty_client_invc');
            $file = "";
            $doc_no = "";

            $i=0;
            $total_row = 0;
            foreach($var_data as $row){
                if($doc_no == ""){
                    $doc_no = $row["doc_no"]; $file = $row["invc_file"];
                }
                else if($doc_no != $row["doc_no"]){
                  echo "<tr id='table_verified_".$doc_no."_".$total_row."'><td colspan='".$colspan."'>";
                    echo "<a class='btn btn-sm btn-outline-dark' href='".$var_link.$target_file.$file."' target=_blank style='width:200px;'>FILE</a>";

                    echo "<button class='btn btn-success btn-sm' style='width:200px; margin-left:20px;' onclick=f_process_verified_all('".$doc_no."','".$total_row."')><i class='bi-check2'></i></button>";

                    echo "<button class='btn btn-danger btn-sm' style='width:200px; margin-left:20px;' onclick=f_process_reject_all('".$doc_no."','".$total_row."')><i class='bi bi-x-lg'></i></button>";

                  echo"</td></tr>";

                  $total_row++;
                  echo "<tr class='table-info'><td colspan='".$colspan."'></td></tr>";
                  $doc_no = $row["doc_no"];
                  $file = $row["invc_file"];
                  $total_row = 0;
                }

                echo "<tr id='table_verified_".$doc_no."_".$total_row."'>";
                  echo "<td>".$row["doc_no"]."</td>";
                  echo "<td>".$row["created_at"]."</td>";
                  echo "<td>".$row["invc_no"]."</td>";
                  echo "<td>".$row["name"]."</td>";
                  echo "<td>".$row["remark1"]."</td>";
                  echo "<td>".$row["remark3"]."</td>";
                  echo "<td>".$row["line"]."</td>";
                  echo "<td>".$row["item_code"]."</td>";
                  echo "<td>".$row["desc"]."</td>";
                  echo "<td>".$row["uom"]."</td>";
                  echo "<td>".$row["qty"]."</td>";
                  echo "<td>".$row["point_per_qty"]."</td>";
                  echo "<td>".$row["point"]."</td>";
                  //echo "<td><button class='btn btn-success btn-sm' onclick=f_process_verified('".$row["doc_no"]."','".$row["line"]."','".$i."','".$row["created_by"]."','".$row["point"]."')><i class='bi-check2'></i></button></td>";
                  //echo "<td><button class='btn btn-danger btn-sm' onclick=f_process_reject('".$row["doc_no"]."','".$row["line"]."','".$i."','".$row["created_by"]."','".$row["point"]."')><i class='bi bi-x-lg'></i></button></td>";
                echo "</tr>";

                $i++;
                $total_row++;
            }
          }


          // last file
          if($file != ""){
            echo "<tr id='table_verified_".$doc_no."_".$total_row."'><td colspan='".$colspan."'>";
              echo "<a class='btn btn-sm btn-outline-dark' href='".$var_link.$target_file.$file."' target=_blank style='width:200px;'>FILE</a>";

              echo "<button class='btn btn-success btn-sm' style='width:200px; margin-left:20px;' onclick=f_process_verified_all('".$doc_no."','".$total_row."')><i class='bi-check2'></i></button>";

              echo "<button class='btn btn-danger btn-sm' style='width:200px; margin-left:20px;' onclick=f_process_reject_all('".$doc_no."','".$total_row."')><i class='bi bi-x-lg'></i></button>";

            echo"</td></tr>";

            $total_row++;
            echo "<tr class='table-info'><td colspan='".$colspan."'></td></tr>";
          }
          //--
        ?>
      </tbody>
  </table>
</div>

<script>

function f_process_verified(doc_no,line,index,userid,point){

  swal({
    title: "Are you sure ?",
    html: "Approved",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
    if(result.value){
      // ajax
      $.ajax({
          url  : "<?php echo base_url();?>index.php/sales/loyalty/verified/save",
          type : "post",
          dataType  : 'html',
          data : {doc_no:doc_no, line:line, userid:userid, point:point},
          success: function(data){
            var responsedata = $.parseJSON(data);

            if(responsedata.status == 1){
                swal({
                   title: responsedata.msg,
                   type: "success", confirmButtonText: "OK",
                }).then(function(){
                    delete_row(index);
                });
            }
            else if(responsedata.status == 0){
                Swal('Error!',responsedata.msg,'error');
                $('#loading_body').hide();
            }
          }
      })
      //---
    }
  })
}
//--

function f_process_reject(doc_no,line,index,userid,point){

  swal({
    title: "Are you sure ?",
    html: "Rejected",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
    if(result.value){
      // ajax
      $.ajax({
          url  : "<?php echo base_url();?>index.php/sales/loyalty/verified/reject",
          type : "post",
          dataType  : 'html',
          data : {doc_no:doc_no, line:line, userid:userid, point:point},
          success: function(data){
            var responsedata = $.parseJSON(data);

            if(responsedata.status == 1){
                swal({
                   title: responsedata.msg,
                   type: "success", confirmButtonText: "OK",
                }).then(function(){
                    delete_row(index);
                });
            }
            else if(responsedata.status == 0){
                Swal('Error!',responsedata.msg,'error');
                $('#loading_body').hide();
            }
          }
      })
      //---
    }
  })
}
//--

function delete_row(id){
    $("#table_verifid_"+id).fadeOut(1000, function(){ $(this).remove(); });
}
//--

function delete_row_all(doc_no,total_row){
    total_row = parseInt(total_row) + 1;
    for(i=0;i<=total_row;i++){
        $("#table_verified_"+doc_no+"_"+i).fadeOut(1000, function(){ $(this).remove(); });
    }
}

//---

function f_process_verified_all(doc_no,total_row){

  swal({
    title: "Are you sure ?",
    html: "Approved",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
    if(result.value){
      // ajax
      $.ajax({
          url  : "<?php echo base_url();?>index.php/sales/loyalty/verified/save_doc",
          type : "post",
          dataType  : 'html',
          data : {doc_no:doc_no},
          success: function(data){
            var responsedata = $.parseJSON(data);

            if(responsedata.status == 1){
                swal({
                   title: responsedata.msg,
                   type: "success", confirmButtonText: "OK",
                }).then(function(){
                     delete_row_all(doc_no,total_row);
                });
            }
            else if(responsedata.status == 0){
                Swal('Error!',responsedata.msg,'error');
                $('#loading_body').hide();
            }
          }
      })
      //---
    }
  })
}
//--

function f_process_reject_all(doc_no,total_row){

  swal({
    input: 'textarea',
    inputPlaceholder: 'Type your message here',
    showCancelButton: true,
    confirmButtonText: 'OK'
  }).then(function (result) {

    if(result.dismiss == "cancel"){}
    else{
      if(result.value == ""){ show_error("You must type the message...");}
      else{
          var message = result.value;

          swal({
            title: "Are you sure ?",
            html: "Rejected",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
            if(result.value){
              // ajax
              $.ajax({
                  url  : "<?php echo base_url();?>index.php/sales/loyalty/verified/reject_doc",
                  type : "post",
                  dataType  : 'html',
                  data : {doc_no:doc_no, message:message},
                  success: function(data){
                    var responsedata = $.parseJSON(data);

                    if(responsedata.status == 1){
                        swal({
                           title: responsedata.msg,
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                            delete_row_all(doc_no,total_row);
                        });

                        //toast_message_success(responsedata.msg);
                    }
                    else if(responsedata.status == 0){
                        Swal('Error!',responsedata.msg,'error');
                        $('#loading_body').hide();
                    }
                  }
              })
              //---
            }
          })

      }
    }
  })


}
//--

</script>
