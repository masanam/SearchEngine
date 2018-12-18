<?php

class UserGroup {

    private $id;

    function __construct() {
        $this->id = JRequest::getInt("id");
    }

    public function setUserGroup($package_id, $contact_id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__ap_useraccounts');
        $query->set('package_id=' . $package_id);
        $query->where('id=' . $contact_id);
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    public function getTotal($condition, $criteria = array()) {
        $db = JFactory::getDbo();

        if ($condition == "name" || $condition == "email") {
            $query = $this->buildQueryNameAndEmail($criteria['criteria'], $condition);
        } else if ($condition == "gender") {
            $query = $this->buildQuery($criteria['criteria'], $condition);
        } else if($condition == "location"){
            $query = $this->buildQueryLocation($criteria);
        } else {
            $query = $this->buildQueryAge($criteria['less'], $criteria['greater']);
        }
        $db->setQuery($query);
        $db->query();
        //var_dump($db->replacePrefix((string) $db->getQuery()));
        return $db->getNumRows();
    }

    public function getData($condition, $population, $criteria = array()) {
        $db = JFactory::getDbo();

        if ($condition == "name" || $condition == "email") {
            $query = $this->buildQueryNameAndEmail($criteria['criteria'], $condition);
        } else if ($condition == "gender") {
            $query = $this->buildQuery($criteria['criteria'], $condition);
        }else if($condition == "location"){
            $query = $this->buildQueryLocation($criteria);
        } else {
            $query = $this->buildQueryAge($criteria['less'], $criteria['greater']);
        }

        $total = $this->getTotal($condition, $criteria);
        $num = $this->getNum($population, $total);
        $query->order('id ASC LIMIT ' . $num);
        $db->setQuery($query);
        //var_dump($db->replacePrefix((string) $db->getQuery()));die;
        return $db->loadObjectList();
    }

    private function buildQueryNameAndEmail($criteria, $condition = "email") {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        if ($condition == "email") {
            $query->select('a.*,u.`name`,u.`email`');
            $query->from($db->quoteName('#__ap_useraccounts') . ' AS a');
            $query->innerJoin($db->quoteName('#__users') . ' AS u ON u.id=a.id');
            $query->where('u.email like ' . $db->quote($this->addLike($criteria)));
        } else {
            list($firstname, $lastname) = explode(' ',$criteria);
            $query->select('a.*,u.`name`,u.`email`');
            $query->from($db->quoteName('#__ap_useraccounts') . ' AS a');
            $query->innerJoin("(SELECT IF(
                LOCATE(' ', `name`) >0,
                SUBSTRING(`name`, 1, LOCATE(' ', `name`) - 1),
                `name`
                ) AS firstname,
                IF(
                    LOCATE(' ', `name`) > 0,
                    SUBSTRING(`name`, LOCATE(' ', `name`) + 1),
                    ''
                ) AS lastname,
                name, id, email
                FROM `#__users`
                ) u ON u.id=a.id");
            $query->where('u.firstname = ' . $db->quote($firstname));

            if ($lastname)
            {
                $query->where('u.lastname = ' . $db->quote($lastname));
            }
        }
        $query->where('a.package_id=0');
        return $query;
    }

    private function buildQuery($criteria, $condition = "gender") {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*,u.`name`,u.`email`');
        $query->from($db->quoteName('#__ap_useraccounts') . ' AS a');
        $query->innerJoin($db->quoteName('#__users') . ' AS u ON u.id=a.id');
        $query->where('a.gender = ' . $db->quote($criteria));
        $query->where('a.package_id=0');
        return $query;
    }

    private function buildQueryLocation($criteria){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*,u.`name`,u.`email`');
        $query->from($db->quoteName('#__ap_useraccounts') . ' AS a');
        $query->innerJoin($db->quoteName('#__users') . ' AS u ON u.id=a.id');
        if(is_array($criteria)){
            foreach ($criteria['criteria'] as $field => $val){
                $query->where('a.'.$field.' = ' . $db->quote($val));
            }
        }
        $query->where('a.package_id=0');
        return $query;
    }
    private function buildQueryAge($less, $greater, $condition = "gender") {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*,u.`name`,u.`email`');
        $query->from($db->quoteName('#__ap_useraccounts') . ' AS a');
        $query->innerJoin($db->quoteName('#__users') . ' AS u ON u.id=a.id');
        $query->where('ROUND(DATEDIFF(CURDATE(),a.birthday) / 365) >= ' . $less);
        $query->where('ROUND(DATEDIFF(CURDATE(),a.birthday) / 365) <= ' . $greater);
        $query->where('a.package_id=0');
        return $query;
    }

    private function addLike($text) {
        $text = "%" . $text . "%";
        return $text;
    }

    public function setUserGroupData($user_group_id, $contact_id, $filter) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->insert('#__ap_usergroup');
        $query->columns($db->quoteName('usergroup_id') . ',' . $db->quoteName('useraccount_id') . ',' . $db->quoteName('filter'));
        $query->values((int) $user_group_id . ',' . (int) $contact_id . ',' . $db->quote($filter));
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    public function getUserGroupData($user_group_id, $filter) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__ap_usergroup'));
        $query->where($db->quoteName('usergroup_id') . '=' . $user_group_id);
        $query->where($db->quoteName('filter') . '=' . $db->quote($filter));
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public function deleteUserGroupData($user_group_id, $filter) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete();
        $query->from($db->quoteName('#__ap_usergroup'));
        $query->where($db->quoteName('usergroup_id') . '=' . $user_group_id);
        $query->where($db->quoteName('filter') . '=' . $db->quote($filter));
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    private function getNum($population, $total) {
        return round(($population / 100) * $total);
    }

    public function add($package_id, $user_group_id, $population, $condition, $criteria, $greater = 30) {
        if ($condition == "email" || $condition == "name" || $condition == "gender") {
            $data['criteria'] = $criteria;
        }
        else if($condition == 'location'){
            $data['criteria'] = $criteria;
            if(is_array($criteria)){

            }
        }
        else {
            $data['less'] = $criteria;
            $data['greater'] = $greater;
        }

        $rows = $this->getData($condition, $population, $data);

        foreach ($rows as $row) {
            $this->setUserGroup($package_id, $row->id);
            $this->setUserGroupData($user_group_id, $row->id, $condition);
        }
    }

    public function delete($user_group_id, $condition) {
        $rows = $this->getUserGroupData($user_group_id, $condition);

        foreach ($rows as $row) {
            $this->setUserGroup(0, $row->useraccount_id);
        }

        $this->deleteUserGroupData($user_group_id, $condition);
    }

    public function edit($package_id, $user_group_id, $population, $condition, $criteria, $greater = 30) {
        $this->delete($user_group_id, $condition);
        $this->add($package_id, $user_group_id, $population, $condition, $criteria, $greater);
    }

}
