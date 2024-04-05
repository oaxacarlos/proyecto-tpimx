<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      "order": [[ 1, "desc" ]]
    });
});
</script>

<?php echo loading_body_full(); ?>

<table id="DataTable" class="table table-bordered table-striped table-sm">
  <thead>
    <tr>
      <th>Date</th>
      <th>No</th>
      <th>WHS</th>
      <th>Ext doc no</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
        foreach($var_whrcpt_list_h as $row){
            echo "<tr id='row_".$row['no']."'>";
              echo "<td>".convert_date_to_date($row['posting_date'])."</td>";
              echo "<td>".$row['no']."</td>";
              echo "<td>".$row['loc_code']."</td>";
              echo "<td>".$row['ext_doc_no']."</td>";
              echo "<td>
                <button class='btn btn-sm btn-outline-primary' onclick=f_show_detail('".$row['no']."')>Detail</button>
                <button class='btn btn-sm btn-primary' onclick=f_process('".$row['no']."')>Process</button>
              </td>";
            echo "</tr>";
        }
    ?>
  </tbody>
</table>

<script>

function f_show_detail(id){
  var link = 'wms/inbound/v_whrcpt_list_data_detail';
  data = {'id':id, 'link':link }
  $('#modal_detail').html('Loading, Please wait...');
  //open the modal with selected parameter attached
  $('#modal_detail').load(
      "<?php echo base_url();?>index.php/wms/inbound/whrcpt/get_whrcpt_list_d",
      data,
      function(responseText, textStatus, XMLHttpRequest) { } // complete callback
  );

  $('#myModalDetail').modal();
}
//---

function f_process(id){


  swal({
    input: 'textarea',
    inputPlaceholder: 'Type your message here',
    showCancelButton: true,
    confirmButtonText: 'OK'
  }).then(function (result) {
      if(result.dismiss == "cancel"){}
      else{
        if(result.value == ""){
            show_error("You have to type message");
        }
        else{
            var message = result.value;

              swal({
                title: "Are you sure ?",
                html: "Received this Items",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
              }).then(function (result) {
                    if(result.value){
                        $("#loading_text").text("Transfering from WHS Receipt to Warehouse, Please wait...");
                        $('#loading_body').show();

                        $.ajax({
                            url  : "<?php echo base_url();?>index.php/wms/inbound/whrcpt/transfer_whrcpt_to_received",
                            type : "post",
                            dataType  : 'html',
                            data : {id:id, message:message},
                            success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                          delete_row(id); // delete the row
                                      },100)
                                    });
                              }
                              else if(responsedata.status == 0){
                                  Swal('Error!',responsedata.msg,'error');
                              }

                              $('#loading_body').hide();
                            }
                        })
                    }
              })
        }
      }
})


}
//----

function delete_row(id){
    $('#row_'+id).fadeOut(1000, function(){ $(this).remove(); });
}

</script>
