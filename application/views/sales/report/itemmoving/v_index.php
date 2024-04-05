<style>
  tr{
    font-size: 12px;
  }

  th#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

  th { position: sticky; top: 0; }

</style>

<div class="container">
  <table class="table table-bordered table-striped table-sm" style="margin-top:20px;">
    <thead>
      <tr>
        <th id="title" class="table-dark">Item</th>
        <th id="title" class="table-dark">Name</th>
        <th id="title" class="table-dark">MAS 120 DIAS (QTY)</th>
        <th id="title" class="table-dark">MAS 180 DIAS (QTY)</th>
        <th id="title" class="table-dark">MAS 240 DIAS (QTY)</th>
        <th id="title" class="table-dark">MAS 120 DIAS (MXN)</th>
        <th id="title" class="table-dark">MAS 180 DIAS (MXN)</th>
        <th id="title" class="table-dark">MAS 240 DIAS (MXN)</th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach($var_report as $row){
            echo "<tr>";
              echo "<td>".$row["code"]."</td>";
              echo "<td>".$row["name"]."</td>";
              echo "<td id='number'>".format_number($row["last_120days_qty"],1,0)."</td>";
              echo "<td id='number'>".format_number($row["last_180days_qty"],1,0)."</td>";
              echo "<td id='number'>".format_number($row["last_240days_qty"],1,0)."</td>";
              echo "<td id='number'>".format_number($row["last_120days_amount"],1,1)."</td>";
              echo "<td id='number'>".format_number($row["last_180days_amount"],1,1)."</td>";
              echo "<td id='number'>".format_number($row["last_240days_amount"],1,1)."</td>";
            echo "</tr>";
        }
      ?>
    </tbody>
  </table>
</div>
