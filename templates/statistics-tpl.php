<?php
include dirname(__DIR__)."/lib/Auth/Process/DatabaseCommand.php";
/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 * @author Dominik Baránek <0Baranek.dominik0@gmail.com>
 */
$this->data['header'] = $this->t('{proxystatistics:Proxystatistics:templates/statistics_header}');

$this->data['jquery'] = array('core' => TRUE, 'ui' => TRUE, 'css' => TRUE);
$this->data['head'] = '<link rel="stylesheet"  media="screen" type="text/css" href="' . SimpleSAML\Module::getModuleUrl('proxystatistics/statisticsproxy.css')  . '" />';
$this->data['head'] .='';
$this->data['head'] .= '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
$this->data['head'] .= '<script type="text/javascript">
$(document).ready(function() {
	$("#tabdiv").tabs();
});
</script>';

$this->includeAtTemplateBase('includes/header.php');

if (!isset($_POST['lastDays'])) {
	$_POST['lastDays'] = 0;
}

if (!isset($_POST['tab'])) {
	$_POST['tab'] = 1;
}
?>

<div id="tabdiv">
    <ul class="tabset_tabs" width="100px">
        <li><a id="tab-1" href='<?php echo "summary.php?lastDays=" . $_POST['lastDays'];?>'><?php echo $this->t('{proxystatistics:Proxystatistics:summary}'); ?></a></li>
        <li><a id="tab-2" href='<?php echo "identityProviders.php?lastDays=" . $_POST['lastDays'];?>'><?php echo $this->t('{proxystatistics:Proxystatistics:templates/statistics-tpl_idpsDetail}'); ?></a></li>
        <li><a id="tab-3" href='<?php echo "serviceProviders.php?lastDays=" . $_POST['lastDays'];?>'><?php echo $this->t('{proxystatistics:Proxystatistics:templates/statistics-tpl_spsDetail}'); ?></a></li>
    </ul>
</div>

<script>
    window.onload = function() {
		<?php echo "$('#tab-" . $_POST['tab'] . "').click();"; ?>
    }
</script>

<?php
$this->includeAtTemplateBase('includes/footer.php');
?>
