<?php
	
	if (!extension_loaded('sockets')) {
	    die('The sockets extension is not loaded.');
	}

	if (isset($_REQUEST['btn_submit'])) {
		$msg = $_REQUEST['message'];

		$host = '127.0.0.1';
		$port = 20205;

		$socket = socket_create(AF_INET, SOCK_STREAM, 0);

		if (!$socket) {
			echo 'Unable to create socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
	        //die('Unable to create AF_UNIX socket');
	    }

	    /*if (!socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1)) {
		    echo 'Unable to set option on socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
		}*/


		$rval = socket_get_option($socket, SOL_SOCKET, SO_REUSEADDR);

		if ($rval === false) {
		    echo 'Unable to get socket option: '. socket_strerror(socket_last_error()) . PHP_EOL;
		} else if ($rval !== 0) {
		    echo 'SO_REUSEADDR is set on socket !' . PHP_EOL;
		}

		socket_connect($socket, $host, $port);

		socket_write($socket, $msg, strlen($msg));

		//Reply logic
		$reply = socket_read($socket, 1924);
		$reply = "Server says:\t".trim($reply);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Server</title>
</head>
<body>
	<form method="POST">
		<table>
			<tr>
				<td>
					Enter Message
				</td>
				<td>
					<input type="text" name="message">
					<input type="submit" name="btn_submit">
				</td>
			</tr>
			<?php if(isset($reply)) {?>
			<tr>
				<td><label>Message</label></td>
				<td><?= $reply?></td>
			</tr>
			<?php }?>
		</table>
	</form>
</body>
</html>