<?php
$result = array();
for ($i = 1; $i < 100; $i++) {
    $result[] = array(
        'id' => $i,
        'GlobalId' => $i,
        'agent_id' => ($i % 10) + 1,
    );
}
return $result;
