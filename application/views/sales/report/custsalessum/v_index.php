<script>
// copy clipboard
var clipboard = new ClipboardJS('#copy_button_report', {
  target: function() {
    return document.querySelector('#tbl_report');
  }
});

// copy clipboard
var clipboard2 = new ClipboardJS('#copy_button_slsreview', {
  target: function() {
     return document.querySelector('#tbl_report_slsreview');
  }
});

// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_productreview', {
  target: function() {
    return document.querySelector('#tbl_report_productreview');
  }
});

// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_custreview', {
  target: function() {
    return document.querySelector('#tbl_report_custreview');
  }
});

// copy clipboard
var clipboard3 = new ClipboardJS('#copy_button_producttype', {
  target: function() {
    return document.querySelector('#tbl_report_producttype');
  }
});
//---

var clipboard3 = new ClipboardJS('#copy_button_item_cat_filter_report', {
  target: function() {
    return document.querySelector('#tbl_report_item_cat_filter');
  }
});
//--

var clipboard3 = new ClipboardJS('#copy_button_item_cat_belt_report', {
  target: function() {
    return document.querySelector('#tbl_report_item_cat_belt');
  }
});
//--

var clipboard3 = new ClipboardJS('#copy_button_cust_product_cat_filter', {
  target: function() {
    return document.querySelector('#tbl_cust_product_cat_filter');
  }
});
//---

var clipboard3 = new ClipboardJS('#copy_button_cust_product_cat_belt', {
  target: function() {
    return document.querySelector('#tbl_cust_product_cat_belt');
  }
});
//---

var clipboard3 = new ClipboardJS('#copy_button_cust_product_cat_filter2', {
  target: function() {
    return document.querySelector('#tbl_cust_product_cat_filter2');
  }
});
//---

var clipboard3 = new ClipboardJS('#copy_button_cust_product_cat_belt2', {
  target: function() {
    return document.querySelector('#tbl_cust_product_cat_belt2');
  }
});
//---

</script>

<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Customer Sales Summary
</div>

<div class="container-fluid" style="margin-top:10px;">
  <div class="row">
    <div class="col-md-2">
      <input type='text' name='inp_search_cust' value="" id='inp_search_cust' class='required form-control' placeholder='search customer' onchange=f_update_cust()>
    </div>
    <div class="col-md-2">
      <input type='text' name='inp_cust_code' value="" id='inp_cust_code' class='required form-control' placeholder='customer no' disabled>
    </div>
    <div class="col-md-3">
      <input type='text' name='inp_cust_name' value="" id='inp_cust_name' class='required form-control' placeholder='customer name' disabled>
    </div>
    <div class="col-md-1">
      <input type='text' name='datepicker_year' value="<?php echo date("Y"); ?>" id='datepicker_year' class='required form-control' placeholder='Year'>
    </div>
    <div class="col-md-1">
      <select id="inp_type" class='required form-control'>
        <option value="1">Quantity</option>
        <option value="2">Amount</option>
      </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" id="btn_go">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <ul class="nav nav-tabs" id="myTab" role="tablist">

    <?php if(isset($_SESSION['user_permis']["17"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="salesreport-tab" data-toggle="tab" href="#salesreport" role="tab" aria-controls="salesreport" aria-selected="true">SalesReport</a>
    </li>
    <?php } ?>

    <?php if(isset($_SESSION['user_permis']["18"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="salesreview-tab" data-toggle="tab" href="#salesreview" role="tab" aria-controls="salesreview" aria-selected="false">SalesReview</a>
    </li>
    <?php } ?>

    <?php if(isset($_SESSION['user_permis']["19"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="productreview-tab" data-toggle="tab" href="#productreview" role="tab" aria-controls="productreview" aria-selected="false">ProductReviewALL</a>
    </li>
    <?php } ?>

    <?php if(isset($_SESSION['user_permis']["20"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="productreviewcust-tab" data-toggle="tab" href="#productreviewcust" role="tab" aria-controls="productreviewcust" aria-selected="false">ProductReviewCust</a>
    </li>
    <?php } ?>

    <!--<li class="nav-item" role="presentation">
      <a class="nav-link" id="producttype-tab" data-toggle="tab" href="#producttype" role="tab" aria-controls="producttype" aria-selected="false">ProductType</a>
    </li>-->

    <?php if(isset($_SESSION['user_permis']["21"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="custreview-tab" data-toggle="tab" href="#custreview" role="tab" aria-controls="custreview" aria-selected="false">CustReview</a>
    </li>
    <?php } ?>

    <!-- // 2023-08-03 -->
    <?php if(isset($_SESSION['user_permis']["21"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="custproductcat-tab" data-toggle="tab" href="#custproductcat" role="tab" aria-controls="custproductcat" aria-selected="false">CustProductCat</a>
    </li>
    <?php } ?>

    <li class="nav-item" role="presentation">
      <a class="nav-link" id="custproductcat2-tab" data-toggle="tab" href="#custproductcat2" role="tab" aria-controls="custproductcat2" aria-selected="false">CustProductCat 2</a>
    </li>
    <!-- end -->

    <?php if(isset($_SESSION['user_permis']["21"])){ ?>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="custreview2-tab" data-toggle="tab" href="#custreview2" role="tab" aria-controls="custreview2" aria-selected="false">CustReview 2</a>
    </li>
    <?php } ?>

    <!-- // 2023-08-03 -->
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="nothing" data-toggle="tab" href="#nothing" role="tab" aria-controls="nothing" aria-selected="false"></a>
    </li>
    <!-- end -->

  </ul>

  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show" id="salesreport" role="tabpanel" aria-labelledby="salesreport-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_salesreport_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="salesreview" role="tabpanel" aria-labelledby="salesreview-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_salesreview_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="productreview" role="tabpanel" aria-labelledby="productreview-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_productreview_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="productreviewcust" role="tabpanel" aria-labelledby="productreviewcust-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_productreviewcust_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="producttype" role="tabpanel" aria-labelledby="producttype-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_producttype_view"></div>
      </div>
    </div>
    <div class="tab-pane fade show" id="custreview" role="tabpanel" aria-labelledby="custreview-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_custreview_view"></div>
      </div>
    </div>

    <div class="tab-pane fade show" id="custproductcat" role="tabpanel" aria-labelledby="custproductcat-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="row">
          <div class="col-1">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            </div>
          </div>
          <div class="col-11">
            <div id="report_custproductcat_prontopago_view"></div>
          </div>
        </div>
      </div>
      <div class="container-fluid" style="margin-top:20px;">
        <div class="row">
          <div class="col-1">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <button class="nav-link active" id="v-pills-filter-tab" data-toggle="pill" data-target="#v-pills-filter" type="button" role="tab" aria-controls="v-pills-filter" aria-selected="true">Filtro</button>
              <button class="nav-link" id="v-pills-banda-tab" data-toggle="pill" data-target="#v-pills-banda" type="button" role="tab" aria-controls="v-pills-banda" aria-selected="false">Banda</button>
            </div>
          </div>
          <div class="col-11">
            <div class="tab-content" id="v-pills-tabContent">
              <div class="tab-pane fade show active" id="v-pills-filter" role="tabpanel" aria-labelledby="v-pills-filter-tab">
                <div id="report_custproductcat_filter_view"></div>
              </div>
              <div class="tab-pane fade" id="v-pills-banda" role="tabpanel" aria-labelledby="v-pills-banda-tab">
                <div id="report_custproductcat_banda_view"></div>
              </div>
            </div>
          </div>
          </div>
      </div>
    </div>

    <!-- // 2023-08-03 -->
    <div class="tab-pane fade show" id="custproductcat2" role="tabpanel" aria-labelledby="custproductcat2-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="row">
          <div class="col-1">
            <div class="nav flex-column nav-pills" id="v-pills2-tab" role="tablist" aria-orientation="vertical">
            </div>
          </div>
          <div class="col-11">
            <div id="report_custproductcat_prontopago2_view"></div>
          </div>
        </div>
      </div>
      <div class="container-fluid" style="margin-top:20px;">
        <div class="row">
          <div class="col-1">
            <div class="nav flex-column nav-pills" id="v-pills2-tab" role="tablist" aria-orientation="vertical">
              <button class="nav-link active" id="v-pills-filter2-tab" data-toggle="pill" data-target="#v-pills-filter2" type="button" role="tab" aria-controls="v-pills-filter" aria-selected="true">Filtro</button>
              <button class="nav-link" id="v-pills-banda2-tab" data-toggle="pill" data-target="#v-pills-banda2" type="button" role="tab" aria-controls="v-pills-banda" aria-selected="false">Banda</button>
            </div>
          </div>
          <div class="col-11">
            <div class="tab-content" id="v-pills2-tabContent">
              <div class="tab-pane fade show active" id="v-pills-filter2" role="tabpanel" aria-labelledby="v-pills-filter-tab">
                <div id="report_custproductcat_filter2_view"></div>
              </div>
              <div class="tab-pane fade" id="v-pills-banda2" role="tabpanel" aria-labelledby="v-pills-banda-tab">
                <div id="report_custproductcat_banda2_view"></div>
              </div>
            </div>
          </div>
          </div>
      </div>
    </div>
    <!-- end -->

    <div class="tab-pane fade show" id="custreview2" role="tabpanel" aria-labelledby="custreview2-tab">
      <div class="container-fluid" style="margin-top:20px;">
          <div id="report_custreview2_view"></div>
      </div>
    </div>

    <!-- // 2023-08-03 -->
    <div class="tab-pane fade show active" id="nothing" role="tabpanel" aria-labelledby="nothing-tab">
      <div class="container-fluid" style="margin-top:20px;">
      </div>
    </div>
    <!-- end -->

  </div>
</div>

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
    var cust_code = $("#inp_cust_code").val();
    var cust_name = $("#inp_cust_name").val();
    var year = $("#datepicker_year").val();
    var type = $("#inp_type").val();

    if(cust_code=="" || cust_name==""){
        show_error("Customer Data not completed");
        return false;
    }

    gen_report_custproductcat_prontopago(year,cust_code);
    gen_report_custproductcat(year,cust_code);

    gen_report_custproductcat2(year,cust_code); // 2023-08-03

    gen_report_salesreport(cust_code, cust_name, year, type);
    gen_report_salesreview(cust_code, cust_name, year, type);
    gen_report_productreview(year, type);
    gen_report_productreviewcust(year, type, cust_code, cust_name);
    ///gen_report_producttype(year, type);
    gen_report_custreview(year, type);
    gen_report_custreview2(year, type); // 2023-05-10




})
//---

function gen_report_salesreport(cust_code, cust_name, year, type){
    $("#report_salesreport_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/custsalessum_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_code:cust_code, cust_name, year:year, type:type},
        success   :  function(respons){
            $('#report_salesreport_view').fadeIn("5000");
            $("#report_salesreport_view").html(respons);
        }
    });
}
//----

function gen_report_salesreview(cust_code, cust_name, year, type){
    $("#report_salesreview_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/custsalesreview_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {cust_code:cust_code, cust_name, year:year, type:type},
        success   :  function(respons){
            $('#report_salesreview_view').fadeIn("5000");
            $("#report_salesreview_view").html(respons);
        }
    });
}
//----

function gen_report_productreview(year, type){
    $("#report_productreview_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/productreview_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_productreview_view').fadeIn("5000");
            $("#report_productreview_view").html(respons);
        }
    });
}
//----

function gen_report_productreviewcust(year, type,cust_code, cust_name){
    $("#report_productreviewcust_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/productreviewcust_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type, cust_code:cust_code, cust_name:cust_name},
        success   :  function(respons){
            $('#report_productreviewcust_view').fadeIn("5000");
            $("#report_productreviewcust_view").html(respons);
        }
    });
}
//----

function gen_report_producttype(year, type){
    $("#report_producttype_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/producttype_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_producttype_view').fadeIn("5000");
            $("#report_producttype_view").html(respons);
        }
    });
}
//----

function gen_report_custreview(year, type){
    $("#report_custreview_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/custreview_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_custreview_view').fadeIn("5000");
            $("#report_custreview_view").html(respons);
        }
    });
}
//----

function gen_report_custproductcat(year,cust_code){
  $("#report_custproductcat_filter_view").html("Loading, Please wait...");
  $("#report_custproductcat_banda_view").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/get_custproductcat_filter_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year,cust_code:cust_code},
      success   :  function(respons){
          $('#report_custproductcat_filter_view').fadeIn("5000");
          $("#report_custproductcat_filter_view").html(respons);
      }
  });

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/get_custproductcat_banda_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, cust_code:cust_code},
      success   :  function(respons){
          $('#report_custproductcat_banda_view').fadeIn("5000");
          $("#report_custproductcat_banda_view").html(respons);
      }
  });
}
//---

function gen_report_custproductcat_prontopago(year,cust_code){
    $('#report_custproductcat_prontopago_view').html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/get_custproductcat_prontopago_data",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year,cust_code:cust_code},
        success   :  function(respons){
            $('#report_custproductcat_prontopago_view').fadeIn("5000");
            $('#report_custproductcat_prontopago_view').html(respons);
        }
    });
}
//---

function gen_report_custreview2(year, type){  // 2023-05-10
    $("#report_custreview2_view").html("Loading, Please wait...");

    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/report/custreview_data2",
        type      : 'post',
        dataType  : 'html',
        data      :  {year:year, type:type},
        success   :  function(respons){
            $('#report_custreview2_view').fadeIn("5000");
            $("#report_custreview2_view").html(respons);
        }
    });
}
//----

//sales item cat report filter
function f_convert_excel_sales_item_cat_filter_report(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_report_item_cat_filter'),"SalesItemCatReportFilter");

}
//--

//sales item cat report belt
function f_convert_excel_sales_item_cat_belt_report(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_report_item_cat_belt'),"SalesItemCatReportBelt");

}
//--

function f_convert_excel_cust_product_cat_filter(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_cust_product_cat_filter'),"CustSalesItemCatReportFilter");

}
//--

function f_convert_excel_cust_product_cat_belt(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_cust_product_cat_belt'),"CustSalesItemCatReportBelt");
}
//--

function f_convert_excel_cust_product_cat_filter2(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_cust_product_cat_filter2'),"CustSalesItemCatReportFilter");

}
//--

function f_convert_excel_cust_product_cat_belt2(){
    var table2excel = new Table2Excel();
    alert("Your converted to Excel, check your DOWNLOAD folder");
    table2excel.export(document.querySelector('#tbl_cust_product_cat_belt2'),"CustSalesItemCatReportBelt");
}
//--

// 2023-08-03
function gen_report_custproductcat2(year,cust_code){
  $("#report_custproductcat_filter2_view").html("Loading, Please wait...");
  $("#report_custproductcat_banda2_view").html("Loading, Please wait...");

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/get_custproductcat_filter2_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year,cust_code:cust_code},
      success   :  function(respons){
          $('#report_custproductcat_filter2_view').fadeIn("5000");
          $("#report_custproductcat_filter2_view").html(respons);
      }
  });

  $.ajax({
      url       : "<?php echo base_url();?>index.php/sales/report/get_custproductcat_banda2_data",
      type      : 'post',
      dataType  : 'html',
      data      :  {year:year, cust_code:cust_code},
      success   :  function(respons){
          $('#report_custproductcat_banda2_view').fadeIn("5000");
          $("#report_custproductcat_banda2_view").html(respons);
      }
  });
}
//---

</script>
