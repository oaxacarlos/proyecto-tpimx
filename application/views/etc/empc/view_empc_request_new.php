<script>
$(document).ready(function() {
    $('#DataTableMaterial').DataTable();
    $('#DataTableEmployee').DataTable();
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
      EMPLOYEE CHARGE REQUEST
</div>

<?php
  $session_data = $this->session->userdata('z_tpimx_logged_in');
?>

<div class="row" style="margin-top:20px; margin-left:20px;">
    <div class="border col-md-5">
      <div class="row" style="padding:5px;">
        <div class="col-md-6">
          Date
          <input type="text" class="form-control form-control-sm" value="<?php echo date("Y-m-d"); ?>" readonly>
        </div>
        <div class="col-md-6">
          Requestor Name
          <input type="text" class="form-control form-control-sm" value="<?php echo $session_data['z_tpimx_name'];?>" readonly>
        </div>
      </div>

      <div class="row" style="padding:5px;">
        <div class="col-md-6">
          Requestor Department
          <input type="text" class="form-control form-control-sm" value="<?php echo $session_data['z_tpimx_depart_name'];?>" readonly>
        </div>
        <div class="col-md-6">
          Email
          <input type="text" class="form-control form-control-sm" value="<?php echo $session_data['z_tpimx_email'];?>" readonly>
        </div>
      </div>
    </div>

    <div class="border col-md-6" style="margin-left:10px;">
        <div class="row" style="padding:5px;">
            <div class="col-md-6">
              Type *
              <select id="empc_type" class="form-control form-control-sm">
                <option value="">-</option>
                <?php
                  foreach($v_list_empc_type as $row){
                      echo "<option value='".$row['movt_type_code']."'>".$row['movt_type_code']." - ".$row['movt_type_name']."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-md-6">
              Depot *
                <div class="input-group">
                  <input type="text" id="empc_plant" name="empc_plant" value="" class="form-control form-control-sm" class="col-md-2" style="pointer-events:none;">
                  <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_empc_plant">...</button>
                </div>
                <span id="empc_plant_text" style="margin-left:10px;" class="badge badge-secondary"></span>
            </div>
            <div class="col-md-6" style="padding-top:10px;">
              Cost Center *
                <div class="input-group">
                  <input type="text" id="empc_costcenter" name="empc_costcenter" value="" class="form-control form-control-sm" class="col-md-2" style="pointer-events:none;">
                  <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_empc_costcenter">...</button>
                </div>
                <span id="empc_costcenter_text" style="margin-left:5px;" class="badge badge-secondary"></span>
            </div>
            <div class="col-md-6" style="padding-top:10px;">
              GL Account *
                <div class="input-group">
                  <input type="text" id="empc_gl_code" name="empc_gl_code" value="" class="form-control form-control-sm" class="col-md-2" style="pointer-events:none;">
                  <input type="text" style="display:none;" id="empc_gl" name="empc_gl" value="" class="form-control" class="col-md-2" style="pointer-events:none;">
                  <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_empc_gl">...</button>
                </div>
                <span id="empc_gl_code_text" style="margin-left:5px;" class="badge badge-secondary"></span>
            </div>
        </div>
    </div>

    <!-- Upload File -->
    <div class="border col-md-5" style="margin-top:10px;">
        <div class="row" style="padding:5px;">
          <span class="badge badge-danger">Upload File is Mandatory (format on PDF, EXCEL) *</span><br>
          <input type='file' name='fileToUpload' id='fileToUpload' accept=".pdf,.xlsx,.xls">
        </div>
    </div>

    <!-- Employee -->
  <!--  <div class="border col-md-4" style="margin-top:10px; margin-left:10px;">
        <div class="row">
          <div class="col-md-5">
              Employ Code SAP *
              <div class="input-group">
                <input type="text" id="empc_employee" name="empc_employee" value="" class="form-control form-control-sm">
                <button class="btn btn-primary btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#myModalEmployee">...</button>
              </div>
          </div>
          <div class="col-md-7">
              Employee Name
              <div class="input-group">
                <input type="text" id="empc_employee_name" name="empc_employee_name" value="" class="form-control form-control-sm" readonly>
              </div>
          </div>
        </div>

    </div>-->

    <div class="border col-md-6" style="margin-top:10px; margin-left:10px;">
        Remarks *
        <span class="input-group">
          <input type="text" id="empc_remarks" name="empc_remarks" value="" class="form-control form-control-sm">
        </span>
    </div>
</div>

<div class="container-fluid">
    <!-- Employee list -->
    <div class="border" style="margin-top:10px;">
      <div class="container-fluid" style="margin-top:10px;">
          <div><span style="font-size:20px;"><b>Employee List</b></span>
          <button class="btn btn-primary btn-sm" style="margin-left:5px;" data-toggle="modal" data-target="#myModalEmployee">...</button>
          </div>
          <table id="TableListEmployee" class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="TableListEmployeeBody">
            </tbody>
          </table>
      </div>
    </div>
</div>

<div class="container-fluid">
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
        <table id="DataTableMaterial" class="table table-striped table-bordered table-sm" style="width:100%">
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

<div class="modal" id="myModalEmployee">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Employee List</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_employee">
        <table id="DataTableEmployee" class="table table-striped table-bordered table-sm" style="width:100%">
          <thead>
            <tr>
              <th>Employee Code</th>
              <th>Name</th>
              <th>Address</th>
              <th>City</th>
              <th>Phone</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
              <?php
                foreach($v_list_employee as $row){
                  echo "<tr>";
                    echo "<td id='tblempcode_".$row['employee_code']."'>".$row['employee_code']."</td>";
                    echo "<td id='tblempname_".$row['employee_code']."'>".$row['employee_name']."</td>";
                    echo "<td id='tblempadd_".$row['employee_code']."'>".$row['employee_address']."</td>";
                    echo "<td id='tblempcity_".$row['employee_code']."'>".$row['city']."</td>";
                    echo "<td id='tblempphone_".$row['employee_code']."'>".$row['phone1']."</td>";
                    echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_selected_employee(".$row['employee_code'].")>select</button></td>";
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
            <span><input type="text" class="form-control form-control-sm" id="empc_matid" readonly></span>
          </div>
          <div>
            <span>Material Desc</span>
            <span><input type="text" class="form-control form-control-sm" id="empc_matdesc" readonly></span>
          </div>
          <div>
            <span>Material Type</span>
            <span><input type="text" class="form-control form-control-sm" id="empc_mattype" readonly></span>
          </div>
          <div>
            <span>QTY</span>
            <span><input type="text" class="form-control form-control-sm" id="empc_qty" autofocus onkeypress="return isNumberKey(event)"></span>
          </div>
          <div>
            <span>UOM</span>
            <span>
              <select id="empc_uom" class="form-control">
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
            <span><input type="text" class="form-control form-control-sm" id="empc_text" maxlength="50"></span>
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
//---------------
function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 29) { // 30 minutes
        alert("You have idle over than 30 minutes, the system will log out..");
        window.location.href = '<?php echo base_url();?>index.php/logout';
    }
}
//-----------------

$('#empc_type').on('change', function() {
  $('#empc_type_text').text($('#empc_type option:selected').text());
});
//-------------

$('#btn_empc_gl').click(function(){
    $('#modal_gl').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_gl').load(
        "<?php echo base_url();?>index.php/empc/show_gl",
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalGL').modal();
});
//---------

$('#btn_empc_costcenter').click(function(){
    var plant = $('#empc_plant').val();

    if(plant == ""){
        Swal('Error!','Please choose the Depot first..','error');
        return false;
    }

    data = {'plant':plant }
    $('#modal_costcenter').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_costcenter').load(
        "<?php echo base_url();?>index.php/empc/show_costcenter",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalCostCenter').modal();
});
//-----------------

$('#btn_empc_plant').click(function(){
    $('#modal_plant').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_plant').load(
        "<?php echo base_url();?>index.php/empc/show_plant",
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalPlant').modal();
});
//---------

function call_modal_qty(matid){
    document.getElementById('empc_matid').value = $('#tblmatid_'+matid).text();
    document.getElementById('empc_matdesc').value = $('#tblmatdesc_'+matid).text();
    document.getElementById('empc_mattype').value = $('#tblmattype_'+matid).text();
    document.getElementById('empc_uom').value = $('#tbluom_'+matid).text();
    $("#empc_qty").focus();
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
total_row_employee = 0;

$('#btn_add_qty').click(function(){
    var matid = $('#empc_matid').val();
    var matdesc = $('#empc_matdesc').val();
    var mattype = $('#empc_mattype').val();
    var qty = $('#empc_qty').val();
    var uom = $('#empc_uom').val();
    var text1 = $('#empc_text').val();

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
        document.getElementById('empc_qty').value = "";
        document.getElementById('empc_uom').value = "";
        document.getElementById('empc_text').value = "";
    }
});
//-------------

function process_delete(index){
    var total_row = parseInt($('#total_row').val());

    total_row = total_row - 1;
    $('#TableListItemRow_'+index).remove();
}
//----------------------------

function process_delete_employee_table(index){
    total_row_employee = total_row_employee - 1;
    $('#TableListEmployeeRow_'+index).remove();
}
//----------------------------

$('#btn_clear_all').click(function(){
    $("#row_input2 input[type='text']").val("");
    $("#row_input3 input[type='text']").val("");
    $("#empc_type").prop("selectedIndex", 0);
    $("#TableListItemBody").empty();
    $("#TableListEmployeeBody").empty();
    total_row = 0;
    $("#empc_plant_text").text("");
    $("#empc_costcenter_text").text("");
    $("#empc_gl_code_text").text("");
    $("#empc_project_text").text("");
})
//-------------

$(document).ready(function() {
    $('#fileToUpload').change(function(){
       var file_data = $('#fileToUpload').prop('files')[0];

       if((!file_data.type.match('.pdf')) && (!file_data.type.match('.sheet')) && (!file_data.type.match('.excel'))) {
          Swal('Error!','The File should PDF, Excel format','error');
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
      url   : "<?php echo base_url();?>index.php/empc/empc_request_new_list_by_user",
      type  : 'post',
      dataType : 'html',
      success   :  function(respons){
          $('#empc_list_today').show();
          $('#empc_list_today').html(respons);
      }
    })
})
//--------------

function f_selected_employee(code){
    /*$('#empc_employee').val(code);
    $('#empc_employee_name').val($("#tblempname_"+code).text());
    $('#myModalEmployee').modal('hide');*/

    total_row_employee = total_row_employee + 1;
    var text = "";

    text = text + "<tr id='TableListEmployeeRow_"+total_row_employee+"'>";

    temp_code = $('#tblempcode_'+code).text();
    text = text + "<td id='TableListItemEmployeeEmCode_"+total_row_employee+"'>"+temp_code+"</td>";

    temp_name = $('#tblempname_'+code).text();
    text = text + "<td id='TableListItemEmployeeEmName_"+total_row_employee+"'>"+temp_name+"</td>";

    temp_add = $('#tblempadd_'+code).text();
    text = text + "<td id='TableListItemEmployeeEmAdd_"+total_row_employee+"'>"+temp_add+"</td>";

    temp_city = $('#tblempcity_'+code).text();
    text = text + "<td id='TableListItemEmployeeEmCity_"+total_row_employee+"'>"+temp_city+"</td>";

    temp_phone = $('#tblempphone_'+code).text();
    text = text + "<td id='TableListItemEmployeeEmPhone_"+total_row_employee+"'>"+temp_phone+"</td>";

    text = text + "<td><button class='btn btn-outline-danger btn-sm' onclick=process_delete_employee_table("+total_row_employee+")>X</button></td>";
    text = text + "</tr>";

    $("#TableListEmployee").append(text);

}
//--------------

$('#btn_save_all').click(function(){

    // check connection
  /*  $.ajax({
        url       : "<?php echo base_url();?>index.php/general/check_connection",
        type      : 'post',
        dataType  : 'html',
        success: function(data){
          alert(data);
            if(data == "false") return false;
        }
    });*/
    //----------------

    var plant = $('#empc_plant').val();
    var empc_type = $('#empc_type').val();
    var gl = $('#empc_gl').val();
    var costcenter = $('#empc_costcenter').val();
    var remarks = $('#empc_remarks').val();
    var employee = $('#empc_employee').val();

    if(empc_type == '') Swal('Error!','Type could not blank','error');
    else if(plant == '') Swal('Error!','Depot could not blank','error');
    else if(costcenter == '') Swal('Error!','Cost Center could not blank','error');
    else if(gl == '') Swal('Error!','GL Account could not blank','error');
    else if(employee == '') Swal('Error!','Employee could not blank','error');
    else if(remarks == '') Swal('Error!','Remarks could not blank','error');
    else if(total_row_employee == 0) Swal('Error!','You have not input the Employee','error');
    else if(total_row == 0) Swal('Error!','You have not input the Material','error');
    else if($('#fileToUpload').get(0).files.length === 0) Swal('Error!','You should upload the Document File','error');
    else{
      swal({
        title: "Are you sure ?",
        html: "Save this EMPC Request ?",
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
                  // get material
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
                  //-----------------

                  // get employee
                  var customer_text = "";

                  for(i=1;i<=total_row_employee;i++){
                      temp = $('#TableListItemEmployeeEmCode_'+i).text() + "-" +$('#TableListItemEmployeeEmName_'+i).text();
                      customer_text = customer_text + temp + "\n\r";
                  }
                  //--------------

                  // start process
                  var file_data = $('#fileToUpload').prop('files')[0];
                  var form_data = new FormData();
                  form_data.append('file', file_data);
                  $.ajax({
                      url  : "<?php echo base_url();?>index.php/empc/empc_request_new_uploadfile",
                      type : "POST",
                      data : form_data,
                      contentType: false,
                      cache: false,
                      processData:false,
                      success: function(data){
                          var responsedata = $.parseJSON(data);
                          console.log(data);

                          if(responsedata.status){
                            $("#loading_text").text("Your file successfully uploaded, We are saving your EMPC request to system, please wait...");

                            setTimeout(function () {
                              var empc_attachment = responsedata.filename;
                              $.ajax({
                                  url       : "<?php echo base_url();?>index.php/empc/empc_request_new_add",
                                  type      : 'post',
                                  dataType  : 'html',
                                  //data      : {plant:plant,empc_type:empc_type,gl:gl,costcenter:costcenter,remarks:remarks,matid:JSON.stringify(matid),qty:JSON.stringify(qty),uom:JSON.stringify(uom),text1:JSON.stringify(text1),employee:employee,empc_attachment:empc_attachment},
                                  data      : {plant:plant,empc_type:empc_type,gl:gl,costcenter:costcenter,remarks:remarks,matid:JSON.stringify(matid),qty:JSON.stringify(qty),uom:JSON.stringify(uom),text1:JSON.stringify(text1),customer:customer_text,empc_attachment:empc_attachment},
                                  success   :  function(respons){
                                      var title_success = 'New Employee Charge '+respons+' has been saved, Wait the Approval from your Department Head'
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

</script>
