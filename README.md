# revertcontactinfo
This extension is aimed for backend users within CiviCRM. It allows the user to revert a Contact's email, phone, address, or all three if there is a previous value for those entities within the CiviCRM database.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.4+
* CiviCRM 5.60

## Installation (Web UI)

Learn more about installing CiviCRM extensions in the [CiviCRM Sysadmin Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/).

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl revertcontactinfo@https://github.com/FIXME/revertcontactinfo/archive/master.zip
```
or
```bash
cd <extension-dir>
cv dl revertcontactinfo@https://lab.civicrm.org/extensions/revertcontactinfo/-/archive/main/revertcontactinfo-main.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/FIXME/revertcontactinfo.git
cv en revertcontactinfo
```
or
```bash
git clone https://lab.civicrm.org/extensions/revertcontactinfo.git
cv en revertcontactinfo
```

## Getting Started
1. Once installed, go to a Contact record within the backend of CiviCRM
2. Click on the **Change Log** tab
3. This extension provides four buttons; one each to revert a Contact's email, phone, or address and one to revert all three. 
4. If this Contact has a previous value for one of the above mentioned entities, clicking the respective button will update the Contact's record and replace the current value with the *second most recent* value. A success message will be returned to the user.
5. If this Contact does not have a previous value for the entity, then the value will not be reverted and the user will receive an error message detailing that inability.


## Known Issues (Technical)

Be aware that the JS file currently relies on using the `innerHTML` method to format the `entity` variable as an array `if (this.innerHTML == 'Revert all')`. 'Revert all' is the name of the fourth button that reverts all three entities. Should the html of this button be changed within the template, the above mentioned coditional within [revert.js](https://github.com/briennekordis/civicrmExt_revertContactInfo/blob/main/js/revert.js) will also need to be updated.
