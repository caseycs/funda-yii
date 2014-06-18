<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return CMap::mergeArray(
    require(dirname(__FILE__) . '/common.php'),
    array(
        'commandMap' => array(
            'fundafetch' => array(
                'class' => 'application.commands.FundaFetchCommand',
                'rpm_limit' => 90,
                'page_expire' => 600, // 10 minutes
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
