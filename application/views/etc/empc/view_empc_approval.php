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
      EMC APPROVAL FORM
</div>

<div class="container-fluid">
  <table class="table table-bordered table-hover table-striped table-sm">
    <thead>
      <tr class="table-primary">
        <th>No</th>
        <th>Status</th>
        <th>EMC Number</th>
        <th>CreatedDate</th>
        <th>Name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Type</th>
        <th>Depot</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      <?php
        if($v_list_empc_apprv == 0){
          echo "<tr>";
            echo "<td colspan='12'>No Data Available</td>";
          echo "</tr>";
        }
        else{
          $i=1;
          foreach($v_list_empc_apprv as $row){
            if($row['empc_status'] == 'EMPCST005') $color_status = "danger";
            else if($row['empc_status'] == 'EMPCST001') $color_status = "info";
            else if($row['empc_status'] == 'EMPCST002') $color_status = "primary";
            else if($row['empc_status'] == 'EMPCST003') $color_status = "warning";
            else if($row['empc_status'] == 'EMPCST004') $color_status = "success";
            else $color_status = "";

              echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td><span class='badge badge-".$color_status."'>".$row['empc_status_name']."</span></td>";
                echo "<td>".$row['empc_h_code']."</td>";
                echo "<td>".$row['empc_h_created_datetime']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['depart_name']."</td>";
                echo "<td>".$row['empc_type_code']."</td>";
                echo "<td>".$row['plant_code']."</td>";
                echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_empc_detail_apprv('".$row['empc_h_code']."')>Detail</button></td>";
              echo "</tr>";
              $i++;
          }
        }
      ?>
    </tbody>
  </table>
</div>

<div class="modal" id="myModalEmpcDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">EMC Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_empc_detail">
      </div>
    </div>
  </div>
</div>



<script>

function f_empc_detail_apprv(empc_code){
    var is_last_approval = '<?php echo $v_is_last_approval; ?>';

    data = {'empc_code':empc_code, 'is_last_approval' : is_last_approval }

    $('#modal_empc_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_empc_detail').load(
        "<?php echo base_url();?>index.php/empc/show_empc_detail",
        data,                                                  // data
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalEmpcDetail').modal();
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
    var pending_approval_status = <?php echo $v_list_empc_apprv ?>;
    var pending_approval = <?php echo count($v_list_empc_apprv); ?>;

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
