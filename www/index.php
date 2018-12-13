<?php
/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */

$config = SimpleSAML_Configuration::getInstance();
$session = SimpleSAML_Session::getSessionFromRequest();

$t = new SimpleSAML_XHTML_Template($config, 'proxystatistics:statistics-tpl.php');
if(!isset($_POST['lastDays']) || !isset($_POST['tab'])) {
	$_POST['lastDays'] = 0;
	$_POST['tab'] = 1;
}
$t->data['lastDays'] = $_POST['lastDays'];
$t->data['tab'] = $_POST['tab'];
$t->show();



?>
