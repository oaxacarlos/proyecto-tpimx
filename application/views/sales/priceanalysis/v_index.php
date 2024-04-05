<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Price Analysis
</div>

<div class="container-fluid" style="margin-top:20px">
    <div class="row">
      <div class="col-md-2">
        <input type='text' name='inp_search_item' value="" id='inp_search_item' class='required form-control' placeholder='search item' onchange=f_update_cust()>
      </div>
      <div class="col-md-2">
        <input type='text' name='inp_item_code' value="" id='inp_item_code' class='required form-control' placeholder='item no' disabled>
      </div>
      <div class="col-md-3">
        <input type='text' name='inp_item_name' value="" id='inp_item_name' class='required form-control' placeholder='item name' disabled>
      </div>
      <div class="col-1">
        <button class="btn btn-primary" id="btn_go">GO</button>
      </div>
    </div>
    <div class="row" style="margin-top:20px;">
        <div class="col-2" id="report_table_detail">Tables detail</div>
        <div class="col-10">
            <div class="row" id="report_item_year">Chart Year</div>
            <div class="row" id="report_chart_year">Year chart</div>
            <div class="container" >
              <div class="col-4" id="report_cross_reference"></div>
            </div>
        </div>
    </div>
</div>

<?php

$option="";
unset($autocomplete);
$i=0;
foreach($var_item as $row){
    $value = $row['item_no']." | ".$row['name'];
    $autocomplete[$i] = $value;
    $i++;
}

$js_array_autocomplete = json_encode($autocomplete);

?>


<script>

var option = "<?php echo $option; ?>";
var counter=0;
var autocomplete = <?php echo $js_array_autocomplete; ?>;

$( function() {
  $( "#inp_search_item").autocomplete({
    source: autocomplete
  });
})
//---


$( function() {
  $( "#inp_search_item").autocomplete({
    source: autocomplete
  });
})
//---

function f_update_cust(){
    var cust = $("#inp_search_item").val();
    cust = cust.split(" | ");

    $("#inp_item_code").val(cust[0]);
    $("#inp_item_name").val(cust[1]);

    $("#inp_search_item").val("");
}
//---


$("#btn_go").click(function(){
    var item_code = $("#inp_item_code").val();
    var item_name = $("#inp_item_name").val();

    if(item_code=="" || item_name==""){
        show_error("Item Data not completed");
        return false;
    }

    gen_report_price_details(item_code);
    gen_report_item_year(item_code);
    gen_report_chart_year(item_code);
    gen_report_cross_reference(item_code);
})
//---

function gen_report_price_details(item_code){
    $("#report_table_detail").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/priceanalysis/detail",
        type      : 'post',
        dataType  : 'html',
        data      :  {item_code:item_code},
        success   :  function(respons){
            $('#report_table_detail').fadeIn("5000");
            $("#report_table_detail").html(respons);
        }
    });
}
//---

function gen_report_item_year(item_code){
    $("#report_item_year").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/priceanalysis/item_year",
        type      : 'post',
        dataType  : 'html',
        data      :  {item_code:item_code},
        success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_item_year_chart(responsedata);
        }
    });
}
//---

function gen_report_item_year_chart(data){
      Highcharts.chart('report_item_year', {
      chart: {
          type: 'column'
      },
      title: {
          text: '',
          align: 'left'
      },
      xAxis: {
          categories: data.categories
      },
      yAxis: {
          min: 0,
          title: {
              text: ''
          },
          stackLabels: {
              enabled: false,
              style: {
                  fontWeight: 'bold',
                  color: ( // theme
                      Highcharts.defaultOptions.title.style &&
                      Highcharts.defaultOptions.title.style.color
                  ) || 'gray',
                  textOutline: 'none'
              }
          }
      },
      legend: {
          align: 'right',
          x: 0,
          verticalAlign: 'top',
          y: 5,
          floating: true,
          backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || 'white',
          borderColor: '#CCC',
          borderWidth: 1,
          shadow: false
      },
      tooltip: {
          headerFormat: '<b>{point.x}</b><br/>',
          pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
      },
      plotOptions: {
          column: {
              stacking: 'normal',
              dataLabels: {
                  enabled: false
              }
          }
      },
      series: data.data
    });
}
//--

function gen_report_chart_year(item_code){
    $("#report_chart_year").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/priceanalysis/chart_year",
        type      : 'post',
        dataType  : 'html',
        data      :  {item_code:item_code},
        success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_chart_year_chart(responsedata);
        }
    });
}
//---

function gen_report_chart_year_chart(data){
  Highcharts.chart('report_chart_year', {
  chart: {
      type: 'column'
  },
  title: {
      align: 'left',
      text: 'Yearly Sales'
  },
  subtitle: {
      align: 'left',
      text: ''
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
          text: 'Total Qty'
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
      pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
  },

  series: [
      {
          name: "Price",
          colorByPoint: true,
          data: data.series
      }
  ],
  drilldown: {
      breadcrumbs: {
          position: {
              align: 'right'
          }
      },
      series: data.drilldown
  }
});
}
//---

function gen_report_cross_reference(item_code){
    $("#report_cross_reference").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/priceanalysis/cross_reference",
        type      : 'post',
        dataType  : 'html',
        data      :  {item_code:item_code},
        success   :  function(respons){
            $('#report_cross_reference').fadeIn("5000");
            $("#report_cross_reference").html(respons);
        }
    });
}
//---

</script>
