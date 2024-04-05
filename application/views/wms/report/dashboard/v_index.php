<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Dashboard
</div>

<div class="container-fluid">
  <div class="row">
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-info" style="color:white; font-size:14px;">WHS Rcpt NAV (Doc)<button class="btn btn-danger btn-sm" onclick=f_refresh_whs_rcpt() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_whs_receipt_no_proceed"></div>
              <div id="dsh_whs_receipt_no_proceed" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-info" style="color:white; font-size:14px;">Recvd OutStand (Qty)<button class="btn btn-danger btn-sm" onclick=f_refresh_outstand_received() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_outstand_received"></div>
              <div id="dsh_outstand_received" style="font-size:20px;">PZA</div>
            </div>
          </div>
      </div>
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-info" style="color:white; font-size:14px;">Received Finished (Qty)<button class="btn btn-danger btn-sm" onclick=f_refresh_received() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_received"></div>
              <div id="dsh_received" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-info" style="color:white; font-size:14px;">PutAwy OutStand (Qty)<button class="btn btn-danger btn-sm" onclick=f_refresh_outstand_putaway() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_outstand_putaway"></div>
              <div id="dsh_outstand_putaway" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-success" style="color:white; font-size:14px;">WhShip NAV (Doc)<button class="btn btn-danger btn-sm" onclick=f_refresh_whs_ship() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_whs_ship_no_proceed"></div>
              <div id="dsh_whs_ship_no_proceed" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-success" style="color:white; font-size:14px;">WhShip OutStd (Doc)<button class="btn btn-danger btn-sm" onclick=f_refresh_outstand_whship_doc() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_whs_ship_outstand_doc"></div>
              <div id="dsh_whs_ship_outstand_doc" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-success" style="color:white; font-size:14px;">Picked OutStand (Doc)<button class="btn btn-danger btn-sm" onclick=f_refresh_outstand_picked_doc() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_picked_outstand_doc"></div>
              <div id="dsh_picked_outstand_doc" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-success" style="color:white; font-size:14px;">QC OutStand (Doc)<button class="btn btn-danger btn-sm" onclick=f_refresh_outstand_qc_doc() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_qc_outstand_doc"></div>
              <div id="dsh_qc_outstand_doc" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
      <div class="col-2">
          <div class="card">
            <div class="card-header bg-success" style="color:white; font-size:14px;">Packed OutStand (Doc)<button class="btn btn-danger btn-sm" onclick=f_refresh_outstand_packed_doc() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button></div>
            <div class="card-body">
              <div class="spinner-border text-danger" id="load_dsh_packed_outstand_doc"></div>
              <div id="dsh_packed_outstand_doc" style="font-size:20px;"></div>
            </div>
          </div>
      </div>
  </div>
</div>

<div class="row border-bottom" style="margin-top:10px;"></div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-6">
      <div id="load_dsh_today" class="spinner-border text-danger"></div>
      <div id="dsh_today" class="col-md-3">
    </div>
    </div>
  </div>
</div>

<!-- detail in-finished 2023-06-07 -->
<div class="container-fluid" style="margin-top:10px;">
  <button class="btn btn-outline-primary btn-sm" id="btn_last_month">Last month</button>
  <button class="btn btn-outline-primary btn-sm" id="btn_this_month">This month</button>

      <div id="dsh_in_finished_detail"></div>

</div>
<!-- end of detail in-finished -->


<div class="row border-bottom" style="margin-top:10px;"></div>
<!--
<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-3">
      <div id="load_dsh_outstanding_amount" class="spinner-border text-danger"></div>
      <div id="dsh_outstanding_amount" class="col-md-3">
    </div>
    </div>
  </div>
</div>

<div class="row border-bottom" style="margin-top:10px;"></div>
-->

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-12">

      <input type='text' value="<?php echo date("Y-m-01"); ?>" id='datepicker_from1' style="display:none;">
      <input type='text' value="<?php echo date("Y-m-d"); ?>" id='datepicker_to1' style="display:none;">
      <input type='text' value="10" id='inp_top' style="display:none;">
    </div>
    <div class="col-md-6"><div id="dsh_top10_inbound"></div></div>
    <div class="col-md-6"><div id="dsh_top10_outbound"></div></div>
  </div>
  </div>
</div>



<script>

// start
//f_refresh_wms_amount_outstanding();
f_refresh_outstand_received();
f_refresh_received();
f_refresh_outstand_putaway();
//f_refresh_outstand_whship();
//f_refresh_outstand_picked();
//f_refresh_outstand_qc();
//f_refresh_outstand_packed();

f_refresh_outstand_whship_doc();
f_refresh_outstand_picked_doc();
f_refresh_outstand_qc_doc();
f_refresh_outstand_packed_doc();

f_refresh_today();

f_refresh_top10_inbound();
f_refresh_top10_outbound();

setTimeout(function() { f_refresh_whs_rcpt() }, 2000);
setTimeout(function() { f_refresh_whs_ship() }, 2000);

f_refresh_in_finished_detail(); // 2023-06-07

//--

function f_refresh_whs_rcpt(){

    $('#load_dsh_whs_receipt_no_proceed').show();
    $('#dsh_whs_receipt_no_proceed').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/whsreceipt_not_proceed",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_whs_receipt_no_proceed').hide();
            $('#dsh_whs_receipt_no_proceed').text(responsedata);
            $('#dsh_whs_receipt_no_proceed').show();
        }
    })
}
//---

function f_refresh_whs_ship(){

    $('#load_dsh_whs_ship_no_proceed').show();
    $('#dsh_whs_ship_no_proceed').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/whship_not_proceed",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_whs_ship_no_proceed').hide();
            $('#dsh_whs_ship_no_proceed').text(responsedata);
            $('#dsh_whs_ship_no_proceed').show();
        }
    })
}
//---

function f_refresh_outstand_received(){
    $('#load_dsh_outstand_received').show();
    $('#dsh_outstand_received').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_received",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_outstand_received').hide();
            $('#dsh_outstand_received').text(responsedata);
            $('#dsh_outstand_received').show();
        }
    })
}
//---

function f_refresh_received(){
    $('#load_dsh_received').show();
    $('#dsh_received').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/received",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_received').hide();
            $('#dsh_received').text(responsedata);
            $('#dsh_received').show();
        }
    })
}
//---

function f_refresh_outstand_putaway(){
    $('#load_dsh_outstand_putaway').show();
    $('#dsh_outstand_putaway').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_putaway",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_outstand_putaway').hide();
            $('#dsh_outstand_putaway').text(responsedata);
            $('#dsh_outstand_putaway').show();
        }
    })
}
//---

function f_refresh_outstand_whship(){
    $('#load_dsh_whs_ship_outstand').show();
    $('#dsh_whs_ship_outstand').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_whship",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_whs_ship_outstand').hide();
            $('#dsh_whs_ship_outstand').text(responsedata);
            $('#dsh_whs_ship_outstand').show();
        }
    })
}
//---

function f_refresh_outstand_picked(){
    $('#load_dsh_picked_outstand').show();
    $('#dsh_picked_outstand').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_picked",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_picked_outstand').hide();
            $('#dsh_picked_outstand').text(responsedata);
            $('#dsh_picked_outstand').show();
        }
    })
}
//---

function f_refresh_outstand_qc(){
    $('#load_dsh_qc_outstand').show();
    $('#dsh_qc_outstand').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_qc",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_qc_outstand').hide();
            $('#dsh_qc_outstand').text(responsedata);
            $('#dsh_qc_outstand').show();
        }
    })
}
//---

function f_refresh_outstand_packed(){
    $('#load_dsh_packed_outstand').show();
    $('#dsh_packed_outstand').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_packed",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_packed_outstand').hide();
            $('#dsh_packed_outstand').text(responsedata);
            $('#dsh_packed_outstand').show();
        }
    })
}
//---

function f_refresh_outstand_whship_doc(){
    $('#load_dsh_whs_ship_outstand_doc').show();
    $('#dsh_whs_ship_outstand_doc').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_whship_doc",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_whs_ship_outstand_doc').hide();
            $('#dsh_whs_ship_outstand_doc').text(responsedata);
            $('#dsh_whs_ship_outstand_doc').show();
        }
    })
}
//---

function f_refresh_outstand_picked_doc(){
    $('#load_dsh_picked_outstand_doc').show();
    $('#dsh_picked_outstand_doc').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_picked_doc",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_picked_outstand_doc').hide();
            $('#dsh_picked_outstand_doc').text(responsedata);
            $('#dsh_picked_outstand_doc').show();
        }
    })
}
//---

function f_refresh_outstand_qc_doc(){
    $('#load_dsh_qc_outstand_doc').show();
    $('#dsh_qc_outstand_doc').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_qc_doc",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_qc_outstand_doc').hide();
            $('#dsh_qc_outstand_doc').text(responsedata);
            $('#dsh_qc_outstand_doc').show();
        }
    })
}
//---

function f_refresh_outstand_packed_doc(){
    $('#load_dsh_packed_outstand_doc').show();
    $('#dsh_packed_outstand_doc').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_packed_doc",
        type : "post",
        dataType  : 'html',
        success: function(data){
            var responsedata = $.parseJSON(data);
            $('#load_dsh_packed_outstand_doc').hide();
            $('#dsh_packed_outstand_doc').text(responsedata);
            $('#dsh_packed_outstand_doc').show();
        }
    })
}
//---

function f_refresh_top10_inbound(){

    var date_from = $("#datepicker_from1").val();
    var date_to = $("#datepicker_to1").val();
    var top = $("#inp_top").val();

    $('#dsh_top10_inbound').empty();
    $('#dsh_top10_inbound').html("Loading...");

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/get_top10_inbound",
        type : "post",
        dataType  : 'html',
        data : { date_from:date_from, date_to:date_to, top:top },
        success: function(data){
            var responsedata = $.parseJSON(data);
            if(responsedata.status == 0){
                $('#dsh_top10_inbound').html("No Data Available");
            }
            else{
                highchart_top10('dsh_top10_inbound',responsedata.data,'Top 10 Inbound',$("#datepicker_from1").val(), $("#datepicker_to1").val());
            }


        }
    })
}
//--

function f_refresh_top10_outbound(){

    var date_from = $("#datepicker_from1").val();
    var date_to = $("#datepicker_to1").val();
    var top = $("#inp_top").val();

    $('#dsh_top10_outbound').empty();
    $('#dsh_top10_outbound').html("Loading...");

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/get_top10_outbound",
        type : "post",
        dataType  : 'html',
        data : { date_from:date_from, date_to:date_to, top:top },
        success: function(data){
            var responsedata = $.parseJSON(data);
            if(responsedata.status == 0){
                $('#dsh_top10_outbound').html("No Data Available");
            }
            else{
                highchart_top10('dsh_top10_outbound',responsedata.data,'Top 10 Outbound',$("#datepicker_from1").val(), $("#datepicker_to1").val());
            }


        }
    })
}
//--

$("#btn_this_month").click(function(){
    get_this_month("datepicker_from1","datepicker_to1");
    f_refresh_top10_inbound();
    f_refresh_top10_outbound();
    f_refresh_in_finished_detail()
})
//---

$("#btn_last_month").click(function(){
    get_last_month("datepicker_from1","datepicker_to1");
    f_refresh_top10_inbound();
    f_refresh_top10_outbound();
    f_refresh_in_finished_detail()
})
//---

function highchart_top10(location, data,text,subtitle1, subtitle2){
      Highcharts.chart(location, {
      chart: {
          type: 'column'
      },
      title: {
          text: text
      },
      subtitle: {
          text: subtitle1+' until '+subtitle2
      },
      xAxis: {
          type: 'category',
          labels: {
              rotation: -45,
              style: {
                  fontSize: '13px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Quantity (PZA)'
          }
      },
      legend: {
          enabled: false
      },
      tooltip: {
          pointFormat: 'QTY : <b>{point.y:.0f} PZA</b>'
      },
      series: [{
          name: 'Quantity',
          data : data,
          dataLabels: {
              enabled: true,
              rotation: -90,
              color: '#FFFFFF',
              align: 'right',
              format: '{point.y:.0f}', // one decimal
              y: 10, // 10 pixels down from the top
              style: {
                  fontSize: '13px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
      }]
    });
}
//---

function f_refresh_wms_amount_outstanding(){
    $('#load_dsh_outstanding_amount').show();
    $('#dsh_outstanding_amount').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/outstand_amount",
        type : "post",
        dataType  : 'html',
        success: function(data){
            $('#load_dsh_outstanding_amount').hide();
            $('#dsh_outstanding_amount').html(data);
            $('#dsh_outstanding_amount').show();
        }
    })
}
//---

function f_refresh_today(){

    $('#load_dsh_today').show();
    $('#dsh_today').hide();

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/shipment_today",
        type : "post",
        dataType  : 'html',
        success: function(data){
            $('#load_dsh_today').hide();
            $('#dsh_today').html(data);
            $('#dsh_today').show();
        }
    })
}
//---

// 2023-06-07
function f_refresh_in_finished_detail(){

    var date_from = $("#datepicker_from1").val();
    var date_to = $("#datepicker_to1").val();

    $("#dsh_in_finished_detail").html("Loading, Please wait...");

    $.ajax({
        url  : "<?php echo base_url();?>index.php/wms/report/dashboard/in_finished_detail",
        type : "post",
        dataType  : 'html',
        data      :  {date_from:date_from, date_to:date_to},
        success: function(data){
            var responsedata = $.parseJSON(data);
            gen_report_in_finished_detail_chart(responsedata);
        }
    })
}
//---

// 2023-06-07
function gen_report_in_finished_detail_chart(data){

    Highcharts.chart('dsh_in_finished_detail', {
      chart: {
          type: 'column'
      },
      title: {
          text: 'IN vs FINISHED'
      },
      subtitle: {
          text: ''
      },
      xAxis: {
          categories: data.categories,
          crosshair: true
      },
      yAxis: {
        stackLabels: {
           style: {
               color: '#FFFFFF',
               fontWeight: 'bold'
           },
           enabled: true,
           verticalAlign: 'top'
       }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
              '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
      },
      plotOptions: {
          column: {
              pointPadding: 0.2,
              borderWidth: 0
          }
      },
      series : data.detail
  });
}
//--


</script>
