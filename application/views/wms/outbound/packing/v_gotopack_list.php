<style>

</style>

<div class="modal" id="myModalPick" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Picking</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modal_detail_pick"></div>
    </div>
  </div>
</div>

  <div class="row fontsize">
    <div class="container" style="margin-top:10px;">
        <table class="table table-bordered table-sm table-striped">
          <thead>
            <tr>
              <th>Item Code</th>
              <th>Desc</th>
              <th>Qty</th>
              <th>Uom</th>
              <th>S/N</th>
              <th>Action</th>
            </tr>
          </thead>
          <?php
          $i=0;
          foreach($var_pick_list as $row){
            echo "<tr>";
              echo "<td id='tbl_list_item_".$i."'>".$row['item_code']."</td>";
              echo "<td id='tbl_list_desc_".$i."'>".$row['description']."</td>";
              echo "<td id='tbl_list_qtypick_".$i."'>".convert_number2($row['qty_to_picked'])."</td>";
              echo "<td id='tbl_list_uom_".$i."'>".$row['uom']."</td>";
              echo "<td id='tbl_list_sn_".$i."'>".$row['serial_number_scan']."</td>";
              echo "<td id='tbl_list_index_".$i."'>".$i."</td>";
              echo "<td id='tbl_list_show_".$i."'>show</td>";
            echo "</tr>";
            $i++;
          }
          ?>
        </table>
    </div>
    </div>

    <input type='hidden' value='<?php echo $i ?>' id='total_row_list'>

<script>


</script>
