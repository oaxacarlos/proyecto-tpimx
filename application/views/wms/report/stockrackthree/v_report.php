<table class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th colspan='3' style="text-align:center;"><?php echo $location ?></th>
    </tr>
    <tr class="table-info">
      <th>Master Barcode</th>
      <th>Item Code</th>
      <th>Item Name</th>
      <th>Status</th>
      <th>Qty</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["sn2"]."</td>";
            echo "<td>".$row["item_code"]."</td>";
            echo "<td>".$row["item_name"]."</td>";
            echo "<td>".$row["sts_name"]."</td>";
            echo "<td>".$row["qty"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
