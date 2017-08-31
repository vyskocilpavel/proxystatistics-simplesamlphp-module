<?php
include dirname(__DIR__)."/lib/Auth/Process/DatabaseCommand.php";
/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */
?>
<link rel="stylesheet"  media="screen" type="text/css" href="<?php SimpleSAML_Module::getModuleUrl('proxystatistics/statisticsproxy.css')?>" />
<h2>All login</h2>
<div id="tableOfAllLogin">
    <script type="text/javascript">
        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(drawTable);

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Count');
            data.addRows([<?php DatabaseCommand::getLoginCountPerDay()?>]);

            var table = new google.visualization.Table(document.getElementById('tableOfAllLogin'));

            var formatter = new google.visualization.DateFormat({pattern:"d  MMMM  yyyy"});
            formatter.format(data, 0); // Apply formatter to second column

            table.draw(data, {showRowNumber: false, width: '60%', height: '300px'});
        }
    </script>

    <div id="tableOfAllLogin" ></div>
</div>



<h2>All login per IdP</h2>
<div>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(drawTable);

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('string', 'SourceIdp');
            data.addColumn('number', 'Count');
            data.addRows([<?php DatabaseCommand::getLoginCountPerDeyPerService()?>]);

            var table = new google.visualization.Table(document.getElementById('tablePerIdP'));

            var formatter = new google.visualization.DateFormat({pattern:"MMMM  yyyy"});
            formatter.format(data, 0); // Apply formatter to second column

            table.draw(data, {showRowNumber: false, width: '100%', height: '300px'});
        }
    </script>

    <div id="tablePerIdP"></div>
</div>

<h2> All access to the services</h2>
<div>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(drawTable);

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('string', 'Service');
            data.addColumn('number', 'Count');
            data.addRows([<?php DatabaseCommand::getAccessToServicesPerMonth()?>]);

            var table = new google.visualization.Table(document.getElementById('accessToServices'));

            var formatter = new google.visualization.DateFormat({pattern:"MMMM  yyyy"});
            formatter.format(data, 0); // Apply formatter to second column

            table.draw(data, {showRowNumber: false, width: '100%', height: '300px'});
        }
    </script>

    <div id="accessToServices"></div>
</div>

