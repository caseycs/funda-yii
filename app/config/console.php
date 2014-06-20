<?php
return CMap::mergeArray(
    require(dirname(__FILE__) . '/common.php'),
    array(
        'commandMap' => array(
            'fundafetch' => array(
                'class' => 'application.commands.FundaFetchCommand',
                'rpm_limit' => 60,
                'page_expire' => 60 * 120, // 120 minutes, so we retrieve each page every 2 hours
                'pages_limit' => 100, // request 100 pages before die per script run
            ),
        ),
        // application components
        'components' => array(
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    //additional full output to stdout in console
                    array(
                        'class' => 'ext.StdoutLogRoute',
//                        'levels' => 'info,profile,warning,error',
                        'levels' => 'trace,info,profile,warning,error', //for developing purposes
                    ),
                ),
            ),
        ),
    )
);
