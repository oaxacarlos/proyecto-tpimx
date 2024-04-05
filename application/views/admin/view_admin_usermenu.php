<style>
  tr{
      font-size: 12px;
      height: 5px;
  }
</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      User Menu
</div>

<div class="container-fluid">
  <div class="col-md-2">
    User
      <div class="input-group">
        <input type="text" id="username_menu" name="username_menu" value="" class="form-control" class="col-md-2" style="pointer-events:none;">
        <input type="hidden" id="userid_menu" name="userid_menu">
        <button class="btn btn-primary btn-sm" style="margin-left:5px;" id="btn_userid">...</button>
      </div>
  </div>
</div>

<div class="container-fluid" style="padding-top:20px;">
  <div class="progress" id="progress" style="display:none;">
    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:50%"></div>
  </div>
  <div id="usermenu_detail"></div>
</div>

<div class="modal" id="myModalUser">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">User</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_user"></div>
    </div>
  </div>
</div>

<script>
$('#btn_userid').click(function(){
    $('#modal_user').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_user').load(
        "<?php echo base_url();?>index.php/admin/admin_usermenu/show_user",
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalUser').modal();
});
//--------------


</script>
