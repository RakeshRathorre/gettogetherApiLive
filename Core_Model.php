 <?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

 // Basic Class For General use class 

class Core_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->booking = 'booking';
        $this->user = 'user';
        // $this->notificationss = 'notification';
    }
   
      public function updateMessageNotification($user_id) {
        if (!empty($user_id)) {
            $this->db->set('status', 1);
            $this->db->where('recieverId', $user_id);
            $this->db->where('status', 0);
            $this->db->where('type', 2);

            $this->db->update($this->notificationss);
            return true;
        } else {
            return FALSE;
        }
    }
    

    public function AllMessageNotication($user_id) {
        // $this->db->select($this->user.'.name');
        $this->db->select($this->notificationss . '.*');
        $this->db->from($this->notificationss);
        // $this->db->join($this->user,$this->user.'.id='.$this->notificationss.'.recieverId','left');
        $this->db->where('recieverId', $user_id);
        $this->db->where('type', 2);
        $this->db->order_by('n_id', 'DESC');
        $sql = $this->db->get();
        // echo $this->db->last_query(); die;
        if ($sql->num_rows() > 0) {
            return $sql->result();
        } else {
            return FALSE;
        }
    }

    public function updateNotification($user_id) {
        if (!empty($user_id)) {
            $this->db->set('status', 1);
            $this->db->where('recieverId', $user_id);
            $this->db->where('status', 0);
            $this->db->where('type', 1);

            $this->db->update($this->notificationss);
            return true;
        } else {
            return FALSE;
        }
    }

    public function AllNotication($user_id) {
        // $this->db->select($this->user.'.name');
        $this->db->select($this->notificationss . '.*');
        $this->db->from($this->notificationss);
        // $this->db->join($this->user,$this->user.'.id='.$this->notificationss.'.recieverId','left');
        $this->db->where('recieverId', $user_id);
        $this->db->where('type', 1);
        $this->db->order_by('n_id', 'DESC');
        $sql = $this->db->get();
        // echo $this->db->last_query(); die;
        if ($sql->num_rows() > 0) {
            return $sql->result();
        } else {
            return FALSE;
        }
    }

    function InsertRecord($TableName, $Data) {
        $this->db->insert($TableName, $Data);
        return $this->db->insert_id();
    }

    function InsertBatch($TableName, $Data) {
        $this->db->insert_batch($TableName, $Data);
        return $this->db->insert_id();
    }

    function UpdateRecord($TableName, $Data, $WhereData = NULL) {
        if ($WhereData != NULL) {
            $this->db->where($WhereData);
        }
        $Result = $this->db->update($TableName, $Data);
        return $Result;
    }

    function UpdateBatch($TableName, $Data, $WhereData) {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $Result = $this->db->update_batch($TableName, $Data, $WhereData);
        //echo $this->db->last_query(); die;
        return true;
        // print_r($Result); die;
    }

    function DeleteRecord($TableName, $Data, $WhereData) {
        $this->db->where($WhereData);
        $this->db->update($TableName, $Data);
        return $this->db->affected_rows();
    }

    public function countrecords_rows($TableName, $group_by) {
        $this->db->group_by($group_by);
        $this->db->from($TableName);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function SelectSingleRecord($TableName, $Selectdata, $WhereData = array(), $orderby = array()) {
        $this->db->select($Selectdata);
        if (!empty($orderby)) {
            $this->db->order_by($orderby);
        }
        if (!empty($WhereData)) {
            $this->db->where($WhereData);
        }
        $query = $this->db->get($TableName);
        return $query->row();
    }

    function SelectRecord($TableName, $Selectdata, $WhereData, $orderby) {
        $this->db->select($Selectdata);
        if (!empty($orderby)) {
            $this->db->order_by($orderby);
        }
        if (!empty($WhereData)) {
            $this->db->where($WhereData);
        }
        $query = $this->db->get($TableName);

        return $query->result_array();
    }

    function SelectRecordpaginatoin($TableName, $Selectdata, $WhereData, $orderby, $limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select($Selectdata);
        $this->db->order_by($orderby);
        $this->db->where($WhereData);
        $query = $this->db->get($TableName);
        return $query->result_array();
    }

    function joindatapagination($place1, $place2, $WhereData, $Selectdata, $TableName1, $TableName2, $orderby, $limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2);
        $this->db->order_by($orderby);
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->result_array();
    }

    function joindata($place1, $place2, $WhereData, $Selectdata, $TableName1, $TableName2, $orderby) {
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2);
        $this->db->order_by($orderby);
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->row_array();
    }

    function joindataResult($place1, $place2, $WhereData, $Selectdata, $TableName1, $TableName2, $orderby) {
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2);
        if($orderby!='')
        $this->db->order_by($orderby);
        if($WhereData!='')
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->result_array();
    }

    function joinApidata($place1, $place2, $WhereData, $Selectdata, $TableName1, $TableName2, $orderby) {
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2);
        $this->db->order_by($orderby);
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->result_array();
    }

    function SelectRecordlimit($TableName, $Selectdata, $WhereData, $orderby, $limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select($Selectdata);
        if (!empty($orderby)) {
            $this->db->order_by($orderby);
        }
        if (!empty($WhereData)) {
            $this->db->where($WhereData);
        }
        $query = $this->db->get($TableName);
        return $query->result_array();
    }

    function countrecords($TableName, $WhereData) {
        $this->db->where($WhereData);
        return $query = $this->db->count_all_results($TableName);
    }

    public function delete_record($tbl, $where) {
        $this->db->where($where);
        $this->db->delete($tbl);
        return $this->db->affected_rows();
    }

    public function SelectMaxRecord($table) {
        $this->db->select_max('order_id');
        $this->db->from($table);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    function getsingle($TableName,$where_profile,$field_profile){
        $this->db->where($where_profile);
        $res=$this->db->get($TableName,$field_profile);
        return $res->result_array();
    }
   
   function updateFields($TableName,$field_update,$where_update){
       $this->db->where($where_update);
        $res=$this->db->update($TableName,$field_update);
        return $res; 
   }
   
   function ParkingSpaceResult($where){    //$this->db->select('user.id,user.name,user.email,user.contact,rentourspace.id,rentourspace.driveway_owner,rentourspace.typeofspace,rentourspace.noofspace,rentourspace.address,countries.countryName');
        $this->db->select('user.id,user.name,user.email,user.contact,rentourspace.id as pid,rentourspace.uid,rentourspace.typeofspace,rentourspace.noofspace,rentourspace.status,rentourspace.is_deleted,rentourspace.driveway_owner,rentourspace.phour,rentourspace.pday,rentourspace.pweek,rentourspace.pmonth,rentourspace.address,rentourspace.description,rentourspace.created_dt,countries.countryName');
        $this->db->from('user');
        $this->db->join('rentourspace','user.id=rentourspace.uid','inner');
        $this->db->join('countries', 'rentourspace.country=countries.countryID','inner');
        if($where!='')
        $this->db->where('rentourspace.uid', $where);
        $query=$this->db->get();
        return $query->result_array();
}

   function jointables($place1, $place2, $WhereData, $Selectdata, $TableName1, $TableName2, $orderby,$place3,$TableName3,$place4,$TableName4) {
       
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2,'inner');
        if($TableName3!='')
        $this->db->join($TableName3, $place1 . '=' . $place3,'inner');
        if($TableName4!='')
        $this->db->join($TableName4, $place1 . '=' . $place4,'inner');
        if($orderby!='')
        $this->db->order_by($orderby);
        if($WhereData!='')
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->result_array();
    }

    function jointables_result($place1, $place2, $WhereData, $Selectdata, $TableName1, $TableName2, $orderby,$place3,$TableName3,$place4,$TableName4) {
       
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2,'inner');
        if($TableName3!='')
        $this->db->join($TableName3, $place1 . '=' . $place3,'inner');
        if($TableName4!='')
        $this->db->join($TableName4, $place1 . '=' . $place4,'inner');
        // if($orderby!='')
        $this->db->order_by($orderby);
        // if($WhereData!='')
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->result_array();
    }
     
     
       public function CountDetails($table,$where,$value) {
         if($value==1){
        $res = $this->db->query("SELECT COUNT(*) as count FROM $table 
        WHERE DATE(created_dt) IN (CURDATE())");
        }
        else if($value==2){
            $res = $this->db->query("select count(*) as total from $table where status=1");
        }
        else if($value==3){
            $res = $this->db->query("SELECT COUNT(*) as count FROM $table 
        WHERE DATE(created_at) IN (CURDATE())");
        }
        else if($value==4){
            $res = $this->db->query("SELECT COUNT(*) as count FROM $table 
        WHERE DATE(created_at) IN (CURDATE()) and status=1");
        }
        else if($value==5){
            $res = $this->db->query("select count(*) as total from $table where status=0");
        }
        else if($value==6){
            $res = $this->db->query("SELECT COUNT(*) as count FROM $table 
        WHERE DATE(created_at) IN (CURDATE()) and status=0");
        }    
        else{
            $res = $this->db->query("select count(*) as total from $table where group_id= $where");
        }
        return $res->result_array();
    }
    
    public function Earning($table,$value){
        if($value==1){
            $res = $this->db->query("SELECT SUM(commission) as total FROM $table 
        WHERE DATE(created_at) IN (CURDATE())");
        }
        else{
            //date('Y-m-d', strtotime());

            $sd = date('Y-m-1');
            $ed = date('Y-m-t');
            
            $res = $this->db->query("select cast(sum(payment_amt) as decimal(10,3)) as total from $table where created_at BETWEEN date('$sd') and date('$ed')");
        }
        return $res->result_array();
    }
    function CountDet($where){
                 $res=$this->db->query("select count(*) as total from group_members where category_id = $where");
        return $res->result_array();
    }

    function CountDetMamber($where){
                 $res=$this->db->query("select count(*) as total from group_members where group_id = $where");
        return $res->row_array();
    }

    //  function CountDet($where){
    //              $res=$this->db->query("select count(*) as total from group_members where category_id = $where");
    //     return $res->result_array();
    // }
      function Countmembers($where){
                 $res=$this->db->query("select count(*) as total from group_members where group_id= $where");
        
        return $res->result_array();
    }
      function Countevent_group($where){
                 $res=$this->db->query("select count(*) as total from event where group_id= $where");
        
        return $res->result_array();
    }

}
