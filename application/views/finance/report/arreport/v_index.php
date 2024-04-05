<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      AR-Reports
</div>


<div class="container-fluid">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <!--<li class="nav-item" role="presentation" style="display:none;">
      <a class="nav-link" id="one-tab" data-toggle="tab" data-target="#one" type="button" role="tab" aria-controls="one" aria-selected="true">Terms-AR-InvoiceDate</a>
    </li>
    <li class="nav-item" role="presentation" style="display:none;">
      <a class="nav-link" id="two-tab" data-toggle="tab" data-target="#two" type="button" role="tab" aria-controls="two" aria-selected="false">CreditTerms-InvoiceDate</a>
    </li>-->
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="three-tab" data-toggle="tab" data-target="#three" type="button" role="tab" aria-controls="three" aria-selected="false">CashFlow-AR-PaymentDate</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="four-tab" data-toggle="tab" data-target="#four" type="button" role="tab" aria-controls="four" aria-selected="false">CreditTerms-PaymentDate</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade" id="one" role="tabpanel" aria-labelledby="one-tab">
      <div class="row" style="margin-top:10px;">
        <div class="col-1">
          <span class="badge badge-primary">Year (INVC)</span>
          <input type="text" id="inp_year_one" value="<?php echo date("Y"); ?>"  class="form-control">
        </div>

        <div class="col-1">
          <span class="badge badge-primary">Month (INVC)</span>
          <select id="inp_month_one" class="form-control">
            <?php
              $selected = date("m");
              echo generate_month($selected);
            ?>
          </select>
        </div>

        <div class="col-1">
          <button class="btn btn-primary" id="btn_go_one" style="margin-top:18px;">GO</button>
        </div>
      </div>
      <div class="container" id="report_one" style="margin-top:20px;"></div>
    </div>

    <div class="tab-pane fade" id="two" role="tabpanel" aria-labelledby="two-tab">
        <div class="row" style="margin-top:10px;">
          <div class="col-1">
            <span class="badge badge-info">Year (INVC)</span>
            <input type="text" id="inp_year_two" value="<?php echo date("Y"); ?>"  class="form-control">
          </div>

          <div class="col-1">
            <span class="badge badge-info">Month (INVC)</span>
            <select id="inp_month_two" class="form-control">
              <?php
                $selected = date("m");
                echo generate_month($selected);
              ?>
            </select>
          </div>

          <div class="col-1">
            <button class="btn btn-info" id="btn_go_two" style="margin-top:18px;">GO</button>
          </div>
        </div>
        <div class="container" id="report_two" style="margin-top:20px;"></div>
    </div>

    <div class="tab-pane fade show active" id="three" role="tabpanel" aria-labelledby="three-tab">
        <div class="row" style="margin-top:10px;">
          <div class="col-1">
            <span class="badge badge-warning">Year (PAYMENT)</span>
            <input type="text" id="inp_year_three" value="<?php echo date("Y"); ?>"  class="form-control">
          </div>

          <div class="col-1">
            <span class="badge badge-warning">Month (PAYMENT)</span>
            <select id="inp_month_three" class="form-control">
              <?php
                $selected = date("m");
                echo generate_month($selected);
              ?>
            </select>
          </div>

          <div class="col-1">
            <button class="btn btn-warning" id="btn_go_three" style="margin-top:18px;">GO</button>
          </div>
        </div>
        <div class="container" id="report_three" style="margin-top:20px;"></div>
    </div>

    <div class="tab-pane fade" id="four" role="tabpanel" aria-labelledby="four-tab">
        <div class="row" style="margin-top:10px;">
          <div class="col-1">
            <span class="badge badge-danger">Year (PAYMENT)</span>
            <input type="text" id="inp_year_four" value="<?php echo date("Y"); ?>"  class="form-control">
          </div>

          <div class="col-1">
            <span class="badge badge-danger">Month (PAYMENT)</span>
            <select id="inp_month_four" class="form-control">
              <?php
                $selected = date("m");
                echo generate_month($selected);
              ?>
            </select>
          </div>

          <div class="col-1">
            <button class="btn btn-danger" id="btn_go_four" style="margin-top:18px;">GO</button>
          </div>
        </div>
        <div class="container" id="report_four" style="margin-top:20px;"></div>
    </div>
  </div>
</div>


<script>
  //---
  $("#btn_go_one").click(function(){
      $("#report_one").html("Loading, Please wait...");

      var year = $("#inp_year_one").val();
      var month = $("#inp_month_one").val();

      $.ajax({
          url       : "<?php echo base_url();?>index.php/finance/report/arreport_one_data",
          type      : 'post',
          dataType  : 'html',
          data      :  {year:year, month:month},
          success   :  function(respons){
              $('#report_one').fadeIn("5000");
              $("#report_one").html(respons);
          }
      });
  })
  //---

  $("#btn_go_two").click(function(){
      $("#report_two").html("Loading, Please wait...");

      var year = $("#inp_year_two").val();
      var month = $("#inp_month_two").val();

      $.ajax({
          url       : "<?php echo base_url();?>index.php/finance/report/arreport_two_data",
          type      : 'post',
          dataType  : 'html',
          data      :  {year:year, month:month},
          success   :  function(respons){
              $('#report_two').fadeIn("5000");
              $("#report_two").html(respons);
          }
      });
  })
  //---

  $("#btn_go_three").click(function(){
      $("#report_three").html("Loading, Please wait...");

      var year = $("#inp_year_three").val();
      var month = $("#inp_month_three").val();

      $.ajax({
          url       : "<?php echo base_url();?>index.php/finance/report/arreport_three_data",
          type      : 'post',
          dataType  : 'html',
          data      :  {year:year, month:month},
          success   :  function(respons){
              $('#report_three').fadeIn("5000");
              $("#report_three").html(respons);
          }
      });
  })
  //---

  $("#btn_go_four").click(function(){
      $("#report_four").html("Loading, Please wait...");

      var year = $("#inp_year_four").val();
      var month = $("#inp_month_four").val();

      $.ajax({
          url       : "<?php echo base_url();?>index.php/finance/report/arreport_four_data",
          type      : 'post',
          dataType  : 'html',
          data      :  {year:year, month:month},
          success   :  function(respons){
              $('#report_four').fadeIn("5000");
              $("#report_four").html(respons);
          }
      });
  })
  //---
</script>
