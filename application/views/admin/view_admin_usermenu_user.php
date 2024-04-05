<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
} );

</script>


<table id="DataTable" class="table  table-striped table-bordered" style="width:100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Department</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
      <?php
        foreach($v_list_user as $row){
          echo "<tr>";
            echo "<td id='name_".$row['user_id']."'>".$row['name']."</td>";
            echo "<td id='email_".$row['user_id']."'>".$row['email']."</td>";
            echo "<td id='depart_".$row['user_id']."'>".$row['depart_name']."</td>";
            echo "<td><button class='btn btn-outline-primary btn-sm' onclick=f_process('".$row['user_id']."')>Select</button></td>";
          echo "</tr>";
        }
      ?>
  </tbody>
</table>

<script>
function f_process(userid){
    document.getElementById('username_menu').value = $('#name_'+userid).text();
    document.getElementById('userid_menu').value = userid;

    // load his menu
    $('#progress').show();
    $('#usermenu_detail').hide();
    $.ajax({
        url : "<?php echo base_url();?>index.php/admin/admin_usermenu/modal_menu",
        type      : 'post',
        dataType  : 'html',
        data      : {user:userid},
        success: function(respons){
          $("#usermenu_detail").empty().append(respons);
          $('#usermenu_detail').show();
          $('#progress').hide();
          $('#myModalUser').modal('hide');
        }
    });
}
//-----------
</script>
