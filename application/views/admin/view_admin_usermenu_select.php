List Menu User
<table class="table table-striped">
  <thead>
      <tr>
        <th>Menu Lvl 1</th>
        <th>Menu Lvl 2</th>
        <th>Menu Lvl 3</th>
        <th>Action</th>
      </tr>
  </thead>
  <tbody>
    <?php
      $i = 1;
      foreach($v_list_menu_by_user as $row){
          echo "<tr>";
          echo "<td>".$row['menu1_name']."</td>";
          echo "<td>".$row['menu2_name']."</td>";
          echo "<td>".$row['menu3_name']."</td>";
          echo "<td style='display:none;' id='code_".$i."'>".$row['menu3_code']."</td>";

          if($row['menu_status'] == 1){ $checked = "checked"; $value=1;}
          else if($row['menu_status'] == 0) { $checked = ""; $value=0; }

          echo "<td><input type='checkbox' ".$checked." value='".$row['menu3_code']."' id='checklist_item' name='checklist_item[]' class='".$row['menu3_code']."'></td>";
          echo "</tr>";
          $i++;
      }

    ?>
  </tbody>
</table>

<input type="hidden" value="<?php echo count($v_list_menu_by_user); ?>" id="total_menu">

<div class="text-right">
    <button class="btn btn-success text-right" onclick=f_process_assign()>Process Assign</button>
</div>


<script>

//-------------
function f_process_assign(){
      swal({
      title: "Are You Sure?",
      html: "Assign the Menus to this user ?",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
          if(result.value){
              swal({ title: "Please Wait", text: "Progressing...", showConfirmButton: false });

              var total_menu = $('#total_menu').val();
              var user = '<?php echo $userid; ?>';
              var menu = [];
              var checked = [];
              var counter = 0;

              var total = $('input[name="checklist_item[]"]:checked').length;
              var checklist = $('input[name="checklist_item[]"]:checked').map(function(){
                  return this.value;
              }).toArray();

              $.ajax({
                  url       : "<?php echo base_url();?>index.php/admin/admin_usermenu/assign_menu",
                  type      : 'post',
                  dataType  : 'html',
                  data      : {user:user,checked:JSON.stringify(checklist)},
                  success   :  function(respons){
                    if(respons != 0){
                        Swal('Success','Menus have been Assigned to this user','success');
                        f_process(user);
                    }
                    else{
                        Swal('Error!','Menus were not successfull assigned to this user','error');
                    }
                  }
              });

          }
    })
}

</script>
