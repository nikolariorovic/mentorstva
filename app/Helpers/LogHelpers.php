<?php
/**
 * @param string $message
 */
function logError(string $message): void
{
    $logFile = dirname(__DIR__, 2) . '/storage/logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}