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

class oeGuestBookBackendTest extends \OxidEsales\TestingLibrary\AcceptanceTestCase
{
    /**
     * Activates Guestbook module
     *
     * @param string $sTestSuitePath
     *
     * @throws Exception
     */
    public function addTestData($sTestSuitePath)
    {
        $this->importSql(__DIR__ . '/../../docs/install.sql');
        parent::addTestData($sTestSuitePath);
    }

    /**
     * creating Guestbook
     *
     * @group creatingitems
     */
    public function testCreateGuestbook()
    {
        $this->loginAdmin("Customer Info", "Guestbook");
        $this->openListItem("Demo guestbook entry [DE] šÄßüл");
        $this->assertEquals("Demo guestbook entry [DE] šÄßüл", $this->getValue("editval[oeguestbookentry__oxcontent]"));
        $this->type("editval[oeguestbookentry__oxcontent]", "ddd_šÄßüл");
        $this->clickAndWaitFrame("save", "list");
        $this->frame("list");
        $this->assertElementPresent("link=ddd_šÄßüл");
        $this->frame("edit");
        $this->assertEquals("ddd_šÄßüл", $this->getValue("editval[oeguestbookentry__oxcontent]"));
    }


}
