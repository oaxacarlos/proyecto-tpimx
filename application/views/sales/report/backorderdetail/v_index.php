<script>
$(document).ready(function() {

    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'TPM-BackOrderDetail'
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

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Backorder Detail
</div>

<div style="margin-top:20px;">
  <div class="container">
    <span class="badge badge-danger">Actualizar cada una hora</span>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px">
<table class="table table-bordered table-striped table-sm" id="DataTable">
  <thead>
    <tr>
      <th>Document Date</th>
      <th>Document No</th>
      <th>Line No</th>
      <th>Item No</th>
      <th>Item No SET</th>
      <th>Primary/Secondary</th>
      <th>Description</th>
      <th>Location</th>
      <th>Sell to Cust No</th>
      <th>Sell to Cust Name</th>
      <th>External Doc No</th>
      <th>ShipTo Address</th>
      <th>ShipTo Address 2</th>
      <th>ShipTo City</th>
      <th>ShipTo County</th>
      <th>ShipTo Post Code</th>
      <th>ShipTo Ctry Code</th>
      <th>Year</th>
      <th>Month</th>
      <th>Item Cat</th>
      <th>Sales<br>Person</th>
      <th>Sales<br>Person<br>Name</th>
      <th>Qty Order</th>
      <th>Qty Shipped</th>
      <th>Qty OutStanding</th>
      <th>Price</th>
      <th>Amount OutStanding</th>
      <th>Disponible</th>
      <th>Incoming</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
          echo "<tr>";
            echo "<td>".$row["document_date"]."</td>";
            echo "<td>".$row["document_no"]."</td>";
            echo "<td>".$row["line_no"]."</td>";
            echo "<td>".$row["item_no"]."</td>";
            echo "<td>".$row["item_code_set"]."</td>";
            echo "<td>".$row["text1"]."</td>";
            echo "<td>".$row["description"]."</td>";
            echo "<td>".$row["location_code"]."</td>";
            echo "<td>".$row["sell_to_customer_no"]."</td>";
            echo "<td>".$row["sell_to_customer_name"]."</td>";
            echo "<td>".$row["external_document_no"]."</td>";
            echo "<td>".$row["ship_to_addr"]."</td>";
            echo "<td>".$row["ship_to_addr2"]."</td>";
            echo "<td>".$row["ship_to_city"]."</td>";
            echo "<td>".$row["ship_to_county"]."</td>";
            echo "<td>".$row["ship_to_post_code"]."</td>";
            echo "<td>".$row["ship_to_ctry_code"]."</td>";
            echo "<td>".$row["year_doc_date"]."</td>";
            echo "<td>".$row["month_doc_date"]."</td>";
            echo "<td>".$row["item_category_code"]."</td>";
            echo "<td>".$row["salesperson_code"]."</td>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["qty"]."</td>";
            echo "<td>".$row["qty_shipped"]."</td>";
            echo "<td>".$row["qty_outstanding"]."</td>";
            echo "<td>".$row["unit_price"]."</td>";
            echo "<td>".$row["amount_outstanding"]."</td>";
            echo "<td>".$row["qty_available"]."</td>";
            echo "<td>".$row["estimation_arrived"]."</td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
</div>
