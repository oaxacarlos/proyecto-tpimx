<script>
// copy clipboard
var clipboard = new ClipboardJS('#copy_button_custdetailreview_filter', {
  target: function() {
    return document.querySelector('#tbl_report_custdetailreview_filter');
  }
});

var clipboard2 = new ClipboardJS('#copy_button_custdetailreview_banda', {
  target: function() {
    return document.querySelector('#tbl_report_custdetailreview_banda');
  }
});

var clipboard3 = new ClipboardJS('#copy_button_custdetailreview_filter2', {
  target: function() {
    return document.querySelector('#tbl_report_custdetailreview_filter2');
  }
});

var clipboard4 = new ClipboardJS('#copy_button_custdetailreview_banda2', {
  target: function() {
    return document.querySelector('#tbl_report_custdetailreview_banda2');
  }
});

//--

</script>


<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Customer Detail Review
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-1">
      <input type='text' name='datepicker_year' value="<?php echo date("Y"); ?>" id='datepicker_year' class='required form-control' placeholder='Year'>
    </div>
    <div class="col-md-1">
      <select id="inp_type" class='required form-control'>
        <option value="2">Amount</option>
        <option value="1">Quantity</option>
      </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <!--<li class="nav-item" role="presentation">
      <a class="nav-link active" id="filterreport-tab" data-toggle="tab" href="#filterreport" role="tab" aria-controls="filterreport" aria-selected="true">Filter</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="bandareport-tab" data-toggle="tab" href="#bandareport" role="tab" aria-controls="bandareport" aria-selected="true">Banda</a>
    </li>-->

    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="filterreport2-tab" data-toggle="tab" href="#filterreport2" role="tab" aria-controls="filterreport2" aria-selected="true">Filter</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="bandareport2-tab" data-toggle="tab" href="#bandareport2" role="tab" aria-controls="bandareport2" aria-selected="true">Banda</a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">
    <!--<div class="tab-pane fade show active" id="filterreport" role="tabpanel" aria-labelledby="filterreport-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_filterreport_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="bandareport" role="tabpanel" aria-labelledby="bandareport-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_bandareport_view"></div>
      </div>
    </div>-->

    <div class="tab-pane fade show active" id="filterreport2" role="tabpanel" aria-labelledby="filterreport2-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_filterreport2_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="bandareport2" role="tabpanel" aria-labelledby="bandareport2-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_bandareport2_view"></div>
      </div>
    </div>

  </div>
</div>

<script>

$("#btn_go").click(function(){
    var year = $("#datepicker_year").val();
    var type = $("#inp_type").val();

    //filter_report(year,type);
    //banda_report(year,type);

    filter_report2(year,type);
    banda_report2(year,type);
})
//---

function filter_report(year,type){
    $("#report_filterreport_view").html("Loading Filter Report, Please wait...");
    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/custdetailreview_filter_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_filterreport_view').fadeIn("5000");
            $("#report_filterreport_view").html(respons);
        }
    });
}
//--

function banda_report(year,type){
    $("#report_bandareport_view").html("Loading Banda Report, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/custdetailreview_banda_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_bandareport_view').fadeIn("5000");
            $("#report_bandareport_view").html(respons);
        }
    });
}
//---

function filter_report2(year,type){
    $("#report_filterreport2_view").html("Loading Filter Report, Please wait...");
    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/custdetailreview_filter2_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_filterreport2_view').fadeIn("5000");
            $("#report_filterreport2_view").html(respons);
        }
    });
}
//--

function banda_report2(year,type){
    $("#report_bandareport2_view").html("Loading Banda Report, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/management/sd/custdetailreview_banda2_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_bandareport2_view').fadeIn("5000");
            $("#report_bandareport2_view").html(respons);
        }
    });
}
//---

</script>
