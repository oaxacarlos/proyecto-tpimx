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
      EMC REPORT
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


  <table id="empc_report_generate"></table>
</div>

<div class="modal" id="myModalEmpcDetail">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">EMC Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_empc_detail">
      </div>
    </div>
  </div>
</div>

<script>

$('#btn_process').click(function(){
    var date_from = $('#datepicker_from').val();
    var date_to = $('#datepicker_to').val();

    if((date_from == '') || (date_to == '')) Swal('Error','You have not filled all the Period','error');
    else if(date_from > date_to) Swal('Error','Period From should below than Period To','error');
    else{
        $('#empc_report_generate').hide();
        $('#progress').show();
        $.ajax({
            url       : "<?php echo base_url();?>index.php/empc/get_empc_report_detail",
            type      : 'post',
            dataType  : 'html',
            data      : {date_from:date_from,date_to:date_to},
            success   :  function(respons){
                $('#progress').hide();
                $('#empc_report_generate').show();
                $("#empc_report_generate").html(respons);
            }
        });
    }
});
//-----------------

function f_empc_report_detail(empc_code){
    data = {'empc_code':empc_code}

    $('#modal_empc_detail').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_empc_detail').load(
        "<?php echo base_url();?>index.php/empc/show_empc_detail_report",
        data,                                                  // data
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalEmpcDetail').modal();
}

//-----------

$(document).ready(function() {
    $('#DataTable').DataTable();
} );
</script>
