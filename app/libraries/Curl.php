<?php
class Curl {
	
	protected $config = array(
		CURLOPT_CONNECTTIMEOUT => 2,
	);
	protected $params = array();
	public $info = array();
	public $data = null;


	/* setOption(mixed $name, string $value = null)
		
		Sets the cURL options. May be called as setOption($key, $value) or as setOption($array), where $array
		is an array of key and value pairs. Options are appended or overwritten, never replaced entirely. Key
		names should be STRINGS, not CURLOPT constants!

	*/

	public function setOption($name, $value = null) {
		if (is_array($name)) {
			foreach ($name as $key => $value) {
				$this->config[constant($key)] = $value;
			}
			return $this;
		}

		$this->config[constant($name)] = $value;
		return $this;
	}


	/* setParam(mixed $name, string $value = null)
		
		Sets the POST or GET parameters. May be called as setOption($key, $value) or as setOption($array),
		where $array is an array of key and value pairs. Values are appended or overwritten, never replaced
		entirely.

	*/
	public function setParam($name, $value = null) {
		if (is_array($name)) {
			foreach ($name as $key => $value) {
				$this->params[$key] = $value;
			}
			return $this;
		}

		$this->params[$name] = $value;
		return $this;
	}


	/* request(string $url = null, string $type = null, array $info = array())
		
		Make the cURL request. If $url or $type (GET, POST, or other cURL request types) is given, it will
		overwrite the option, if it was previously set with setOption(). If $url has not been set, it is
		required. Default $type is POST if it has not previously been set.

		info() specifies the CURLINFOs to get from the request, in an array strings. These may later be
		accessed with $Curl->info in the form of array('CURLINFO_ONE' => 'data', 'CURLINFO_TWO' => 'data'...)

		The data may be later accessed in $Curl->data or with $Curl->getData().

	*/
	public function request($url = null, $type = '', $info = array()) {

		if ($type == 'POST') {
			$this->config[CURLOPT_POST] = true;
		}

		if ($url) {
			$this->config[CURLOPT_URL] = $url;
		} elseif (!array_key_exists(CURLOPT_URL, $this->config)) {
			throw new Exception('cURL error: no ULR provided!');
			return false;
		}

		$ch = curl_init();

		if (count($this->params)) {
			if ($this->config[CURLOPT_POST] == false) {

				if (!strpos($url, '?')) {
					$url .= '?';
				}

				foreach ($this->params as $key => $value) {
					$url .= $key . '=' . urlencode($value) . '&';
				}
				$url = rtrim($url, '&');
			} else {
				$this->config[CURLOPT_POSTFIELDS] = $this->params;
			}
		}

		curl_setopt_array($ch, $this->config);
		$this->data = curl_exec($ch);

		foreach ($info as $i) {
			$this->info[$i] = curl_getinfo($ch, constant($i) );
		}

		curl_close($ch);

		return $this;
		
	}


	/* getData(string $url = null, string $type = null, array $info = array())
		
		Return the cURL data, making the request if it does not exist. Explicity defines
		CURLOPT_RETURNTRANSFER = true.

	*/
	public function getData($url = null, $type = 'POST', $info = array()) {

		$this->option[CURLOPT_RETURNTRANSFER] = true;

		if ($this->data) {
			return $this->data;
		} else {
			$this->request($url, $type, $info);
			return $this->data;
		}

	}

}