<?php

// com awardpackage helpers
defined('_JEXEC') or die;

class AwardpackagesHelper {

    // create sub menu function
    public function addSubmenu($a, $vName) {
    	JSubMenuHelper::addEntry(
           JText::_('Home'), 'index.php?option=com_awardpackage',
           $vName == 'donation'
        );
		/*
		JSubMenuHelper::addEntry(
			JText::_('Currencies'),'index.php?option=com_awardpackage&view=currencies&package_id=' . JRequest::getVar('package_id'),
			$vName	== 'currencies'
		);*/
		
		//JSubMenuHelper::addEntry(
		//	JText::_('Payment Options'),'index.php?option=com_awardpackage&view=payments&task=payments.get_payment_list&package_id=' . JRequest::getVar('package_id'),
		//	$vName	== 'currencies'
		//);
		
    }

    // create sub menu function
    public function addSubmenuAward($vName) {

        JSubMenuHelper::addEntry(
            JText::_('Home'), 'index.php?option=com_awardpackage'
        );
		if($vName!='award'){
			JSubMenuHelper::addEntry(
				JText::_('COM_SYMBOL_AWARD_SYMBOL_LIST'), 'index.php?option=com_awardpackage&view=awardsymbol&package_id=' . JRequest::getVar('package_id')
			);
		}
		
		if($vName!='prize'){
			JSubMenuHelper::addEntry(
				JText::_('COM_SYMBOL_PRIZE_LIST'), 'index.php?option=com_awardpackage&view=prize&package_id=' . JRequest::getVar('package_id')
			);
		}
		
		if($vName!='presentation'){
			JSubMenuHelper::addEntry(JText::_('Presentation List'), 'index.php?option=com_awardpackage&view=anewpresentationlist&task=anewpresentationlist.initiate&package_id='. JRequest::getVar('package_id')
			);
		}
    }

    // create sub menu function
    public function addSubmenuGiftcode($a, $vName) {

        JSubMenuHelper::addEntry(
                JText::_('Home'), 'index.php?option=com_awardpackage',
                //$submenu == 'donation',
                $vName == 'home'
        );

        JSubMenuHelper::addEntry(
                JText::_('Giftcode Collection List'), 'index.php?option=com_awardpackage&view=giftcodecode&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName == 'giftcodecollection'
        );

        /*JSubMenuHelper::addEntry(
                JText::_('Giftcode Queue List'), 'index.php?option=com_awardpackage&view=queue&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName == 'queue'
        );*/
		
        /*
        JSubMenuHelper::addEntry(
                JText::_('Giftcode Category'), 'index.php?option=com_awardpackage&view=giftcode&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName == 'donation'
        );
        */
    }

    // create sub menu funding function
    public function addSubmenuFunding($vName) {

        JSubMenuHelper::addEntry(
                JText::_('Home'), 'index.php?option=com_awardpackage',
                //$submenu == 'donation',
                $vName = 'home'
        );

        JSubMenuHelper::addEntry(
                JText::_('Fund Prize List'), 'index.php?option=com_awardpackage&view=funding&presentation_id='.JRequest::getVar('presentation_id').'&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'donations'
        );

        JSubMenuHelper::addEntry(
                JText::_('Funding Records'), 'index.php?option=com_awardpackage&view=prizefundingrecord&presentation_id='.JRequest::getVar('presentation_id').'&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'prizefundingrecord'
        );
    }

    public function pollMenu($vName) {
        JSubMenuHelper::addEntry(
                JText::_('Home'), 'index.php?option=com_awardpackage',
                //$submenu == 'donation',
                $vName = 'home'
        );
        
        /*
        JSubMenuHelper::addEntry(
                JText::_('Categories'), 'index.php?option=com_awardpackage&view=categories&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        */

        /*
        JSubMenuHelper::addEntry(
                JText::_('Polls'), 'index.php?option=com_awardpackage&view=boxen&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        */

        /*
        JSubMenuHelper::addEntry(
                JText::_('Answers'), 'index.php?option=com_awardpackage&view=answers&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        */

        /*
        JSubMenuHelper::addEntry(
                JText::_('Comments'), 'index.php?option=com_awardpackage&view=comments&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        */

        /*
        JSubMenuHelper::addEntry(
                JText::_('BBcodes'), 'index.php?option=com_awardpackage&view=bbcodes&controller=bbcodes&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        */
        
        /*
        JSubMenuHelper::addEntry(
                JText::_('Ads'), 'index.php?option=com_awardpackage&view=adadmin&layout=listcontents&package_id=' . JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        */
    }

    public function ads_menu() {
        JSubMenuHelper::addEntry(
                JText::_('Home'), 'index.php?option=com_awardpackage',
                //$submenu == 'donation',
                $vName = 'home'
        );
        /*
        JSubMenuHelper::addEntry(
                JText::_('COM_ADSMANAGER_CONFIGURATION'), 'index.php?option=com_awardpackage&view=adadmin&layout=configuration&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_ADSMANAGER_FIELDS'), 'index.php?option=com_awardpackage&view=adadmin&layout=listfields&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_ADSMANAGER_COLUMNS'), 'index.php?option=com_awardpackage&view=adadmin&layout=columns&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        
        JSubMenuHelper::addEntry(
                JText::_('Categories'), 'index.php?option=com_awardpackage&view=adadmin&layout=listcategories&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_ADSMANAGER_CONTENTS'), 'index.php?option=com_awardpackage&view=adadmin&layout=listcontents&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_ADSMANAGER_PLUGINS'), 'index.php?option=com_awardpackage&view=adadmin&layout=listplugins&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        JSubMenuHelper::addEntry(
                JText::_('COM_ADSMANAGER_FIELD_IMAGES'), 'index.php?option=com_awardpackage&view=adadmin&layout=listfieldimages&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        JSubMenuHelper::addEntry(
                JText::_('Polls'), 'index.php?option=com_awardpackage&view=boxen&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName = 'home'
        );
        */
    }
    
    public function deposit_menu($a, $vName) {
        JSubMenuHelper::addEntry(
                JText::_('Home'), 'index.php?option=com_awardpackage',
                //$submenu == 'donation',
                $vName == 'home'
        );
        JSubMenuHelper::addEntry(
                JText::_('Transaction history'), 'index.php?option=com_awardpackage&view=deposit&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName == 'transaction_history'
        );
        JSubMenuHelper::addEntry(
                JText::_('Withdraw List'), 'index.php?option=com_awardpackage&view=deposit&layout=withdraw&package_id='.JRequest::getVar('package_id'),
                //$submenu == 'donation',
                $vName == 'withdraw_list'
        );
    }

    public function Countries_list() {
        $country_list = array(
            "Afghanistan",
            "Albania",
            "Algeria",
            "Andorra",
            "Angola",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Brazil",
            "Brunei",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cape Verde",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Colombi",
            "Comoros",
            "Congo (Brazzaville)",
            "Congo",
            "Costa Rica",
            "Cote d'Ivoire",
            "Croatia",
            "Cuba",
            "Cyprus",
            "Czech Republic",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "East Timor (Timor Timur)",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Ethiopia",
            "Fiji",
            "Finland",
            "France",
            "Gabon",
            "Gambia, The",
            "Georgia",
            "Germany",
            "Ghana",
            "Greece",
            "Grenada",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Honduras",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran",
            "Iraq",
            "Ireland",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea, North",
            "Korea, South",
            "Kuwait",
            "Kyrgyzstan",
            "Laos",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macedonia",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Mauritania",
            "Mauritius",
            "Mexico",
            "Micronesia",
            "Moldova",
            "Monaco",
            "Mongolia",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepa",
            "Netherlands",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Poland",
            "Portugal",
            "Qatar",
            "Romania",
            "Russia",
            "Rwanda",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Vincent",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia and Montenegro",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Swaziland",
            "Sweden",
            "Switzerland",
            "Syria",
            "Taiwan",
            "Tajikistan",
            "Tanzania",
            "Thailand",
            "Togo",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Vatican City",
            "Venezuela",
            "Vietnam",
            "Yemen",
            "Zambia",
            "Zimbabwe"
        );
        return $country_list;
    }

}

?>