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
 * Tests for oeGuestBookAdminGuestBookList class
 */
class oeGuestBookAdminGuestBookListTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * AdminGuestbook_List::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oEntry = oxNew('oeGuestBookEntry');
        $oEntry->oeguestbookentry__oxuserid = new oxField("oxdefaultadmin");

        $oList = oxNew('oxList');
        $oList->offsetSet("testEntryId", $oEntry);

        // testing..
        $oView = $this->getMock("oeGuestBookAdminGuestBookList", array("getItemList"));
        $oView->expects($this->any())->method('getItemList')->will($this->returnValue($oList));
        $sTplName = $oView->render();
        $this->assertEquals('oeguestbookadminguestbooklist.tpl', $sTplName);

        // testing view data
        $aViewData = $oView->getViewData();
        $this->assertTrue($aViewData["mylist"] instanceof oxList);
        $this->assertEquals(1, $aViewData["mylist"]->count());

    }
}
