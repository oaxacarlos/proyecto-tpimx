<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>Doc No</th>
      <th>WHS</th>
      <th>User</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Doc Status</th>
      <th>Message</th>
      <th>WH Transfer From</th>
      <th>WH Transfer Doc</th>
      <th>Action</th>
      <th>Action 2</th>
      <th>Action 3</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $j=0;
        foreach($var_received as $row){
            echo "<tr id='row_".$row['doc_no']."'>";
              echo "<td>".$row['doc_datetime']."</td>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['doc_location_code']."</td>";
              echo "<td>".$row['uname']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['sts_name']."</td>";
              echo "<td>".$row['text']."</td>";
              echo "<td>".$row['from_wh']."</td>";
              echo "<td id='transfer_from_wh_".$j."'>".$row['transfer_from_wh']."</td>";
              echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_show_detail('".$row['doc_no']."',2,".$j.")>DETAIL</button></td>";

              // check if need to gen sn
              $new_wh_transfer_no_gen_sn = explode("|",$wh_transfer_no_gen_sn);
              $no_need_gen_sn = 0;
              for($i=0;$i<count($new_wh_transfer_no_gen_sn);$i++){
                  $temp_combine_wh = $row['from_wh']."-".$row['doc_location_code'];
                  if($temp_combine_wh == $new_wh_transfer_no_gen_sn[$i]){
                      $no_need_gen_sn = 1;
                  }
              }
              //--

              if($row['status_h'] == '4'){
                if($no_need_gen_sn == 0){
                  echo "<td>
                    <button class='btn btn-sm btn-primary' onclick=f_show_detail('".$row['doc_no']."',1,".$j.")>Generate S/N</button>
                    </td>";
                  echo "<td></td>";
                }
                else{
                  echo "<td>
                    <button class='btn btn-sm btn-warning' onclick=f_show_detail('".$row['doc_no']."',0,".$j.")>TRANSFER S/N</button>
                    </td>";
                  echo "<td></td>";
                }
              }

              if($row['status_h'] == '5'){
                echo "<td>
                  <a href='".base_url()."index.php/wms/barcode/print_barcode_by_doc?doctype=received&docno=".$row['doc_no']."' target='_blank' class='btn btn-success'>Barcode</a>
                  <br>Print : ".$row["print_barcode"]."
                </td>";

                echo "<td>
                  <a href='".base_url()."index.php/wms/barcode/print_master_barcode_by_doc?doctype=received2&docno=".$row['doc_no']."' target='_blank' class='btn btn-success'>Master Barcode</a>
                  <br>Print : ".$row["print_master_barcode"]."
                </td>";
              }

            echo "</tr>";

            $j++;
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(id,gen,idx){

  var link = 'wms/inbound/v_gen_sn_list_data';
  var whship_no = $("#transfer_from_wh_"+idx).text();
  data = {'id':id, 'link':link, 'gen' : gen, 'whship_no':whship_no }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/inbound/received/get_gen_sn_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

</script>
