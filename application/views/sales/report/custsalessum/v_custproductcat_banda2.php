
<div class="containter-fluid">
  <?php if(isset($_SESSION['user_permis']["7"])){ ?>
    <button class="btn btn-success btn-sm" onclick=f_convert_excel_cust_product_cat_belt2()>EXCEL</button>
  <?php } ?>

  <?php if(isset($_SESSION['user_permis']["8"])){ ?>
    <button class='btn btn-primary btn-sm' id="copy_button_cust_product_cat_belt2" style='margin-left:20px;'>Copy ALL</button>
  <?php } ?>
</div>

<table class="table table-bordered table-striped table-sm" style="margin-top:10px;" id="tbl_cust_product_cat_belt2">
  <thead>
    <tr>
      <th rowspan='2'></th>
      <th rowspan='2'>CustNo</th>
      <th rowspan='2'>CustName</th>
      <th rowspan='2'>Item</th>
      <?php
        foreach($var_cat as $row){
          echo "<th colspan='2' style='text-align:center;'>".$row["cat"]."</th>";
        }
      ?>
      <th colspan='2' style='text-align:center;'>Total</th>
    </tr>
    <tr>
      <?php
        foreach($var_cat as $row){
          echo "<th>Qty</th><th>AMT</th>";
        }
      ?>
      <th>Qty</th><th>AMT</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($var_cat_all as $row_cat_all){
          echo "<tr>";
            echo "<td><button data-toggle='collapse' href='#detail_".$row_cat_all['customer']."'>+</button></td>";
            echo "<td>".$row_cat_all["customer"]."</td>";
            echo "<td>".$row_cat_all["cust_name"]."</td>";
            echo "<td></td>";
            foreach($var_cat as $row_cat){
                echo "<td>".number_format($row_cat_all["qty_".$row_cat["cat"]])."</td>";
                echo "<td>".number_format($row_cat_all["amount_".$row_cat["cat"]])."</td>";

            }
            echo "<td>".number_format($row_cat_all["qty_total"])."</td>";
            echo "<td>".number_format($row_cat_all["amount_total"])."</td>";

          echo "</tr>";

          foreach($var_cat_cust as $row_cat_cust){
              if($row_cat_all['customer'] == $row_cat_cust["customer"]){
                echo "<tr class='collapse table-secondary' id='detail_".$row_cat_all['customer']."' >";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td>".$row_cat_cust["item_name"]."</td>";
                  foreach($var_cat as $row_cat){
                      echo "<td>".number_format($row_cat_cust["qty_".$row_cat["cat"]])."</td>";
                      echo "<td>".number_format($row_cat_cust["amount_".$row_cat["cat"]])."</td>";
                  }
                  echo "<td>".number_format($row_cat_cust["qty_total"])."</td>";
                  echo "<td>".number_format($row_cat_cust["amount_total"])."</td>";
                echo "</tr>";
              }
          }
      }
    ?>
  </tbody>
</table>
