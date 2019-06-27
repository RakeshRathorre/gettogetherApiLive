<?php   
class Auth extends CI_Controller{

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
    public function signup() 
    {
        $request = json_decode(rtrim(file_get_contents('php://input'), "\0")); 
        $email = $request->email;
        $password = $request->password;    
        $full_name = $request->full_name;    
        
        if (!$email) {
                    $this->_error('Form error', 'Email is not specified.');
        }
        if (!$password) {
                    $this->_error('Form error', 'Password is not specified.');
        }
        if (!$full_name) {
                    $this->_error('Form error', 'Full Name is not specified.');
        }
        if ($this->email_check($email)) {
                    $this->_error('Form error', 'Email already exists.');
        }
        else {
            $where = array('email'=>$email,'password'=>md5($password),'full_name'=>$full_name);
            // $field=array('email');
            $get_email = $this->Core_Model->InsertRecord('user', $where);
            // print_r($get_email);die;
            }
            if (!empty($get_email)) {
        // echo  "yes";die();
                $this->res->success = true;
                return true;
            }
            $this->res->success = false;
            return false;
        }
    function email_check($email)
     {
        $where = array('email' => $email);
        $field = array('email');
        $get_email = $this->Core_Model->selectsinglerecord('user', $field, $where);
    //print_r($get_email);die;
        if (!empty($get_email))
         {
            return true;
        }
        return false;
    }
    public function signin()
    {
        $request = json_decode(rtrim(file_get_contents('php://input'), "\0"));
        $email = $request->email;
        $password = $request->password;
        // print_r($this->input->request_headers());die();
        //for accesstoken check
        // echo $password;die();
        if (!$email) {
            $this->_error('Form error', 'Email-Id is not specified.');
        }
        if (!$password) {
            $this->_error('Form error', 'Password is not specified.');
        }
         $where_login = array('email' => $email, 'password' => md5($password));
         $aray_login = $this->Core_Model->selectsinglerecord('user', '*', $where_login);
         // print_r ($aray_login);die();
         if(empty($aray_login)) {
            $this->_error('error', 'Incorrect Email Id & Password.');
        } else {
            // $id=$aray_login['id'];
            $accesstoken = base64_encode(random_bytes(32));
            $is_user_login=1;
            // print_r($accesstoken);die();
            //for accesstoken show
            //update access token
            $where_update = array('email' => $email);
            $field_update = array('accesstoken'=>$accesstoken,'is_user_login'=>$is_user_login);
            $this->Core_Model->updateFields('user', $field_update, $where_update);
            // $this->res->success = 'Success';
            $aray_login = $this->Core_Model->selectsinglerecord('user', '*', $where_login);
            $this->res->success = true;
            $this->res->data[] = $aray_login;

            //$aray_login['accesstoken'] = $accesstoken;
           //$this->res->accesstoken = $accesstoken;
        }
        $this->_output();
        exit();
    }
    public function logout()
    {
        $request = json_decode(rtrim(file_get_contents('php://input'), "\0"));
         $user_id = $request->user_id;
         // print_r($user_id);die();
         $header = $this->input->request_headers();
         $accesstoken = $header['Accesstoken'];
         // print_r($accesstoken);die;
        if($this->check_accesstoken($user_id,$accesstoken)){
            $where_update = array('user_id' => $user_id);
            $field_update = array('accesstoken'=>0,'is_user_login'=>0);
            $this->Core_Model->updateFields('user', $field_update, $where_update);
            $this->res->success = true;
             $this->res->message = 'Logout Successfully.';
        }else{
            $this->_error('error', 'Invalid accesstoken.');
        }
        $this->_output();
          exit();
    }
    public function check_accesstoken($user_id,$accesstoken)
    {
        $where = array('user_id'=>$user_id,'accesstoken'=>$accesstoken);
        $selectdata = 'user_id,accesstoken';
        $res = $this->Core_Model->SelectSingleRecord('user',$selectdata,$where,$order='');
        // print_r($res);die();
       if($res){
        return true;
       }else
       return false;
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
        // $this->res->error = $error;
        $this->res->message = $reason;
        $this->res->datetime = date('Y-m-d\TH:i:sP');
        echo json_encode($this->res);
        exit();
    }
}

?>