<script>
$(document).ready(function() {
    $('#DataTable').DataTable();
});
</script>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>No</th>
      <th>Item No</th>
      <th>Desc</th>
      <th>Qty</th>
      <th>Uom</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no=1;
        foreach($var_received_detail as $row){
            echo "<tr>";
              echo "<td>".$no."</td>";
              echo "<td>".$row['item_code']."</td>";
              echo "<td>".$row['description']."</td>";
              echo "<td>".number_format($row['qty'],2)."</td>";
              echo "<td>".$row['uom']."</td>";
            echo "</tr>";
            $no++;
        }
    ?>
  </tbody>
</table>

<?php if($gen == 1){  ?>
<div class='text-right'>
  <button class="btn btn-primary text-right" id="btn_process_gen_sn">Generate S/N</button>
</div>
<?php }
      else if($gen == 0){
?>

<div class='text-right'>
  <button class="btn btn-warning text-right" id="btn_process_transfer_sn">TRANSFER S/N</button>
</div>

<?php } ?>

<input type='hidden' id='doc_no_h' name='doc_no_h' value='<?php echo $doc_no_h;  ?>'>
<input type='hidden' id='whship_no' name='doc_no_h' value='<?php echo $whship_no;  ?>'>

<script>
$('#btn_process_gen_sn').click(function(){
    var id = $('#doc_no_h').val();

    $("#loading_text").text("Checking Item, Please wait...");
    $('#loading_body').show();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/inbound/received/check_item_already_has_converter",
        type : "post",
        dataType  : 'html',
        data : {id:id},
        success: function(data){
          var responsedata = $.parseJSON(data);

          if(responsedata.status == 1){ gen_sn(id); }
          else if(responsedata.status == 0){
              show_error("The item doesn't have converter = "+ responsedata.msg);
              $('#loading_body').hide();
          }
        }
    })
});
//---

function gen_sn(id){
  swal({
    title: "Are you sure ?",
    html: "Generate S/N",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {
        if(result.value){
            $("#loading_text").text("Generating Serial Number, Please wait...");
            $('#loading_body').show();

            $.ajax({
                //url  : "<?php //echo base_url();?>index.php/wms/inbound/received/generating_sn",
                url  : "<?php echo base_url();?>index.php/wms/inbound/received/generating_sn_ver_master_barcode",
                type : "post",
                dataType  : 'html',
                data : {id:id},
                success: function(data){
                  var responsedata = $.parseJSON(data);

                  if(responsedata.status == 1){
                        swal({
                           title: responsedata.msg,
                           type: "success", confirmButtonText: "OK",
                        }).then(function(){
                          setTimeout(function(){
                            $('#loading_body').hide();
                            $('#myModalDetail').modal('toggle');
                            f_refresh();
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
        else{
            $('#loading_body').hide();
        }
  })
}
//---

$('#btn_process_transfer_sn').click(function(){
    var id = $('#doc_no_h').val();
    var whship_no = $('#whship_no').val();

    swal({
      title: "Are you sure ?",
      html: "Transfer S/N",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
          if(result.value){
              $("#loading_text").text("Transfering Serial Number, Please wait...");
              $('#loading_body').show();

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/inbound/received/transfer_sn_between_wh",
                  type : "post",
                  dataType  : 'html',
                  data : {id:id, whship_no:whship_no},
                  success: function(data){
                    var responsedata = $.parseJSON(data);

                    if(responsedata.status == 1){
                          swal({
                             title: responsedata.msg,
                             type: "success", confirmButtonText: "OK",
                          }).then(function(){
                            setTimeout(function(){
                              $('#loading_body').hide();
                              $('#myModalDetail').modal('toggle');
                              f_refresh();
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
          else{
              $('#loading_body').hide();
          }
    })
});

</script>
