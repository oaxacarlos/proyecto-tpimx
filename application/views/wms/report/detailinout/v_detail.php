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
      <th>Qty</th>
      <th>Uom</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        foreach($var_detail as $row){
            if($doc_type == 1){ $qty = $row['qty']; }
            else if($doc_type == 2){ $qty = $row['qty_to_ship']; }

            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".number_format($qty,2)."</td>";
              echo "<td>".$row['uom']."</td>";
            echo "</tr>";
            $no++;
        }
    ?>
  </tbody>
</table>

<script>

</script>
