
<?php
foreach($v_list_empc_apprv_detail_h as $row){

  $target_file = base_url()."assets/empcfiles/";
  $filepdf = $target_file.$row['attachment'];
?>
  <table class="table table-bordered table-sm">
    <tr class="table-info">
      <th>NAME : <?php echo $row['name']; ?></th>
      <th>EMAIL : <?php echo $row['email'] ?></th>
      <th>DEPARTMENT : <?php echo $row['depart_name']; ?></th>
    </tr>
    <tr class="table-info">
      <th>STATUS : <?php echo $row['empc_status_name']; ?></th>
      <th>DOC DATE : <?php echo $row['empc_h_doc_date']; ?></th>
      <th>CREATED DATE : <?php echo $row['empc_h_created_datetime']; ?></th>
    </tr>
    <tr class="table-info">
      <th>EMC NUMBER : <?php echo $row['empc_h_code'] ?></th>
      <th>TYPE : <?php echo $row['empc_type_code']." - ".$row['empc_type_name']; ?></th>
      <th>SOURCE DEPOT : <?php echo $row['plant_code']." - ".$row['plant_name'] ?></th>
    </tr>
    <tr class="table-info">
    <!--  <th>EMPLOYEE CODE : <?php //echo $row['customer_code'] ?><br>
          EMPLOYEE NAME : <?php //echo $row['employee_name'] ?>
      </th>-->
      <th>EMPLOYEE : <?php echo "<br>".nl2br($row['customer_text']); ?></th>
      <th>File : <a class="btn btn-primary btn-sm" href="<?php echo $filepdf; ?>" target="blank">View EMC File</a></th>
      <th></th>
    </tr>
  </table>

  <table class="table table-bordered table-sm">
    <tr class="table-warning">
      <th colspan='5' class="text-center">GL Account</th>
    </tr>
    <tr>
      <th><?php echo $row['gl_code']; ?></th>
      <th><?php echo $row['gl_name']; ?></th>
      <th><?php echo $row['gl_text1'] ?></th>
      <th><?php echo $row['gl_text2']; ?></th>
      <th><?php echo $row['gl_depart_name']; ?></th>
    </tr>
  </table>

  <table class="table table-bordered table-sm">
    <tr class="table-warning">
      <th class="text-center">Cost Center</th>
      <th class="text-center">Project</th>
    </tr>
    <tr>
      <th><?php echo $row['costcenter_code']." - ".$row['costcenter_name']; ?></th>
      <th><?php echo $row['empc_project_code']." - ".$row['empc_project_name']; ?></th>
    </tr>
  </table>

  <table class="table table-bordered table-sm">
    <tr class="table-secondary">
      <th colspan='2' class="text-center">Remarks from Requestor</th>
    </tr>
    <tr>
      <th><?php echo $row['empc_h_text1']; ?></th>
    </tr>
  </table>

<?php if($row['canceled'] == "X"){ ?>
  <table class="table table-bordered table-sm">
    <tr class="table-danger">
      <th colspan='2' class="text-center">Rejected Reason</th>
    </tr>
    <tr>
      <th><?php echo $row['empc_h_text2']; ?></th>
    </tr>
  </table>
<?php } ?>

<?php
}
?>

<table class="table table-striped table-sm table-bordered">
  <thead>
      <tr>
        <th colspan='6' class='text-center table-primary'>MATERIAL LIST</th>
      </tr>
      <tr>
        <th style="width:40px;">Material ID</th>
        <th style="width:400px;">Material Desc</th>
        <th>Material Type</th>
        <th>QTY</th>
        <th>UOM</th>
        <th style="width:300px;">Text</th>
      </tr>
  </thead>
  <tbody>

<?php
foreach($v_list_empc_apprv_detail_d as $row){
    echo "<tr>";
      echo "<td>".$row['mat_id']."</td>";
      echo "<td>".$row['mat_desc']."</td>";
      echo "<td>".$row['mat_type']."</td>";
      echo "<td>".$row['qty']."</td>";
      echo "<td>".$row['uom']."</td>";
      echo "<td>".$row['empc_d_text1']."</td>";
    echo "</tr>";
}
?>
</tbody>
<tfoot>
  <tr >
    <th colspan='6' style="color:red;">Kindly check again all the information and material</th>
  </tr>
</tfoot>
</table>

<table class="table table-striped table-sm table-bordered">
  <thead>
      <tr>
        <th colspan='6' class='text-center table-success'>APPROVAL HISTORY</th>
      </tr>
      <tr>
        <th>Approval</th>
        <th>DateTime</th>
        <th>Name</th>
        <th>Email</th>
        <th>Remarks</th>
      </tr>
  </thead>
  <tbody>

<?php

if($v_list_empc_apprv_detail_approval == 0){
    echo "<tr><td colspan='5'>No Data Available</td></tr>";
}
else{
    foreach($v_list_empc_apprv_detail_approval as $row){
        if($row['empc_approval_code']=='') $color_approval_name = "danger";
        else $color_approval_name = "success";

        echo "<tr>";
          echo "<td class='badge badge-".$color_approval_name."'>".$row['empc_approval_name']."</td>";
          echo "<td>".$row['approval_datetime']."</td>";
          echo "<td>".$row['name']."</td>";
          echo "<td>".$row['email_approval']."</td>";
          echo "<td>".$row['empc_h_approval_text1']."</td>";
        echo "</tr>";
    }
}

?>
  </tbody>
</table>

<script>


</script>
