<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 0, "asc" ]],
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Inventory-Incoming'
          }
        ],
    });
});
</script>

<div style="margin-top:20px;">
<table class="table table-bordered table-striped table-sm" id="DataTable" >

<thead>
  <th>Container No</th>
  <th>Item No</th>
  <th>QTY</th>
  <th>Estimation Date</th>
  <th>Arrived Date</th>
</thead>
<tbody>
<?php

  foreach($var_report as $row){
      echo "<tr>";
        echo "<td>".$row["container_no"]."</td>";
        echo "<td>".$row["item_no"]."</td>";
        echo "<td>".$row["qty"]."</td>";
        echo "<td>".$row["estimation_arrived"]."</td>";
        echo "<td>".$row["arrived"]."</td>";
      echo "</tr>";
  }

?>
</tbody>

</table>
</div>
