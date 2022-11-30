<?php
/*-------------------------------------------------------+
| SYSTOPIA Remote Event Extension                        |
| Copyright (C) 2022 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Events_ExtensionUtil as E;
use \Civi\RemoteParticipant\Event\ValidateEvent as ValidateEvent;
use Civi\RemoteParticipant\Event\GetParticipantFormEventBase as GetParticipantFormEventBase;
use Civi\RemoteParticipant\Event\RegistrationEvent as RegistrationEvent;

/**
 * Implements profile 'Intes regulär'
 */
class CRM_Remoteevent_RegistrationProfile_IntesRegular extends CRM_Remoteevent_RegistrationProfile_Standard3
{
    /**
     * Get the internal name of the profile represented
     *
     * @return string name
     */
    public function getName()
    {
        return 'intesregular';
    }

    /**
     * Get the human-readable name of the profile represented
     *
     * @return string label
     */
    public function getLabel()
    {
        return 'Intes regulär';
    }


    /**
     * @param string $locale
     *   the locale to use, defaults to null (current locale)
     *
     * @return array field specs
     * @see CRM_Remoteevent_RegistrationProfile::getFields()
     *
     */
    public function getFields($locale = null)
    {
        $parent_fields = parent::getFields($locale);

        $l10n = CRM_Remoteevent_Localisation::getLocalisation($locale);
        $fields = [
            'billing_address' => [
                'type'        => 'fieldset',
                'name'        => 'billing_address',
                'label'       => $l10n->localise("Billing Address"),
                'weight'      => 30,
                'description' => '',
            ],
            'billing_organisation' => [
                'name'        => 'billing_organisation',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 128,
                'weight'      => 5,
                'required'    => 0,
                'label'       => $l10n->localise('Organisation Rechnungsadresse'),
                'description' => $l10n->localise("Bitte angeben, falls die Rechnung an eine Organisation gestellt werden soll."),
                'parent'      => 'billing_address',
            ],
            'billing_street_address'         => [
                'name'        => 'billing_street_address',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 96,
                'weight'      => 10,
                'required'    => 0,
                'label'       => $l10n->localise('Street Address'),
                'description' => $l10n->localise("Participant's street and house number"),
                'parent'      => 'billing_address',
            ],
            'billing_supplemental_address_1' => [
                'name'        => 'billing_supplemental_address_1',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 96,
                'weight'      => 20,
                'required'    => 0,
                'label'       => $l10n->localise('Supplemental Address'),
                'parent'      => 'billing_address',
            ],
            'billing_supplemental_address_2' => [
                'name'        => 'billing_supplemental_address_2',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 96,
                'weight'      => 30,
                'required'    => 0,
                'label'       => $l10n->localise('Supplemental Address 2'),
                'parent'      => 'billing_address',
            ],
            'billing_postal_code'            => [
                'name'        => 'billing_postal_code',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 64,
                'weight'      => 40,
                'required'    => 0,
                'label'       => $l10n->localise('Postal Code'),
                'parent'      => 'billing_address',
            ],
            'billing_city'                   => [
                'name'        => 'billing_city',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 64,
                'weight'      => 50,
                'required'    => 0,
                'label'       => $l10n->localise('City'),
                'parent'      => 'billing_address',
            ],
            'billing_country_id'             => [
                'name'        => 'billing_country_id',
                'type'        => 'Select',
                'options'     => $this->getCountries($locale),
                'validation'  => '',
                'weight'      => 60,
                'required'    => 0,
                'label'       => $l10n->localise('Country'),
                'parent'      => 'billing_address',
                'dependencies'=> [
                    [
                        'dependent_field'       => 'billing_state_province_id',
                        'hide_unrestricted'     => 1,
                        'hide_restricted_empty' => 1,
                        'command'               => 'restrict',
                        'regex_subject'         => 'dependent',
                        'regex'                 => '^({current_value}-[0-9]+)$',
                    ],
                ],
            ],
            'billing_state_province_id'    => [
                'name'        => 'billing_state_province_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 70,
                'required'    => 0,
                'options'     => $this->getStateProvinces($locale),
                'label'       => $l10n->localise('State or Province'),
                'parent'      => 'billing_address'
            ],
        ];

        return $parent_fields + $fields;
    }

    /**
     * This function will tell you which entity/entities the given field
     *   will relate to. It would mostly be Contact or Participant (or both)
     *
     * @param string $field_key
     *   the field key as used by this profile
     *
     * @return array
     *   list of entities
     */
    public function getFieldEntities($field_key)
    {
        if (substr($field_key, 0, 8)  == 'billing_') {
            return ['Participant'];
        } else {
            return parent::getFieldEntities($field_key);
        }
    }

    /**
     * Allows us to tweak the data for the participant just before it's being created
     *
     * @param $registration RegistrationEvent
     *   registration event
     */
    public static function mapParticipantData($registration)
    {
        if (!$registration->hasErrors()) {
            $profile = CRM_Remoteevent_RegistrationProfile::getProfile($registration);
            if (   $profile instanceof CRM_Remoteevent_RegistrationProfile_IntesRegular ) {
                // we have a 'Intes regulär' profile
                /** @var CRM_Remoteevent_RegistrationProfile_IntesRegular $profile */

                // apply custom fields to participant data
                $participant_data = &$registration->getParticipantData();
                $mapping = $profile->getParticipantDataMapping();
                foreach ($mapping as $registration_field => $custom_field) {
                    $submitted_value = $registration->getSubmittedValue($registration_field);
                    if (isset($submitted_value)) {
                        $participant_data[$custom_field] = $submitted_value;
                    }
                }

                // WORKAROUND for https://github.com/systopia/de.systopia.remoteevent/issues/17
                foreach ($participant_data as $field => &$value) {
                    if (!empty($value) && preg_match('/state_province_id/', $field)) {
                        $country_and_state = explode('-', $value);
                        $value = end($country_and_state);
                    }
                }

                // SUPPORT FOR additional participant roles
                if (!empty($participant_data['additional_role'])) {
                    // make sure there's a default (that should already be the case)
                    if (!isset($participant_data['role_id'])) {
                        $participant_data['role_id'] = 1; // Attendee
                    }

                    // make sure the roles are an array
                    if (!is_array($participant_data['role_id'])) {
                        $participant_data['role_id'] = [$participant_data['role_id']];
                    }

                    // add additional role, if requested
                    if (!in_array($participant_data['additional_role'], $participant_data['role_id'])) {
                        $participant_data['role_id'][] = $participant_data['additional_role'];
                    }
                }
            }
        }
    }


    /**
     * Get a mapping from participant registration fields to the participant custom field
     *
     * @return array mapping
     */
    public function getParticipantDataMapping()
    {
        // use the base profile's mapping and add our two fields
        return [
            'billing_street_address'          => 'participant_billing.event_participant_billing_street_address',
            'billing_supplemental_address_1'  => 'participant_billing.event_participant_billing_supplemental_address_1',
            'billing_supplemental_address_2'  => 'participant_billing.event_participant_billing_supplemental_address_2',
            'billing_postal_code'             => 'participant_billing.event_participant_billing_postal_code',
            'billing_city'                    => 'participant_billing.event_participant_billing_city',
            'billing_country_id'              => 'participant_billing.event_participant_billing_country',
            'billing_state_province_id'       => 'participant_billing.event_participant_billing_state_province_id',
            'billing_organisation'            => 'participant_billing.event_participant_billing_organisation_name',
        ];
    }



    /**
     * Add the extra role to participants
     *
     * @param RegistrationEvent $registration
     *   registration event
     */
    public static function markContributor($registration)
    {
        // of there is already an issue, don't waste any more time on this
        if ($registration->hasErrors()) {
            return;
        }

        // check if the contributor flag is set
        $is_contributor = $registration->getSubmittedValue('is_contributor');
        if ($is_contributor) {
            // first: get the contributor role
            static $contributor_role_id = null;
            if ($contributor_role_id === null) {
                try {
                    $contributor_role_id = civicrm_api3('OptionValue', 'getvalue', [
                        'return' => 'value',
                        'option_group_id' => 'participant_role',
                        'name' => 'contributor'
                    ]);
                } catch (CiviCRM_API3_Exception $exception) {
                    Civi::log()->error("EKIR-Events: Couldn't mark participant as contributor, role not found. Error was: " . $exception->getMessage());
                    return;
                }
            }

            // now: add the contributor role, if it's not there
            $participant_data = &$registration->getParticipantData();
            $participant_roles = $participant_data['role_id'];
            if (!is_array($participant_roles)) {
                $participant_roles = explode(',', $participant_roles);
            }
            if (!in_array($contributor_role_id, $participant_roles)) {
                $participant_roles[] = $contributor_role_id;
                // the following will edit the participant data of the registration process itself
                $participant_data['role_id'] = $participant_roles;
            }
        }
    }
}
