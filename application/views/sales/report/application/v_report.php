<table class="table table-bordered table-striped">
  <thead>
    <th>Product</th>
    <th>Part Number</th>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["product"]."</td>";
            echo "<td>".$row["partnumber"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
