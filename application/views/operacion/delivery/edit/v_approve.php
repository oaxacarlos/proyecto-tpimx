<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "paging": false
    });
});
</script>

<style>
  tr{
    font-size:12px;
  }
</style>

<table class="table table-sm table-striped" id="DataTable">
  <thead>
    <tr>
      <th>Doc Date</th>
      <th>Doc No</th>
      <th>Sending Date</th>
      <th>Destination</th>
      <th>State</th>
      <th>Driver</th>
      <th>Vendor No</th>
      <th>Vendor Name</th>
      <th>Tracking No</th>
      <th>Folio</th>
      <th>Domicili</th>
      <th>Payment Term</th>
      <th>Delivery Status</th>
      <th>SubTotal</th>
      <th>Total</th>
      <th>Remarks</th>
      <th>Box</th>
      <th>Pallet</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_data as $row){
          echo "<tr>";
            echo "<td>".$row["doc_date"]."</td>";
            echo "<td>".$row["doc_no"]."</td>";
            echo "<td>".$row["delv_date"]."</td>";
            echo "<td>".$row["destination"]."</td>";
            echo "<td>".$row["state"]."</td>";
            echo "<td>".$row["driver"]."</td>";
            echo "<td>".$row["vendor_no"]."</td>";
            echo "<td>".$row["vendor_name"]."</td>";
            echo "<td>".$row["tracking_no"]."</td>";
            echo "<td>".$row["folio"]."</td>";
            echo "<td>".$row["domicili"]."</td>";
            echo "<td>".$row["payment_term"]."</td>";
            echo "<td>".$row["delv_status"]."</td>";
            echo "<td style='text-align:right;'>".number_format($row["subtotal"],2)."</td>";
            echo "<td style='text-align:right;'>".number_format($row["total"],2)."</td>";
            echo "<td>".$row["remark1"]."</td>";
            echo "<td>".$row["box"]."</td>";
            echo "<td>".$row["pallet"]."</td>";
            echo "<td><a href='".base_url()."index.php/operacion/delivery/edit/approvedoc?docno=".$row["doc_no"]."' class='btn btn-sm btn-warning'>".$row["statuss_name"]."</a></td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
