<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
		    buttons: [
          {
            extend: 'excel',
            title : 'EMC-TrackReport'
          }
        ],
    });
});
</script>

<style>
  tr{
      font-size: 12px;
  }
</style>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>EMC Number</th>
      <th>CreatedDate</th>
      <th>Name</th>
      <th>Type</th>
      <th>DepotCode</th>
      <th>DepotName</th>
      <th>MatId</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>ResvNo</th>
      <th>SAP MatDoc</th>
      <th>SAP MatDocType</th>
      <th>SAP MatDocDate</th>
      <!--<th>TranfReq</th>
      <th>TranfOrderNo</th>
      <th>ConfirmDate</th>-->
      <th>Resv Matid</th>
      <th>Resv Qty</th>
      <th>Resv Uom</th>
      <th>Plant</th>
    </tr>
  </thead>
  <tbody>
  <?php

  if($v_list_empc_tracking == 0){}
  else{
    foreach($v_list_empc_tracking as $row){
        echo "<tr>";
          echo "<td>".$row['empc_h_code_empc']."</td>";
          echo "<td>".$row['empc_h_created_date']."</td>";
          echo "<td>".$row['name']."</td>";
          echo "<td>".$row['empc_type_name']."</td>";
          echo "<td>".$row['plant_code']."</td>";
          echo "<td>".$row['plant_name']."</td>";
          echo "<td>".$row['mat_id']."</td>";
          echo "<td>".$row['qty']."</td>";
          echo "<td>".$row['uom']."</td>";
          echo "<td class='table-success'>".$row['sap_no']."</td>";
          echo "<td class='table-success'>".$row['sap_matdoc']."</td>";
          echo "<td class='table-success'>".$row['bwart_mat']."</td>";
          echo "<td class='table-success'>".$row['sap_matdoc_date']."</td>";
          //echo "<td>".$row['tbnum']."</td>";
          //echo "<td>".$row['tanum']."</td>";
          //echo "<td>".$row['qdatu']."</td>";
          echo "<td class='table-success'>".$row['sap_matid']."</td>";
          echo "<td class='table-success'>".$row['rsv_qty']."</td>";
          echo "<td class='table-success'>".$row['rsv_uom']."</td>";
          echo "<td class='table-success'>".$row['lgnum']."</td>";
        echo "</tr>";

    }
  }

  ?>
  </tbody>
</table>
