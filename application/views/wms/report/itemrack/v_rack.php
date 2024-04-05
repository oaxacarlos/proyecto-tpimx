<div class="container-fluid">
  <div class="row">
        <table class="table table-bordered table-sm table-striped">
          <tr>
            <td><i class='bi bi-clipboard2-data-fill'></i> Extraction</td>
            <td><i class='bi bi-basket-fill'></i></i> Putaway</td>
            <td><i class='bi bi-check2-all'></i></i> Available</td>
            <td><i class='bi bi-cart-fill'></i> Picking</td>
          </tr>
        </table>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
        <table class="table table-bordered table-sm table-striped">
          <thead>
            <tr>
              <th colspan="8" style="text-align:center;" class="table-warning"><?php echo $item_code ?></th>
            </tr>
            <tr>
              <th>Loc</th>
              <th>Zone</th>
              <th>Area</th>
              <th>Rack</th>
              <th>Bin</th>
              <th>Qty</th>
              <th>Status</th>
              <th>Uom</th>
            </tr>
          </thead>
          <tbody>
          <?php

          $location_code["WH2"] = "table-danger";
          $location_code["WH3"] = "table-info";

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

                echo "<td id='tbl_pick_uom_".$i."'>PZA</td>";
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
