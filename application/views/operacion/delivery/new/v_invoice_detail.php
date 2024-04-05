<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
        "paging": false,
    });
});
</script>

<div class="container-fluid" style="margin-top:20px; margin-bottom:20px;">

    <button class="btn btn-primary btn-success" id="btn_add">ADD</button>

</div>

<table id="DataTable" class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th></th>
        <th>Date</th>
        <th>Doc No</th>
        <th>Ext Doc</th>
        <th>Cust No</th>
        <th>Cust Name</th>
        <th>Address</th>
        <th>Address 2</th>
        <th>City</th>
        <th>State</th>
        <th>Post Code</th>
        <th>Country</th>
        <th>Qty</th>
        <th>Amount Exc TAX</th>
        <th>Amount Inc TAX</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $i=0;
        foreach($var_invoices as $row){
            echo "<tr>";
              echo "<td><input type='checkbox' id='check_".$i."'></td>";
              echo "<td id='tbl_invoice_doc_date_".$i."'>".$row["doc_date"]."</td>";
              echo "<td id='tbl_invoice_doc_no_".$i."'>".$row["doc_no"]."</td>";
              echo "<td id='tbl_invoice_ext_doc_".$i."'>".$row["external_document"]."</td>";
              echo "<td id='tbl_invoice_cust_no_".$i."'>".$row["bill_cust_no"]."</td>";
              echo "<td id='tbl_invoice_cust_name_".$i."'>".$row["bill_cust_name"]."</td>";
              echo "<td id='tbl_invoice_addr_".$i."'>".$row["ship_to_addr"]."</td>";
              echo "<td id='tbl_invoice_addr2_".$i."'>".$row["ship_to_addr2"]."</td>";
              echo "<td id='tbl_invoice_city_".$i."'>".$row["ship_to_city"]."</td>";
              echo "<td id='tbl_invoice_county_".$i."'>".$row["ship_to_county"]."</td>";
              echo "<td id='tbl_invoice_post_code_".$i."'>".$row["ship_to_post_code"]."</td>";
              echo "<td id='tbl_invoice_ctr_region_".$i."'>".$row["ship_to_ctry_region_code"]."</td>";
              echo "<td id='tbl_invoice_qty_".$i."'>".$row["qty"]."</td>";
              echo "<td id='tbl_invoice_amount_no_tax_".$i."'>".sprintf('%0.2f', $row["amount_without_tax"])."</td>";
              echo "<td id='tbl_invoice_amount_with_tax_".$i."'>".sprintf('%0.2f', $row["amount_including_vat"])."</td>";
            echo "</tr>";
            $i++;
        }
      ?>
    </tbody>

  </table>

  <input type="text" id="inp_invoice_total_row" value="<?php echo count($var_invoices); ?>">

<script>

invc_conflict = "";

$("#btn_add").click(function(){
    var table = $('#DataTable').DataTable();
    table.search("").draw();

    var total_row = parseInt($("#inp_invoice_total_row").val());

    invc_conflict = "";

    for(i=0;i<total_row;i++){
      if(check_if_id_exist('#check_'+i)){
        if($('#check_'+i).is(':checked') == true){
            f_add_table(i);
        }
      }
    }

    if(invc_conflict!=""){
        show_warning("Tienes conflicto con Factura = "+invc_conflict+" \n porque ya se aplicó a otros Documentos, por lo que debe poner la RAZÓN en REMARKS");
    }

    $('input:checkbox').removeAttr('checked');
    $("#myModalFactura").modal("toggle");
})
//---

function f_add_table(i){

    var check = 0;

    for(j=0;j<glb_idx;j++){
      if(check_if_id_exist("#table_doc_no_"+j)){
        if($("#table_doc_no_"+j).val() == $("#tbl_invoice_doc_no_"+i).text()){
            check = 1;
            break;
        }
      }
    }

    // check if the invoice has been applied to another documents
    check_document = 0;
    required_remark = '';
    required_remark_text = '';
    invc_doc_no_temp = $("#tbl_invoice_doc_no_"+i).text();


    $.ajax({
        url  : "<?php echo base_url();?>index.php/operacion/delivery/newdelivery/check_invc_no_has_applied",
        type : "post",
        dataType  : 'json',
        async : false,
        data : {doc_no:invc_doc_no_temp},
        success: function(data){
            result = $.parseJSON(data);
            if(data == true){
                required_remark = "*";
                required_remark_text = "Mandatory";
                invc_conflict = invc_conflict + invc_doc_no_temp + ", ";
            }
        }
    })
    //---

    if(check == 0){
        table = "";
        table = table + "<tr id='table_result_"+glb_idx+"'>";
          table = table + "<td><input type='text' id='table_doc_type_"+glb_idx+"' class='form-control' value='1' disabled></td>";

          table = table + "<td><input type='text' id='table_doc_no_"+glb_idx+"' class='form-control' value='"+$("#tbl_invoice_doc_no_"+i).text()+"' disabled></td>";

          table = table + "<td><input type='text' id='table_doc_date_"+glb_idx+"' class='form-control' value='"+$("#tbl_invoice_doc_date_"+i).text()+"' disabled></td>";

          table = table + "<td><input type='text' id='table_so_ref_"+glb_idx+"' class='form-control' value='"+$("#tbl_invoice_ext_doc_"+i).text()+"' disabled></td>";

          table = table + "<td><input type='text' id='table_cust_no_"+glb_idx+"' class='form-control' value='"+$("#tbl_invoice_cust_no_"+i).text()+"' disabled></td>";

          table = table + "<td><input type='text' id='table_subtotal_"+glb_idx+"' class='form-control' value='"+$("#tbl_invoice_amount_no_tax_"+i).text()+"' disabled></td>";

          table = table + "<td><input type='text' id='table_total_"+glb_idx+"' class='form-control' value='"+$("#tbl_invoice_amount_with_tax_"+i).text()+"' disabled></td>";

          table = table + "<td><input type='text' id='table_remarks_"+glb_idx+"' class='form-control' placeholder='Remarks'><span id='table_required_remarks_"+glb_idx+"'>"+required_remark+"</span><span id='table_required_remarks_text_"+glb_idx+"'>"+required_remark_text+"</span></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_cust_name_"+glb_idx+"' value='"+$("#tbl_invoice_cust_name_"+i).text()+"'></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_address_"+glb_idx+"' value='"+$("#tbl_invoice_addr_"+i).text()+"'></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_address2_"+glb_idx+"' value='"+$("#tbl_invoice_addr2_"+i).text()+"'></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_city_"+glb_idx+"' value='"+$("#tbl_invoice_city_"+i).text()+"'></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_state_"+glb_idx+"' value='"+$("#tbl_invoice_county_"+i).text()+"'></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_post_code_"+glb_idx+"' value='"+$("#tbl_invoice_post_code_"+i).text()+"'></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_country_"+glb_idx+"' value='"+$("#tbl_invoice_ctr_region_"+i).text()+"'></td>";

          table = table + "<td style='display:none;'><input type='text' id='table_qty_"+glb_idx+"' value='"+$("#tbl_invoice_qty_"+i).text()+"'></td>";

          table = table + "<td><button class='btn btn-sm btn-danger' id='btn_delete_table_"+glb_idx+"' onclick=f_delete_line("+glb_idx+")>X</button></td>";

        table = table + "</tr>";

        glb_idx++;

        $("#table_detail tbody").append(table);
    }

}
//--

function f_check_data_in_table(idx){

    var check = 0;

    for(i=0;i<glb_idx;i++){
      if(check_if_id_exist("#table_doc_no_"+i)){
        if($("#table_doc_no_"+i).text() == $("#tbl_invoice_doc_no_"+idx).text()){
            check = 1;
            break;
        }
      }
    }

    if(check == 1) return true;
    else return false;

}
//---

</script>
