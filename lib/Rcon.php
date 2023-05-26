<?php
class rcon
{
	public $connected = false;
	private $socket;

	public function __construct($password, $host = '127.0.0.1', $port = 25575, $timeout = 5)
	{
		$this->socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
		if (!$this->socket) {
			echo trim("RCON: $errstr");
			return;
		}
		stream_set_timeout($this->socket, 30);
		$this->write(3, $password);
		$response = $this->read();
		if (strpos($response,'failed') > -1)
		{
			fclose($this->socket);
			echo "RCON: $response";
			return;
		}
		$this->connected = true;
	}

	public function send($command)
	{
		$this->write(2, $command);
		return $this->read();
	}

	private function write($type, $data)
	{
		$packet = pack('VV', 0x2A, $type).$data."\x00\x00";
		$packet = pack('V', strlen($packet)).$packet;
		fwrite($this->socket, $packet, strlen($packet));
	}

	private function read()
	{
		$size = fread($this->socket, 4);
		$size = unpack('V1size', $size)['size'];
		$packet = '';
		do {
			$packet = $packet.fread($this->socket, $size - strlen($packet));
		} while (strlen($packet) != $size);
		$packet = unpack('V1id/V1type/a*body', $packet)['body'];
		return substr($packet, 0, -2);
	}
}
?>