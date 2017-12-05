# Guestbook module

## Description

This module provides a function commonly known as guestbook: Frontend users can write text messages on the page guestbook of the eShop.
In order to prevent spam, there is a configuration option to set the maximum number of messages a user can write per day.
There is a section in the eShop admin where those text messages can be managed. In the module settings, you can configure that
messages get only published when they are activated by an Administrator User.

## Installation

Please proceed with one of the following ways to install the module:

### Module installation via composer

In order to install the module via composer, run the following commands in commandline of your shop base directory 
(where the shop's composer.json file resides).

```
composer require oxid-projects/guestbook-module
```

### Module installation via repository cloning

Clone the module to your OXID eShop **modules/oe/** directory:
```
git clone https://github.com/OXIDprojects/guestbook-module guestbook
```

### Module installation from zip package

* Make a new folder "guestbook" in the **modules/oe/ directory** of your shop installation. 
* Download the https://github.com/OXIDprojects/guestbook-module/archive/master.zip file and unpack it into the created folder.

## Activate Module

- Activate the module in the administration panel.
- Adapt module settings

## Uninstall

Disable the module in administration panel and/or delete the module folder. The database gets not touched at uninstallation.

## License

Licensing of the software product depends on the shop edition used. The software for OXID eShop Community Edition
is published under the GNU General Public License v3. You may distribute and/or modify this software according to
the licensing terms published by the Free Software Foundation. Legal licensing terms regarding the distribution of
software being subject to GNU GPL can be found under http://www.gnu.org/licenses/gpl.html. The software for OXID eShop
Professional Edition and Enterprise Edition is released under commercial license. OXID eSales AG has the sole rights to
the software. Decompiling the source code, unauthorized copying as well as distribution to third parties is not
permitted. Infringement will be reported to the authorities and prosecuted without exception.
