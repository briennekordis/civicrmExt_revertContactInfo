<?php

require_once 'revertcontactinfo.civix.php';
// phpcs:disable
use CRM_Revertcontactinfo_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function revertcontactinfo_civicrm_config(&$config): void {
  _revertcontactinfo_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function revertcontactinfo_civicrm_install(): void {
  _revertcontactinfo_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function revertcontactinfo_civicrm_enable(): void {
  _revertcontactinfo_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function revertcontactinfo_civicrm_preProcess($formName, &$form): void {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function revertcontactinfo_civicrm_navigationMenu(&$menu): void {
//  _revertcontactinfo_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _revertcontactinfo_civix_navigationMenu($menu);
//}

function revertcontactinfo_civicrm_buildForm($formName, &$form) {
  if ($formName === 'CRM_Activity_Form_ActivityLinks') {
    CRM_Core_Region::instance('page-footer')->add(
      ['template' => 'revertdata.tpl'],
    );
    Civi::resources()->addScriptFile('revertcontactinfo', 'js/revert.js');
  }
}
