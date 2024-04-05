<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 0, "asc" ]],
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

<div style="margin-top:20px;">
  <div class="container">
    <span class="badge badge-danger">Actualizar cada una hora</span>
  </div>
</div>

<div style="margin-top:20px;">
<table class="table table-bordered table-striped table-sm" id="DataTable" >

<thead>
  <th>Item No</th>
  <th>Name</th>
  <th>WH1<br>QTY</th>
  <th>WH2<br>QTY</th>
  <th>WH3<br>QTY</th>
  <th>WH4<br>QTY</th>
  <th>Incoming<br>QTY</th>
  <th>PO<br>QTY</th>
</thead>
<tbody>
<?php

  foreach($var_report as $row){
      echo "<tr>";
        echo "<td>".$row["code"]."</td>";
        echo "<td>".$row["name"]."</td>";
        echo "<td>".$row["qty_wh1"]."</td>";
        echo "<td>".$row["qty_wh2"]."</td>";
        echo "<td>".$row["qty_wh3"]."</td>";
        echo "<td>".$row["qty_wh4"]."</td>";
        echo "<td>".$row["qty_incoming"]."</td>";
        echo "<td>".$row["qty_po"]."</td>";
      echo "</tr>";
  }

?>
</tbody>

</table>
</div>
