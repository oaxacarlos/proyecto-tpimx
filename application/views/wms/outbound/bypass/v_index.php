<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Outbound ByPass
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-3">
      Warehouse Shipment
      <input type="text" value="TPM-WSHIP-" id="inp_whship" class="form-control">
    </div>
    <div class="col-1">
      Process
      <button class="btn btn-primary" id="btn_process">PROCESS</button>
    </div>
  </div>
</div>

<?php echo loading_body_full() ?>

<script>

$("#btn_process").click(function(){

  swal({
    input: 'textarea',
    inputPlaceholder: 'Type your message here',
    showCancelButton: true,
    confirmButtonText: 'OK'
  }).then(function (result) {
        if(result.dismiss == "cancel"){}
        else{
          if(result.value == ""){ show_error("You have to type message");}
          else{
              var message = result.value;

              swal({
                title: "Are you sure ?",
                html: "Bypass this document",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
              }).then(function (result) {
                  if(result.value){

                    // if stock available.. create picking
                    $("#loading_text").text("Processing the Document, Please wait...");
                    $('#loading_body').show();
                    //---

                      // get variable
                      var doc_no = $("#inp_whship").val();

                      $.ajax({
                          url  : "<?php echo base_url();?>index.php/wms/outbound/bypass/process",
                          type : "post",
                          dataType  : 'html',
                          data : {doc_no:doc_no, message:message},
                          success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                        $('#loading_body').hide();
                                        location.reload();
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

          }
    }
  })
})

</script>
