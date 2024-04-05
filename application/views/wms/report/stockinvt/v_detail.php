<table class="table table-bordered">
  <thead>
    <tr>
      <th>ITEM</th>
      <th>LOCATION</th>
      <th>QTY</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_item as $row){
          echo "<tr>";
            echo "<td>".$row["item_code"]."</td>";
            echo "<td>".$row["location_code"]."</td>";
            echo "<td>".$row["qty"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
