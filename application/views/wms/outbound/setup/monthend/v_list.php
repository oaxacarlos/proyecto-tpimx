<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Name</th>
      <th>Warehouse</th>
      <th>From</th>
      <th>To</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_monthend as $row){
            echo "<tr>";
              echo "<td>".$row['name']."</td>";
              echo "<td>".$row['location_code']."</td>";
              echo "<td>".$row['fromm']."</td>";
              echo "<td>".$row['too']."</td>";
              echo "<td><button class='btn btn-danger btn-sm' onclick=f_delete('".$row['id']."')>X</button></td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_delete(id){
  $("#loading_text").text("Deleteing data, Please wait...");
  $('#loading_body').show();

  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/outbound/setup/monthend_delete",
      type : "post",
      dataType  : 'html',
      async: false,
      data : {id:id},
      success: function(data){
        var responsedata = $.parseJSON(data);
        if(responsedata.status == 1){
              swal({
                 title: responsedata.msg,
                 type: "success", confirmButtonText: "OK",
              }).then(function(){
                setTimeout(function(){
                  f_refresh();
                  $('#loading_body').hide();
                },100)
              });
        }
        else if(responsedata.status == 0){
            Swal('Error!',responsedata.msg,'error');
            $('#loading_body').hide();
        }
      }
  })
}

</script>
