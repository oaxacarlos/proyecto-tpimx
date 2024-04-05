<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<div class="modal" id="myModalDetaild3" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Serial Number</h4>
      </div>
      <div class="modal-body" id="modal_detail_d3"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id='btn_close_modal_d3'>Close</button>
      </div>
    </div>
  </div>
</div>

<table id="DataTable_d2" class="table table-bordered table-striped table-sm">
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
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        foreach($var_putaway_d2 as $row){
            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".number_format($row['qty'],2)."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['location_code']."</td>";
              echo "<td>".$row['zone_code']."</td>";
              echo "<td>".$row['area_code']."</td>";
              echo "<td>".$row['rack_code']."</td>";
              echo "<td>".$row['bin_code']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail_d3('".$row['doc_no']."','".$row['line_no']."','".$row['src_line_no']."','".$row['src_no']."')>DETAIL S/N</button>
              </td>";
            echo "</tr>";
            $no++;
        }
    ?>
  </tbody>
</table>


<script>

function f_show_detail_d3(id, line_no, src_line_no, src_no){
  var link = 'wms/inbound/v_putaway_list_data_d3';
  data = {'id':id, 'link':link, 'line_no':line_no, 'src_line_no':src_line_no, 'src_no':src_no }
  $('#modal_detail_d3').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_d3').load(
      "<?php echo base_url();?>index.php/wms/inbound/putaway/get_putaway_list_d3",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetaild3').modal();

}
//---

$('#btn_close_modal_d3').click(function(){
    $('#myModalDetaild3').modal('hide');
})

</script>
