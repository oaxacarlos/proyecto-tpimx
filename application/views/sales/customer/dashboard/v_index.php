<script>
// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_report', {
  target: function() {
    return document.querySelector('#tbl_report');
  }
});
//--

var clipboard3 = new ClipboardJS('#copy_button_item_cat_report', {
  target: function() {
    return document.querySelector('#tbl_report_item_cat');
  }
});
//--

var clipboard3 = new ClipboardJS('#copy_button_last_3years', {
  target: function() {
    return document.querySelector('#tbl_sales_3year');
  }
});

</script>


<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Customer Dashboard
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <span class="badge badge-primary">Search Customer</span>
      <input type='text' name='inp_search_cust' value="" id='inp_search_cust' class='required form-control' placeholder='search customer' onchange=f_update_cust()>
    </div>
    <div class="col-md-2">
      <span class="badge badge-primary">Customer No</span>
      <input type='text' name='inp_cust_code' value="" id='inp_cust_code' class='required form-control' placeholder='customer no' disabled>
    </div>
    <div class="col-md-3">
      <span class="badge badge-primary">Customer Name</span>
      <input type='text' name='inp_cust_name' value="" id='inp_cust_name' class='required form-control' placeholder='customer name' disabled>
    </div>
    <div class="col-md-1">
      <span class="badge badge-primary">Year</span>
      <input type='text' name='inp_year' value="<?php echo date("Y"); ?>" id='inp_year' class='required form-control' placeholder='Year'>
    </div>
    <div class="col-1">
      <span class="badge badge-primary">Month</span>
      <select id="inp_month" name="inp_month" class="form-control">
        <?php
          $selected = date("m");
          echo generate_month($selected);
        ?>
      </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go" style="margin-top:18px;">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" id="report_3month_sales_view" style="margin-top:20px;"></div>
<div class="border"></div>
<div class="container-fluid">
    <div class="container-fluid" id="report_3year_sales_view"></div>
    <div class="container-fluid" id="report_3year_sales_detail_view" style="margin-top:20px;"></div>
</div>
<div class="border"></div>
<div class="container-fluid" id="report_fill_rate_view" style="margin-top:20px;"></div>
<div class="container-fluid" id="report_sales_item_cat_view" style="margin-top:20px;"></div>
<div class="container-fluid" id="report_sales_item_view" style="margin-top:20px;"></div>



<?php

$option="";
unset($autocomplete);
$i=0;
foreach($var_customer_data as $row){
    $value = $row['cust_no']." | ".$row['name'];
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
  $( "#inp_search_cust").autocomplete({
    source: autocomplete
  });
})
//---

function f_update_cust(){
    var cust = $("#inp_search_cust").val();
    cust = cust.split(" | ");

    $("#inp_cust_code").val(cust[0]);
    $("#inp_cust_name").val(cust[1]);

    $("#inp_search_cust").val("");
}
//---

$("#btn_go").click(function(){
    var year = $("#inp_year").val();
    var month = $("#inp_month").val();
    var custno = $("#inp_cust_code").val();
    var cust_code = $("#inp_cust_code").val();
    var cust_name = $("#inp_cust_name").val();

    if(cust_code=="" || cust_name==""){
        show_error("Customer Data not completed");
        return false;
    }

    if(year == ""){
        show_error("Year could not blank");
        return false
    }

    gen_report_customer_daily_trend_last_3months(year, month, custno);
    gen_report_customer_daily_trend_last_3years(year, custno);
    gen_report_customer_fill_rate(year, custno, '1');
    gen_report_customer_sales_item_cat(cust_code, cust_name, year, '1');
    gen_report_customer_sales_item2(cust_code, cust_name, year, '1');

})
//---

function gen_report_customer_daily_trend_last_3months(year, month, customer){
  $("#report_3month_sales_view").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/customer/report_3months_sales",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, customer:customer},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_customer_daily_trend_last_3months_chart(responsedata);
      }
  });
}
//----

function gen_report_customer_daily_trend_last_3months_chart(data){
      Highcharts.chart('report_3month_sales_view', {

      title: {
        style: {
          fontSize: '10px'
        },
          text: '3 Months Sales Trend ($K)'
      },

      yAxis: {
          title: {
              text: ''
          }
      },

      xAxis: {
          accessibility: {
              rangeDescription: 'Range: '+data.last_2month_text+' to '+data.this_month_text
          },
      },

      legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'middle'
      },

      plotOptions: {
          series: {
              label: {
                  connectorAllowed: false,
              },
              pointStart: 1
          }
      },

      series: [{
          name: data.this_month_text,
          data: data.this_month
      }, {
          name: data.last_month_text,
          data: data.last_month
      }, {
          name: data.last_2month_text,
          data: data.last_2month
      }],

      responsive: {
          rules: [{
              condition: {
                  maxWidth: 500
              },
              chartOptions: {
                  legend: {
                      layout: 'horizontal',
                      align: 'center',
                      verticalAlign: 'bottom'
                  }
              }
          }]
      }

    });
}
//---

function gen_report_customer_sales_item(cust_code, cust_name, year, type){
    $("#report_sales_item_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_salesreport_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_code:cust_code,cust_name:cust_name, year:year, type:type},
        success   :  function(respons){
          $('#report_sales_item_view').fadeIn("5000");
          $("#report_sales_item_view").html(respons);
        }
    });
}
//---

function gen_report_customer_sales_item2(cust_code, cust_name, year, type){
    $("#report_sales_item_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/customer/salesman_salesreport_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_code:cust_code,cust_name:cust_name, year:year, type:type},
        success   :  function(respons){
          $('#report_sales_item_view').fadeIn("5000");
          $("#report_sales_item_view").html(respons);
        }
    });
}
//---

function gen_report_customer_sales_item_cat(cust_code, cust_name, year, type){
    $("#report_sales_item_cat_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/customer/sales_item_cat",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_code:cust_code,cust_name:cust_name, year:year, type:type},
        success   :  function(respons){
          $('#report_sales_item_cat_view').fadeIn("5000");
          $("#report_sales_item_cat_view").html(respons);
        }
    });
}
//---

function gen_report_customer_daily_trend_last_3years(year, customer){
  $("#report_3year_sales_view").html("Loading, Please wait...");
  $("#report_3year_sales_detail_view").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/customer/report_3years_sales",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, customer:customer},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_customer_daily_trend_last_3years_chart(responsedata);
          gen_report_customer_daily_trend_last_3years_table(responsedata);
      }
  });
}
//----

function gen_report_customer_daily_trend_last_3years_chart(data){
    Highcharts.chart('report_3year_sales_view', {
    title: {
        text: '3 Years',
        align: 'left'
    },
    xAxis: {
        categories: data.months
    },
    yAxis: {
        title: {
            text: 'Million'
        }
    },
    labels: {
        items: [{
            html: 'Total Sales',
            style: {
                left: '50px',
                top: '18px',
                color: ( // theme
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || 'black'
            }
        }]
    },
    series: [{
        type: 'column',
        name: data.last_2year_text,
        data: data.last_2year
    }, {
        type: 'column',
        name: data.last_year_text,
        data: data.last_year
    },
    {
        type: 'spline',
        name: data.this_year_text,
        data: data.this_year,
        marker: {
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'white'
        }
    }
    ]
  });
}
//----

function gen_report_customer_daily_trend_last_3years_table(data){
    var table = "";

    table = table + "<div class='containter-fluid'>";
    if(data.btn_excel == 1) table = table + "<button class='btn btn-success btn-sm' onclick=f_convert_excel_last_3years() style='margin-right:10px;'>EXCEL</button>";
    if(data.btn_copy == 1)  table = table + "<button class='btn btn-primary btn-sm' id='copy_button_last_3years'>COPY</button>";
    table = table + "</div>";

    table = table + "<table class='table table-bordered table-sm' style='font-size:12px; margin-top:20px;' id='tbl_sales_3year' onselectstart='return false'>";
      table = table + "<thead><tr>";
      table = table + "<th>Year</th>";
      for(i=0;i<data.months.length;i++){ table = table + "<th>"+data.months[i]+"</th>"; }
      table = table + "<th>TOTAL</th>";
      table = table + "<th>PROMEDIO</th>";
      table = table + "</tr></thead>";

      table = table + "<tbody>";

        //--- last 2 year
        var total = 0;
        table = table + "<tr>";
        table = table + "<td>"+data.last_2year_text+"</td>";
        for(i=0;i<data.last_2year.length;i++){
          table = table + "<td style='text-align:right;'>"+js_number_format(data.last_2year[i])+"</td>";
          total = total + data.last_2year[i];
        }

        table = table + "<td style='text-align:right;'>"+js_number_format(total)+"</td>";
        table = table + "<td style='text-align:right;'>"+js_number_format(total/12)+"</td>";
        table = table + "</tr>";
        //---

        //--- last year ---//
        var total = 0;
        table = table + "<tr>";
        table = table + "<td>"+data.last_year_text+"</td>";
        for(i=0;i<data.last_year.length;i++){
          table = table + "<td style='text-align:right;'>"+js_number_format(data.last_year[i])+"</td>";
          total = total + data.last_year[i];
        }
        table = table + "<td style='text-align:right;'>"+js_number_format(total)+"</td>";
        table = table + "<td style='text-align:right;'>"+js_number_format(total/12)+"</td>";
        table = table + "</tr>";
        //---

        //--- this year --//
        var total = 0;
        table = table + "<tr>";
        table = table + "<td>"+data.this_year_text+"</td>";
        for(i=0;i<data.this_year.length;i++){
          table = table + "<td style='text-align:right;'>"+js_number_format(data.this_year[i])+"</td>";
          total = total + data.this_year[i];
        }

        const d = new Date();
        let month = d.getMonth();
        var total_month = 1;

        for(i=0;i<11;i++){
            if(i == month) break;
            else total_month = total_month + 1;
        }

        table = table + "<td style='text-align:right;'>"+js_number_format(total)+"</td>";
        table = table + "<td style='text-align:right;'>"+js_number_format(total/total_month)+"</td>";
        table = table + "</tr>";
        //----

      table = table + "</tbody>";

    table = table + "</table>";

    $('#report_3year_sales_detail_view').fadeIn("5000");
    $("#report_3year_sales_detail_view").empty().append(table);
}
//---

function gen_report_customer_fill_rate(year, customer, type){
  $("#report_fill_rate_view").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/customer/fill_rate_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {cust_code:customer,year:year, type:type},
      success   :  function(respons){
        $('#report_fill_rate_view').fadeIn("5000");
        $("#report_fill_rate_view").html(respons);
      }
  });
}
//----


</script>

<script>
//sales report
function f_convert_excel_sales_report(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_report'),"CustSlsItemReport");

}
//--

//sales item cat report
function f_convert_excel_sales_item_cat_report(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_report_item_cat'),"CustSlsItemCatReport");

}
//--

function f_convert_excel_last_3years(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_sales_3year'),"Sales3Years");

}
//--

</script>
