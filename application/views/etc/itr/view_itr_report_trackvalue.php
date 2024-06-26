<script>

$(document).ready(function(){
  //Initialize the datePicker(I have taken format as mm-dd-yyyy, you can     //have your owh)
  $("#datepicker_from").datetimepicker({
     timepicker: false,
     format : 'Y-m-d'
  });

  $("#datepicker_to").datetimepicker({
     timepicker: false,
     format : 'Y-m-d'
  });

  $.datetimepicker.setLocale('en');

});

</script>

<style>
  tr{
      font-size: 14px;
  }

  .modal-lg {
    max-width: 80%;
  }
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      ITR REPORT TRACKING VALUE
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from' class='required form-control' placeholder='Period From'>
    </div>
    <div class="col-md-2">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to' class='required form-control' placeholder='Period To'>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-primary" id="btn_process">Process</button>
    </div>
  </div>
</div>



<div class="container-fluid" style="margin-top:30px;">
  <div id="progress" style="display:none; text-align:center;">
    <div class="spinner-grow text-muted"></div>
    <div class="spinner-grow text-primary"></div>
    <div class="spinner-grow text-success"></div>
    <div class="spinner-grow text-info"></div>
    <div class="spinner-grow text-warning"></div>
    <div class="spinner-grow text-danger"></div>
    <div class="spinner-grow text-secondary"></div>
    <div class="spinner-grow text-dark"></div>
    <div class="spinner-grow text-light"></div>
  </div>


  <div id="itr_report_track_generate"></div>
</div>

<script>

$('#btn_process').click(function(){
    var date_from = $('#datepicker_from').val();
    var date_to = $('#datepicker_to').val();

    if((date_from == '') || (date_to == '')) Swal('Error','You have not filled all the Period','error');
    else if(date_from > date_to) Swal('Error','Period From should below than Period To','error');
    else{
        $('#itr_report_track_generate').hide();
        $('#progress').show();
        $.ajax({
            url       : "<?php echo base_url();?>index.php/itr_report/get_itr_report_trackvalue_generate",
            type      : 'post',
            dataType  : 'html',
            data      : {date_from:date_from,date_to:date_to},
            success   :  function(respons){
                $('#progress').hide();
                $('#itr_report_track_generate').show();
                $("#itr_report_track_generate").html(respons);
            }
        });
    }
});
//-----------------
</script>
