<?php
/*-------------------------------------------------------+
| REMOTE EVENTS IMPLEMENTATION AND MODIFICATIONS           |
| Copyright (C) 2020 SYSTOPIA                            |
| Author: J. Margraf <margraf@systopia.de>,              |
|         B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

require_once 'events.civix.php';
use CRM_Events_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function events_civicrm_config(&$config) {
    _events_civix_civicrm_config($config);

    // register for RemoteParticipant.create events
    $dispatcher = new Civi\RemoteDispatcher();
    $dispatcher->addUniqueListener(
        'civi.remoteevent.registration.submit',
        ['CRM_Remoteevent_RegistrationProfile_IntesRegular', 'mapParticipantData'], CRM_Remoteevent_Registration::BEFORE_PARTICIPANT_CREATION);

    //$dispatcher->addUniqueListener(
    //    'civi.remoteevent.registration.submit',
    //    ['CRM_Remoteevent_RegistrationProfile_IntesRegular', 'markContributor'], CRM_Remoteevent_Registration::BEFORE_PARTICIPANT_CREATION);

    // register for RemoteParticipant.update events
    //$dispatcher->addUniqueListener(
    //    'civi.remoteevent.registration.update',
    //    ['CRM_Events_PresbyterTag', 'mapRegistrationFieldsToContactFields'], CRM_Remoteevent_RegistrationUpdate::BEFORE_APPLY_CONTACT_CHANGES);

    // register for event/session info render events
    //$dispatcher->addUniqueListener(
    //    'civi.remoteevent.render',
    //    ['CRM_Events_UmbrellaEvent', 'modifySessionRendering']);
    //$dispatcher->addUniqueListener(
    //    'civi.remoteevent.label',
    //    ['CRM_Events_UmbrellaEvent', 'modifyGroupLabels']);

    // register for RemoteParticipant.validate events
    //$dispatcher->addUniqueListener(
    //    'civi.remoteevent.registration.validate',
    //    ['CRM_Events_UmbrellaEvent', 'adjustValidationResults']);
    //$dispatcher->addUniqueListener(
    //    'civi.remoteevent.registration.validate',
    //    ['CRM_Events_GeneralModifications', 'adjustValidationResults']);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function events_civicrm_xmlMenu(&$files) {
  _events_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function events_civicrm_install() {
  _events_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function events_civicrm_postInstall() {
  _events_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function events_civicrm_uninstall() {
  _events_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function events_civicrm_enable() {
  _events_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function events_civicrm_disable() {
  _events_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function events_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _events_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function events_civicrm_managed(&$entities) {
  _events_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function events_civicrm_caseTypes(&$caseTypes) {
  _events_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function events_civicrm_angularModules(&$angularModules) {
  _events_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function events_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _events_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function events_civicrm_entityTypes(&$entityTypes) {
  _events_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function events_civicrm_themes(&$themes) {
  _events_civix_civicrm_themes($themes);
}


// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function events_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function events_civicrm_navigationMenu(&$menu) {
//  _events_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _events_civix_navigationMenu($menu);
//}
