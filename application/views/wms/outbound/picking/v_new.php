<style>

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      New Picking List
</div>

<div class="row" style="margin-left:5px;">
  <div class="col-6 border">
    <div class="row">
      <div class="col-4">
          Doc No
          <span><input type='text' class="form-control input-sm" value="<?php echo $doc_no; ?>" disabled id="h_doc_no"></span>
      </div>
      <div class="col-2">
          Doc Date
          <span><input type='text' class="form-control" value="<?php echo $doc_date; ?>" disabled></span>
      </div>
      <div class="col-2">
        WHS
        <span><input type='text' class="form-control" value="<?php echo $whs; ?>" disabled id="h_whs"></span>
      </div>
      <div class="col-2">
        <button class="btn btn-danger btn-sm" style="margin-top:30px;" onclick=f_close('<?php echo $doc_no; ?>')>Close</button>
      </div>
      <div class="col-2 text-right">
        <button class="btn btn-outline-primary btn-sm" style="margin-top:30px;" id="btn_refresh_list_outbound"><i class="bi-arrow-clockwise"></i></button>
      </div>
    </div>
    <div id="list_outbound"></div>
    <?php echo progress_bar("progr_list_outbound"); ?>
  </div>
  <div class="col-6 border">
      <div id="list_pick"></div>
  </div>
</div>

<?php echo loading_body_full(); ?>

<script>

// first load
f_refresh_list_outbound();
//--

function f_refresh_list_outbound(){
    var doc_no = '<?php echo $doc_no; ?>';
    var whs = '<?php echo $whs; ?>'; // 2023-3-06 WH3

    $('#list_outbound').hide();
    $('#progr_list_outbound').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/picking/list_outbound",
        type      : 'post',
        dataType  : 'html',
        data      :  {doc_no:doc_no, whs:whs},
        success   :  function(respons){
            $('#progr_list_outbound').hide();
            $('#list_outbound').fadeIn("3000");
            $("#list_outbound").html(respons);
        }
    });
}
//---

$("#btn_refresh_list_outbound").click(function(){
    f_refresh_list_outbound();
})
//---

function f_load_list_pick(){
    $('#list_pick').hide();
    //$('#progr_list_pick').show();

    var whs = '<?php echo $whs; ?>'; // 2023-3-06 WH3
    var doc_no = '<?php echo $doc_no; ?>'; // 2023-07-12

    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/picking/list_pick",
        type      : 'post',
        dataType  : 'html',
        data      : { whs:whs, doc_no:doc_no },
        success   :  function(respons){
            $('#progr_list_pick').hide();
            $('#list_pick').fadeIn("3000");
            $("#list_pick").html(respons);
        }
    });
}
//---

f_load_list_pick();
//---

function f_close(id){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/whship/doc_unlocked",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    window.location.href = "<?php echo base_url();?>index.php/wms/outbound/picking";
}
//----

function checking_this_doc_not_locked(){

    var id = '<?php echo $doc_no; ?>';
    if(check_doc_locked(id)==1){
        alert("This Document has been locked by another user");
        //window.location.href = "<?php //echo base_url();?>index.php/wms/outbound/picking";
        window.location.href = "<?php echo $srclink;?>";

    }
    else{
        // locked the document
        //doc_locked(id);
    }
}
checking_this_doc_not_locked();

//---

function check_doc_locked(id){
  $.ajax({
      url  : "<?php echo base_url();?>index.php/wms/outbound/whship/check_doc_locked",
      type : "post",
      dataType  : 'json',
      async: false,
      data : {id:id},
      success: function(data){
          result = $.parseJSON(data);
      }
  })

  return result;
}
//--

function doc_locked(id){
    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/outbound/whship/doc_locked",
        type : "post",
        dataType  : 'json',
        async: false,
        data : {id:id},
        success: function(data){
            result = $.parseJSON(data);
        }
    })

    return result;
}
//---
/*
window.addEventListener("beforeunload", function (e) {
 var confirmationMessage = "tab close";

 (e || window.event).returnValue = confirmationMessage;     //Gecko + IE
 sendkeylog(confirmationMessage);
 return confirmationMessage;                                //Webkit, Safari, Chrome etc.
});
*/
</script>
