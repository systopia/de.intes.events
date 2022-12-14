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
        return 'IntesRegular';
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
            'company'         => [
                'name'        => 'company',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 96,
                'weight'      => 60,
                'required'    => 1,
                'label'       => $l10n->localise('Company'),
                'parent'      => 'contact_base',
            ],
            'job_title'         => [
                'name'        => 'job_title',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 96,
                'weight'      => 70,
                'required'    => 1,
                'label'       => $l10n->localise('Job Title'),
                'parent'      => 'contact_base',
            ],

            'email' => [
                'name'        => 'email',
                'type'        => 'Text',
                'validation'  => 'Email',
                'weight'      => 80,
                'required'    => 1,
                'label'       => $l10n->localise('Email'),
                'description' => '', // $l10n->localise("Participant's email address"),
                'parent'      => 'contact_address',
            ],
            'email_confirmation' => [
                'name'        => 'email_confirmation',
                'type'        => 'Text',
                'validation'  => 'Email',
                'weight'      => 90,
                'required'    => 1,
                'label'       => $l10n->localise('Email bestätigen'),
                'description' => '', // $l10n->localise("Participant's email address"),
                'parent'      => 'contact_address',
            ],
            'phone'                  => [
                'name'        => 'phone',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 32,
                'weight'      => 100,
                'required'    => 0,
                'label'       => $l10n->localise('Phone Number'),
                'description' => $l10n->localise("Please include country code"),
                'parent'      => 'contact_address',
            ],

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
            'billing_comment'                   => [
                'name'        => 'billing_comment',
                'type'        => 'Textarea',
                'validation'  => '',
                'weight'      => 60,
                'required'    => 0,
                'label'       => $l10n->localise('Bemerkungen'),
                'parent'      => 'billing_address',
            ],
            'other' => [
                'type'        => 'fieldset',
                'name'        => 'other',
                'label'       => $l10n->localise("Weiteres"),
                'weight'      => 70,
                'description' => '',
            ],
            'event_recommendation'                   => [
                'name'        => 'event_recommendation',
                'type'        => 'Select',
                'validation'  => '',
                'maxlength'   => 64,
                'weight'      => 70,
                'required'    => 0,
                'options'     => $this->getEventRecommendationOptions(),
                'label'       => $l10n->localise('Wie sind Sie auf die INTES Akademie aufmerksam geworden?'),
                'parent'      => 'other',

            ],
            'event_recommendation_other'                   => [
                'name'        => 'event_recommendation_other',
                'type'        => 'Text',
                'validation'  => '',
                'maxlength'   => 64,
                'weight'      => 80,
                'required'    => 0,
                'label'       => $l10n->localise('Sonstiges'),
                'parent'      => 'other',
            ],
            'event_newsletter'                   => [
                'name'        => 'event_newsletter',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 90,
                'required'    => 0,
                'label'       => $l10n->localise('Ja, ich möchte INTES Up-to-date erhalten.'),
                'description' => $l10n->localise("Damit wir Sie einladen und informieren dürfen möchten wir Sie bitten, ihre Anmeldung zu INTES Up-to-date zu bestätigen. Selbstverständlich können Sie zu diesen jederzeit über einen entsprechenden Link am Ende jedes Newsletters abbestellen und ihre Einwilligung jederzeit ohne Angabe von Gründen widerrufen."),
                'parent'      => 'other',
            ],
            'event_agb'                   => [
                'name'        => 'event_agb',
                'type'        => 'Checkbox',
                'validation'  => '',
                'weight'      => 90,
                'required'    => 1,
                'label'       => $l10n->localise('Anmeldebedingungen für unsere Seminare und Veranstaltungen'),
                'description' => $l10n->localise("Mit Ihrer Anmeldung bestätigen Sie, dass Sie unsere AGB zur Kenntnis genommen haben. Hinsichtlich der Verarbeitung Ihrer personenbezogenen Daten verweisen wir auf unsere Datenschutzerklärung.
Nach Ihrer Anmeldung erhalten Sie eine schriftliche Bestätigung und eine Anfahrtsbeschreibung. Unsere Rechnung erhalten Sie mit der Bestätigung. Bei Absagen später als vier Wochen vor Veranstaltungsbeginn werden 50 Prozent des Preises berechnet. Bei Absagen später als zwei Wochen vor Veranstaltungsbeginn wird der volle Preis berechnet. Sie können jedoch gerne Ersatzteilnehmer benennen. Für Webinare gilt eine verkürzte Stornofrist. Webinare können bis zu 48 h vor Veranstaltungstermin kostenfrei storniert werden. Bitte beachten Sie: Bei Webinaren gibt es keinen Begleitpersonentarif. "),
                'parent'      => 'other',
            ],
        ];

        # unset Phone, supplementary address
        unset($parent_fields['phone']);
        unset($parent_fields['supplemental_address_1']);
        unset($parent_fields['supplemental_address_2']);

        # require Plz, Str, City, Country
        $parent_fields['postal_code']['required'] = 1;
        $parent_fields['street_address']['required'] = 1;
        $parent_fields['city']['required'] = 1;
        $parent_fields['country_id']['required'] = 1;

        # move Email to Address Block
        unset($parent_fields['email']);

        # remove Bundesland
        unset($parent_fields['state_province_id']);
        unset($parent_fields['country_id']['dependencies']);


        #
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

                // SUPPORT for Newsletter Subscribe feature
                if (!empty($participant_data['event_newsletter'])){
                    $profile->createMailingEventSubscribe($registration->getSubmittedValue('email'), 'INTES-Up-to-date');
                }
            }
        }
    }

    /**
     * Subscribe Contact to Newsletter Group
     *
     * @return void
     */
    public function createMailingEventSubscribe($email, $group_name){

        // get id of the name to subscribe for
        try{
            $group = civicrm_api3('Group', 'getSingle', [
                'return' => 'id',
                'name' => $group_name,
            ]);
        } catch (CiviCRM_API3_Exception $exception) {
            Civi::log()->error("Remote-Events: Couldn't get Group for createMailingEventSubscribe. Error was: " . $exception->getMessage());
            return;
        }

        // if group id exists
        if (empty($group) or empty($group['id'])) {
            Civi::log()->error("Remote-Events: No Group returned for createMailingEventSubscribe. Maybe no Group exists with name: " . $group_name);
            return;
        }else{
            try{
                // subscribe contact to group
                $subscription = civicrm_api3(
                    'MailingEventSubscribe',
                    'create',
                    [
                        'email' => $email,
                        'group_id' => $group['id']
                    ]
                );
                #Civi::log()->debug('Successfully subscribed for Mailing Group ID:'.$group['id']);

            } catch (CiviCRM_API3_Exception $exception) {
                Civi::log()->error("Remote-Events: Couldn't Subscribe contact to Group. Error was: " . $exception->getMessage());
                return;
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
            'billing_postal_code'             => 'participant_billing.event_participant_billing_postal_code',
            'billing_city'                    => 'participant_billing.event_participant_billing_city',
            'billing_organisation'            => 'participant_billing.event_participant_billing_organisation_name',
            'billing_comment' => 'participant_billing.event_participant_billing_comment',
            'event_recommendation' => 'participant_details.event_recommendation',
            'event_recommendation_other' => 'participant_details.event_recommendation_other',
            'company' => 'participant_details.event_company',
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
                    Civi::log()->error("Remote-Events: Couldn't mark participant as contributor, role not found. Error was: " . $exception->getMessage());
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

    /**
     * Get a list of all Event Recommendation Options
     *
     * @return array
     */
    public function getEventRecommendationOptions()
    {
        $options = [];

        // load the two roles expected here
        $query = civicrm_api3('OptionValue', 'get', [
            'option_group_id' => "remote_registration_recommendation",
            'return' => ["value", "label", "name"],
        ]);

        foreach ($query['values'] as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }
}
