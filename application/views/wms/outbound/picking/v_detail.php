<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<div class="modal" id="myModalDetaild2" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Put Away</h4>
      </div>
      <div class="modal-body" id="modal_detail_d2"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id='btn_close_modal_d2'>Close</button>
      </div>
    </div>
  </div>
</div>

<table id="DataTable_data" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>Item No</th>
      <th>Desc</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Loc</th>
      <th>Zone</th>
      <th>Area</th>
      <th>Rack</th>
      <th>Bin</th>
      <th>Picked<br>DateTime</th>
      <th>Complete<br>DateTime</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        foreach($var_picking_d as $row){
            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".number_format($row['qty_to_picked'],2)."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['location_code']."</td>";
              echo "<td>".$row['zone_code']."</td>";
              echo "<td>".$row['area_code']."</td>";
              echo "<td>".$row['rack_code']."</td>";
              echo "<td>".$row['bin_code']."</td>";
              echo "<td>".$row['picked_datetime']."</td>";
              echo "<td>".$row['completely_picked']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail_d2('".$row['doc_no']."','".$row['line_no']."','".$row['src_line_no']."','".$row['src_no']."')>DETAIL SN</button>
              </td>";
            echo "</tr>";
            $no++;
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail_d2(id,line_no, src_line_no, src_no){
  var link = 'wms/outbound/picking/v_detail2';
  data = {'id':id, 'link':link, 'line_no':line_no, 'src_line_no':src_line_no, 'src_no':src_no }
  $('#modal_detail_d2').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_d2').load(
      "<?php echo base_url();?>index.php/wms/outbound/picking/get_picking_list_d2",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetaild2').modal();
}
//---

$('#btn_close_modal_d2').click(function(){
    $('#myModalDetaild2').modal('hide');
})

</script>
