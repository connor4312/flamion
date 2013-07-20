<?php

class MCBridge extends McErrors {
    public $autoconnect;
    public $socketTimeout;

    private $connections = array();
    private static $inst;
    private $daemonIds = false;
    private $passwords = array();

    private $db = null;

    public function __construct($autoconnect = true) {
        $this -> socketTimeout = 5;
        $this -> autoconnect = $autoconnect;
        $this -> passwords = include('../multicraft/protected/config/daemons.php');

        if (!is_array($this -> passwords)) $this -> passwords = array();

        $this -> passwords['default'] = array('password' => 'none');

        $this -> db = new db();
        $this -> db -> connect('multicraft_daemon');
    }
    
    static function get() {
        if (!isset(self::$inst)) {
            self::$inst = new McBridge();
        }

        return self::$inst;
    }

    public function conStrings() {
        $c = array();
        $ids = $this -> getDaemonIds(); 
        foreach ($ids as $id) {
            $con = $this -> getConnection($id);
            if (!$con) continue;
            $c[$id] = 'ID ' . $id . ' - ' . $con -> name . ' (' . $con -> ip . ')';
        }
        return $c;
    }

    public function getConnection($id) {
        if (@isset($this -> connections[$id])) {
            return $this -> connections[$id];
        }

        $result = $this -> db -> query('SELECT `name`, `ip`, `port` FROM `daemon` WHERE `id` = ?', array($id));
        $daemon = $result[0];
        
        if (!$daemon) return null;

        $pw = (isset($this -> passwords[$id]['password']) ? $this -> passwords[$id]['password'] : @$this -> passwords['default']['password']);
        $con = new McConnection($this, $id, $daemon['name'], $daemon['ip'], $daemon['port'], $pw);
        
        //var_dump($con);die();

        return ($this -> connections[$id] = $con);
    }

    public function getDaemonIds() {
        if (!$this -> daemonIds) {
            $ids = $this -> db -> query('SELECT id FROM daemon');
            foreach ($ids as $id) {
                $this -> daemonIds[] = (int) $id['id'];
            }
        }

        return $this -> daemonIds;
    }

    public function connectionCount() {
        return count($this -> getDaemonIds());   
    }

    public function serverCmd($serverid, $cmd, &$data = null, $broadcast = false) {
        $command = $cmd;
        $r = array();
        
        $cmd = 'server ' . $serverid . ':' . $cmd;
        $ret = array();

        if ($broadcast) {
            $ret = $this -> globalCmd($cmd);
        } else {
            $result = $this -> db -> query('SELECT daemon_id FROM server WHERE id = ?', array($serverid));
            $ret = array($this -> cmd((int) $result[0]['daemon_id'], $cmd));
        }

        $e = '';
        foreach ($ret as $r) {
            if ($r['success']) {
                $data = $r['data'];
                return true;
            }

            $e = $r['error'];
        }

        $this -> addError($e);
        return false;
    }

    public function globalCmd($cmd) {
        $this -> clearErrors();
        $ret = array();
        $ids = $this -> getDaemonIds();
        foreach ($ids as $id) {
            $con = $this -> getConnection($id);
            if (!$con) continue;
            $ret[$id] = array();
            $d = array();
            $ret[$id]['success'] = $con -> command($cmd, $d);
            $ret[$id]['data'] = $d;
            $ret[$id]['error'] = $con -> lastError();
        }

        return $ret;
    }

    public function cmd($daemonid, $cmd) {
        /*if (!preg_match('/^(server\s+\d+\s*:(get\s|plugin has|backup (status|list))|updatejar (list|status)|cfgfile (check|get)|version)/', $cmd))
            Yii::log('Sending command "'.$cmd.'" to daemon '.$daemon);*/
        $this -> clearErrors();
        $ret = array();
        $con = $this -> getConnection($daemonid);

        if (!$con) {
            $ret['success'] = false;
            $ret['data'] = '';
            $ret['error'] = 'No connection for daemon ' . $daemonid;
            return $ret;
        }

        $d = array();
        $ret['success'] = $con -> command($cmd, $d);
        $ret['data'] = $d;
        $ret['error'] = $con -> lastError();
        return $ret;
    }

    static function parse($data) {
        if (!$data)
            return array();
        if (!is_array($data))
            $data = array($data);

        $ret = array();
        foreach ($data as $line) {
            $items = preg_split('/ :/', $line);
            $data = array();
            for ($i = 0; ($i + 1) < count($items); $i += 2)
                $data[$items[$i]] = preg_replace('/\\\\\\\\/', '\\', preg_replace('/ \\\:/', ' :', $items[$i + 1]));
            $ret[] = $data;
        }
        return $ret;
    }

}

class McConnection extends McErrors {
    var $id;
    var $name;
    var $ip;
    var $port;
    var $password;
    var $socket;
    var $socketTimeout;
    var $bridge;
    var $triedConnect = false;
    var $connectError = '';

    function __construct($bridge, $id, $name, $ip, $port, $password) {
        $this -> bridge = $bridge;
        $this -> id = $id;
        $this -> name = $name;
        $this -> ip = $ip;
        $this -> port = $port;
        $this -> password = $password;
        $this -> socketTimeout = $bridge -> socketTimeout;
        $this -> socket = false;        
    }

    function command($cmd, &$data) {
        $cmd = str_replace("\n", ' ', $cmd);

        if (!$this -> send($cmd)) return false;

        $r = $this -> recv();

        if (!$r['ack']) return false;

        $data = McBridge::parse($r['data']);
        return true;
    }

    public function connect() {
        if ($this -> triedConnect) {
            $this -> addError($this -> connectError);
            return false;
        }

        $this -> triedConnect = true;
        $this -> connectError = '';
        $errno = 0; $errstr = '';
        $this -> socket = @pfsockopen($this->ip, $this->port, $errno, $errstr, $this->socketTimeout);

        if (!$this -> socket) {
            $this -> connectError = 'Can\'t connect to Minecraft bridge! (' . $errno . ': ' . $errstr . ')';
            $this -> addError($this -> connectError);
            $this -> socket = false;
            return false;
        }
        stream_set_timeout($this -> socket, $this -> socketTimeout);

        //clear stream (we're using persistent connection)
        while ($this->dataReady())
            if (!fgets($this->socket))
                break;
        if (!$this -> connected()) {   
            $this -> connectError = 'Can\'t connect to Minecraft bridge! (Connection lost)';
            $this -> addError($this -> connectError);
            $this -> socket = false;
            return false;
        }
        return true;
    }

    public function auth() {
        $data;
        if (!$this -> command('auth', $data)) {
            $this -> disconnect();
            $this -> addError('Authentication failed! (auth: ' . $this -> lastError() . ')');
            return false;
        }

        $token = @$data[0]['token'];

        if (preg_match('/^([0-9]+)/', $token)) {
            $code = base64_encode(sha1($token . sha1(sha1($this -> password))) ^ sha1($this -> password));
            
            if (!$this -> command('codeword: '. $code, $none)) {
                $this -> disconnect();
                $this -> addError('Authentication failed! (code: ' . $this -> lastError() . ')');
                return false;
            }
        }

        return true;
    }

    public function connected() {
        return $this -> socket !== false;
    }
    
    public function dataReady() {
        if (!$this -> connected()) return false;
        return @stream_select($r = array($this -> socket), $w = null, $x = null, 0) > 0;
    }

    public function send($data) {
        if (!$this -> connected()) {
            if (!$this -> bridge -> autoconnect) {
                $this -> addError('Not connected!');
                return false;
            }

            if (!$this -> connect() || !$this -> auth()) return false; 
        }
        if (@fwrite($this -> socket, $data . "\n") === false) {
            $this -> addError('Send failed!');
            return false;
        }
        return true;
    }

    public function recv() {
        if (!$this -> connected()) {
            $this -> addError('Not connected!');
            return false;
        }

        $ret = array();
        $ret['ack'] = false;
        $ret['error'] = 'Data receive timeout';
        $ret['data'] = array();

        $prev = '';
        while (true) {
            $r = fgets($this -> socket);
            $data = $prev . $r;
            $prev = $data;
            if ($r && $data[strlen($data) - 1] != "\n") continue;
            if (strlen($data) && $data[0] == '>') {
                if ($data[1] != 'O') {
                    $ret['error'] = preg_replace('/ERROR( - )?/', '', substr($data, 1, strlen($data) - 2));
                    //$this->addError($ret['error']);
                } else {
                    $ret['ack'] = true;
                    $ret['error'] = false;
                }

                if ($this -> dataReady()) {
                    //We somehow have a second response on the stream, discard current response
                    $ret['ack'] = false;
                    $ret['error'] = false;
                    $ret['data'] = array();
                    $prev = '';
                } else {
                    break;
                }
            } else if (!$data) {
                if (!$ret['ack']) $ret['error'] = 'Empty response';
                break;
            } else {
                $prev = '';
                $ret['data'][] = substr($data, 1, strlen($data) - 2);
            }
        }

        if (!$ret['ack']) $this -> addError($ret['error']);

        return $ret;
    }

    public function disconnect() {
        if (!$this -> connected()) return;
        fclose($this -> socket);
        $this -> socket = false;
        $this -> triedConnect = false;
    }
}

class McErrors {
    var $_errors = array();

    public function addError($error) {
        $this -> _errors[] = $error;
    }

    public function errors() {
        return $this -> _errors;
    }

    public function lastError() {
        $c = count($this -> _errors);
        if (!$c) return false;
        return $this -> _errors[$c - 1];
    }

    public function showErrors() {
        /*foreach ($this->_errors as $error)
            echo $error.'<br/>';*/
    }

    public function clearErrors() {
        $this -> errors = array();
    }
}
?>