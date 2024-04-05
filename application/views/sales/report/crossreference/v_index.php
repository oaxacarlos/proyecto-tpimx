<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Cross Reference
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <span class="badge badge-primary">Search *</span>
      <input type='text' name='inp_search' value="" id='inp_search' class='required form-control' placeholder='Search here'>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
    <div id="report_view"></div>
</div>


<script>

$("#btn_go").click(function(){

      var search = $("#inp_search").val();

      $.ajax({
          url       : "<?php echo base_url();?>index.php/sales/report/crossreference_data",
          type      : 'post',
          dataType  : 'html',
          data      :  {search:search},
          success   :  function(respons){
              $("#report_view").fadeIn("5000");
              $("#report_view").html(respons);
          }
      });
})

</script>
