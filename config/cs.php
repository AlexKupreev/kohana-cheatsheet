<?php defined('SYSPATH') OR die('No direct access allowed.');
return array
(
    /* classes to exclude from sheet. Names can be set precisely or using a mask. For masked ones 
     only '*' placeholder is currently supported in such variants: '*someword*', 'someword*' and '*someword' */
    'exclude_class' => array (
        '*kodoc*', '*bench*', 'controller*', 'model_*', 'database_*', 'kohana_log_*', 'kohana_config_*', '*exception', 'http_exception*'
        ),
    // should data be cached (recommended, cache is real data consistent, not on time expiration)
    'cache' => TRUE,
);
