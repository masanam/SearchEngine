<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class SearchEngineModelMain extends JModelLegacy {

    public function save($data) { 
        $fields = $this->show_fields('search_engine');
        foreach ($data as $k => $v) {
            if (in_array($k, $fields)) {
                $input[$k] = htmlentities($v);
            }
        }
        $db = JFactory::getDBO();
        if ($input['package_id'] > 0) {
            $query = "UPDATE `#__search_engine` SET package_name = '$input[package_name]', start_date = '$input[start_date]',
            end_date = '$input[end_date]', published = '$input[published]', awardpackageid = '$input[awardpackageid]' WHERE package_id = '$input[package_id]'";
            $db->setQuery($query);
            $db->query();
        } else {
            unset($input['package_id']);
            $query = "INSERT INTO `#__search_engine` (" . implode(',', array_keys($input)) . ") VALUES ('" . implode('\',\'', array_values($input)) . "')";
            $db->setQuery($query);
            $db->query();
            $package_id = $db->insertid();
            //$this->create_setting($db->insertid());
            //$this->creategiftcodecat($package_id);
            //$this->createDefaultQuizSurveyCategoires($package_id);
            //$this->createDefaultTicktes($package_id);
        }
    }
    public function createDefaultQuizSurveyCategoires($package_id){
        if($package_id){
            $categories = array(
                'books-authors'=>'Books & Authors',
                'websites-blogs' =>'Websites & Blogs',
                'brands-products'=>'Brands & Products',
                'realestate-housing'=>'Realestate & Housing',
                'cars-transports'=>'Cars & Transports',
                'travel-accomodation' =>'Travel & Accomodation',
                'food-drinks'=>'Food & Drinks',
                'clubs-charities'=>'Clubs & Charities',
                'entertainment-fashion' =>'Entertainment & Fashion',
                'services-professionals' =>'Services & Professionals',
                'politics-government'=>'Politics & Government',
                'technology-science'=>'Technology & Science',
                'hobbies-interests'=>'Hobbies & Interests',
                'events-events-planning'=>'Events & Events planning',
                'health-medical'=>'Health & Medical',
                'other-stuff'=>'Other stuff'
                );
            $values = array();
            $nleft = 2;            
            foreach ($categories as $key => $cat) {
                $nright = $nleft+1;
                $values[] = "('$cat','$key',1,'$nleft','$nright','0','0','0','$package_id')";
                $nleft = $nright+1;
            }

            $query = "INSERT INTO `#__quiz_categories` (title,alias,parent_id,nleft,nright,nlevel,norder,quizzes,package_id) VALUES " .implode(',',$values);
            //echo str_replace('#__', 'raj_', $query );exit;
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $db->query();

            $query = "INSERT INTO `#__survey_categories` (title,alias,parent_id,nleft,nright,nlevel,norder,surveys,package_id) VALUES " .implode(',',$values);
            //echo str_replace('#__', 'raj_', $query );exit;
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $db->query();
        }
    }

     public function createDefaultTicktes($package_id){
         if($package_id){
            $tickets = array(
                array('Super member 1',5000000,50000,10,5000,45000),
                array('Super member 2',10000000,100000,15,15000,85000),
                array('Super member 3',15000000,150000,20,30000,120000),
                array('Super member 4',20000000,200000,25,50000,150000),
                array('Super member 5',100000000,10000000,40,400000,600000)
            );

            foreach ($tickets as $key => $ticket) {                
                $values[] = "('".$ticket[0]."','".$ticket[1]."','','0','$package_id','".date('Y-m-d h:i:s')."','0','".$ticket[2]."','".$ticket[3]."','1','".$ticket[4]."','".$ticket[5]."')";                
            }

            $query = "INSERT INTO `#__membertickets` (title,points,description,status,package_id,created_at,quantity,price,discount,type,discount_amount,discount_ticket_price) VALUES " .implode(',',$values);
           
            $db = JFactory::getDBO();
            $db->setQuery($query);
            $db->query();
        }
    }

    public function doclone($package_id) {

        $package = $this->info($package_id);

        unset($package->package_id);

        $package->package_name = $package->package_name . ' (clone)';

        $package->created = date("Y-m-d H:i:s");

        foreach ($package as $k => $v) {

            $key[] = $k;

            $value[] = $v;
        }
        $db = JFactory::getDBO();

        $query = "INSERT INTO `#__search_engine` (" . implode(",", $key) . ") VALUES ('" . implode('\',\'', $value) . "')";
        $db->setQuery($query);

        $db->query();

        $insertid = $db->insertid();
        $idpackage = $insertid;
        if ($insertid > 0) {
            $query = "SELECT * FROM `#__ap_categories` WHERE package_id = '$package_id'";
            $db->setQuery($query);
            $rs = $db->loadObjectList();
            if (count($rs) > 0) {
                foreach ($rs as $k => $v) {
                    $q = "INSERT INTO `#__ap_categories` (package_id,category_id,colour_code,category_name,donation_amount,poll_price,survey_price,quiz_price,user_survey_price,published)
                        VALUES ('$insertid','$v->category_id','$v->colour_code','$v->category_name','$v->donation_amount','$v->poll_price','$v->survey_price','$v->quiz_price','$v->user_survey_price','$v->published')";
                    $db->setQuery($q);
                    if ($db->query()) {
                        $return = 1;
                    }
                }
            }
        }
        if ($return) {

            //create duplicate symbol prize data
            $this->clonePrize($package_id, $idpackage);

            //create duplicate giftcode category
            $this->cloneGiftcode($package_id, $idpackage);
            
            //create duplicate giftcode collection
            //$this->cloneGiftcodeCollection($package_id, $idpackage);

            //create duplicate giftcode giftcode
            //$this->cloneGiftcodeGiftcode($package_id, $insertid);

            //create duplicate giftcode rules
            $this->cloneGiftcodeRules($package_id, $idpackage);

            //create duplicate symbol presentation
            $this->cloneSymbolPresentation($package_id, $idpackage);

            //create duplicate symbol symbol
            $this->cloneSymbolSymbol($package_id, $idpackage);

            //create duplicate funding
            $this->cloneFunding($package_id, $idpackage);
            
            $this->survey_cat_mapper = array();
            //create duplicate survey Category
            $this->cloneSurveyCategory($package_id, $idpackage);

            //create duplicate survey
            $this->cloneSurvey($package_id, $idpackage);

            $this->quiz_cat_mapper = array();
            //create duplicate quiz Category
            $this->cloneQuizCategory($package_id, $idpackage);

            //create duplicate quiz
            $this->cloneQuiz($package_id, $idpackage);

            //create duplicate ContributionRange
            $this->cloneContributionRange($package_id, $idpackage);
            
            //create duplicate ProgressCheck
            $this->cloneProgressCheck($package_id, $idpackage);

            //create duplicate ShoppingCreditCategory
            $this->cloneShoppingCreditCategory($package_id, $idpackage);
            
            //create duplicate ShoppingCreditFromDonation
            $this->cloneShoppingCreditFromDonation($package_id, $idpackage);

            //create duplicate ShoppingCreditPlan
            $this->cloneShoppingCreditPlan($package_id, $idpackage);
            
            //create duplicate ShoppingCreditPlanDetail
            $this->cloneShoppingCreditPlanDetail($package_id, $idpackage);

            //create duplicate SymbolQueueGroup
            $this->cloneSymbolQueueGroup($package_id, $idpackage);
            
            //create duplicate FundReceiverList
            $this->cloneFundReceiverList($package_id, $idpackage);          

            //create duplicate FundReceiver
           // $this->cloneFundReceiver($package_id, $idpackage);            
            
            //create duplicate FundReceiverAge
            $this->cloneFundReceiverAge($package_id, $idpackage);           

            //create duplicate FundReceiverGender
            $this->cloneFundReceiverGender($package_id, $idpackage);            

            //create duplicate FundReceiverLocation
            $this->cloneFundReceiverLocation($package_id, $idpackage);          
            
            //create duplicate FundPrizePlan
            $this->cloneFundPrizePlan($package_id, $idpackage);         

            //create duplicate AwardFundPlan
            $this->cloneAwardFundPlan($package_id, $idpackage); 

            //create duplicate ProcessPresentation
            $this->cloneProcessPresentation($package_id, $idpackage);   

            //create duplicate ProcessPresentationList
            $this->cloneProcessPresentationList($package_id, $idpackage);   
            
            //create duplicate UsergroupPresentation
            $this->cloneUsergroupPresentation($package_id, $idpackage); 

            //create duplicate ap_symbol_process
            $this->cloneApSymbolProcess($package_id, $idpackage);   

            return true;
        }
    }

    private function cloneGiftcodeRules($package_id, $insertid){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__ap_free_giftcode_rules')->where('package_id='.$db->quote($package_id));
        $db->setQuery($query);
        $rules = $db->loadObjectList();

        if(count($rules)){
            foreach($rules as $r){
                $query = $db->getQuery(true);
                $columns = array('package_id', 'title', 'created', 'modified');
                $values = array($db->quote($insertid),$db->quote($r->title),$db->quote($r->created),$db->quote($r->modified));
                $query->insert($db->quoteName('#__ap_free_giftcode_rules'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
                $rule_id = $db->insertid();

                $query = $db->getQuery(true);
                $query->select('*')->from('#__ap_free_giftcode_rules_settings')->where('rule_id='.$db->quote($r->id));
                $db->setQuery($query);
                $settings = $db->loadObjectList();

                if(count($settings)){
                    foreach($settings as $s){
                        $query = $db->getQuery(true);
                        $columns = array('rule_id', 'data_type', 'data_value', 'giftcodes');
                        $values = array($db->quote($rule_id),$db->quote($s->data_type),$db->quote($s->data_value),$db->quote($s->giftcodes));
                        $query->insert($db->quoteName('#__ap_free_giftcode_rules_settings'));
                        $query->columns($db->quoteName($columns));
                        $query->values(implode(',', $values));
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
            }
        }

        
    }

            //create duplicate ap_symbol_process
    private function cloneApSymbolProcess($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__ap_symbol_process WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__ap_symbol_process (prize_value_from, prize_value_to, clone_from, clone_to, extra_from, extra_to, shuffle_from, shuffle_to, presentation_id, selected_presentation, package_id) VALUES ('".$row->prize_value_from . "','" . $row->prize_value_to . "','" . $row->clone_from . "','" . $row->clone_to . "','" . $row->extra_from . "','" . $row->extra_to . "','" . $row->shuffle_from . "','" . $row->shuffle_to . "','" . $row->presentation_id . "','" . $row->selected_presentation . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }


//            $this->cloneProcessPresentation($package_id, $idpackage); 
    private function cloneProcessPresentation($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__process_presentation WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__process_presentation (selected_presentation, name, presentation, prize_name, prize_value, fund_prize_plan, funding_value_from, funding_value_to, award_fund_plan, award_fund_rate, award_fund_amount, fund_receiver, limit_receiver, symbol_queue, symbol_assign, fund_plan, fund_amount, fund_prize, date_created, package_id) VALUES ('" . $row->selected_presentation. "', '" . $row->name. "', '" . $row->presentation. "', '" . $row->prize_name. "', '" . $row->prize_value. "', '" . $row->fund_prize_plan. "', '" . $row->funding_value_from. "', '" . $row->funding_value_to. "', '" . $row->award_fund_plan. "', '" . $row->award_fund_rate. "', '" . $row->award_fund_amount. "', '" . $row->fund_receiver. "', '" . $row->limit_receiver. "', '" . $row->symbol_queue. "', '" . $row->symbol_assign. "', '" . $row->fund_plan. "', '" . $row->fund_amount. "', '" . $row->fund_prize. "', '" . $row->date_created. "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

//            $this->cloneUsergroupPresentation($package_id, $idpackage);   
    private function cloneUsergroupPresentation($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__usergroup_presentation WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__usergroup_presentation (usergroup, usergroup_id, presentation_id, process_presentation, name, funds, prize, prize_value, symbol,  date_created, package_id) VALUES ('" . $row->usergroup . "','" .
                        $row->usergroup_id . "','" . $row->presentation_id . "','" . $row->process_presentation . "','" . $row->name . "','" . $row->funds . "','" . $row->prize . "','" . $row->prize_value . "','" . $row->symbol . "','" . $row->date_created . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }



//            $this->cloneProcessPresentationList($package_id, $idpackage); 
    private function cloneProcessPresentationList($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__process_presentation_list WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__process_presentation_list (selected_presentation, name, presentation, prize_name, prize_value, fund_amount, fund_prize, date_created, package_id) VALUES ('" . $row->selected_presentation . "','" .
                        $row->name . "','" . $row->presentation . "','" . $row->prize_name . "','" . $row->prize_value . "','" . $row->fund_amount . "','" . $row->fund_prize . "','" . $row->date_created . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }


//            $this->cloneAwardFundPlan($package_id, $idpackage);   
    private function cloneAwardFundPlan($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__award_fund_plan WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__award_fund_plan (name, usergroup, rate, speed, duration, amount, total, spent, remain, date_created, package_id) VALUES ('" .
                        $row->name . "','" . $row->usergroup . "','" . $row->rate . "','" . $row->speed . "','" . $row->duration . "','" . $row->amount . "','" . $row->total . "','" . $row->spent . "','" . $row->remain . "','" . $row->date_created . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }


//            $this->cloneFundPrizePlan($package_id, $idpackage);           
    private function cloneFundPrizePlan($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__fund_prize_plan WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__fund_prize_plan (name, value_from, value_to, date_created, package_id) VALUES ('" .
                        $row->name . "','" . $row->value_from . "','" . $row->value_to . "','" . $row->date_created . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }


    //cloneSymbolQueueGroup($package_id, $idpackage);
    private function cloneSymbolQueueGroup($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__symbol_queue_group WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__symbol_queue_group (name, selected, amount, total, date_created, package_id) VALUES ('" .
                        $row->name . "','" . $row->selected . "','" . $row->amount . "','" . $row->total . "','" . $row->date_created . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

//            $this->cloneFundReceiverList($package_id, $idpackage);            
    private function cloneFundReceiverList($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__fund_receiver_list WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__fund_receiver_list (title, filter, created_time, package_id) VALUES ('" .
                        $row->title . "','" . $row->filter . "','" . $row->created_time . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

//$this->cloneFundReceiverAge($package_id, $idpackage); 
  private function cloneFundReceiverAge($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__fund_receiver_age WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__fund_receiver_age (title, receiver, from_day, to_day, from_month, to_month, from_year, to_year, filter, created_time, package_id) VALUES ('" .
                        $row->title . "','" . $row->receiver . "','" . $row->from_day . "','" . $row->to_day . "','" . $row->from_month . "','" . $row->to_month . "','" . $row->from_year . "','" . $row->to_year . "','" . $row->filter . "','" . $row->created_time . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }
//$this->cloneFundReceiverGender($package_id, $idpackage);  
  private function cloneFundReceiverGender($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__fund_receiver_gender WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__fund_receiver_gender (title, receiver, gender, filter, created_time, package_id) VALUES ('" .
                        $row->title . "','" . $row->receiver . "','" . $row->gender . "','" . $row->filter . "','" . $row->created_time . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

//$this->cloneFundReceiverLocation($package_id, $idpackage);    
  private function cloneFundReceiverLocation($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__fund_receiver_location WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__fund_receiver_location (title, receiver, gender, filter, created_time, package_id) VALUES ('" .
                        $row->title . "','" . $row->receiver . "','" . $row->gender . "','" . $row->filter . "','" . $row->created_time . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneContributionRange($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__contribution_range WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__contribution_range (shopping_credit_plan_id, min_amount, max_amount, uniq_key, package_id) VALUES ('" .
                        $row->shopping_credit_plan_id . "','" . $row->min_amount . "','" . $row->max_amount . "','" . $row->uniq_key . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneProgressCheck($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__progress_check WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__progress_check (shopping_credit_plan_id, every, type, uniq_key, package_id) VALUES ('" .
                        $row->shopping_credit_plan_id . "','" . $row->every . "','" . $row->type . "','" . $row->uniq_key . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneShoppingCreditCategory($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__shopping_credit_category WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__shopping_credit_category (name, published, package_id) VALUES ('" .
                        $row->name . "','" . $row->published . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneShoppingCreditFromDonation($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__shopping_credit_from_donation WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__shopping_credit_from_donation (shopping_credit_plan_id, fee, refund, expire, uniq_key, contribution_range, progress_check, package_id) VALUES ('" .
                        $row->shopping_credit_plan_id . "','" . $row->fee . "','" . $row->refund . "','" . $row->expire . "','" . $row->uniq_key . "','" . $row->contribution_range . "','" . $row->progress_check . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneShoppingCreditPlan($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__shopping_credit_plan WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__shopping_credit_plan (category, sc_plan, published, note, package_id) VALUES ('" .
                        $row->category . "','" . $row->sc_plan . "','" . $row->published . "','" . $row->note . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneShoppingCreditPlanDetail($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__shopping_credit_plan_detail WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__shopping_credit_plan_detail (start_date, end_date, contribution_range, progress_check, uniq_key, package_id) VALUES ('" .
                        $row->start_date . "','" . $row->end_date . "','" . $row->contribution_range . "','" . $row->progress_check . "','" . $row->uniq_key . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneFunding($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__funding WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO #__funding (funding_session,funding_desc,funding_published,funding_created,funding_modify,funding_1st,package_id) VALUES ('" .
                        $row->funding_session . "','" . $row->funding_desc . "','" . $row->funding_published . "','" . $row->funding_created . "','" . $row->funding_modify . "','" .
                        $row->funding_1st . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

     private function cloneQuizCategory($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__quiz_categories WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();


        if (count($rows) > 0) {


            foreach ($rows as $row) {

                $Query = "INSERT INTO #__quiz_categories (title, alias, package_id) VALUES ('" .
                        $row->title . "','" . $row->alias . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();

                $this->quiz_cat_mapper[$row->id] = $db->insertid();
            }
        }
    }


     private function cloneQuiz($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__quiz_quizzes WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        $quiz_ids = array();

        $master_quiz_ids = array();

        if (count($rows) > 0) {


            foreach ($rows as $row) {

                $query = $db->getQuery(true);
                $columns = array('title', 'alias', 'created_by', 'created', 'catid', 'show_answers','description', 'show_template','published', 'duration', 'multiple_responses', 'cutoff',  'package_id');
                $values = array($db->quote($row->title),$db->quote($row->alias),$db->quote($row->created_by),$db->quote($row->created),$db->quote($this->quiz_cat_mapper[$row->catid]),$db->quote($row->show_answers),$db->quote($row->description),$db->quote($row->show_template),$db->quote($row->published),$db->quote($row->duration),$db->quote($row->multiple_responses),$db->quote($row->cutoff),$db->quote($insertid));
                $query->insert($db->quoteName('#__quiz_quizzes'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
                $quiz_id = $db->insertid();

                //create quiz page
                $uniq_id = md5(uniqid(rand(), true));
                $query = $db->getQuery(true);
                $columns = array('quiz_id','sort_order', 'title', 'uniq_key');
                $values = array($db->quote($quiz_id), '1', 'NULL', $db->quote($uniq_id));
                $query->insert($db->quoteName('#__quiz_pages'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
                $page_id = $db->insertid();

                //get previous questions
                $query = $db->getQuery(true);
                $query->select('*')->from('#__quiz_questions')->where('quiz_id='.$db->quote($row->id));
                $db->setQuery($query);
                $quiz_questions = $db->loadObjectList();

                if(count($quiz_questions)){
                    foreach ($quiz_questions as $q) {

                        $query = $db->getQuery(true);
                        $columns = array('quiz_id','question_type', 'page_number', 'responses','sort_order','mandatory','created_by','include_custom','title','description','answer_explanation','orientation','uniq_key');
                        $values = array($db->quote($quiz_id), $db->quote($q->question_type), $db->quote($page_id), '0',$db->quote($q->sort_order),$db->quote($q->mandatory),$db->quote($q->created_by),$db->quote($q->include_custom),$db->quote($q->title),$db->quote($q->description),$db->quote($q->answer_explanation),$db->quote($q->orientation),$db->quote($uniq_id) );
                        $query->insert($db->quoteName('#__quiz_questions'));
                        $query->columns($db->quoteName($columns));
                        $query->values(implode(',', $values));
                        $db->setQuery($query);
                        $db->execute();
                        $ques_id = $db->insertid();

                        //get previous answers
                        $query = $db->getQuery(true);
                        $query->select('*')->from('#__quiz_answers')->where('question_id='.$db->quote($q->id));
                        $db->setQuery($query);
                        $quiz_answers = $db->loadObjectList();

                        if(count($quiz_answers)){
                            foreach ($quiz_answers as $a) {
                                $query = $db->getQuery(true);
                                $columns = array('title','quiz_id', 'question_id', 'answer_type','responses','correct_answer','sort_order','image','marks');
                                $values = array($db->quote($a->title), $db->quote($quiz_id), $db->quote($ques_id),$db->quote($a->answer_type),$db->quote($a->responses),$db->quote($a->correct_answer),$db->quote($a->sort_order),$db->quote($a->image),$db->quote($a->marks));
                                $query->insert($db->quoteName('#__quiz_answers'));
                                $query->columns($db->quoteName($columns));
                                $query->values(implode(',', $values));
                                $db->setQuery($query);
                                $db->execute();
                            }

                        }

                    }
                }

                //get previous question_giftcode
                $query = $db->getQuery(true);
                $query->select('*')->from('#__quiz_question_giftcode');
                //$query->where('question_id='.$db->quote($q->id));
                $query->where('quiz_id='.$db->quote($row->id));
                $db->setQuery($query);

                $quiz_giftcodes = $db->loadObjectList();

                if(count($quiz_giftcodes)){
                    foreach ($quiz_giftcodes as $gc) {
                        $query = $db->getQuery(true);
                        $columns = array('package_id','quiz_id', 'page_number','question_id','uniq_key','complete_giftcode','complete_giftcode_quantity','complete_giftcode_cost_response','incomplete_giftcode','incomplete_giftcode_quantity','incomplete_giftcode_cost_response');
                        $values = array($db->quote($insertid), $db->quote($quiz_id), $db->quote($page_id),$db->quote($ques_id),$db->quote($uniq_id),$db->quote($gc->complete_giftcode),$db->quote($gc->complete_giftcode_quantity),$db->quote($gc->complete_giftcode_cost_response),$db->quote($gc->incomplete_giftcode),$db->quote($gc->incomplete_giftcode_quantity),$db->quote($gc->incomplete_giftcode_cost_response));
                        $query->insert($db->quoteName('#__quiz_question_giftcode'));
                        $query->columns($db->quoteName($columns));
                        $query->values(implode(',', $values));
                        $db->setQuery($query);
                        $db->execute();
                        //$ques_id = $db->insertid();
                    }

                }

            }
        }
        
        
    }

     private function cloneSurveyCategory($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__survey_categories WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();


        if (count($rows) > 0) {


            foreach ($rows as $row) {

                $query = $db->getQuery(true);
                $columns = array('title','alias', 'package_id');
                $values = array($db->quote($row->title), $db->quote($row->alias), $db->quote($insertid));
                $query->insert($db->quoteName('#__survey_categories'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();

                $this->quiz_cat_mapper[$row->id] = $db->insertid();
            }
        }
    }
    
 private function cloneSurvey($package_id, $insertid) {

        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__survey WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $ques_mapper = array();
        if (count($rows) > 0) {

            foreach ($rows as $row) {
                $uniq_id = md5(uniqid(rand(), true));
                $query = $db->getQuery(true);
                $columns = array('title', 'alias', 'catid', 'introtext', 'endtext', 'created_by','publish_up', 'publish_down','responses', 'private_survey', 'max_responses', 'anonymous', 'custom_header',  'public_permissions','published','survey_key', 'redirect_url','display_template','skip_intro','ip_address','backward_navigation','display_notice','display_progress','notification','restriction','package_id');
                $values = array($db->quote($row->title),$db->quote($row->alias),$db->quote($this->survey_cat_mapper[$row->catid]),$db->quote($row->introtext),$db->quote($row->endtext),$db->quote($row->created_by),$db->quote($row->publish_up),$db->quote($row->publish_down),$db->quote($row->responses),$db->quote($row->private_survey),$db->quote($row->max_responses),$db->quote($row->anonymous),$db->quote($row->custom_header),$db->quote($row->public_permissions),$db->quote($row->published),$db->quote($uniq_id),$db->quote($row->redirect_url),$db->quote($row->display_template),$db->quote($row->skip_intro),$db->quote($row->ip_address),$db->quote($row->backward_navigation),$db->quote($row->display_notice),$db->quote($row->display_progress),$db->quote($row->notification),$db->quote($row->restriction),$db->quote($insertid));
                $query->insert($db->quoteName('#__survey'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
                $survey_id = $db->insertid();

                //create quiz page
                $query = $db->getQuery(true);
                $columns = array('sid','sort_order', 'title', 'uniq_key');
                $values = array($db->quote($survey_id), '1', 'NULL', $db->quote($uniq_id));
                $query->insert($db->quoteName('#__survey_pages'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
                $page_id = $db->insertid();

                //get previous questions
                $query = $db->getQuery(true);
                $query->select('*')->from('#__survey_questions')->where('survey_id='.$db->quote($row->id));
                $db->setQuery($query);
                $survey_questions = $db->loadObjectList();

                if(count($survey_questions)){

                    foreach ($survey_questions as $q) {

                        $query = $db->getQuery(true);
                        $columns = array('title','description','survey_id','question_type', 'page_number', 'responses','sort_order','mandatory','created_by','custom_choice','orientation','min_selections','max_selections','uniq_key');
                        $values = array($db->quote($q->title),$db->quote($q->description),$db->quote($survey_id), $db->quote($q->question_type), $db->quote($page_id), $db->quote($q->responses) ,$db->quote($q->sort_order),$db->quote($q->mandatory),$db->quote($q->created_by),$db->quote($q->custom_choice),$db->quote($q->orientation),$db->quote($q->min_selections),$db->quote($q->max_selections),$db->quote($uniq_id) );
                        $query->insert($db->quoteName('#__survey_questions'));
                        $query->columns($db->quoteName($columns));
                        $query->values(implode(',', $values));
                        $db->setQuery($query);
                        $db->execute();
                        $ques_id = $db->insertid();
                        $ques_mapper[$q->id] = $ques_id;

                        //get previous answers
                        $query = $db->getQuery(true);
                        $query->select('*')->from('#__survey_answers')->where('question_id='.$db->quote($q->id));
                        $db->setQuery($query);
                        $survey_answers = $db->loadObjectList();

                        if(count($survey_answers)){
                            foreach ($survey_answers as $a) {
                                $query = $db->getQuery(true);
                                $columns = array('survey_id', 'question_id', 'answer_type','answer_label','sort_order','image');
                                $values = array($db->quote($survey_id), $db->quote($ques_id),$db->quote($a->answer_type),$db->quote($a->answer_label),$db->quote($a->sort_order),$db->quote($a->image));
                                $query->insert($db->quoteName('#__survey_answers'));
                                $query->columns($db->quoteName($columns));
                                $query->values(implode(',', $values));
                                $db->setQuery($query);
                                $db->execute();
                            }

                        }

                    }
                }


                //get previous question_giftcode
                $query = $db->getQuery(true);
                $query->select('*')->from('#__survey_question_giftcode');
                //$query->where('question_id='.$db->quote($q->id));
                $query->where('survey_id='.$db->quote($row->id));
                $db->setQuery($query);

                $survey_giftcodes = $db->loadObjectList();

                if(count($survey_giftcodes)){
                    foreach ($survey_giftcodes as $gc) {
                        $ques_id = $ques_mapper[$gc->question_id];
                        $query = $db->getQuery(true);
                        $columns = array('package_id','survey_id', 'page_number','question_id','uniq_key','complete_giftcode','complete_giftcode_quantity','complete_giftcode_cost_response','incomplete_giftcode','incomplete_giftcode_quantity','incomplete_giftcode_cost_response');
                        $values = array($db->quote($insertid), $db->quote($survey_id), $db->quote($page_id),$db->quote($ques_id),$db->quote($uniq_id),$db->quote($gc->complete_giftcode),$db->quote($gc->complete_giftcode_quantity),$db->quote($gc->complete_giftcode_cost_response),$db->quote($gc->incomplete_giftcode),$db->quote($gc->incomplete_giftcode_quantity),$db->quote($gc->incomplete_giftcode_cost_response));
                        $query->insert($db->quoteName('#__survey_question_giftcode'));
                        $query->columns($db->quoteName($columns));
                        $query->values(implode(',', $values));
                        $db->setQuery($query);
                        $db->execute();
                        //$ques_id = $db->insertid();
                    }

                }
            }

            
        }
    }

    private function cloneSymbolSymbol($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__symbol_symbol WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();


        if (count($rows) > 0) {


            foreach ($rows as $row) {

                $Query = "INSERT INTO #__symbol_symbol (date_created,symbol_name,symbol_image,pieces,rows,cols,package_id) VALUES ('" .
                        $row->date_created . "','" . $row->symbol_name . "','" . $row->symbol_image . "','" . $row->pieces . "','" . $row->rows . "','" . $row->cols . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    private function cloneSymbolPresentation($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__symbol_presentation WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        if (count($rows) > 0) {

            foreach ($rows as $row) {

                $Query = "INSERT INTO  #__symbol_presentation (presentation_create,presentation_modify,presentation_publish,package_id) VALUES ('" .
                        $row->presentation_create . "','" . $row->presentation_modify . "','" . $row->presentation_publish . "','" . $insertid . "')";

                $db->setQuery($Query);

                $db->query();
            }
        }
    }

    function getGiftcodeCategory($package_id){
        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__giftcode_category WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();
        return $rows;
    }

    private function cloneGiftcode($package_id, $insertid) {

        $db = &JFactory::getDBO();
        $query = "SELECT * FROM #__giftcode_category WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $cat_mapper = array();
        if (count($rows) > 0) {

            foreach ($rows as $data) {

                $query = $db->getQuery(true);
                $columns = array('name','image', 'description','published','symbol_pieces_award','created_date_time','color_code','locked','package_id','category_id');
                $values = array($db->quote($data->name), $db->quote($data->image), $db->quote($data->description),$db->quote($data->published),$db->quote($data->symbol_pieces_award),$db->quote($data->created_date_time),$db->quote($data->color_code),$db->quote($data->locked),$db->quote($insertid),$db->quote($data->category_id));
                $query->insert($db->quoteName('#__giftcode_category'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();

                $cat_mapper[$data->id] = $db->insertid();

            }

            $this->cloneGiftcodeCollection($package_id,$insertid,$cat_mapper);
        }

    }


    private function cloneGiftcodeCollection($package_id, $insertid, $cat_mapper) {

        $db = &JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__giftcode_collection')->where('package_id='.$db->quote($package_id));
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $collection_mapper = array();

        if (count($rows) > 0) {

            foreach ($rows as $data) {

                $query = $db->getQuery(true);
                $columns = array('color_id','total_giftcodes', 'created_date_time','modified_date_time','published','package_id');
                $values = array($db->quote($cat_mapper[$data->color_id]), $db->quote($data->total_giftcodes), $db->quote($data->created_date_time),$db->quote($data->modified_date_time),$db->quote($data->published),$db->quote($insertid));
                $query->insert($db->quoteName('#__giftcode_collection'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
                $collectionId = $db->insertid();
                $collection_mapper[$data->id] = $collectionId;
                $this->cloneGiftcodeGiftcode($package_id,$insertid,$cat_mapper, $collection_mapper);

            }
        }
    }

    private function cloneGiftcodeGiftcode($package_id, $insertid,$cat_mapper, $collection_mapper) {

        $db = &JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__giftcode_giftcode')->where('package_id='.$db->quote($package_id));
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        if (count($rows) > 0) {
            foreach ($rows as $data) {

                $query = $db->getQuery(true);
                $columns = array('giftcode_category_id','giftcode', 'created_date_time','giftcode_collection_id','published','package_id');
                $values = array($db->quote($cat_mapper[$data->giftcode_category_id]), $db->quote($data->giftcode), $db->quote($data->created_date_time),$db->quote($collection_mapper[$data->giftcode_collection_id]),$db->quote($data->published),$db->quote($insertid));
                $query->insert($db->quoteName('#__giftcode_giftcode'));
                $query->columns($db->quoteName($columns));
                $query->values(implode(',', $values));
                $db->setQuery($query);
                $db->execute();
              
            }
        }
    }

    private function clonePrize($package_id, $insertid) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__symbol_prize WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "'";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        $data = "";

        if (count($rows) > 0) {

            foreach ($rows as $row) {


                $query = "INSERT INTO #__symbol_prize (date_created, prize_name, prize_value, prize_image, created_by, package_id, status ) VALUES ('$row->date_created','$row->prize_name','$row->prize_value','$row->prize_image','$row->created_by', '$insertid','$row->status')";

                $db->setQuery($query);

                $db->query();
            }
        }
    }

    public function create_setting($package_id) {
        $row[] = array('package_id' => $package_id, 'category_id' => 1, 'colour_code' => '#FF0000', 'category_name' => 'RED', 'donation_amount' => 0.1, 'published' => 1, 'poll_price' => 0.1, 'survey_price' => 0, 'unlocked' => 0,'user_survey_price' => 0.01,'user_quiz_price' => 0.01);
        $row[] = array('package_id' => $package_id, 'category_id' => 2, 'colour_code' => '#FF6600', 'category_name' => 'ORANGE', 'donation_amount' => 0.2, 'published' => 1, 'poll_price' => 0.2, 'survey_price' => 0, 'unlocked' => 0,'user_survey_price' => 0.01,'user_quiz_price' => 0.01);
        $row[] = array('package_id' => $package_id, 'category_id' => 3, 'colour_code' => '#FFFF00', 'category_name' => 'YELLOW', 'donation_amount' => 0.3, 'published' => 1, 'poll_price' => 0.3, 'survey_price' => 0, 'unlocked' => 0,'user_survey_price' => 0.01,'user_quiz_price' => 0.01);
        $row[] = array('package_id' => $package_id, 'category_id' => 4, 'colour_code' => '#008000', 'category_name' => 'GREEN', 'donation_amount' => 0.4, 'published' => 1, 'poll_price' => 0.4, 'survey_price' => 0, 'unlocked' => 0,'user_survey_price' => 0.01,'user_quiz_price' => 0.01);
        $row[] = array('package_id' => $package_id, 'category_id' => 5, 'colour_code' => '#0000FF', 'category_name' => 'BLUE', 'donation_amount' => 0.5, 'published' => 1, 'poll_price' => 0.5, 'survey_price' => 0, 'unlocked' => 0,'user_survey_price' => 0.01,'user_quiz_price' => 0.01);
        $row[] = array('package_id' => $package_id, 'category_id' => 6, 'colour_code' => '#000080', 'category_name' => 'INDIGO', 'donation_amount' => 0.6, 'published' => 1, 'poll_price' => 0.6, 'survey_price' => 0, 'unlocked' => 0,'user_survey_price' => 0.01,'user_quiz_price' => 0.01);
        $row[] = array('package_id' => $package_id, 'category_id' => 7, 'colour_code' => '#800080', 'category_name' => 'VIOLET', 'donation_amount' => 0.7, 'published' => 1, 'poll_price' => 0.7, 'survey_price' => 0, 'unlocked' => 0,'user_survey_price' => 0.01,'user_quiz_price' => 0.01);
        $db = JFactory::getDBO();
        for ($i = 0; $i <= count($row) - 1; $i++) {
            //echo $row[$i]['category_id'].'<br>';
            $query = "INSERT INTO `#__ap_categories` (package_id,category_id,colour_code,category_name,donation_amount,poll_price, survey_price,user_survey_price,user_quiz_price) 
                          VALUES('" . $row[$i]['package_id'] . "','" . $row[$i]['category_id'] . "','" . $row[$i]['colour_code'] . "','" . $row[$i]['category_name'] . "','" . $row[$i]['donation_amount'] . "','" . $row[$i]['poll_price'] . "','" . $row[$i]['survey_price'] . "','" . $row[$i]['user_survey_price'] . "','" . $row[$i]['user_quiz_price'] . "')";
            $db->setQuery($query);
            $db->query();
        }
    }

    function delete($data) {
        $db = JFactory::getDBO();
        $query = "DELETE FROM `#__search_engine` WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
    }

    function publish($data) {
        $db = JFactory::getDBO();
        $query = "UPDATE `#__search_engine` SET published = 1 WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
    }

    function unpublish($data) {
        $db = JFactory::getDBO();
        $query = "UPDATE `#__search_engine` SET published = 0 WHERE package_id IN (" . implode(',', $data) . ")";
        $db->setQuery($query);
        $db->query();
    }

    function show_fields($table) {              
        $db = JFactory::getDBO();      
        $db->setQuery("SHOW FIELDS FROM #__" . $table);
        $fields = $db->loadColumn();
        return $fields;
    }

    function info($package_id, $array = false) {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__search_engine WHERE package_id = '$package_id' LIMIT 1");
        if ($array) {
            $row = $db->loadAssoc();
        } else {
            $row = $db->loadObject();
        }
        return $row;
    }

    function save_settings($post) {
        $db = & JFactory::getDBO();
        foreach ($post as $key => $value) {
            //echo $key .' => '.$value.'<br>';
            $query = "REPLACE INTO `#__ap_donation_variables` (name,value) VALUES ('$key','$value')";
            $db->setQuery($query);
            $db->query();
        }
        $message = JText::_($query);
        JFactory::getApplication()->enqueueMessage($message, 'error');
    }

    function save_categories() {
        $db = & JFactory::getDBO();
        for ($i = 0; $i <= count(JRequest::getVar('category_id')) - 1; $i++) {
            $query = "UPDATE `#__ap_categories` SET 
                colour_code = '" . $_POST['colour_code'][$i] . "',
                category_name = '" . $_POST['category_name'][$i] . "' 
                WHERE category_id = '" . $_POST['category_id'][$i] . "'";
            $db->setQuery($query);
            $db->query();
        }
    }

    function invar($name, $value) {
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM `#__ap_donation_variables` WHERE name = '$name' LIMIT 1";
        $db->setQuery($query);
        $rs = $db->loadObject();
        if ($rs->name) {
            if ($rs->value) {
                $result = $rs->value;
            } else {
                $result = $value;
            }
            return $result;
        } else {
            return $value;
        }
    }

    /* Create by kadeyasa@gmail.com */

    function creategiftcodecat($package_id) {
        $db = $this->getDBO();
        $dateYear = date('Y-m-d');
        $row[] = array('package_id' => $package_id, 'name' => 'Red', 'image' => 'red.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#FF0000', 'locked' => '1', 'category_id' => '1');
        $row[] = array('package_id' => $package_id, 'name' => 'Orange', 'image' => 'orange.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#FF6600', 'locked' => '1', 'category_id' => '2');
        $row[] = array('package_id' => $package_id, 'name' => 'Yellow', 'image' => 'yellow.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#FFFF00', 'locked' => '1', 'category_id' => '3');
        $row[] = array('package_id' => $package_id, 'name' => 'Green', 'image' => 'green.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#008000', 'locked' => '1', 'category_id' => '4');
        $row[] = array('package_id' => $package_id, 'name' => 'Blue', 'image' => 'blue.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#0000FF', 'locked' => '1', 'category_id' => '5');
        $row[] = array('package_id' => $package_id, 'name' => 'Dark Blue', 'image' => 'dark blue.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#000080', 'locked' => '1', 'category_id' => '6');
        $row[] = array('package_id' => $package_id, 'name' => 'Purple', 'image' => 'purple.jpg', 'description' => 'unique symbol', 'published' => '1', 'symbol_pieces_award' => '', 'created_date_time' => $dateYear, 'color_code' => '#800080', 'locked' => '1', 'category_id' => '7');

        if (count($row) > 0) {
            for ($c = 0; $c < count($row); $c++) {
                $query = "INSERT INTO #__giftcode_category (package_id,name,image,description,published,created_date_time,color_code,locked,category_id) VALUES ('" . $row[$c]['package_id'] . "','" . $row[$c]['name'] . "','" . $row[$c]['image'] . "','" .
                        $row[$c]['description'] . "','" . $row[$c]['published'] . "','" . $row[$c]['created_date_time'] . "','" . $row[$c]['color_code'] . "','" . $row[$c]['locked'] . "','" . $row[$c]['category_id'] . "')";

                $db->setQuery($query);

                $db->query();
            }
        }
    }

    public function getGiftCode($package_id) {

        $db = &JFactory::getDBO();

        $query = "SELECT * FROM #__giftcode_category WHERE " . $db->QuoteName('package_id') . "='" . $package_id . "' ORDER BY id ASC LIMIT 0,1";

        $db->setQuery($query);

        $rows = $db->loadObjectList();

        foreach ($rows as $row) {

            return $row;
        }
    }
    
    public function CheckPackageExpired($package_id){
        $now    = date('Y-m-d');
        $db     = &JFactory::getDBO();
        $query  = $db->getQuery(TRUE);
        $query->select('*');
        $query->from($db->QuoteName('#__search_engine'));
        $query->where("start_date<='".$now."'");
        $query->where("end_date>='".$now."'");
        $query->where("package_id='".$package_id."'");
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }
}
