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

<div style='padding-bottom:20px;'>
  <button class='btn btn-success btn-sm' id='btn_export_excel'>Export to Excel</button>
</div>

<table id="result" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>EMC Number</th>
      <th>CreatedDate</th>
      <th>Name</th>
      <th>DepartCode</th>
      <th>DepartName</th>
      <th>GI/GR</th>
      <th>DepotCode</th>
      <th>DepotName</th>
      <th>MatId</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Num</th>
      <th class="table-dark">ResvNo</th>
      <th class="table-dark">Resv Matid</th>
      <th class="table-dark">Resv Qty</th>
      <th class="table-dark">Resv Uom</th>
      <th class="table-dark">Num</th>
      <th class="table-dark">Balance</th>
    </tr>
  </thead>
  <tbody>
  <?php

  if($v_list_empc_balance == 0){}
  else{
    $i=1;
    foreach($v_list_empc_balance as $row){
        if($row['balance'] == 0) {
            $td_empc_h_code = "class='badge badge-success' style='font-size:11px;'";
            $td_balance = "class='badge badge-success' style='font-size:12px;'";
            $td_resvno = "class='badge badge-success' style='font-size:12px;'";
        }
        else{
            $td_empc_h_code = "";
            $td_balance = "";
            $td_resvno = "";
        }

        echo "<tr>";
          echo "<td>".$i."</td>";
          echo "<td><span ".$td_empc_h_code.">".$row['empc_h_code']."</span></td>";
          echo "<td>".$row['empc_h_created_date']."</td>";
          echo "<td>".$row['name']."</td>";
          echo "<td>".$row['depart_code']."</td>";
          echo "<td>".$row['depart_name']."</td>";
          echo "<td>".$row['empc_type_name']."</td>";
          echo "<td>".$row['plant_code']."</td>";
          echo "<td>".$row['plant_name']."</td>";
          echo "<td>".$row['mat_id']."</td>";
          echo "<td>".$row['qty']."</td>";
          echo "<td>".$row['uom']."</td>";
          echo "<td>".$row['posnr']."</td>";
          echo "<td><span ".$td_resvno.">".$row['sap_no']."</span></td>";
          echo "<td>".$row['sap_matid']."</td>";
          echo "<td>".round($row['gi_qty'],3)."</td>";
          echo "<td>".$row['gi_uom']."</td>";
          echo "<td>".$row['empcps']."</td>";
          echo "<td><span ".$td_balance.">".round($row['balance'],3)."</span></td>";
        echo "</tr>";
        $i++;
    }
  }

  ?>
  </tbody>
</table>

<script>
jQuery(document).ready(function() {

    $('#btn_export_excel').on('click', function(e){
        e.preventDefault();
        ResultsToTable();
    });

    function ResultsToTable(){
        $("#result").table2excel({
            exclude: ".noExl",
            name: "Results",
      filename:'EMC-Balance',
        });
    }
});
</script>
