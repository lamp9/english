<?php
class HtmlXpath{
	public function getXpathObj($url){
		$html = get_content_curl($url, $url, false, $this->cookie);
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		return $xpath = new DOMXpath($doc);
	}

	public function getElementsCount($xpath, $path){
		$elements = $xpath->query($path);
		$i = 0;
		if (!is_null($elements)) {
			foreach ($elements as $element) {
				++$i;
			}
		}
		return $i;
	}

	public function getElement($xpath, $path){
		$elements = $xpath->query($path);
		foreach ($elements as $element) {
			return $element;
		}
	}

	public function getElementUrl($xpath, $pa){
		$element = self::getElement($xpath, $pa);
		$elementAttr = $element->attributes;
		foreach ($elementAttr as $attr){
			if('href' == $attr->name){
				return ['title' => $element->nodeValue, 'url' => $attr->value];
			} else continue;
		}
		return false;
	}
}
?>