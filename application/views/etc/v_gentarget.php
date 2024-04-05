<style>
body {
    margin: 0;
    padding: 0;
}
body, iframe {
    width: 100%;
    height: 100%;
}
iframe {
    border: 0;
}
</style>

<?php
    $datetime = date("YmdHis");
    $key = md5($datetime);
?>

<iframe src="http://192.168.120.31/generatetarget/uploadfile.php?key=<?php echo $key ?>&gen=<?php echo $datetime ?>" style="top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
