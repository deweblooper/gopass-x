<?php

require("class.phpmailer.php");

class my_phpmailer_smtp extends phpmailer {
	// nastavenie spolocnych premennych pre odosielanie cez SMTP
	var $Mailer    = "smtp";
	var $SMTPAuth  = false;
	//var $SMTPDebug = 2;  // vypisanie hlasok zo SMTP na stranku (da sa vypnut)
	var $Port      = 25;
	var $Host      = "mail.tmr.sk";
	var $Username  = "info-aketatrychceme@tmr.sk";
	var $Password  = "Password1234*";
	var $CharSet   = "utf-8";

	var $From      = "info-aketatrychceme@tmr.sk";
	var $FromName  = "AkeTatryChceme.sk";

	var $WordWrap  = 75;
}

class my_phpmailer_local extends phpmailer {
	// nastavenie spolocnych premennych pre odosielanie cez LOCAL
	var $CharSet   = "utf-8";

	var $From      = "info@aketatrychceme.sk";
	var $FromName  = "AkeTatryChceme.sk";

	var $WordWrap  = 75;
}




?>