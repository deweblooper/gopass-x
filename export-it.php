<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header ("content-type: text/html; charset=utf-8");

//$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = $_SERVER['REQUEST_URI'];
$my_url = explode('wp-content' , $url); 
$path = $_SERVER['DOCUMENT_ROOT']."".str_replace('/', '/', $my_url[0]);

if (isset($_GET['go'])) {
	
	$cesta = 'tmp/';
	$export = 'numbers.csv';
	$counterfile = "_count.inc";

	switch ($_GET['go']) {
		// retrieve data, generate CSV, show table + control buttons
		case 'adc329df80ca9552f2d82126':
			require_once $path . '/wp-config.php';

				global $wpdb;
				$gp_query = 'SELECT * FROM '.$wpdb->prefix.'_gopass';
				$gp_export_row = $wpdb->get_results( $wpdb->prepare( $gp_query, 'a' ) );

				// add colmn headers
				$list = array (
					array('por.','číslo karty','pridané')
				);

				foreach ($gp_export_row as $value) {
					$list[] = array( $value->gp_id, $value->gp_cardnum, $value->gp_date_add );
				}

				echo '
				<!doctype html>
				<html lang="en">
				<head>
					<meta charset="UTF-8" />
					<title>GoPass Export</title>
					<style>
						body {
							margin-top:30px;
							margin-bottom:30px;
							font-family: Arial, Helvetica, sans-serif;
							font-size:14px;
						}
						table {
						 margin:0 auto;
						 width:400px;
						 border-spacing: 0;
						}
						td {
							padding:2px 20px;
							border-bottom:1px solid #ddd;
						}
						tr:hover td {
							background-color:#f2f2f2;
						}
						tr:first-child td {
							padding:15px 20px;
							background-color:#f2f2f2;
						}
						tr:nth-child(2) td {
							font-weight:bold;
							font-style:italic;
						}
						a {
							font-weight:bold;
						}
						form {
							display:inline-block;
						}
					</style>
				</head>
				<body>
				<table>
					<tr><td colspan="3">
						<label for="dwl">Aktuálny CSV súbor na stiahnutie:</label> <form action="'.$cesta.$export.'" method="get" id="dwl"><input type="submit" value="stiahnuť" /></form><br />
						<label for="eml">Odoslať CSV súbor emailom:</label> <form action="export-it.php" method="get" id="eml"><input type="hidden" name="go" value="0c83f57c786a0b4a39efab23" /><input type="submit" value="odoslať" /></form></a>
					</td></tr>
				';

				// write data to file
				$fp = fopen($cesta.$export, 'w');
				foreach ($list as $fields) {
					echo ' <tr><td>'. $fields[0] .'</td><td>'. $fields[1] .'</td><td>'. $fields[2] ."</td></tr>\n";
					fputcsv($fp, $fields);
				}
				fclose($fp);

				echo '</table>
				</body>
				</html>
				';
			break;
		// retrieve data, generate CSV, send CSV by email
    case '0c83f57c786a0b4a39efab23':
			require_once $path . '/wp-config.php';

			global $wpdb;
			$gp_query = 'SELECT * FROM '.$wpdb->prefix.'_gopass';
			$gp_export_row = $wpdb->get_results( $wpdb->prepare( $gp_query, 'a' ) );

			// add colmn headers
			$list = array (
				array('por.','číslo karty','pridané')
			);

			foreach ($gp_export_row as $value) {
				$list[] = array( $value->gp_id, $value->gp_cardnum, $value->gp_date_add );
			}
			
			// write data to file
			$fp = fopen($cesta.$export, 'w');
			foreach ($list as $fields) {
				fputcsv($fp, $fields);
			}
			fclose($fp);
			
			$msg = 'OK';  // reset sprav

		// counter
			$handle = fopen($cesta.$counterfile, 'r');
			$count = fgets($handle);
			$count++;
			// set actual count number to file
			$handle = fopen($cesta.$counterfile, 'w');
			if (fwrite($handle, $count) === FALSE) {
				$msg .= "<p>CHYBA: Nedá sa zapisovať do súboru ($cesta$counterfile)</p>";
			}
			// save file
			if (!fclose($handle)) {
				$msg .= "<p>CHYBA: Súbor $counterfile sa nepodarilo uložiť do ($cesta)</p>";
			}

		/*  PRIPRAVA HLAVICIEK A TELA EMAILU  */
			require("class/mail.inc.php");
			$mail = new my_phpmailer_smtp;   //  POZOR! Odosiela von na internet aj z Locahostu. (skontroluj si premenne!)
		//	$mail = new my_phpmailer_local;  // Odosiela len interne do zz_smtp_mails
			
			$mail->SetLanguage("sk");  // jazyk chybovych hlasok
			$mail->AddAddress('info@gopass.sk', 'info@gopass.sk'); // pridat kopiu
			$mail->AddAddress('info@aketatrychceme.sk', 'AkeTatryChceme.sk'); // pridat kopiu
			$mail->AddAddress('catlosova@seesame.com', 'Adriana Čatlošová'); // pridat kopiu
			$mail->Subject = "AkeTatryChceme - GOPASS karty #$count";
			$mail->Body = "$count. export GOPASS kartiet pre 50 bodov ako kredit za dotaznik.";  // nastavíme telo e-mailu
			$mail->AddAttachment($cesta.$export, $export, 'base64', 'application/octet-stream');      // attachment
			
			if(!$mail->Send()) {  // odosle e-mail
				$msg .=  '<p>Pri odosielaní e-mailu došlo ku chybe.</p>';
				$msg .=  '<p>CHYBA: '.$mail->ErrorInfo.'</p>';
			} else {
				$msg .=  '<p>E-mail bol úspešne odoslaný.</p>';
			}

			
		/*  KONTROLNY VYPIS  */
		echo $msg;
			break;
		default :
			// other "go" value enter is not permitted
			exit;
			
	}
} else {
	// direct enter to this file is not permitted
	exit;
}