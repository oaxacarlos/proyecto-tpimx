<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Customers'
          }
        ],
        pageLength:30,
        order: [[2, 'desc']]
    });
});
</script>

<style>
  tr{
    font-size: 12px;
  }
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
    Customers
</div>

<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead>
    <tr>
      <th>Cust No</th>
      <th>Cust Name</th>
      <th>City</th>
      <th>County</th>
      <th>Country Region Code</th>
      <th>Phone No</th>
      <th>VAT No</th>
      <th>Payment Terms</th>
      <th>Sales Person Code</th>
      <th>Sales Person Name</th>
      <th>CS Code</th>
      <th>CS Name</th>
    </tr>
  </thead>
  <body>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["cust_no"]."</td>";
            echo "<td>".$row["cust_name"]."</td>";
            echo "<td>".$row["city"]."</td>";
            echo "<td>".$row["county"]."</td>";
            echo "<td>".$row["country_region_code"]."</td>";
            echo "<td>".$row["phone_no"]."</td>";
            echo "<td>".$row["vat_no"]."</td>";
            echo "<td>".$row["payment_terms_code"]."</td>";
            echo "<td>".$row["sales_person_code"]."</td>";
            echo "<td>".$row["sls_name"]."</td>";
            echo "<td>".$row["cs_person"]."</td>";
            echo "<td>".$row["cs_name"]."</td>";
          echo "</tr>";
      }
    ?>
  </body>
</table>
