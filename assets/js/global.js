
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    //if(charCode == 46) return false;
    if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57)))
        return false;
    return true;
}

function show_error(message){
    Swal('Error!',message,'error');
}

function show_warning(message){
    Swal('Warning!',message,'error');
}

function check_if_id_exist(id){
  if($(id).length) return true; else return false;
}

function convert_number(value){
    if(value=="") number = 0;
    else number = parseInt(value);

    return number;
}
//---

function getfulldatetimenow(){
    //var now = new Date().toLocaleString("en-US", {timeZone: "America/Belize"});
    var now = new Date();
    var year = now.getFullYear();
    var month = convert2digits(now.getMonth()+1);
    var date = convert2digits(now.getDate());
    var hours = convert2digits(now.getHours());
    var minutes = convert2digits(now.getMinutes());
    var seconds = convert2digits(now.getSeconds());
    var formatted = year+"-"+month+"-"+date+" "+hours+":"+minutes+":"+seconds;
    return formatted;
}
//--

function convert2digits(value) {
  return value < 10 ? '0' + value : '' + value;
}
//---

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}
//----

function get_today(from,to){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById(from).value= today;
    document.getElementById(to).value = today;
}
//--

function get_this_month(from,to){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    first = yyyy + '-' + mm + '-' + "01";
    today = yyyy + '-' + mm + '-' + dd;

    document.getElementById(from).value = first;
    document.getElementById(to).value = today;
}
//---

function get_last_month(from,to){
    var now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth()-1, 1);
    const lastDay = new Date(now.getFullYear(), (now.getMonth()-1) + 1, 0);

    document.getElementById(from).value = formatDate(firstDay);
    document.getElementById(to).value = formatDate(lastDay);
}
//---

function check_from_to(from,to){
    if(from=="" || to==""){
        show_error("You must fill Date-from & Date-To");
        return false;
    }
    else if(from > to){
      show_error("Date-from not allow Greater than Date-To");
      return false;
    }

    return true;
}
//---

function get_last_7days(from,to){
    get_today(from,to)
    const firstDay = new Date();
    const previousWeek = new Date(firstDay.getTime() - 6 * 24 * 60 * 60 * 1000);
    document.getElementById(from).value = formatDate(previousWeek);
}
//---

function get_this_year(from,to){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    first = yyyy + '-' + "01" + '-' + "01";
    today = yyyy + '-' + mm + '-' + dd;

    document.getElementById(from).value = first;
    document.getElementById(to).value = today;
}
//---

function js_number_format(number){
    return number.toLocaleString('en-US');
}
//---

function toast_message_success(message){
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    })

    Toast.fire({
      type: 'success',
      title: message
    })
}
//---

function progressbar_dynamic(){
    return '<div class="spinner-border text-danger" role="status"> <span class="sr-only">Loading...</span> </div>';
}
