<?php
/**
 * This file is part of
 * ALAF - PHP Framework System.
 * Copyright by Andre Lachnicht www.alaniso.de
 *
 * $Revision: 1.1 $
 * $Author: aLaNiSo $
 * $Date: 2018-02-18 17:33:00 +0200 (Su, 18. Feb 2018) $
 */

/**
 * AF = Application Framework
 */

/**
 * Zeitstempel, wann der Aufruf begann...
 */
if (! defined ( 'AF_TIME' )) {
	define ( 'AF_TIME', microtime ( true ) );
}

// define ('MX_IS_ADMIN', false);

/**
 * Framework Hauptdatei includen
 */
require_once ('mainfile.php');
// Framework-Engine starten:
load_class ( 'af_engine', 'frontend' );
af_engine::start (); // Startet die Ausgabe-Engine...

// Ausgabe-Theme auf FrontEnd setzen:
af_engine::set_theme ( af_base::get_afConfig ( 'frontendTheme' ) );

// Hauptverarbeitung läuft über das Modul:
load_class ( 'af_module' );
echo af_module::run ();

/* Cookiechoice integration */

// if (isset($mxCookieInfo) && ($afCookieInfo=='1' && (AF_MODULE != "admin")) )
$CookieInfo = af_base::get_afConfig ( 'cookieInfo' );
$CookieLink = af_base::get_afConfig ( 'cookieLink' );
// if (isset($CookieInfo) && ($CookieInfo=='1' && (AF_MODULE != "admin")) )
if (isset ( $CookieInfo ) && ($CookieInfo == '1')) {
	if (! empty ( $CookieLink )) {
		$cookieinfotext = af_tran ( '_COOKIEINFO' ) . "','" . af_tran ( 'OK' ) . "', '" . af_tran ( '_MOREINFO' ) . "', '" . $CookieLink;
	} else {
		$cookieinfotext = af_tran ( '_COOKIEINFO' ) . "','" . af_tran ( 'OK' );
	}
	echo "<script>document.addEventListener('DOMContentLoaded', function(event) {cookieChoices.showCookieConsentBar('" . $cookieinfotext . "');});</script>";
	
	echo "<script type=\"text/javascript\" src=\"" . 'js' . DS . 'plugins' . DS . "cookiechoices/cookiechoices.js\"></script>";
}

// Letzte Aktion: Ausgabe
af_engine::final_output (); // Schliesst den Ausgabepuffer und gibt den Seiteninhalt aus!

?>