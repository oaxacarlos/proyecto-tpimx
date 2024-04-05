<style>
  tr{
      font-size: 12px;
      height: 5px;
  }

  .modal-lg {
    max-width: 80%;
  }
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      ITR APPROVAL FORM
</div>

<div class="container-fluid">
  <table class="table table-bordered table-hover table-striped">
    <thead>
      <tr class="table-primary">
        <th>No</th>
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
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <?php
        if($v_list_itr_apprv_sap == 0){
          echo "<tr>";
            echo "<td colspan='12'>No Data Available</td>";
          echo "</tr>";
        }
        else{
          $i=1;
          foreach($v_list_itr_apprv_sap as $row){
              echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['itr_status_name']."</td>";
                echo "<td>".$row['itr_h_code']."</td>";
                echo "<td>".$row['itr_h_created_datetime']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['depart_name']."</td>";
                echo "<td>".$row['itr_type_code']."</td>";
                echo "<td>".$row['gl_code']."</td>";
                echo "<td>".$row['costcenter_code']."</td>";
                echo "<td>".$row['plant_code']."</td>";
                echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_itr_detail_apprv_sap('".$row['itr_h_code']."')>Detail</button></td>";
              echo "</tr>";
              $i++;
          }
        }
      ?>
    </tbody>
  </table>
</div>

<div class="modal" id="myModalItrSapDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">ITR Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_itr_sap_detail">
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalItrSapDetailApprove">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">ITR Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_itr_sap_detail_approve">
      </div>
    </div>
  </div>
</div>

<script>

function f_itr_detail_apprv_sap(itr_code){
    data = {'itr_code':itr_code}

    $('#modal_itr_sap_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_itr_sap_detail').load(
        "<?php echo base_url();?>index.php/itr_apprv_sap/show_itr_detail",
        data,                                                  // data
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalItrSapDetail').modal();
}


</script>
