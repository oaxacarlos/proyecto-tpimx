<?php
foreach($v_list_itr_apprv_detail_h as $row){
  $target_file = base_url()."assets/itrfiles/";
  $filepdf = $target_file.$row['attachment'];

  // check the last person approval.. if yes -> show change GL button
  if($v_is_last_approval == 0) $text_btn_gl = " hidden ";
  else $text_btn_gl = " ";


  // check can be approve, condition is the detail and document must completed
  $file_location = "./assets/itrfiles/";
  $filename = $file_location.$row['attachment'];

  if(($count_detail > 0) && (file_exists($filename))) $disable_approve = "";
  else $disable_approve = "disabled";
  //-------------------

?>
  <table class="table table-bordered table-sm">
    <tr class="table-info">
      <th>NAME : <?php echo $row['name']; ?></th>
      <th>EMAIL : <?php echo $row['email'] ?></th>
      <th>DEPARTMENT : <?php echo $row['depart_name']; ?></th>
    </tr>
    <tr class="table-info">
      <th>STATUS : <?php echo $row['itr_status_name']; ?></th>
      <th>DOC DATE : <?php echo $row['itr_h_doc_date']; ?></th>
      <th>CREATED DATE : <?php echo $row['itr_h_created_datetime']; ?></th>
    </tr>
    <tr class="table-info">
      <th>ITR NUMBER : <?php echo $row['itr_h_code'] ?></th>
      <th>TYPE : <?php echo $row['itr_type_code']." - ".$row['itr_type_name']; ?></th>
      <th>SOURCE DEPOT : <?php echo $row['plant_code']." - ".$row['plant_name'] ?></th>
    </tr>
    <tr class="table-info">
      <th>CUSTOMER : <?php echo $row['customer_text'] ?></th>
      <th>File : <a class="btn btn-primary btn-sm" href="<?php echo $filepdf; ?>" target="blank">View ITR File</a></th>
      <th></th>
    </tr>
  </table>

  <table class="table table-bordered table-sm" style="margin-top:10px;">
    <tr class="table-warning">
      <th colspan='5' class="text-center">GL Account</th>
    </tr>
    <tr>
      <th style="width:25%;">
        <div class="input-group">
          <input type="text" id="gl_code_apprv_edit" value="<?php echo $row['gl_code']; ?>" class="form-control" style="pointer-events:none;">
          <button class="btn btn-primary btn-sm" style="margin-left:5px;" <?php echo $text_btn_gl; ?> id="btn_itr_gl">Change GL</button>
        </div>
      </th>
      <th id="gl_name_apprv_edit"><?php echo $row['gl_name']; ?></th>
      <th id="gl_text1_apprv_edit"><?php echo $row['gl_text1'] ?></th>
      <th id="gl_text2_apprv_edit"><?php echo $row['gl_text2']; ?></th>
      <th id="gl_depart_apprv_edit"><?php echo $row['gl_depart_name']; ?></th>
      <input type="text" id="gl_id_apprv_edit" value="<?php echo $row['gl_id']; ?>" style="display:none;">
    </tr>
    <?php  if($v_is_last_approval == 1) { ?>
    <tr>
      <th colspan='5'style="color:red;">You can change the GL Account, if the user inputed not correct</th>
    </tr>
    <?php } ?>

  </table>

  <table class="table table-bordered table-sm">
    <tr class="table-warning">
      <th class="text-center">Cost Center</th>
      <th class="text-center">Project</th>
    </tr>
    <tr>
      <th><?php echo $row['costcenter_code']." - ".$row['costcenter_name']; ?></th>
      <th><?php echo $row['itr_project_code']." - ".$row['itr_project_name']; ?></th>
    </tr>
  </table>

  <table class="table table-bordered table-sm">
    <tr class="table-secondary">
      <th colspan='2' class="text-center">Remarks from Requestor</th>
    </tr>
    <tr>
      <th><?php echo $row['itr_h_text1']; ?></th>
    </tr>
  </table>

  <input type="hidden" id="itr_code" name="itr_code" value="<?php echo $row['itr_h_code']; ?>">
  <input type="hidden" id="itr_approval_code" name="itr_approval_code" value="<?php echo $row['itr_approval_code']; ?>">
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
foreach($v_list_itr_apprv_detail_d as $row){
    echo "<tr>";
      echo "<td>".$row['mat_id']."</td>";
      echo "<td>".$row['mat_desc']."</td>";
      echo "<td>".$row['mat_type']."</td>";
      echo "<td>".$row['qty']."</td>";
      echo "<td>".$row['uom']."</td>";
      echo "<td>".$row['itr_d_text1']."</td>";
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

if($v_list_itr_apprv_detail_approval == 0){
    echo "<tr><td colspan='5'>No Data Available</td></tr>";
}
else{
    foreach($v_list_itr_apprv_detail_approval as $row){
        if($row['itr_approval_code']=='') $color_approval_name = "danger";
        else $color_approval_name = "success";

        echo "<tr>";
          echo "<td class='badge badge-".$color_approval_name."'>".$row['itr_approval_name']."</td>";
          echo "<td>".$row['approval_datetime']."</td>";
          echo "<td>".$row['name']."</td>";
          echo "<td>".$row['email_approval']."</td>";
          echo "<td>".$row['itr_h_approval_text1']."</td>";
        echo "</tr>";
    }
}

?>

  </tbody>
</table>

<div class="pb-2 mt-1 mb-2" style="font-size:12px;">
  <input type="text" id="remarks" class="form-control" placeholder="put your reject/approval remarks for this ITR in here">
  <?php
  if($disable_approve == "disabled"){
    echo "<span style='color:red;'>You only reject this ITR because the DATA is not completed...</span>";
  }

  ?>
</div>


<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
  <button class="btn btn-danger" id="btn_reject_itr" style="margin-right:20px;">Reject</button>
  <button class="btn btn-success" id="btn_approve_itr" <?php echo $disable_approve; ?>>Approve</button>
</div>

<div class="modal" id="myModalGL">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">GL Account</h4>
        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
      </div>
      <div class="modal-body" id="modal_gl"></div>
    </div>
  </div>
</div>

<div id="loading_body" style="display:none;">
  <div class="container" style="text-align:center; width:100%;">
    <div class="spinner-grow text-muted"></div>
    <div class="spinner-grow text-primary"></div>
    <div class="spinner-grow text-success"></div>
    <div class="spinner-grow text-info"></div>
    <div class="spinner-grow text-warning"></div>
    <div class="spinner-grow text-danger"></div>
    <div class="spinner-grow text-secondary"></div>
    <div class="spinner-grow text-dark"></div>
    <div class="spinner-grow text-light"></div><br>
    <div class="badge badge-light" id="loading_text"></div>
  </div>
</div>

<script>


$('#btn_approve_itr').click(function(){
    var remarks = $('#remarks').val();
    var itr_code = $('#itr_code').val();
    var itr_approval_code = $('#itr_approval_code').val();
    var itr_gl_id_edit = $('#gl_id_apprv_edit').val();
    var disabled_approve = '<?php echo $disabled_approve; ?>';

    if(remarks == '') swal('Error','You should put the remarks','error');
    else{
        swal({
        title: "Are You Sure?",
        html: "Approve this ITR",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                //swal({ title: "Please Wait", text: "Progressing...", showConfirmButton: false });
                $("#loading_text").text("The ITR is on progress approval, Please wait...");
                $('#loading_body').show();

                $("#btn_approve_itr").attr( "disabled", "disabled" );
                $("#btn_reject_itr").attr( "disabled", "disabled" );

                setTimeout(function () {
                  $.ajax({
                      url       : "<?php echo base_url();?>index.php/itr_apprv/itr_approval",
                      type      : 'post',
                      dataType  : 'html',
                      data      : {remarks:remarks,itr_code:itr_code,itr_approval_code:itr_approval_code,itr_gl_id_edit:itr_gl_id_edit},
                      success   :  function(respons){
                        var responsdata = $.parseJSON(respons);
                        if(responsdata.status == "1"){
                            swal({
                               title: responsdata.message,
                               type: "success", confirmButtonText: "OK",
                            }).then(function(){
                              setTimeout(function () {

                                location.reload(true);
                                $('#loading_body').hide();
                              },100)
                            });
                        }
                        else{
                            Swal('Error!',responsdata.message,'error');
                            $('#loading_body').hide();
                            $("#btn_reject_itr").prop( "disabled", false );
                            if(disabled_approve == "") $("#btn_approve_itr").prop( "disabled", false );
                        }
                      }
                  });
                }, 500);
            }
          })
    }
})
//-----------------
$('#btn_reject_itr').click(function(){

  var remarks = $('#remarks').val();
  var itr_code = $('#itr_code').val();
  var disabled_approve = '<?php echo $disabled_approve; ?>';

  if(remarks == '') swal('Error','You should put the remarks','error');
  else{
      swal({
      title: "Are You Sure?",
      html: "Reject this ITR",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
          if(result.value){
              $("#loading_text").text("The ITR is on progress rejected, Please wait...");
              $('#loading_body').show();

              $("#btn_approve_itr").attr( "disabled", "disabled" );
              $("#btn_reject_itr").attr( "disabled", "disabled" );

              setTimeout(function () {
                $.ajax({
                    url       : "<?php echo base_url();?>index.php/itr_apprv/itr_rejected",
                    type      : 'post',
                    dataType  : 'html',
                    data      : {remarks:remarks,itr_code:itr_code},
                    success   :  function(respons){
                      if(respons != 0){
                          swal({
                             title: "The ITR has been rejected",
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              location.reload(true);
                              $('#loading_body').hide();
                            },500)
                          });
                      }
                      else{
                          Swal('Error!','The ITR was not succesfull rejected','error');
                          $('#loading_body').hide();
                          $("#btn_reject_itr").prop( "disabled", false );
                          if(disabled_approve == "") $("#btn_approve_itr").prop( "disabled", false );
                      }
                    }
                });
              }, 500);
          }
        })
  }

})

//------------------

$('#btn_itr_gl').click(function(){
    $('#modal_gl').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_gl').load(
        "<?php echo base_url();?>index.php/itr_apprv/show_gl",
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalGL').modal();
});
//---------

</script>
