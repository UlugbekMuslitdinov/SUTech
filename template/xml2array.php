<?php

// http://php.net/manual/en/class.simplexmlelement.php#108867
function xml2array($xml) {
	function normalize_xml2array($obj, &$result) {
		$data = $obj;
		if (is_object($data)) {
			$data = get_object_vars($data);

			foreach($obj->getDocNamespaces() as $ns_name => $ns_uri) {
				if ($ns_name === '') continue;
				$ns_obj = $obj->children($ns_uri);
				foreach(get_object_vars($ns_obj) as $k => $v) {
					$data[ $ns_name .':' . $k] = $v;
				}
			}
		}

		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$res = null;
				call_user_func_array(__FUNCTION__, array($value, &$res));
				$result[$key] = $res;
			}
		} else {
			$result = $data;
		}
	}

	normalize_xml2array(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA), $result);
	$json = json_encode($result);
	return json_decode($json);
}

?>