<?php   
class Category extends CI_Controller{

 function __construct() {
    parent::__construct();
    header('Content-Type: application/json');
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Origin: *"); 
    $this->load->model('Core_Model');
    $this->load->model('Common_Model');
    $this->load->library('session');
    $this->res = new stdClass();
    //$request = json_decode(rtrim(file_get_contents('php://input'), "\0"));
    }
    public function category_list() {
        $request = json_decode(rtrim(file_get_contents('php://input'), "\0")); 
        $where = array('home_status' => 1 );
        $res = $this->Core_Model->SelectRecord('category','id,image,name',$where,$order = '');
        $userdata = [];
        foreach ($res as $total) {
            $userdata[] = array('id' => $total['id'],'name' => $total['name'],'image' => base_url('assets/img/').$total['image'] );
            // print_r($total);die();
        }
        // print_r($userdata);die();
        if (empty($res)) {
            $this->res->success = false;
            $this->_error('error', 'Incorrect data.');
        } else {
        
            $this->res->success = true;
            $this->res->data = $userdata;
        }
        $this->_output();
          exit();
    }
     function _output() {
        $this->res->datetime = date('Y-m-d\TH:i:sP');
        echo json_encode($this->res);
    }
    function _error($error, $reason, $code = null) {
        $this->res->success = false;
        if (isset($this->req->request)) {
            $this->res->request = $this->req->request;
        }
        $this->res->error = $error;
        $this->res->message = $reason;
        $this->res->datetime = date('Y-m-d\TH:i:sP');
        echo json_encode($this->res);
        // die();
        exit();
    }
}

?>