<?php

declare(strict_types=1);

use SimpleSAML\Logger;
use SimpleSAML\Module\proxystatistics\DatabaseCommand;

/**
 * Hook to run a cron job.
 *
 * @param array $croninfo  Output
 * @author Pavel Břoušek <brousek@ics.muni.cz>
 */
function proxystatistics_hook_cron(&$croninfo)
{
    if ($croninfo['tag'] !== 'daily') {
        Logger::debug('cron [proxystatistics]: Skipping cron in cron tag [' . $croninfo['tag'] . '] ');
        return;
    }

    Logger::info('cron [proxystatistics]: Running cron in cron tag [' . $croninfo['tag'] . '] ');

    try {
        $dbCmd = new DatabaseCommand();
        $dbCmd->aggregate();
    } catch (\Exception $e) {
        $croninfo['summary'][] = 'Error during statistics aggregation: ' . $e->getMessage();
    }
}
