<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<style>
  tr{
      font-size: 14px;
  }

  .modal-lg {
    max-width: 80%;
  }

  .modal {
  overflow-y:auto;
}
</style>

<div class="container-fluid">
  <table id="DataTable" class="table table-bordered table-hover table-striped table-sm">
    <thead>
      <tr>
        <th>No</th>
        <th>Status</th>
        <th>ITR Number</th>
        <th>CreatedDate</th>
        <th>Name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Type</th>
        <th>Depot</th>
        <th>Customer</th>
        <th>Action</th>
        <th style="display:none;"></th>
        <th style="display:none;"></th>
      </tr>
    </thead>
    <tbody>

      <?php
        if($v_list_itr_approval_super == 0){
          echo "<tr>";
            echo "<td colspan='12'>No Data Available</td>";
          echo "</tr>";
        }
        else{
          $i=1;
          foreach($v_list_itr_approval_super as $row){
              if($row['itr_status_code'] == 'ITRST004') $color_status = "danger";
              else if($row['itr_status_code'] == 'ITRST001') $color_status = "info";
              else if($row['itr_status_code'] == 'ITRST002') $color_status = "primary";
              else if($row['itr_status_code'] == 'ITRST003') $color_status = "success";
              else $color_status = "";

              echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td><span class='badge badge-".$color_status."'>".$row['itr_status_name']."</span></td>";
                echo "<td>".$row['itr_h_code']."</td>";
                echo "<td>".$row['itr_h_created_datetime']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['depart_name']."</td>";
                echo "<td>".$row['itr_type_code']."</td>";
                echo "<td>".$row['plant_code']."</td>";
                echo "<td>".$row['customer_text']."</td>";
                echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_itr_detail_apprv('".$row['itr_h_code']."',".$i.")>Detail</button></td>";

                echo "<td id='status_".$i."' style='display:none;'>".$row['itr_status_code']."</td>";
                echo "<td id='approvalcode_".$i."' style='display:none;'>".$row['itr_approval_code']."</td>";
              echo "</tr>";
              $i++;

          }
        }
      ?>
    </tbody>
  </table>
</div>

<div class="modal" id="myModalItrDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">ITR Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_itr_detail">
      </div>
    </div>
  </div>
</div>



<script>

function f_itr_detail_apprv(itr_code,index){
    var is_last_approval = '<?php echo $v_is_last_approval; ?>';
    var status = $('#status_'+index).text();
    var approval_code = $('#approvalcode_'+index).text();

    data = {'itr_code':itr_code, 'is_last_approval' : is_last_approval, 'status':status, 'approval_code': approval_code}

    $('#modal_itr_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_itr_detail').load(
        "<?php echo base_url();?>index.php/itr_apprv/show_itr_detail_super",
        data,                                                  // data
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalItrDetail').modal();
}

</script>
