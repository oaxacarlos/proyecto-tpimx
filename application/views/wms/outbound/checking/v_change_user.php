<div class="container">
    <select id="inp_user_id" name="inp_user_id" class="form-control">
        <?php
          foreach($var_user as $row){
              echo "<option value='".$row["user_id"]."'>".$row["name"]."</option>";
          }
        ?>
    </select>
</div>

<div class="container" style="margin-top:20px;">
    <button class="btn btn-primary" onclick=f_change_user_process('<?php echo $doc_no; ?>')>CHANGE</button>
</div>

<script>
  function f_change_user_process(id){
      var doc_no = id;
      var userid = $("#inp_user_id").val();

      $.ajax({
          url  : "<?php echo base_url();?>index.php/wms/outbound/checking/change_user_process",
          type : "post",
          dataType  : 'html',
          data : {doc_no:doc_no, userid:userid},
          success: function(data){
              var responsedata = $.parseJSON(data);

              if(responsedata.status == 1){
                    swal({
                       title: responsedata.msg,
                       type: "success", confirmButtonText: "OK",
                    }).then(function(){
                      setTimeout(function(){
                        $('#myModalChangeUser').modal("toggle");
                        f_refresh();
                      },100)
                    });
              }
              else if(responsedata.status == 0){
                  Swal('Error!',responsedata.msg,'error');
              }
          }
      })
  }
</script>
