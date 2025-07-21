<?php
$schedule = new \App\Schedule\Schedule();

$weekly = $schedule->shell('newsletter weekly')
    ->weekly()
    ->description('Weekly Newsletter');

$monthly = $schedule->shell('newsletter monthly')
    ->monthly()
    ->description('Monthly Newsletter');

$monthly = $schedule->shell('newsletter quarterly')
    ->quarterly()
    ->description('Quarterly Newsletter');


return $schedule;