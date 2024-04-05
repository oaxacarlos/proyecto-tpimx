<?php
$_SESSION['refresh_home'] = $_SESSION['refresh_home'] + 1;
?>


<script>

$(document).ready(function(){
    //-- load sign in -----//
    var refresh_count = <?php echo $_SESSION['refresh_home']; ?>;

    if(refresh_count == 1){
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        })

        Toast.fire({
          type: 'success',
          title: 'Signed in successfully'
        })
    }
    //------------------
})

</script>


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

<figure class="highcharts-figure">
    <div id="container" style="height:500px;"></div>
</figure>

<script>
var text = 'TPI-MX Importaciones Toyopower Filtro Banda Sakura Mexico CS Operacion Warehouse Contabilidad Sistema Humanos Marketing';
var lines = text.split(/[,\. ]+/g),
    data = Highcharts.reduce(lines, function (arr, word) {
        var obj = Highcharts.find(arr, function (obj) {
            return obj.name === word;
        });
        if (obj) {
            obj.weight += 1;
        } else {
            obj = {
                name: word,
                weight: 1
            };
            arr.push(obj);
        }
        return arr;
    }, []);

Highcharts.chart('container', {
    accessibility: {
        screenReaderSection: {
            beforeChartFormat: '<h5>{chartTitle}</h5>' +
                '<div>{chartSubtitle}</div>' +
                '<div>{chartLongdesc}</div>' +
                '<div>{viewTableButton}</div>'
        }
    },
    series: [{
        type: 'wordcloud',
        data: data,
        name: 'Occurrences'
    }],
    title: {
        text: 'Welcome to TPIMX-Portal, Stay Safe and always say "Buenos Dias" for our Spirit'
    }
});
</script>
