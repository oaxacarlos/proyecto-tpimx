<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>


<table class="table table-bordered table-sm table-striped" id="DataTable">
    <thead>
      <tr>
        <th>Date</th>
        <th>Doc No</th>
        <th>Name</th>
        <th>Email</th>
        <th>Redeem</th>
        <th>Lastest<br>Point</th>
        <th>Remain<br>Point</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Buy Date</th>
        <th>Sent Date</th>
        <th>Delv Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $colspan = 9;
        foreach($var_report as $row){
            echo "<tr>";
              echo "<td>".$row["created_at"]."</td>";
              echo "<td>".$row["doc_no"]."</td>";
              echo "<td>".$row["name"]."</td>";
              echo "<td>".$row["email"]."</td>";
              echo "<td>".$row["point_redeem"]."</td>";
              echo "<td>".$row["lastest_point"]."</td>";
              echo "<td>".$row["remain_point"]."</td>";
              echo "<td>".$row["product_name"]."</td>";
              echo "<td>".$row["qty"]."</td>";
              echo "<td>".$row["buy_date"]."</td>";
              echo "<td>".$row["sent_date"]."</td>";
              echo "<td>".$row["delivered_date"]."</td>";
            echo "</tr>";
        }

      ?>
    </tbody>
</table>
