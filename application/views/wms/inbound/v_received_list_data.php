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

<div class='text-right'>
  <button class="btn btn-primary text-right" id="btn_process_verify">PROCESS</button>
</div>

<input type='hidden' id='doc_no_h' name='doc_no_h' value='<?php echo $doc_no_h;  ?>'>

<script>
$('#btn_process_verify').click(function(){
    var id = $('#doc_no_h').val();

    swal({
      title: "Are you sure ?",
      html: "All the Items have been verified",
      type: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }).then(function (result) {
          if(result.value){
              $("#loading_text").text("Verifiying process, Please wait...");
              $('#loading_body').show();

              $.ajax({
                  url  : "<?php echo base_url();?>index.php/wms/inbound/received/tranf_to_gen_sn",
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
    })

});
//---
</script>
