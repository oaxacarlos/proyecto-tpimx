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
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        foreach($var_putaway_d as $row){
            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".number_format($row['qty_to_put'],2)."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail_d2('".$row['doc_no']."','".$row['line_no']."','".$row['src_line_no']."','".$row['src_no']."')>DETAIL PUT AWAY</button>
              </td>";
            echo "</tr>";
            $no++;
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail_d2(id,line_no, src_line_no, src_no){
  var link = 'wms/inbound/v_putaway_list_data_d2';
  data = {'id':id, 'link':link, 'line_no':line_no, 'src_line_no':src_line_no, 'src_no':src_no }
  $('#modal_detail_d2').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail_d2').load(
      "<?php echo base_url();?>index.php/wms/inbound/putaway/get_putaway_list_d2",
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
