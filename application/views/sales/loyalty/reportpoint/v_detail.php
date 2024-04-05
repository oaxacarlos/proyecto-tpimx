<script>
$(document).ready(function() {
    //$('#DataTable2').DataTable();
});
</script>

<table id="DataTable2" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>Item</th>
      <th>Desc</th>
      <th>Qty</th>
      <th>Uom</th>
      <th>Point</th>
      <th>Point Got</th>
      <th>Rejected</th>
      <th>Rejected at</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_detail as $row){
            echo "<tr>";
              echo "<td>".$row['line']."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['desc']."</td>";
              echo "<td>".$row['qty']."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['point']."</td>";

              if($row["verified"] == "1") echo "<td>".$row['point']."</td>";
              else if($row["verified"] == "0") echo "<td>0</td>";
              else echo "<td></td>";

              if($row["verified"] == "1") echo "<td><i class='bi bi-check-lg'></i></td>";
              else if($row["verified"] == "0") echo "<td><i class='bi bi-x-lg'></i></td>";
              else echo "<td></td>";

              if($row["verified"] == "1") echo "<td>".$row['verified_at']."</td>";
              else if($row["verified"] == "0") echo "<td>".$row['rejected_at']."</td>";
              else echo "<td></td>";

            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

</script>
