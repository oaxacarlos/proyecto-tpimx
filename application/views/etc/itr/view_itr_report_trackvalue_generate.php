<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
		    buttons: [
          {
            extend: 'excel',
            title : 'ITR-TrackValueReport'
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
      <th>ITR Number</th>
      <th>CreatedDate</th>
      <th>Name</th>
      <th>Type</th>
      <th>Project</th>
      <th>DepotCode</th>
      <th>DepotName</th>
      <th>Customer</th>
      <th>MatId</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>ResvNo</th>
      <th>SAP MatDoc</th>
      <th>SAP MatDocDate</th>
      <!--<th>TranfReq</th>
      <th>TranfOrderNo</th>
      <th>ConfirmDate</th>-->
      <th>Resv Matid</th>
      <th>Resv Qty</th>
      <th>Resv Uom</th>
      <th>Plant</th>
      <th>Value</th>
      <th>Curr</th>
    </tr>
  </thead>
  <tbody>
  <?php

  if($v_list_itr_tracking == 0){}
  else{
    foreach($v_list_itr_tracking as $row){
        echo "<tr>";
          echo "<td>".$row['itr_h_code_itr']."</td>";
          echo "<td>".$row['itr_h_created_date']."</td>";
          echo "<td>".$row['name']."</td>";
          echo "<td>".$row['itr_type_name']."</td>";
          echo "<td>".$row['itr_project_name']."</td>";
          echo "<td>".$row['plant_code']."</td>";
          echo "<td>".$row['plant_name']."</td>";
          echo "<td>".$row['customer_text']."</td>";
          echo "<td>".$row['mat_id']."</td>";
          echo "<td>".$row['qty']."</td>";
          echo "<td>".$row['uom']."</td>";
          echo "<td class='table-success'>".$row['sap_no']."</td>";
          echo "<td class='table-success'>".$row['sap_matdoc']."</td>";
          echo "<td class='table-success'>".$row['sap_matdoc_date']."</td>";
          //echo "<td>".$row['tbnum']."</td>";
          //echo "<td>".$row['tanum']."</td>";
          //echo "<td>".$row['qdatu']."</td>";
          echo "<td class='table-success'>".$row['sap_matid']."</td>";
          echo "<td class='table-success'>".$row['rsv_qty']."</td>";
          echo "<td class='table-success'>".$row['rsv_uom']."</td>";
          echo "<td class='table-success'>".$row['lgnum']."</td>";
          echo "<td class='table-success'>".$row['dmbtr']."</td>";
          echo "<td class='table-success'>".$row['waers']."</td>";
        echo "</tr>";

    }
  }

  ?>
  </tbody>
</table>
