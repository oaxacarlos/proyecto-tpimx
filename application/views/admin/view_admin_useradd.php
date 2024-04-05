<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      User Add Form
</div>

<div class="container">
  <div class="row">
    <span class="col-md-2">Name</span>
    <span class="col-md-4"><input type="text" value="" id="name_add" class="form-control"></span>
  </div>
  <div class="row" style="margin-top:20px;">
    <span class="col-md-2">Email</span>
    <span class="col-md-4"><input type="text" value="" id="email_add" class="form-control"></span>
  </div>
  <div class="row" style="margin-top:20px;">
    <span class="col-md-2">Password</span>
    <span class="col-md-4"><input type="password" value="" id="password_add" class="form-control"></span>
  </div>
  <div class="row" style="margin-top:20px;">
    <span class="col-md-2">Department</span>
    <span class="col-md-4">
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
  <!--<div class="row" style="margin-top:20px;">
    <span class="col-md-2">Depot</span>
    <span class="col-md-4">
      <select class="form-control" name="" id="choosen_plant">
        <option value='-'>-</option>
        <option value=''>All Depot</option>
      <?php
        //foreach($v_list_plant as $row){
        //  echo "<option value='".$row['plant_code']."'>".$row['plant_code']." - ".$row['plant_name']."</option>";
        //}
      ?>
      </select>
    </span>
  </div>-->
  <div class="row" style="margin-top:20px;">
    <span class="col-md-2">User Level</span>
    <span class="col-md-4">
      <select class="form-control" name="" id="choosen_userlevel">
        <option value=''>-</option>
      <?php
        foreach($v_list_level as $row){
          echo "<option value='".$row['id_user_level']."'>".$row['user_level_name']."</option>";
        }
      ?>
      </select>
    </span>
  </div>

  <div class="row" style="margin-top:20px;">
    <span class="col-md-2">Option</span>
    <span><input type="checkbox" id="change_pass" name="change_pass">&nbsp;Change Password on next login</span>
  </div>

  <div class="row text-right" style="margin-top:20px;">
    <span class="col-md-6">
      <button class="btn btn-success" id="btn_process_add">Process Add</button>
    </spn>
  </div>
</div>

<script>
  $('#btn_process_add').click(function(){
      var change_pass = $('#change_pass').is(":checked");
      var name = $('#name_add').val();
      var password = $('#password_add').val();
      var depart = $("#choosen_depart").val();
      var email = $("#email_add").val();
      var userlevel = $("#choosen_userlevel").val();
      //var plant = $("#choosen_plant").val();

      if(name == '') swal('Error','Name could not blank','error');
      else if(email== '') swal('Error','Email could not blank','error');
      else if(password == '') swal('Error','Password could not blank','error');
      else if(depart == '') swal('Error','You should choose Department','error');
      //else if(plant == '-') swal('Error','You should choose Depot','error');
      else if(userlevel == '') swal('Error','You should choose User Level','error');
      else{
        swal({
          title: "Are you sure?",
          html: "Add this user",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Yes",
          showLoaderOnConfirm: true,
          closeOnConfirm: false
        }).then(function (result) {
              if(result.value){
                  swal({ title: "Please Wait", text: "Progressing...", showConfirmButton: false });

                $.ajax({
                    url       : "<?php echo base_url();?>index.php/admin/admin_useradd/user_add",
                    type      : 'post',
                    dataType  : 'html',
                    data      : {name:name,password:password,depart:depart,email:email,userlevel:userlevel, change_pass:change_pass},
                    success   :  function(respons){
                      if(respons == 'success'){
                          Swal('Success','User has been added','success');
                          clear();
                      }
                      else{
                          Swal('Error!',respons,'error');
                      }
                    }
                });
              }
        })
      }
  });

  function clear(){
    document.getElementById("name_add").value = "";
    document.getElementById("password_add").value = "";
    document.getElementById("choosen_depart").value = "";
    document.getElementById("email_add").value = "";
    document.getElementById("choosen_userlevel").value = "";
  }

</script>
