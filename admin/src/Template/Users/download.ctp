<?php

$line = $users->toArray();

$filename = 'volunteers-' . date('Y-m-d H:i:s') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$filename);

$output = fopen('php://output', 'w');
ob_end_clean();

$liner = json_decode(json_encode($line), True);
fputcsv($output, array_keys($liner[0]));

foreach(array_values($line) as $line_new){
    $row = json_decode(json_encode($line_new), True);
    fputcsv($output, $row);
}

exit;
