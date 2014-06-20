<?php
$result = array();
for ($i = 1; $i < 11; $i++) {
    $result[] = array(
        'id' => $i,
        'MakelaarId'=> $i,
        'MakelaarNaam'=>'name ' . $i,
    );
}
return $result;
