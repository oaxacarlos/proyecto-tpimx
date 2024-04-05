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
      User Menu
</div>

<div class="container-fluid">
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
              echo "<td>".$row['name']."</td>";
              echo "<td>".$row['email']."</td>";
              echo "<td>".$row['depart_name']."</td>";
            echo "<td><button class='btn btn-outline-primary btn-sm' onclick=process_menu(".$row['user_id'].")>Menu</button></td>";
            echo "</tr>";
          }
        ?>
    </tbody>
  </table>
</div>

<div class="modal" id="myModalMenu">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Menu</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>


<script>

function process_menu(index){

  var user = index;

  data = {'user':user}

  $('.modal-body').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('.modal-body').load(
      "<?php echo base_url();?>index.php/admin_usermenu/modal_menu",
      data,                                                  // data
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalMenu').modal();
}
//---------



</script>
