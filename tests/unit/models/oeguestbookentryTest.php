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

class oeGuestBookEntryTest extends OxidTestCase
{

    private $_oObj = null;

    private $_sObjTime = null;

    private $adminId = null;

    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp()
    {
        parent::setUp();

        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $this->adminId = $db->getOne("select OXID from `oxuser` where `OXUSERNAME` = 'admin';");

        $myConfig = $this->getConfig();
        $this->_oObj = oxNew('oeGuestBookEntry');
        $this->_oObj->oeguestbookentry__oxuserid = new oxField($this->adminId, oxField::T_RAW);
        $this->_oObj->oeguestbookentry__oxcontent = new oxField("test content\ntest content", oxField::T_RAW);
        $this->_oObj->oeguestbookentry__oxcreate = new oxField(null, oxField::T_RAW);
        $this->_oObj->oeguestbookentry__oxshopid = new oxField($myConfig->getShopId(), oxField::T_RAW);
        $this->_oObj->save();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown()
    {
        $this->_oObj->delete();
        $this->cleanUpTable('oeguestbookentry');
        parent::tearDown();
    }

    public function testInsert()
    {
        $iTime = time();

        oxAddClassModule('modOxUtilsDate', 'oxUtilsDate');
        oxRegistry::get("oxUtilsDate")->UNITSetTime($iTime);

        $this->_oObj->delete();

        // resaving
        $this->_oObj->oeguestbookentry__oxcreate = new oxField(null, oxField::T_RAW);
        $this->_oObj->save();

        $this->assertEquals(date('Y-m-d H:i:s', $iTime), $this->_oObj->oeguestbookentry__oxcreate->value);
    }

    public function testUpdate()
    {
        // copying
        $sBefore = $this->_oObj->oeguestbookentry__oxcreate->value;

        $this->_oObj->save();

        // comparing
        $this->assertEquals($sBefore, $this->_oObj->oeguestbookentry__oxcreate->value);
    }

    public function testUpdateWithSpecChar()
    {
        $this->_oObj->oeguestbookentry__oxcontent = new oxField("test content\ntest content <br>", oxField::T_RAW);
        $this->_oObj->save();

        // comparing
        $this->assertEquals("test content\ntest content <br>", $this->_oObj->oeguestbookentry__oxcontent->value);

    }

    public function testAssignNoUserData()
    {
        $oObj = oxNew('oeGuestBookEntry');
        $oObj->load($this->_oObj->getId());
        $oObj->oeguestbookentry__oxuserid = new oxField('', oxField::T_RAW);
        $oObj->save();

        $oObj = oxNew('oeGuestBookEntry');
        $oObj->load($this->_oObj->getId());
        $this->assertEquals("test content\ntest content", $oObj->oeguestbookentry__oxcontent->value);
        $this->assertFalse(isset($oObj->oxuser__oxfname));
    }

    public function testAssignWithUserData()
    {
        $oObj = oxNew('oeGuestBookEntry');
        $oObj->load($this->_oObj->getId());

        $this->assertEquals("test content\ntest content", $oObj->oeguestbookentry__oxcontent->value);
        $this->assertTrue(isset($oObj->oxuser__oxfname));
        $this->assertEquals("John", $oObj->oxuser__oxfname->value);
    }

    public function testGetAllEntries()
    {
        $myDB = oxDb::getDb();
        $sSql = 'insert into oeguestbookentry (oxid,oxshopid,oxuserid,oxcontent)values("_test","' . $this->getConfig()->getBaseShopId() . '","' . $this->adminId . '","AA test content")';
        $myDB->execute($sSql);
        $oObj = oxNew('oeGuestBookEntry');
        $aEntries = $oObj->getAllEntries(0, 10, 'oxcontent');
        $this->assertEquals(2, $aEntries->count());
        $oEntry = $aEntries->current();
        $this->assertEquals("AA test content", $oEntry->oeguestbookentry__oxcontent->value);
    }

    public function testGetAllEntriesModerationOn()
    {
        $this->getConfig()->setConfigParam('oeGuestBookModerate', 1);
        $myDB = oxDb::getDb();
        $sSql = 'insert into oeguestbookentry (oxid,oxshopid,oxuserid,oxcontent)values("_test","' . $this->getConfig()->getBaseShopId() . '","' . $this->adminId . '","AA test content")';
        $myDB->execute($sSql);
        $oObj = oxNew('oeGuestBookEntry');
        $aEntries = $oObj->getAllEntries(0, 10, null);
        $this->assertEquals(0, $aEntries->count());
        $sSql = 'update oeguestbookentry set oxactive="1" where oxid="_test"';
        $myDB->execute($sSql);
        $aEntries = $oObj->getAllEntries(0, 10, null);
        $this->assertEquals(1, $aEntries->count());
    }

    public function testGetEntryCount()
    {
        $oObj = oxNew('oeGuestBookEntry');
        $iCnt = $oObj->getEntryCount();
        $this->assertEquals(1, $iCnt);
    }

    public function testGetEntryCountModerationOn()
    {
        $this->getConfig()->setConfigParam('oeGuestBookModerate', 1);
        $oObj = oxNew('oeGuestBookEntry');
        $iCnt = $oObj->getEntryCount();
        $this->assertEquals(0, $iCnt);
        $this->_oObj->oeguestbookentry__oxactive = new oxField(1, oxField::T_RAW);
        $this->_oObj->save();
        $iCnt = $oObj->getEntryCount();
        $this->assertEquals(1, $iCnt);
    }

    public function testFloodProtectionIfAllow()
    {
        $oObj = oxNew('oeGuestBookEntry');
        $myConfig = $this->getConfig();
        $this->assertFalse($oObj->floodProtection($myConfig->getShopId(), $this->adminId));
    }

    public function testFloodProtectionMaxReached()
    {
        $oObj = oxNew('oeGuestBookEntry');
        $myConfig = $this->getConfig();
        $myConfig->setConfigParam('oeGuestBookMaxGuestBookEntriesPerDay', 1);
        $this->assertTrue($oObj->floodProtection($myConfig->getShopId(), $this->adminId));
    }

    public function testFloodProtectionIfShopAndUserNotSet()
    {
        $oObj = oxNew('oeGuestBookEntry');
        $this->assertTrue($oObj->floodProtection());
    }

    public function testSetFieldData()
    {
        $oObj = $this->getProxyClass('oeGuestBookEntry');
        $oObj->UNITsetFieldData("oeguestbookentry__oxcontent", "asd< as");
        $this->assertEquals('asd&lt; as', $oObj->oeguestbookentry__oxcontent->value);
    }
}
