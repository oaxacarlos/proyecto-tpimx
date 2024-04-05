<style>
  table#table_doc_released{
      font-size: 10px;
  }
</style>

<table class="table table-bordered table-sm" id="table_doc_released">
    <thead>
      <th>Doc</th>
      <th>Type</th>
      <th>Putter Name</th>
      <th>Put Qty</th>
      <th>Total of Line Qty</th>
    </thead>
    <tbody>

<?php

    function print_detail($row){
        $text = "";
        $text.= "<tr>";
          $text.= "<td>".$row["src_no"]."</td>";
          $text.= "<td>WHRcpt</td>";
          $text.= "<td>".$row["username"]."</td>";
          $text.= "<td>".$row["qty_put"]."</td>";
          $text.= "<td>".$row["line"]."</td>";
          $text.= "</tr>";
        return $text;
    }
    //---

    function print_total($qty_put, $line, $user, $total_all){

      if($total_all == 1) $class_table = 'table-primary';
      else $class_table = 'table-info';

      $text = "";
      $text.= "<tr class=".$class_table.">";
        $text.= "<td colspan='3'>".$user." Summary</td>";
        $text.= "<td>".$qty_put."</td>";
        $text.= "<td>".$line."</td>";
        $text.= "</tr>";
      return $text;
    }
    //---

    $total_all_put = 0;
    $total_all_line = 0;
    $total_put = 0;
    $total_line = 0;
    $user = "";
    $username = "";

    foreach($var_report as $row){
      if($user == ""){
        $user = $row["assign_user"];
        $username = $row["username"];
      }
      else if($user!=$row["assign_user"]){
          echo print_total($total_put, $total_line, $username, "0");
          $user = $row["assign_user"];
          $username = $row["username"];
          $total_put = 0;
          $total_line = 0;
      }

      echo print_detail($row);
      $total_all_line+=$row["line"];
      $total_all_put+=$row["qty_put"];
      $total_line+=$row["line"];
      $total_put+=$row["qty_put"];
    }

    echo print_total($total_put, $total_line, $username, "0");
    echo print_total($total_all_put, $total_all_line, "Overall", 1);
    ?>
    </tbody>
</table>
