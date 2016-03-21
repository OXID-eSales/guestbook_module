<?php

/**
 
 *
 * @category      module
 * @package       guestbook
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-20152016
 */

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'oeguestbook',
    'title'       => array(
        'de' => 'Gästebuch',
        'en' => 'Guestbook',
    ),
    'description' => array(
        'de' => 'Erlaubt Kunden, Texteinträge auf der Seite Gästebuch des eShops zu schreiben. Im eShop Admin können die
        Texteinträge verwaltet werden.',
        'en' => 'Customers can write text messages on the page guestbook of the eShop. There is a section in the eShop 
        admin where those text messages can be managed.',
    ),
    'thumbnail'   => 'out/pictures/picture.png',
    'version'     => '1.0.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'http://www.oxid-esales.com',
    'email'       => 'info@oxid-esales.com',
    'files'       => array(
        'oeguestbookmodule' => 'oe/guestbook/core/oeguestbookmodule.php',
        'oeguestbookguestbook' => 'oe/guestbook/controllers/oeguestbookguestbook.php',
        'oeguestbookguestbookentry' => 'oe/guestbook/controllers/oeguestbookguestbookentry.php',
        'oegeustbookadminguestbook' => 'oe/guestbook/controllers/admin/oegeustbookadminguestbook.php',
        'oeguestbookadminguestbooklist' => 'oe/guestbook/controllers/admin/oeguestbookadminguestbooklist.php',
        'oeguestbookadminguestbookmain' => 'oe/guestbook/controllers/admin/oeguestbookadminguestbookmain.php',
        'oeguestbookentry' => 'oe/guestbook/models/oeguestbookentry.php',
    ),
    'templates'   => array(
        'page/guestbook/oeguestbookguestbook_login.tpl' => 'oe/guestbook/views/tpl/page/guestbook/oeguestbookguestbook.tpl',
        'page/guestbook/oeguestbookguestbook.tpl' => 'oe/guestbook/views/tpl/page/guestbook/oeguestbookguestbook.tpl',
        'form/oeguestbookguestbook.tpl' => 'oe/guestbook/views/tpl/form/oeguestbookguestbook.tpl',
        'oeguestbookadminguestbook.tpl' => 'oe/guestbook/views/admin/tpl/oeguestbookadminguestbook.tpl',
        'oeguestbookadminguestbooklist' => 'oe/guestbook/views/admin/tpl/oeguestbookadminguestbooklist.tpl',
        'oeguestbookadminguestbookmain' => 'oe/guestbook/views/admin/tpl/oeguestbookadminguestbookmain.tpl',
    ),
    'events'      => array(
        'onActivate'   => 'oeGuestbookModule::onActivate',
        'onDeactivate' => 'oeGuestbookModule::onDeactivate',
    ),
);