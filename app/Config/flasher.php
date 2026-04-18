<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

return array(
    'default' => 'toastr',
    'root_script' => '/vendor/flasher/flasher.min.js',
    'use_cdn' => false,
    'adapters' => array(
        'toastr' => array(
            'scripts' => array('/vendor/flasher/flasher-toastr.min.js'),
            'styles'  => array('/vendor/flasher/flasher-toastr.min.css'),
            'options' => array(
                'positionClass' => 'toast-bottom-right',
            ),
        ),
        'sweetalert' => array(
            'scripts' => array('/vendor/flasher/flasher-sweetalert.min.js'),
            'styles'  => array('/vendor/flasher/flasher-sweetalert.min.css'),
        ),
    ),
);
  
