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

<div class="modal" id="myModalDetailRack">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Rack</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_rack"></div>
    </div>
  </div>
</div>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Stock Rack
</div>


<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Item</th>
      <th>Name</th>
      <th>Available</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_stock_invt as $row){
            echo "<tr id='row_".$item_code."'>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['name']."</td>";
              echo "<td id='aval_".$row['item_code']."'>".$row['available']."</td>";
              echo "<td>";
                echo "<button class='btn btn-outline-primary' onclick=f_refresh_item('".$row['item_code']."')><i class='bi-arrow-clockwise'></i></button>";
                echo "<button class='btn btn-outline-danger btn-sm' onclick=f_rack_item('".$row['item_code']."') style='margin-left:10px;'>Rack</button>";
              echo "</td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_refresh_item(item_code){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/report/stockrack/get_item_invt_by_code",
      type : "post",
      dataType  : 'html',
      data : {item_code:item_code},
      success: function(data){
          var responsedata = $.parseJSON(data);

          $("#aval_"+item_code).text("");

          setTimeout(function(){
            $("#aval_"+item_code).text(responsedata.available);
          },500);
      }
  })
}
//---

function f_rack_item(id){
  var link = 'wms/report/stockrack/v_rack';
  data = {'id':id, 'link':link }
  $('#modal_detail_rack').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_rack').load(
      "<?php echo base_url();?>index.php/wms/report/stockrack/get_rack_item",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetailRack').modal();
}
//---

</script>
