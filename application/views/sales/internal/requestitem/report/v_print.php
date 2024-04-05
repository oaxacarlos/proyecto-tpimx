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

table#noborder {
  border: 0px solid black;
  border-collapse: collapse;
}

tr#noborder {
  border: 0px solid black;
  border-collapse: collapse;
}

td#noborder {
  border: 0px solid black;
  border-collapse: collapse;
}

</style>

<div style="padding:10px;">

<table id="noborder">
  <tr>
    <td id="noborder" style="width:400px;">
        <img src="<?php echo base_url().$this->config->item("pic_folder")."logosakuratoyo.svg"; ?>" class="image" alt="logo" width=300>
        <br><br>
        <span style="font-size:20px;">TPI IMPORTACIONES MÉXICO SA DE CV</span>
    </td>
    <td id="noborder" style="font-size:15px; margin-left:10px; width:120px;">

    </td>
    <td id="noborder">
      <table id="noborder">
        <tr id="noborder">
          <td id="noborder">FOLIO :</td>
          <td id="noborder"><?php echo $var_h["doc_no"] ?></td>
        </tr>
        <tr id="noborder">
          <td id="noborder">FECHA :</td>
          <td id="noborder"><?php echo $var_h["doc_date"] ?></td>
        </tr>
        <tr id="noborder">
          <td id="noborder">REF DOC :</td>
          <td id="noborder"><?php echo $var_h["ref_doc"] ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr id="noborder"><td style="height:50px;" id="noborder"></td></tr>

  <tr id="noborder">
    <td colspan='3' id="noborder">
      <table width='100%' id="noborder">
        <tr><td style="border-top:0px; border-left:0px; border-right:0px; padding: 5px; font-size:14px;">CLIENTE NO: <?php echo strtoupper($var_h["cust_no"]); ?></td></tr>
        <tr><td style="border-top:0px; border-left:0px; border-right:0px; padding: 5px; font-size:14px;">CLIENTE NOMBRE: <?php echo strtoupper($var_h["name"]); ?></td></tr>
        <tr><td style="border-top:0px; border-left:0px; border-right:0px; padding: 5px; font-size:14px;">DIRRECION: <?php echo strtoupper($var_h["address"].", ".$var_h["address2"]); ?></td></tr>
        <tr><td style="border-top:0px; border-left:0px; border-right:0px; padding: 5px; font-size:14px;">CIUDAD: <?php echo strtoupper($var_h["city"]); ?></td></tr>
        <tr><td style="border-top:0px; border-left:0px; border-right:0px; padding: 5px; font-size:14px;">STATE: <?php echo strtoupper($var_h["county"]); ?></td></tr>
        <tr><td style="border-top:0px; border-left:0px; border-right:0px; border-bottom:0px; padding: 5px; font-size:14px;">POSTAL CODE: <?php echo strtoupper($var_h["post_code"]); ?></td></tr>
      </table>
    </td>
  </tr>

  <tr id="noborder"><td style="height:20px;" id="noborder"></td></tr>

  <tr id="noborder">
    <td colspan='3' id="noborder">
      <table width='100%' id="noborder">
        <tr>
          <td style="text-align:center;">ITEM</td>
          <td style="text-align:center;">DESCRIPCION</td>
          <td style="text-align:center;">CANTIDAD</td>
        </tr>
          <?php
            $i=1;
            $total = 0;
            foreach($var_d as $row){
                echo "<tr>";
                  echo "<td style='padding:3px; font-size:14px;'>".$i."</td>";
                  echo "<td style='padding:3px; font-size:14px;'>".$row["item_code"].", ".$row["description"]."</td>";
                  echo "<td style='padding-left:5px; font-size:14px;'>".$row["qty_edited"]."</td>";
                echo "</tr>";
                $i++;
                $total += $row["qty_edited"];
            }
          ?>

          <tr>
            <td colspan='2' style="text-align:right; padding-right:10px; font-size:14px;"><b>TOTAL</b></td>
            <td style='padding-left:5px; padding:3px; font-size:14px;'><?php echo $total; ?></td>
          </tr>
        </table>
        </tr>
    </td>
  </tr>
</table>

<div>
