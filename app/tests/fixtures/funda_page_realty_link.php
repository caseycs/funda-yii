<?php
$result = array();
for ($i = 1; $i <= 2; $i ++) {
    for ($j = 1; $j <= 100; $j ++) {
        $result[] = array(
            'd_funda_filter_id' => $i,
            'funda_page_id' => $i + ($j % 2),
            'realty_id' => $j,
        );
    }
}
return $result;
