<style>
  .file-select {
    position: relative;
    display: inline-block;
    cursor: pointer;
  }

  .file-select::before {
    background-color: green;
    cursor: pointer;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 3px;
    content: 'Seleccionar';
    /* testo por defecto */
    position: absolute;
    left: 0;
    right: 0;
    top: 10px;
    bottom: 0;
  }

  .file-select input[type="file"] {
    opacity: 0;
    width: 100px;
    height: 20px;
    display: inline-block;
    cursor: pointer;
  }
</style>

<script>
  $(document).ready(function () {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'excel',
          title: 'Stock-Inventory'
        }
      ],
    });
    $("#inp_delivery_deadline").datetimepicker({
      timepicker: false,
      format: 'Y-m-d'
    });
    $("#inp_delivery_deadline").datetimepicker({
      timepicker: false,
      format: 'Y-m-d'
    });
    $.datetimepicker.setLocale('en');
  });
</script>
<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
  New Purchase Order
</div>
<div class="row" style="margin-top:20px; margin-right:10px; margin-left:10px;">
  <div class="col-lg-2 col-sm-12"> Date
    <input type="text" class="form-control" id="inp_doc_date" value="<?php echo date('Y-m-d'); ?>" readonly>
  </div>
  <div class="col-lg-2 col-sm-12"> Location
    <?php
    $user_plant = get_plant_code_user();
    $new_user_plant = explode(',', $user_plant);

    echo "<select id='inp_location' class='form-control'>";
    foreach ($var_location as $row) {
        for ($i = 0; $i < count($new_user_plant); ++$i) {
            $temp = trim($new_user_plant[$i], "\'");
            if ($row['code'] == $temp) {
                echo "<option value='".$row['code']."'>".$row['name'].'</option>';
            }
        }
    }
    echo '</select>';
    ?>
  </div>
  <div class="col-lg-3 col-sm-12"> Delivery To
    <select name='user_list' id='inp_delivery_to' class="form-control">
      <option value='-'>-</option>
      <?php
      foreach ($user_list as $row) {
          echo "<option value='".$row['user_id']."'>".$row['name'].'</option>';
      }
      ?>
    </select>
  </div>

  <div class="col-lg-2 col-sm-12"> delivery deadline
    <input type='text' name='datepicker_check' value="<?php echo date('Y-m-d'); ?>" id='inp_delivery_deadline'
      class='required form-control' placeholder='Period From'>
  </div>
  <div class="col-lg-2 col-sm-12"> Urgent
    <input type="checkbox" class="form-control" id="inp_urgent">
  </div>
  <div class="col-lg-4 col-sm-12"> Deparment
    <select class="form-control" name="" id="inp_choosen_depart">
      <option value='-'>-</option>
      <?php
      foreach ($v_list_department as $row) {
          echo "<option value='".$row['depart_code']."'>".$row['depart_name'].'</option>';
      }
      ?>
    </select>
  </div>
  <div class="col-lg-5 col-sm-12"> Shopping Purpose
    <input type="text" class="form-control" id="inp_shopping_pur">
  </div>
</div>
<div class="row col-lg-11 col-sm-12" style="margin-top:20px; margin-left:10px;">
  <table class="table table-bordered table-striped" id="tbl_item">
    <thead>
      <tr>
        <th colspan='7'>Search Item<input type="text" id="search_item" class="form-control"
            onkeypress='return get_item(event)'></th>
        <th colspan='2'>
          <div class=' form-group row justify-content-center'>
            <button class="btn-lg btn-primary" id="btn_process">PROCESS</button>
          </div>
        </th>
      </tr>
      <tr class="table-info">
        <th>Code</th>
        <th>Description</th>
        <th>Uom</th>
        <th>Loc</th>
        <th>Reference Img</th>
        <th>Reference Link</th>
        <th>Remarks</th>
        <th>Qty</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<?php
// funcion de autoacompletar
unset($autocomplete);
$i = 0;
foreach ($var_item as $row) {
    $value2 = $row['item_code'].'|'.$row['description'].'|'.$row['uom'];
    $autocomplete[$i] = $value2;
    ++$i;
}
$js_array_autocomplete = json_encode($autocomplete);
?>
<script>
  var glb_index = 0;
  var autocomplete = <?php echo $js_array_autocomplete; ?>;
  $(document).ready(function () {
    $("#search_item").autocomplete({ source: autocomplete });

    $("#search_item").keyup(function () {
      this.value = this.value.toLocaleUpperCase();
    });
  })
  // funcion para obtener el item,descripcion,PZA, 
  function get_item(event) {
    if (event.keyCode == 13) {
      if ($("#search_item").val() == "") return false;

      var item = $("#search_item").val();
      var new_item = item.split("|");
      f_add_row(new_item[0], new_item[1], new_item[2]);
      $("#search_item").val("");
      $("#search_item").focus();
    }
  }
  // Funcion agregar el item alas filas
  function f_add_row(item_code, desc, uom) {
    var text = "";
    text = text + "<tr id='tbl_item_row_" + glb_index + "'>";
    text = text + "<td style='display:none;'id='tbl_line_" + glb_index + "'>" + glb_index + "</td>";
    text = text + "<td id='tbl_item_code_" + glb_index + "'>" + item_code + "</td>";
    text = text + "<td><input type='text' id='tbl_item_desc_" + glb_index + "' value='" + desc + "' class='form-control'></td>";
    text = text + "<td id='tbl_item_uom_" + glb_index + "'>" + uom + "</td>";
    text = text + "<td id='tbl_item_loc_" + glb_index + "'>" + $("#inp_location").val() + "</td>";
    text = text + "<td class='file-select'><input accept='image/*' type='file' id='tbl_item_img_" + glb_index + "' class='form-control btn btn-info'></td>";
    text = text + "<td><input type='text' id='tbl_item_link_" + glb_index + "' class=' form-control'></td>";
    text = text + "<td><input type='text' id='tbl_item_remarks_" + glb_index + "' class='form-control'></td>";
    text = text + "<td><input type='text' id='tbl_item_qty_" + glb_index + "' class='form-control' onkeypress='return isNumberKey(event)'></td>";
    text = text + "<td><button class='btn btn-danger btn-sm' onclick=f_delete_item(" + glb_index + ")>X</button></td>";
    text = text + "</tr>";
    $("#tbl_item").append(text);
    glb_index++;
  }
  function f_delete_item(i) { $("#tbl_item_row_" + i).remove(); }

  $("#btn_process").click(function () {

    // check item not blank
    if (!f_check_item_not_blank()) {
      show_error("No Item");
      return false;
    }

    // check QTY,DESC,REMARKS en la tabla
    var check_desc = f_check_if_all_desc_has_filled();
    if (check_desc != true) {
      show_error("No Tiene DESC del Item " + $("#tbl_item_code_" + check_desc).text());
      return false;
    }
    var check_qty = f_check_if_all_qty_has_filled();
    if (check_qty != true) {
      show_error("No Tiene QTY en Item " + $("#tbl_item_code_" + check_qty).text());
      return false;
    }

    var check_remarks = f_check_if_all_remarks_has_filled();
    if (check_remarks != true) {
      show_error("No Tiene REMARKS del Item " + $("#tbl_item_code_" + check_remarks).text());
      return false;
    }

    // process
    f_process();
  })
  function f_check_item_not_blank() {
    if (glb_index == 0) return false;

    check = 0;
    for (i = 0; i <= glb_index; i++) {
      if (check_if_id_exist($("#tbl_item_code_" + i))) {
        check = 1;
        break;
      }
    }

    if (check == 1) return true;
    else return false;
  }
  // funcion para verificar si DESC esta en blanco
  function f_check_if_all_desc_has_filled() {
    check = 1;
    for (i = 0; i <= glb_index; i++) {
      if (check_if_id_exist($("#tbl_item_desc_" + i))) {
        if ($("#tbl_item_desc_" + i).val() == '') {
          check = 0;
          break;
        }
      }
    }

    if (check == 1) return true;
    else return i;
  }
  // funcion para verificar si REMAKS esta en blanco
  function f_check_if_all_remarks_has_filled() {
    check = 1;
    for (i = 0; i <= glb_index; i++) {
      if (check_if_id_exist($("#tbl_item_remarks_" + i))) {
        if ($("#tbl_item_remarks_" + i).val() == '') {
          check = 0;
          break;
        }
      }
    }

    if (check == 1) return true;
    else return i;
  }
  // funcion para verificar si QTY esta en blanco
  function f_check_if_all_qty_has_filled() {
    check = 1;
    for (i = 0; i <= glb_index; i++) {
      if (check_if_id_exist($("#tbl_item_qty_" + i))) {
        if ($("#tbl_item_qty_" + i).val() == '') {
          check = 0;
          break;
        }
      }
    }

    if (check == 1) return true;
    else return i;
  }

  function f_process() {
    if ($('#inp_delivery_to').val() == "-") { show_error("You have to type delivery to"); }
    else if ($('#inp_choosen_depart').val() == "-") { show_error("You have to type which department"); }
    else if ($('#inp_shopping_pur').val() == "") { show_error("You have to type proposal"); }
    else{
    swal({
      input: 'textarea',
      inputPlaceholder: 'Type your message here',
      showCancelButton: true,
      confirmButtonText: 'OK'
    }).then(function (result) {
      if (result.dismiss == "cancel") { }
      else {
        if (result.value == "") { show_error("You have to type message"); }
         
        else {
          var message = result.value;
          swal({
            title: "Are you sure ?",
            html: "Proceed this Purchase Order",
            type: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
          }).then(function (result) {
            // get all data
            var doc_date = $('#inp_doc_date').val();
            var h_loc = $("#inp_location").val();
            var delivery_to = $('#inp_delivery_to').val();
            var delivery_deadline = $('#inp_delivery_deadline').val();
            var urgent = document.getElementById('inp_urgent').checked;
            var choosen_depart = $('#inp_choosen_depart').val();
            var shopping_pur = $('#inp_shopping_pur').val();

            var item = []; var desc = []; var uom = []; var loc = []; var qty = [];
            var src_img = []; var src_link = []; var src_remaks = []; var line_img = [];

            var ii = 0;
            for (i = 0; i < glb_index; i++) {
              if (check_if_id_exist($("#tbl_item_code_" + i))) {

                item[ii] = $("#tbl_item_code_" + i).text();
                line_img[ii] = $("#tbl_line_" + i).text();
                desc[ii] = $("#tbl_item_desc_" + i).val();
                uom[ii] = $("#tbl_item_uom_" + i).text();
                loc[ii] = $("#tbl_item_loc_" + i).text();
                qty[ii] = $("#tbl_item_qty_" + i).val();
                src_img[ii] = $('#tbl_item_img_' + i).val();
                src_link[ii] = $("#tbl_item_link_" + i).val();
                src_remaks[ii] = $("#tbl_item_remarks_" + i).val();
                ii++;
              }
            }
            //---
            $.ajax({
              url: "<?php echo base_url(); ?>index.php/purchasing/po_request/create_new",
              type: "post",
              dataType: 'html',
              data: {
                item: JSON.stringify(item),
                desc: JSON.stringify(desc),
                uom: JSON.stringify(uom),
                loc: JSON.stringify(loc),
                qty: JSON.stringify(qty),
                src_img: JSON.stringify(src_img),
                line_img: JSON.stringify(line_img),
                src_link: JSON.stringify(src_link),
                src_remaks: JSON.stringify(src_remaks),
                doc_date: doc_date,
                h_loc: h_loc,
                delivery_to: delivery_to,
                delivery_deadline: delivery_deadline,
                urgent: urgent,
                choosen_depart: choosen_depart,
                shopping_pur: shopping_pur,
                message: message,
              },
              success: function (data) {
                var responsedata = $.parseJSON(data);
                if (responsedata.status == 1) {
                  // test solo una imagen
                  for (i = 0; i < glb_index; i++) {
                    var data_img = responsedata.img_data[i].request_img; // 
                    var data_t = Object.values(data_img);
                    // console.log(data_t);
                    console.log(responsedata.img_data[i]);
                    
                    if (check_if_id_exist($("#tbl_item_code_" + i))) {
                      var line_img = $("#tbl_line_" + i).text();
                      console.log(line_img);
                      var file_data = $('#tbl_item_img_' + i).prop('files')[0];
                      var form_data = new FormData();
                      form_data.append('file', file_data);
                      form_data.append('file_name', data_img);
                      var doc_no_h = responsedata.doc_no_h;
                      $.ajax({
                        url: "<?php echo base_url(); ?>index.php/purchasing/po_request/upload_img",
                        type: "post",
                        data: form_data, 
                        contentType: false,
                        cache: false,
                        processData: false,
                        
                        success: function (data) {
                          if (responsedata.status == 1) {
                                              //  
                  }
                  else if (responsedata.status == 0) {
                  Swal('Error!', responsedata.msg, 'error');
                  $('#loading_body').hide();
                }
                }
                      })

              }
            }
            swal({
                      title: responsedata.msg,
                      type: "success", confirmButtonText: "OK",
                    }).then(function () {
                      setTimeout(function () {
                        $('#loading_body').hide();
                        window.location.href = "<?php echo base_url(); ?>index.php/purchasing/po_request";
                      }, 100)
                    });
            }
            }
          })

          })
        }
      }
    })
  }
  }
</script>