<style>
td#rating{
    font-size:14px;
    vertical-align: bottom;
    width:200px;
}
td.rating_value{
    font-size: 20px;
    text-align: left;
    padding-bottom: 20px;
}

</style>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Sales Weekly
</div>

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">

    <?php if(isset($_SESSION['user_permis']["10"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="dsh_salesnational-tab" data-toggle="tab" href="#dsh_salesnational" role="tab" aria-controls="dsh_salesnational" aria-selected="true">Sales National</a>
    </li>
    <?php } ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="dsh_salesman-tab" data-toggle="tab" href="#dsh_salesman" role="tab" aria-controls="dsh_salesman" aria-selected="false">Salesman</a>
    </li>

    <?php if(isset($_SESSION['user_permis']["15"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="dsh_cs-tab" data-toggle="tab" href="#dsh_cs" role="tab" aria-controls="dsh_cs" aria-selected="false">Customer Service</a>
    </li>
    <?php } ?>
  </ul>

  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show" id="dsh_salesnational" role="tabpanel" aria-labelledby="dsh_salesnational-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div class="row">
            <div class="col-1">
              <span class="badge badge-primary">Year</span>
              <input type="text" id="dsh_salesnational_year" name="dsh_salesnational_year" value="<?php echo date("Y"); ?>"  class="form-control">
            </div>

            <div class="col-1">
              <span class="badge badge-primary">Month</span>
              <select id="dsh_salesnational_month" name="dsh_salesnational_month" class="form-control">
                <?php
                  $selected = date("m");
                  echo generate_month($selected);
                ?>
              </select>
            </div>

            <div class="col-1">
              <button class="btn btn-primary" id="btn_go_sales_national" style="margin-top:18px;">GO</button>
            </div>
          </div>

          <!-- 2023-05-30 -->
          <div class="row" style='margin-top:20px;'>
              <div class="col-12" id="report_salesnational_view_achievement"></div>
          </div>
          <!-- end -->

          <!-- 2023-10-02 -->
          <div class="row" style='margin-top:20px;'>
              <div class="col-12" id="report_salesnational_view_sales_this_year_vs_last_year"></div>
          </div>
          <!-- end -->

          <div class="row" id="report_salesnational_view" style="margin-top:10px;">
            <div class="col-6">
              <div class="row">
                <div id="report_salesnational_view_salesvsbudget_tbl" style="display:none;"></div>
                <div class="col-6 border" id="report_salesnational_view_salesvsbudget" style="height:300px;">Sales vs Budget by Salesperson ($K)</div>

                <div class="col-6 border" id="report_salesnational_view_salesbycategory" style="height:300px;">Sales by Category to date ($K)</div>
              </div>

              <!-- 2023-05-25 -->
              <div class="row">
                <div class="col-6 border">
                  Actual Net Sales MTD Filter ($K)
                  <div id="report_salesnational_view_actual_netsales_mtd_filter" style="height:275px;"></div>
                </div>
                <div class="col-6 border">
                  Actual Net Sales YTD Filter ($K)
                  <div id="report_salesnational_view_actual_netsales_ytd_filter" style="height:275px;"></div>
                </div>
              </div>
              <!-- end -->

              <div class="row border" id="report_salesnational_view_salestrendvsbudget" style="height:300px;">
                Total Sales Trend vs Budget ($K)
              </div>

              <!-- 2023-05-25 -->
              <!--<div class="row">
                <div id="report_salesnational_view_salesvsbudget_tbl" style="display:none;"></div>
                <div class="col-6 border" id="report_salesnational_view_salesvsbudget_filter" style="height:300px;">Sales vs Budget by Salesperson Filter ($K)</div>

                <div class="col-6 border" id="report_salesnational_view_salesvsbudget_belt" style="height:300px;">Sales vs Budget by Salesperson Belt ($K)</div>
              </div>-->

              <div class="row border" id="report_salesnational_view_salestrendvsbudget_filter" style="height:300px;">
                Total Sales Trend vs Budget Filter ($K)
              </div>

              <!-- end -->

              <div class="row border" id="report_salesnational_view_dailysalestrend" style="height:300px;">
                Daily Sales Trend ($K)
              </div>
              <div class="row border" id="report_salesnational_view_dailysalesorder">
                Daily Sales Order
              </div>
            </div>

            <div class="col-6">
              <div class="row">
                <div class="col-6 border">
                  Actual Net Sales MTD ALL ($K)
                  <div id="report_salesnational_view_actual_netsales_mtd" style="height:275px;"></div>
                </div>
                <div class="col-6 border">
                  Actual Net Sales YTD ALL ($K)
                  <div id="report_salesnational_view_actual_netsales_ytd" style="height:275px;"></div>
                </div>
              </div>

              <!-- 2023-05-25 -->
              <div class="row">
                <div class="col-6 border">
                  Actual Net Sales MTD Belt ($K)
                  <div id="report_salesnational_view_actual_netsales_mtd_belt" style="height:275px;"></div>
                </div>
                <div class="col-6 border">
                  Actual Net Sales YTD Belt ($K)
                  <div id="report_salesnational_view_actual_netsales_ytd_belt" style="height:275px;"></div>
                </div>
              </div>
              <!-- end -->

              <div class="row">
                <div class="col-6 border" id="report_salesnational_view_actual_netsales_sakura" style="height:300px;">
                  Actual Net Sales Sakura ($K)
                </div>
                <div class="col-6 border" id="report_salesnational_view_actual_netsales_typ">
                  Actual Net Sales Toyopower ($K)
                </div>
              </div>

              <!-- 2023-05-25 -->
              <div class="row border" id="report_salesnational_view_salestrendvsbudget_belt" style="height:300px;">
                Total Sales Trend vs Budget Belt ($K)
              </div>
              <!-- end -->

              <div class="row border" id="report_salesnational_view_actual_netsales_salesbygeography" style="height:500px;">
                Sales by Geography ($K)
              </div>
              <div class="row border">
                <div class="col-6" id="report_salesnational_view_nav_total_invc_cn_net">
                  TOTAL INVOICE - CN - NETT
                </div>
                <div class="col-6" id="report_salesnational_view_nav_value_wms"></div>
              </div>

            </div>

          </div>
      </div>
    </div>

    <div class="tab-pane fade show active" id="dsh_salesman" role="tabpanel" aria-labelledby="dsh_salesreview-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="row">
            <div class="col-1">
              <span class="badge badge-primary">Year</span>
              <input type="text" id="dsh_salesman_year" name="dsh_salesman_view_year" value="<?php echo date("Y"); ?>"  class="form-control">
            </div>
            <div class="col-1">
              <span class="badge badge-primary">Month</span>
              <select id="dsh_salesman_month" name="dsh_salesman_view_month" class="form-control">
                <?php
                  $selected = date("m");
                  echo generate_month($selected);
                ?>
              </select>
            </div>
            <div class="col-2">
              <span class="badge badge-primary">Salesman</span>
              <select id="dsh_salesman_id" name="dsh_salesman_id" class="form-control">
                <?php
                  //echo "===".count($var_salesman_data);
                  foreach($var_salesman_data as $row){
                    echo "<option value='".$row["slscode"]."'>".$row["slsname"]."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-1">
              <button class="btn btn-primary" id="btn_go_salesman" style="margin-top:18px;">GO</button>
            </div>
          </div>

          <!-- 2023-05-30 -->
          <!--<div class="row" style='margin-top:20px;'>
              <div class="col-12" id="report_salesman_view_achievement"></div>
          </div>-->
          <!-- end -->

          <div id="report_salesman_view" class="row" style="margin-top:10px; margin-bottom:10px;">

              <div id="report_salesman_rating" class="col-3 border" style="height:750px;">
                <table class="table table-border table-sm" style="margin-top:10px;">
                  <tr>
                    <td colspan='3'>Salesman Name</td>
                  </tr>
                  <tr>
                    <td id="rating_name" class="rating_value" colspan='3'></td>
                  </tr>
                  <tr>
                    <td id="rating_sales_mtd" class="rating_value" style="width:40px;"></td><td id="rating">MTD Achievement</td>
                    <td id="rating_sales_mtd_arrow" style="width:20px;"></td>
                    <td id="rating_sales_mtd_bar"></td>
                  </tr>
                  <tr>
                    <td id="rating_target_month" class="rating_value"></td><td id="rating">Target</td>
                    <td id="rating_target_month_arrow" style="width:20px;"></td>
                    <td id="rating_target_month_bar"></td>
                  </tr>
                  <tr>
                    <td id="rating_percentage_mtd" class="rating_value"></td><td id="rating">% MTD Target</td>
                    <td id="rating_percentage_mtd_arrow" style="width:20px;"></td>
                    <td id="rating_percentage_mtd_bar"></td>
                  </tr>

                  <!-- 2023-07-27 -->
                  <tr>
                    <td id="rating_mtd_achv_last_year" class="rating_value"></td><td id="rating">MTD Achv Last Year</td>
                    <td id="rating_mtd_achv_last_year_arrow" style="width:20px;"></td>
                    <td id="rating_mtd_achv_last_year_bar"></td>
                  </tr>
                  <tr>
                    <td id="rating_percentage_mtd_growth" class="rating_value"></td><td id="rating">% MTD Growth</td>
                    <td id="rating_percentage_mtd_growth_arrow" style="width:20px;"></td>
                    <td id="rating_percentage_mtd_growth_bar"></td>
                  </tr>
                  <!-- end 2023-07-27 -->

                  <!-- 2023-07-27 -->
                  <tr>
                    <td colspan='4' class='table-dark'></td>
                  </tr>
                  <!-- 2023-07-27 -->

                  <tr>
                    <td id="rating_sales_ytd" class="rating_value"></td><td id="rating">YTD Achievement</td>
                    <td id="rating_sales_ytd_arrow" style="width:20px;"></td>
                    <td id="rating_sales_ytd_bar"></td>
                  </tr>
                  <tr>
                    <td id="rating_target_year" class="rating_value"></td><td id="rating">Year Target</td>
                    <td id="rating_target_year_arrow" style="width:20px;"></td>
                    <td id="rating_target_year_bar"></td>
                  </tr>
                  <tr>
                    <td id="rating_percentage_ytd" class="rating_value"></td><td id="rating">% YTD Target</td>
                    <td id="rating_percentage_ytd_arrow" style="width:20px;"></td>
                    <td id="rating_percentage_ytd_bar"></td>
                  </tr>

                  <!-- 2023-07-27 -->
                  <tr>
                    <td id="rating_ytd_achv_last_year" class="rating_value"></td><td id="rating">YTD Achv Last Year</td>
                    <td id="rating_ytd_achv_last_year_arrow" style="width:20px;"></td>
                    <td id="rating_ytd_achv_last_year_bar"></td>
                  </tr>
                  <tr>
                    <td id="rating_percentage_ytd_growth" class="rating_value"></td><td id="rating">% YTD Growth</td>
                    <td id="rating_percentage_ytd_growth_arrow" style="width:20px;"></td>
                    <td id="rating_percentage_ytd_growth_bar"></td>
                  </tr>
                  <!-- end 2023-07-27 -->

                </table>
              </div>
              <div class="col-6 border" style="height:500px;">
                <div class="row">
                  <div id="report_salesman_by_category" class="col-5 border" style="height:250px;">Category</div>
                  <div id="report_salesman_weekly_performance" class="col-7 border" style="height:250px;">Weekly</div>
                </div>
                <div class="row border" id="report_salesman_daily_trend_3months" style="height:250px;">Daily Trend</div>
                <div class="row border" id="report_salesman_last_6months" style="height:250px;">Last 6 Months Sales</div>
              </div>
              <div id="report_salesman_top_20customers" class="col-3 border" style="height:750px;">Top 20 Customers</div>
              <div id="report_salesman_top_40items" class="col-9 border" style="height:400px;">Top 40 Items</div>
              <div id="report_salesman_customer_active" class="col-3 border" style="height:400px;">Customer active</div>
              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                    <div class="row">
                      <div class="col-6 border">
                        Actual Net Sales MTD Filter ($K)
                        <div id="report_salesman_sales_mtd_filter"></div>
                      </div>
                      <div class="col-6 border">
                        Actual Net Sales YTD Filter ($K)
                        <div id="report_salesman_sales_ytd_filter"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="row">
                      <div class="col-6 border">
                        Actual Net Sales MTD Belt ($K)
                        <div id="report_salesman_sales_mtd_belt"></div>
                      </div>
                      <div class="col-6 border">
                        Actual Net Sales YTD Belt ($K)
                        <div id="report_salesman_sales_ytd_belt"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                    <div class="row">
                      <div class="col-6 border">
                        Actual Net Sales MTD Filter Detail ($K)
                        <div id="report_salesman_sales_mtd_filter_detail" style="height:700px;"></div>
                      </div>
                      <div class="col-6 border">
                        Actual Net Sales YTD Filter Detail ($K)
                        <div id="report_salesman_sales_ytd_filter_detail" style="height:700px;"></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="row">
                      <div class="col-6 border">
                        Actual Net Sales MTD Belt Detail ($K)
                        <div id="report_salesman_sales_mtd_belt_detail" style="height:700px;"></div>
                      </div>
                      <div class="col-6 border">
                        Actual Net Sales YTD Belt Detail ($K)
                        <div id="report_salesman_sales_ytd_belt_detail" style="height:700px;"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <div class="row">
                  <div id="report_salesman_backorder" class="border">Back Order</div>
                </div>
              </div>

              <div id="report_salesman_salesorder_daily" class="col-4 border">Sales Order Daily</div>
          </div>

      </div>
    </div>

    <!-- CS -->
    <div class="tab-pane fade show" id="dsh_cs" role="tabpanel" aria-labelledby="dsh_salesreview-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="row">
            <div class="col-1">
              <span class="badge badge-primary">Year</span>
              <input type="text" id="dsh_cs_year" name="dsh_cs_view_year" value="<?php echo date("Y"); ?>"  class="form-control">
            </div>
            <div class="col-1">
              <span class="badge badge-primary">Month</span>
              <select id="dsh_cs_month" name="dsh_cs_view_month" class="form-control">
                <?php
                  $selected = date("m");
                  echo generate_month($selected);
                ?>
              </select>
            </div>
            <div class="col-2">
              <span class="badge badge-primary">CS</span>
              <select id="dsh_cs_id" name="dsh_cs_id" class="form-control">
                <?php
                  //echo "===".count($var_cs_data);
                  foreach($var_cs_data as $row){
                    echo "<option value='".$row["userid_1"]."'>".$row["name"]."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-1">
              <button class="btn btn-primary" id="btn_go_cs" style="margin-top:18px;">GO</button>
            </div>
          </div>

          <!-- 2023-05-30 -->
          <div class="row" style='margin-top:20px;'>
              <div class="col-12" id="report_cs_view_achievement"></div>
          </div>
          <!-- end -->

          <div id="report_cs_view" class="row" style="margin-top:10px; margin-bottom:10px;">

              <div id="report_cs_rating" class="col-3 border" style="height:500px;">
                <table class="table table-border table-sm" style="margin-top:10px;">
                  <tr>
                    <td id="rating_name_cs" class="rating_value"></td><td id="rating">CS Name</td>
                  </tr>
                  <tr>
                    <td id="rating_sales_mtd_cs" class="rating_value"></td><td id="rating">MTD Achievement</td>
                  </tr>
                  <tr>
                    <td id="rating_target_month_cs" class="rating_value"></td><td id="rating">Target</td>
                  </tr>
                  <tr>
                    <td id="rating_percentage_mtd_cs" class="rating_value"></td><td id="rating">% MTD Target</td>
                  </tr>
                  <tr>
                    <td id="rating_sales_ytd_cs" class="rating_value"></td><td id="rating">YTD Achievement</td>
                  </tr>
                  <tr>
                    <td id="rating_target_year_cs" class="rating_value"></td><td id="rating">Year Target</td>
                  </tr>
                  <tr>
                    <td id="rating_percentage_ytd_cs" class="rating_value"></td><td id="rating">% YTD Target</td>
                  </tr>
                </table>
              </div>
              <div class="col-6 border" style="height:500px;">
                <div class="row">
                  <div id="report_cs_by_category" class="col-5 border" style="height:250px;">Category</div>
                  <div id="report_cs_weekly_performance" class="col-7 border" style="height:250px;">Weekly</div>
                </div>
                <div class="row border" id="report_cs_daily_trend_3months" style="height:250px;">Daily Trend</div>
              </div>
              <div id="report_cs_top_20customers" class="col-3 border" style="height:500px;">Top 20 Customers</div>
              <div id="report_cs_top_40items" class="col-9 border" style="height:400px;">Top 40 Items</div>
              <div id="report_cs_customer_active" class="col-3 border" style="height:400px;">Customer active</div>
              <div id="report_cs_salesorder_daily" class="col-4 border">Sales Order Daily</div>
          </div>

      </div>
    </div>
    <!-- end of CS -->

  </div>
</div>

<div class="row" style="margin-top:30px;"></div>

<!-- 2023-06-29 -->
<div class="modal" id="myModalMapTop">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Item</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_map_top"></div>
    </div>
  </div>
</div>
<!-- end -->

<script>

$("#btn_go_sales_national").click(function(){
    var year = $("#dsh_salesnational_year").val();
    var month = $("#dsh_salesnational_month").val();

    if(year == ""){
        show_error("Year could not blank");
        return false
    }

    //2023-10-02
    gen_report_salesnational_view_sales_this_year_vs_last_year(year);

    // 2023-05-30
    /*gen_report_salesnational_view_achievement(year,month);
    //--



    gen_report_salesnational_view_salesvsbudget(year, month);
    gen_report_salesnational_view_salesbycategory(year, month);
    gen_report_salesnational_view_actual_netsales_mtd(year, month);
    gen_report_salesnational_view_actual_netsales_ytd(year,month);
    gen_report_salesnational_view_actual_netsales_sakura(year, month);
    gen_report_salesnational_view_actual_netsales_typ(year, month);
    gen_report_salesnational_view_salestrendvsbudget(year);
    gen_report_salesnational_view_dailysalestrend(year, month);*/
    //gen_report_salesnational_view_actual_netsales_salesbygeography(year, month);

    // 2023-05-25
    /*gen_report_salesnational_view_salesvsbudget_filter(year, month);
    gen_report_salesnational_view_salesvsbudget_belt(year, month);
    gen_report_salesnational_view_salestrendvsbudget_filter(year);
    gen_report_salesnational_view_salestrendvsbudget_belt(year);
    gen_report_salesnational_view_actual_netsales_mtd_filter(year, month);
    gen_report_salesnational_view_actual_netsales_ytd_filter(year,month);
    gen_report_salesnational_view_actual_netsales_mtd_belt(year, month);
    gen_report_salesnational_view_actual_netsales_ytd_belt(year,month);*/
    //--

    setTimeout(() => {
      //gen_report_salesnational_view_total_invoice_cn_nett_nav(year, month); // get from navision would be slow
      //gen_report_salesnational_view_total_value_wms(); // get from navision would be slow
      //gen_report_salesnational_view_dailysalesorder(year, month); // get from navision would be slow*/
    }, "2000");



})
//---

function gen_report_salesnational_view_salesvsbudget(year, month){
    $("#report_salesnational_view_salesvsbudget").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_salesvsbudget_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_salesvsbudget_chart(responsedata);
        }
    });
}
//---

function gen_report_salesnational_view_salesvsbudget_chart(data){
    /*var tbl = "";
    tbl = tbl + "<table id='report_salesnational_view_salesvsbudget_tbl_content'>";
    tbl = tbl + "<thead>";
      tbl = tbl + "<tr>";
      tbl = tbl + "<th></th>";
      tbl = tbl + "<th>Sales</th>";
      tbl = tbl + "<th>Target</th>";
      tbl = tbl + "</tr>";
    tbl = tbl + "</thead>";
    tbl = tbl + "<tbody>";

    for(var i=0;i<data.length;i++){
      tbl = tbl + "<tr>";
        tbl = tbl + "<th>"+data[i].sales_person_code+"</th>";
        tbl = tbl + "<td>"+data[i].sales_value+"</td>";
        tbl = tbl + "<td>"+data[i].target_value+"</td>";
      tbl = tbl + "</tr>";
    }

    tbl = tbl + "</tbody>";
    tbl = tbl + "</table>";

    $("#report_salesnational_view_salesvsbudget_tbl").empty().append(tbl);

    // gen highchart
    Highcharts.chart('report_salesnational_view_salesvsbudget', {
    data: {
        table: 'report_salesnational_view_salesvsbudget_tbl_content'
    },
    chart: {
        type: 'column'
    },
    title: {
      style: {
        fontSize: '10px'
      },
        text: 'Sales vs Budget by Sales person ($K)'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        allowDecimals: false,
        title: {
            text: ''
        }
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
                this.point.y + ' ' + this.point.name.toLowerCase();
        }
    }
  });

    //---*/

    Highcharts.chart('report_salesnational_view_salesvsbudget', {
      chart: {
          type: 'column'
      },
      title: {
          text: 'Sales vs Budget by Sales person ($K)'
      },
      subtitle: {
          text: ''
      },
      xAxis: {
          categories: data.categories,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'MXN'
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
      series: data.detail
  });
}
//--

function gen_report_salesnational_view_salesbycategory(year, month){
    $("#report_salesnational_view_salesbycategory").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_salesbycategory_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_salesbycategory_chart(responsedata);
        }
    });
}
//---

function gen_report_salesnational_view_salesbycategory_chart(data){

      Highcharts.chart('report_salesnational_view_salesbycategory', {
      chart: {
          type: 'column'
      },
      title:
      {
        style: {
          fontSize: '10px'
        },
          text: 'Sales By Category to data ($K)'
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
              text: ''
          }
      },
      legend: {
          enabled: false
      },
      tooltip: {
          pointFormat: 'Sales: <b>{point.y:.0f} K</b>'
      },
      series: [{
          name: 'Sales',
          data: data,
          dataLabels: {
              enabled: true,
              rotation: -90,
              color: '#FFFFFF',
              align: 'right',
              format: '{point.y:.0f}', // one decimal
              y: 10, // 10 pixels down from the top
              style: {
                  fontSize: '10px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
      }]
    });
}
//---

function gen_report_salesnational_view_actual_netsales_mtd(year, month){
    $("#report_salesnational_view_actual_netsales_mtd").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_mtd_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_mtd_chart(responsedata);
        }
    });
}
//---

function gen_report_salesnational_view_actual_netsales_mtd_chart(data){
    //report_salesnational_view_actual_netsales_mtd

    Highcharts.chart('report_salesnational_view_actual_netsales_mtd', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: 0,
          plotShadow: false
      },
      title: {
          text: data.sales+'%',
          align: 'center',
          verticalAlign: 'middle',
          y: 60
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              dataLabels: {
                  enabled: true,
                  distance: -50,
                  style: {
                      fontWeight: 'bold',
                      color: 'white'
                  }
              },
              startAngle: -90,
              endAngle: 90,
              center: ['50%', '75%'],
              size: '110%'
          }
      },
      series: [{
          type: 'pie',
          name: 'Sales MTD',
          innerSize: '50%',
          data: [
              ['Sales : '+data.salesvalue, data.sales],
              ['Target : '+data.targetvalue, data.target]
          ]
      }]
  });
}
//---

function gen_report_salesnational_view_actual_netsales_ytd(year,month){
    $("#report_salesnational_view_actual_netsales_ytd").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_ytd_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_ytd_chart(responsedata);
        }
    });
}
//---

function gen_report_salesnational_view_actual_netsales_ytd_chart(data){
  //report_salesnational_view_actual_netsales_mtd

    Highcharts.chart('report_salesnational_view_actual_netsales_ytd', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: 0,
          plotShadow: false
      },
      title: {
          text: data.sales+'%',
          align: 'center',
          verticalAlign: 'middle',
          y: 60
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              dataLabels: {
                  enabled: true,
                  distance: -50,
                  style: {
                      fontWeight: 'bold',
                      color: 'white'
                  }
              },
              startAngle: -90,
              endAngle: 90,
              center: ['50%', '75%'],
              size: '110%'
          }
      },
      series: [{
          type: 'pie',
          name: 'Sales MTD',
          innerSize: '50%',
          data: [
              ['Sales : '+data.salesvalue, data.sales],
              ['Target : '+data.targetvalue, data.target]
          ]
      }]
  });
}
//---

function gen_report_salesnational_view_actual_netsales_sakura(year, month){
    $("#report_salesnational_view_actual_netsales_sakura").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_sakura_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_sakura_chart(responsedata);
        }
    });
}
//---

function gen_report_salesnational_view_actual_netsales_sakura_chart(data){

      // Radialize the colors
    Highcharts.setOptions({
      colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
          return {
              radialGradient: {
                  cx: 0.5,
                  cy: 0.3,
                  r: 0.7
              },
              stops: [
                  [0, color],
                  [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
              ]
          };
      })
    });

    // Build the chart
    Highcharts.chart('report_salesnational_view_actual_netsales_sakura', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
        style: {
          fontSize: '10px'
        },
          text: 'SAKURA'
      },
      tooltip: {
        style: {
          fontSize: '12px'
        },
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>value: {point.x}'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: false,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %<br>value: {point.x}',
                  connectorColor: 'silver'
              }
          },

      },
      series: [{
          name: 'Sales',
          data: data
      }]
    });
}
//---

function gen_report_salesnational_view_actual_netsales_typ(year, month){
    $("#report_salesnational_view_actual_netsales_typ").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_typ_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_typ_chart(responsedata);
        }
    });
}
//---

function gen_report_salesnational_view_actual_netsales_typ_chart(data){

    // Build the chart
    Highcharts.chart('report_salesnational_view_actual_netsales_typ', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
          style: {
            fontSize: '10px'
          },
            text: 'TOYOPOWER'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>value: {point.x}'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: false
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: data
        }]
    });
}
//--

function gen_report_salesnational_view_salestrendvsbudget(year){
  $("#report_salesnational_view_salestrendvsbudget").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_salestrendvsbudget_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesnational_view_salestrendvsbudget_chart(responsedata);
      }
  });
}
//---

function gen_report_salesnational_view_salestrendvsbudget_chart(data){

    Highcharts.chart('report_salesnational_view_salestrendvsbudget', {
        title: {
          style: {
            fontSize: '10px'
          },
            text: 'Total Sales Trend Vs Budget ($K)'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        labels: {
            items: [{
                html: '',
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
            name: 'Target',
            data: data.target
        }, {
            type: 'spline',
            name: 'Sales',
            data: data.sales,
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }]
    });

}
//---

function gen_report_salesnational_view_dailysalestrend(year, month){
  $("#report_salesnational_view_dailysalestrend").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_dailysalestrend_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesnational_view_dailysalestrend_chart(responsedata);
      }
  });
}
//---

function gen_report_salesnational_view_dailysalestrend_chart(data){
      Highcharts.chart('report_salesnational_view_dailysalestrend', {

      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Daily Sales Trend ($K)'
      },

      yAxis: {
          title: {
              text: ''
          }
      },

      xAxis: {
          accessibility: {
              rangeDescription: 'Range: '+data.last_2year_text+' to '+data.this_year_text
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
          name: data.this_year_text,
          data: data.this_year
      }, {
          name: data.last_year_text,
          data: data.last_year
      }, {
          name: data.last_2year_text,
          data: data.last_2year
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

function gen_report_salesnational_view_actual_netsales_salesbygeography(year, month){
  $("#report_salesnational_view_actual_netsales_salesbygeography").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_salesbygeographic",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesnational_view_actual_netsales_salesbygeography_chart(responsedata, year, month);
      }
  });
}

//--
function gen_report_salesnational_view_actual_netsales_salesbygeography_chart(data2, year, month){

      (async () => {

        const topology = await fetch(
            'https://code.highcharts.com/mapdata/countries/mx/mx-all.topo.json'
        ).then(response => response.json());

        // Prepare demo data. The data is joined to map using value of 'hc-key'
        // property by default. See API docs for 'joinBy' for more info on linking
        // data and map.
        const data = data2;

        // Create the chart
        Highcharts.mapChart('report_salesnational_view_actual_netsales_salesbygeography', {
            chart: {
                map: topology
            },

            title: {
              style: {
                fontSize: '10px'
              },
                text: 'Sales by Geography ($K)'
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

            plotOptions:{
            	series:{
                	point:{
                    	events:{
                        	click: function(){
                            	get_top_item_by_name(this.name, year, month);
                            }
                        }
                    }
                }
            },

            series: [{
                data: data,
                name: 'Sales ($K)',
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
//----

// 2023-05-30
function gen_report_salesnational_view_achievement(year, month){
  $("#report_salesnational_view_achievement").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_achievement_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year,month:month},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          var html = "";

          var sess = '<?php echo $_SESSION['user_permis']["22"]; ?>';

          if(sess){
            html = html + "<button class='btn btn-success btn-sm'  id='btn_export_xlsx_salesnational_view_achievement' onclick=f_convert_excel_salesnational()>EXCEL</button>";
          }


          html = html + "<table class='table table-bordered' id='tbl_salesnational_view_achievement'>";
            html = html + "<tr>";
              html = html + "<th>Sales Nat</th>";
              html = html + "<th>MTD Achv</th>";
              html = html + "<th>Tgt</th>";
              html = html + "<th>% MTD Tgt</th>";
              html = html + "<th>YTD Achv</th>";
              html = html + "<th>Year Tgt</th>";
              html = html + "<th>% YTD Tgt</th>";
              html = html + "<th>MTD Achv Last Year</th>";
              html = html + "<th>% MTD Growth</th>";
              html = html + "<th>YTD Achiv Last Year</th>";
              html = html + "<th>% YTD Growth</th>";
            html = html + "</tr>";
            html = html + "<tr>";
              html = html + "<td>Sales Nat</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_thismonth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.targetvalue_thismonth)+"</td>";
              html = html + "<td>"+responsedata.sales_thismonth+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.targetvalue_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.sales_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_lastyearmonth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.mtd_growth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_lastyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.ytd_growth)+"</td>";
            html = html + "</tr>";
          html = html + "</table>";

          $("#report_salesnational_view_achievement").html(html);
      }
  });
}

//---

/*** salesman ****/
//----

$("#btn_go_salesman").click(function(){
    var year = $("#dsh_salesman_year").val();
    var month = $("#dsh_salesman_month").val();
    var slscode = $("#dsh_salesman_id").val();

    if(year == ""){
        show_error("Year could not blank");
        return false
    }

    //gen_report_salesman_achievement(year, month, slscode); // 2023-05-30

    gen_report_salesman_rating(year, month, slscode);
    gen_report_salesman_by_category(year, month, slscode);
    gen_report_salesman_weekly_performance(year, month, slscode);
    //gen_report_salesman_daily_trend_3months(year, month, slscode);
    gen_report_salesman_daily_trend_last_3months(year, month, slscode);
    gen_report_salesman_last_6months(year, month, slscode);
    gen_report_salesman_customer_active(year, month, slscode);
    gen_report_salesman_top_20customers(year, month, slscode);
    gen_report_salesman_top_40items(year, month, slscode);
    //gen_report_salesman_salesorder_daily(year, month, slscode);

    // 2023-07-28
    gen_report_salesman_view_actual_netsales_mtd_filter(year, month, slscode);
    gen_report_salesman_view_actual_netsales_ytd_filter(year, month, slscode);
    gen_report_salesman_view_actual_netsales_mtd_belt(year, month, slscode);
    gen_report_salesman_view_actual_netsales_ytd_belt(year, month, slscode);
    //--

    // 2023-07-31
    gen_report_salesman_view_actual_netsales_category_filter_mtd(year, month, slscode);
    gen_report_salesman_view_actual_netsales_category_filter_ytd(year, month, slscode);
    gen_report_salesman_view_actual_netsales_category_belt_mtd(year, month, slscode);
    gen_report_salesman_view_actual_netsales_category_belt_ytd(year, month, slscode);
    //---

    // 2023-07-31
    gen_report_salesman_backorder(year,slscode);
    //--

})
//---

function gen_report_salesman_by_category(year, month, slscode){
    $("#report_salesman_by_category").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_by_category_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesman_by_category_chart(responsedata);
        }
    });
}
//---

function gen_report_salesman_by_category_chart(data){

      Highcharts.chart('report_salesman_by_category', {
      chart: {
          type: 'column'
      },
      title:
      {
        style: {
          fontSize: '10px'
        },
          text: 'Sales By Category to data ($K)'
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
              text: ''
          }
      },
      legend: {
          enabled: false
      },
      tooltip: {
          pointFormat: 'Sales: <b>{point.y:.0f} K</b>'
      },
      series: [{
          name: 'Sales',
          data: data,
          dataLabels: {
              enabled: true,
              rotation: -90,
              color: '#FFFFFF',
              align: 'right',
              format: '{point.y:.0f}', // one decimal
              y: 10, // 10 pixels down from the top
              style: {
                  fontSize: '10px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
      }]
    });
}
//---

function gen_report_salesman_daily_trend_3months(year, month, slscode){
  $("#report_salesman_daily_trend_3months").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_daily_trend_3months_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, slscode:slscode},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesman_daily_trend_3months_chart(responsedata);
      }
  });
}
//----

function gen_report_salesman_daily_trend_3months_chart(data){
      Highcharts.chart('report_salesman_daily_trend_3months', {

      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Daily Sales Trend ($K)'
      },

      yAxis: {
          title: {
              text: ''
          }
      },

      xAxis: {
          accessibility: {
              rangeDescription: 'Range: '+data.last_2year_text+' to '+data.this_year_text
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
          name: data.this_year_text,
          data: data.this_year
      }, {
          name: data.last_year_text,
          data: data.last_year
      }, {
          name: data.last_2year_text,
          data: data.last_2year
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

function gen_report_salesman_daily_trend_last_3months(year, month, slscode){
  $("#report_salesman_daily_trend_3months").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_daily_trend_last_3months_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, slscode:slscode},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesman_daily_trend_last_3months_chart(responsedata);
      }
  });
}
//----

function gen_report_salesman_daily_trend_last_3months_chart(data){
      Highcharts.chart('report_salesman_daily_trend_3months', {

      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Daily Sales Trend ($K)'
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

function gen_report_salesman_customer_active(year, month, slscode){
    $("#report_salesman_customer_active").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_customer_active_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesman_customer_active_chart(responsedata);
        }
    });
}
//----

function gen_report_salesman_customer_active_chart(data){
      // Build the chart
    Highcharts.chart('report_salesman_customer_active', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Customers Active'
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
      },
      accessibility: {
          point: {
              valueSuffix: ''
          }
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: false
              },
              showInLegend: true
          }
      },
      series: [{
          name: 'Customers',
          colorByPoint: true,
          data: [{
            name: 'Buy',
            y: data.buy,
            sliced: true,
            selected: true
        }, {
            name: 'Not Buy',
            y: data.notbuy
        },]
      }]
    });
}
//---

function gen_report_salesman_top_20customers(year, month, slscode){
    $("#report_salesman_top_20customers").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_top_20customers_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesman_top_20customers_chart(responsedata);
        }
    });
}
//---

function gen_report_salesman_top_20customers_chart(data){
  Highcharts.chart('report_salesman_top_20customers', {
    chart: {
        type: 'bar'
    },
    title: {
      style: {
        fontSize: '10px'
      },
        text: 'Top 20 Customers'
    },

    xAxis: {
        categories: data.categories,
        title: {
            text: null
        },
        labels: {
    			style: {
    				fontSize: '8px'
    			}
		}
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Sales ($K)',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ''
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: false
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'bottom',
        x: 0,
        y: 20,
        floating: true,
        borderWidth: 1,
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: data.this_month_name,
        data: data.this_month
    },{
        name: data.last_month_name,
        data: data.last_month
    }, {
        name: data.last_2months_name,
        data: data.last_2months
    }]
});
}
//--

function gen_report_salesman_top_40items(year, month, slscode){
    $("#report_salesman_top_40items").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_top_40items_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            report_salesman_top_40items_chart(responsedata);
        }
    });
}
//---

function report_salesman_top_40items_chart(data){
    Highcharts.chart('report_salesman_top_40items', {
      chart: {
          type: 'column'
      },
      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Top 40 Items'
      },
      xAxis: {
          categories: data.categories,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: ''
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
              '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
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
      series: [{
          name: data.this_month_name,
          data: data.this_month
      },{
          name: data.last_month_name,
          data: data.last_month
      }, {
          name: data.last_2months_name,
          data: data.last_2months
      }]
      });
}
//---

function  gen_report_salesman_rating(year, month, slscode){
    $("#rating_name").text($("#dsh_salesman_id :selected").text());

    var progress = '<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%"> Loading...</div></div>';

    $("#rating_target_month").html(progress);
    $("#rating_sales_mtd").html(progress);
    $("#rating_percentage_mtd").html(progress);
    $("#rating_target_year").html(progress);
    $("#rating_sales_ytd").html(progress);
    $("#rating_percentage_ytd").html(progress);
    $("#rating_mtd_achv_last_year").html(progress);
    $("#rating_percentage_mtd_growth").html(progress);
    $("#rating_ytd_achv_last_year").html(progress);
    $("#rating_percentage_ytd_growth").html(progress);

    $("#rating_target_month_bar").html("");
    $("#rating_percentage_mtd_bar").html("");
    $("#rating_target_year_bar").html("");
    $("#rating_percentage_ytd_bar").html("");
    $("#rating_mtd_achv_last_year_bar").html("");
    $("#rating_percentage_mtd_growth_bar").html("");
    $("#rating_percentage_ytd_growth_bar").html("");
    $("#rating_ytd_achv_last_year_bar").html("");
    $("#rating_sales_mtd_bar").html("");
    $("#rating_sales_ytd_bar").html("");

    $("#rating_percentage_mtd_growth_arrow").html("");
    $("#rating_percentage_ytd_growth_arrow").html("");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_rating_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var data = $.parseJSON(respons);

            $("#rating_sales_mtd").text(data.sales_mtd);
            $("#rating_sales_ytd").text(data.sales_ytd);

            $("#rating_target_month").text(data.tgt_value);
            $("#rating_target_month_bar").html("<div class='progress'><div class='progress-bar' style='width:100%'></div></div>");

            $("#rating_percentage_mtd").text(data.percentage_mtd);
            var percent = parseInt(data.sales_mtd.replaceAll(",",""))/parseInt(data.tgt_value.replaceAll(",",""))*100;
            $("#rating_sales_mtd_bar").html("<div class='progress'><div class='progress-bar' style='width:"+percent+"%'></div></div>");

            $("#rating_target_year").text(data.tgt_value_ytd);
            $("#rating_target_year_bar").html("<div class='progress'><div class='progress-bar bg-info' style='width:100%'></div></div>");

            $("#rating_percentage_ytd").text(data.percentage_ytd);
            var percent = parseInt(data.sales_ytd.replaceAll(",",""))/parseInt(data.tgt_value_ytd.replaceAll(",",""))*100;
            $("#rating_sales_ytd_bar").html("<div class='progress'><div class='progress-bar bg-info' style='width:"+percent+"%'></div></div>");

            var mtd_percent = parseFloat($("#rating_percentage_mtd").text());
            var ytd_percent = parseFloat($("#rating_percentage_ytd").text());

            if(mtd_percent < 90) $("#rating_percentage_mtd").css("color", "red");
            if(ytd_percent < 90) $("#rating_percentage_ytd").css("color", "red");
        }
    });

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_view_achievement_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year,month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);

            $("#rating_mtd_achv_last_year").text(responsedata.salesvalue_lastyearmonth);
            $("#rating_mtd_achv_last_year_bar").html("<div class='progress'><div class='progress-bar bg-warning' style='width:100%'></div></div>");

            $("#rating_percentage_mtd_growth").text(responsedata.mtd_growth);
            var percent = parseInt(responsedata.mtd_growth);
            if(percent < 0){
              percent = 100+percent;
              $("#rating_percentage_mtd_growth_arrow").html("<i class='bi bi-arrow-down-left' style='font-size: 30px; color:red;'></i>");
              $("#rating_percentage_mtd_growth_bar").html("<div class='progress'><div class='progress-bar bg-danger' style='width:"+percent+"%'></div></div>");
            }
            else{
                $("#rating_percentage_mtd_growth_arrow").html("<i class='bi bi-arrow-up-right' style='font-size: 30px; color:green;'></i>");
                $("#rating_percentage_mtd_growth_bar").html("<div class='progress'><div class='progress-bar bg-success' style='width:100%'></div></div>");
            }

            $("#rating_ytd_achv_last_year").text(responsedata.salesvalue_lastyear);
            $("#rating_ytd_achv_last_year_bar").html("<div class='progress'><div class='progress-bar bg-warning' style='width:100%'></div></div>");

            $("#rating_percentage_ytd_growth").text(responsedata.ytd_growth);
            var percent = parseInt(responsedata.ytd_growth);
            if(percent < 0){
              percent = 100+percent;
              $("#rating_percentage_ytd_growth_arrow").html("<i class='bi bi-arrow-down-left' style='font-size: 30px; color:red;'></i>");
              $("#rating_percentage_ytd_growth_bar").html("<div class='progress'><div class='progress-bar bg-danger' style='width:"+percent+"%'></div></div>");
            }
            else{
              $("#rating_percentage_ytd_growth_arrow").html("<i class='bi bi-arrow-up-right' style='font-size: 30px; color:green;'></i>");
              $("#rating_percentage_ytd_growth_bar").html("<i class='bi bi-arrow-up-right'></i><div class='progress'><div class='progress-bar bg-success' style='width:100%'></div></div>");
            }

            var mtd_growth_percent = parseFloat($("#rating_percentage_mtd_growth").text());
            var ytd_growth_percent = parseFloat($("#rating_percentage_ytd_growth").text());

            if(mtd_growth_percent < 0) $("#rating_percentage_mtd_growth").css("color", "red");
            if(ytd_growth_percent < 0) $("#rating_percentage_ytd_growth").css("color", "red");
        }
    });
}
//--

function gen_report_salesman_weekly_performance(year, month, slscode){
    $("#report_salesman_weekly_performance").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_weekly_performance_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            report_salesman_weekly_performance_chart(responsedata);
        }
    });
}
//--

function report_salesman_weekly_performance_chart(data){
      var chart = Highcharts.chart('report_salesman_weekly_performance', {

    chart: {
        type: 'column'
    },

    title: {
      style: {
        fontSize: '10px'
      },
        text: 'Weekly Performance'
    },
    legend: {
        align: 'right',
        verticalAlign: 'middle',
        layout: 'vertical'
    },

    xAxis: {
        categories: data.week,
        labels: {
            x: -10
        }
    },

    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Amount'
        }
    },

    series: [{
        name: 'Sales',
        data: data.sales
    }, {
        name: 'Target',
        data: data.target
    }, ],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                yAxis: {
                    labels: {
                        align: 'left',
                        x: 0,
                        y: -5
                    },
                    title: {
                        text: null
                    }
                },
                subtitle: {
                    text: null
                },
                credits: {
                    enabled: false
                }
            }
        }]
    }
    });

    document.getElementById('small').addEventListener('click', function () {
    chart.setSize(400);
    });

    document.getElementById('large').addEventListener('click', function () {
    chart.setSize(600);
    });

    document.getElementById('auto').addEventListener('click', function () {
    chart.setSize(null);
    });
}
//---

function gen_report_salesnational_view_dailysalesorder(year, month){
  $("#report_salesnational_view_dailysalesorder").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_dailysalesorder_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month},
      success   :  function(respons){
        $('#report_salesnational_view_dailysalesorder').fadeIn("5000");
        $("#report_salesnational_view_dailysalesorder").html(respons);
      }
  });
}
//---

function gen_report_salesman_salesorder_daily(year, month, slscode){
  $("#report_salesman_salesorder_daily").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_salesorder_daily_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, slscode:slscode},
      success   :  function(respons){
        $('#report_salesman_salesorder_daily').fadeIn("5000");
        $("#report_salesman_salesorder_daily").html(respons);
      }
  });
}
//---

function gen_report_salesnational_view_total_invoice_cn_nett_nav(year, month){
    $("#report_salesnational_view_nav_total_invc_cn_net").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_total_invoice_cn_nett",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
          $('#report_salesnational_view_nav_total_invc_cn_net').fadeIn("5000");
          $("#report_salesnational_view_nav_total_invc_cn_net").html(respons);
        }
    });
}
//---

function gen_report_salesnational_view_total_value_wms(){
    $("#report_salesnational_view_nav_value_wms").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_total_value_wms",
        type      : 'post',
        dataType  : 'html',
        success   :  function(respons){
          $('#report_salesnational_view_nav_value_wms').fadeIn("5000");
          $("#report_salesnational_view_nav_value_wms").html(respons);
        }
    });
}
//---

//-- CS --//

$("#btn_go_cs").click(function(){
    var year = $("#dsh_cs_year").val();
    var month = $("#dsh_cs_month").val();
    var slscode = $("#dsh_cs_id").val();

    if(year == ""){
        show_error("Year could not blank");
        return false
    }

    gen_report_cs_achievement(year, month, slscode); // 2023-05-30

    gen_report_cs_rating(year, month, slscode);
    gen_report_cs_by_category(year, month, slscode);
    gen_report_cs_weekly_performance(year, month, slscode);
    gen_report_cs_daily_trend_3months(year, month, slscode);
    gen_report_cs_daily_trend_last_3months(year, month, slscode);
    gen_report_cs_customer_active(year, month, slscode);
    gen_report_cs_top_20customers(year, month, slscode);
    gen_report_cs_top_40items(year, month, slscode);
    gen_report_cs_salesorder_daily(year, month, slscode);
})
//---

function  gen_report_cs_rating(year, month, slscode){
    $("#rating_name_cs").text($("#dsh_cs_id :selected").text());

    var progress = '<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%"> Loading...</div></div>';

    $("#rating_target_month_cs").html(progress);
    $("#rating_sales_mtd_cs").html(progress);
    $("#rating_percentage_mtd_cs").html(progress);
    $("#rating_target_year_cs").html(progress);
    $("#rating_sales_ytd_cs").html(progress);
    $("#rating_percentage_ytd_cs").html(progress);

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_cs_rating_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var data = $.parseJSON(respons);

            $("#rating_target_month_cs").text(data.tgt_value);
            $("#rating_sales_mtd_cs").text(data.sales_mtd);
            $("#rating_percentage_mtd_cs").text(data.percentage_mtd);
            $("#rating_target_year_cs").text(data.tgt_value_ytd);
            $("#rating_sales_ytd_cs").text(data.sales_ytd);
            $("#rating_percentage_ytd_cs").text(data.percentage_ytd);
        }
    });
}
//--

function gen_report_cs_by_category(year, month, slscode){
    $("#report_cs_by_category").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_cs_by_category_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_cs_by_category_chart(responsedata);
        }
    });
}
//---

function gen_report_cs_by_category_chart(data){

      Highcharts.chart('report_cs_by_category', {
      chart: {
          type: 'column'
      },
      title:
      {
        style: {
          fontSize: '10px'
        },
          text: 'Sales By Category to data ($K)'
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
              text: ''
          }
      },
      legend: {
          enabled: false
      },
      tooltip: {
          pointFormat: 'Sales: <b>{point.y:.0f} K</b>'
      },
      series: [{
          name: 'Sales',
          data: data,
          dataLabels: {
              enabled: true,
              rotation: -90,
              color: '#FFFFFF',
              align: 'right',
              format: '{point.y:.0f}', // one decimal
              y: 10, // 10 pixels down from the top
              style: {
                  fontSize: '10px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
      }]
    });
}
//---

function gen_report_cs_weekly_performance(year, month, slscode){
    $("#report_cs_weekly_performance").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_cs_weekly_performance_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            report_cs_weekly_performance_chart(responsedata);
        }
    });
}
//--

function report_cs_weekly_performance_chart(data){
      var chart = Highcharts.chart('report_cs_weekly_performance', {

    chart: {
        type: 'column'
    },

    title: {
      style: {
        fontSize: '10px'
      },
        text: 'Weekly Performance'
    },
    legend: {
        align: 'right',
        verticalAlign: 'middle',
        layout: 'vertical'
    },

    xAxis: {
        categories: data.week,
        labels: {
            x: -10
        }
    },

    yAxis: {
        allowDecimals: false,
        title: {
            text: 'Amount'
        }
    },

    series: [{
        name: 'Sales',
        data: data.sales
    }, {
        name: 'Target',
        data: data.target
    }, ],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                yAxis: {
                    labels: {
                        align: 'left',
                        x: 0,
                        y: -5
                    },
                    title: {
                        text: null
                    }
                },
                subtitle: {
                    text: null
                },
                credits: {
                    enabled: false
                }
            }
        }]
    }
    });

    document.getElementById('small').addEventListener('click', function () {
    chart.setSize(400);
    });

    document.getElementById('large').addEventListener('click', function () {
    chart.setSize(600);
    });

    document.getElementById('auto').addEventListener('click', function () {
    chart.setSize(null);
    });
}
//---

function gen_report_cs_daily_trend_3months(year, month, slscode){
  $("#report_cs_daily_trend_3months").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/report_cs_daily_trend_3months_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, slscode:slscode},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_cs_daily_trend_3months_chart(responsedata);
      }
  });
}
//----

function gen_report_cs_daily_trend_3months_chart(data){
      Highcharts.chart('report_cs_daily_trend_3months', {

      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Daily Sales Trend ($K)'
      },

      yAxis: {
          title: {
              text: ''
          }
      },

      xAxis: {
          accessibility: {
              rangeDescription: 'Range: '+data.last_2year_text+' to '+data.this_year_text
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
          name: data.this_year_text,
          data: data.this_year
      }, {
          name: data.last_year_text,
          data: data.last_year
      }, {
          name: data.last_2year_text,
          data: data.last_2year
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

function gen_report_cs_customer_active(year, month, slscode){
    $("#report_cs_customer_active").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_cs_customer_active_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_cs_customer_active_chart(responsedata);
        }
    });
}
//----

function gen_report_cs_customer_active_chart(data){
    // Build the chart
    Highcharts.chart('report_cs_customer_active', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Customers Active'
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
      },
      accessibility: {
          point: {
              valueSuffix: ''
          }
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: false
              },
              showInLegend: true
          }
      },
      series: [{
          name: 'Customers',
          colorByPoint: true,
          data: [{
            name: 'Buy',
            y: data.buy,
            sliced: true,
            selected: true
        }, {
            name: 'Not Buy',
            y: data.notbuy
        },]
      }]
    });
}
//---

function gen_report_cs_top_20customers(year, month, slscode){
    $("#report_cs_top_20customers").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_cs_top_20customers_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_cs_top_20customers_chart(responsedata);
        }
    });
}
//---

function gen_report_cs_top_20customers_chart(data){
  Highcharts.chart('report_cs_top_20customers', {
    chart: {
        type: 'bar'
    },
    title: {
      style: {
        fontSize: '10px'
      },
        text: 'Top 20 Customers'
    },

    xAxis: {
        categories: data.categories,
        title: {
            text: null
        },
        labels: {
    			style: {
    				fontSize: '8px'
    			}
		}
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Sales ($K)',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ''
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: false
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'left',
        verticalAlign: 'bottom',
        x: 0,
        y: 20,
        floating: true,
        borderWidth: 1,
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: data.this_month_name,
        data: data.this_month
    },{
        name: data.last_month_name,
        data: data.last_month
    }, {
        name: data.last_2months_name,
        data: data.last_2months
    }]
});
}
//--

function gen_report_cs_top_40items(year, month, slscode){
    $("#report_cs_top_40items").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/report_cs_top_40items_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            report_cs_top_40items_chart(responsedata);
        }
    });
}
//---

function gen_report_cs_salesorder_daily(year, month, slscode){
  $("#report_cs_salesorder_daily").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/report_cs_salesorder_daily_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, slscode:slscode},
      success   :  function(respons){
        $('#report_cs_salesorder_daily').fadeIn("5000");
        $("#report_cs_salesorder_daily").html(respons);
      }
  });
}
//---

function gen_report_cs_daily_trend_last_3months(year, month, slscode){
  $("#report_cs_daily_trend_3months").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/report_cs_daily_trend_last_3months_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, slscode:slscode},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_cs_daily_trend_last_3months_chart(responsedata);
      }
  });
}
//----

function gen_report_cs_daily_trend_last_3months_chart(data){
      Highcharts.chart('report_cs_daily_trend_3months', {

      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Daily Sales Trend ($K)'
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

function report_cs_top_40items_chart(data){
    Highcharts.chart('report_cs_top_40items', {
      chart: {
          type: 'column'
      },
      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Top 40 Items'
      },
      xAxis: {
          categories: data.categories,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: ''
          }
      },
      tooltip: {
          headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
              '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
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
      series: [{
          name: data.this_month_name,
          data: data.this_month
      },{
          name: data.last_month_name,
          data: data.last_month
      }, {
          name: data.last_2months_name,
          data: data.last_2months
      }]
      });
}
//---


// 2023-05-25
function gen_report_salesnational_view_salesvsbudget_filter(year, month){
    $("#report_salesnational_view_salesvsbudget_filter").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_salesvsbudget_filter_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_salesvsbudget_filter_chart(responsedata);
        }
    });
}
//---

// 2023-05-25
function gen_report_salesnational_view_salesvsbudget_filter_chart(data){

    Highcharts.chart('report_salesnational_view_salesvsbudget_filter', {
      chart: {
          type: 'column'
      },
      title: {
          text: 'Sales vs Budget by Sales person Filter ($K)'
      },
      subtitle: {
          text: ''
      },
      xAxis: {
          categories: data.categories,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'MXN'
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
      series: data.detail
  });
}
//--

// 2023-05-25
function gen_report_salesnational_view_salesvsbudget_belt(year, month){
    $("#report_salesnational_view_salesvsbudget_belt").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_salesvsbudget_belt_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_salesvsbudget_belt_chart(responsedata);
        }
    });
}
//---

// 2023-05-25
function gen_report_salesnational_view_salesvsbudget_belt_chart(data){

    Highcharts.chart('report_salesnational_view_salesvsbudget_belt', {
      chart: {
          type: 'column'
      },
      title: {
          text: 'Sales vs Budget by Sales person Belt ($K)'
      },
      subtitle: {
          text: ''
      },
      xAxis: {
          categories: data.categories,
          crosshair: true
      },
      yAxis: {
          min: 0,
          title: {
              text: 'MXN'
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
      series: data.detail
  });
}
//--

// 2023-05-25
function gen_report_salesnational_view_salestrendvsbudget_filter(year){
  $("#report_salesnational_view_salestrendvsbudget_filter").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_salestrendvsbudget_filter_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesnational_view_salestrendvsbudget_filter_chart(responsedata);
      }
  });
}
//---

// 2023-05-25
function gen_report_salesnational_view_salestrendvsbudget_filter_chart(data){

    Highcharts.chart('report_salesnational_view_salestrendvsbudget_filter', {
        title: {
          style: {
            fontSize: '10px'
          },
            text: 'Total Sales Trend Vs Budget Filter ($K)'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        labels: {
            items: [{
                html: '',
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
            name: 'Target',
            data: data.target
        }, {
            type: 'spline',
            name: 'Sales',
            data: data.sales,
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }]
    });

}
//---

// 2023-05-25
function gen_report_salesnational_view_salestrendvsbudget_belt(year){
  $("#report_salesnational_view_salestrendvsbudget_belt").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_salestrendvsbudget_belt_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesnational_view_salestrendvsbudget_belt_chart(responsedata);
      }
  });
}
//---

// 2023-05-25
function gen_report_salesnational_view_salestrendvsbudget_belt_chart(data){

    Highcharts.chart('report_salesnational_view_salestrendvsbudget_belt', {
        title: {
          style: {
            fontSize: '10px'
          },
            text: 'Total Sales Trend Vs Budget Belt ($K)'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        labels: {
            items: [{
                html: '',
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
            name: 'Target',
            data: data.target
        }, {
            type: 'spline',
            name: 'Sales',
            data: data.sales,
            marker: {
                lineWidth: 2,
                lineColor: Highcharts.getOptions().colors[3],
                fillColor: 'white'
            }
        }]
    });

}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_mtd_filter(year, month){
    $("#report_salesnational_view_actual_netsales_mtd_filter").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_mtd_filter_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_mtd_chart_filter(responsedata);
        }
    });
}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_mtd_chart_filter(data){
    //report_salesnational_view_actual_netsales_mtd

    Highcharts.chart('report_salesnational_view_actual_netsales_mtd_filter', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: 0,
          plotShadow: false
      },
      title: {
          text: data.sales+'%',
          align: 'center',
          verticalAlign: 'middle',
          y: 60
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              dataLabels: {
                  enabled: true,
                  distance: -50,
                  style: {
                      fontWeight: 'bold',
                      color: 'white'
                  }
              },
              startAngle: -90,
              endAngle: 90,
              center: ['50%', '75%'],
              size: '110%'
          }
      },
      series: [{
          type: 'pie',
          name: 'Sales MTD',
          innerSize: '50%',
          data: [
              ['Sales : '+data.salesvalue, data.sales],
              ['Target : '+data.targetvalue, data.target]
          ]
      }]
  });
}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_ytd_filter(year,month){
    $("#report_salesnational_view_actual_netsales_ytd_filter").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_ytd_filter_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_ytd_chart_filter(responsedata);
        }
    });
}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_ytd_chart_filter(data){
  //report_salesnational_view_actual_netsales_mtd

    Highcharts.chart('report_salesnational_view_actual_netsales_ytd_filter', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: 0,
          plotShadow: false
      },
      title: {
          text: data.sales+'%',
          align: 'center',
          verticalAlign: 'middle',
          y: 60
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              dataLabels: {
                  enabled: true,
                  distance: -50,
                  style: {
                      fontWeight: 'bold',
                      color: 'white'
                  }
              },
              startAngle: -90,
              endAngle: 90,
              center: ['50%', '75%'],
              size: '110%'
          }
      },
      series: [{
          type: 'pie',
          name: 'Sales MTD',
          innerSize: '50%',
          data: [
              ['Sales : '+data.salesvalue, data.sales],
              ['Target : '+data.targetvalue, data.target]
          ]
      }]
  });
}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_mtd_belt(year, month){
    $("#report_salesnational_view_actual_netsales_mtd_belt").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_mtd_belt_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_mtd_chart_belt(responsedata);
        }
    });
}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_mtd_chart_belt(data){
    //report_salesnational_view_actual_netsales_mtd

    Highcharts.chart('report_salesnational_view_actual_netsales_mtd_belt', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: 0,
          plotShadow: false
      },
      title: {
          text: data.sales+'%',
          align: 'center',
          verticalAlign: 'middle',
          y: 60
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              dataLabels: {
                  enabled: true,
                  distance: -50,
                  style: {
                      fontWeight: 'bold',
                      color: 'white'
                  }
              },
              startAngle: -90,
              endAngle: 90,
              center: ['50%', '75%'],
              size: '110%'
          }
      },
      series: [{
          type: 'pie',
          name: 'Sales MTD',
          innerSize: '50%',
          data: [
              ['Sales : '+data.salesvalue, data.sales],
              ['Target : '+data.targetvalue, data.target]
          ]
      }]
  });
}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_ytd_belt(year,month){
    $("#report_salesnational_view_actual_netsales_ytd_belt").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesnational_view_actual_netsales_ytd_belt_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesnational_view_actual_netsales_ytd_chart_belt(responsedata);
        }
    });
}
//---

// 2023-05-25
function gen_report_salesnational_view_actual_netsales_ytd_chart_belt(data){
  //report_salesnational_view_actual_netsales_mtd

    Highcharts.chart('report_salesnational_view_actual_netsales_ytd_belt', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: 0,
          plotShadow: false
      },
      title: {
          text: data.sales+'%',
          align: 'center',
          verticalAlign: 'middle',
          y: 60
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              dataLabels: {
                  enabled: true,
                  distance: -50,
                  style: {
                      fontWeight: 'bold',
                      color: 'white'
                  }
              },
              startAngle: -90,
              endAngle: 90,
              center: ['50%', '75%'],
              size: '110%'
          }
      },
      series: [{
          type: 'pie',
          name: 'Sales MTD',
          innerSize: '50%',
          data: [
              ['Sales : '+data.salesvalue, data.sales],
              ['Target : '+data.targetvalue, data.target]
          ]
      }]
  });
}
//---

// 2023-05-30
function gen_report_salesman_achievement(year, month, slscode){
  //$("#report_salesman_view_achievement").html("Loading, Please wait...");

  //var progress = '<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%"> Loading...</div></div>';

  /*$("#rating_mtd_achv_last_year").html(progress);
  $("#rating_percentage_mtd_growth").html(progress);
  $("#rating_ytd_achv_last_year").html(progress);
  $("#rating_percentage_ytd_growth").html(progress);*/

  /*$.ajax({
      url       : "<?php //echo base_url();?>index.php/sales/report/salesman_view_achievement_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year,month:month, slscode:slscode},
      success   :  function(respons){
          /*var responsedata = $.parseJSON(respons);

          $("#rating_mtd_achv_last_year").text(responsedata.salesvalue_lastyearmonth);
          $("#rating_percentage_mtd_growth").text(responsedata.mtd_growth);
          $("#rating_ytd_achv_last_year").text(responsedata.salesvalue_lastyear);
          $("#rating_percentage_ytd_growth").text(responsedata.ytd_growth);*/

        /*  var html = "";

          var sess = '<?php //echo $_SESSION['user_permis']["23"]; ?>';

          if(sess){
            html = html + "<button class='btn btn-success btn-sm'  id='btn_export_xlsx_salesman_view_achievement' onclick=f_convert_excel_salesman()>EXCEL</button>";
          }


          html = html + "<table class='table table-bordered' id='tbl_salesman_view_achievement'>";
            html = html + "<tr>";
              html = html + "<th>Salesman</th>";
              html = html + "<th>MTD Achv</th>";
              html = html + "<th>Tgt</th>";
              html = html + "<th>% MTD Tgt</th>";
              html = html + "<th>YTD Achv</th>";
              html = html + "<th>Year Tgt</th>";
              html = html + "<th>% YTD Tgt</th>";
              html = html + "<th>MTD Achv Last Year</th>";
              html = html + "<th>% MTD Growth</th>";
              html = html + "<th>YTD Achiv Last Year</th>";
              html = html + "<th>% YTD Growth</th>";
            html = html + "</tr>";
            html = html + "<tr>";
              html = html + "<td>Salesman</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_thismonth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.targetvalue_thismonth)+"</td>";
              html = html + "<td>"+responsedata.sales_thismonth+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.targetvalue_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.sales_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_lastyearmonth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.mtd_growth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_lastyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.ytd_growth)+"</td>";
            html = html + "</tr>";
          html = html + "</table>";

          $("#report_salesman_view_achievement").html(html);
*/
    //  }
  //});
}

//---

// 2023-05-30
function gen_report_cs_achievement(year, month, slscode){
  $("#report_cs_view_achievement").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/cs_view_achievement_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year,month:month, slscode:slscode},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          var html = "";

          var sess = '<?php echo $_SESSION['user_permis']["24"]; ?>';

          if(sess){
            html = html + "<button class='btn btn-success btn-sm'  id='btn_export_xlsx_salesman_view_achievement' onclick=f_convert_excel_cs()>EXCEL</button>";
          }

          html = html + "<table class='table table-bordered' id='tbl_cs_view_achievement'>";
            html = html + "<tr>";
              html = html + "<th>CS</th>";
              html = html + "<th>MTD Achv</th>";
              html = html + "<th>Tgt</th>";
              html = html + "<th>% MTD Tgt</th>";
              html = html + "<th>YTD Achv</th>";
              html = html + "<th>Year Tgt</th>";
              html = html + "<th>% YTD Tgt</th>";
              html = html + "<th>MTD Achv Last Year</th>";
              html = html + "<th>% MTD Growth</th>";
              html = html + "<th>YTD Achiv Last Year</th>";
              html = html + "<th>% YTD Growth</th>";
            html = html + "</tr>";
            html = html + "<tr>";
              html = html + "<td>CS</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_thismonth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.targetvalue_thismonth)+"</td>";
              html = html + "<td>"+responsedata.sales_thismonth+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.targetvalue_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.sales_thisyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_lastyearmonth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.mtd_growth)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.salesvalue_lastyear)+"</td>";
              html = html + "<td>"+js_number_format(responsedata.ytd_growth)+"</td>";
            html = html + "</tr>";
          html = html + "</table>";

          $("#report_cs_view_achievement").html(html);
      }
  });
}

//---

// national
function f_convert_excel_salesnational(){
  var table2excel = new Table2Excel();
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel.export(document.querySelector('#tbl_salesnational_view_achievement'),"NationalAchievement"),1000);
}
//---

// salesman
function f_convert_excel_salesman(){
  var table2excel2 = new Table2Excel();
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel2.export(document.querySelector('#tbl_salesman_view_achievement'),"SalesmanAchievement"),1000);
}
//---

// cs
function f_convert_excel_cs(){
  var table2excel3 = new Table2Excel();
  alert("Your converted to Excel, check your DOWNLOAD folder");
  setTimeout(table2excel3.export(document.querySelector('#tbl_cs_view_achievement'),"CsAchievement"),1000);
}
//---

// 2023-06-29
function get_top_item_by_name(name, year, month){

    data = {'name':name, 'year':year,'month':month }
    $('#modal_detail_map_top').html('Loading, Please wait...');
    //open the modal with selected parameter attached
    $('#modal_detail_map_top').load(
        "<?php echo base_url();?>index.php/sales/report/salesnational_view_top_by_state",
        data,
        function(responseText, textStatus, XMLHttpRequest) { } // complete callback
    );

    $('#myModalMapTop').modal();
}
//---

//2023-07-21
function gen_report_salesman_last_6months(year, month, slscode){
  $("#report_salesman_last_6months").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/report_salesman_last_6months",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, month:month, slscode:slscode},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesman_last_6months_chart(responsedata);
      }
  });
}
//----

//2023-07-21
function gen_report_salesman_last_6months_chart(data){
      /*Highcharts.chart('report_salesman_last_6months', {

      title: {
        style: {
          fontSize: '10px'
        },
          text: 'Sales last 6 Months ($K)'
      },

      yAxis: {
          title: {
              text: ''
          }
      },

      xAxis: {
          accessibility: {
              rangeDescription: 'Range: '+data.last_6months_text
          },
          type: 'category',
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
          name: data.last_6months_text,
          data: data.last_6months
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

    });*/

    Highcharts.chart('report_salesman_last_6months', {
    title: {
        text: 'Sales last 6 Months ($K)',
        align: 'center'
    },
    xAxis: {
        categories: data.x_axis_name
    },
    yAxis: {
        title: {
            text: 'Millions'
        }
    },
    tooltip: {
        valueSuffix: ''
    },
    plotOptions: {
        series: {
            borderRadius: '10%'
        }
    },
    series: [{
        type: 'column',
        name: 'Sales ($K)',
        data: data.sales
    }, {
        type: 'column',
        name: 'Target ($K)',
        data: data.target
    }, {
        type: 'spline',
        name: 'Sales ($K)',
        data: data.last_6months_line,
        marker: {
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'white'
        }
    }]
});
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_mtd_filter(year, month, slscode){
    $("#report_salesman_sales_mtd_filter").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_actual_netsales_mtd_filter_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesman_view_actual_netsales_mtd_filter_chart(responsedata);
        }
    });
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_mtd_filter_chart(data){

  Highcharts.chart('report_salesman_sales_mtd_filter', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: data.sales+'%',
        align: 'center',
        verticalAlign: 'middle',
        y: 60
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        }
    },
    series: [{
        type: 'pie',
        name: 'Sales MTD',
        innerSize: '50%',
        data: [
            ['Sales : '+data.salesvalue, data.sales],
            ['Target : '+data.targetvalue, data.target]
        ]
    }]
});
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_ytd_filter(year, month, slscode){
    $("#report_salesman_sales_ytd_filter").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_actual_netsales_ytd_filter_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesman_view_actual_netsales_ytd_filter_chart(responsedata);
        }
    });
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_ytd_filter_chart(data){

  Highcharts.chart('report_salesman_sales_ytd_filter', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: data.sales+'%',
        align: 'center',
        verticalAlign: 'middle',
        y: 60
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        }
    },
    series: [{
        type: 'pie',
        name: 'Sales MTD',
        innerSize: '50%',
        data: [
            ['Sales : '+data.salesvalue, data.sales],
            ['Target : '+data.targetvalue, data.target]
        ]
    }]
});
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_mtd_belt(year, month, slscode){
    $("#report_salesman_sales_mtd_belt").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_actual_netsales_mtd_belt_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesman_view_actual_netsales_mtd_belt_chart(responsedata);
        }
    });
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_mtd_belt_chart(data){

  Highcharts.chart('report_salesman_sales_mtd_belt', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: data.sales+'%',
        align: 'center',
        verticalAlign: 'middle',
        y: 60
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        }
    },
    series: [{
        type: 'pie',
        name: 'Sales MTD',
        innerSize: '50%',
        data: [
            ['Sales : '+data.salesvalue, data.sales],
            ['Target : '+data.targetvalue, data.target]
        ]
    }]
});
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_ytd_belt(year, month, slscode){
    $("#report_salesman_sales_ytd_belt").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_actual_netsales_ytd_belt_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            var responsedata = $.parseJSON(respons);
            gen_report_salesman_view_actual_netsales_ytd_belt_chart(responsedata);
        }
    });
}
//---

// 2023-07-28
function gen_report_salesman_view_actual_netsales_ytd_belt_chart(data){

  Highcharts.chart('report_salesman_sales_ytd_belt', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: data.sales+'%',
        align: 'center',
        verticalAlign: 'middle',
        y: 60
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: -50,
                style: {
                    fontWeight: 'bold',
                    color: 'white'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        }
    },
    series: [{
        type: 'pie',
        name: 'Sales MTD',
        innerSize: '50%',
        data: [
            ['Sales : '+data.salesvalue, data.sales],
            ['Target : '+data.targetvalue, data.target]
        ]
    }]
});
}
//---

// 2023-07-31
function gen_report_salesman_view_actual_netsales_category_filter_mtd(year, month, slscode){
    $("#report_salesman_sales_mtd_filter_detail").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_sales_item_cat_filter_mtd",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            $('#report_salesman_sales_mtd_filter_detail').fadeIn("5000");
            $("#report_salesman_sales_mtd_filter_detail").html(respons);
        }
    });
}
//---

// 2023-07-31
function gen_report_salesman_view_actual_netsales_category_filter_ytd(year, month, slscode){
    $("#report_salesman_sales_ytd_filter_detail").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_sales_item_cat_filter_ytd",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            $('#report_salesman_sales_ytd_filter_detail').fadeIn("5000");
            $("#report_salesman_sales_ytd_filter_detail").html(respons);
        }
    });
}
//---

// 2023-07-31
function gen_report_salesman_view_actual_netsales_category_belt_mtd(year, month, slscode){
    $("#report_salesman_sales_mtd_belt_detail").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_sales_item_cat_belt_mtd",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            $('#report_salesman_sales_mtd_belt_detail').fadeIn("5000");
            $("#report_salesman_sales_mtd_belt_detail").html(respons);
        }
    });
}
//---

// 2023-07-31
function gen_report_salesman_view_actual_netsales_category_belt_ytd(year, month, slscode){
    $("#report_salesman_sales_ytd_belt_detail").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_sales_item_cat_belt_ytd",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, month:month, slscode:slscode},
        success   :  function(respons){
            $('#report_salesman_sales_ytd_belt_detail').fadeIn("5000");
            $("#report_salesman_sales_ytd_belt_detail").html(respons);
        }
    });
}
//---

function gen_report_salesman_backorder(year,slscode){
    $("#report_salesman_backorder").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/salesman_backorder_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, slscode:slscode},
        success   :  function(respons){
            $('#report_salesman_backorder').fadeIn("5000");
            $("#report_salesman_backorder").html(respons);
        }
    });
}
//---

//2023-10-02
function gen_report_salesnational_view_sales_this_year_vs_last_year(year){
  $("#report_salesnational_view_sales_this_year_vs_last_year").html("Loading, Please wait...");
  $("#report_salesnational_view_sales_this_year_vs_last_year").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/salesnational_sales_this_year_vs_last_year_per_month",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year},
      success   :  function(respons){
          var responsedata = $.parseJSON(respons);
          gen_report_salesnational_view_sales_this_year_vs_last_year_chart(responsedata);
      }
  });
}
//--

//2023-10-02
function gen_report_salesnational_view_sales_this_year_vs_last_year_chart(data){
    Highcharts.chart('report_salesnational_view_sales_this_year_vs_last_year', {
    title: {
        text: 'Last Year VS This Year',
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
        name: data.last_year_text,
        data: data.last_year
    }, {
        type: 'column',
        name: data.this_year_text,
        data: data.this_year
    }
    ]
  });
}
//--

</script>
