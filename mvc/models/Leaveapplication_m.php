<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaveapplication_m extends MY_Model {

    protected $_table_name = 'leaveapplication';
    protected $_primary_key = 'leaveapplicationID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "leaveapplicationID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_leaveapplication($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_leaveapplication($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_leaveapplication($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_leaveapplication($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_leaveapplication($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_leaveapplication($id)
    {
        parent::delete($id);
    }

    public function get_sum_of_leave_days_by_user($roleID, $userID, $year)
    {
        $this->db->select('leavecategoryID, SUM(leave_days) AS days');
        $this->db->where('create_userID', $userID);
        $this->db->where('create_roleID', $roleID);
        $this->db->where("status", 1);
        $this->db->where("year", $year);
        $this->db->group_by("leavecategoryID");
        $query = $this->db->get('leaveapplication');
        return $query->result();
    } 

    public function get_sum_of_leave_days_by_user_for_single_category($roleID, $userID , $categoryID, $year)
    {
        $this->db->select('leavecategoryID, SUM(leave_days) AS days');
        $this->db->where('create_userID', $userID);
        $this->db->where('create_roleID', $roleID);
        $this->db->where("status", 1);
        $this->db->where("year", $year);
        $this->db->where("leavecategoryID", $categoryID);
        $query = $this->db->get('leaveapplication');
        return $query->row();
    }

    public function get_leaveapplication_for_report($array)
    {
        $this->db->select('leaveapplication.*, leavecategory.leavecategory');
        $this->db->from('leaveapplication');
        $this->db->join('leavecategory', 'leaveapplication.leavecategoryID = leavecategory.leavecategoryID', 'LEFT');

        if(isset($array['roleID'])) {
            $this->db->where('leaveapplication.create_roleID', $array['roleID']);
        }
        if(isset($array['userID'])) {
            $this->db->where('leaveapplication.create_userID', $array['userID']);
        }
        if(isset($array['categoryID'])) {
            $this->db->where('leaveapplication.leavecategoryID', $array['categoryID']);
        }
        if(isset($array['statusID'])) {
            if($array['statusID'] == 1) {
                $this->db->where('leaveapplication.status', NULL);
            } elseif($array['statusID'] == 2) {
                $this->db->where('leaveapplication.status', 0);
            } elseif($array['statusID'] == 3) {
                $this->db->where('leaveapplication.status', 1);
            }
        }

        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('leaveapplication.apply_date >=', $array['from_date']);
            $this->db->where('leaveapplication.apply_date <=', $array['to_date']);
        }
        $this->db->order_by('leaveapplication.leaveapplicationID', "desc");
        return $this->db->get()->result();
    }

    public function get_leaveapplication_by_role($featureName)
    {
        $this->db->select('*');
        $this->db->from('permissions');
        $this->db->join('permissionlog', 'permissionlog.permissionlogID = permissions.permissionlogID', 'LEFT');
        $this->db->join('role', 'role.roleID = permissions.roleID', 'LEFT');
        $this->db->where(array('permissionlog.name' => $featureName));
        $query = $this->db->get();
        $roles = $query->result();

        if(!inicompute($roles)) {
            $this->load->model('role_m');
            $admin = $this->role_m->get_single_role(['roleID' => 1]);
            $array = (object) [
                'roleID'    => 1,
                'role'      => (inicompute($admin) ? $admin->role : ''),
            ];
            return $array;
        } else {
            $array = [];
            $i = 0;
            foreach ($roles as $role) {
                $array[$i] = (object) ['roleID' => $role->roleID, 'role' => $role->role];
            }
            return $array;
        }
    }
    
}