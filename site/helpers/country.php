<?php

// No direct access to this file
defined('_JEXEC') or die;

/**
 * Country component helper.
 */
abstract class CountryHelper {

    /**
     * Get List Country.
     */
    public function getCountries() {
        $result = array();
        
        array_push($result, 
                array (
                    id => 'Afghanistan',
                    name => 'Afghanistan'
                ),
                array (
                    id => 'Aland Islands',
                    name => 'Aland Islands'
                ),
                array (
                    id => 'Albania',
                    name => 'Albania'
                ),
                array (
                    id => 'Algeria',
                    name => 'Algeria'
                ),
                array (
                    id => 'American Samoa',
                    name => 'American Samoa'
                ),
                array (
                    id => 'Andorra',
                    name => 'Andorra'
                ),
                array (
                    id => 'Angola',
                    name => 'Angola'
                ),
                array (
                    id => 'Anguilla',
                    name => 'Anguilla'
                ),
                array (
                    id => 'Antarctica',
                    name => 'Antarctica'
                ),
                array (
                    id => 'Antigua and Barbuda',
                    name => 'Antigua and Barbuda'
                ),
                array (
                    id => 'Argentina',
                    name => 'Argentina'
                ),
                array (
                    id => 'Armenia',
                    name => 'Armenia'
                ),
                array (
                    id => 'Aruba',
                    name => 'Aruba'
                ),
                array (
                    id => 'Australia',
                    name => 'Australia'
                ),
                array (
                    id => 'Austria',
                    name => 'Austria'
                ),
                array (
                    id => 'Azerbaijan',
                    name => 'Azerbaijan'
                ),
                array (
                    id => 'Bahamas',
                    name => 'Bahamas'
                ),
                array (
                    id => 'Bahrain',
                    name => 'Bahrain'
                ),
                array (
                    id => 'Bangladesh',
                    name => 'Bangladesh'
                ),
                array (
                    id => 'Barbados',
                    name => 'Barbados'
                ),
                array (
                    id => 'Belarus',
                    name => 'Belarus'
                ),
                array (
                    id => 'Belgium',
                    name => 'Belgium'
                ),
                array (
                    id => 'Belize',
                    name => 'Belize'
                ),
                array (
                    id => 'Benin',
                    name => 'Benin'
                ),
                array (
                    id => 'Bermuda',
                    name => 'Bermuda'
                ),
                array (
                    id => 'Bhutan',
                    name => 'Bhutan'
                ),
                array (
                    id => 'Bolivia',
                    name => 'Bolivia'
                ),
                array (
                    id => 'Bosnia and Herzegovina',
                    name => 'Bosnia and Herzegovina'
                ),
                array (
                    id => 'Botswana',
                    name => 'Botswana'
                ),
                array (
                    id => 'Bouvet Island',
                    name => 'Bouvet Island'
                ),
                array (
                    id => 'Brazil',
                    name => 'Brazil'
                ),
                array (
                    id => 'British Indian Ocean Territory',
                    name => 'British Indian Ocean Territory'
                ),
                array (
                    id => 'Brunei Darussalam',
                    name => 'Brunei Darussalam'
                ),
                array (
                    id => 'Bulgaria',
                    name => 'Bulgaria'
                ),
                array (
                    id => 'Burkina Faso',
                    name => 'Burkina Faso'
                ),
                array (
                    id => 'Burundi',
                    name => 'Burundi'
                ),
                array (
                    id => 'Cambodia',
                    name => 'Cambodia'
                ),
                array (
                    id => 'Cameroon',
                    name => 'Cameroon'
                ),
                array (
                    id => 'Canada',
                    name => 'Canada'
                ),
                array (
                    id => 'Cape Verde',
                    name => 'Cape Verde'
                ),
                array (
                    id => 'Cayman Islands',
                    name => 'Cayman Islands'
                ),
                array (
                    id => 'Central African Republic',
                    name => 'Central African Republic'
                ),
                array (
                    id => 'Chad',
                    name => 'Chad'
                ),
                array (
                    id => 'Chile',
                    name => 'Chile'
                ),
                array (
                    id => 'China',
                    name => 'China'
                ),
                array (
                    id => 'Christmas Island',
                    name => 'Christmas Island'
                ),
                array (
                    id => 'Cocos (Keeling) Islands',
                    name => 'Cocos (Keeling) Islands'
                ),
                array (
                    id => 'Colombia',
                    name => 'Colombia'
                ),
                array (
                    id => 'Comoros',
                    name => 'Comoros'
                ),
                array (
                    id => 'Congo',
                    name => 'Congo'
                ),
                array (
                    id => 'Congo, The Democratic Republic of The',
                    name => 'Congo, The Democratic Republic of The'
                ),
                array (
                    id => 'Cook Islands',
                    name => 'Cook Islands'
                ),
                array (
                    id => 'Costa Rica',
                    name => 'Costa Rica'
                ),
                array (
                    id => 'Cote D`ivoire',
                    name => 'Cote D`ivoire'
                ),
                array (
                    id => 'Croatia',
                    name => 'Croatia'
                ),
                array (
                    id => 'Cuba',
                    name => 'Cuba'
                ),
                array (
                    id => 'Cyprus',
                    name => 'Cyprus'
                ),
                array (
                    id => 'Czech Republic',
                    name => 'Czech Republic'
                ),
                array (
                    id => 'Denmark',
                    name => 'Denmark'
                ),
                array (
                    id => 'Djibouti',
                    name => 'Djibouti'
                ),
                array (
                    id => 'Dominica',
                    name => 'Dominica'
                ),
                array (
                    id => 'Dominican Republic',
                    name => 'Dominican Republic'
                ),
                array (
                    id => 'Ecuador',
                    name => 'Ecuador'
                ),
                array (
                    id => 'Egypt',
                    name => 'Egypt'
                ),
                array (
                    id => 'El Salvador',
                    name => 'El Salvador'
                ),
                array (
                    id => 'Equatorial Guinea',
                    name => 'Equatorial Guinea'
                ),
                array (
                    id => 'Eritrea',
                    name => 'Eritrea'
                ),
                array (
                    id => 'Estonia',
                    name => 'Estonia'
                ),
                array (
                    id => 'Ethiopia',
                    name => 'Ethiopia'
                ),
                array (
                    id => 'Falkland Islands (Malvinas)',
                    name => 'Falkland Islands (Malvinas)'
                ),
                array (
                    id => 'Faroe Islands',
                    name => 'Faroe Islands'
                ),
                array (
                    id => 'Fiji',
                    name => 'Fiji'
                ),
                array (
                    id => 'Finland',
                    name => 'Finland'
                ),
                array (
                    id => 'France',
                    name => 'France'
                ),
                array (
                    id => 'French Guiana',
                    name => 'French Guiana'
                ),
                array (
                    id => 'French Polynesia',
                    name => 'French Polynesia'
                ),
                array (
                    id => 'French Southern Territories',
                    name => 'French Southern Territories'
                ),
                array (
                    id => 'Gabon',
                    name => 'Gabon'
                ),
                array (
                    id => 'Gambia',
                    name => 'Gambia'
                ),
                array (
                    id => 'Georgia',
                    name => 'Georgia'
                ),
                array (
                    id => 'Germany',
                    name => 'Germany'
                ),
                array (
                    id => 'Ghana',
                    name => 'Ghana'
                ),
                array (
                    id => 'Gibraltar',
                    name => 'Gibraltar'
                ),
                array (
                    id => 'Greece',
                    name => 'Greece'
                ),
                array (
                    id => 'Greenland',
                    name => 'Greenland'
                ),
                array (
                    id => 'Grenada',
                    name => 'Grenada'
                ),
                array (
                    id => 'Guadeloupe',
                    name => 'Guadeloupe'
                ),
                array (
                    id => 'Guam',
                    name => 'Guam'
                ),
                array (
                    id => 'Guatemala',
                    name => 'Guatemala'
                ),
                array (
                    id => 'Guernsey',
                    name => 'Guernsey'
                ),
                array (
                    id => 'Guinea',
                    name => 'Guinea'
                ),
                array (
                    id => 'Guinea-bissau',
                    name => 'Guinea-bissau'
                ),
                array (
                    id => 'Guyana',
                    name => 'Guyana'
                ),
                array (
                    id => 'Haiti',
                    name => 'Haiti'
                ),
                array (
                    id => 'Heard Island and Mcdonald Islands',
                    name => 'Heard Island and Mcdonald Islands'
                ),
                array (
                    id => 'Holy See (Vatican City State)',
                    name => 'Holy See (Vatican City State)'
                ),
                array (
                    id => 'Honduras',
                    name => 'Honduras'
                ),
                array (
                    id => 'Hong Kong',
                    name => 'Hong Kong'
                ),
                array (
                    id => 'Hungary',
                    name => 'Hungary'
                ),
                array (
                    id => 'Iceland',
                    name => 'Iceland'
                ),
                array (
                    id => 'India',
                    name => 'India'
                ),
                array (
                    id => 'Indonesia',
                    name => 'Indonesia'
                ),
                array (
                    id => 'Iran, Islamic Republic of',
                    name => 'Iran, Islamic Republic of'
                ),
                array (
                    id => 'Iraq',
                    name => 'Iraq'
                ),
                array (
                    id => 'Ireland',
                    name => 'Ireland'
                ),
                array (
                    id => 'Isle of Man',
                    name => 'Isle of Man'
                ),
                array (
                    id => 'Israel',
                    name => 'Israel'
                ),
                array (
                    id => 'Italy',
                    name => 'Italy'
                ),
                array (
                    id => 'Jamaica',
                    name => 'Jamaica'
                ),
                array (
                    id => 'Japan',
                    name => 'Japan'
                ),
                array (
                    id => 'Jersey',
                    name => 'Jersey'
                ),
                array (
                    id => 'Jordan',
                    name => 'Jordan'
                ),
                array (
                    id => 'Kazakhstan',
                    name => 'Kazakhstan'
                ),
                array (
                    id => 'Kenya',
                    name => 'Kenya'
                ),
                array (
                    id => 'Kiribati',
                    name => 'Kiribati'
                ),
                array (
                    id => 'Korea, Democratic People`s Republic of',
                    name => 'Korea, Democratic People`s Republic of'
                ),
                array (
                    id => 'Korea, Republic of',
                    name => 'Korea, Republic of'
                ),
                array (
                    id => 'Kuwait',
                    name => 'Kuwait'
                ),
                array (
                    id => 'Kyrgyzstan',
                    name => 'Kyrgyzstan'
                ),
                array (
                    id => 'Lao People`s Democratic Republic',
                    name => 'Lao People`s Democratic Republic'
                ),
                array (
                    id => 'Latvia',
                    name => 'Latvia'
                ),
                array (
                    id => 'Lebanon',
                    name => 'Lebanon'
                ),
                array (
                    id => 'Lesotho',
                    name => 'Lesotho'
                ),
                array (
                    id => 'Liberia',
                    name => 'Liberia'
                ),
                array (
                    id => 'Libyan Arab Jamahiriya',
                    name => 'Libyan Arab Jamahiriya'
                ),
                array (
                    id => 'Liechtenstein',
                    name => 'Liechtenstein'
                ),
                array (
                    id => 'Lithuania',
                    name => 'Lithuania'
                ),
                array (
                    id => 'Luxembourg',
                    name => 'Luxembourg'
                ),
                array (
                    id => 'Macao',
                    name => 'Macao'
                ),
                 array (
                    id => 'Madagascar',
                    name => 'Madagascar'
                ),
                array (
                    id => 'Malawi',
                    name => 'Malawi'
                ),
                array (
                    id => 'Malaysia',
                    name => 'Malaysia'
                ),
                array (
                    id => 'Maldives',
                    name => 'Maldives'
                ),
                array (
                    id => 'Mali',
                    name => 'Mali'
                ),
                array (
                    id => 'Malta',
                    name => 'Malta'
                ),
                array (
                    id => 'Marshall Islands',
                    name => 'Marshall Islands'
                ),
                array (
                    id => 'Martinique',
                    name => 'Martinique'
                ),
                array (
                    id => 'Mauritania',
                    name => 'Mauritania'
                ),
                array (
                    id => 'Mauritius',
                    name => 'Mauritius'
                ),
                array (
                    id => 'Mayotte',
                    name => 'Mayotte'
                ),
                array (
                    id => 'Mexico',
                    name => 'Mexico'
                ),
                array (
                    id => 'Micronesia, Federated States of',
                    name => 'Micronesia, Federated States of'
                ),
                array (
                    id => 'Moldova, Republic of',
                    name => 'Moldova, Republic of'
                ),
                array (
                    id => 'Monaco',
                    name => 'Monaco'
                ),
                array (
                    id => 'Mongolia',
                    name => 'Mongolia'
                ),
                array (
                    id => 'Montenegro',
                    name => 'Montenegro'
                ),
                array (
                    id => 'Montserrat',
                    name => 'Montserrat'
                ),
                array (
                    id => 'Morocco',
                    name => 'Morocco'
                ),
                array (
                    id => 'Mozambique',
                    name => 'Mozambique'
                ),
                array (
                    id => 'Myanmar',
                    name => 'Myanmar'
                ),
                array (
                    id => 'Namibia',
                    name => 'Namibia'
                ),
                array (
                    id => 'Nauru',
                    name => 'Nauru'
                ),
                array (
                    id => 'Nepal',
                    name => 'Nepal'
                ),
                array (
                    id => 'Netherlands',
                    name => 'Netherlands'
                ),
                array (
                    id => 'Netherlands Antilles',
                    name => 'Netherlands Antilles'
                ),
                array (
                    id => 'New Caledonia',
                    name => 'New Caledonia'
                ),
                array (
                    id => 'New Zealand',
                    name => 'New Zealand'
                ),
                array (
                    id => 'Nicaragua',
                    name => 'Nicaragua'
                ),
                array (
                    id => 'Niger',
                    name => 'Niger'
                ),
                array (
                    id => 'Nigeria',
                    name => 'Nigeria'
                ),
                array (
                    id => 'Niue',
                    name => 'Niue'
                ),
                array (
                    id => 'Norfolk Island',
                    name => 'Norfolk Island'
                ),
                array (
                    id => 'Northern Mariana Islands',
                    name => 'Northern Mariana Islands'
                ),
                array (
                    id => 'Norway',
                    name => 'Norway'
                ),
                array (
                    id => 'Oman',
                    name => 'Oman'
                ),
                array (
                    id => 'Pakistan',
                    name => 'Pakistan'
                ),
                array (
                    id => 'Palau',
                    name => 'Palau'
                ),
                array (
                    id => 'Palestinian Territory, Occupied',
                    name => 'Palestinian Territory, Occupied'
                ),
                array (
                    id => 'Panama',
                    name => 'Panama'
                ),
                array (
                    id => 'Papua New Guinea',
                    name => 'Papua New Guinea'
                ),
                array (
                    id => 'Paraguay',
                    name => 'Paraguay'
                ),
                array (
                    id => 'Peru',
                    name => 'Peru'
                ),
                array (
                    id => 'Philippines',
                    name => 'Philippines'
                ),
                array (
                    id => 'Pitcairn',
                    name => 'Pitcairn'
                ),
                array (
                    id => 'Poland',
                    name => 'Poland'
                ),
                array (
                    id => 'Portugal',
                    name => 'Portugal'
                ),
                array (
                    id => 'Puerto Rico',
                    name => 'Puerto Rico'
                ),
                array (
                    id => 'Qatar',
                    name => 'Qatar'
                ),
                array (
                    id => 'Reunion',
                    name => 'Reunion'
                ),
                array (
                    id => 'Romania',
                    name => 'Romania'
                ),
                array (
                    id => 'Russian Federation',
                    name => 'Russian Federation'
                ),
                array (
                    id => 'Rwanda',
                    name => 'Rwanda'
                ),
                array (
                    id => 'Saint Helena',
                    name => 'Saint Helena'
                ),
                array (
                    id => 'Saint Kitts and Nevis',
                    name => 'Saint Kitts and Nevis'
                ),
                array (
                    id => 'Saint Lucia',
                    name => 'Saint Lucia'
                ),
                array (
                    id => 'Saint Vincent and The Grenadines',
                    name => 'Saint Vincent and The Grenadines'
                ),
                array (
                    id => 'Samoa',
                    name => 'Samoa'
                ),
                array (
                    id => 'San Marino',
                    name => 'San Marino'
                ),
                array (
                    id => 'Sao Tome and Principe',
                    name => 'Sao Tome and Principe'
                ),
                array (
                    id => 'Saudi Arabia',
                    name => 'Saudi Arabia'
                ),
                array (
                    id => 'Senegal',
                    name => 'Senegal'
                ),
                array (
                    id => 'Serbia',
                    name => 'Serbia'
                ),
                array (
                    id => 'Seychelles',
                    name => 'Seychelles'
                ),
                array (
                    id => 'Sierra Leone',
                    name => 'Sierra Leone'
                ),
                array (
                    id => 'Singapore',
                    name => 'Singapore'
                ),
                array (
                    id => 'Slovakia',
                    name => 'Slovakia'
                ),
                array (
                    id => 'Slovenia',
                    name => 'Slovenia'
                ),
                array (
                    id => 'Solomon Islands',
                    name => 'Solomon Islands'
                ),
                array (
                    id => 'Somalia',
                    name => 'Somalia'
                ),
                array (
                    id => 'South Africa',
                    name => 'South Africa'
                ),
                
                array (
                    id => 'Spain',
                    name => 'Spain'
                ),
                array (
                    id => 'Sri Lanka',
                    name => 'Sri Lanka'
                ),
                array (
                    id => 'Sudan',
                    name => 'Sudan'
                ),
                array (
                    id => 'Suriname',
                    name => 'Suriname'
                ),
                array (
                    id => 'Svalbard and Jan Mayen',
                    name => 'Svalbard and Jan Mayen'
                ),
                array (
                    id => 'Swaziland',
                    name => 'Swaziland'
                ),
                array (
                    id => 'Sweden',
                    name => 'Sweden'
                ),
                array (
                    id => 'Switzerland',
                    name => 'Switzerland'
                ),
                array (
                    id => 'Syrian Arab Republic',
                    name => 'Syrian Arab Republic'
                ),
                array (
                    id => 'Taiwan, Province of China',
                    name => 'Taiwan, Province of China'
                ),
                array (
                    id => 'Tajikistan',
                    name => 'Tajikistan'
                ),
                array (
                    id => 'Tanzania, United Republic of',
                    name => 'Tanzania, United Republic of'
                ),
                array (
                    id => 'Thailand',
                    name => 'Thailand'
                ),
                array (
                    id => 'Timor-leste',
                    name => 'Timor-leste'
                ),
                array (
                    id => 'Togo',
                    name => 'Togo'
                ),
                array (
                    id => 'Tokelau',
                    name => 'Tokelau'
                ),
                array (
                    id => 'Trinidad and Tobago',
                    name => 'Trinidad and Tobago'
                ),
                array (
                    id => 'Tunisia',
                    name => 'Tunisia'
                ),
                array (
                    id => 'Turkey',
                    name => 'Turkey'
                ),
                array (
                    id => 'Turkmenistan',
                    name => 'Turkmenistan'
                ),
                array (
                    id => 'Turks and Caicos Islands',
                    name => 'Turks and Caicos Islands'
                ),
                array (
                    id => 'Tuvalu',
                    name => 'Tuvalu'
                ),
                array (
                    id => 'Uganda',
                    name => 'Uganda'
                ),
                array (
                    id => 'Ukraine',
                    name => 'Ukraine'
                ),
                array (
                    id => 'United Arab Emirates',
                    name => 'United Arab Emirates'
                ),
                array (
                    id => 'United Kingdom',
                    name => 'United Kingdom'
                ),
                array (
                    id => 'United States',
                    name => 'United States'
                ),
                array (
                    id => 'United States Minor Outlying Islands',
                    name => 'United States Minor Outlying Islands'
                ),
                array (
                    id => 'Uruguay',
                    name => 'Uruguay'
                ),
                array (
                    id => 'Uzbekistan',
                    name => 'Uzbekistan'
                ),
                array (
                    id => 'Vanuatu',
                    name => 'Vanuatu'
                ),
                array (
                    id => 'Venezuela',
                    name => 'Venezuela'
                ),
                array (
                    id => 'Viet Nam',
                    name => 'Viet Nam'
                ),
                array (
                    id => 'Virgin Islands, British',
                    name => 'Virgin Islands, British'
                ),
                array (
                    id => 'Virgin Islands, U.S.',
                    name => 'Virgin Islands, U.S.'
                ),
                array (
                    id => 'Wallis and Futuna',
                    name => 'Wallis and Futuna'
                ),
                array (
                    id => 'Western Sahara',
                    name => 'Western Sahara'
                ),
                array (
                    id => 'Yemen',
                    name => 'Yemen'
                ),
                array (
                    id => 'Zambia',
                    name => 'Zambia'
                ),
                array (
                    id => 'Zimbabwe',
                    name => 'Zimbabwe'
                )
                );
						
        return $result;
    }

    

}
