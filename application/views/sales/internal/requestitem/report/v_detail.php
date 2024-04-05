<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      Doc No
      <input type="text" value="<?php echo $doc_no; ?>" class="form-control" disabled id="inp_doc_no">
    </div>
    <div class="col-md-1">
      Cust No
      <input type="text" value="<?php echo $cust_no; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      Cust Name
      <input type="text" value="<?php echo $cust_name; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      Contact
      <input type="text" value="<?php echo $contact; ?>" class="form-control" disabled>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      Address
      <input type="text" value="<?php echo $address; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      Address 2
      <input type="text" value="<?php echo $address2; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-2">
      City
      <input type="text" value="<?php echo $city; ?>" class="form-control" disabled>
    </div>
  </div>
</div>

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <div class="col-md-3">
      Post Code
      <input type="text" value="<?php echo $post_code; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-3">
      County
      <input type="text" value="<?php echo $county; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-2">
      Country
      <input type="text" value="<?php echo $country_region_code; ?>" class="form-control" disabled>
    </div>
    <div class="col-md-2">
      <button class="btn btn-danger" style="margin-top:25px;" id=btn_pdf>PDF</button>
    </div>
  </div>
</div>

<input type="hidden" value="<?php echo $status; ?>" class="form-control" id="inp_status">

<div class="container-fluid" style="margin-top:20px;">
  <div class="row">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Line</th>
          <th>Item</th>
          <th>Desc</th>
          <th>Uom</th>
          <th>Qty Requested</th>
          <th>Qty Edited</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $i=0;
          foreach($var_in_out_d as $row){
              echo "<tr id='table_detail_row_".$i."'>";
                echo "<td id='table_detail_line_".$i."'>".$row["line_no"]."</td>";
                echo "<td id='table_detail_item_code_".$i."'>".$row["item_code"]."</td>";
                echo "<td>".$row["description"]."</td>";
                echo "<td>".$row["uom"]."</td>";
                echo "<td id='table_detail_qty_".$i."'>".$row["qty"]."</td>";
                echo "<td>".$row["qty_edited"]."</td>";
              echo "</tr>";
              $i++;
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
