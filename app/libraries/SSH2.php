<?php
class SSH2 {

	public function connect($host, $user, $pass = null) {
		if (isset($this->conn)) {
			ssh2_exec($this->conn, 'exit');
			unset($this->conn);
		}
		
		$this->conn = ssh2_connect($data['ip'], 22);
		if ($pass)
			ssh2_auth_password($this->conn, $user, $pass);
	}

	public function executeBasic(string $cmd, $getoutput = false) {

		$stream = ssh2_exec($this->conn, $_POST['command']);

		if ($getoutput) {
			stream_set_blocking($stream, false);
			$data = '';
			while($buffer = fread($stream, 4096)) {
					$data .= $buffer;
			}
			fclose($stream);

			return $data;
		}

		return true;
	}

	public function executeMultiple(array $cmds, $getoutput = false, $breakonfail = true) {
		$context = '/';
		
		$return = array();
		$return['cmd'] = array();
		$files = array();
		foreach ($cmds as $cmd) {
			$out = array();
			$out['cmd'] = $cmd;
			try {
				if ($cmd[0] == "#") {
					$argument = explode(' ', substr($cmd, 1));

					switch ($argument[0]) {
						case 'send':
							ssh2_scp_send($this->conn, $argument[1], $argument[2]);
							break;
						case 'recieve':
							$filear = explode('.', $argument[1]);
							$files[] = "tmp/" . md5($argument1) . $filear[count($filear)-1];
							ssh2_scp_recv($this->conn, $argument[1], $files[count($files)-1]);
							break;
					}

				} else {
					$cmd = str_replace('./', $context, $cmd);

					if ($pointer=strstr($cmd, 'cd')) {
						$pointer += 3;
						$dir = explode('/', substr($cmd, $pointer));
						$newdir = explode('/', $context);

						foreach ($dir as $elem) {
							if ($elem == "..")
								array_pop($newdir);
							else
								$newdir[] = $elem;
						}

						$context = implode('/', $newdir);
					} elseif (!is_string($data = $this->execute($cmd, $getoutput)))
						$out['data'] = $data;

					$return['cmd'][] = $out;
				}
			} catch (Exception $e) {
				return 'Aksel has failed to execute command "' . $cmd . '"" with message "' . $e->getMessage() . '". ' . ($breakonfail ? 'Execution of subsequent commands has been halted.' : 'Attempting to keep calm and carry on...');
			}
		}
		if (!empty($files))
			$return['files'] = $files;

		return $return;
	}
}
?>