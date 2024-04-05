<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Tracking No</th>
      <th>Sales Order No</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["comment"]."</td>";
            echo "<td>".$row["no"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
