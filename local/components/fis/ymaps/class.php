<?php
/**
 * Created by PhpStorm.
 * Filename: class.php
 * Project Name: gpn-test
 * User: Ivan
 * Date: 09/06/2022
 * Time: 19:05
 * Github: https://github.com/Ivan-Fotev
 * Telegram: @ifotev
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Main\LoaderException,
    Bitrix\Main\SystemException,
    Bitrix\Iblock\Elements\ElementFisymapsTable,
    Bitrix\Iblock\IblockTable,
    Bitrix\Main\Data\Cache;

/**
 * Class FISYmapsComponent
 */
class FISYmapsComponent extends CBitrixComponent
{
    protected $officeList = [];

    /*
     * Обработка входных параметров компонента
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['API_KEY'] = trim($arParams['API_KEY']);

        try {
            if(strlen($arParams['API_KEY']) == 0){
                throw new SystemException(Loc::getMessage('FIS_YMAPS_CLASS_EMPTY_API_KEY'));
            }
        }catch (SystemException $exception){
            showError($exception->getMessage());
        }

        return parent::onPrepareComponentParams($arParams);
    }

    /**
     * Проверка и подключение зависимостей
     */
    private function checkModules() : void
    {
        try
        {
            if (!Loader::includeModule('iblock'))
            {
                throw new LoaderException(Loc::getMessage('FIS_YMAPS_CLASS_IBLOCK_NOT_FOUND'));
            }
        }
        catch (LoaderException $exception)
        {
            showError($exception->getMessage());
        }
    }

    /**
     * Получние данных из информационного блока
     */
    protected function prepareData(): void
    {
        $offices        = [];
        $cache          = Cache::createInstance();
        $taggedCache    = Application::getInstance()->getTaggedCache();
        $cachePath      = str_replace(':','/',$this->getName());
        $cacheTtl       = 86400;
        $cacheKey       = md5($this->getName().'_'.$this->arParams['API_KEY']);

        if ($cache->initCache($cacheTtl, $cacheKey, $cachePath)) {
            $offices = $cache->getVars();
        }elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache($cachePath);

            $iblockId = 0;
            $elemenst = ElementFisymapsTable::getList([
                'select' => [
                    'ID',
                    'IBLOCK_ID',
                    'NAME',
                    'PHONE',
                    'EMAIL',
                    'COORDINATES',
                    'CITY'
                ],
                'filter' => [
                    '=ACTIVE' => 'Y'
                ],
            ])->fetchCollection();

            foreach ($elemenst as $element) {
                if($iblockId == 0){
                    $iblockId = $element->getIblockId();
                }

                $coordinates = explode(',', $element->getCoordinates()->getValue());

                if(count($coordinates) == 2 && strlen($element->getName()) > 0){
                    $offices[] = [
                        'ID'            => $element->getId(),
                        'NAME'          => $element->getName(),
                        'PHONE'         => $element->getPhone()->getValue(),
                        'EMAIL'         => $element->getEmail()->getValue(),
                        'COORDINATES'   => $coordinates,
                        'CITY'          => $element->getCity()->getValue(),
                    ];
                }

                unset($coordinates);
            }

            $taggedCache->registerTag('iblock_id_'.$iblockId);
            $taggedCache->endTagCache();
            $cache->endDataCache($offices);
        }

        $this->officeList = $offices;
    }

    /*
     * Преобразование данных в нужную структуру для гео объекта
     */
    protected function  formatData(): void
    {
        $arPropsTemplate = [
            'type'      => 'FeatureCollection',
            'features'  =>   []
        ];

        foreach ($this->officeList as $office){
            $feature[] =  [
                'type'      => 'Feature',
                'id'        => $office['ID'],
                'geometry'  =>
                    [
                        'type'          => 'Point',
                        'coordinates'   => $office['COORDINATES']
                    ],
                'properties' => [
                    'balloonContentHeader' => $office['NAME'],
                    'balloonContentBody'   => $this->createBalloonContentBody($office)
                ]
            ];
        }

        try {
            if(count($feature) == 0){
                throw new SystemException(Loc::getMessage('FIS_YMAPS_CLASS_EMPTY_DATA'));
            }
        }catch (SystemException $e){
            showError($e->getMessage());
        }

        $arPropsTemplate['features'] = $feature;
        $this->arResult['DATA'] = $arPropsTemplate;
    }

    /**
     * Формирование html структуры для popup на карте
     *
     * @param  array  $item
     * @return string
     */
    protected function createBalloonContentBody($item = []): string
    {
        $arFields = ['CITY','PHONE','EMAIL'];
        $html   = '';

        foreach ($arFields as $field){
            $value = $item[$field];
            if(isset($value) && strlen($value) > 0){
                $html .= '<p>'.Loc::getMessage('FIS_YMAPS_CLASS_HTML_FIELD_'.$field).' : '.$value;
            }
            unset($value);
        }

        return $html;
    }

    /**
     * Проверка сущестования инфорамационного блока
     *
     * @return bool
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    protected function issetIblock(): bool
    {
        $result = false;

        $iblock = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['CODE' => 'fisymaps']
        ])->fetch();

        if(isset($iblock['ID'])){
           $result = true;
        }

        return $result;
    }

    public function executeComponent(): void
    {
        $this->checkModules();
        if(self::issetIblock()){
            $this->prepareData();
            $this->formatData();

            \CUtil::InitJSCore();
            $this->includeComponentTemplate();
        }else{
            showError(Loc::getMessage('FIS_YMAPS_CLASS_NOT_ISSET_IBLOCK'));
        }
    }
}