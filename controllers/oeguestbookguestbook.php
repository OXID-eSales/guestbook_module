<?php
/**

 *
 * @category      module
 * @package       guestbook
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 */


/**
 * Shop guestbook page.
 * Manages, collects, denies user comments.
 */
class oeGuestBookGuestBook extends oxUBase
{


    /**
     * Number of possible pages.
     *
     * @var integer
     */
    protected $_iCntPages = null;

    /**
     * Boolean for showing login form instead of guestbook entries
     *
     * @var bool
     */
    protected $_blShowLogin = false;

    /**
     * Array of sorting columns
     *
     * @var array
     */
    protected $_aSortColumns = null;

    /**
     * Order by
     *
     * @var string
     */
    protected $_sListOrderBy = false;

    /**
     * Oreder directory
     *
     * @var string
     */
    protected $_sListOrderDir = false;

    /**
     * Flood protection
     *
     * @var bool
     */
    protected $_blFloodProtection = null;

    /**
     * Guestbook entries
     *
     * @var array
     */
    protected $_aEntries = null;

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'page/guestbook/oeguestbookguestbook.tpl';

    /**
     * Current class login template name
     *
     * @var string
     */
    protected $_sThisLoginTemplate = 'page/guestbook/oeguestbookguestbook_login.tpl';

    /**
     * Marked which defines if current view is sortable or not
     *
     * @var bool
     */
    protected $_blShowSorting = true;

    /**
     * Page navigation
     *
     * @var object
     */
    protected $_oPageNavigation = null;

    /**
     * Current view search engine indexing state
     *
     * @var int
     */
    protected $_iViewIndexState = VIEW_INDEXSTATE_NOINDEXNOFOLLOW;

    /**
     * Loads guestbook entries, forms guestbook naviagation URLS,
     * executes parent::render() and returns name of template to
     * render guestbook::_sThisTemplate.
     *
     * @return  string  $this->_sThisTemplate   current template file name
     */
    public function render()
    {
        $this->_sThisTemplate = $this->render_parent();

        // #774C no user mail and password check in guesbook
        if ($this->_blShowLogin) {
            //no valid login
            return $this->_sThisLoginTemplate;
        }

        $this->getEntries();

        return $this->_sThisTemplate;
    }

    /**
     * Parent `render` call. Method required for mocking.
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    protected function render_parent()
    {
        return parent::render();
    }

    /**
     * Template variable getter. Returns sorting columns
     *
     * @return array
     */
    public function getSortColumns()
    {
        if ($this->_aSortColumns === null) {
            $this->setSortColumns(array('author', 'date'));
        }

        return $this->_aSortColumns;
    }

    /**
     * Template variable getter. Returns order by
     *
     * @return string
     */
    public function getGbSortBy()
    {
        return $this->_sListOrderBy;
    }

    /**
     * Template variable getter. Returns order directory
     *
     * @return string
     */
    public function getGbSortDir()
    {
        return $this->_sListOrderDir;
    }

    /**
     * Loads guestbook entries for active page and returns them.
     *
     * @return array Guestbook entries
     */
    public function getEntries()
    {
        if ($this->_aEntries === null) {
            $this->_aEntries = false;
            $numberOfCategoryArticles = (int) $this->getConfig()->getConfigParam('iNrofCatArticles');
            $numberOfCategoryArticles = $numberOfCategoryArticles ? $numberOfCategoryArticles : 10;

            // loading only if there is some data
            $entries = oxNew('oxGbEntry');
            if ($count = $entries->getEntryCount()) {
                $this->_iCntPages = ceil($count / $numberOfCategoryArticles);
                $this->_aEntries = $entries->getAllEntries(
                    $this->getActPage() * $numberOfCategoryArticles,
                    $numberOfCategoryArticles,
                    $this->getSortingSql($this->getSortIdent())
                );
            }
        }

        return $this->_aEntries;
    }

    /**
     * Template variable getter. Returns boolean of flood protection
     *
     * @return bool
     */
    public function floodProtection()
    {
        if ($this->_blFloodProtection === null) {
            $this->_blFloodProtection = false;
            // is user logged in ?
            $userId = oxRegistry::getSession()->getVariable('usr');
            $userId = $userId ? $userId : 0;

            $entries = oxNew('oxGbEntry');
            $this->_blFloodProtection = $entries->floodProtection($this->getConfig()->getShopId(), $userId);
        }

        return $this->_blFloodProtection;
    }

    /**
     * Returns sorted column parameter name
     *
     * @return string
     */
    public function getSortOrderByParameterName()
    {
        return 'gborderby';
    }

    /**
     * Returns sorted column direction parameter name
     *
     * @return string
     */
    public function getSortOrderParameterName()
    {
        return 'gborder';
    }

    /**
     * Returns page sort identificator. It is used as identificator in session variable aSorting[id]
     *
     * @return string
     */
    public function getSortIdent()
    {
        return 'oxgb';
    }

    /**
     * Returns default category sorting for selected category
     *
     * @return array
     */
    public function getDefaultSorting()
    {
        $sorting = array('sortby' => 'date', 'sortdir' => 'desc');

        return $sorting;
    }

    /**
     * Template variable getter. Returns page navigation
     *
     * @return object
     */
    public function getPageNavigation()
    {
        if ($this->_oPageNavigation === null) {
            $this->_oPageNavigation = false;
            $this->_oPageNavigation = $this->generatePageNavigation();
        }

        return $this->_oPageNavigation;
    }

    /**
     * Method applies validation to entry and saves it to DB.
     * On error/success returns name of action to perform
     * (on error: "guestbookentry?error=x"", on success: "guestbook").
     *
     * @return string
     */
    public function saveEntry()
    {
        if (!oxRegistry::getSession()->checkSessionChallenge()) {
            return;
        }

        $reviewText = trim(( string ) oxRegistry::getConfig()->getRequestParameter('rvw_txt', true));
        $shopId = $this->getConfig()->getShopId();
        $userId = oxRegistry::getSession()->getVariable('usr');

        // guest book`s entry is validated
        $utilsView = oxRegistry::get("oxUtilsView");
        if (!$userId) {
            $utilsView->addErrorToDisplay('ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_LOGIN_TO_WRITE_ENTRY');

            //return to same page
            return;
        }

        if (!$shopId) {
            $utilsView->addErrorToDisplay('ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_UNDEFINED_SHOP');

            return 'guestbookentry';
        }

        // empty entries validation
        if ('' == $reviewText) {
            $utilsView->addErrorToDisplay('ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_REVIEW_CONTAINS_NO_TEXT');

            return 'guestbook';
        }

        // flood protection
        $entry = oxNew('oxGbEntry');
        if ($entry->floodProtection($shopId, $userId)) {
            $utilsView->addErrorToDisplay('ERROR_MESSAGE_GUESTBOOK_ENTRY_ERR_MAXIMUM_NUMBER_EXCEEDED');

            return 'guestbookentry';
        }

        // double click protection
        if ($this->canAcceptFormData()) {
            // here the guest book entry is saved
            $newEntry = oxNew('oxGbEntry');
            $newEntry->oxgbentries__oxshopid = new oxField($shopId);
            $newEntry->oxgbentries__oxuserid = new oxField($userId);
            $newEntry->oxgbentries__oxcontent = new oxField($reviewText);
            $newEntry->save();
        }

        return 'guestbook';
    }

    /**
     * Returns Bread Crumb - you are here page1/page2/page3...
     *
     * @return array
     */
    public function getBreadCrumb()
    {
        $paths = array();
        $path = array();

        $baseLanguageId = oxRegistry::getLang()->getBaseLanguage();
        $path['title'] = oxRegistry::getLang()->translateString('GUESTBOOK', $baseLanguageId, false);
        $path['link'] = $this->getLink();
        $paths[] = $path;

        return $paths;
    }
}
