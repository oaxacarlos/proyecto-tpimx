<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Submit Nav
</div>

<div class="container-fluid" style="margin-top:30px;">
  <button class="btn btn-outline-primary" id="btn_refresh"><i class="bi-arrow-clockwise"></i></button>
</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>
  <div id="submitnav_data"></div>
</div>

<?php echo loading_body_full(); ?>

<script>
// first load
f_refresh();
//--

$('#btn_refresh').click(function(){
    f_refresh();
});
//---

function f_refresh(){
    $('#submitnav_data').hide();
    $('#progress').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/submitnav/get_list",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progress').hide();
            $('#submitnav_data').fadeIn("5000");
            $("#submitnav_data").html(respons);
        }
    });
}
//---

function f_submit(id, month_end){
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
                html: "Submit to Navision",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                closeOnConfirm: false
              }).then(function (result) {
                  if(result.value){
                      $("#loading_text").text("Processing Document, Please wait...");
                      $('#loading_body').show();

                      $.ajax({
                          url  : "<?php echo base_url();?>index.php/wms/outbound/submitnav/submit",
                          type : "post",
                          dataType  : 'html',
                          data : {id:id, message:message, month_end:month_end},
                          success: function(data){
                              var responsedata = $.parseJSON(data);

                              if(responsedata.status == 1){
                                    swal({
                                       title: responsedata.msg,
                                       type: "success", confirmButtonText: "OK",
                                    }).then(function(){
                                      setTimeout(function(){
                                        $('#loading_body').hide();
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
          }
        }
  })
}
//---

</script>
