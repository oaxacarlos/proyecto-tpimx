<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
} );

</script>

<style>
  tr{
      font-size: 12px;
  }
</style>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Status</th>
      <th>ITR Number</th>
      <th>CreatedDate</th>
      <th>Name</th>
      <th>Email</th>
      <th>Department</th>
      <th>Type</th>
      <th>GL Account</th>
      <th>CostCenter</th>
      <th>Depot</th>
      <th>Customer</th>
      <th>Resrv No</th>
      <th>Detail</th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach($v_itr_report_generate as $row){
      if($row['itr_status_code'] == 'ITRST004') $color_status = "danger";
      else if($row['itr_status_code'] == 'ITRST001') $color_status = "info";
      else if($row['itr_status_code'] == 'ITRST002') $color_status = "primary";
      else if($row['itr_status_code'] == 'ITRST003') $color_status = "success";
      else $color_status = "";

      echo "<tr>";
        echo "<td><span class='badge badge-".$color_status."'>".$row['itr_status_name']."</td>";
        echo "<td>".$row['itr_h_code']."</td>";
        echo "<td>".$row['itr_h_created_datetime']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['email']."</td>";
        echo "<td>".$row['depart_name']."</td>";
        echo "<td>".$row['itr_type_code']."</td>";
        echo "<td>".$row['gl_code']."</td>";
        echo "<td>".$row['costcenter_code']."</td>";
        echo "<td>".$row['plant_code']."</td>";
        echo "<td>".$row['customer_text']."</td>";
        echo "<td style='font-size:20px;'><span class='badge badge-success'>".$row['sap_no']."</td>";
        echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_itr_report_detail('".$row['itr_h_code']."')>Detail</td>";
        echo "</tr>";
  }

  ?>
  </tbody>
</table>
