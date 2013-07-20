<?php 
	class MCQuery {	 
		public $server;
		public $motd, $online_players, $max_players;
		public $error = 'OK';
	 
		function __construct($url, $port = '25565') {
			$this -> server = array(
				'url' => $url,
				'port' => $port
			);

			if ($handle = @stream_socket_client('tcp://' . $url . ':' . $port, $errno, $errstr, 1)) {	
				stream_set_timeout($handle, 2);

				fwrite($handle, "\xFE\x01");
				$d = fread($handle, 1024);
				if ($d != false && substr($d, 0, 1) == "\xFF") {
					$d = substr($d, 3);
					$d = mb_convert_encoding($d, 'auto', 'UCS-2');
					$d = explode("\x00", $d);
					
					if ($d[0] == '/xff') {
						$this -> error = 'Kicked by server';
					} else {
						if (sizeof($d) == 6) {
							$this -> motd = str_replace(substr($d[0], 0, 1), '&', (string) $d[3]);
							
							$this -> motd = preg_replace('#\&(0|1|2|3|4|5|6|7|8|9|a|b|c|d|e|f|k|l)#', '', $this -> motd);
							
							$this -> online_players = (int) $d[4];
							$this -> max_players = (int) $d[5];
						} else {
							$this -> error = 'Cannot retrieve server info (' . sizeof($d) . ')';
						}
					}
					fclose($handle);
				} else {
					$this -> error = 'Timeout occurred';
				}
			} else {
				$this -> error = 'Cannot connect to server';
			}
		}
	}
?>