<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    "NAME"          => Loc::getMessage("FIS_YMAPS_COMP_NAME"),
    "DESCRIPTION"   => Loc::getMessage("FIS_YMAPS_COMP_DESC"),
    "PATH"          => [
        "ID"        => "FIS",
        "NAME"  => Loc::getMessage("FIS_YMAPS_COMP_NAMESPACE"),
    ]
];