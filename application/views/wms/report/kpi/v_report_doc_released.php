<style>
  table#table_doc_released{
      font-size: 10px;
  }
</style>

<table class="table table-bordered table-sm" id="table_doc_released">
    <thead>
      <th>Doc</th>
      <th>Type</th>
      <th>Picker Name</th>
      <th>Picked Qty</th>
      <th>Total of Line Qty</th>
    </thead>
    <tbody>

<?php

    function print_detail($row){
        $text = "";
        $text.= "<tr>";
          $text.= "<td>".$row["src_no"]."</td>";
          $text.= "<td>WHShip</td>";
          $text.= "<td>".$row["username"]."</td>";
          $text.= "<td>".$row["qty_picked"]."</td>";
          $text.= "<td>".$row["line"]."</td>";
          $text.= "</tr>";
        return $text;
    }
    //---

    function print_total($qty_picked, $line, $user, $total_all){

      if($total_all == 1) $class_table = 'table-primary';
      else $class_table = 'table-info';

      $text = "";
      $text.= "<tr class=".$class_table.">";
        $text.= "<td colspan='3'>".$user." Summary</td>";
        $text.= "<td>".$qty_picked."</td>";
        $text.= "<td>".$line."</td>";
        $text.= "</tr>";
      return $text;
    }
    //---

    $total_all_picked = 0;
    $total_all_line = 0;
    $total_picked = 0;
    $total_line = 0;
    $user = "";
    $username = "";

    foreach($var_report as $row){
      if($user == ""){
        $user = $row["assign_user"];
        $username = $row["username"];
      }
      else if($user!=$row["assign_user"]){
          echo print_total($total_picked, $total_line, $username, "0");
          $user = $row["assign_user"];
          $username = $row["username"];
          $total_picked = 0;
          $total_line = 0;
      }

      echo print_detail($row);
      $total_all_line+=$row["line"];
      $total_all_picked+=$row["qty_picked"];
      $total_line+=$row["line"];
      $total_picked+=$row["qty_picked"];
    }

    echo print_total($total_picked, $total_line, $username, "0");
    echo print_total($total_all_picked, $total_all_line, "Overall", 1);
    ?>
    </tbody>
</table>
