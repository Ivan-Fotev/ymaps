<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Офисы на карте");
?><?$APPLICATION->IncludeComponent(
	"fis:ymaps",
	"",
	Array(
		"API_KEY" => "92aee9ca-e36e-4761-8b7a-6d1ea749c438"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>