
<script>
$(document).ready(function() {

    $("#datepicker_from_kpi").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#datepicker_to_kpi").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      KPI
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
    <button class="btn btn-outline-primary btn-sm" id="btn_last_month">Last month</button>
    <button class="btn btn-outline-primary btn-sm" id="btn_this_month">This month</button>
    <button class="btn btn-outline-primary btn-sm" id="btn_last_7days">Last 7 days</button>
    <button class="btn btn-outline-primary btn-sm" id="btn_today">Today</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_from_kpi' class='required form-control' placeholder='Period From'>
    </div>
    <div class="col-md-2">
      <input type='text' name='datepicker_check' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to_kpi' class='required form-control' placeholder='Period To'>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>

<div class="row" style="margin-top:10px;">
  <div class="col-4"></div>
  <div class="col-4" style="font-size:20px;"><b>KPI WAREHOUSE DASHBOARD</b><br><span id="title_text" style="font-size:12px;"></span></div>
  <div class="col-4"></div>
</div>

<div class="container-fluid" style="margin-top:10px;">

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="put-tab" data-toggle="tab" href="#put" role="tab" aria-controls="put" aria-selected="true">PutAway</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="pick-tab" data-toggle="tab" href="#pick" role="tab" aria-controls="pick" aria-selected="false">Picking</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="puttime-tab" data-toggle="tab" href="#puttime" role="tab" aria-controls="puttime" aria-selected="false">PutAway-Time</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="picktime-tab" data-toggle="tab" href="#picktime" role="tab" aria-controls="picktime" aria-selected="false">Pick-Time</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="qc-tab" data-toggle="tab" href="#qctime" role="tab" aria-controls="qctime" aria-selected="false">QC</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="put" role="tabpanel" aria-labelledby="put-tab">
      <div class="row" id="report_view_put" style="margin-top:20px;">
          <div class="col-4">
            <div id="report_doc_released_put"></div>
          </div>
          <div class="col-8">
              <div class="row" id="report_chart_qty_put"></div>
              <div class="row" id="report_chart_line_put"></div>
          </div>
      </div>
    </div>
    <div class="tab-pane fade" id="pick" role="tabpanel" aria-labelledby="pick-tab">
      <div class="row" id="report_view_pick" style="margin-top:20px;">
          <div class="col-4">
            <div id="report_doc_released"></div>
          </div>
          <div class="col-8">
              <div class="row" id="report_chart_qty"></div>
              <div class="row" id="report_chart_line"></div>
              <div class="row" id="report_chart_doc_no"></div> <!-- 2023-04-11 -->
              <div class="row" id="report_chart_consume_time"></div> <!-- 2023-05-22 -->
          </div>
      </div>
    </div>
    <div class="tab-pane fade" id="puttime" role="tabpanel" aria-labelledby="puttime-tab">
      <div id="report_view_putaway_time" style="margin-top:20px;"></div>
    </div>
    <div class="tab-pane fade" id="picktime" role="tabpanel" aria-labelledby="picktime-tab">
      <div id="report_view_pick_time" style="margin-top:20px;"></div>
    </div>
    <div class="tab-pane fade" id="qctime" role="tabpanel" aria-labelledby="qctime-tab">
        <div class="row" id="report_chart_qc_doc"></div>
        <div class="row" id="report_chart_qc_qty"></div>
    </div>
  </div>

</div>

<div class="container-fluid" style="margin-top:30px;">
  <?php echo load_progress("progress"); ?>

  <div class="row border-bottom" style="margin-top:10px; margin-bottom:10px;"></div>


</div>

<script>


$("#btn_today").click(function(){
    get_today("datepicker_from_kpi","datepicker_to_kpi");
    $("#btn_go").click();
    title_text();
})
//---

$("#btn_this_month").click(function(){
    get_this_month("datepicker_from_kpi","datepicker_to_kpi");
    $("#btn_go").click();
    title_text();
})
//---

$("#btn_last_month").click(function(){
    get_last_month("datepicker_from_kpi","datepicker_to_kpi");
    $("#btn_go").click();
    title_text();
})
//---

$("#btn_last_7days").click(function(){
    get_last_7days("datepicker_from_kpi","datepicker_to_kpi");
    $("#btn_go").click();
    title_text();
})
//---

$("#btn_go").click(function(){
    var date_from = $("#datepicker_from_kpi").val();
    var date_to = $("#datepicker_to_kpi").val();

    if(check_from_to(date_from,date_to)){
        // Put Away
        generate_report_doc_released_put(date_from, date_to);
        generate_report_chart_qty_put(date_from, date_to);
        generate_report_chart_line_put(date_from, date_to);
        //--

        // Picking
        generate_report_doc_released(date_from, date_to);
        generate_report_chart_qty(date_from, date_to);
        generate_report_chart_line(date_from, date_to);
        generate_report_chart_doc_no(date_from, date_to); //2023-04-11
        generate_report_chart_consume_time(date_from, date_to); //2023-05-22
        //--

        generate_report_putaway_time(date_from, date_to);
        generate_report_pick_time(date_from, date_to);

        // QC
        generate_report_chart_qc_doc(date_from, date_to);
        generate_report_chart_qc_qty(date_from, date_to);
    }
})
//---

function title_text(){
    var date_from = $("#datepicker_from_kpi").val();
    var date_to = $("#datepicker_to_kpi").val();
    $("#title_text").text("Period : "+date_from+" until "+date_to);
}
//---

function generate_report_doc_released(date_from, date_to){
    $("#report_doc_released").hide();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_doc_released",
        type      : 'post',
        dataType  : 'html',
        data      :  {date_from:date_from, date_to:date_to},
        success   :  function(respons){
            $('#progress').hide();
            $('#report_doc_released').fadeIn("1000");
            $("#report_doc_released").html(respons);
            //$("#btn_pdf_doc_released").show();
        }
    });
}
//---

function generate_report_chart_qty(date_from, date_to){
  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_picked_qty",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_qty","Picker Qty", responsedata.data, responsedata.data_d, 'QTY (PZA)');
      }
  });
}
//---

function generate_report_chart_line(date_from, date_to){
  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_picked_line",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_line","Picker Line", responsedata.data, responsedata.data_d, 'LINE');
      }
  });
}
//---

function generate_report_doc_released_put(date_from, date_to){
    $("#report_doc_released_put").hide();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_doc_released_put",
        type      : 'post',
        dataType  : 'html',
        data      :  {date_from:date_from, date_to:date_to},
        success   :  function(respons){
            $('#progress').hide();
            $('#report_doc_released_put').fadeIn("1000");
            $("#report_doc_released_put").html(respons);
            //$("#btn_pdf_doc_released_put").show();
        }
    });
}
//---

function generate_report_chart_qty_put(date_from, date_to){
  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_put_qty",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_qty_put","Putter Qty", responsedata.data, responsedata.data_d, 'QTY (PZA)');
      }
  });
}
//---

function generate_report_chart_line_put(date_from, date_to){
  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_put_line",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_line_put","Picker Line", responsedata.data, responsedata.data_d, 'LINE');
      }
  });
}
//---

function generate_report_putaway_time(date_from, date_to){
    $("#report_view_putaway_time").hide();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_putaway_time",
        type      : 'post',
        dataType  : 'html',
        data      :  {date_from:date_from, date_to:date_to},
        success   :  function(respons){
            $('#progress').hide();
            $('#report_view_putaway_time').fadeIn("1000");
            $("#report_view_putaway_time").html(respons);
        }
    });
}
//----

function generate_report_pick_time(date_from, date_to){
    $('#report_view_pick_time').hide();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_pick_time",
        type      : 'post',
        dataType  : 'html',
        data      :  {date_from:date_from, date_to:date_to},
        success   :  function(respons){
            $('#progress').hide();
            $('#report_view_pick_time').fadeIn("1000");
            $("#report_view_pick_time").html(respons);
        }
    });
}
//----

function highchart(location, title_text, data, data_d, yAxis_text){

  // Create the chart
  Highcharts.chart(location, {
      chart: {
          type: 'column'
      },
      title: {
          align: 'left',
          text: title_text
      },
      subtitle: {
          align: 'left',
          text: 'Click the columns to view the detail.'
      },
      accessibility: {
          announceNewData: {
              enabled: true
          }
      },
      xAxis: {
          type: 'category'
      },
      yAxis: {
          title: {
              text: yAxis_text
          }

      },
      legend: {
          enabled: false
      },
      plotOptions: {
          series: {
              borderWidth: 0,
              dataLabels: {
                  enabled: true,
                  format: '{point.y:.0f}'
              }
          }
      },

      tooltip: {
          headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
      },

      series: [
          {
              name: "Picker",
              colorByPoint: true,
              data: data
          }
      ],
      drilldown: {
          breadcrumbs: {
              position: {
                  align: 'right'
              }
          },
          series: data_d
      }
    });
}
//---

$("#btn_pdf_doc_released").click(function(){
  var HTML_Width = $("#report_doc_released").width();
  var HTML_Height = $("#report_doc_released").height();
  var top_left_margin = 15;
  var PDF_Width = HTML_Width+(top_left_margin*2);
  var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
  var canvas_image_width = HTML_Width;
  var canvas_image_height = HTML_Height;

  var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;

  html2canvas($("#report_doc_released")[0],{allowTaint:true}).then(function(canvas) {
    canvas.getContext('2d');

    //console.log(canvas.height+"  "+canvas.width);

    var imgData = canvas.toDataURL("image/png", 3.0);
    var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
      pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);


    for (var i = 1; i <= totalPDFPages; i++) {
      pdf.addPage(PDF_Width, PDF_Height);
      pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
    }

      pdf.save("HTML-Document.pdf");
    });
})
//--

// 2023-04-11
function generate_report_chart_doc_no(date_from, date_to){
  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_picked_doc_no",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_doc_no","Doc No", responsedata.data, responsedata.data_d, 'DOC NO');
      }
  });
}
//---

// 2023-05-22
function generate_report_chart_consume_time(date_from, date_to){
  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_picked_consume_time",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_consume_time","Consume Time (on Minutes)", responsedata.data, responsedata.data_d, 'CONSUME TIME');
      }
  });
}
//---

// 2023-05-22
function generate_report_chart_qc_doc(date_from, date_to){
  $("#report_chart_qc_doc").html("Loading, Please Wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_qc_doc_no",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_qc_doc","Doc No", responsedata.data, responsedata.data_d, 'DOC NO');
      }
  });
}
//---

// 2023-05-22
function generate_report_chart_qc_qty(date_from, date_to){
  $("#report_chart_qc_qty").html("Loading, Please Wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/wms/report/kpi/gen_report_user_qc_qty",
      type      : 'post',
      dataType  : 'html',
      data      :  {date_from:date_from, date_to:date_to},
      success   :  function(data){
          var responsedata = $.parseJSON(data);
          highchart("report_chart_qc_qty","Qty", responsedata.data, responsedata.data_d, 'QTY');
      }
  });
}
//---

</script>
