<?php   
class Group extends CI_Controller{

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
    public function group_list() {
        $request = json_decode(rtrim(file_get_contents('php://input'), "\0"));
        $category_id = $request->category_id;
        // print_r($request);die();
        $where = array('category_id' => $category_id );
        $res = $this->Core_Model->SelectRecord('group_list','id,image,name',$where,$order = '');
        // print_r($res);die();
        $userdata = [];
        foreach ($res as $total) {
            $userdata[] = array('id' => $total['id'],'name' => $total['name'],'image' => base_url('assets/img/').$total['image'] );
            // print_r($total);die();
        }
        if (empty($userdata)) {
            $this->res->success = false;
            $this->_error('error', 'Incorrect data.');
        } else {
            $i = 0;
            foreach ($userdata as $value) {
                $count = $this->Core_Model->CountDetMamber($value['id']);
                if(!empty($count)){
                    $userdata[$i]['membersCount'] = $count['total'];
                }else{
                    $userdata[$i]['membersCount'] = 0;
                }
                //print_r($count);die();
            $i++;   
            }
            //$where1 = $res[0]['category_id'];
            //print_r($where1);die();
            //$res1 = $this->Core_Model->CountDet($where1);
            $this->res->success = true;
            // $this->res->data = $res;
            $this->res->data = $userdata;
           // $this->res->members = $res1;
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