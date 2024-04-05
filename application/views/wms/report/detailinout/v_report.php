<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Detail-InOutBound'
          }
        ],
    });
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th>Date</th>
        <th>Doc No</th>
        <th>SO</th>
        <th>Cust No</th>
        <th>Cust Name</th>
        <th>Postal Code</th>
        <th>Type</th>
        <th>WHS</th>
        <th>Qty</th>
        <th>Uom</th>
        <th>Doc Status</th>
        <th>Message</th>
        <th>Finished<br>Packing</th>
        <th>Action</th>
        <th>Action 2</th>
      </tr>
    </thead>
    <tbody>
        <?php
          foreach($var_report as $row){
              if($row["doc_type"] == "1"){
                  $link = "timelinewhrcpt";
                  $type = "InBound";
              }
              else if($row["doc_type"]=="2"){
                 $link = "timelinewhshipment";
                 $type = "OutBound";
              }

              if($row["month_end"] == "1") $month_end = "<span class='badge badge-warning' style='font-size:10px;'>Month End</span>";
              else $month_end="";

              echo "<tr>";
                echo "<td>".$row["doc_datetime"]."</td>";
                echo "<td>".$row["doc_no"]."</td>";
                echo "<td>".$row['so_no']."</td>";
                echo "<td>".$row['bill_cust_no']."</td>";
                echo "<td>".$row['bill_cust_name']."</td>";
                echo "<td>".$row['ship_to_post_code']."</td>";
                echo "<td>".$type."</td>";
                echo "<td>".$row["doc_location_code"]."</td>";
                echo "<td>".$row["qty_to_ship"]."</td>";
                echo "<td>".$row["uom"]."</td>";

                if($row["canceled"] == 0) echo "<td>".$row["status_name"]." ".$month_end."</td>";
                else{
                    echo "<td>
                      <button type='button' class='btn btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='".$row["canceled_text"]."'>Canceled</button>
                    </td>";
                }

                echo "<td>".$row["text"]."</td>";
                echo "<td>".$row["pack_finished_datetime"]."</td>";
                echo "<td>";
                  echo "<button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['doc_no']."','".$row["doc_type"]."')>Detail</button>";
                echo "</td>";
                echo "<td>";
                  echo "<a href='".base_url()."index.php/wms/report/".$link."?doc_no=".$row['doc_no']."' target='_blank' class='btn btn-sm btn-outline-primary' style='margin-left:5px;'>Timeline</a>";
                echo "</tr>";
              echo "</tr>";
          }
        ?>
    </tbody>
</table>

<script>

function f_show_detail(id,doc_type){
  var link = 'wms/report/detailinout/v_detail';
  data = {'id':id, 'link':link, 'doc_type':doc_type }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/report/detailinout/get_detail",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

</script>
