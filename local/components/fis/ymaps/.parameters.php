<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    'PARAMETERS'    => [
        'API_KEY'   => [
            'NAME'      => Loc::getMessage('FIS_YMAPS_PARAM_MAP_API_KEY'),
            'TYPE'      => 'STRING',
            'DEFAULT'   => '92aee9ca-e36e-4761-8b7a-6d1ea749c438',
            'PARENT'    => 'BASE',
        ]
    ]
];