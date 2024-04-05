<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Doc No</th>
      <th>UserID</th>
      <th>Name</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_locked_doc as $row){
            echo "<tr>";
              echo "<td>".$row['doc_no']."</td>";
              echo "<td>".$row['user_locked']."</td>";
              echo "<td>".$row['name']."</td>";
              echo "<td><button class='btn btn-danger btn-sm' onclick=f_delete('".$row['doc_no']."')>X</button></td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<?php echo loading_body_full(); ?>

<script>

function f_delete(id){
    $("#loading_text").text("Unlocked Data, Please wait...");
    $('#loading_body').show();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/whship/doc_unlocked",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id},
        success: function(data){
            var responsedata = $.parseJSON(data);
            if(responsedata == "1"){
                  swal({
                     title: "Document has been unlocked",
                     type: "success", confirmButtonText: "OK",
                  }).then(function(){
                    setTimeout(function(){
                      f_refresh();
                      $('#loading_body').hide();
                    },100)
                  });
            }
            else if(responsedata == 0){
                Swal('Error!','Error','error');
                $('#loading_body').hide();
            }
        }
    })
}

</script>
