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

class oeGuestBookGuestBookTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    private $_oObj = null;

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

        $oConfig = $this->getConfig();
        $this->_oObj = oxNew('oeGuestBookEntry');
        $this->_oObj->oeguestbookentry__oxuserid = new oxField($this->adminId, oxField::T_RAW);
        $this->_oObj->oeguestbookentry__oxcontent = new oxField("test content\ntest content", oxField::T_RAW);
        $this->_oObj->oeguestbookentry__oxcreate = new oxField(null, oxField::T_RAW);
        $this->_oObj->oeguestbookentry__oxshopid = new oxField($oConfig->getShopId(), oxField::T_RAW);
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
        parent::tearDown();
    }

    /**
     * compare::render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oView = $this->getMock("oeGuestBookGuestBook", array("floodProtection", "getSortColumns", "getGbSortBy", "getGbSortDir", "getEntries", "getPageNavigation"));
        $oView->expects($this->never())->method('floodProtection');
        $oView->expects($this->never())->method('getSortColumns');
        $oView->expects($this->never())->method('getGbSortBy');
        $oView->expects($this->never())->method('getGbSortDir');
        $oView->expects($this->once())->method('getEntries');
        $oView->expects($this->never())->method('getPageNavigation');

        $this->assertEquals("page/guestbook/oeguestbookguestbook.tpl", $oView->render());
    }

    /**
     * Test flood protection when allowed amount is not exceeded.
     *
     * @return null
     */
    public function testFloodProtectionIfAllow()
    {
        $oObj = oxNew('oeGuestBookGuestBook');
        $this->getConfig()->setConfigParam('oeGuestBookMaxGuestBookEntriesPerDay', 10);
        $this->getSession()->setVariable('usr', $this->adminId);
        $this->assertFalse($oObj->floodProtection());
    }

    /**
     * Test flood protection when allowed amount is exceeded.
     *
     * @return null
     */
    public function testFloodProtectionMaxReached()
    {
        $oObj = oxNew('oeGuestBookGuestBook');
        $this->getConfig()->setConfigParam('oeGuestBookMaxGuestBookEntriesPerDay', 1);
        $this->getSession()->setVariable('usr', $this->adminId);
        $this->assertTrue($oObj->floodProtection());
    }

    /**
     * Test flood protection when user is not logged in.
     *
     * @return null
     */
    public function testFloodProtectionIfUserNotSet()
    {
        $oObj = oxNew('oeGuestBookGuestBook');
        $this->getSession()->setVariable('usr', null);
        $this->assertTrue($oObj->floodProtection());
    }

    /**
     * Test get guest book entries.
     *
     * @return null
     */
    public function testGetEntries()
    {
        $oObj = oxNew('oeGuestBookGuestBook');
        $aEntries = $oObj->getEntries();
        $oEntries = $aEntries->current();
        $this->assertEquals("test content\ntest content", $oEntries->oeguestbookentry__oxcontent->value);
        $this->assertTrue(isset($oEntries->oxuser__oxfname));
        $this->assertEquals("John", $oEntries->oxuser__oxfname->value);
    }

    /**
     * GuestBook::getPageNavigation() test case
     *
     * @return null
     */
    public function testGetPageNavigation()
    {
        $oView = $this->getMock("oeGuestBookGuestBook", array("generatePageNavigation"));
        $oView->expects($this->once())->method('generatePageNavigation')->will($this->returnValue("generatePageNavigation"));
        $this->assertEquals("generatePageNavigation", $oView->getPageNavigation());
    }

    /**
     * Testing Contact::getBreadCrumb()
     *
     * @return null
     */
    public function testGetBreadCrumb()
    {
        $oGuestBook = oxNew('oeGuestBookGuestBook');

        $this->assertEquals(1, count($oGuestBook->getBreadCrumb()));
    }

    /**
     * oeGuestBookGuestBook::render() test case - login screen.
     *
     * @return null
     */
    public function testRender_loginScreen()
    {
        $oView = $this->getMock($this->getProxyClassName('oeGuestBookGuestBook'), array('getEntries'));
        $oView->expects($this->never())->method('getEntries');
        $oView->setNonPublicVar('_blShowLogin', true);

        $this->assertEquals('page/guestbook/oeguestbookguestbook_login.tpl', $oView->render());
    }

    public function testSaveEntry_nouser()
    {
        $this->getSession()->setVariable('usr', null);
        $this->setRequestParameter('rvw_txt', '');

        /** @var oxConfig|PHPUnit_Framework_MockObject_MockObject $oConfig */
        $oConfig = $this->getMock('oxConfig', array('getShopId'));
        $oConfig->expects($this->atLeastOnce())->method('getShopId')->will($this->returnValue('1'));

        /** @var oeGuestBookEntry|PHPUnit_Framework_MockObject_MockObject $oGuestBookEntry */
        $oGuestBookEntry = $this->getMock('oeGuestBookEntry', array('save', 'floodProtection'));
        $oGuestBookEntry->expects($this->never())->method('save');
        $oGuestBookEntry->expects($this->never())->method('floodProtection');
        oxTestModules::addModuleObject('oeGuestBookEntry', $oGuestBookEntry);

        /** @var oxSession|PHPUnit_Framework_MockObject_MockObject $oSession */
        $oSession = $this->getMock('oxSession', array('checkSessionChallenge'));
        $oSession->expects($this->once())->method('checkSessionChallenge')->will($this->returnValue(true));
        oxRegistry::set('oxSession', $oSession);

        /** @var oeGuestBookGuestBook|PHPUnit_Framework_MockObject_MockObject $oView */
        $oView = $this->getMock('oeGuestBookGuestBook', array('getConfig', 'canAcceptFormData'));
        $oView->expects($this->atLeastOnce())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->never())->method('canAcceptFormData');
        $this->assertNull($oView->saveEntry());
    }

    public function testSaveEntry_noshop()
    {
        $this->getSession()->setVariable('usr', 'some_userid');
        $this->setRequestParameter('rvw_txt', '');

        /** @var oxConfig|PHPUnit_Framework_MockObject_MockObject $oConfig */
        $oConfig = $this->getMock('oxConfig', array('getShopId'));
        $oConfig->expects($this->atLeastOnce())->method('getShopId')->will($this->returnValue(null));

        /** @var oeGuestBookEntry|PHPUnit_Framework_MockObject_MockObject $oGuestBookEntry */
        $oGuestBookEntry = $this->getMock('oeGuestBookEntry', array('save', 'floodProtection'));
        $oGuestBookEntry->expects($this->never())->method('save');
        $oGuestBookEntry->expects($this->never())->method('floodProtection');
        oxTestModules::addModuleObject('oeGuestBookEntry', $oGuestBookEntry);

        /** @var oxSession|PHPUnit_Framework_MockObject_MockObject $oSession */
        $oSession = $this->getMock('oxSession', array('checkSessionChallenge'));
        $oSession->expects($this->once())->method('checkSessionChallenge')->will($this->returnValue(true));
        oxRegistry::set('oxSession', $oSession);

        /** @var oeGuestBookGuestBook|PHPUnit_Framework_MockObject_MockObject $oView */
        $oView = $this->getMock('oeGuestBookGuestBook', array('getConfig', 'canAcceptFormData'));
        $oView->expects($this->atLeastOnce())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->never())->method('canAcceptFormData');
        $this->assertSame('oeguestbookguestbookentry', $oView->saveEntry());
    }

    public function testSaveEntry_noReview()
    {
        $this->getSession()->setVariable('usr', 'some_userid');
        $this->setRequestParameter('rvw_txt', '');

        /** @var oxConfig|PHPUnit_Framework_MockObject_MockObject $oConfig */
        $oConfig = $this->getMock('oxConfig', array('getShopId'));
        $oConfig->expects($this->atLeastOnce())->method('getShopId')->will($this->returnValue('1'));

        /** @var oeGuestBookEntry|PHPUnit_Framework_MockObject_MockObject $oGuestBookEntry */
        $oGuestBookEntry = $this->getMock('oeGuestBookEntry', array('save', 'floodProtection'));
        $oGuestBookEntry->expects($this->never())->method('save');
        $oGuestBookEntry->expects($this->never())->method('floodProtection');
        oxTestModules::addModuleObject('oeGuestBookEntry', $oGuestBookEntry);

        /** @var oxSession|PHPUnit_Framework_MockObject_MockObject $oSession */
        $oSession = $this->getMock('oxSession', array('checkSessionChallenge'));
        $oSession->expects($this->once())->method('checkSessionChallenge')->will($this->returnValue(true));
        oxRegistry::set('oxSession', $oSession);

        /** @var oeGuestBookGuestBook|PHPUnit_Framework_MockObject_MockObject $oView */
        $oView = $this->getMock('oeGuestBookGuestBook', array('getConfig', 'canAcceptFormData'));
        $oView->expects($this->atLeastOnce())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->never())->method('canAcceptFormData');
        $this->assertSame('oeguestbookguestbookentry', $oView->saveEntry());
    }

    public function testSaveEntry_floodFailed()
    {
        $this->getSession()->setVariable('usr', 'some_userid');
        $this->setRequestParameter('rvw_txt', 'some review');

        /** @var oxConfig|PHPUnit_Framework_MockObject_MockObject $oConfig */
        $oConfig = $this->getMock('oxConfig', array('getShopId'));
        $oConfig->expects($this->atLeastOnce())->method('getShopId')->will($this->returnValue('1'));

        /** @var oeGuestBookEntry|PHPUnit_Framework_MockObject_MockObject $oGuestBookEntry */
        $oGuestBookEntry = $this->getMock('oeGuestBookEntry', array('save', 'floodProtection'));
        $oGuestBookEntry->expects($this->never())->method('save');
        $oGuestBookEntry->expects($this->once())->method('floodProtection')->will($this->returnValue(true));
        oxTestModules::addModuleObject('oeGuestBookEntry', $oGuestBookEntry);

        /** @var oxSession|PHPUnit_Framework_MockObject_MockObject $oSession */
        $oSession = $this->getMock('oxSession', array('checkSessionChallenge'));
        $oSession->expects($this->once())->method('checkSessionChallenge')->will($this->returnValue(true));
        oxRegistry::set('oxSession', $oSession);

        /** @var oeGuestBookGuestBook|PHPUnit_Framework_MockObject_MockObject $oView */
        $oView = $this->getMock('oeGuestBookGuestBook', array('getConfig', 'canAcceptFormData'));
        $oView->expects($this->atLeastOnce())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->never())->method('canAcceptFormData');
        $this->assertSame('oeguestbookguestbookentry', $oView->saveEntry());
    }

    public function testSaveEntry_saveCall()
    {
        $this->getSession()->setVariable('usr', 'some_userid');
        $this->setRequestParameter('rvw_txt', 'some review');

        /** @var oxConfig|PHPUnit_Framework_MockObject_MockObject $oConfig */
        $oConfig = $this->getMock('oxConfig', array('getShopId'));
        $oConfig->expects($this->atLeastOnce())->method('getShopId')->will($this->returnValue('1'));

        /** @var oeGuestBookEntry|PHPUnit_Framework_MockObject_MockObject $oGuestBookEntry */
        $oGuestBookEntry = $this->getMock('oeGuestBookEntry', array('save', 'floodProtection'));
        $oGuestBookEntry->expects($this->once())->method('save');
        $oGuestBookEntry->expects($this->once())->method('floodProtection')->will($this->returnValue(false));
        oxTestModules::addModuleObject('oeGuestBookEntry', $oGuestBookEntry);

        /** @var oxSession|PHPUnit_Framework_MockObject_MockObject $oSession */
        $oSession = $this->getMock('oxSession', array('checkSessionChallenge'));
        $oSession->expects($this->once())->method('checkSessionChallenge')->will($this->returnValue(true));
        oxRegistry::set('oxSession', $oSession);

        /** @var oeGuestBookGuestBook|PHPUnit_Framework_MockObject_MockObject $oView */
        $oView = $this->getMock('oeGuestBookGuestBook', array('getConfig', 'canAcceptFormData'));
        $oView->expects($this->atLeastOnce())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->once())->method('canAcceptFormData')->will($this->returnValue(true));
        $this->assertSame('oeguestbookguestbook', $oView->saveEntry());
    }

    public function testSaveEntry_nosavecall()
    {
        $this->getSession()->setVariable('usr', 'some_userid');
        $this->setRequestParameter('rvw_txt', 'some review');

        /** @var oxConfig|PHPUnit_Framework_MockObject_MockObject $oConfig */
        $oConfig = $this->getMock('oxConfig', array('getShopId'));
        $oConfig->expects($this->atLeastOnce())->method('getShopId')->will($this->returnValue('1'));

        /** @var oeGuestBookEntry|PHPUnit_Framework_MockObject_MockObject $oGuestBookEntry */
        $oGuestBookEntry = $this->getMock('oeGuestBookEntry', array('save', 'floodProtection'));
        $oGuestBookEntry->expects($this->never())->method('save');
        $oGuestBookEntry->expects($this->once())->method('floodProtection')->will($this->returnValue(false));
        oxTestModules::addModuleObject('oeGuestBookEntry', $oGuestBookEntry);

        /** @var oxSession|PHPUnit_Framework_MockObject_MockObject $oSession */
        $oSession = $this->getMock('oxSession', array('checkSessionChallenge'));
        $oSession->expects($this->once())->method('checkSessionChallenge')->will($this->returnValue(true));
        oxRegistry::set('oxSession', $oSession);

        /** @var oeGuestBookGuestBook|PHPUnit_Framework_MockObject_MockObject $oView */
        $oView = $this->getMock('oeGuestBookGuestBook', array('getConfig', 'canAcceptFormData'));
        $oView->expects($this->atLeastOnce())->method('getConfig')->will($this->returnValue($oConfig));
        $oView->expects($this->once())->method('canAcceptFormData')->will($this->returnValue(false));
        $this->assertSame('oeguestbookguestbook', $oView->saveEntry());
    }
}
