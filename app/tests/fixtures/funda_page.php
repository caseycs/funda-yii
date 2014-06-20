<?php
$result = array();
for ($i = 1; $i <= 2; $i ++) {
    for ($j = 1; $j <= 2; $j ++) {
        $result[] = array(
            'id' => $i * 1000 + $j,
            'funda_filter_id' => $i,
            'number' => $j,
        );
    }
}
return $result;
