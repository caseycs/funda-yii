<?php
return CMap::mergeArray(
    require(dirname(__FILE__) . '/common.php'),
    array(
        // application components
        'components' => array(
            'errorHandler' => array(
                // use 'site/error' action to display errors
                'errorAction' => 'site/error',
            ),
            'request' => array(
                'baseUrl' => '', // we use abother directory structure, so we need to change baseUrl
            ),
            'viewRenderer' => array(
                'class' => 'application.extensions.EMustache.EMustacheViewRenderer',
            ),
            'urlManager' => array(
                'urlFormat' => 'path',
            ),
        ),
    )
);
