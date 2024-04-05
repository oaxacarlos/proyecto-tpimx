<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Backorder
</div>

<div class="container-fluid" style="margin-top:20px">
  <div class="row">
    <div class="col-3">
      <span class="badge badge-primary">Salesman</span>
      <select id="dsh_salesman_id" name="dsh_salesman_id" class="form-control">
        <?php
          foreach($var_salesman_data as $row){
            echo "<option value='".$row["slscode"]."'>".$row["slsname"]."</option>";
          }
        ?>
      </select>
    </div>
    <div class="col-1">
      <button class="btn btn-primary" id="btn_go_backorder" style="margin-top:18px;">GO</button>
    </div>
  </div>
</div>

<div class="container-fluid">
  <ul class="nav nav-tabs" style="margin-top:20px;">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" data-toggle="tab" href="#bycustomers">By Customers</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" data-toggle="tab" href="#byitems">By Items</a>
    </li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="bycustomers">
      <div class="container-fluid" style="margin-top:20px;">
        <?php echo load_progress("progr_list_backorder"); ?>
        <div id="list_backorder"></div>
      </div>
    </div>

    <div class="tab-pane fade" id="byitems">
      <div class="container-fluid" style="margin-top:20px;">
        <?php echo load_progress("progr_list_backorder_by_items"); ?>
        <div id="list_backorder_items"></div>
      </div>
    </div>

  </div>
</div>



<script>

$("#btn_go_backorder").click(function(){
    var slscode = $("#dsh_salesman_id").val();

    load_backorder(slscode);
    load_backorder_items(slscode);
})
//---

function load_backorder(slscode){
    $('#progr_list_backorder').show();
    $("#list_backorder").hide();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/backorder/get_list",
        type      : 'post',
        dataType  : 'html',
        data      : {slscode:slscode},
        success   :  function(respons){
            $('#progr_list_backorder').hide();
            $('#list_backorder').fadeIn("3000");
            $("#list_backorder").html(respons);
        }
    });
}
//--

function load_backorder_items(slscode){
    $('#progr_list_backorder_by_items').show();
    $("#list_backorder_items").hide();
    $.ajax({
        url       : "<?php echo base_url();?>index.php/sales/backorder/get_list_by_items",
        type      : 'post',
        dataType  : 'html',
        data      : {slscode:slscode},
        success   :  function(respons){
            $('#progr_list_backorder_by_items').hide();
            $('#list_backorder_items').fadeIn("3000");
            $("#list_backorder_items").html(respons);
        }
    });
}



</script>
