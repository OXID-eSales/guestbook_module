<?php
/**
 * This file is part of OXID eSales GuestBook module.
 *
 * OXID eSales Lexware export module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales Lexware export module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales Lexware export module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category      module
 * @package       guestbook
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
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
    'thumbnail'   => '',
    'version'     => '1.0.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'http://www.oxid-esales.com',
    'email'       => 'info@oxid-esales.com',
    'files'       => array(
        'oeguestbookmodule' => 'oe/guestbook/core/oeguestbookmodule.php',
        'oeguestbookguestbook' => 'oe/guestbook/controllers/oeguestbookguestbook.php',
        'oeguestbookguestbookentry' => 'oe/guestbook/controllers/oeguestbookguestbookentry.php',
        'oeguestbookadminguestbook' => 'oe/guestbook/controllers/admin/oeguestbookadminguestbook.php',
        'oeguestbookadminguestbooklist' => 'oe/guestbook/controllers/admin/oeguestbookadminguestbooklist.php',
        'oeguestbookadminguestbookmain' => 'oe/guestbook/controllers/admin/oeguestbookadminguestbookmain.php',
        'oeguestbookentry' => 'oe/guestbook/models/oeguestbookentry.php',
    ),
    'templates'   => array(
        'page/guestbook/oeguestbookguestbook_login.tpl' => 'oe/guestbook/views/tpl/page/guestbook/oeguestbookguestbook.tpl',
        'page/guestbook/oeguestbookguestbook.tpl' => 'oe/guestbook/views/tpl/page/guestbook/oeguestbookguestbook.tpl',
        'form/oeguestbookguestbook.tpl' => 'oe/guestbook/views/tpl/form/oeguestbookguestbook.tpl',
        'oeguestbookadminguestbook.tpl' => 'oe/guestbook/views/admin/tpl/oeguestbookadminguestbook.tpl',
        'oeguestbookadminguestbooklist.tpl' => 'oe/guestbook/views/admin/tpl/oeguestbookadminguestbooklist.tpl',
        'oeguestbookadminguestbookmain.tpl' => 'oe/guestbook/views/admin/tpl/oeguestbookadminguestbookmain.tpl',
    ),
    'events'      => array(
        'onActivate'   => 'oeGuestbookModule::onActivate',
        'onDeactivate' => 'oeGuestbookModule::onDeactivate',
    ),
);