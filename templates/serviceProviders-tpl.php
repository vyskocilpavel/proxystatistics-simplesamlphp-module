<?php
include dirname(__DIR__)."/lib/Auth/Process/DatabaseCommand.php";

/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 * @author Dominik Baránek <0Baranek.dominik0@gmail.com>
 */

$lastDays = $this->data['lastDays'];

?>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'table']});
    google.charts.setOnLoadCallback(drawSpsChart);
    google.charts.setOnLoadCallback(drawSpsTable);

    function drawSpsChart() {
        var data = google.visualization.arrayToDataTable([
            ['service', 'Count'],
			<?php DatabaseCommand::getAccessCountPerService($lastDays)?>
        ]);

        var options = {
            pieSliceText: 'value',
            chartArea:{left:20,top:0,width:'100%',height:'100%'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('spsChartDetail'));

        data.sort([{column: 1, desc: true}]);
        chart.draw(data, options);
    }

    function drawSpsTable() {
        var data = new google.visualization.DataTable();
        
        data.addColumn('string', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/tables_service_provider}'); ?>');
        data.addColumn('number', '<?php echo $this->t('{proxystatistics:Proxystatistics:templates/count}'); ?>');
        data.addRows([<?php DatabaseCommand::getAccessCountPerService($lastDays)?>]);

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
        <input name="tab" value="3" hidden>
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

<h2><?php echo $this->t('{proxystatistics:Proxystatistics:templates/graphs_service_providers}'); ?></h2>
<div class="legend">
    <div><?php echo $this->t('{proxystatistics:Proxystatistics:templates/serviceProviders_legend}'); ?></div>
</div>
<div class="row">
    <div class="col-md-8">
        <div id="spsChartDetail" ></div>
    </div>
    <div class="col-md-4">
        <div id="spsTable" ></div>
    </div>
</div>
</body>
