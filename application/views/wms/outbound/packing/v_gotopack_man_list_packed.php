<style>
tr{
  font-size: 12px;
}
</style>


<div class="container-fluid text-right" style="margin-bottom:10px;">
    <a href="<?php echo base_url()?>index.php/wms/outbound/packing/printlistall?id=<?php echo $doc_no_print_all; ?>" target=_blank class='btn btn-warning btn-sm'>Print all Content List</a>
</div>

    <table class="table table-bordered table-sm table-striped">
      <thead>
        <tr>
          <th>Doc No</th>
          <th>DocDate</th>
          <th>WHS</th>
          <!--<th>Item Code</th>
          <th>Desc</th>-->
          <th>Qty Packed</th>
          <th>Uom</th>
          <th>Sent To</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Addr</th>
          <th>Addr2</th>
          <th>City</th>
          <th>PostCode</th>
          <th>County</th>
          <th>Country</th>
          <th>SO/TO</th>
          <th>Remarks</th>
          <th>Print</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i=1;
        $total_row = count($var_list_packed);
        foreach($var_list_packed as $row){
            echo "<tr'>";
              echo "<td>".$row["doc_no"]."</td>";
              echo "<td>".$row["doc_date"]."</td>";
              echo "<td>".$row["src_location_code"]."</td>";
              //echo "<td>".$row["item_code"]."</td>";
              //echo "<td>".$row["description"]."</td>";
              echo "<td>".$row["qty_to_packed"]."</td>";
              echo "<td>".$row["uom"]."</td>";
              echo "<td>".$row["dest_no"]."</td>";
              echo "<td>".$row["dest_name"]."</td>";
              echo "<td>".$row["dest_contact"]."</td>";
              echo "<td>".$row["dest_addr"]."</td>";
              echo "<td>".$row["dest_addr2"]."</td>";
              echo "<td>".$row["dest_city"]."</td>";
              echo "<td>".$row["dest_post_code"]."</td>";
              echo "<td>".$row["dest_county"]."</td>";
              echo "<td>".$row["dest_country"]."</td>";
              echo "<td>".$row["so_no"]."</td>";
              echo "<td>".$i."/".$total_row."</td>";
              echo "<td>
                <a href='".base_url()."index.php/wms/outbound/packing/print?id=".$row['doc_no']."&so=".$row["so_no"]."' target=_blank class='btn btn-info btn-sm' style='font-size:10px;'>Shipping</a><br><br>
                <a href='".base_url()."index.php/wms/outbound/packing/print2?id=".$row['doc_no']."&so=".$row["so_no"]."' target=_blank class='btn btn-info btn-sm' style='font-size:10px;'>Shipping 2</a><br><br>
                <a href='".base_url()."index.php/wms/outbound/packing/printlist?id=".$row['doc_no']."&box=".$i."&row=".$total_row."&so=".$row["so_no"]."' target=_blank class='btn btn-warning btn-sm' style='font-size:10px;'>ContentList</a>
                <a href='".base_url()."index.php/wms/outbound/packing/printlist2?id=".$row['doc_no']."&box=".$i."&row=".$total_row."&so=".$row["so_no"]."' target=_blank class='btn btn-warning btn-sm' style='font-size:10px;'>ContentList 2</a>
              </td>";
            echo "</tr>";
            $i++;
        }
        ?>
      </tbody>
    </table>
