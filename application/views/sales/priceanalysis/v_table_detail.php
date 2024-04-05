<style>
  tr{
    font-size: 11px;
  }

  th#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

</style>

<table class="table table-bordered table-sm table-striped">
  <thead>
    <th>Price</th>
    <?php
      foreach($var_year as $row){
          echo "<th>Sum of ".$row."</th>";
      }
    ?>
  </thead>

  <tbody>
    <?php
      foreach($var_detail as $row){
          echo "<tr>";
            echo "<td id='number'>".format_number($row["unit_price"],1,2)."</td>";
            foreach($var_year as $row2){
                echo "<td id='number'>".format_number($row["qty_".$row2],1,0)."</td>";
            }
          echo "</tr>";
      }
    ?>
  </tbody>

</table>
