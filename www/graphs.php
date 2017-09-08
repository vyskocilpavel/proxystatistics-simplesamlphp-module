<?php
include dirname(__DIR__)."/lib/Auth/Process/DatabaseCommand.php";

/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */

?>
<h2>Number of logins</h2>
<div>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart', 'controls']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Date', 'Count'],
                <?php DatabaseCommand::getLoginCountPerDay()?>
            ]);

            var dashboard = new google.visualization.Dashboard(
                document.getElementById('dashboard_div'));

            var chartRangeFilter=new google.visualization.ControlWrapper({
                'controlType': 'ChartRangeFilter',
                'containerId': 'control_div',
                'options': {
                    'filterColumnLabel': 'Date'
                }
            });

            var chart = new google.visualization.ChartWrapper({
                'chartType' : 'LineChart',
                'containerId' : 'line_div',
                'options':{
                    'title' :'Number of logins per day',
                    'legend' : 'none'
                }
            });


            dashboard.bind(chartRangeFilter, chart);
            dashboard.draw(data);
        }
    </script>

    <div id="dashboard_div" style="width: 900px; height: 600px">
        <div id="line_div" style="width: 900px; height: 550px"></div>
        <div id="control_div" style="width: 900px; height: 50px"></div>
    </div>

</div>

<h2>Identity providers</h2>
<div>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['sourceIdp', 'Count'],
                <?php DatabaseCommand::getLoginCountPerIdp()?>
            ]);

            var options = {
                title: 'Overall logins per identity provider',
		pieSliceText: 'value'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            data.sort([{column: 1, desc: true}]);
            chart.draw(data, options);
        }
    </script>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</div>

<h2>Service providers</h2>
<div>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['service', 'Count'],
                <?php DatabaseCommand::getAccessCountPerService()?>
            ]);

            var options = {
                title: 'Overall logins to service providers',
		pieSliceText: 'value'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

            data.sort([{column: 1, desc: true}]);
            chart.draw(data, options);
        }
    </script>
    <div id="piechart2" style="width: 900px; height: 500px;"></div>
</div>
