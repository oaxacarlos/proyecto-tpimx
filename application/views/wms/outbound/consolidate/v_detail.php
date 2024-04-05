<table class="table table-bordered">
  <thead>
    <tr>
      <th>Created</th>
      <th>Packing No</th>
    </tr>
  </thead>
  <tbody>
      <?php
      foreach($var_console_detail as $row){
          echo "<tr>";
            echo "<td>".$row["created_datetime"]."</td>";
            echo "<td>".$row["src_no"]."</td>";
          echo "</tr>";
      }
      ?>
  </tbody>
</table>
