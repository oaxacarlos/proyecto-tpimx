<?php
/*
$filename = "hasil.txt";
$myfile = fopen($filename, "a");
fwrite($myfile,$query_temp."\r\n");
fclose($myfile);
*/

/*
$.ajax({
    url       : "<?php echo base_url();?>index.php/itr_request_new/itr_request_new_uploadfile",
    type: "POST",
    data: form_data,
    contentType: false,
    cache: false,
    processData:false,
    success: function(data){

    }
});
*/


/*

swal({
  input: 'textarea',
  inputPlaceholder: 'Type your message here',
  showCancelButton: true,
  confirmButtonText: 'OK'
}).then(function (result) {
    if(result.dismiss == "cancel"){}
    else{
      if(result.value == ""){
          show_error("You have to type message");
      }
      else{}
    }
})

*/

// assign data from table mysql/sql server to array
function assign_data($result){
    if(!$result){
      return 0;
    }
    else{
      $temp = array_keys($result[0]);
      foreach($result as $row){
        unset($temp_data);
        foreach($temp as $row2){
          $temp_data[$row2] = $row[$row2];
        }
        $data[] = $temp_data;
      }
      return $data;
    }


}
//-----

// assign data from table mysql/sql server to array
function assign_data_one($result){
    $temp = array_keys($result[0]);
    foreach($result as $row){
      unset($temp_data);
      foreach($temp as $row2){
        $temp_data[$row2] = $row[$row2];
      }
      $data = $temp_data;
    }

    return $data;
}
//-----

function load_progress($id){
  /*  $result =
    "<div id='".$id."' style='display:none; text-align:center;'>
      <div class='spinner-grow text-muted'></div>
      <div class='spinner-grow text-primary'></div>
      <div class='spinner-grow text-success'></div>
      <div class='spinner-grow text-info'></div>
      <div class='spinner-grow text-warning'></div>
      <div class='spinner-grow text-danger'></div>
      <div class='spinner-grow text-secondary'></div>
      <div class='spinner-grow text-dark'></div>
      <div class='spinner-grow text-light'></div>
      <br>Loading, Please wait...
    </div>";*/

    $result =
      "<div id='".$id."' style='display:none; text-align:center;'>
        <div class='spinner-border text-primary'>TYP</div>
        <div class='spinner-border text-danger'>SKR</div>
        <br>Loading, Please wait...
      </div>";

    return $result;
}
//---

function html_no_data(){
    $text = "<tr><td>No DATA</td></tr>";

    return $text;
}
//---

function convert_date_to_date($input){
    $date = strtotime($input);
    $date = date('Y-m-d', $date);
    return $date;
}
//---

function loading_body_full(){
    /*$result = "<div id='loading_body' style='display:none;'>
      <div class='container' style='text-align:center; width:100%;'>
        <div class='spinner-grow text-muted'></div>
        <div class='spinner-grow text-primary'></div>
        <div class='spinner-grow text-success'></div>
        <div class='spinner-grow text-info'></div>
        <div class='spinner-grow text-warning'></div>
        <div class='spinner-grow text-danger'></div>
        <div class='spinner-grow text-secondary'></div>
        <div class='spinner-grow text-dark'></div>
        <div class='spinner-grow text-light'></div><br>
        <div class='badge badge-light' id='loading_text'></div>
      </div>
    </div>";*/

    $result = "<div id='loading_body' style='display:none;'>
      <div class='container' style='text-align:center; width:100%;'>
        <div class='spinner-border text-info'>TYP</div>
        <div class='spinner-border text-danger'>SKR</div><br>
        <div class='badge badge-light' id='loading_text'></div>
      </div>
    </div>";

    return $result;
}
//---

function get_datetime_now(){
  return date("Y-m-d H:i:s");
}
//---

function get_date_now(){
  return date("Y-m-d");
}
//---

function convert_number2($value){
    if(is_null($value) || $value=='') return 0;
    else return $value;
}
//--

function progress_bar($id){
  $result = "<div class='progress' id='".$id."'>
    <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 75%'></div>
  </div>";

  return $result;
}
//---

function calculate_time($start, $end){
    /*debug("start = ".$start);
    debug("end = ".$end);
    $totaltime = strtotime($end) - strtotime($start);
    $h = intval($totaltime / 3600);
    $m = intval($totaltime / 60);
    $s = $totaltime - ($m * 60);
    $data["hours"] = make_two_digits($h);
    $data["minutes"] = make_two_digits($m);
    $data["seconds"] = make_two_digits($s);*/

    //echo date_create('03:00:00 PM')->diff(date_create('03:25:00 PM'))->format('%H:%i:%s');

    return date_create($start)->diff(date_create($end))->format('%H:%i:%s');
}
//---

function make_two_digits($value){
    if($value >= 0 && $value <=9) $value = "0".$value;
    return $value;
}
//---

function debug($text){
    $filename = "hasil.txt";
    $myfile = fopen($filename, "a");
    fwrite($myfile,$text."\r\n");
    fclose($myfile);
}
//--

function percentage($value1, $value2){
    if($value1==0 || $value2==0) return 0;
    $value = round(($value1/$value2)*100,2);
    return $value;
}
//---

function format_number($number,$amount_format,$comma_digit){
    if($amount_format == 1){
      if($number == 0) return "-";
      return number_format($number,$comma_digit);
    }
    else{
      if($number == 0) return "-";
      else{
        if($comma_digit != 0) return round($number,$comma_digit);
        else return $number;
      }
    }
}
//--

function get_last_12months_first_day($today){
    return date("Y-m-01", strtotime( $today." -12 months"));
}
//---

function get_last_6months_first_day($today){
    return date("Y-m-01", strtotime( $today." -6 months"));
}
//---

function get_last_month_first_day($today){
    return date("Y-m-01", strtotime( $today." -1 months"));
}
//---

function get_last_month_last_day($today){
    return date("Y-m-t", strtotime( $today." -1 months"));
}
//---

function get_last_2months_first_day($today){
    return date("Y-m-01", strtotime( $today." -2 months"));
}
//---

function get_last_2months_last_day($today){
    return date("Y-m-t", strtotime( $today." -2 months"));
}
//---

function get_ip_address(){
  //whether ip is from share internet
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
      $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }
  //whether ip is from proxy
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
  //whether ip is from remote address
  else
    {
      $ip_address = $_SERVER['REMOTE_ADDR'];
    }
  //---

  return $ip_address;
}
//----

function generate_month($selected){
    $result = "";
    for($i=1;$i<=12;$i++){
        if($i>=1 && $i<=9){ $value = "0".$i;}
        else{ $value = $i; }

        if($value == $selected) $selected_option = "selected";
        else $selected_option = "";

        $result.="<option value='".$value."' ".$selected_option.">".$value."</option>";
    }
    return $result;
}
//---

function get_week_in_month($year, $month){

    unset($result);
    $i=0;
    $format = "Y-m-d";

    $week = date("W", strtotime($year . "-" . $month ."-01")); // weeknumber of first day of month

    $result[$i]["from"] = date($format, strtotime($year . "-" . $month ."-01")); // first day of month;

    $unix = strtotime($year."W".$week ."+1 week");
    While(date("m", $unix) == $month){ // keep looping/output of while it's correct month

       $result[$i]["to"] = date($format, $unix-86400); // Sunday of previous week
       $i++;

       $result[$i]["from"] = date($format, $unix); // this week's monday
     $unix = $unix + (86400*7);
    }
    $result[$i]["to"] = date($format, strtotime("last day of ".$year . "-" . $month)); //echo last day of month

    return $result;
}
//---

function get_total_working_days_a_month($year, $month){
    $workdays = array();
    $type = CAL_GREGORIAN;
    //$month = "8"; // Month ID, 1 through to 12.
    //$year = "2022"; // Year in 4 digit 2009 format.
    $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

    //loop through all days
    for ($i = 1; $i <= $day_count; $i++) {

            $date = $year.'/'.$month.'/'.$i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

            //if not a weekend add day to array
            if($day_name != 'Sun' && $day_name != 'Sat'){
                $workdays[] = $i;
            }

    }

    // look at items in the array uncomment the next line
       //print_r($workdays);
    return count($workdays);
}
//---

function get_working_days_between_date($date1, $date2, $workSat = FALSE, $patron = NULL){
    if (!defined('SATURDAY')) define('SATURDAY', 6);
      if (!defined('SUNDAY')) define('SUNDAY', 0);

      // Array of all public festivities
      $publicHolidays = array('01-01', '01-06', '04-25', '05-01', '06-02', '11-01', '12-08', '12-25', '12-26');
      // The Patron day (if any) is added to public festivities
      if ($patron) {
        $publicHolidays[] = $patron;
      }

      /*
       * Array of all Easter Mondays in the given interval
       */
      $yearStart = date('Y', strtotime($date1));
      $yearEnd   = date('Y', strtotime($date2));

      for ($i = $yearStart; $i <= $yearEnd; $i++) {
        $easter = date('Y-m-d', easter_date($i));
        list($y, $m, $g) = explode("-", $easter);
        $monday = mktime(0,0,0, date($m), date($g)+1, date($y));
        $easterMondays[] = $monday;
      }

      $start = strtotime($date1);
      $end   = strtotime($date2);
      $workdays = 0;
      for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
        $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
        $mmgg = date('m-d', $i);
        if ($day != SUNDAY &&
          !in_array($mmgg, $publicHolidays) &&
          !in_array($i, $easterMondays) &&
          !($day == SATURDAY && $workSat == FALSE)) {
            $workdays++;
        }
      }

      return intval($workdays);
}
//---

function secondsToTime($seconds) {
    $s = $ss%60;
    $m = floor(($ss%3600)/60);
    $h = floor(($ss%86400)/3600);
    $d = floor(($ss%2592000)/86400);
    $M = floor($ss/2592000);

    return "$M months, $d days, $h hours, $m minutes, $s seconds";
}
//---

function logs($text){
    $datetime = get_datetime_now();
    $filename = "logs.txt";
    $myfile = fopen($filename, "a");
    fwrite($myfile,$datetime." -> ".$text."\r\n");
    fclose($myfile);
}
//--

function get_period_from_period($date_from, $date_to){
    $date_from_split = explode("-",$date_from);
    $date_to_split = explode("-",$date_to);

    if($date_from_split[0] == $date_to_split[0]) $same_year = 1;
    else $same_year = 0;

    if($date_from_split[1] == $date_to_split[1]) $same_month = 1;
    else $same_month = 0;

    unset($period);
    if($same_year==1 && $same_month==1){
      $period[] = array( "from" => $date_from, "to" => $date_to, "month" => $date_to_split[1], "year" => $date_to_split[0] );
    }
    else if($same_year==1 && $same_month==0){
        $month = (int)$date_from_split[1];
        $first = 1;
        $month_end = (int)$date_to_split[1];
        while($month != $month_end){
           if($first == 1){
               $last_day = date("Y-m-t", strtotime($date_from));
               $period[] = array( "from" => $date_from, "to" => $last_day,  "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day)));
           }
           else{
              $last_day = date("Y-m-t", strtotime($date_from_split[0]."-".$month."-".$date_from_split[2]));
              $first_day = $date_from_split[0]."-".$month."-"."01";
              $period[] = array( "from" => $first_day, "to" => $last_day, "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day)));
           }
           $first++;
           $month++;
        }

        $last_day  = $date_to;
        $first_day = $date_from_split[0]."-".$month."-"."01";
        $period[] = array( "from" => $first_day, "to" => $last_day, "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day)));
    }
    else if($same_year==0 && $same_month==0){
        $year = (int)$date_from_split[0];
        $year_end = (int)$date_to_split[0];
        $month = (int)$date_from_split[1];
        while($year != $year_end){
            $first = 1;
            $month_end = 13;
            while($month != $month_end){
               if($first == 1){
                   $last_day = date("Y-m-t", strtotime($date_from));
                   $period[] = array( "from" => $date_from, "to" => $last_day,  "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day))  );
               }
               else{
                  $last_day = date("Y-m-t", strtotime($year."-".$month."-".$date_from_split[2]));
                  $first_day = $year."-".$month."-"."01";
                  $period[] = array( "from" => $first_day, "to" => $last_day, "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day)));
               }
               $first++;
               $month++;
            }

            $year++;
            $month = 1;
        }

        // start last year
        $month = (int)date("m", strtotime($year."-01-01"));
        $first = 1;
        $month_end = (int)$date_to_split[1];
        while($month != $month_end){
           if($first == 1){
               $last_day = date("Y-m-t", strtotime( $year."-".$month."-01"));
               $first_day = $year."-".$month."-01";
               $period[] = array( "from" => $first_day, "to" => $last_day,  "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day)) );
           }
           else{
              $last_day = date("Y-m-t", strtotime($date_from_split[0]."-".$month."-".$date_from_split[2]));
              $first_day = $date_from_split[0]."-".$month."-"."01";
              $period[] = array( "from" => $first_day, "to" => $last_day, "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day)));
           }
           $first++;
           $month++;
        }

        $last_day  = $date_to;
        $first_day = $year."-".$month."-"."01";
        $period[] = array( "from" => $first_day, "to" => $last_day, "month" => date("m", strtotime($last_day)), "year" => date("Y", strtotime($last_day)));
    }

    return $period;
}
//---

function mysql_escape_mimic($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);

    if(!empty($inp) && is_string($inp)) {
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
    }

    return $inp;
}
//---

function combine_location($loc, $zone, $area, $rack, $bin){
    return $loc."-".$zone."-".$area."-".$rack."-".$bin;
}
//---

function split_location($location,$explode){
    $location = explode($explode,$location);
    return $location;
}
//---

function get_counting_months_before($month){
    unset($data);

    $data[] = $month;

    if($month >= 2){
        for($i=($month-1);$i>=1;$i--){
            if($i>=1 && $i<=9) $data[] = "0".$i;
            else $data[] = $i;
        }
    }

    return $data;
}
//---

function calculateTransactionDuration($startDate, $endDate)
{
    $date = new DateTime( $startDate);
    $date2 = new DateTime( $endDate);
    $diffInSeconds = $date2->getTimestamp() - $date->getTimestamp();
    return $diffInSeconds;
}
//---

function convert_number3($value){
    if(is_null($value) || $value=='' || $value==0) return '-';
    else return $value;
}
//--

function replace_string($string){
    return str_replace(str_split('\\/:*?"<>|+-'), '', $string);
}
//--

function get_plant_code_user(){

    $ci = &get_instance();
    $ci->load->library('session');

    $session_data = $ci->session->userdata('z_tpimx_logged_in');
    return $session_data["z_tpimx_plant_code"];
}
//--

function get_plant_user_by_array(){
      unset($result);
      $user_plant = get_plant_code_user();
      $new_user_plant = explode(",",$user_plant);
      for($i=0; $i<count($new_user_plant); $i++){
          $temp = trim($new_user_plant[$i],"\'");
          $result[] = $temp;
      }

      return $result;
}
//---

function get_counting_months_before2($year, $month, $total){

    unset($data);

    $temp_month = 0 ;
    $temp_year = $year;
    for($i=$total;$i>=1;$i--){
        $temp_month = $month - $i;
        $temp_year = $year;

        if($temp_month <= 0){
          $temp_year = $year - 1;
          $temp_month = 12+$temp_month;
        }
        $data[] = array(
          "year" => $temp_year,
          "month" => $temp_month
        );
    }

    /*$data[] = array(
      "year" => $year,
      "month" => $month
    );*/

    return $data;
}
//--

function getBrowser()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "N/A";

    $browsers = [
        '/msie/i' => 'Internet explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/mobile/i' => 'Mobile browser',
    ];

    foreach ($browsers as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }

    return $browser;
}
//--
function replace_item_code($item_code){
  $result = "<span style='display:none;'>'</span>".$item_code;
  return $result;
}

?>
