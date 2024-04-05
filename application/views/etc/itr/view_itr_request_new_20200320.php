<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
} );

</script>

<style>
  tr{
      font-size: 12px;
  }

  .modal-lg {
    max-width: 80%;
  }
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      ITR REQUEST
</div>

<?php
  $session_data = $this->session->userdata('z_tpimx_logged_in');
?>

<div class="container-fluid">
    Your ITR encoded today<br>
    <span style="font-size:10px;">
      If any issue when submitting the last ITR. Please check your ITR encoded in here. Successfull if DataDetail and Document are giving green color.
    </span>
    <div id="itr_list_today"  style="width:100%; max-height:200px; overflow: auto;"></div>
</div>

<div class="container-fluid" style="margin-top:20px;">
    <div class="border">
      <div class="row" style="padding:10px;" id="row_input1">
        <div class="col-md-2">
          Date
          <input type="text" class="form-control" value="<?php echo date("Y-m-d"); ?>" readonly>
        </div>
        <div class="col-md-3">
          Requestor Name
          <input type="text" class="form-control" value="<?php echo $session_data['z_tpimx_name'];?>" readonly>
        </div>
        <div class="col-md-3">
          Requestor Department
          <input type="text" class="form-control" value="<?php echo $session_data['z_tpimx_depart_name'];?>" readonly>
        </div>
        <div class="col-md-3">
          Email
          <input type="text" class="form-control" value="<?php echo $session_data['z_tpimx_email'];?>" readonly>
        </div>
      </div>
    </div>

    <!-- GL -->
    <div class="border" style="margin-top:10px;">
      <div class="row" style="padding:10px;" id="row_input2">
        <div class="col-md-2">
          Depot
            <div class="input-group">
              <input type="text" id="itr_plant" name="itr_plant" value="" class="form-control" class="col-md-2" style="pointer-events:none;">
              <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_itr_plant">...</button>
            </div>
        </div>
        <div class="col-md-2">
          Cost Center
            <div class="input-group">
              <input type="text" id="itr_costcenter" name="itr_costcenter" value="" class="form-control" class="col-md-2" style="pointer-events:none;">
              <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_itr_costcenter">...</button>
            </div>
        </div>
          <div class="col-md-4">
            GL Account
              <div class="input-group">
                <input type="text" id="itr_gl_code" name="itr_gl_code" value="" class="form-control" class="col-md-2" style="pointer-events:none;">
                <input type="text" style="display:none;" id="itr_gl" name="itr_gl" value="" class="form-control" class="col-md-2" style="pointer-events:none;">
                <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_itr_gl">...</button>
              </div>

          </div>
          <div class="col-md-4">
              Project  / Promo
              <div class="input-group">
                <input type="text" id="itr_project" name="itr_project" value="" class="form-control" style="pointer-events:none;">
                <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_itr_project">...</button>
              </div>
          </div>
      </div>

      <!-- text -->
      <div class="row">
          <div class="col-md-2">
              <span id="itr_plant_text" style="margin-left:10px;" class="badge badge-secondary"></span>
          </div>
          <div class="col-md-2">
              <span id="itr_costcenter_text" style="margin-left:5px;" class="badge badge-secondary"></span>
          </div>
          <div class="col-md-4">
              <span id="itr_gl_code_text" style="margin-left:5px;" class="badge badge-secondary"></span>
          </div>
          <div class="col-md-2">
              <span id="itr_project_text" style="margin-left:5px;" class="badge badge-secondary"></span>
          </div>

      </div>
      <!-- end -->

      <div class="row" style="padding:10px;" id="row_input3">
          <div class="col-md-4">
            Type
            <select id="itr_type" class="form-control">
              <option value="">-</option>
              <?php
                foreach($v_list_itr_type as $row){
                    echo "<option value='".$row['itr_type_code']."'>".$row['itr_type_code']." - ".$row['itr_type_name']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-md-4">
              Customer (Mandatory)
              <div class="input-group">
                <input type="text" id="itr_customer" name="itr_customer" value="" class="form-control">
              </div>
          </div>
          <div class="col-md-4">
              Remarks (Mandatory)
              <div class="input-group">
                <input type="text" id="itr_remarks" name="itr_remarks" value="" class="form-control">
              </div>
          </div>
      </div>
    </div>

    <!-- Upload File -->
    <div class="border" style="margin-top:10px;">
      <div class="container-fluid">
            <span class="badge badge-danger">Upload File is Mandatory (format on PDF)</span><br>
            <input type='file' name='fileToUpload' id='fileToUpload' accept=".pdf">
      </div>
    </div>

    <!-- material list -->
    <div class="border" style="margin-top:10px;">
      <div class="container-fluid" style="padding-top:20px;">
        <button class="btn btn-info btn-sm text-right" style="margin-bottom:10px;"
         data-toggle="modal" data-target="#myModalMaterial">Material List</button>
      </div>

      <div class="container-fluid" style="margin-top:10px;">
          <h5>List Item Request</h5>
          <table id="TableListItem" class="table table-bordered">
            <thead>
              <tr>
                <th>Material ID</th>
                <th>Material Desc</th>
                <th>Material Type</th>
                <th>Quantity</th>
                <th>UOM</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="TableListItemBody">
            </tbody>
          </table>
      </div>
    </div>

      <div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px; padding-top:10px;">
        <button class="btn btn-success btn-sm" style="margin-bottom:10px;" id="btn_save_all">SAVE</button>
        <button class="btn btn-danger btn-sm" style="margin-bottom:10px; margin-left:10px;" id="btn_clear_all">CLEAR</button>
      </div>
</div>

<div style="padding-bottom:20px;"></div>

<div class="modal" id="myModalMaterial">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Material List</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_material">
        <table id="DataTable" class="table table-striped table-bordered table-sm" style="width:100%">
          <thead>
            <tr>
              <th>Material ID</th>
              <th>Material Description</th>
              <th>Material Type</th>
              <th>Base UOM</th>
              <th>Reguler / Promo</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
              <?php
                foreach($v_list_material as $row){
                  echo "<tr>";
                    echo "<td id='tblmatid_".$row['mat_id']."'>".$row['mat_id']."</td>";
                    echo "<td id='tblmatdesc_".$row['mat_id']."'>".$row['mat_desc']."</td>";
                    echo "<td id='tblmattype_".$row['mat_id']."'>".$row['mat_type']."</td>";
                    echo "<td id='tbluom_".$row['mat_id']."'>".$row['uom']."</td>";
                    echo "<td id='tbllab_".$row['mat_id']."'>".$row['lab_office']."</td>";
                    echo "<td><button class='btn btn-outline-primary btn-sm' onclick=call_modal_qty('".$row['mat_id']."')>select</button></td>";
                    echo "</tr>";
                }
              ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalGL">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">GL Account</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_gl"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalCostCenter">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cost Center</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_costcenter"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalPlant">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Depot</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_plant"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalProject">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Project / Promo</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_project"></div>
    </div>
  </div>
</div>

<div class="modal" id="myModalQty">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Input QTY</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <div>
            <span>Material ID</span>
            <span><input type="text" class="form-control form-control-sm" id="itr_matid" readonly></span>
          </div>
          <div>
            <span>Material Desc</span>
            <span><input type="text" class="form-control form-control-sm" id="itr_matdesc" readonly></span>
          </div>
          <div>
            <span>Material Type</span>
            <span><input type="text" class="form-control form-control-sm" id="itr_mattype" readonly></span>
          </div>
          <div>
            <span>QTY</span>
            <span><input type="text" class="form-control form-control-sm" id="itr_qty" autofocus onkeypress="return isNumberKey(event)"></span>
          </div>
          <div>
            <span>UOM</span>
            <span>
              <select id="itr_uom" class="form-control">
                  <option value="">-</option>
                  <?php
                    foreach($v_list_uom as $row){
                        echo "<option value='".$row['uom_code']."'>".$row['uom_code']." - ".$row['uom_name']."</option>";
                    }
                  ?>
              </select>
              <span style='color:red; font-size:12px;'>Make sure the UOM is correct</span>
            </span>
          </div>
          <div>
            <span>Text</span>
            <span><input type="text" class="form-control form-control-sm" id="itr_text" maxlength="50"></span>
            <span style='color:red; font-size:12px;'>This text will send to SAP for the Reservation (Max 50 char)</span>
          </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-success" id="btn_add_qty">Add</button>
      </div>
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

var idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 29) { // 20 minutes
        alert("You have idle over than 30 minutes, the system will log out..");
        window.location.href = '<?php echo base_url();?>index.php/logout';
    }
}


$('#itr_type').on('change', function() {
  $('#itr_type_text').text($('#itr_type option:selected').text());
});
//-------------

$('#btn_itr_gl').click(function(){
    $('#modal_gl').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_gl').load(
        "<?php echo base_url();?>index.php/itr_request_new/show_gl",
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalGL').modal();
});
//---------

$('#btn_itr_costcenter').click(function(){
    var plant = $('#itr_plant').val();

    if(plant == ""){
        Swal('Error!','Please choose the Depot first..','error');
        return false;
    }

    data = {'plant':plant }
    $('#modal_costcenter').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_costcenter').load(
        "<?php echo base_url();?>index.php/itr_request_new/show_costcenter",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalCostCenter').modal();
});
//-----------------

$('#btn_itr_plant').click(function(){
    $('#modal_plant').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_plant').load(
        "<?php echo base_url();?>index.php/itr_request_new/show_plant",
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalPlant').modal();
});
//---------

$('#btn_itr_project').click(function(){
    $('#modal_project').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_project').load(
        "<?php echo base_url();?>index.php/itr_request_new/show_project",
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalProject').modal();
});
//---------

function call_modal_qty(matid){
    document.getElementById('itr_matid').value = $('#tblmatid_'+matid).text();
    document.getElementById('itr_matdesc').value = $('#tblmatdesc_'+matid).text();
    document.getElementById('itr_mattype').value = $('#tblmattype_'+matid).text();
    document.getElementById('itr_uom').value = $('#tbluom_'+matid).text();
    $("#itr_qty").focus();
    $('#myModalQty').modal();
}
//---------

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))
        return false;
    return true;
}
//-----------------
total_row = 0;
$('#btn_add_qty').click(function(){
    var matid = $('#itr_matid').val();
    var matdesc = $('#itr_matdesc').val();
    var mattype = $('#itr_mattype').val();
    var qty = $('#itr_qty').val();
    var uom = $('#itr_uom').val();
    var text1 = $('#itr_text').val();

    if(qty == '') Swal('Error!','QTY could not blank','error');
    else if(uom == '') Swal('Error!','UOM could not blank','error');
    else if(text1 == '') Swal('Error!','The Text could not blank','error');
    else if(parseFloat(qty) <= 0)  Swal('Error!','The QTY could be zero','error');
    else{
        total_row = total_row + 1;
        var text = "";

        text = text + "<tr id='TableListItemRow_"+total_row+"'>";
        text = text + "<td id='TableListItemRowMatId_"+total_row+"'>"+matid+"</td>";
        text = text + "<td>"+matdesc+"</td>";
        text = text + "<td>"+mattype+"</td>";
        text = text + "<td id='TableListItemRowQty_"+total_row+"'>"+qty+"</td>";
        text = text + "<td id='TableListItemRowUom_"+total_row+"'>"+uom+"</td>";
        text = text + "<td id='TableListItemRowText_"+total_row+"'>"+text1+"</td>";
        text = text + "<td><button class='btn btn-outline-danger btn-sm' onclick=process_delete("+total_row+")>X</button></td>";
        text = text + "</tr>";

        $("#TableListItem").append(text);
        $('#myModalQty').modal('hide');
        document.getElementById('itr_qty').value = "";
        document.getElementById('itr_uom').value = "";
        document.getElementById('itr_text').value = "";
    }
});
//-------------

function process_delete(index){
    var total_row = parseInt($('#total_row').val());

    total_row = total_row - 1;
    $('#TableListItemRow_'+index).remove();
}
//----------------------------

$('#btn_clear_all').click(function(){
    $("#row_input2 input[type='text']").val("");
    $("#row_input3 input[type='text']").val("");
    $("#itr_type").prop("selectedIndex", 0);
    $("#TableListItemBody").empty();
    total_row = 0;
    $("#itr_plant_text").text("");
    $("#itr_costcenter_text").text("");
    $("#itr_gl_code_text").text("");
    $("#itr_project_text").text("");
})
//-------------

$('#btn_save_all').click(function(){

    var plant = $('#itr_plant').val();
    var itr_type = $('#itr_type').val();
    var gl = $('#itr_gl').val();
    var costcenter = $('#itr_costcenter').val();
    var remarks = $('#itr_remarks').val();
    var customer = $('#itr_customer').val();
    var project = $('#itr_project').val();

    if(plant == '') Swal('Error!','Depot could not blank','error');
    else if(itr_type == '') Swal('Error!','ITR Type could not blank','error');
    else if(gl == '') Swal('Error!','GL Account could not blank','error');
    else if(costcenter == '') Swal('Error!','Cost Center could not blank','error');
    else if(remarks == '') Swal('Error!','Remarks could not blank','error');
    else if(customer == '') Swal('Error!','Customer could not blank','error');
    else if(project == '') Swal('Error!','Project / Promo could not blank','error');
    else if(total_row == 0) Swal('Error!','Item could not blank','error');
    else if($('#fileToUpload').get(0).files.length === 0) Swal('Error!','You should upload the Document File','error');
    else{
      swal({
        title: "Are you sure ?",
        html: "Save this ITR Request ?",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                $("#loading_text").text("File is uploading, Please wait...");
                $('#loading_body').show();

                setTimeout(function () {
                  var matid = [];
                  var qty = [];
                  var uom = [];
                  var text1 = [];
                  var counter = 0;

                  for(i=1;i<=total_row;i++){
                      matid[counter]  = $('#TableListItemRowMatId_'+i).text();
                      qty[counter]    = parseFloat($('#TableListItemRowQty_'+i).text());
                      uom[counter]    = $('#TableListItemRowUom_'+i).text();
                      text1[counter]   = $('#TableListItemRowText_'+i).text();
                      counter++;
                  }

                  // start process
                  var file_data = $('#fileToUpload').prop('files')[0];
                  var form_data = new FormData();
                  form_data.append('file', file_data);
                  $.ajax({
                      url       : "<?php echo base_url();?>index.php/itr_request_new/itr_request_new_uploadfile",
                      type: "POST",
                      data: form_data,
                      contentType: false,
                      cache: false,
                      processData:false,
                      success: function(data){
                          var responsedata = $.parseJSON(data);
                          console.log(data);

                          if(responsedata.status){
                            $("#loading_text").text("Your file successfully uploaded, We are saving your ITR request to system, please wait...");

                            setTimeout(function () {
                              var itr_attachment = responsedata.filename;
                              $.ajax({
                                  url       : "<?php echo base_url();?>index.php/itr_request_new/itr_request_new_add",
                                  type      : 'post',
                                  dataType  : 'html',
                                  data      : {plant:plant,itr_type:itr_type,gl:gl,costcenter:costcenter,remarks:remarks,matid:JSON.stringify(matid),qty:JSON.stringify(qty),uom:JSON.stringify(uom),text1:JSON.stringify(text1),customer:customer,project:project,itr_attachment:itr_attachment},
                                  success   :  function(respons){
                                      var title_success = 'New ITR '+respons+' has been saved, Wait the Approval from your Department Head'
                                      swal({
                                         title: title_success,
                                         type: "success", confirmButtonText: "OK",
                                      }).then(function(){
                                        setTimeout(function(){
                                          $("#fileToUpload").val(null);
                                          $('#loading_body').hide();
                                          location.reload(true);
                                        },100)
                                      });
                                  }
                                })
                            }, 1000);
                          }
                          else{
                              Swal('Error!','File failed to uploaded, please try again','error');
                              $("#fileToUpload").val(null);
                              $('#loading_body').hide();
                          }
                      }
                  });
                  //------------
                }, 1000);


            }
      })
    }
})
//--------------------

$(document).ready(function() {
    $('#fileToUpload').change(function(){
        var file_data = $('#fileToUpload').prop('files')[0];

       if(!file_data.type.match('.pdf')) {
          Swal('Error!','The File should PDF format','error');
          $("#fileToUpload").val(null);
          file_data = "";
       }
       else if(file_data.size > 3048576){
            Swal('Error!','The File not allow over than 3 MB','error');
            $("#fileToUpload").val(null);
            file_data = "";
        }
    });
});

///-----------

$(document).ready(function(){
    $.ajax({
      url   : "<?php echo base_url();?>index.php/itr_request_new/itr_request_new_list_by_user",
      type  : 'post',
      dataType : 'html',
      success   :  function(respons){
          $('#itr_list_today').show();
          $('#itr_list_today').html(respons);
      }
    })
})
//--------------

</script>
