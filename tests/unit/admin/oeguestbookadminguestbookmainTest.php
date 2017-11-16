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

/**
 * Tests for oeGuestBookAdminGuestBookMain class
 */
class oeGuestBookAdminGuestBookMainTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * oeGuestBookAdminGuestBookMain::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $this->setRequestParameter("oxid", "xxx");
        oxTestModules::addFunction('oeguestbookEntry', 'save', '{ return true; }');

        // testing..
        $oView = oxNew('oeGuestBookAdminGuestBookMain');
        $sTplName = $oView->render();

        // testing view data
        $aViewData = $oView->getViewData();
        $this->assertNotNull($aViewData["edit"]);
        $this->assertTrue($aViewData["edit"] instanceof oeGuestBookEntry);
        $this->assertEquals($this->getConfig()->getConfigParam("oeGuestBookModerate"), $aViewData["blShowActBox"]);

        $this->assertEquals('oeguestbookadminguestbookmain.tpl', $sTplName);
    }

    /**
     * oeGuestBookAdminGuestBookMain::Render() test case
     *
     * @return null
     */
    public function testRenderDefaultOxid()
    {
        $this->setRequestParameter("oxid", "-1");
        $this->setRequestParameter("saved_oxid", "-1");

        // testing..
        $oView = oxNew('oeGuestBookAdminGuestBookMain');
        $sTplName = $oView->render();

        // testing view data
        $aViewData = $oView->getViewData();
        $this->assertFalse(isset($aViewData["edit"]));
        $this->assertTrue(isset($aViewData["oxid"]));
        $this->assertEquals("-1", $aViewData["oxid"]);
        $this->assertEquals($this->getConfig()->getConfigParam("oeGuestBookModerate"), $aViewData["blShowActBox"]);

        $this->assertEquals('oeguestbookadminguestbookmain.tpl', $sTplName);
    }

    /**
     * oeGuestBookAdminGuestBookMain::Save() test case
     *
     * @return null
     */
    public function testSave()
    {
        oxTestModules::addFunction('oeguestbookentry', 'load', '{ return true; }');
        oxTestModules::addFunction('oeguestbookentry', 'save', '{ return true; }');

        $this->setRequestParameter("oxid", "xxx");
        $this->setRequestParameter("editval", array("xxx"));

        $oView = oxNew('oeGuestBookAdminGuestBookMain');
        $oView->save();

        $aViewData = $oView->getViewData();
        $this->assertTrue(isset($aViewData["updatelist"]));
        $this->assertEquals(1, $aViewData["updatelist"]);
    }

    /**
     * oeGuestBookAdminGuestBookMain::Save() test case
     *
     * @return null
     */
    public function testSaveDefaultOxid()
    {
        oxTestModules::addFunction('oeguestbookentry', 'save', '{ $this->oeguestbookentry__oxid = new oxField( "testId" ); return true; }');

        $this->setRequestParameter("oxid", "-1");
        $this->setRequestParameter("editval", array("xxx"));

        $oView = oxNew('oeGuestBookAdminGuestBookMain');
        $oView->save();

        $aViewData = $oView->getViewData();
        $this->assertTrue(isset($aViewData["updatelist"]));
        $this->assertEquals(1, $aViewData["updatelist"]);
    }
}
