<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

$this->addExternalJs('https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey='.$arParams['API_KEY']);
?>
<script>
    let fisYmapsElements = <?php echo CUtil::PHPToJsObject($arResult['DATA']);?>
</script>

<div class="fis-yamap">
    <div id="fis_ymap__map"></div>
</div>