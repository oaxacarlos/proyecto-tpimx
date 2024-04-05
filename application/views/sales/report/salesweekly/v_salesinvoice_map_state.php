<style>

  th#title{
    text-align: center;
  }

  td#number{
    text-align: right;
  }

</style>


<div class="container-fluid">
  TOP : <?php echo $top; ?>
</div>

<table class="table table-bordered table-sm table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Name</th>
        <th>Item Code</th>
        <th>Desc</th>
        <th>Amount</th>
        <th>Qty</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no = 1;
        foreach($var_report as $row){
            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row["name"]."</td>";
              echo "<td>".$row["item_code"]."</td>";
              echo "<td>".$row["description"]."</td>";
              echo "<td id='number'>".format_number($row["amount"],1,1)."</td>";
              echo "<td id='number'>".$row["qty"]."</td>";
            echo "</tr>";
            $no++;
        }

      ?>
    </tbody>
</table>
