<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_Model Extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->booking = 'booking';
        $this->notification = 'notification';
    } 

    public function countNewMessage($userid) {
        $this->db->select('*');
        $this->db->from($this->notification);
        $this->db->where('recieverId', $userid);
        $this->db->where('status', 0);
        $this->db->where('type', 2);
        $sql = $this->db->get();
        if ($sql->num_rows() > 0) {
            return count($sql->result());
        } else {
            return FALSE;
        }
    }

    public function insertNotification($array) {
        if (!empty($array)) {
            $this->db->insert($this->notification, $array);
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    /*     * ********************Show New Booked Notification*********************************** */

    public function countNewBookedStatus($userid) {
        $this->db->select('*');
        $this->db->from($this->notification);
        $this->db->where('recieverId', $userid);
        $this->db->where('status', 0);
        $this->db->where('type', 1);
        $sql = $this->db->get();
        if ($sql->num_rows() > 0) {
            return count($sql->result());
        } else {
            return FALSE;
        }
    }

    /*     * ********************End New Booked Notification*********************************** */

    public function gettotal($table, $where) {
        if ($where != "")
            $this->db->where($where);
        return $this->db->count_all_results($table);
    }

    public function getdata($table, $where, $sort) {
        if ($where != "")
            $this->db->where($where);
        if ($sort != "")
            $this->db->order_by($sort);
        return $this->db->get($table)->result();
    }

    public function getblog($table,$where,$sort,$limit) {
        
        if ($where != "")
            $this->db->where($where);
        if ($sort != "")
            $this->db->order_by($sort);
        if ($limit != "")
            $this->db->limit($limit);
        return $this->db->get($table)->result();
    }


    public function getdatabylimit($table, $where, $sort, $limit, $start) {
        if ($where != "")
            $this->db->where($where);
        if ($sort != "")
            $this->db->order_by($sort);
        if ($limit != "")
            $this->db->limit($limit, $start);
        return $this->db->get($table)->result();
    }

    function onejoindata($place1, $place2, $WhereData, $Selectdata, $TableName1, $TableName2, $orderby) {
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2, 'left');
        $this->db->order_by($orderby);
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->result();
    }

    function twojoindata($place1, $place2, $place3, $place4, $WhereData, $Selectdata, $TableName1, $TableName2, $TableName3, $orderby) {
        $this->db->select($Selectdata);
        $this->db->from($TableName1);
        $this->db->join($TableName2, $place1 . '=' . $place2, 'left');
        $this->db->join($TableName3, $place3 . '=' . $place4, 'left');
        $this->db->order_by($orderby);
        $this->db->where($WhereData);
        $query = $this->db->get();
        return $query->result();
    }

    public function deletedata($table, $where) {
        $this->db->where($where);
        $this->db->delete($table);
        if ($this->db->affected_rows() > 0) {
            echo 1;
        } else {
            return false;
        }
    }

    public function insert($table, $data) {
        $this->db->insert($table, $data);
        $id = $this->db->insert_id();
        return ($this->db->affected_rows() > 0) ? $id : FALSE;
    }

    public function update($table, $data, $where) {
        $this->db->where($where);
        $this->db->update($table, $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    public function forgotpassword($email, $code) {
        $this->db->where("email", $email);
        $query = $this->db->get("user");

        if ($query->num_rows() > 0) {

            $data = array('code' => $code);
            $this->db->where('email', $email);
            $this->db->update('user', $data);
            if ($this->db->affected_rows() > 0) {
                return 1;
            } else {
                return 3;
            }
        } else {
            return 2;
        }
    }

    public function resetpassword($id) {
        $email = base64_decode($id);
        $code = $this->input->post('code');
        $password = md5($this->input->post('password'));
        $this->db->where("code", $code);
        $this->db->where("email", $email);
        $query = $this->db->get("user");

        if ($query->num_rows() > 0) {
            $data = array('password' => $password, "code" => 0);
            $this->db->where('email', $email);
            $this->db->where('code', $code);
            $this->db->update('user', $data);
            if ($this->db->affected_rows() > 0) {
                echo 1;
            } else {
                return false;
            }
        } else {
            echo 3;
        }
    }

    public function checkpropertyfeatured($id) {
        $values = "";
        $this->db->select("id");
        $this->db->where("pid", $id);
        $this->db->where("status", 0);
        $query = $this->db->get("feature_property_payment");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $values = $result[0]->id;
        }
        return $values;
    }

    public function checkpropertyfeaturedexpiry($id) {
        $values = "";
        $this->db->select("created_dt");
        $this->db->where("pid", $id);
        $this->db->where("status", 0);
        $query = $this->db->get("feature_property_payment");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $cdt = $result[0]->created_dt;
            $d = $this->Common_Model->getfeaturedays();
            $values = date("Y-m-d", strtotime("+" . $d, strtotime($cdt)));
        }
        return $values;
    }

    public function getfeaturedays() {
        $values = "";
        $this->db->select("days");
        $query = $this->db->get("feature_package");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $values = $result[0]->days;
        }
        return $values;
    }

    public function getlogo() {
        $values = "";
        $this->db->select("logo");
        $query = $this->db->get("front_setting");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $values = $result[0]->logo;
        }
        echo $values;
    }

    public function getpopularproperty() {
        $this->db->select("property.title,property.id,property.photo,property.price,propertylocation.mapaddress,property.ophoto");
        $this->db->join("propertylocation", "property.id=propertylocation.pid", "left");
        $this->db->order_by("property.popular", "desc");
        $this->db->limit(2, 0);
        return $this->db->get("property")->result();
    }

    public function checkpaid($id) {
        $values = "";
        $this->db->select("mid");
        $this->db->where("id", $id);
        $this->db->where("mid !=", 0);
        $query = $this->db->get("user");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $values = $result[0]->mid;
        }
        return $values;
    }

    public function checktrial($id, $mid) {
        $values = "";
        $this->db->select("trial");
        $this->db->where("id", $id);
        $this->db->where("mid", $mid);
        $this->db->where("trial", 1);
        $query = $this->db->get("user");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $values = $result[0]->trial;
        }
        return $values;
    }

    public function getfeature($id) {
        $values = "";
        $this->db->select("speciality");
        $this->db->where("id", $id);
        $this->db->where("mid !=", 0);
        $query = $this->db->get("user");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $arr = $result[0]->speciality;

            $arr = explode(",", $arr);
            $this->db->where_in("id", $arr);
            $result1 = $this->db->get("feature")->result();
            return $result1;
        } else {

            return $values;
        }
    }

    public function checkuserproperty($id) {
        $values = 0;
        $this->db->select("count(id) as no");
        $this->db->where("uid", $id);
        $this->db->where("pc", 0);
        $query = $this->db->get("property");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $values = $result[0]->no;
        }
        return $values;
    }

function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('faq');
        if(array_key_exists("id",$params)){
            $this->db->where('id',$params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            //set start and limit
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }

        //return fetched data
        return $result;
    }


 function getblogdata($params = array()){
        $this->db->select('*');
        $this->db->from('blog');
        if(array_key_exists("id",$params)){
            $this->db->where('id',$params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            //set start and limit
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $this->db->count_all_results();
            }else{
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }

        //return fetched data
        return $result;
    }

}

?>