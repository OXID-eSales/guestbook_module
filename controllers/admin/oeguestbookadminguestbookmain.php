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
class oeGuestBookAdminGuestBookMain extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
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
            $links = oxNew('oeGuestBookEntry');
            $links->load($soxId);

            // #580A - setting GB entry as viewed in admin
            if (!isset($links->oeguestbookentry__oxviewed) || !$links->oeguestbookentry__oxviewed->value) {
                $links->oeguestbookentry__oxviewed = new oxField(1);
                $links->save();
            }
            $this->_aViewData["edit"] = $links;
        }

        //show "active" checkbox if moderating is active
        $this->_aViewData['blShowActBox'] = $myConfig->getConfigParam('oeGuestBookModerate');

        return 'oeguestbookadminguestbookmain.tpl';
    }

    /**
     * Saves guestbook record changes.
     */
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        /** @var \OxidEsales\Eshop\Core\Request $request */
        $request = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Request::class);
        $formData = $request->getRequestEscapedParameter("editval");

        // checkbox handling
        if (!isset($formData['oeguestbookentry__oxactive'])) {
            $formData['oeguestbookentry__oxactive'] = 0;
        }

        $links = $this->getGuestbookEntryObject();
        if ($soxId != "-1") {
            $links->load($soxId);
        } else {
            $formData['oeguestbookentry__oxid'] = null;

            // author
            $formData['oeguestbookentry__oxuserid'] = oxRegistry::getSession()->getVariable('auth');
        }

        $formData = $this->appendAdditionalParametersForSave($formData);

        $links->assign($formData);
        $links->save();
        $this->setEditObjectId($links->getId());
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
     * Add additional parameters for saving on oeGuestBookEntry save
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
