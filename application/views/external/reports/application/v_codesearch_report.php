<table class="table table-bordered table-striped" onselectstart="return false" >
  <thead>
    <tr>
      <th class="table-dark">Make</th>
      <th class="table-dark">Model</th>
      <th class="table-dark">Year</th>
      <th class="table-dark">Product</th>
      <th class="table-dark">Partnumber</th>
      <th class="table-dark">Engine</th>
    </tr>
  </thead>

  <tbody>
    <?php
      foreach($var_report as $row){
        echo "<tr>";
          echo "<td>".$row["make"]."</td>";
          echo "<td>".$row["model"]."</td>";
          echo "<td>".$row["year"]."</td>";
          echo "<td>".$row["product"]."</td>";
          echo "<td>".$row["partnumber"]."</td>";
          echo "<td>".$row["enginee"]."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>
