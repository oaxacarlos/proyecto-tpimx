<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Count'
          }
        ],
        pageLength : 25,
        order: [[1, 'desc']]
    });
});
</script>

<style>
  tr#stockcountdata{
    font-size:14px;
  }

  td#number{
    text-align: right;
  }
</style>

<table class="table table-bordered table-sm table-striped" id="DataTable">
  <thead>
    <tr id="stockcountdata">
      <th>DOC DATE</th>
      <th>CREATED AT</th>
      <th>ITEM</th>
      <th>WHS</th>
      <th>LOCATION</th>
      <th>QTY</th>
      <th>TYPE</th>  <!-- // 2023-09-12 -->
      <th>USER</th>
      <?php if(isset($_SESSION['user_permis']["36"])){ ?>
        <th style='width:10px;'>ACTION</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php

      $color["WH2"] = "danger";
      $color["WH3"] = "info";

      foreach($var_report as $row){
          echo "<tr id='stockcountdata_".$row["id"]."'>";
            echo "<td>".$row["doc_date"]."</td>";
            echo "<td>".$row["created_at"]."</td>";
            echo "<td>".$row["item_code"]."</td>";
            echo "<td class='table-".$color[$row["location_code"]]."'>".$row["location_code"]."</td>";
            echo "<td>".combine_location($row["location_code"], $row["zone_code"], $row["area_code"], $row["rack_code"], $row["bin_code"])."</td>";
            echo "<td id='number'>".$row["qty"]."</td>";

            if($row['typee'] == "1") $type_color = "info";
            else $type_color = "warning";

            echo "<td><span class='badge badge-".$type_color."'>".$row["type_name"]."</span></td>";
            echo "<td id='number'>".$row["name"]."</td>";

            if(isset($_SESSION['user_permis']["36"]))
              echo "<td style='text-align:center;'><button class='btn btn-danger btn-sm' onclick=f_delete_data('".$row["id"]."')>X</button></td>";
          echo "</tr>";
      }
    ?>
  </tbody>
</table>

<script>

function f_delete_data(id){
  swal({
    title: "Estas Seguro ?",
    html: "Eliminar el Dato",
    type: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    showLoaderOnConfirm: true,
    closeOnConfirm: false
  }).then(function (result) {

      if(result.value){
        $("#loading_text").text("Eliminando, Espera Por Favor...");
        $('#loading_body').show();

        $.ajax({
            url : "<?php echo base_url();?>index.php/wms/report/stockcount/delete",
            type : 'post',
            dataType : 'html',
            data : {id:id},
            success:function(data){
                responsedata = $.parseJSON(data);

                if(responsedata.status == 1){
                    swal({
                       title: responsedata.msg,
                       type: "success", confirmButtonText: "OK",
                    }).then(function(){
                      setTimeout(function(){
                        $("#stockcountdata_"+id).fadeOut("normal", function() {
                            $("#stockcountdata_"+id).remove();
                        });

                        $('#loading_body').hide();
                      },100)
                    });
                }
                else{
                    show_error("Insert Error");
                }
            }
        })
      }
  })
  //--
}

</script>
