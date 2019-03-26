<?php
	$host = "127.0.0.1";
	$port = 20205;
	set_time_limit(0);

	$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

	if (!is_resource($socket)) {
	    echo 'Unable to create socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
	    die;
	}

    $rval = socket_get_option($socket, SOL_SOCKET, SO_REUSEADDR);

    if (!socket_bind($socket, $host, $port)) {
	    echo 'Unable to bind socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
	}
	
	if ($rval === false) {
    	echo 'Unable to get socket option: '. socket_strerror(socket_last_error()) . PHP_EOL;
	} else if ($rval !== 0) {
	    echo 'SO_REUSEADDR is set on socket !' . PHP_EOL;
	}

	socket_listen($socket, 3) or die("Could not setup socket listener");

	/**
	 * 
	 */
	class Chat
	{
		
		function readLine()
		{
			return rtrim(fgets(STDIN));
		}
	}

	do {
		$accept = socket_accept($socket) or die("Could not accepting incoming requests\n");
		
		$msg = @socket_read($accept, 1024) or die("Could not read input\n");

		echo "Client says:\t",$msg, "\n\n";

		$line  = new Chat();
		$reply = $line->readLine();

		socket_write($accept, $reply, strlen($reply)) or die("Could not write output");
	} while (true);

	socket_close($accept, $socket);
?>