<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}

@media print {
  @page {
    margin: 0 0 0 0;
    size: auto;
  }

  /*top right bottom left */
  body { margin: 0cm 0cm 1cm 0cm; }

  #printPageButton {
      display: none;
    }

}

td#bold{
  font-weight: bold;
  font-size: 12px;
}

td#value{
  padding-bottom:2px;
  font-size: 12px;
}

</style>

<table border="1" style="width:500px; margin-left:30px; margin-top:30px;">
  <tr>
    <td id='bold'>Date</td>
    <td id='value'><?php echo $var_picking_h["created_datetime"]; ?></td>
  </tr>
  <tr>
    <td id='bold'>Warehouse Shipment</td>
    <td id='value'><?php echo $var_picking_h["src_no"]; ?></td>
  </tr>
  <tr>
    <td id='bold'>Document No</td>
    <td id='value'><?php echo $var_picking_h["doc_no"]; ?><br></td>
  </tr>
  <tr>
    <td id='bold'>SO No</td>
    <td id='value'><?php echo $var_picking_h["so_no"]; ?><br></td>
  </tr>
  <tr>
    <td id='bold'>Customer No</td>
    <td id='value'><?php echo $var_picking_h["bill_cust_no"]; ?><br></td>
  </tr>
  <tr>
    <td id='bold'>Customer Name</td>
    <td id='value'><?php echo $var_picking_h["bill_cust_name"]; ?><br></td>
  </tr>
  <tr>
    <td id='bold'>City</td>
    <td id='value'><?php echo $var_picking_h["ship_to_city"]; ?><br></td>
  </tr>
  <tr>
    <td id='bold'>Remarks</td>
    <td id='value'><?php echo $var_picking_h["text1"]; ?><br></td>
  </tr>
  <tr>
    <td id='bold'>Picker</td>
    <td id='value'><?php echo $var_picking_h["assign_name"]; ?><br></td>
  </tr>
</table>

<table border="1" style="width:500px; margin-left:30px; margin-top:30px;">
  <tr>
    <td id='bold'>No</td>
    <td id='bold'>Item</td>
    <td id='bold'>Qty</td>
    <td id='bold'>Uom</td>
    <td id='bold'>Loc</td>
    <td id='bold'>Shipment</td>
  </tr>
  <?php
    $i=1;
    $total_qty = 0;
    foreach($var_picking_d as $row){
        echo "<tr>";
          echo "<td>".$i."</td>";
          echo "<td id='value'>".$row["item_code"]."<br>".$row["description"]."</td>";
          echo "<td id='value'>".$row["qty_to_picked"]."</td>";
          echo "<td id='value'>".$row["uom"]."</td>";

          $temp = $row["location_code"]."-".$row["zone_code"]."-".$row["area_code"]."-".$row["rack_code"]."-".$row["bin_code"];

          echo "<td id='value'>".$temp."</td>";
          echo "<td id='value'>".$row["src_no"]."</td>";

        echo "</tr>";
        $total_qty += $row["qty_to_picked"];
        $i++;
    }

    echo "<tr><td colspan='2' id='value'>TOTAL QTY</td><td id='value'>".$total_qty."</td></tr>";
  ?>
</table>


<button id="printPageButton" onclick="window.print();return false;" / style="margin-top:10px;">PRINT</button>
