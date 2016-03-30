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

class oeGuestBookFrontendTest extends \OxidEsales\TestingLibrary\AcceptanceTestCase
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
        parent::addTestData($sTestSuitePath);

        $this->open(shopURL . "admin");
        $this->loginAdminForModule("Extensions", "Modules");

        $this->openListItem("Guestbook");
        if ($this->isElementPresent('module_activate')) {
            $this->clickAndWait("module_activate");
        }
    }

    /**
     * Guestbook spam control
     *
     * @group frontend
     */
    public function testFrontendGuestbookSpamProtection()
    {
        //setting spam protection 2 entries per day
        $this->callShopSC("oxConfig", null, null, array("oeGuestBookMaxGuestBookEntriesPerDay" => array("type" => "str", "value" => '2')));

        $this->clearCache();
        $this->openShop();
        $this->loginInFrontend("example_test@oxid-esales.dev", "useruser");
        $this->clickAndWait("//dl[@id='footerServices']//a[text()='%GUESTBOOK%']");
        $this->assertEquals("%PAGE_TITLE_GUESTBOOK%", $this->getText("//h1"));
        $this->assertFalse($this->isVisible("rvw_txt"));
        $this->assertElementPresent("writeNewReview");
        $this->_writeReview(1, true);

        $this->_writeReview(2, true);
        $this->_writeReview(3, false);

        $this->clickAndWait("//dl[@id='footerServices']//a[text()='%GUESTBOOK%']");
        $this->assertElementNotPresent("writeNewReview");

        // increasing guestbook entries limit
        $this->callShopSC("oxConfig", null, null, array("oeGuestBookMaxGuestBookEntriesPerDay" => array("type" => "str", "value" => '10')));

        $this->clickAndWait("//dl[@id='footerServices']//a[text()='%GUESTBOOK%']");
        $this->assertEquals("%PAGE_TITLE_GUESTBOOK%", $this->getText("//h1"));
        $this->assertElementPresent("writeNewReview");
        $this->_writeReview(3, true);
        $this->_writeReview(4, true);
    }

    /**
     * site footer
     *
     * @group frontend
     */
    public function testFrontendFooter()
    {
        $this->clickAndWait("//dl[@id='footerServices']//a[text()='%GUESTBOOK%']");
        $this->assertEquals("%YOU_ARE_HERE%: / %GUESTBOOK%", $this->getText("breadCrumb"));
        $this->assertEquals("%PAGE_TITLE_GUESTBOOK%", $this->getText("//h1"));
        $this->assertElementPresent("link=%MESSAGE_TO_BE_LOGGED_WRITE_GUESTBOOK%");
    }

    /**
     * Writes review and checks link visibility.
     *
     * @param $iGuestBookEntryNumberToAssert
     * @param $blReviewLinkVisible
     */
    private function _writeReview($iGuestBookEntryNumberToAssert, $blReviewLinkVisible)
    {
        $this->click("writeNewReview");
        $this->waitForItemAppear("rvw_txt");
        $this->type("rvw_txt", "guestbook entry No. $iGuestBookEntryNumberToAssert");
        $this->clickAndWait("//button[text()='%SUBMIT%']");
        $this->assertTextPresent("guestbook entry No. $iGuestBookEntryNumberToAssert");
        if ($blReviewLinkVisible) {
            $this->assertElementPresent("writeNewReview");
        } else {
            $this->assertElementNotPresent("writeNewReview");
        }
    }
}
