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
    <td id='value'><?php echo $var_doc_h["created_datetime"]; ?></td>
  </tr>
  <tr>
    <td id='bold'>Warehouse Shipment</td>
    <td id='value'><?php echo $var_doc_h["doc_no"]; ?></td>
  </tr>
  <tr>
    <td id='value' colspan='2'>Ext Doc : <?php echo $var_doc_h["external_document"] ?></td>
  </tr>
</table>

<table border="1" style="width:500px; margin-left:30px; margin-top:30px;">
  <tr>
    <td id='bold'>SO No</td>
    <td id='bold'>Cust No</td>
    <td id='bold'>Cust Name</td>
    <td id='bold'>City</td>
  </tr>
  <?php
    foreach($var_so as $row){
        echo "<tr>";
          echo "<td id='value'>".$row["so_no"]."</td>";
          echo "<td id='value'>".$row["bill_cust_no"]."</td>";
          echo "<td id='value'>".$row["bill_cust_name"]."</td>";
          echo "<td id='value'>".$row["ship_to_city"]."</td>";
        echo "</tr>";
        echo "<tr><td id='value' colspan='4'>Address : ".$row["ship_to_addr"]."</td></tr>";
        echo "<tr><td id='value' colspan='4'>Address 2 : ".$row["ship_to_addr2"]."</td></tr>";
        echo "<tr><td id='value' colspan='4'>County : ".$row["ship_to_county"]."</td></tr>";
        echo "<tr><td id='value' colspan='4'>Post Code : ".$row["ship_to_post_code"]."</td></tr>";
    }

  ?>
</table>

<table border="1" style="width:500px; margin-left:30px; margin-top:30px;">
  <tr>
    <td id='bold'>No</td>
    <td id='bold'>Item</td>
    <td id='bold'>Qty</td>
    <td id='bold'>Uom</td>
    <td id='bold'>SO</td>
  </tr>
  <?php
    $i=1;
    $total_qty = 0;
    foreach($var_doc_d as $row){
        echo "<tr>";
          echo "<td>".$i."</td>";
          echo "<td id='value'>".$row["item_code"]."<br>".$row["description"]."</td>";
          echo "<td id='value'>".$row["qty_to_ship"]."</td>";
          echo "<td id='value'>".$row["uom"]."</td>";
          echo "<td id='value'>".$row["src_no"]."</td>";
        echo "</tr>";
        $total_qty += $row["qty_to_ship"];
        $i++;
    }

    echo "<tr><td colspan='2' id='value'>TOTAL QTY</td><td id='value'>".$total_qty."</td></tr>";

  ?>
</table>



<button id="printPageButton" onclick="window.print();return false;" / style="margin-top:10px;">PRINT</button>
