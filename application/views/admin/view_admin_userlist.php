<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
} );

</script>

<style>
  tr{
      font-size: 12px;
      height: 5px;
  }
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      User List
</div>

<div class="container-fluid">
  <table id="DataTable" class="table  table-striped table-bordered" style="width:100%">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Depot</th>
        <th>Action 1</th>
        <th>Action 2</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
        <?php
          foreach($v_list_user as $row){
            echo "<tr>";
              echo "<td id='name_".$row['user_id']."'>".$row['name']."</td>";
              echo "<td id='email_".$row['user_id']."'>".$row['email']."</td>";
              echo "<td id='depart_".$row['user_id']."'>".$row['depart_name']."</td>";
              echo "<td id='plant_".$row['user_id']."'>".$row['plant_code']."</td>";
              echo "<td><button class='btn btn-outline-primary btn-sm' onclick=process_edit_detail(".$row['user_id'].",'".$row['depart_code']."')>Edit Detail</button></td>";
              echo "<td><button class='btn btn-outline-dark btn-sm' onclick=process_edit_password(".$row['user_id'].")>Edit Password</button></td>";

              if($row['active'] == 'Y') echo "<td><button class='btn btn-success btn-sm' onclick=process_block('".$row['user_id']."','".$row['active']."')>ACTIVE</button></td>";
              else if($row['active'] == 'N') echo "<td><button class='btn btn-danger btn-sm' onclick=process_block('".$row['user_id']."','".$row['active']."')>BLOCKED</button></td>";

            echo "</tr>";

            //echo "<input type='hidden' name='departcode_".$row['user_id']."' value='".$row['depart_code']."' id='departcode_".$row['user_id']."'>";
          }
        ?>
    </tbody>
  </table>
</div>


<div class="modal" id="myModalEditUser">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit User</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <div class="row" style="display:none;">
            <span class="col-md-4">Userid</span>
            <span class="col-md-8"><input type="text" value="" id="userid_edit" class="form-control"></span>
          </div>
          <div class="row" >
            <span class="col-md-4">Name</span>
            <span class="col-md-8"><input type="text" value="" id="name_edit" class="form-control"></span>
          </div>
          <div class="row" style="margin-top:20px;">
            <span class="col-md-4">Email</span>
            <span class="col-md-8"><input type="text" value="" id="email_edit" class="form-control"></span>
          </div>
          <div class="row" style="margin-top:20px;">
            <span class="col-md-4">Departmet</span>
            <span class="col-md-8">
              <select class="form-control" name="" id="choosen_depart">
                <option value=''>-</option>
              <?php
                foreach($v_list_department as $row){
                  echo "<option value='".$row['depart_code']."'>".$row['depart_name']."</option>";
                }
              ?>
              </select>
            </span>
          </div>
          <div class="row" style="margin-top:20px;">
            <span class="col-md-4">Depot</span>
            <span class="col-md-8">
              <input type="text" id="plant_edit" class="form-control">
            </span>
          </div>
          <div class="row" style="margin-top:20px;">
            <span class="col-md-12" style="font-size:12px; color:red;">
              If the user can access more than 1 Depot, we have to set with character "'".<br>
              Example : 'WH3','WH2'<br>
              If the user can access for all depot just put it blank
            </span>
          </div>
          <div class="row text-right" style="margin-top:20px;">
            <span class="col-md-12">
              <button class="btn btn-success" id="btn_process_edit">Process Edit</button>
            </spn>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="myModalEditPassword">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Password</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row" style="display:none">
          <span class="col-md-4">Userid</span>
          <span class="col-md-8"><input type="text" value="" id="userid_password" class="form-control" readonly></span>
        </div>
          <div class="row" >
            <span class="col-md-4">Name</span>
            <span class="col-md-8"><input type="text" value="" id="name_password" class="form-control" readonly></span>
          </div>
          <div class="row" style="margin-top:20px;">
            <span class="col-md-4">Password</span>
            <span class="col-md-8"><input type="password" value="" id="password1" class="form-control"></span>
          </div>
          <div class="row" style="margin-top:20px;">
            <span class="col-md-4">Repeat Password</span>
            <span class="col-md-8"><input type="password" value="" id="password2" class="form-control"></span>
          </div>
          <div class="row text-right" style="margin-top:20px;">
            <span class="col-md-12">
              <button class="btn btn-success" id="btn_process_edit_password">Edit Password</button>
            </span>
          </div>
      </div>
    </div>
  </div>
</div>

<script>
function process_edit_detail(index,depart){
    document.getElementById('userid_edit').value = index;
    document.getElementById('name_edit').value = $('#name_'+index).text();
    document.getElementById('email_edit').value = $('#email_'+index).text();
    document.getElementById('plant_edit').value = $('#plant_'+index).text();
    $("#choosen_depart").val(depart).change();
    $('#myModalEditUser').modal();
};
//-------------------
function process_edit_password(index){
    document.getElementById('userid_password').value = index;
    document.getElementById('name_password').value = $('#name_'+index).text();
    document.getElementById('password1').value = "";
    document.getElementById('password2').value = "";
    $('#myModalEditPassword').modal();
};
//-------------------

$('#btn_process_edit').click(function(){
    var name = $("#name_edit").val();
    var email = $("#email_edit").val();
    var depart = $('#choosen_depart').val();
    var userid = $('#userid_edit').val();
    var plant = $('#plant_edit').val();

    if(name == '') swal('Error','Name could not blank','error');
    else if(email == '') swal('Error','Email could not blank','error');
    else if(depart == '') swal('Error','Department could not blank','error');
    else{
      swal({
        title: "Are You Sure?",
        html: "Edit this user",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                swal({ title: "Please Wait", text: "Progressing...", showConfirmButton: false });

              $.ajax({
                  url       : "<?php echo base_url();?>index.php/admin/admin_userlist/edit_user",
                  type      : 'post',
                  dataType  : 'html',
                  data      : {name:name,email:email,depart:depart,userid:userid,plant:plant},
                  success   :  function(respons){
                    if(respons != 0){
                      $('#myModalEditUser').modal('hide');
                      swal({
                         title: "Changed user has been successfull",
                         type: "success", confirmButtonText: "OK",
                      }).then(function(){
                        setTimeout(function () {
                          location.reload(true);
                        },300)
                      });
                    }
                    else{
                        Swal('Error!','Changed User not successfull','error');
                    }
                  }
              });
            }
      })
    }

});
//----------------

$('#btn_process_edit_password').click(function(){
    var userid = $('#userid_password').val();
    var password1 = $("#password1").val();
    var password2 = $("#password2").val();

    if(password1 == '' || password2 == '') swal('Error','Password could not blank','error');
    else if(password1 != password2){
        swal('Error','Password not matched','error');
        document.getElementById('password1').value = "";
        document.getElementById('password2').value = "";
    }
    else{
      swal({
        title: "Are You Sure?",
        html: "Change password for this user",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                swal({ title: "Please Wait", text: "Progressing...", showConfirmButton: false });

              $.ajax({
                  url       : "<?php echo base_url();?>index.php/admin/admin_userlist/edit_password",
                  type      : 'post',
                  dataType  : 'html',
                  data      : {userid:userid,password:password1},
                  success   :  function(respons){
                    if(respons != 0){
                        $('#myModalEditPassword').modal('hide');
                        swal({
                           title: "Changed User Password has been successfull",
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function () {
                            //location.reload(true);
                          },500)
                        });
                    }
                    else{
                        Swal('Error!','Changed User Password not successfull','error');
                    }
                  }
              });
            }
      })
    }
});
//------------------

function process_block(user,status){

      if(status == 'Y'){
        text1 = "Now this user status is Active";
        text2 = "Are you sure to block this user ?";
      }
      else if(status == 'N'){
        text1 = "Now this user status Blocked";
        text2 = "Are you sure to Active this user ?";
      }

      swal({
        title: text1,
        html: text2,
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Yes",
        showLoaderOnConfirm: true,
        closeOnConfirm: false
      }).then(function (result) {
            if(result.value){
                swal({ title: "Please Wait", text: "Progressing...", showConfirmButton: false });

             $.ajax({
                  url       : "<?php echo base_url();?>index.php/admin/admin_userlist/edit_active",
                  type      : 'post',
                  dataType  : 'html',
                  data      : {userid:user,status:status},
                  success   :  function(respons){
                    if(respons != 0){
                        swal({
                           title: "User status has been changed",
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function () {
                            location.reload(true);
                          },100)
                        });
                    }
                    else{
                        swal({
                           title: "User status not successfull to changed",
                           type: "error", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function () {
                            location.reload(true);
                          },100)
                        });
                    }
                  }
              });
            }
     })

}
//------------------

</script>
