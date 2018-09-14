#!/usr/local/bin/php -q
<?php 

include "utils.php";

error_reporting(E_ALL);

\utils\init();

\utils\log_message("Version de php : ".phpversion());

\utils\log_message(\utils\change_ini("display_errors", "1"));

//socket php 

/* Le script fonctionne sans limite de temps */
set_time_limit(0);

/*Vidage implicite des buffers on*/

ob_implicit_flush();

$address = '192.168.1.56';
$port = 10000;

if(($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
	\utils\log_message("socket_create() a échoué ".socket_strerror(socket_last_error()), true);

}

if(socket_bind($sock, $address, $port) === false){
	\utils\log_message("socket_bind() a échoué ".socket_strerror(socket_last_error($sock)), true);
	
}

if(socket_listen($sock, 5) === false){
	\utils\log_message("socket_listen() a échoué ".socket_strerror(socket_last_error($sock)), true);
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
        \utils\log_message("socket_accept() a échoué : raison : " . socket_strerror(socket_last_error($sock)), true);
        break;
    }
    /* Send instructions. */
    $msg = "\Bienvenue sur le serveur de test PHP.\n" .
        "Pour quitter, tapez 'quit'. Pour éteindre le serveur, tapez 'shutdown'.\n";
    socket_write($msgsock, $msg, strlen($msg));

    do {
        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
            echo "socket_read() a échoué : raison : " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
        }
        if (!$buf = trim($buf)) {
            continue;
        }
        if ($buf == 'quit') {
            break;
        }
        if ($buf == 'shutdown') {
            socket_close($msgsock);
            break 2;
        }
        $talkback = "PHP: You said '$buf'.\n";
        socket_write($msgsock, $talkback, strlen($talkback));
        \utils\log_message("$buf", true);
    } while (true);
    socket_close($msgsock);
} while (true);

socket_close($sock);


?>