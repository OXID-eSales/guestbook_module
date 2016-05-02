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

class TimestampTest extends OxidTestCase
{
    /**
     * Tear down the fixture.
     */
    protected function tearDown()
    {
        $this->cleanUpTable('oeguestbookentry');

        // OXID is not string in oxshops table !!!
        oxDb::getDb()->execute("DELETE FROM `oxshops` WHERE `oxid` = '0'");

        parent::tearDown();
    }

    /**
     * oxtimestamp field must have been setted with creation date on direct db insert
     *
     */
    public function testOnInsertDb()
    {
        $sInsertSql = "INSERT INTO `oeguestbookentry` SET `oxid` = '_testId'";
        $sSelectSql = "SELECT `oxtimestamp` FROM `oeguestbookentry` WHERE `oxid` = '_testId'";

        $oDb = oxDb::getDb();

        $oDb->Execute($sInsertSql);
        $sTimeStamp = $oDb->getOne($sSelectSql);

        $this->assertTrue($sTimeStamp != '0000-00-00 00:00:00');
    }

    /**
     * oxtimestamp field must have been setted with modification date on direct db update
     *
     */
    public function testOnUpdateDb()
    {
        $sInsertSql = "INSERT INTO `oeguestbookentry` SET `oxid` = '_testId', `oxtimestamp` = '0000-00-00 00:00:00' ";
        $sUpdateSql = "UPDATE `oeguestbookentry` SET `oxcontent` = '_testmodified' WHERE `oxid` = '_testId'";
        $sSelectSql = "SELECT `oxtimestamp` FROM `oeguestbookentry` WHERE `oxid` = '_testId'";

        $oDb = oxDb::getDb();

        $oDb->Execute($sInsertSql);
        $oDb->Execute($sUpdateSql);

        $sTimeStamp = $oDb->getOne($sSelectSql);

        $this->assertTrue($sTimeStamp != '0000-00-00 00:00:00');
    }

    /**
     * oxtimestamp field must have been setted creation date on object insert
     *
     */
    public function testOnInsert()
    {
        $oObject = oxNew('oeGuestBookEntry');
        $oObject->setId('_testId');
        $oObject->oeguestbookentry__oxcontent = new oxField('test');
        $oObject->save();

        $oObject = oxNew('oeGuestBookEntry');
        $oObject->load('_testId');

        $attName = oeguestbookentry . '__oxtimestamp';

        $this->assertTrue($oObject->$attName->value != '0000-00-00 00:00:00');
    }

    /**
     * oxtimestamp field must have been setted modification date on object update
     *
     */
    public function testOnUpdate()
    {
        $attName = oeguestbookentry . '__oxtimestamp';
        $attNameMod = oeguestbookentry__oxcontent;

        $oObject = oxNew('oeGuestBookEntry');
        $oObject->setId('_testId');
        $oObject->$attName = new oxField('0000-00-00 00:00:00');
        $oObject->$attNameMod = new oxField('test');
        $oObject->save();

        $oObject = oxNew('oeGuestBookEntry');
        $oObject->load('_testId');
        $oObject->$attNameMod = new oxField('testmodyfied');
        $oObject->save();

        $oObject = oxNew('oeGuestBookEntry');
        $oObject->load('_testId');

        $this->assertTrue($oObject->$attName->value != '0000-00-00 00:00:00');
    }

}