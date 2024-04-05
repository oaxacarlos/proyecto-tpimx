<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Application
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-3">
      <span class="badge badge-primary">Application *</span>
      <input type='text' name='inp_search' value="" id='inp_search' class='required form-control' placeholder='Search'>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
    <div id="report_view"></div>
</div>


<?php

//-- customer
unset($autocomplete);
$i=0;
foreach($var_application as $row){
    $value = $row['application'];
    $autocomplete[$i] = $value;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);
//--

?>

<script>

var autocomplete = <?php echo $js_array_autocomplete; ?>;

$( function() {
  $( "#inp_search").autocomplete({
    source: autocomplete
  });

})
//---

$("#btn_go").click(function(){
  var search = $("#inp_search").val();

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/application_data",
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
