<table class="table table-bordered table-striped">
  <tr>
    <th>Qty Filtro De Aceite Promo</th>
    <td><?php echo number_format($promo_data["quantity"],2); ?></td>
  </tr>
  <tr>
    <th>Amount Filtro De Aceite Promo</th>
    <td><?php echo number_format($promo_data["amount"],2); ?></td>
  </tr>
  <tr>
    <th>Volumen Discount</th>
    <td><?php echo number_format($volumen,2); ?></td>
  </tr>
  <tr>
    <th>Pronto Pago</th>
    <td><?php echo number_format($pronto_pago,2); ?></td>
  </tr>
  <tr>
    <th>Total</th>
    <td><?php echo number_format($pronto_pago+$volumen,2); ?></td>
  </tr>
</table>
