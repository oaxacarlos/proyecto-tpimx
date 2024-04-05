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

</style>

<table border="1" style="width:740px; margin-left:30px; margin-top:30px;">
  <tr>
    <td colspan='3' style='font-weight:bold;'>
      <?php
        echo strtoupper("Date : ").$var_packing_h["doc_date"]; echo "<br>";
        echo strtoupper("document no : ").$var_packing_h["doc_no"]; echo "<br>";
        echo strtoupper("SO no : ").$so_no; echo "<br>";
        echo "<br>";
        echo strtoupper("Content List"); echo "<br>";
      ?>
    </td>
    <td style="font-size:20px; text-align:center;">
      <?php
        echo "BOX = ".$box."/".$total_row;
      ?>
    </td>
  </tr>
        <tr>
          <td style="text-align:center; font-weight:bold;">Item</td>
          <td style="text-align:center; font-weight:bold;">Description</td>
          <td style="text-align:center; font-weight:bold;">Qty</td>
          <td style="text-align:center; font-weight:bold;">Uom</td>
        </tr>
        <?php
          $total_qty=0;
          foreach($var_packing_d as $row){
            echo "<tr style='height:30px;'>";
              echo "<td>".$row["item_code"]."</td>";
              echo "<td>".$row["description"]."</td>";
              echo "<td style='text-align:right; padding-right:2px;'>".$row["qty_to_packed"]."</td>";
              echo "<td style='text-align:right;'>".$row["uom"]."</td>";
            echo "</tr>";
            $total_qty += $row["qty_to_packed"];
          }

          echo "<tr>";
            echo "<td colspan='2' style='text-align:right;'><b>Total</b></td>";
            echo "<td style='text-align:right; padding-right:2px;'><b>".$total_qty."</b></td>";
            echo "<td style='text-align:right;'><b>PZA</b></td>";
          echo "</tr>";
        ?>

  </tr>
</table>

<button id="printPageButton" onclick="window.print();return false;" / style="margin-top:10px;">PRINT</button>
