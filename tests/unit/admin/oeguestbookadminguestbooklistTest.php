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
 * Tests for AdminGuestbook_List class
 */
class oeGuestBookAdminGuestBookListTest extends OxidTestCase
{

    /**
     * AdminGuestbook_List::Render() test case
     *
     * @return null
     */
    public function testRender()
    {
        $oEntry = oxNew('GuestbookEntry');
        $oEntry->oxgbentries__oxuserid = new oxField("oxdefaultadmin");

        $oList = oxNew('oxList');
        $oList->offsetSet("testEntryId", $oEntry);

        // testing..
        $oView = $this->getMock("AdminGuestbook_List", array("getItemList"));
        $oView->expects($this->any())->method('getItemList')->will($this->returnValue($oList));
        $sTplName = $oView->render();
        $this->assertEquals('adminguestbook_list.tpl', $sTplName);

        // testing view data
        $aViewData = $oView->getViewData();
        $this->assertTrue($aViewData["mylist"] instanceof oxList);
        $this->assertEquals(1, $aViewData["mylist"]->count());

    }
}
