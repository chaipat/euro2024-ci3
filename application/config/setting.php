<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*************** REDIS ***************/
$config['radis_server'] = 'tls://db-redis-sgp1-34551-do-user-7497661-0.a.db.ondigitalocean.com';
$config['radis_port'] = '25061';
$config['radis_auth'] = 'qls90jlkcqod1vj5';

if (in_array(ENVIRONMENT, ['production'])) {
    $config['radis_prefix'] = "wc2022-front-";
}else if (in_array(ENVIRONMENT, ['staging'])) {
    $config['radis_prefix'] = "wc2022-staging-";
}else{
    $config['radis_prefix'] = "wc2022-local-";
}

$config['cache_enable'] = true;
$config['html_compress'] = false;