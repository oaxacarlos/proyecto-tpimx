<script>
$(document).ready(function() {
    $('#tbl_report').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Delivery-Detail'
          }
        ],
    });
});
</script>

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

</style>

<table class="table table-bordered table-sm table-striped" style="margin-top:10px;" id="tbl_report">
  <thead>
    <tr>
      <th>Doc<br>No</th>
      <th>Created<br>at</th>
      <th>Delv<br>Date</th>
      <th>Destination</th>
      <th>State</th>
      <th>Driver</th>
      <th>Vendor<br>No</th>
      <th>Vendor<br>Name</th>
      <th>Tracking<br>No</th>
      <th>Folio</th>
      <th>Domicili</th>
      <th>Payment<br>Term</th>
      <th>SubTotal</th>
      <th>Total</th>
      <th>Tax</th>
      <th>Box</th>
      <th>Pallet</th>
      <th>Delivery<br>Status</th>
      <th>Received<br>date</th>
      <th>Received<br>by</th>
      <th>Created<br>by</th>
      <th>Payment<br>Date</th>
      <th>Payment<br>Status</th>
      <th>Delv Remark 1</th>
      <th>Delv Remark 2</th>
      <th>Invc Vendor No</th>
      <th>Invc Vendor Date</th>
      <th>Invc Doc No</th>
      <th>Invc Doc Date</th>
      <th>SO Ref</th>
      <th>Invc Cust No</th>
      <th>Invc Cust Name</th>
      <th>Invc Remark</th>
      <th>Invc Address</th>
      <th>Invc Address 2</th>
      <th>Invc City</th>
      <th>Invc State</th>
      <th>Invc Post Code</th>
      <th>Invc Country</th>
      <th>Invc Subtotal</th>
      <th>Invc Total</th>
      <th>Invc Qty</th>
      <th>Invc Doc Type</th>
      <th>UUID</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
        echo "<tr>";
          echo "<td>".$row["doc_no"]."</td>";
          echo "<td>".$row["created_at"]."</td>";
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
          echo "<td id='number' style='text-align:right;'>".$row["subtotal_delv"]."</td>";
          echo "<td id='number' style='text-align:right;'>".$row["total_delv"]."</td>";
          echo "<td id='number' style='text-align:right;'>".$row["delv_tax"]."</td>";
          echo "<td id='number'>".$row["box"]."</td>";
          echo "<td id='number'>".$row["pallet"]."</td>";
          echo "<td>".$row["delv_status"]."</td>";
          echo "<td>".$row["receiv_date"]."</td>";
          echo "<td>".$row["receiv_person"]."</td>";
          echo "<td>".$row["created_by_name"]."</td>";
          echo "<td>".$row["payment_date"]."</td>";
          echo "<td>".$row["payment_status"]."</td>";
          echo "<td>".$row["delv_remark1"]."</td>";
          echo "<td>".$row["delv_remark2"]."</td>";
          echo "<td>".$row["invc_vendor_no"]."</td>";
          echo "<td>".$row["invc_vendor_date"]."</td>";
          echo "<td>".$row["invc_doc_no"]."</td>";
          echo "<td>".$row["invc_doc_date"]."</td>";
          echo "<td>".$row["so_ref"]."</td>";
          echo "<td>".$row["invc_cust_no"]."</td>";
          echo "<td>".$row["invc_cust_name"]."</td>";
          echo "<td>".$row["invc_remark1"]."</td>";
          echo "<td>".$row["invc_address"]."</td>";
          echo "<td>".$row["invc_address2"]."</td>";
          echo "<td>".$row["invc_city"]."</td>";
          echo "<td>".$row["invc_state"]."</td>";
          echo "<td>".$row["invc_post_code"]."</td>";
          echo "<td>".$row["invc_country"]."</td>";
          echo "<td id='number'>".$row["invc_subtotal"]."</td>";
          echo "<td id='number'>".$row["invc_total"]."</td>";
          echo "<td id='number'>".$row["invc_qty"]."</td>";
          echo "<td>".$row["invc_doc_type"]."</td>";
          echo "<td>".$row["uuid"]."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>
