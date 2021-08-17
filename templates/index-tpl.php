<?php declare(strict_types=1);

/**
 * @author Pavel Břoušek <brousek@ics.muni.cz>
 */

use SimpleSAML\Module\proxystatistics\Config;

$this->includeAtTemplateBase('includes/header.php');
?>

<div id="tabdiv" data-activetab="<?php echo htmlspecialchars($this->data['tab']); ?>">
    <ul class="tabset_tabs" width="100px">
        <li>
            <a <?php echo $this->data['tabsAttributes']['PROXY'] ?>>
                <?php echo $this->t('{proxystatistics:stats:summary}'); ?>
            </a>
        </li>
        <?php foreach (Config::SIDES as $side) : ?>
        <li>
            <a <?php echo $this->data['tabsAttributes'][$side] ?>>
                <?php echo $this->t('{proxystatistics:stats:side' . $side . 'Detail}'); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
$this->includeAtTemplateBase('includes/footer.php');
?>
