<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "asc" ]]
    });
});
</script>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>No</th>
      <th>WHS</th>
      <th>Ext Doc No</th>
      <th>Qty</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php

        if(isset($var_whship_list_h)){
          $time_now = date("H:i:s");
          $time_now = DateTime::createFromFormat('H:i:s', $time_now);
          $locked_doc_from2 = DateTime::createFromFormat('H:i:s', $locked_doc_from);
          $locked_doc_to2 = DateTime::createFromFormat('H:i:s', $locked_doc_to);

          if($time_now >= $locked_doc_from2 && $time_now >= $locked_doc_to2){
            $disabled = "disabled";
            echo "<div class='alert alert-danger' role='alert'>
                en WH2, Ningún proceso de ".$locked_doc_from." hasta ".$locked_doc_to."
              </div>";
          }
          else $disabled = "";

          foreach($var_whship_list_h as $row){
              echo "<tr id='row_".$row['no']."'>";
                echo "<td>".convert_date_to_date($row['posting_date'])."</td>";
                echo "<td>".$row['no']."</td>";
                echo "<td id='tbl_loc_code_".$row['no']."'>".$row['loc_code']."</td>";
                echo "<td>".$row['ext_doc_no']."</td>";
                echo "<td>".round($row['qty'])."</td>";

                if($row['loc_code'] == "WH3"){
                    echo "<td>
                      <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['no']."')>Detail</button>
                      <button class='btn btn-sm btn-primary' onclick=f_process('".$row['no']."')>Process</button>
                    </td>";
                }
                else{
                    echo "<td>
                      <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['no']."')>Detail</button>
                      <button class='btn btn-sm btn-primary' onclick=f_process('".$row['no']."') ".$disabled.">Process</button>
                    </td>";
                }

              echo "</tr>";
          }
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(id){
  var link = 'wms/outbound/nav/v_nav_whship_list_detail';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/outbound/whship/get_whship_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#modal_doc_no').text(id);
  $('#myModalDetail').modal();
}
//---

function f_process(id){

  // check month end
  var month_end = check_month_end(id);
  if(month_end == 1){
    alert("Month End, You can direct submit back to Navision on 'Submit to Navision Menu'");
  }
  //---

  swal({
    title: "Are you sure ?",
    html: "Process this Document",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
        if(result.value){
            $("#loading_text").text("Transfering from WH Shipment to Warehouse, Please wait...");
            $('#loading_body').show();

            $.ajax({
                url  : "<?php echo base_url();?>index.php/wms/outbound/whship/transfer_whship_to_warehouse",
                type : "post",
                dataType  : 'html',
                data : {id:id, month_end:month_end},
                success: function(data){
                  var responsedata = $.parseJSON(data);

                  if(responsedata.status == 1){
                        swal({
                           title: responsedata.msg,
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function(){
                              delete_row(id); // delete the row
                          },100)
                        });
                  }
                  else if(responsedata.status == 0){
                      Swal('Error!',responsedata.msg,'error');
                  }

                  $('#loading_body').hide();
                }
            })
        }
  })
}
//----

function delete_row(id){
    $('#row_'+id).fadeOut(1000, function(){ $(this).remove(); });
}
//---

function check_month_end(id){
  var result = "";
  var whs = $("#tbl_loc_code_"+id).text();
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/outbound/whship/check_month_end",
      type : "post",
      dataType  : 'json',
      data : {whs:whs},
      async: false,
      success: function(data){
          result = $.parseJSON(data);
      }
  })
  return result;
}

</script>
