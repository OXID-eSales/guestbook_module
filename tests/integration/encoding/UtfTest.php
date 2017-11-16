<?php
/**
 * This file is part of OXID eSales Guestbook module.
 *
 * OXID eSales GuestBook module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales Guestbook module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales Guestbook module.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category      module
 * @package       guestbook
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 */

class UtfTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /** @var string Original theme */
    private $_sOrigTheme;

    /**
     * Sets up test
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_sOrigTheme = $this->getConfig()->getConfigParam('sTheme');
        $this->getConfig()->setConfigParam('sTheme', 'azure');
    }

    /**
     * Cleans up database.
     */
    protected function tearDown()
    {
        $this->getConfig()->setConfigParam('sTheme', $this->_sOrigTheme);
        $this->cleanUpTable('oeguestbookentry');
        $this->getConfig()->setActiveView(null);
        parent::tearDown();
    }
    
    public function testOeGuestBookEntrySaveAndLoad()
    {
        $sValue = 'sėkme Литовские für';

        $oEntry = new oeGuestBookEntry();
        $oEntry->setId('_testGbentry');
        $oEntry->oeguestbookentry__oxcontent = new \OxidEsales\Eshop\Core\Field($sValue);
        $oEntry->save();

        $oEntry = new oeGuestBookEntry();
        $oEntry->load('_testGbentry');
        $this->assertEquals($sValue, $oEntry->oeguestbookentry__oxcontent->value);
    }
}
