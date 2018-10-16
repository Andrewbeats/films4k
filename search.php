<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-type: application/xml; charset=UTF-8');

$searchValue = "";
if (isset($_GET['search'])) {
	$searchValue = $_GET['search'];
}

function openXML() {
    // open xml file
    $inputFile = new DOMDocument();
    $inputFile->load("test.xml"); 
    // return opened file
    return $inputFile;
}

$outputXml = new DOMDocument("1.0", "UTF-8");
$outputXml->formatOutput = true;
$xml_items = $outputXml->createElement('items');
$outputXml->appendChild($xml_items);

function addChannelInfo($fTitle, $fLogo, $fDescription, $fStream_url) {
	global $outputXml;
	global $xml_items;
	// create channel
	$xml_channel_node = $outputXml->createElement('channel');
	$xml_items->appendChild($xml_channel_node);

	// create title
	$xml_title_node = $outputXml->createElement('title');
	$xml_channel_node->appendChild($xml_title_node);
	$xml_title_CData = $outputXml->createCDATASection("$fTitle");
	$xml_title_node->appendChild($xml_title_CData);

	// create logo
	$xml_logo_node = $outputXml->createElement('logo');
	$xml_channel_node->appendChild($xml_logo_node);
	$xml_logo_CData = $outputXml->createCDATASection("$fLogo");
	$xml_logo_node->appendChild($xml_logo_CData);

	// create description
	$xml_description_node = $outputXml->createElement('description');
	$xml_channel_node->appendChild($xml_description_node);
	$xml_description_CData = $outputXml->createCDATASection("$fDescription");
	$xml_description_node->appendChild($xml_description_CData);

	// create stream_url
	$xml_stream_url_node = $outputXml->createElement('stream_url');
	$xml_channel_node->appendChild($xml_stream_url_node);
	$xml_stream_url_CData = $outputXml->createCDATASection("$fStream_url");
	$xml_stream_url_node->appendChild($xml_stream_url_CData);
}

$EMPTY_FIELD = 'Пустая строка';
$LOW_VALUE = 'Строка меньше 4-х символов';
$NOT_FOUND = 'Ничего не найдено';
function getErrorMessage($typeErrorMessage) {
	global $searchValue;
	
	function getStyleErrorMessage($errorMessageText) {
		return "<table style=\"width:100%;font-size:32px;text-align:left;\"><tr><td><font color=\"#0000ee\"><b>$errorMessageText</b></font></td></tr></table>";
	}
	
	$messageIfEmptyValue = "Введена пустая строка. Вернитесь назад и измените текст поиска.";
	$messageIfLowValue = "Текст \"$searchValue\" содержит меньше 4-х символов. Вернитесь назад и измените текст поиска.";
	$messageIfNotFound = "Не могу найти текст \"$searchValue.\" Вернитесь назад и измените текст поиска.";
	$errorMessage = "";
	
	switch ($typeErrorMessage) {
		case 'Пустая строка':
			return getStyleErrorMessage($messageIfEmptyValue);
		case 'Строка меньше 4-х символов':
			return getStyleErrorMessage($messageIfLowValue);
		case 'Ничего не найдено':
			return getStyleErrorMessage($messageIfNotFound);
		default:
			return 'Что-то пошло не так';
	}
}

if ($searchValue == "") {
	addChannelInfo($EMPTY_FIELD, '', getErrorMessage($EMPTY_FIELD), '');
}
else if (iconv_strlen($searchValue) < 4) {
	addChannelInfo($LOW_VALUE, '', getErrorMessage($LOW_VALUE), '');
}
else {
	$searchResult = false;
	$inputXml = openXML();
	$channels = $inputXml->getElementsByTagName('channel');
	foreach($channels as $channel) {
		$titles = $channel->getElementsByTagName('title');
		$stringTitle = $titles->item(0)->nodeValue;
		$stringTitleSearch = mb_strtolower($stringTitle, "UTF-8");
		
		$logos = $channel->getElementsByTagName('logo');
		$stringLogo = $logos->item(0)->nodeValue;
		$stringLogoSearch = mb_strtolower($stringLogo, "UTF-8");
		
		$descriptions = $channel->getElementsByTagName('description');
		$stringDescription = $descriptions->item(0)->nodeValue;
		$stringDescriptionSearch = mb_strtolower($stringDescription, "UTF-8");
		
		$stream_urls = $channel->getElementsByTagName('stream_url');
		$stringStream_url = $stream_urls->item(0)->nodeValue;
		$stringStream_urlSearch = mb_strtolower($stringStream_url, "UTF-8");
		
		$searchValueSearch = mb_strtolower($searchValue, 'UTF-8');
		
		$substr_count = substr_count($stringTitleSearch, $searchValueSearch);
		if ($substr_count != 0) {
			addChannelInfo($stringTitle, $stringLogo, $stringDescription, $stringStream_url);
			$searchResult = true;
		}
	}
	if ($searchResult == false) {
		addChannelInfo($NOT_FOUND, "", getErrorMessage($NOT_FOUND), "");
	}
}
$outputSimpleXml = simplexml_import_dom($outputXml);
print ($outputSimpleXml->asXML());
?>