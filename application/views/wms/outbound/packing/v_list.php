<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 0, "desc" ]]
    });
});
</script>

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

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>SO</th>
      <th>Cust No</th>
      <th>Cust Name</th>
      <th>City</th>
      <th>Ext Doc</th>
      <th>WHS</th>
      <th>Created User</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Action</th>
      <th>Action 2</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_packing as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['so_no']."</td>";
              echo "<td>".$row['bill_cust_no']."</td>";
              echo "<td>".$row['bill_cust_name']."</td>";
              echo "<td>".$row['ship_to_city']."</td>";
              echo "<td>".$row['external_document']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['qty_to_ship']."</td>";
              //echo "<td>".$row['qty_outstanding']."</td>";
              //echo "<td>".$row['qty_to_picked']."</td>";
              //echo "<td>".$row['qty_has_picked']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";

              /*echo "<td>
                <a href='".base_url()."index.php/wms/outbound/packing/gotopack_man?id=".$row['doc_no']."&whs=".$row['doc_location_code']."' class='btn btn-sm btn-primary'>GoTo Pack</a>
              </td>";*/
              echo "<td><button onclick=f_check_submit_nav('".$row['doc_no']."','".$row['doc_location_code']."') class='btn btn-sm btn-primary'>GoTo Pack</button></td>";
              echo "<td>
                <a href='".base_url()."index.php/wms/barcode/print_barcode_by_doc?doctype=whshipment&docno=".$row['doc_no']."' class='btn btn-sm btn-success' target=_blank>Print Barcode</a>
              </td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_check_submit_nav(docno,loc){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/outbound/packing/check_submit_navision",
      type : "post",
      dataType  : 'html',
      data : {docno:docno},
      success: function(data){
          var responsedata = $.parseJSON(data);

          if(responsedata == 1){
              window.location.href = "<?php echo base_url(); ?>index.php/wms/outbound/packing/gotopack_man?id="+docno+"&whs="+loc;
          }
          else if(responsedata == 0){
              Swal('Error!',"No ha enviado este documento a Navision",'error');
          }
      }
  })

}
//---

</script>
