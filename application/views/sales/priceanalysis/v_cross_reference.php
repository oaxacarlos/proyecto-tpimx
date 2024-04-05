<table class="table table-bordered table-striped table-sm" style="font-size:12px;">
  <thead>
    <tr class="table-info">
      <th>No</th>
      <th>Brand-Year-Model-Cil</th>
    </tr>
  </thead>
  <tbody>
    <?php
      if(!$var_detail){
          echo "<tr><td>No Data</td></tr>";
      }
      else{
        $i=1;
        foreach($var_detail as $row){
            $text = $row["brand"]." ".$row["anio"]." ".$row["modelo"]." ".$row["no_cli"];
            echo "<tr><td>".$i."</td><td>".$text."</td></tr>";
            $i++;
        }
      }

    ?>
  </tbody>
</table>
