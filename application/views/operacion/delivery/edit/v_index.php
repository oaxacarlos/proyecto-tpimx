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
      <th>Status</th>
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
            echo "<td style='text-align:right;'>".number_format($row["subtotal"])."</td>";
            echo "<td style='text-align:right;'>".number_format($row["total"])."</td>";
            echo "<td>".$row["remark1"]."</td>";
            echo "<td>".$row["box"]."</td>";
            echo "<td>".$row["pallet"]."</td>";

            if($row["statuss"] == "1") $badge_status = "info";
            else if($row["statuss"] == "2") $badge_status = "warning";
            else if($row["statuss"] == "3") $badge_status = "success";

            echo "<td><div class='badge badge-".$badge_status."'>".$row["statuss_name"]."</div></td>";

            echo "<td><a href='".base_url()."index.php/operacion/delivery/edit/editdoc?docno=".$row["doc_no"]."' class='btn btn-sm btn-primary'>EDIT</a></td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>
