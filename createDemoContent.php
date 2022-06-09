<?php
/**
 * Created by PhpStorm.
 * Filename: createDemoContent.php
 * Project Name: gpn-test
 * User: Ivan
 * Date: 09/06/2022
 * Time: 19:00
 * Github: https://github.com/Ivan-Fotev
 * Telegram: @ifotev
 */

namespace FIS;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';


class CreateDemoContent
{
    protected static $typeIblockId = 'fis';
    protected static $IblockCode = 'fisymaps';
    protected static $IblockId = 0;
    protected static $IblockProp = [];

 
    public static function generete()
    {
        if (\CModule::IncludeModule("iblock")) {
            if (!self::issetIblockType()) {
                self::createIblockType();
            }

            if (!self::issetIblock()) {
                self::createIblock();
            }

            self::сreateIblockProp();

            self::createIblockElement();
        }
    }

    protected function getIblockTypeId(): string
    {
        return static::$typeIblockId;
    }

    protected function getIblockCode(): string
    {
        return static::$IblockCode;
    }

    protected function issetIblockType(): bool
    {
        $result = false;
        $ibType = \CIBlockType::GetByID(self::getIblockTypeId());
        $arr = $ibType->Fetch();

        if (is_array($arr) && $arr['ID'] == self::getIblockTypeId()) {
            $result = true;
        }

        return $result;
    }

    protected function createIblockType(): void
    {
        $arFields = [
            'ID' => self::getIblockTypeId(),
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => [
                'ru' => [
                    'NAME' => 'FIS',
                ]
            ]
        ];

        $ob = new \CIBlockType;
        $ob->Add($arFields);

    }

    protected function createIblock(): void
    {
        $arFields = [
            'ACTIVE' => 'Y',
            'NAME' => 'Офисы',
            'CODE' => self::getIblockCode(),
            'API_CODE' => self::getIblockCode(),
            'IBLOCK_TYPE_ID' => self::getIblockTypeId(),
            'SITE_ID' => self::getSite(),
            'GROUP_ID' => ['2' => 'R'],
            'FIELDS' => [
                'CODE' => [
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE' => 'Y',
                        'TRANSLITERATION' => 'Y',
                        'TRANS_LEN' => '16',
                        'TRANS_CASE' => 'L',
                        'TRANS_SPACE' => '-',
                        'TRANS_OTHER' => '-',
                        'TRANS_EAT' => 'Y',
                        'USE_GOOGLE' => 'N',
                    ]
                ]
            ],
            'WORKFLOW'  => 'N',
            'BIZPROC'   => 'N'
        ];

        $ib = new \CIBlock;
        $ib->Add($arFields);
    }

    protected function сreateIblockProp(): void
    {

        if (intval(self::getIblockId()) > 0) {
            $property = [
                [
                    'NAME' => 'Телефон',
                    'ACTIVE' => 'Y',
                    'CODE' => 'PHONE',
                    'PROPERTY_TYPE' => 'S',
                    'IBLOCK_ID' => self::getIblockId()
                ],
                [
                    'NAME' => 'Email',
                    'ACTIVE' => 'Y',
                    'CODE' => 'EMAIL',
                    'PROPERTY_TYPE' => 'S',
                    "IBLOCK_ID" => self::getIblockId()
                ],
                [
                    'NAME' => 'Координаты',
                    'ACTIVE' => 'Y',
                    'CODE' => 'COORDINATES',
                    'PROPERTY_TYPE' => 'S',
                    'USER_TYPE' => 'map_yandex',
                    "IBLOCK_ID" => self::getIblockId()
                ],
                [
                    'NAME' => 'Город',
                    'ACTIVE' => 'Y',
                    'CODE' => 'CITY',
                    'PROPERTY_TYPE' => 'S',
                    "IBLOCK_ID" => self::getIblockId()
                ]
            ];

            $ibp = new \CIBlockProperty;

            foreach ($property as $prop) {
                if (!self::issetIblockProp($prop['CODE'])) {
                    $ibp->Add($prop);
                }
            }
        }
    }


    protected function issetIblockProp($code = ''): bool
    {
        $result = false;

        foreach (self::getIblockProp() as $prop) {
            if ($code == $prop['CODE']) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    protected function getIblockProp(): array
    {
        if (is_array(self::$IblockProp) && count(self::$IblockProp) == 0) {
            $propCode = [];
            $dbProperties = \CIBlockProperty::GetList(
                [],
                [
                    'IBLOCK_ID' => self::getIblockId()
                ]
            );
            while ($arProp = $dbProperties->Fetch()) {
                $propCode[] = [
                    'ID' => $arProp['ID'],
                    'CODE' => $arProp['CODE']
                ];
            }

            self::$IblockProp = $propCode;
        }

        return self::$IblockProp;
    }

    protected function issetIblock(): bool
    {
        $result = false;

        if (intval(self::getIblockId()) > 0) {
            $result = true;
        }

        return $result;
    }

    protected function getIblockId(): int
    {
        if (self::$IblockId == 0) {
            $arr = \CIBlock::GetList(
                [],
                [
                    'TYPE' => self::getIblockTypeId(),
                    'CODE' => self::getIblockCode()
                ], true
            )->Fetch();

            if (is_array($arr) && $arr['CODE'] == self::getIblockCode()) {
                self::$IblockId = $arr['ID'];
            }
        }

        return self::$IblockId;
    }

    protected function createIblockElement(): void
    {
        $elements = [
            [
                'NAME' => 'Офис №1',
                'IBLOCK_ID' => self::getIblockId(),
                'PROPERTY_VALUES' => [
                    'PHONE' => '8-931-589-60-71',
                    'CITY' => 'Санкт-Петербург',
                    'EMAIL' => 'office1@email.ru',
                    'COORDINATES' => '59.940262, 30.369614'
                ]
            ],
            [
                'NAME' => 'Офис №2',
                'IBLOCK_ID' => self::getIblockId(),
                'PROPERTY_VALUES' => [
                    'PHONE' => '8-931-589-60-72',
                    'CITY' => 'Санкт-Петербург',
                    'EMAIL' => 'office2@email.ru',
                    'COORDINATES' => '59.901766, 30.323836'
                ]
            ],
            [
                'NAME' => 'Офис №3',
                'IBLOCK_ID' => self::getIblockId(),
                'PROPERTY_VALUES' => [
                    'PHONE' => '8-931-589-60-73',
                    'CITY' => 'Санкт-Петербург',
                    'EMAIL' => 'office3@email.ru',
                    'COORDINATES' => '59.943025, 30.370611'
                ]
            ],
            [
                'NAME' => 'Офис №4',
                'IBLOCK_ID' => self::getIblockId(),
                'PROPERTY_VALUES' => [
                    'PHONE' => '8-931-589-60-74',
                    'CITY' => 'Москва',
                    'EMAIL' => 'office4@email.ru',
                    'COORDINATES' => '55.654142, 37.555885'
                ]
            ],
            [
                'NAME' => 'Офис №5',
                'IBLOCK_ID' => self::getIblockId(),
                'PROPERTY_VALUES' => [
                    'PHONE' => '8-931-589-60-75',
                    'CITY' => 'Новосибирск',
                    'EMAIL' => 'office5@email.ru',
                    'COORDINATES' => '55.027325, 82.924768'
                ]
            ],
            [
                'NAME' => 'Офис №6',
                'IBLOCK_ID' => self::getIblockId(),
                'PROPERTY_VALUES' => [
                    'PHONE' => '8-931-589-60-76',
                    'CITY' => 'Екатеринбург',
                    'EMAIL' => 'office6@email.ru',
                    'COORDINATES' => '56.826237, 60.645903'
                ]
            ]
        ];

        $param = ['max_len' => 12, 'replace_space' => '_', 'replace_other' => '_'];
        $el = new \CIBlockElement;

        foreach ($elements as $key => $element) {
            $element['CODE'] = \Cutil::translit(strtolower($element['NAME']), "ru", $param);

            foreach ($element['PROPERTY_VALUES'] as $code => $value) {
                $propId = self::changePropCodeToId($code);
                if (intval($propId) > 0) {
                    $element['PROPERTY_VALUES'][$propId] = $value;
                    unset($element['PROPERTY_VALUES'][$code], $propId);
                }
            }

            $el->Add($element);
        }
    }

    protected function changePropCodeToId($propCode = []): int
    {
        $id = 0;
        $iblockProps = self::getIblockProp();

        foreach ($iblockProps as $prop) {
            if ($prop['CODE'] == $propCode) {
                $id = $prop['ID'];
            }
        }

        return $id;
    }

    protected function getSite(): array
    {
        $arSiteId = [];

        $rsSites = \CSite::GetList($by = "sort", $order = "desc", []);
        while ($arSite = $rsSites->Fetch()) {
            $arSiteId[] = $arSite['ID'];
        }

        return $arSiteId;
    }
}