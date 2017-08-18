<?php

/**
 * Engine msngs.
 * @author Attila
 * @copyright 2011
 * @package engine
 */


//==============================================Community==============================================\\

/**
 * COM_ACCESS_DENNIED message.
 */
define("SYSMSG".COM_ACCESS_DENNIED, 
    "Nem megfelelő lekérdezés! Helytelen paraméterek. Hozzáférés megtagadva!");

/**
 *  COM_REG_ACTIVATION_SUCCESS message.
 */
define("SYSMSG".COM_REG_ACTIVATION_SUCCESS, 
    "Regisztráció sikeresen aktiválva! Most már be tudsz jelentkezni!");
    
/**
 * COM_USERWALL_NODATA message.
 */
define("SYSMSG".COM_USERWALL_NODATA, 
    "Nincs bejegyzés!");

/**
 * COM_USER_AVATARUPLOAD_SUCCESS message.
 */
define("SYSMSG".COM_USER_AVATARUPLOAD_SUCCESS,
    "Avatar feltöltése és mentése sikeres!");

/**
 * COM_USER_AVATARUPLOAD_INAVLIDFORMAT messgae.
 */
define("SYSMSG".COM_USER_AVATARUPLOAD_INAVLIDFORMAT, 
    "Nem megfelelő avatar! Az avatar típusa png, gif, jpg és a maximális mérete ".($info->avatarMaxSize/1024)." KB lehet!");

/**
 * COM_USER_NOFRIENDREQUEST message.
 */
define("SYSMSG".COM_USER_NOFRIENDREQUEST, 
    "Nincs barátkérelmed!");
    
/**
 * COM_CHAT_ACCESSDENNIED message.
 */
define("SYSMSG".COM_CHAT_ACCESSDENNIED, 
    "Hozzáférés megtagadva! A chat csak bejelentkezett felhasználók számára érhető el!");
    
?>