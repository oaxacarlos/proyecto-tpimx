<script>
$(document).ready(function() {

    $("#dsh_maps_from").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $("#dsh_maps_to").datetimepicker({
       timepicker: false,
       format : 'Y-m-d'
    });

    $.datetimepicker.setLocale('en');
});
</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Dashboard Operacion
</div>

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="dsh_maps-tab" data-toggle="tab" href="#dsh_maps" role="tab" aria-controls="dsh_maps" aria-selected="true">Maps</a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="dsh_maps" role="tabpanel" aria-labelledby="dsh_maps-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <button class="btn btn-outline-primary btn-sm" onclick=get_this_year('dsh_maps_from','dsh_maps_to')>This Year</button>
              <button class="btn btn-outline-primary btn-sm" onclick=get_last_month('dsh_maps_from','dsh_maps_to')>Last month</button>
              <button class="btn btn-outline-primary btn-sm" onclick=get_this_month('dsh_maps_from','dsh_maps_to')>This month</button>
              <button class="btn btn-outline-primary btn-sm" onclick=get_last_7days('dsh_maps_from','dsh_maps_to')>Last 7 days</button>
              <button class="btn btn-outline-primary btn-sm" onclick=get_today('dsh_maps_from','dsh_maps_to')>Today</button>
            </div>
          </div>
        </div>
        <div class="row" style="margin-top:10px;">
          <div class="col-2">
            <span class="badge badge-primary">From</span>
            <input type="text" id="dsh_maps_from" name="dsh_maps_from" value="<?php echo date("Y-m-01"); ?>"  class="form-control">
          </div>
          <div class="col-2">
            <span class="badge badge-primary">To</span>
            <input type="text" id="dsh_maps_to" name="dsh_maps_to" value="<?php echo date("Y-m-d"); ?>"  class="form-control">
          </div>
          <div class="col-1">
            <span class="badge badge-primary">By</span>
              <select id="dsh_maps_by" class="form-control">
                <option value="qty">QTY</option>
                <option value="value">Value</option>
              </select>
          </div>
          <div class="col-1">
            <button class="btn btn-primary" id="btn_go_maps" style="margin-top:18px;">GO</button>
          </div>
        </div>

        <div class="row" id="dsh_maps_report" style="margin-top:10px;"></div>

      </div>
    </div>
  </div>
</div>

<script>



$("#btn_go_maps").click(function(){
    var from = $("#dsh_maps_from").val();
    var to = $("#dsh_maps_to").val();
    var by = $("#dsh_maps_by").val();

    check_from_to(from,to);

    gen_dsh_maps_report(from, to, by);
})
//---

function gen_dsh_maps_report(from,to,by){
  $("#dsh_maps_report").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/operacion/report/dsh_maps_report_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {from:from, to:to, by:by},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          console.log(responsedata);
          gen_dsh_maps_report_chart(responsedata, by.toUpperCase());
      }
  });
}
//----

function gen_dsh_maps_report_chart(data2, by){

    (async () => {

      const topology = await fetch(
          'https://code.highcharts.com/mapdata/countries/mx/mx-all.topo.json'
      ).then(response => response.json());

      // Prepare demo data. The data is joined to map using value of 'hc-key'
      // property by default. See API docs for 'joinBy' for more info on linking
      // data and map.
      const data = data2;

      // Create the chart
      Highcharts.mapChart('dsh_maps_report', {
          chart: {
              map: topology
          },

          title: {
            style: {
              fontSize: '10px'
            },
              text: 'Maps Delivery by '+by
          },

          mapNavigation: {
              enabled: true,
              buttonOptions: {
                  verticalAlign: 'bottom'
              }
          },

          colorAxis: {
              min: 0
          },

          series: [{
              data: data,
              name: 'Delivery by '+by,
              states: {
                  hover: {
                      color: '#BADA55'
                  }
              },
              dataLabels: {
                  enabled: true,
                  format: '{point.name}'
              }
          }]
      });

  })();
}

</script>
