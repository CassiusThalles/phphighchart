<?php

//myApiKeys is here so I can keep my Keys safe
require_once('myApiKeys.php');

//here I'll list the parameters needed to send the http request to the currencylayer API
$endpoint = 'historical';
$today = date('Y-m-d');
$currencylist = 'BRL,EUR,ARS';

//put the parameters and the URL all together
$url_api = 'http://apilayer.net/api/'.$endpoint.'?access_key='.$currencylayer_key.'&date='.$today.'&currencies='.$currencylist.'&format=1';

//to send the http request to the currencylayer api I'll be using cURL. First we need to initialize it
$ch = curl_init();

//the line below will define $url_api as the target of the http request
curl_setopt($ch, CURLOPT_URL, $url_api);

//the line below will express that the result of the request will be assigned to a variable
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//the line below will execute the http request and assign the result to the variable $result
$result = curl_exec($ch);

//it's important to close the connection after it's use
curl_close($ch);

//here we'll decode the result and assign the 'quotes' to an array. This array will be used by HighchartsJS to plot the chart
$json = json_decode($result);
$myArr = array();
foreach($json->quotes as $quotes):
    array_push($myArr, $quotes);
endforeach;
$myArr = json_encode($myArr);
//print_r($myArr);
//echo "<br/><br/><br/>"

?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Highcharts Example</title>

		<style type="text/css">
#container {
	min-width: 310px;
	max-width: 800px;
	height: 400px;
	margin: 0 auto
}
		</style>
	</head>
	<body>
<script src="code/highcharts.js"></script>
<script src="code/modules/series-label.js"></script>
<script src="code/modules/exporting.js"></script>
<script src="code/modules/export-data.js"></script>

<div id="container"></div>



		<script type="text/javascript">

Highcharts.chart('container', {

    title: {
        text: 'Price of BRL, EUR and ARS currencies based on US Dollar'
    },

    subtitle: {
        text: 'Source: currencylayer api'
    },

    yAxis: {
        title: {
            text: 'price in USD'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'currency',
        data: [<?php print_r($myArr); ?>]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
		</script>
	</body>
</html>
