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
 * Guestbook records list manager.
 * Returns template, that arranges guestbook records list.
 * Admin Menu: User information -> Guestbook.
 */
class oeGuestBookAdminGuestBookList extends \OxidEsales\Eshop\Application\Controller\Admin\AdminListController
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'oeguestbookadminguestbooklist.tpl';

    /**
     * List item object type
     *
     * @var string
     */
    protected $_sListClass = 'oeguestbookentry';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = 'oxcreate';

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_blDesc = true;

    /**
     * Executes parent method parent::render(), gets entries with authors
     * and returns template file name "oeguestbookadminguestbooklist.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $itemList = $this->getItemList();
        if ($itemList && $itemList->count()) {
            $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            foreach ($itemList as $entry) {
                // preloading user info ..
                $userIdField = 'oeguestbookentry__oxuserid';
                $userLastNameField = 'oxuser__oxlname';
                if (isset($entry->$userIdField) && $entry->$userIdField->value) {
                    $sSql = "select oxlname from oxuser where oxid=" . $oDb->quote($entry->$userIdField->value);
                    $entry->$userLastNameField = new \OxidEsales\Eshop\Core\Field($oDb->getOne($sSql, false));
                }
            }
        }

        $this->_aViewData["mylist"] = $itemList;

        return $this->_sThisTemplate;
    }
}
