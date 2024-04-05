<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      //"ordering": false
    });

});
</script>

<style>
  .bi-x-lg{
    color: red;
  }

  .bi-check-lg{
    color: green;
  }
</style>

<?php echo loading_body_full(); ?>

<div class="modal" id="myModalDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Item</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalBarcode">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Barcode</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_barcode"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalChangeUser">
  <div class="modal-dialog2 modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Change User</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_change_user"></div>
    </div>
  </div>
</div>

<?php

$waiting_picking = 0;
$qc_ready = 0;
$urgent = 0;

$waiting_picking_qty = 0;
$qc_ready_qty = 0;
$urgent_qty = 0;

unset($user_qc_doc);

foreach($var_whship_has_picked as $row){
    if(($row['qty_to_ship'] == $row['qty_has_picked']) && $row['statuss']=="12"){
       $qc_ready+=1;
       $qc_ready_qty += $row['qty_to_ship'];
    }
    else{
       $waiting_picking += 1;
       $waiting_picking_qty += $row['qty_to_ship'];
    }

    if($row['urgent'] == "1"){
        $urgent += 1;
        $urgent_qty += $row['qty_to_ship'];
    }
    else{
      $total_time = calculateTransactionDuration($row['doc_datetime'], $now);
      if($total_time >= $var_timeout_wms){
          $urgent+=1;
          $urgent_qty += $row['qty_to_ship'];
      }
    }

    if(!isset($user_qc_doc[$row["qc_user"]])) $user_qc_doc[$row["qc_user"]] = 1;
    else  $user_qc_doc[$row["qc_user"]] += 1;
}

?>

<div class="row" style="margin-bottom:10px;">
  <div class="col-1"><span class="badge badge-primary" style='font-size:15px;'>(DOC)&nbsp;&nbsp;<i class="bi bi-alarm-fill"></i>&nbsp;&nbsp;<?php echo $waiting_picking; ?></badge></div>
  <div class="col-1"><span class="badge badge-success" style='font-size:15px;'>(DOC)&nbsp;&nbsp;<i class="bi bi-upc-scan"></i>&nbsp;&nbsp;<?php echo $qc_ready; ?></badge></div>
  <div class="col-1"><span class="badge badge-danger" style='font-size:15px;'>Urgent (DOC) : <?php echo $urgent; ?></badge></div>
</div>

<div class="row" style="margin-bottom:20px;">
  <div class="col-1"><span class="badge badge-primary" style='font-size:15px;'>(QTY)&nbsp;&nbsp;<i class="bi bi-alarm-fill"></i>&nbsp;&nbsp;<?php echo $waiting_picking_qty; ?></badge></div>
  <div class="col-1"><span class="badge badge-success" style='font-size:15px;'>(QTY)&nbsp;&nbsp;<i class="bi bi-upc-scan"></i>&nbsp;&nbsp;<?php echo $qc_ready_qty; ?></badge></div>
  <div class="col-1"><span class="badge badge-danger" style='font-size:15px;'>Urgent (QTY) : <?php echo $urgent_qty; ?></badge></div>
</div>

<div class="row" style="margin-bottom:10px;">
  <?php
    foreach($user_qc_doc as $key => $value){
        echo "<span class='col-1'><i class='bi bi-person-fill' style='color:".$user_color[$key].";'></i>".$value."</span>";
    }
  ?>
</div>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>SO</th>
      <th>Cust No</th>
      <th>Cust Name</th>
      <th>City</th>
      <th>WHS</th>
      <th>Ext Doc</th>
      <th>Created User</th>
      <th>Assign to User Picking</th>
      <th>Assign to User QC</th>
      <th>Type</th>
      <th>Qty</th>
      <th>Picklist</th>
      <th>Qty has Picked</th>
      <th>Qty SN</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>QC</th>
      <th>Action 2</th>
      <th>Action 3</th>
    </tr>
  </thead>

  <tbody>
    <?php
        $now = get_datetime_now();
        foreach($var_whship_has_picked as $row){
            if(($row['qty_to_ship'] == $row['qty_has_picked']) && $row['statuss']=="12"){
              $btn_disabled = "";
              $icon_qc = "<i class='bi bi-check-lg' style='font-size:27px; font-weight:bold;'></i>"; // 2023-07-12
            }
            else{
              $btn_disabled = "disabled";
              $icon_qc = "<i class='bi bi-x-lg' style='font-size:25px; font-weight:bold;'></i>"; // 2023-07-12
            }

            // qc_user 2023-07-12
            if($row["qc_user"] != $user) $qc_user2 = "0";
            else $qc_user2 = "1";
            //--

            if($row["month_end"] == "1") $month_end = "<span class='badge badge-warning' style='font-size:10px;'>Month End</span>";
            else $month_end="";

            $total_time = calculateTransactionDuration($row['doc_datetime'], $now);
            if($total_time >= $var_timeout_wms)
                $badge_doc_no = "<span class='badge badge-danger' style='font-size:10px;'>".$var_timeout_text."</span>";
            else $badge_doc_no = "";

            if($row['urgent'] == "1") $badge_doc_no = "<span class='badge badge-danger' style='font-size:10px;'>URGENT POR PEDIDO</span>";

            $qc_locked="";
            if($row["locked"] == 1 && ($row['qty_to_ship'] == $row['qty_has_picked']) && $row['statuss']=="12"){
                //$btn_disabled = "disabled";
                $qc_locked = "<i class='bi bi-upc-scan' style='font-size:15px; font-weight:bold;'></i>";
            }

            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']." ".$badge_doc_no."</td>";
              echo "<td>".$row['so_no']."</td>";
              echo "<td>".$row['bill_cust_no']."</td>";
              echo "<td>".$row['bill_cust_name']."</td>";
              echo "<td>".$row['ship_to_city']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['external_document']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['assign_name']."</td>";
              echo "<td><i class='bi bi-person-fill' style='color:".$user_color[$row["qc_user"]].";'></i>".$row['qc_name']."</td>"; // 2023-07-12

              if($row['bill_cust_no'][0] == "1"){
                  $type = "Filter"; $type_color="danger";
              }
              else if($row['bill_cust_no'][0] == "2"){
                  $type = "Belt"; $type_color="primary";
              }
              else{
                  $type = "-"; $type_color="warning";
              }

              echo "<td><div class='badge badge-".$type_color."'>".$type."</td>";

              echo "<td>".$row['qty_to_ship']."</td>";
              //echo "<td>".$row['qty_outstanding']."</td>";
              echo "<td>".$row['qty_to_picked']."</td>";
              echo "<td>".$row['qty_has_picked']."</td>";
              echo "<td>".$row['qty_picked_d2']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']." ".$month_end."</td>";

              echo "<td>";
              if($qc_user2 == "1"){
                echo "<a href='".base_url()."index.php/wms/outbound/checking/qc?id=".$row['doc_no']."' class='btn btn-primary btn-block ".$btn_disabled."' >QC</a>";
              }
              echo $icon_qc."<br>".$qc_locked."</td>";

              echo "<td><div class='btn-group' role='group'>
                <button id='btnGroupDropBarcode' type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  BARCODE
                </button>
                <div class='dropdown-menu' aria-labelledby='btnGroupDropBarcode'>";

                echo "<a href='".base_url()."index.php/wms/barcode/print_barcode_by_doc?doctype=qctemp&docno=".$row['doc_no']."' class='btn btn-success btn-block ".$btn_disabled."' target=_blank>BARCODE ALL</a>";

                echo "<button class='btn btn-success btn-block ".$btn_disabled."' onclick=f_show_barcode('".$row['doc_no']."','qctemp','".$btn_disabled."') style='margin-top:10px;'>BARCODE ONE</button>";

                echo "<a href='".base_url()."index.php/wms/barcode/print_master_barcode_by_doc?doctype=qctemp2&docno=".$row['doc_no']."' class='btn btn-success btn-block ".$btn_disabled."' target=_blank>MASTER BARCODE</a>";

              echo"</div>
              </div></td>";

              echo "<td><div class='btn-group' role='group'>
                <button id='btnGroupDropMore' type='button' class='btn btn-outline-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  MAS
                </button>
                <div class='dropdown-menu' aria-labelledby='btnGroupDropMore'>";

                echo "<button class='btn btn-outline-primary btn-block' onclick=f_show_detail('".$row['doc_no']."')>Detail</button>";

                echo "<a href='".base_url()."index.php/wms/outbound/checking/print?id=".$row['doc_no']."' class='btn btn-outline-primary btn-block ".$btn_disabled."' target='_blank'>PRINT</a>";

                if(isset($_SESSION['user_permis']["13"])){
                  if($row['skip_scan'] == 0) $text_skip_scan = "Skip Scan";
                  else $text_skip_scan= "Back to Scan";

                  echo "<button class='btn btn-outline-warning btn-block' onclick=f_process_skip_scan('".$row['doc_no']."',".$row['skip_scan'].") ".$btn_disabled.">".$text_skip_scan."</button>";
                }

                if(isset($_SESSION['user_permis']["16"])){
                  if($row['urgent'] == 0) $text_urgent = "URGENTE";
                  else $text_urgent = "No URGENTE";

                  echo "<button class='btn btn-outline-danger btn-block' onclick=f_process_urgent('".$row['doc_no']."',".$row['urgent'].")>".$text_urgent."</button>";
                }

                // 2023-07-12
                if(isset($_SESSION['user_permis']["30"])){
                  echo "<button class='btn btn-outline-secondary btn-block' onclick=f_change_user('".$row['doc_no']."')>CHANGE QC</button>";
                }
                //---

              echo"</div>
              </div></td>";

            echo "</tr>";
        }
    ?>
  </tbody>
</table>


<script>
function f_show_detail(id){
  var link = 'wms/outbound/warehouse/v_detail';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/outbound/whship/get_warehouse_detail",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

function f_process_skip_scan(id,status){

  if(status == "0") skip_scan_text = "Process Skip Scan";
  else skip_scan_text = "Back to Scan";

          swal({
            title: "Are you sure ?",
            html: skip_scan_text,
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
              if(result.value){
                $("#loading_text").text("Skip Scanning, Please wait...");
                $('#loading_body').show();

                $.ajax({
                    url  : "<?php echo base_url();?>index.php/wms/outbound/checking/skipscan",
                    type : "post",
                    dataType  : 'html',
                    data : {doc_no: id, status:status},
                    success: function(data){
                        var responsedata = $.parseJSON(data);

                        if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              f_refresh();
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
//---

function f_show_barcode(id, doc_type,status){

  if(status == ""){
      var link = 'wms/outbound/checking/v_detail_barcode';
      data = {'id':id, 'link':link,'doc_type':doc_type }
      $('#modal_detail_barcode').html('Loading, Please wait...');
      //open the modal with selected parameter attached
      $('#modal_detail_barcode').load(
          "<?php echo base_url();?>index.php/wms/outbound/checking/get_detail_barcode",
          data,
          function(responseText, textStatus, XMLHttpRequest) { } // complete callback
      );

      $('#myModalBarcode').modal();
  }
}
//---

function f_process_urgent(id,urgent){

  if(urgent == "0") urgent_text = "Process URGENTE";
  else urgent_text = "Back to NO URGENTE";

          swal({
            title: "Are you sure ?",
            html: urgent_text,
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
              if(result.value){
                $("#loading_text").text("Process URGENT, Please wait...");
                $('#loading_body').show();

                $.ajax({
                    url  : "<?php echo base_url();?>index.php/wms/outbound/checking/urgent",
                    type : "post",
                    dataType  : 'html',
                    data : {doc_no: id, urgent:urgent},
                    success: function(data){
                        var responsedata = $.parseJSON(data);

                        if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              f_refresh();
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
//---

function f_change_user(id){
    data = {'id':id }
    $('#modal_change_user').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_change_user').load(
        "<?php echo base_url();?>index.php/wms/outbound/checking/get_change_user",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalChangeUser').modal();
}
//---

</script>
