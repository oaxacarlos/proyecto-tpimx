<div onselectstart="return false" style="padding-bottom:20px;"></div>

<div class="container-fluid" style="height:100%; width:100%;" onselectstart="return false">
  <ul class="nav nav-tabs" id="myTab" role="tablist" onselectstart="return false">

    <li class="nav-item" role="presentation" onselectstart="return false">
      <a class="nav-link active" id="appsearch-tab" data-toggle="tab" href="#appsearch" role="tab" aria-controls="appsearch" aria-selected="false">Application Search</a>
    </li>

    <li class="nav-item" role="presentation" onselectstart="return false">
      <a class="nav-link" id="codesearch-tab" data-toggle="tab" href="#codesearch" role="tab" aria-controls="codesearch" aria-selected="false">Search by Code</a>
    </li>
  </ul>

  <div class="tab-content" id="myTabContent" onselectstart="return false">
    <!-- by appsearch -->
    <div class="tab-pane fade show active" id="appsearch" role="tabpanel" aria-labelledby="appsearch-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="container-fluid" onselectstart="return false">
          <div class="row">
            <div class="col-md-1">
              Make
              <select id="inp_make" class="form-control">
                <option value="">-</option>
                <?php
                  foreach($var_make as $row){
                      echo "<option value='".$row["make"]."'>".$row["make"]."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-md-1">
              Model
              <select id="inp_model" class="form-control" disabled></select>
            </div>
            <div class="col-md-2">
              Year
              <select id="inp_year" class="form-control" disabled></select>
            </div>
            <div class="col-md-2">
              Engine
              <select id="inp_engine" class="form-control" disabled></select>
            </div>
            <div class="col-md-2">
              PROCESS<br>
              <button class="btn btn-primary" id='btn_go_appsearch'>GO</button>
            </div>
          </div>
        </div>

        <div class="container" style="margin-top:30px;">
          <?php echo load_progress("progress_appsearch"); ?>
          <div id="report_appsearch_view"></div>
        </div>
      </div>
    </div>
    <!-- end of by appsearch -->

    <!-- by codesearch -->
    <div class="tab-pane fade" id="codesearch" role="tabpanel" aria-labelledby="codesearch-tab">
      <div class="container-fluid" style="margin-top:20px;">
        <div class="container-fluid" style="margin-top:10px;" onselectstart="return false">
          <div class="row">
            <div class="col-md-12">
              You must write in the space the code of other brands or sakura code.
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              Escribir en el espacio el código de sakura, cruce de referencia o código original que se busca para poder ver todas las aplicaciones vehiculares de dicho código.
            </div>
          </div>
          <div class="row" style="margin-top:20px;">
            <div class="col-md-2">
              SEARCH
              <input type="text" id="inp_code_search" class="form-control">
            </div>
            <div class="col-md-2">
              PROCESS<br>
              <button class="btn btn-primary" id='btn_go_codesearch'>GO</button>
            </div>
          </div>

        </div>

        <div class="container" style="margin-top:30px;">
          <?php echo load_progress("progress_codesearch"); ?>
          <div id="report_codesearch_view"></div>
        </div>
      </div>
    </div>
    <!-- en of by codesearch -->

  </div>

</div>

<script>

  $("#inp_make").change(function(){
      var make = $("#inp_make").val();

      if(make == "") return false;

      $.ajax({
          url       : "<?php echo base_url();?>index.php/external/reports/application/get_model",
          type      : 'post',
          dataType  : 'html',
          data      :  {make:make},
          success   :  function(respons){
              var responsedata = $.parseJSON(respons);

              $("#inp_model").empty();

              $('#inp_model').append($('<option>', {
                value: "",
                text: "-"
              }));

              for(i=0;i<responsedata.data.length;i++){
                $('#inp_model').append($('<option>', {
                  value: responsedata.data[i].model,
                  text: responsedata.data[i].model
                }));
              }

              $("#inp_model").removeAttr("disabled");
              clear_year();
              clear_engine();
          }
      });
  })
  //--

  $("#inp_model").change(function(){
      var make = $("#inp_make").val();
      var model = $("#inp_model").val();

      if(make == "") return false;

      $.ajax({
          url       : "<?php echo base_url();?>index.php/external/reports/application/get_year",
          type      : 'post',
          dataType  : 'html',
          data      :  {make:make, model:model},
          success   :  function(respons){
              var responsedata = $.parseJSON(respons);

              $("#inp_year").empty();

              $('#inp_year').append($('<option>', {
                value: "",
                text: "-"
              }));

              for(i=0;i<responsedata.data.length;i++){
                $('#inp_year').append($('<option>', {
                  value: responsedata.data[i].year,
                  text: responsedata.data[i].year
                }));
              }

              $("#inp_year").removeAttr("disabled");
          }
      });
  })
  //--

  $("#inp_year").change(function(){
      var make = $("#inp_make").val();
      var model = $("#inp_model").val();
      var year = $("#inp_year").val();

      if(make == "") return false;

      $.ajax({
          url       : "<?php echo base_url();?>index.php/external/reports/application/get_engine",
          type      : 'post',
          dataType  : 'html',
          data      :  {make:make, model:model, year:year},
          success   :  function(respons){
              var responsedata = $.parseJSON(respons);

              $("#inp_engine").empty();

              $('#inp_engine').append($('<option>', {
                value: "",
                text: "-"
              }));

              for(i=0;i<responsedata.data.length;i++){
                $('#inp_engine').append($('<option>', {
                  value: responsedata.data[i].enginee,
                  text: responsedata.data[i].enginee
                }));
              }

              $("#inp_engine").removeAttr("disabled");
          }
      });
  })
  //--

  function clear_year(){
      $("#inp_year").empty();
      $("#inp_year").attr("disabled", "disabled");
  }
  //---

  function clear_engine(){
      $("#inp_engine").empty();
      $("#inp_engine").attr("disabled", "disabled");
  }
  //---

  $("#btn_go_appsearch").click(function(){
      var make = $("#inp_make").val();

      if(make == ""){
          show_error("You have to choose MAKE");
          return false;
      }
      else{
          $('#report_appsearch_view').html("Loading, Please wait...");

          var model = $("#inp_model").val();
          var year = $("#inp_year").val();
          var engine = $("#inp_engine").val();

          $.ajax({
              url       : "<?php echo base_url();?>index.php/external/reports/application/get_report_appsearch",
              type      : 'post',
              dataType  : 'html',
              data      :  {make:make, model:model, year:year, engine:engine},
              success   :  function(respons){
                  $('#progress_appsearch').hide();
                  $('#report_appsearch_view').fadeIn("5000");
                  $("#report_appsearch_view").html(respons);
              }
          });
      }
  })
  //---

  $("#btn_go_codesearch").click(function(){
      var search = $("#inp_code_search").val();

      if(search == ""){
          show_error("You have to fill SEARCH");
          return false;
      }
      else{
          $('#report_codesearch_view').html("Loading, Please wait...");

          $.ajax({
              url       : "<?php echo base_url();?>index.php/external/reports/application/get_report_codesearch",
              type      : 'post',
              dataType  : 'html',
              data      :  {search:search},
              success   :  function(respons){
                  $('#progress_appsearch').hide();
                  $('#report_codesearch_view').fadeIn("5000");
                  $("#report_codesearch_view").html(respons);
              }
          });
      }
  })
  //---

</script>
