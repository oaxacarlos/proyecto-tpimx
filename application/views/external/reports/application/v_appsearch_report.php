<table class="table table-bordered table-striped" onselectstart="return false" >
  <thead>
    <tr>
      <th class="table-dark">Part Number</th>
      <th class="table-dark">Product</th>
      <th class="table-dark">Note</th>
    </tr>
  </thead>

  <tbody>
    <?php
      foreach($var_report as $row){
        echo "<tr>";
          echo "<td>".$row["partnumber"]."</td>";
          echo "<td>".$row["product"]."</td>";
          echo "<td>".$row["note"]."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>
