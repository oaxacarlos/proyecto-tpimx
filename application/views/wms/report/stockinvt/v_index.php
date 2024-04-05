<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Inventory'
          }
        ],
    });
});
</script>

<style>
.table-striped>tbody>tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}

.attention {
  animation: 3s attention;
}

@keyframes attention {
  0% {
    background-color: #bcf516;
  }
}
</style>

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

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Stock Inventory
</div>


<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Item</th>
      <th>Name</th>
      <th>Extraction</th>
      <th>Available</th>
      <th>Picking</th>
      <th>Picked</th>
      <th>Packing</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_stock_invt as $row){
            echo "<tr id='row_".$item_code."'>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['name']."</td>";
              echo "<td id='extc_".$row['item_code']."'>".$row['extraction']."</td>";
              echo "<td id='aval_".$row['item_code']."'><a href='#' onclick=f_show_detail('".$row['item_code']."','1')>".$row['available']."</a></td>";
              echo "<td id='pick_".$row['item_code']."'>".$row['picking']."</td>";
              echo "<td id='picked_".$row['item_code']."'>".$row['picked']."</td>";
              echo "<td id='pack_".$row['item_code']."'>".$row['packing']."</td>";
              echo "<td><button class='btn btn-outline-primary' onclick=f_refresh_item('".$row['item_code']."')><i class='bi-arrow-clockwise'></i></button></td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_refresh_item(item_code){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/report/stockinvt/get_item_invt_by_code",
      type : "post",
      dataType  : 'html',
      data : {item_code:item_code},
      success: function(data){
          var responsedata = $.parseJSON(data);

          $("#extc_"+item_code).text("");
          $("#aval_"+item_code).text("");
          $("#pick_"+item_code).text("");
          $("#picked_"+item_code).text("");
          $("#pack_"+item_code).text("");

          setTimeout(function(){
            $("#extc_"+item_code).text(responsedata.extraction);
            $("#aval_"+item_code).text(responsedata.available);
            $("#pick_"+item_code).text(responsedata.picking);
            $("#picked_"+item_code).text(responsedata.picked);
            $("#pack_"+item_code).text(responsedata.packing);
          },500);
      }
  })
}
//---

function f_show_detail(item_code,status){
    data = { 'item_code':item_code, 'status':status}

    $('#modal_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_detail').load(
        "<?php echo base_url();?>index.php/wms/report/stockinvt/get_detail_by_location",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalDetail').modal();


}
//---

</script>
