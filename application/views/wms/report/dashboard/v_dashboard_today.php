<table class="table table-bordered table-striped">
    <tr>
      <th colspan='2'>TODAY Shipment
        <button class="btn btn-danger btn-sm" onclick=f_refresh_today() style="margin-left:5px;"><i class='bi-arrow-clockwise'></i></button>
      </th>
    </tr>
    <tr>
      <th>Total Doc In</th>
      <td><?php echo $var_total["total_in"]; ?></td>
    </tr>
    <tr>
      <th>Total Doc Finished</th>
      <td><?php echo $var_total["total_out"]; ?></td>
    </tr>
    <tr>
      <th>% Finished</th>
      <td><?php echo percentage($var_total["total_out"], $var_total["total_in"]); ?></td>
    </tr>
</table>
