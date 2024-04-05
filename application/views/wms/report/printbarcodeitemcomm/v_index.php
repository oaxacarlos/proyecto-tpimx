<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
          {
            extend: 'excel',
            title : 'Stock-Inventory'
          }
        ],
    });
});
</script>


<div class="pb-2 mt-1 mb-2 text-right" style="font-size:12px;">
      Print Barcode Item Commercial
</div>

<div class="container-fluid">
  <table id="DataTable" class="table table-bordered table-striped table-sm">
    <thead>
      <tr>
        <th>Item</th>
        <th>Name</th>
        <th>Commercial Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
          foreach($var_item as $row){
              echo "<tr id='row_".$item_code."'>";
                echo "<td>".$row['code']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['comm_name']."</td>";
                echo "<td>
                  <a href='".base_url()."index.php/wms/barcode/print_barcode_by_com?comname=".$row['comm_name']."' target='_blank' class='btn btn-success'>Barcode</a>
                </td>";
              echo "</tr>";
          }
      ?>
    </tbody>
  </table>
</div>
