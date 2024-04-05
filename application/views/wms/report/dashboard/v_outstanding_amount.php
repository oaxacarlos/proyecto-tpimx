<table class="table table-bordered">
    <tr>
      <th>Name</th>
      <th>Total</th>
    </tr>
    <tbody>
      <?php
        foreach($var_report as $row){
            echo "<tr>";
              echo "<td>".$row["total_text"]."</td>";
              echo "<td>".$row["total"]."</td>";
            echo "</tr>";
        }
      ?>
    </tbody>
</table>
