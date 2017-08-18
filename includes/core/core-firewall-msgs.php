<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage system
 */
 

/**
 * FIREWALL constans.
 */
define("FIREWALL", "Tűzfal: hozzáférés megtagadva! ");

/**
 * FIREWALL_PROTECTION_URL constans.
 */
define("FIREWALL_PROTECTION_URL", FIREWALL."Lekérdezési string nem elfogadható!");

/**
 * FIREWALL_PROTECTION_OTHER_SERVER constans.
 */
define("FIREWALL_PROTECTION_OTHER_SERVER", FIREWALL."Másik szerveről POST küldés tilos!");

/**
 * FIREWALL_PROTECTION_SANTY constans.
 */
define("FIREWALL_PROTECTION_SANTY", FIREWALL."Santy támadás észlelve!");

/**
 * FIREWALL_PROTECTION_BOTS constans.
 */
define("FIREWALL_PROTECTION_BOTS", FIREWALL."Bot támadás észlelve!");

/**
 * FIREWALL_PROTECTION_REQUEST constans.
 */
define("FIREWALL_PROTECTION_REQUEST", FIREWALL."Rossz lekérdezési folyamat!");

/**
 * FIREWALL_PROTECTION_DOS constans.
 */
define("FIREWALL_PROTECTION_DOS", FIREWALL."Dos támadás észlelve! Vagy rossz a böngésző!");

/**
 * FIREWALL_PROTECTION_SQL constans.
 */
define("FIREWALL_PROTECTION_SQL", FIREWALL."Adatbázis támadás észlelve!");

/**
 * FIREWALL_PROTECTION_CLICK constans.
 */
define("FIREWALL_PROTECTION_CLICK", FIREWALL."Click támadás észlelve!");

/**
 * FIREWALL_PROTECTION_XSS constans.
 */
define("FIREWALL_PROTECTION_XSS", FIREWALL."XSS támadás észlelve!");

/**
 * FIREWALL_IPBANN constans.
 */
define("FIREWALL_IPBANN", FIREWALL."IP címed bannolva!");

?>