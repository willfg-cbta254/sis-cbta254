<?php

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Spatie\Backup\Tasks\Backup\DbDumperFactory;

try {
    $dumper = DbDumperFactory::createFromConnection('mysql');
    $ref = new ReflectionClass($dumper);

    $extraOpts = $ref->getProperty('extraOptions');
    $extraOpts->setAccessible(true);
    echo 'Extra options: '.json_encode($extraOpts->getValue($dumper)).PHP_EOL;

    $skipSsl = $ref->getProperty('skipSsl');
    $skipSsl->setAccessible(true);
    echo 'Skip SSL: '.($skipSsl->getValue($dumper) ? 'true' : 'false').PHP_EOL;

    $host = $ref->getProperty('host');
    $host->setAccessible(true);
    echo 'Host: '.$host->getValue($dumper).PHP_EOL;

    $socket = $ref->getProperty('socket');
    $socket->setAccessible(true);
    echo 'Socket: "'.$socket->getValue($dumper).'"'.PHP_EOL;

    $dumpBinPath = $ref->getProperty('dumpBinaryPath');
    $dumpBinPath->setAccessible(true);
    echo 'DumpBinaryPath: "'.$dumpBinPath->getValue($dumper).'"'.PHP_EOL;

    $getCmd = $ref->getMethod('getDumpCommand');
    $getCmd->setAccessible(true);
    $getCreds = $ref->getMethod('getContentsOfCredentialsFile');
    $getCreds->setAccessible(true);

    echo PHP_EOL.'CREDENTIALS:'.PHP_EOL;
    echo $getCreds->invoke($dumper).PHP_EOL;

    echo PHP_EOL.'COMMAND:'.PHP_EOL;
    echo $getCmd->invoke($dumper, 'test_dump.sql', 'test_cred.cnf').PHP_EOL;

    echo PHP_EOL.'Testing actual dump...'.PHP_EOL;
    $tempDump = tempnam(sys_get_temp_dir(), 'bkp_');
    $dumper->dumpToFile($tempDump);
    echo 'Success! File size: '.filesize($tempDump).PHP_EOL;
    unlink($tempDump);

} catch (Exception $e) {
    echo 'ERROR: '.$e->getMessage().PHP_EOL;
    echo 'Class: '.get_class($e).PHP_EOL;
    if ($e->getPrevious()) {
        echo 'Previous: '.$e->getPrevious()->getMessage().PHP_EOL;
    }
}
