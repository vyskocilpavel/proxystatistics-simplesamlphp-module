<?php
include dirname(__DIR__)."/lib/Auth/Process/DatabaseCommand.php";
/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 * @author Dominik Baránek <0Baranek.dominik0@gmail.com>
 */

$lastDays = $this->data['lastDays'];

?>

<link rel="stylesheet"  media="screen" type="text/css" href="<?php SimpleSAML\Module::getModuleUrl('proxystatistics/statisticsproxy.css')?>" />

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'controls', 'table']});
    google.charts.setOnLoadCallback(drawLoginsChart);
    google.charts.setOnLoadCallback(drawLoginsTable);

    function drawLoginsChart() {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'Count'],
			<?php DatabaseCommand::getLoginCountPerDay($lastDays)?>
        ]);

        var dashboard = new google.visualization.Dashboard(document.getElementById('loginsDashboard'));

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
                'legend' : 'none'
            }
        });
        dashboard.bind(chartRangeFilter, chart);
        dashboard.draw(data);
    }

    function drawLoginsTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/tables_date}'); ?>');
        data.addColumn('number', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/count}'); ?>');
        data.addRows([<?php DatabaseCommand::getLoginCountPerDay($lastDays)?>]);

        var table = new google.visualization.Table(document.getElementById('loginsTable'));

        var formatter = new google.visualization.DateFormat({pattern: "d  MMMM  yyyy"});
        formatter.format(data, 0); // Apply formatter to second column

        table.draw(data, {showRowNumber: false,});
    }

    google.charts.setOnLoadCallback(drawIdpsChart);
    google.charts.setOnLoadCallback(drawIdpsTable);

    function drawIdpsChart() {
        var data = google.visualization.arrayToDataTable([
            ['sourceIdp', 'Count'],
			<?php DatabaseCommand::getLoginCountPerIdp($lastDays)?>
        ]);

        var options = {
            pieSliceText: 'value',
            chartArea:{left:20,top:0,width:'100%',height:'100%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('idpsChart'));

        data.sort([{column: 1, desc: true}]);
        chart.draw(data, options);
    }

    function drawIdpsTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/tables_month}'); ?>');
        data.addColumn('string', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/tables_identity_provider}'); ?>');
        data.addColumn('number', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/count}'); ?>');
        data.addRows([<?php DatabaseCommand::getLoginCountPerDeyPerService()?>]);

        var table = new google.visualization.Table(document.getElementById('idpsTable'));

        var formatter = new google.visualization.DateFormat({pattern:"MMMM  yyyy"});
        formatter.format(data, 0); // Apply formatter to second column

        table.draw(data);
    }

    google.charts.setOnLoadCallback(drawSpsChart);
    google.charts.setOnLoadCallback(drawSpsTable);

    function drawSpsChart() {
        var data = google.visualization.arrayToDataTable([
            ['service', 'Count'],
			<?php DatabaseCommand::getAccessCountPerService($lastDays)?>
        ]);

        var options = {
            pieSliceText: 'value',
            chartArea:{left:20,top:0,width:'100%',height:'100%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('spsChart'));

        data.sort([{column: 1, desc: true}]);
        chart.draw(data, options);
    }

    function drawSpsTable() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/tables_month}'); ?>');
        data.addColumn('string', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/tables_service_provider}'); ?>');
        data.addColumn('number', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/count}'); ?>');
        data.addRows([<?php DatabaseCommand::getAccessToServicesPerMonth()?>]);

        var table = new google.visualization.Table(document.getElementById('spsTable'));

        var formatter = new google.visualization.DateFormat({pattern:"MMMM  yyyy"});
        formatter.format(data, 0); // Apply formatter to second column

        table.draw(data);
    }
</script>
</head>

<body>
<div class="timeRange">
    <h4><?php echo $this->t('{proxystatistics:Proxystatistics:templates_time_range}'); ?></h4>
    <form id="dateSelector" method="post" >
        <input name="tab" value="1" hidden>
        <label>
            <input id="1" type="radio" name="lastDays" value=0 onclick="this.form.submit()" <?php echo ($lastDays == 0) ? "checked=true" : ""  ?> > <?php echo $this->t('{proxystatistics:Proxystatistics:templates/statistics-tpl_all}'); ?>
        </label>
        <label>
            <input id="2" type="radio" name="lastDays" value=7 onclick="this.form.submit()" <?php echo ($lastDays == 7) ? "checked=true" : "" ?>> <?php echo $this->t('{proxystatistics:Proxystatistics:templates/statistics-tpl_week}'); ?>
        </label>
        <label>
            <input id="3" type="radio" name="lastDays" value=30 onclick="this.form.submit()" <?php echo ($lastDays == 30) ? "checked=true" : "" ?>> <?php echo $this->t('{proxystatistics:Proxystatistics:templates/statistics-tpl_month}'); ?>
        </label>
        <label>
            <input id="4" type="radio" name="lastDays" value=365 onclick="this.form.submit()" <?php echo ($lastDays == 365) ? "checked=true" : "" ?>> <?php echo $this->t('{proxystatistics:Proxystatistics:templates/statistics-tpl_year}'); ?>
        </label>
    </form>
</div>

<h2><?php echo $this->t('{proxystatistics:Proxystatistics:templates/graphs_logins}'); ?></h2>
<div id="loginsDashboard" >
    <div id="line_div"></div>
    <div id="control_div"></div>
</div>

<div class="row tableMaxHeight">
    <div class="col-md-6 graph">
        <h2><?php echo $this->t('{proxystatistics:Proxystatistics:templates/graphs_id_providers}'); ?></h2>
        <div id="idpsChart" ></div>
    </div>
    <div class="col-md-6">
        <h2><?php echo $this->t('{proxystatistics:Proxystatistics:templates/graphs_service_providers}'); ?></h2>
        <div id="spsChart" ></div>
    </div>
</div>
</body>
