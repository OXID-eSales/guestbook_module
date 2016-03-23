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
 * Guestbook record manager.
 * Returns template, that arranges guestbook record information.
 * Admin Menu: User information -> Guestbook -> Main.
 */
class oeGuestBookAdminGuestBookMain extends oxAdminDetails
{

    /**
     * Executes parent method parent::render() and returns template file
     * name "adminguestbook_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        if (isset($soxId) && $soxId != '-1') {
            // load object
            $oLinks = oxNew('oeGuestBookEntry');
            $oLinks->load($soxId);

            // #580A - setting GB entry as viewed in admin
            if (!isset($oLinks->oeguestbookentry__oxviewed) || !$oLinks->oeguestbookentry__oxviewed->value) {
                $oLinks->oeguestbookentry__oxviewed = new oxField(1);
                $oLinks->save();
            }
            $this->_aViewData["edit"] = $oLinks;
        }

        //show "active" checkbox if moderating is active
        $this->_aViewData['blShowActBox'] = $myConfig->getConfigParam('blGBModerate');

        return 'oeguestbookadminguestbookmain.tpl';
    }

    /**
     * Saves guestbook record changes.
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = oxRegistry::getConfig()->getRequestParameter("editval");

        // checkbox handling
        if (!isset($aParams['oeguestbookentry__oxactive'])) {
            $aParams['oeguestbookentry__oxactive'] = 0;
        }

        $oLinks = $this->getGuestbookEntryObject();
        if ($soxId != "-1") {
            $oLinks->load($soxId);
        } else {
            $aParams['oeguestbookentry__oxid'] = null;

            // author
            $aParams['oeguestbookentry__oxuserid'] = oxRegistry::getSession()->getVariable('auth');
        }

        $aParams = $this->appendAdditionalParametersForSave($aParams);

        $oLinks->assign($aParams);
        $oLinks->save();
        $this->setEditObjectId($oLinks->getId());
    }

    /**
     * Getter of oeGuestBookEntry object
     *
     * @return oeGuestBookEntry
     */
    public function getGuestbookEntryObject()
    {
        return oxNew('oeGuestBookEntry');
    }

    /**
     * Add additional parameters for saving on oxgbentry save
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function appendAdditionalParametersForSave($parameters)
    {
        return $parameters;
    }
}
