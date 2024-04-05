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
        <!--<th>GL Account</th>
        <th>CostCenter</th> -->
        <th>Depot</th>
        <th>Customer</th>
        <th>Project</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <?php
        if($v_list_itr_apprv == 0){
          echo "<tr>";
            echo "<td colspan='12'>No Data Available</td>";
          echo "</tr>";
        }
        else{
          $i=1;
          foreach($v_list_itr_apprv as $row){
            if($row['itr_status'] == 'ITRST004') $color_status = "danger";
            else if($row['itr_status'] == 'ITRST001') $color_status = "info";
            else if($row['itr_status'] == 'ITRST002') $color_status = "primary";
            else if($row['itr_status'] == 'ITRST003') $color_status = "success";
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
                //echo "<td>".$row['gl_code']."</td>";
                //echo "<td>".$row['costcenter_code']."</td>";
                echo "<td>".$row['plant_code']."</td>";
                echo "<td>".$row['customer_text']."</td>";
                echo "<td>".$row['itr_project_name']."</td>";
                echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_itr_detail_apprv('".$row['itr_h_code']."')>Detail</button></td>";
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

function f_itr_detail_apprv(itr_code){
    var is_last_approval = '<?php echo $v_is_last_approval; ?>';

    data = {'itr_code':itr_code, 'is_last_approval' : is_last_approval }

    $('#modal_itr_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_itr_detail').load(
        "<?php echo base_url();?>index.php/itr_apprv/show_itr_detail",
        data,                                                  // data
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalItrDetail').modal();
}
//----------------

function notif(text,type,timeout){
    noty({
                text        : text,
                type        : type,
                dismissQueue: true,
                progressBar : true,
                timeout     : timeout,
                layout      : 'topRight',
                closeWith   : ['click'],
                theme       : 'relax',
                maxVisible  : 10,
                animation   : {
                    open  : 'animated bounceInRight',
                    close : 'animated bounceOutRight',
                    easing: 'swing',
                    speed : 500
                }
    });
}

function call_notif(){
    var notif_number_approval_html;
    var pending_approval_status = <?php echo $v_list_itr_apprv ?>;
    var pending_approval = <?php echo count($v_list_itr_apprv); ?>;

    // notif for how many approval still pending
    if(pending_approval_status != 0){
      notif_number_approval_html = "<div class='activity-item'><i class='fa fa-tasks text-warning'>";
      notif_number_approval_html = notif_number_approval_html + "</i><div class='activity'> There are <b>"+pending_approval+" approval</b>";
      notif_number_approval_html = notif_number_approval_html + " waiting for you.</div> </div>";
      notif(notif_number_approval_html,'warning',3000);
    }
    //----------------


}

call_notif();

</script>
