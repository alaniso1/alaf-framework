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

/* ich bin da... */
define ( 'ALAF', true );
define ( 'alafMainFileLoaded', true );

define ( 'ALAF_VERSION', '1.0' );

/* Benchmarkanzeige initialisieren */
defined ( 'AF_TIME' ) or define ( 'AF_TIME', microtime ( true ) );

if (1 == 1) {
	/* Übergabeparameter bereinigen */
	$keysToSkip = array (
			'GLOBALS',
			'_SERVER',
			'_GET',
			'_POST',
			'_FILES',
			'_COOKIE',
			'_SESSION',
			'_REQUEST',
			'_ENV',
			'PHP_SELF',
			'keysToSkip',
			'php_errormsg',
			'HTTP_RAW_POST_DATA',
			'http_response_header',
			'argc',
			'argv' 
	);
	foreach ( $_REQUEST as $key => $value ) {
		/* pruefen ob gueltiger Schluessel, ansonsten komplett entfernen */
		if (preg_match ( '#[^a-zA-Z0-9-_\x7f-\xff]#', $key )) {
			unset ( $_REQUEST [$key], $_COOKIE [$key], $_GET [$key], $_POST [$key] );
			continue;
		}
		/* killt Variablen, die bei register_globals=ON uebergeben werden koennten */
		if (in_array ( $key, $keysToSkip )) {
			die ( 'unaccepted requestkey: ' . $key );
		}
		unset ( $$key );
	}
	unset ( $key, $value, $keysToSkip );
}

defined ( 'ENT_HTML5' ) or define ( 'ENT_HTML5', 16 | 32 );

/* Kurzform dieser System-Konstanten erstellen */
define ( 'DS', DIRECTORY_SEPARATOR );

/* der wichtigste Pfad: zum Root-Verzeichnis, ohne Slash am Ende */
define ( 'ALAF_REAL_BASE_DIR', __DIR__ );

/* Ordner mit den Systemdateien, weitere Ordner werden in der mx_baseconfig definiert */
define ( 'ALAF_SYSTEM_DIR', ALAF_REAL_BASE_DIR . DS . 'includes' );
define ( 'ALAF_SYSTEM_CLASS_DIR', ALAF_SYSTEM_DIR . DS . 'classes' );

/* Ordner mit den Administrationsdateien */
define ( 'ALAF_ADMIN_DIR', ALAF_REAL_BASE_DIR . DS . 'admin' );

/* Ordner mit den Administrations Modulen */
define ( 'ALAF_ADMINMODULES_DIR', ALAF_ADMIN_DIR . DS . 'modules' );
/* Ordner mit den Administrations Funktionen */
define ( 'ALAF_ADMINSYSTEM_DIR', ALAF_ADMIN_DIR . DS . 'includes' );

/* Ordner mit dynamischen Inhalten (Logdatein, Cache, etc.) */
define ( 'ALAF_DYNADATA_DIR', ALAF_REAL_BASE_DIR . DS . 'dynadata' );

/* Ordner mit dynamischen Medien (Bilder, Dokumente, etc.) */
define ( 'ALAF_MEDIA_DIR', ALAF_REAL_BASE_DIR . DS . 'media' );

/* Ordner mit den Systemdateien fuer die HTML-Ausgabe (view) */
define ( 'ALAF_LAYOUT_DIR', ALAF_REAL_BASE_DIR . DS . 'layout' );

/* Ordner mit den Modulen */
define ( 'ALAF_MODULES_DIR', ALAF_REAL_BASE_DIR . DS . 'modules' );

/* Ordner mit den System-Bloecken */
define ( 'ALAF_BLOCKS_DIR', ALAF_REAL_BASE_DIR . DS . 'blocks' );

/* Ordner mit den Themes */
define ( 'ALAF_THEMES_DIR', ALAF_REAL_BASE_DIR . DS . 'themes' );

/* Ordner mit den Bildern */
define ( 'ALAF_IMAGE_DIR', ALAF_REAL_BASE_DIR . DS . 'images' );

/* Ordner mit den Systemsprachen */
define ( 'ALAF_LANGUAGE_DIR', ALAF_REAL_BASE_DIR . DS . 'language' );

/* Ordner mit den Themes */
define ( 'ALAF_SETUP_DIR', ALAF_REAL_BASE_DIR . DS . 'setup' );

/* Ordner mit den Plugins */
define ( 'ALAF_PLUGIN_DIR', ALAF_REAL_BASE_DIR . DS . 'plugins' );

/* Ordner mit Standard Javascripten */
define ( 'ALAF_JAVASCRIPT_DIR', ALAF_REAL_BASE_DIR . DS . 'js' );

/* die Systemkonfigurationsdatei */
define ( 'ALAF_CONFIGFILE', ALAF_REAL_BASE_DIR . DS . 'config.php' );

// Weitere Pfade ermitteln...
afDefinePath (); // siehe mx_baseconfig.php

/* Standard chmods */
// -- ACHTUNG, das muss für die Funktion chmod() mit octdec() noch umgewandelt werden
define ( 'ALAF_CHMOD_LOCK', octdec ( '0444' ) );
define ( 'ALAF_CHMOD_NORMAL', octdec ( '0644' ) );
define ( 'ALAF_CHMOD_UNLOCK', octdec ( '0666' ) );
define ( 'ALAF_CHMOD_FULLOCK', octdec ( '0400' ) );
define ( 'ALAF_CHMOD_FULLUNOCK', octdec ( '0777' ) );

/* alles auf utf8 stellen */
ini_set ( 'default_charset', 'UTF-8' );
// ini_set ( 'mbstring.internal_encoding', 'UTF-8' ); deprecated since php5.x
if (ini_get ( 'mbstring.internal_encoding' ))
	ini_set ( 'mbstring.internal_encoding', 'UTF-8' );

/**
 * Alle Fehler ausser E_NOTICE melden,
 * dies ist die Standardeinstellung in php.ini
 */
error_reporting ( E_ALL ^ E_NOTICE );

// // Falls Konfigurationsdatei noch nicht vorhanden: Setup ausführen //
// if(!file_exists(ALAF_CONFIGFILE)){
// include_once('install'.DS.'install.php');
// die('Ex existiert noch keine Konfigurationsdatei - schade :-(');
// }

/* Konfiguration laden */
/* Parsefehler in config.php abfangen und bei Bedarf Setup anbieten. */
if (! @include (ALAF_CONFIGFILE)) {
	header ( 'Content-type: text/html; charset=utf-8' );
	$msg = '<html><body><img src="images/logo.png" alt="alAf-Error" /><ul>';
	if (@file_exists ( 'install' ) && ! is_file ( ALAF_CONFIGFILE )) {
		$msg .= '
		<li>alAf seems not to be installed correctly, or you\'re running alAf for the first time. Click <a href="install/" rel="nofollow"><b>here</b></a> to run the installer.</li>
		<li>alAf scheint nicht korrekt installiert zu sein oder Sie starten alAf zum erstem Mal. Klicken Sie <a href="install/" rel="nofollow"><b>hier</b></a>, um den Installer zu starten.</li>
		<li>alAf semble ne pas &ecirc;tre install&eacute; correctement, ou vous ex&eacute;cutez alAf pour la premi&egrave;re fois. Cliquer <a href="setup/" rel="nofollow"><b>ici</b></a> pour commencer l\'installation.</li>
		<li>alAf düzgün kurulmam&#305;&#351; veya ilk defa alAf &#231;al&#305;&#351;t&#305;r&#305;yorsunuz. Kurulumu ba&#351;latmak i&#231;in <a href="setup/" rel="nofollow"><b>buraya</b></a> t&#305;klay&#305;n&#305;z.</li>
		';
	} else {
		$msg .= '
		<li>The config-file is missing or corrupted!</li>
		<li>Die Konfigurationsdatei fehlt oder ist besch&auml;digt!</li>
		<li>Le fichier config.php est absent ou corrompu!</li>
		<li>Ayar dosyas&#305; eksik veya hatal&#305;!</li>
		';
	}
	$msg .= '</ul></body></html>';
	die ( $msg );
}

/* System-Funktionen laden und API's einbinden */
require_once (ALAF_SYSTEM_DIR . DS . 'af_system.php');

// Falls ein SecurityTokenKey übergeben wurde,
// muessen die aufgeloesten Werte in die $_REQUEST gesetzt werden:
if (isset ( $_REQUEST ['stk'] )) {
	afDecodeToken ( $_REQUEST ['stk'] );
	unset ( $_REQUEST ['stk'] );
}

// Basisklasse für das Framework laden:
load_class ( 'af_base' );

/* Länderspezifische Einstellungen, wird teilweise durch die Einstellung der Sprachdateien überschrieben */
setlocale ( LC_ALL, array (
		'en_GB.UTF-8',
		'en_GB.UTF8',
		'en_GB.ISO-8859-1',
		'en_GB',
		'en_US',
		'en',
		'eng',
		'english-uk',
		'english-us',
		'uk',
		'us',
		'GB',
		'GBR',
		'826',
		'CTRY_UNITED_KINGDOM',
		'840',
		'CTRY_UNITED_STATES',
		'de-DE' 
) );

/* Standardzeitzone, die von allen Zeitfunktionen verwendet wird, einstellen */
date_default_timezone_set ( 'Europe/Berlin' );
$GLOBALS ['currentlang'] = 'german';

/* Fehlerbehandlung und Debugmethoden aktivieren */
load_class ( 'af_debug', false );
af_debug::init ();

/* Session einbinden (Start erfolgt in af_engine::start() */
$afSessionLifetime = af_base::get_afConfig ( 'afSessionLifetime' );
$afSessionLifetime = (isset ( $afSessionLifetime )) ? $afSessionLifetime : 30;
define ( "AF_COOKIE_LIFETIME", $afSessionLifetime * 24 * 60 * 60 );
define ( "AF_SESSION_LIFETIME", $afSessionLifetime * 24 * 60 * 60 ); // Standard 6 Stunden
/* die Sessionlifetime für nicht-User / nicht-Admins in Sekunden */
define ( 'AF_SESSION_LIFETIME_NOUSER', '1440' );

require_once (ALAF_SYSTEM_DIR . DS . 'af_session.php');

/* Referer aktualisieren */
afReferer ();

/**
 * Pfade ermitteln etc.
 * in Funktion gekapselt, um die verwendeten Variablen nicht in den "Globalen Scope" zu kopieren ;)
 */
function afDefinePath() {
	
	/* Ordner mit Session Inhalten */
	define ( 'ALAF_SESSDATA_PATH', ALAF_DYNADATA_DIR . '/session/' );
	
	/* Pfade ermitteln etc. */
	$info_script_name = pathinfo ( $_SERVER ['SCRIPT_NAME'] );
	$scriptpath = str_replace ( DS, '/', realpath ( dirname ( $_SERVER ['SCRIPT_FILENAME'] ) ) );
	$basepath = str_replace ( DS, '/', ALAF_REAL_BASE_DIR );
	
	/* just the subfolder part between <installation_path> and the page */
	$scriptpath = substr ( $scriptpath, strlen ( $basepath ) );
	/* unter PHP7 gibt substr bei gleicher Länge "" zurück, vorher FALSE! */
	$scriptpath = ($scriptpath == "") ? FALSE : $scriptpath;
	$rootpath = str_replace ( DS, '/', $info_script_name ['dirname'] );
	// we subtract the subfolder part from the end of <installation_path>, leaving us with just <installation_path> :)
	if ($scriptpath !== false) {
		$rootpath = str_replace ( '//', '/', substr ( $rootpath, 0, - strlen ( $scriptpath ) ) . '/' );
		$scriptpath = trim ( $scriptpath, '/' ) . '/';
	} else {
		$rootpath = str_replace ( '//', '/', $rootpath . '/' );
		$scriptpath = '';
	}
	
	/* der wichtigste Pfad: zum alaf-Root */
	define ( 'ALAF_BASE_PATH', $rootpath );
	define ( 'ALAF_DOCUMENT_ROOT', $_SERVER ['DOCUMENT_ROOT'] );
	
	$server = (! empty ( $_SERVER ['HTTP_HOST'] )) ? $_SERVER ['HTTP_HOST'] : '';
	$proto = (! empty ( $_SERVER ['HTTPS'] ) && (strtolower ( $_SERVER ['HTTPS'] ) != 'off' || strtolower ( $_SERVER ['HTTPS'] ) == 'on')) ? 'https://' : 'http://';
	
	if (! defined ( 'ALAF_HOME_URL' )) {
		// ohne slash, entspricht $nukeurl
		define ( 'ALAF_HOME_URL', trim ( $proto . $server . ALAF_BASE_PATH, ' .;/:' ) );
	}
	
	define ( 'ALAF_REQUEST_URI', (! empty ( $_SERVER ['REQUEST_URI'] )) ? $_SERVER ['REQUEST_URI'] : '' );
	$curr_url = str_replace ( ALAF_BASE_PATH, '', ALAF_REQUEST_URI );
	define ( 'ALAF_CALLED_URL', $curr_url );
	
	/* versch. Servervariablen prüfen und neu initialisieren */
	
	/* remote Adresse "cleanen" */
	if ((! empty ( $_SERVER ['REMOTE_ADDR'] ))) {
		switch (true) {
			case function_exists ( 'filter_var' ) :
				$_SERVER ['REMOTE_ADDR'] = filter_var ( $_SERVER ['REMOTE_ADDR'], FILTER_VALIDATE_IP );
				break;
			case preg_match ( '#^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(:\d{1,5})?$#', $_SERVER ['REMOTE_ADDR'] ) :
			// case preg_match('#(^|\s|(\[))(::)?([a-f\d]{1,4}::?){0,7}(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(?=(?(2)\]|($|\s|(?(3)($|\s)|(?(4)($|\s)|:\d)))))|((?(3)[a-f\d]{1,4})|(?(4)[a-f\d]{1,4}))(?=(?(2)\]|($|\s))))(?(2)\])(:\d{1,5})?#', $_SERVER['REMOTE_ADDR']):
			case preg_match ( '#^(((?=(?>.*?::)(?!.*::)))(::)?|([\dA-F]{1,4}(\2::|:(?!$)|$)|\2))(?4){5}((?4){2}|(25[0-5]|(2[0-4]|1\d|[1-9])?\d)(\.(?7)){3})\z#i', $_SERVER ['REMOTE_ADDR'] ) :
				break;
			default :
				$_SERVER ['REMOTE_ADDR'] = '';
		}
		if (! $_SERVER ['REMOTE_ADDR'] || $_SERVER ['REMOTE_ADDR'] == '::') {
			$_SERVER ['REMOTE_ADDR'] = '0.0.0.0';
		}
	} else {
		$_SERVER ['REMOTE_ADDR'] = '0.0.0.0';
	}
	// wenn leer, einfach die IP verwenden
	$_SERVER ['REMOTE_HOST'] = strip_tags ( (empty ( $_SERVER ['REMOTE_HOST'] )) ? $_SERVER ['REMOTE_ADDR'] : $_SERVER ['REMOTE_HOST'] );
	// da kann ja sonst was kommen...
	$_SERVER ['HTTP_USER_AGENT'] = strip_tags ( (! empty ( $_SERVER ['HTTP_USER_AGENT'] )) ? $_SERVER ['HTTP_USER_AGENT'] : '' );
	// referer gibts nicht auf allen Servern
	$_SERVER ['HTTP_REFERER'] = strip_tags ( (! isset ( $_SERVER ['HTTP_REFERER'] )) ? getenv ( 'HTTP_REFERER' ) : $_SERVER ['HTTP_REFERER'] );
	// k.A. warum, aber wir hatten da schon Hosts mit slashes drumrum...
	$_SERVER ['HTTP_HOST'] = (! empty ( $_SERVER ['HTTP_HOST'] )) ? strip_tags ( strtolower ( trim ( $_SERVER ['HTTP_HOST'], ' /:;.' ) ) ) : '';
	
	switch (true) {
		// so sollte es sein
		case isset ( $_SERVER ['SERVER_ADDR'] ) :
		// da hat ma mal nen Strato Server
		case $_SERVER ['SERVER_ADDR'] = getenv ( 'SERVER_ADDR' ) :
			break;
		// IIS Spezial...
		case isset ( $_SERVER ['LOCAL_ADDR'] ) :
			$_SERVER ['SERVER_ADDR'] = $_SERVER ['LOCAL_ADDR'];
			break;
		// weiss Gott was...
		default :
			$_SERVER ['SERVER_ADDR'] = '0.0.0.0';
	}
	
	define ( 'AF_REMOTE_ADDR', $_SERVER ['REMOTE_ADDR'] );
	define ( 'AF_REMOTE_HOST', $_SERVER ['REMOTE_HOST'] );
	define ( 'AF_USER_AGENT', $_SERVER ['HTTP_USER_AGENT'] );
	// Dokumentenverzeichnis auf dem installierten System:
	define ( 'AF_DOCUMENT_ROOT', $_SERVER ['DOCUMENT_ROOT'] );
}
/**
 * Entschlüsselt einen Security-Token
 * Dies ist eine Funktion, damit die verwendeten Variablen nicht in den "Globalen Scope" kopiert werden!
 *
 * @param unknown $alaf_req_token        	
 */
function afDecodeToken($alaf_req_token) {
	$SecurityTokenObj = load_class ( 'af_token', 'Hallo_zusammenBeiALAFfuerMondi' );
	
	$decrypted2 = $SecurityTokenObj->decrypt2 ( $alaf_req_token );
	
	// uns interessiert der query-Part:
	$query = parse_url ( $decrypted2 );
	$query = str_replace ( '&amp;', '&', $query );
	
	$ar_query = explode ( '&', $query ['query'] );
	
	foreach ( $ar_query as $key => $parval ) {
		$arSplit = explode ( '=', $parval );
		if (($arSplit) && (isset ( $arSplit [1] ))) {
			$_REQUEST [$arSplit [0]] = $arSplit [1];
		}
	}
	unset ( $SecurityTokenObj );
}

