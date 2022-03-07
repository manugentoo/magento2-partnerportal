“Manugentoo Base Module” extension
=====================
Magento 2 Manugentoo PartnerPortal Module
Extension homepage: https://github.com/manugentoo/partnerportal

## CONTACTS
* Email: manugentoo@gmail.com
* LinkedIn: https://www.linkedin.com/in/manuelcsantosjr/

## DONATIONS / SUPPORT ME ON
* [Patreon](https://www.patreon.com/manugentoo)

## INSTALLATION

### COMPOSER INSTALLATION
* run composer command:
>`$> composer require manugentoo/partnerportal`

### MANUAL INSTALLATION
* extract files from an archive

* deploy files into Magento2 folder `app/code/Manugentoo/PartnerPortal`

### ENABLE EXTENSION
* enable extension (use Magento 2 command line interface \*):
>`$> php bin/magento module:enable Manugentoo_PartnerPortal`

* to make sure that the enabled module is properly registered, run 'setup:upgrade':
>`$> php bin/magento setup:upgrade`

* [if needed] re-compile code and re-deploy static view files:
>`$> php bin/magento setup:di:compile`
>`$> php bin/magento setup:static-content:deploy`

Enjoy!

Best regards,
Manu Gentoo

-------------
\* see: http://devdocs.magento.com/guides/v2.0/config-guide/cli/config-cli-subcommands.html
