<?php

declare(strict_types=1);

/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 * @author Pavel Břoušek <brousek@ics.muni.cz>
 */

namespace SimpleSAML\Module\proxystatistics\Auth\Process;

use DateTime;
use SimpleSAML\Auth\ProcessingFilter;
use SimpleSAML\Module\proxystatistics\DatabaseCommand;

class Statistics extends ProcessingFilter
{
    public function __construct($config, $reserved)
    {
        parent::__construct($config, $reserved);
    }

    public function process(&$request)
    {
        $dateTime = new DateTime();
        $dbCmd = new DatabaseCommand();
        $dbCmd->insertLogin($request, $dateTime);
    }
}
