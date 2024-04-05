<style>
  td#number{
    text-align: right;
  }
</style>

<div class="container text-right">
  <button class="btn btn-info text-right" onclick=gen_report_salesnational_view_total_invoice_cn_nett_nav(<?php echo $year; ?>,<?php echo $month; ?>)>refresh</button>
</div>
<table class='table table-bordered table-striped' style='margin-top:20px; font-size:20px;'>
  <tr>
    <th>Year</th>
    <td id='number'><?php echo $var_detail["yearr"] ?></td>
  </tr>
  <tr>
    <th>Month</th>
    <td id='number'><?php echo $var_detail["monthh"] ?></td>
  </tr>
  <tr>
    <th>Invoice Total (MXN)</th>
    <td id='number'><?php echo $var_detail["total_invoice"] ?></td>
  </tr>
  <tr>
    <th>CN Total (MXN)</th>
    <td id='number'><?php echo $var_detail["total_cm"] ?></td>
  </tr>
  <tr>
    <th>Nett Total (MXN)</th>
    <td id='number'><?php echo $var_detail["total_nett"] ?></td>
  </tr>
  <tr>
    <th>Total WMS Value (MXN)</th>
    <td id='number'><?php echo number_format($total_wms_value,2) ?></td>
  </tr>
  <tr>
    <th>Total Shipment Value (MXN)</th>
    <td id='number'><?php echo number_format($total_sls_shipment,2) ?></td>
  </tr>
  <tr>
    <th>Total (MXN)</th>
    <td id='number'><?php
      echo number_format($var_detail["total_nett2"] + $total_wms_value + $total_sls_shipment,2) ;
    ?></td>
  </tr>
  <tr><td>-</td><td>-</td></tr>
  <tr>
    <th>BO without Stock (MXN)</th>
    <td id='number'><?php echo format_number($total_bo_value,1,2); ?></td>
  </tr>
</table>
