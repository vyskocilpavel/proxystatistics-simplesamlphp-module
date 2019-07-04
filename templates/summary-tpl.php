<?php

use SimpleSAML\Module;

/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 * @author Dominik Baránek <0Baranek.dominik0@gmail.com>
 */

?>

<?php
require 'timeRange.include.php';
?>

<h2><?php echo $this->t('{proxystatistics:Proxystatistics:templates/graphs_logins}'); ?></h2>
<div class="legend-logins">
    <div>
        <?php echo $this->t('{proxystatistics:Proxystatistics:templates/summary_logins_info}'); ?>
    </div>
</div>
<div id="loginsDashboard">
    <div id="line_div"></div>
    <div id="control_div"></div>
</div>

<div class="row tableMaxHeight">
    <div class="<?php echo $this->data['summaryGraphs']['identityProviders'] ?>">
        <h2><?php echo $this->t('{proxystatistics:Proxystatistics:templates/graphs_id_providers}'); ?></h2>
        <div class="row">
            <div class="<?php echo $this->data['summaryGraphs']['identityProvidersLegend'] ?>">
                <div class="legend">
                    <div id="summaryIdp">
                        <?php echo $this->t('{proxystatistics:Proxystatistics:templates/summary_idps_info}'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="<?php echo $this->data['summaryGraphs']['identityProvidersGraph'] ?>">
                <div id="idpsChart" class="pieChart chart-idpsChart"></div>
            </div>
        </div>
    </div>
    <div class="<?php echo $this->data['summaryGraphs']['serviceProviders'] ?>">
        <h2><?php echo $this->t('{proxystatistics:Proxystatistics:templates/graphs_service_providers}'); ?></h2>
        <div class="row">
            <div class="<?php echo $this->data['summaryGraphs']['serviceProvidersLegend'] ?>">
                <div class="legend">
                    <div>
                        <?php echo $this->t('{proxystatistics:Proxystatistics:templates/summary_sps_info}'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="<?php echo $this->data['summaryGraphs']['serviceProvidersGraph'] ?>">
                <div id="spsChart" class="pieChart chart-spsChart"></div>
            </div>
        </div>
    </div>
</div>
