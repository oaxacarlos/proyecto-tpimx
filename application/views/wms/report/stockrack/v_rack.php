
<?php
  unset($stock_summary);

  foreach($var_item_loc as $row){
      if(!isset($stock_summary[$row["location_code"]][$row["statuss"]]))
        $stock_summary[$row["location_code"]][$row["statuss"]] = $row["total"];
      else $stock_summary[$row["location_code"]][$row["statuss"]] += $row["total"];
  }

  $location_code["WH2"] = "table-danger";
  $location_code["WH3"] = "table-info";
  $location_code["WH4"] = "table-warning";

?>

  <div class="row">
    <div class="container">
      ITEM : <?php echo $item_code;?>
    </div>
  </div>

  <div class="row">
      <div class="container">
        <table class="table table-bordered table-sm table-striped">
          <tr>
            <td><i class='bi bi-clipboard2-data-fill'></i> Extraction</td>
            <td><i class='bi bi-basket-fill'></i></i> Putaway</td>
            <td><i class='bi bi-check2-all'></i></i> Available</td>
            <td><i class='bi bi-cart-fill'></i>Picking</td>
            <td><i class='bi bi-boxes'></i>Transfer BIN <br>(Not Transfer WH)</td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td>
              <table class="table table-bordered table-sm" width="100%">
              <?php
                $status = 1;
                foreach($var_location as $row){
                    if(isset($stock_summary[$row["code"]][$status])) $total = $stock_summary[$row["code"]][$status];
                    else $total = 0;

                    if($total > 0){
                      echo "<tr>";
                        echo "<td class='".$location_code[$row['code']]."'>".$row["code"]."</td>";
                        echo "<td>".$total."</td>";
                        echo "</tr>";
                    }
                }
              ?>
            </table>
            </td>
            <td>
              <table class="table table-bordered table-sm" width="100%">
              <?php
                $status = 2;
                foreach($var_location as $row){
                    if(isset($stock_summary[$row["code"]][$status])) $total = $stock_summary[$row["code"]][$status];
                    else $total = 0;

                    if($total > 0){
                      echo "<tr>";
                        echo "<td class='".$location_code[$row['code']]."'>".$row["code"]."</td>";
                        echo "<td>".$total."</td>";
                        echo "</tr>";
                    }
                }
              ?>
            </table>
            </td>
            <td>
              <table class="table table-bordered table-sm" width="100%">
              <?php
                $status = 4;
                foreach($var_location as $row){
                    if(isset($stock_summary[$row["code"]][$status])) $total = $stock_summary[$row["code"]][$status];
                    else $total = 0;

                    if($total > 0){
                      echo "<tr>";
                        echo "<td class='".$location_code[$row['code']]."'>".$row["code"]."</td>";
                        echo "<td>".$total."</td>";
                        echo "</tr>";
                    }
                }
              ?>
            </table>
            </td>
          </tr>
        </table>
    </div>
  </div>

  <div class="row">
    <div class="container" style="margin-top:10px;">
        <table class="table table-bordered table-sm table-striped">
          <thead>
            <tr>
              <th>Loc</th>
              <th>Zone</th>
              <th>Area</th>
              <th>Rack</th>
              <th>Bin</th>
              <th>Qty</th>
              <th>Status</th>
              <th>Uom</th>
              <th colspan='2'>Action (PRINT)</th>
            </tr>
          </thead>
          <tbody>
          <?php

          $i=0;
          foreach($var_item_loc as $row){
            if($row['statuss']=='-1'){
              echo "<tr>";
                echo "<td colspan='5'>No Rack Yet</td>";
                echo "<td id='tbl_pick_qty_code_".$i."'>".$row['total']."</td>";
                echo "<td><i class='bi bi-clipboard2-data-fill'></i></td>";
                echo "<td id='tbl_pick_uom_".$i."'>PZA</td>";
              echo "</tr>";
            }
            else if($row['statuss']=='0'){
              echo "<tr>";
                echo "<td colspan='5'>No Rack Yet</td>";
                echo "<td id='tbl_pick_qty_code_".$i."'>".$row['total']."</td>";
                echo "<td><i class='bi bi-basket-fill'></td>";
                echo "<td id='tbl_pick_uom_".$i."'>PZA</td>";
              echo "</tr>";
            }
            else {
              echo "<tr>";
                echo "<td id='tbl_pick_loc_code_".$i."' class='".$location_code[$row['location_code']]."'>".$row['location_code']."</td>";
                echo "<td id='tbl_pick_zone_code_".$i."'>".$row['zone_code']."</td>";
                echo "<td id='tbl_pick_area_code_".$i."'>".$row['area_code']."</td>";
                echo "<td id='tbl_pick_rack_code_".$i."'>".$row['rack_code']."</td>";
                echo "<td id='tbl_pick_bin_code_".$i."'>".$row['bin_code']."</td>";
                echo "<td id='tbl_pick_qty_code_".$i."'>".$row['total']."</td>";

                if($row['statuss']=='1'){ echo "<td><i class='bi bi-check2-all'></i></td>";}
                else if($row['statuss']=='2'){ echo "<td><i class='bi bi-cart-fill'></i></td>"; }
                else if($row['statuss']=='4'){ echo "<td><i class='bi bi-boxes'></i></td>"; }

                echo "<td id='tbl_pick_uom_".$i."'>PZA</td>";

                if($row["statuss"] == '1'){
                  if(isset($_SESSION['user_permis']["14"])){
                    if($row['area_code']!="X"){
                      echo "<td><a href='".base_url()."index.php/wms/barcode/print_barcode_by_item_code_status?id=".$row['item_code']."&status=1&loc=".$row['location_code']."&zone=".$row['zone_code']."&area=".$row['area_code']."&rack=".$row['rack_code']."&bin=".$row['bin_code']."' class='btn btn-success btn-sm' target='_blank'>BARCODE</a></td>";

                      echo "<td><a href='".base_url()."index.php/wms/barcode/print_masterbarcode_by_item_code_status?id=".$row['item_code']."&status=1&loc=".$row['location_code']."&zone=".$row['zone_code']."&area=".$row['area_code']."&rack=".$row['rack_code']."&bin=".$row['bin_code']."' class='btn btn-warning btn-sm' target='_blank'>MASTER</a></td>";
                    }
                  }
                }

              echo "</tr>";
            }
            $i++;
          }
          ?>
          </tbody>
        </table>

    </div>
    </div>


</script>
