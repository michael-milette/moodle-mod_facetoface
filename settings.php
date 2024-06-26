<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Copyright (C) 2007-2011 Catalyst IT (http://www.catalyst.net.nz)
 * Copyright (C) 2011-2013 Totara LMS (http://www.totaralms.com)
 * Copyright (C) 2014 onwards Catalyst IT (http://www.catalyst-eu.net)
 *
 * @package    mod
 * @subpackage facetoface
 * @copyright  2014 onwards Catalyst IT <http://www.catalyst-eu.net>
 * @author     Stacey Walker <stacey@catalyst-eu.net>
 * @author     Alastair Munro <alastair.munro@totaralms.com>
 * @author     Aaron Barnes <aaron.barnes@totaralms.com>
 * @author     Francois Marier <francois@catalyst.net.nz>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/facetoface/lib.php');
require_once($CFG->dirroot . '/user/profile/lib.php');

$settings->add(new admin_setting_configtext(
    'facetoface/fromaddress',
    get_string('setting:fromaddress_caption', 'facetoface'),
    get_string('setting:fromaddress', 'facetoface'),
    get_string('setting:fromaddressdefault', 'facetoface'),
    PARAM_EMAIL,
    30
));

// Load roles.
$choices = [];
if ($roles = role_fix_names(get_all_roles(), context_system::instance())) {
    foreach ($roles as $role) {
        $choices[$role->id] = format_string($role->localname);
    }
}

$settings->add(new admin_setting_configmultiselect(
    'facetoface/session_roles',
    get_string('setting:sessionroles_caption', 'facetoface'),
    get_string('setting:sessionroles', 'facetoface'),
    [],
    $choices
));

$settings->add(new admin_setting_configcheckbox(
    'facetoface/limit_candidates',
    get_string('setting:limit_candidates_caption', 'facetoface'),
    get_string('setting:limit_candidates', 'facetoface'),
    0
));

$settings->add(new admin_setting_configcheckbox(
    'facetoface/enableapprovals',
    get_string('setting:enableapprovals_caption', 'facetoface'),
    get_string('setting:enableapprovals', 'facetoface'),
    1
));

$settings->add(new admin_setting_heading(
    'facetoface/manageremail_header',
    get_string('manageremailheading', 'facetoface'),
    get_string('manageremaildisabled', 'facetoface')
));

$settings->add(new admin_setting_configcheckbox(
    'facetoface/addchangemanageremail',
    get_string('setting:addchangemanageremail_caption', 'facetoface'),
    get_string('setting:addchangemanageremail', 'facetoface'),
    0
));
$settings->hide_if('facetoface/addchangemanageremail', 'facetoface/enableapprovals');

$settings->add(new admin_setting_configtext(
    'facetoface/manageraddressformat',
    get_string('setting:manageraddressformat_caption', 'facetoface'),
    get_string('setting:manageraddressformat', 'facetoface'),
    get_string('setting:manageraddressformatdefault', 'facetoface'),
    PARAM_TEXT
));
$settings->hide_if('facetoface/manageraddressformat', 'facetoface/enableapprovals');

$settings->add(new admin_setting_configtext(
    'facetoface/manageraddressformatreadable',
    get_string('setting:manageraddressformatreadable_caption', 'facetoface'),
    get_string('setting:manageraddressformatreadable', 'facetoface'),
    get_string('setting:manageraddressformatreadabledefault', 'facetoface'),
    PARAM_NOTAGS
));
$settings->hide_if('facetoface/manageraddressformatreadable', 'facetoface/enableapprovals');

$settings->add(new admin_setting_heading('facetoface/cost_header', get_string('costheading', 'facetoface'), ''));

$settings->add(new admin_setting_configcheckbox(
    'facetoface/hidecost',
    get_string('setting:hidecost_caption', 'facetoface'),
    get_string('setting:hidecost', 'facetoface'),
    0
));

$settings->add(new admin_setting_configcheckbox(
    'facetoface/hidediscount',
    get_string('setting:hidediscount_caption', 'facetoface'),
    get_string('setting:hidediscount', 'facetoface'),
    0
));

$settings->add(new admin_setting_heading('facetoface/icalendar_header', get_string('icalendarheading', 'facetoface'), ''));

$settings->add(new admin_setting_configcheckbox(
    'facetoface/oneemailperday',
    get_string('setting:oneemailperday_caption', 'facetoface'),
    get_string('setting:oneemailperday', 'facetoface'),
    0
));

$settings->add(new admin_setting_configcheckbox(
    'facetoface/disableicalcancel',
    get_string('setting:disableicalcancel_caption', 'facetoface'),
    get_string('setting:disableicalcancel', 'facetoface'),
    0
));

// List of user profile fields to optionally be included in attendees export.

$settings->add(new admin_setting_heading(
    'facetoface_attendeesexporttofile_header',
    get_string('attendeesexporttofileheading', 'facetoface'),
    ''
));

// Load profile fields.
// Basic fields.
$choices = [
    'middlename'  => new lang_string('middlename'),
    'alternatename' => new lang_string('alternatename'),
    'username'    => new lang_string('username'),
    'idnumber'    => new lang_string('idnumber'),
    'email'       => new lang_string('email'),
    'phone1'      => new lang_string('phone1'),
    'phone2'      => new lang_string('phone2'),
    'department'  => new lang_string('department'),
    'institution' => new lang_string('institution'),
    'city'        => new lang_string('city'),
    'country'     => new lang_string('country'),
    'lang'        => new lang_string('language'),
    'firstnamephonetic' => new lang_string('firstnamephonetic'),
    'lastnamephonetic'  => new lang_string('lastnamephonetic'),
];

// Custom profile fields.
$profilefields = profile_get_custom_fields();
foreach ($profilefields as $field) {
    $choices['profile_field_' . $field->shortname] = format_string($field->name)
        . ' (' . get_string('customfield', 'customfield'). ')';
}

$settings->add(new admin_setting_configmultiselect(
    'facetoface_attendeesexportfields',
    new lang_string('setting:attendeesexportfields_caption', 'facetoface'),
    new lang_string('setting:attendeesexportfields', 'facetoface'),
    [],
    $choices
));

// List of existing custom fields.
$html  = facetoface_list_of_customfields();
$html .= html_writer::start_tag('p');
$url   = new moodle_url('/mod/facetoface/customfield.php', ['id' => 0]);
$html .= html_writer::link($url, get_string('addnewfieldlink', 'facetoface'));
$html .= html_writer::end_tag('p');

$settings->add(new admin_setting_heading('facetoface/customfields_header', get_string('customfieldsheading', 'facetoface'), $html));

// List of existing site notices.
$html  = facetoface_list_of_sitenotices();
$html .= html_writer::start_tag('p');
$url  = new moodle_url('/mod/facetoface/sitenotice.php', ['id' => 0]);
$html .= html_writer::link($url, get_string('addnewnoticelink', 'facetoface'));
$html .= html_writer::end_tag('p');

$settings->add(new admin_setting_heading('facetoface/sitenotices_header', get_string('sitenoticesheading', 'facetoface'), $html));
