<script>
$(document).ready(function() {

    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'TPM-Invoices'
          }
        ],
    });


});
</script>

<style>
  tr{
    font-size: 12px;
  }
</style>

<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead>
    <tr>
      <th>Posting Date</th>
      <th>Bill to Cust No</th>
      <th>Bill to Cust Name</th>
      <th>Sales Person Code</th>
      <th>Sales Person Name</th>
      <th>Doc No</th>
      <th>Doc Type</th>
      <th>Line No</th>
      <th>External Doc SALES INVOICE</th>
      <th>Your REF SALES INVOICE</th>
      <th>Location Code</th>
      <th>Item Code</th>
      <th>Description</th>
      <th>Sell To Customer No</th>
      <th>Quantity</th>
      <th>Currency Code</th>
      <th>Unit Price</th>
      <th>Amount</th>
      <th>Item Cat Code</th>
      <th>Year</th>
      <th>Month</th>
      <th>VAT Bus Posting Group</th>
      <th>ShipTo Code</th>
      <th>ShipTo Post Code</th>
      <th>ShipTo City</th>
      <th>ShipTo County</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["posting_date"]."</td>";
            echo "<td>".$row["bill_to_customer_no"]."</td>";
            echo "<td>".$row["bill_to_name"]."</td>";
            echo "<td>".$row["sales_person_code"]."</td>";
            echo "<td>".$row["slsname"]."</td>";
            echo "<td>".$row["invoice_no"]."</td>";
            echo "<td>".$row["doc_type"]."</td>";
            echo "<td>".$row["line_no"]."</td>";
            echo "<td>".$row["external_document_no"]."</td>";
            echo "<td>".$row["your_ref"]."</td>";
            echo "<td>".$row["location_code"]."</td>";
            echo "<td>".$row["item_code"]."</td>";
            echo "<td>".$row["description"]."</td>";
            echo "<td>".$row["sell_to_customer_no"]."</td>";
            echo "<td>".$row["quantity"]."</td>";
            echo "<td>".$row["currency_code"]."</td>";
            echo "<td>".$row["unit_price"]."</td>";
            echo "<td>".$row["amount"]."</td>";
            echo "<td>".$row["item_category_code"]."</td>";
            echo "<td>".$row["yearr"]."</td>";
            echo "<td>".$row["monthh"]."</td>";
            echo "<td>".$row["vat_bus_posting_group"]."</td>";
            echo "<td>".$row["ship_to_code"]."</td>";
            echo "<td>".$row["ship_to_post_code"]."</td>";
            echo "<td>".$row["ship_to_city"]."</td>";
            echo "<td>".$row["ship_to_county"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
