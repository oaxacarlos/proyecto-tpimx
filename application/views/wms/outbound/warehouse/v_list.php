<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "asc" ]]
    });
});
</script>

<div class="modal" id="myModalDetailShip">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_ship"></div>
    </div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>SO</th>
      <th>Cust No</th>
      <th>Cust Name</th>
      <th>Ext Doc</th>
      <th>WHS</th>
      <th>User</th>
      <th>Type</th>
      <th>Qty</th>
      <th>Qty Picked</th>
      <th>Qty Rem</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Action</th>
      <th>Action 2</th>
      <th>Action 3</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_ship as $row){
            if($row['bill_cust_no'] == ""){ $btn_disabled = "disabled"; }
            else{ $btn_disabled = ""; }

            if(strpos($row['doc_no'], 'WMS-WSHIP-') !== false) $btn_disabled = "";

            if($row["month_end"] == "1") $month_end = "<span class='badge badge-warning' style='font-size:10px;'>Month End</span>";
            else $month_end="";

            if($row['qty_outstanding'] >0){
              echo "<tr id='row_".$row['doc_no']."'>";
                echo "<td>".$row['doc_datetime']."</td>";
                echo "<td>".$row['doc_no']."</td>";
                echo "<td>".$row['so_no']."</td>";
                echo "<td>".$row['bill_cust_no']."</td>";
                echo "<td>".$row['bill_cust_name']."</td>";
                echo "<td>".$row['external_document']."</td>";
                echo "<td>".$row['doc_location_code']."</td>";
                echo "<td>".$row['uname']."</td>";

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
                echo "<td>".$row['qty_to_picked']."</td>";
                echo "<td>".$row['qty_outstanding']."</td>";
                echo "<td>".$row['uom']."</td>";
                echo "<td>".$row['sts_name']." ".$month_end."</td>";
                echo "<td><button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."')>Detail</button></td>";
                echo "<td><a href='".base_url()."index.php/wms/outbound/checking/print?id=".$row['doc_no']."' class='btn btn-outline-primary btn-sm' target='_blank'>PRINT</a></td>";
                echo "<td><a href ='".base_url()."index.php/wms/outbound/picking/new?id=".$row['doc_no']."&docdate=".$row['doc_date']."&whs=".$row['doc_location_code']."&srclink=".base_url()."index.php/wms/outbound/whship/warehouse/' class='btn btn-sm btn-primary ".$btn_disabled."' >Picking</a></td>";
              echo "</tr>";
            }
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




</script>
