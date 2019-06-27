<?php   
class Group_detail extends CI_Controller{

 function __construct() {
    parent::__construct();
    header('Content-Type: application/json');
    // header("Access-Control-Allow-Headers: Content-Type");
    // header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Origin: *"); 
    $this->load->model('Core_Model');
    $this->load->model('Common_Model');
    $this->load->library('session');
    $this->res = new stdClass();
    // $request = json_decode(rtrim(file_get_contents('php://input'), "\0"));
    }

    public function group_detail() 
    {
        $request = json_decode(rtrim(file_get_contents('php://input'), "\0")); 
        $group_id = $request->group_id;
        $where = array('group_list.id' => $group_id);
        $res = $this->Core_Model->joindataResult($place1 ='user.user_id', $place2 = 'group_list.user_id',$where,'group_list.id,group_list.name,group_list.image as groupimage ,group_list.group_type,user.image,user.address,user.full_name,user.user_id','user','group_list',$order='');
        
        // print_r($res);die();
        $userdata = [];
        foreach ($res as $total) 
        {
            $userdata[] = array('group_id' => $total['id'],'user_id' => $total['user_id'],'group_name' => $total['name'],'name' => $total['full_name'],'Location' => $total['address'],'group type' => $total['group_type'],'image' => base_url('assets/img/').$total['image'],'group image' => base_url('assets/img/').$total['groupimage']);
            // print_r($userdata);die();
        }
        if (empty($userdata)) 
        {
            $this->res->success = false;
            $this->_error('error', 'Incorrect id or data.');
        } else
             {
                $this->res->success = 'true';
                 $this->res->data = $res;
                // $where1 = $res[0]['group_id'];
                $where1 = $res[0]['id'];
                // print_r($where1);die();
                $res1 = $this->Core_Model->Countmembers($where1);
                $this->res->success = true;
                $this->res->data = $userdata;
                $this->res->members = $res1;
            }
                $this->_output();
                exit();
    }

        function _output() {
            // header('Content-Type: application/json');
            //$this->res->request = $this->req->request;
            $this->res->datetime = date('Y-m-d\TH:i:sP');
            echo json_encode($this->res);
        }
        function _error($error, $reason, $code = null) {
            // header('Content-Type: application/json');
            $this->res->success = false;
            if (isset($this->req->request)) {
                $this->res->request = $this->req->request;
            }
            // $this->res->error = $error;
            $this->res->message = $reason;
            $this->res->datetime = date('Y-m-d\TH:i:sP');
            echo json_encode($this->res);
            exit();
    }
}

?>