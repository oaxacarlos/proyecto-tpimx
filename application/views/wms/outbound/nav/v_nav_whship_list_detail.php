<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>Item No</th>
      <th>Desc</th>
      <th>Qty to Ship</th>
      <th>Uom</th>
      <th>Source</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        foreach($var_whship_list_d as $row){
            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row['item_no']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".number_format($row['qty_to_ship'],2)."</td>";
              echo "<td>".$row['uom']."</td>";
              echo "<td>".$row['src_no']."</td>";
            echo "</tr>";
            $no++;
        }
    ?>
  </tbody>
</table>

<script>

</script>
