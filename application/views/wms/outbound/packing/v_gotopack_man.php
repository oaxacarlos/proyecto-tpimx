<style>

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      GoTo Pack
</div>

<div class="row">
  <div class="col-md-6 border">
    <div class="row">
      <div class="col-md-4">
          Doc No
          <span><input type='text' class="form-control input-sm" value="<?php echo $doc_no; ?>" disabled id="h_doc_no"></span>
      </div>
      <div class="col-md-3">
        WHS
        <span><input type='text' class="form-control" value="<?php echo $whs; ?>" disabled id="h_whs"></span>
      </div>
      <div class="col-md-1">
        Close
        <button class="btn btn-danger" onclick=f_close('<?php echo $doc_no; ?>')>Close</button>
      </div>
      <div class="col-md-2 text-right">
        <button class="btn btn-outline-primary btn-sm" style="margin-top:30px;" id="btn_refresh_list_outbound"><i class="bi-arrow-clockwise"></i></button>
      </div>
    </div>
    <div style="margin-top:10px;">
      <textarea class="form-control" disabled> <?php echo $result_doc_h["external_document"]; ?>
      </textarea>
    </div>
    <div id="list_outbound"></div>
    <?php echo progress_bar("progr_list_outbound"); ?>
  </div>

  <div class="col-md-6 border">
      <div id="list_pack"></div>
  </div>

</div>

<div class="container-fluid" style="margin-top:20px;">
  Packing List
  <div id="list_packed"></div>
</div>

<?php echo loading_body_full(); ?>

<script>

// first load
f_refresh_list_outbound();
//--

function f_refresh_list_outbound(){
    var doc_no = '<?php echo $doc_no; ?>';

    $('#list_outbound').hide();
    $('#progr_list_outbound').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/packing/get_gotopack_man_list",
        type      : 'post',
        dataType  : 'html',
        data      :  {doc_no:doc_no},
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

function f_load_list_pack(){
    $('#list_pack').hide();
    //$('#progr_list_pick').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/packing/get_gotopack_man_pack",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
            $('#progr_list_pack').hide();
            $('#list_pack').fadeIn("3000");
            $("#list_pack").html(respons);
        }
    });
}
//---

f_load_list_pack();
//---

function f_load_list_packed(){
    var doc_no = '<?php echo $doc_no; ?>';

    $('#list_packed').hide();
    //$('#progr_list_pick').show();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/outbound/packing/get_gotopack_man_list_packed",
        type      : 'post',
        dataType  : 'html',
        data      :  {doc_no:doc_no},
        success   :  function(respons){
            $('#progr_list_packed').hide();
            $('#list_packed').fadeIn("3000");
            $("#list_packed").html(respons);
        }
    });
}
//---

f_load_list_packed();
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

    window.location.href = "<?php echo base_url();?>index.php/wms/outbound/packing";
}
//----

function checking_this_doc_not_locked(){

    var id = '<?php echo $doc_no; ?>';
    if(check_doc_locked(id)==1){
        alert("This Document has been locked by another user");
        window.location.href = "<?php echo base_url();?>index.php/wms/outbound/packing";
    }
    else{
        // locked the document
        doc_locked(id);
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

</script>
