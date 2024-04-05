<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Item</th>
            <th>Sales Type</th>
            <th>Sales Code</th>
            <th>Currency Code</th>
            <th>Minimum Quantity</th>
            <th>Unit Price</th>
            <th>Starting Date</th>
            <th>Ending Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($var_data as $row){
                echo "<tr>";
                    echo "<td>".$row["item_no"]."</td>";
                    echo "<td>".$row["sales_type"]."</td>";
                    echo "<td>".$row["sales_code"]."</td>";
                    echo "<td>".$row["currency_code"]."</td>";
                    echo "<td>".format_number($row["min_qty"],1,2)."</td>";
                    echo "<td>".format_number($row["unit_price"],1,2)."</td>";
                    echo "<td>".$row["starting_date"]."</td>";
                    echo "<td>".$row["ending_date"]."</td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>