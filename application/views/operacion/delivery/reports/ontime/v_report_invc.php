<script>
$(document).ready(function() {
    $('#tbl_report_invc').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Delivery-OnTime-ByInvoces'
          }
        ],
        "order": [],
        "pageLength": 20
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

<?php
    $ontime = 0;
    $no_ontime = 0;
    $total_data = 0;
    foreach($var_report as $row){
      if($row["lead_days"]=='' || $row["leadtime"]==''){}
      else{
        if($row["lead_days"] - $row["leadtime"] >= 0){
          $ontime++;
          $total_data++;
        }
        else{
          $no_ontime++;
          $total_data++;
        }
      }
    }
?>

<div class="container-fluid" style="margin-bottom:20px;">
  <div class="row">
    <div class="col-md-12">
      <span class="badge badge-success" style="font-size:14px;">OnTime : <?php echo $ontime; ?> (<?php echo percentage($ontime,$total_data); ?>%)</span>
      <span class="badge badge-danger" style="font-size:14px;">No OnTime : <?php echo $no_ontime; ?> (<?php echo percentage($no_ontime,$total_data); ?>%)</span>
    </div>
  </div>
</div>

<table class="table table-bordered table-sm table-striped" style="margin-top:10px;" id="tbl_report_invc">
  <thead>
    <tr>
      <th class="table-dark">Doc No</th>
      <th class="table-dark">Doc Date</th>
      <th class="table-dark">Delv Date</th>
      <th class="table-dark">Receiv Date</th>
      <th class="table-dark">Lead Time (TPM)</th>
      <th class="table-dark">Estimated Days</th>
      <th class="table-dark">OnTime</th>
      <th>Destination</th>
      <th>State</th>
      <th>Customer</th>
      <th>Ship To Cust</th>
      <th>Ship To Address</th>
      <th>Ship To Address 2</th>
      <th>Ship To City</th>
      <th>Ship To PostCode</th>
      <th>Ship To County</th>
      <th>Delv Doc</th>
      <th>Driver</th>
      <th>Vendor No</th>
      <th>Vendor Name</th>
      <th>Tracking No</th>
      <th>Domicili</th>
      <th>Receive Person</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_report as $row){
        echo "<tr>";
          echo "<td>".$row["doc_no"]."</td>";
          echo "<td>".$row["doc_date"]."</td>";
          echo "<td>".$row["delv_date"]."</td>";
          echo "<td>".$row["receiv_date"]."</td>";
          echo "<td>".$row["leadtime"]."</td>";
          echo "<td>".$row["lead_days"]."</td>";

          if($row["lead_days"]=='' || $row["leadtime"]=='') echo "<td>-</td>";
          else{
            if($row["lead_days"] - $row["leadtime"] >= 0){
              $ontime_color = "success";
              $ontime_text = "YES";
            }
            else{
              $ontime_color = "danger";
              $ontime_text = "NO";
            }

            echo "<td class='table-".$ontime_color."'>".$ontime_text."</td>";
          }

          echo "<td>".$row["destination"]."</td>";
          echo "<td>".$row["state"]."</td>";
          echo "<td>".$row["sell_to_customer_no"]."</td>";
          echo "<td>".$row["ship_to_name"]."</td>";
          echo "<td>".$row["ship_to_address"]."</td>";
          echo "<td>".$row["ship_to_address2"]."</td>";
          echo "<td>".$row["ship_to_city"]."</td>";
          echo "<td>".$row["ship_to_post_code"]."</td>";
          echo "<td>".$row["ship_to_county"]."</td>";
          echo "<td>".$row["delv_doc_no"]."</td>";
          echo "<td>".$row["driver"]."</td>";
          echo "<td>".$row["vendor_no"]."</td>";
          echo "<td>".$row["vendor_name"]."</td>";
          echo "<td>".$row["tracking_no"]."</td>";
          echo "<td>".$row["domicili"]."</td>";
          echo "<td>".$row["receiv_person"]."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>
