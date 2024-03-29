<?php   
class Create_group extends CI_Controller{

 function __construct() {
    parent::__construct();
    header('Content-Type: application/json');
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Origin: *"); 
    $this->load->model('Core_Model');
    $this->load->library('session');
    $this->res = new stdClass();
    // $request = json_decode(rtrim(file_get_contents('php://input'), "\0"));
    
    }
//  
    

    public function create_group()
    {
        $request = json_decode(rtrim(file_get_contents('php://input'), "\0")); 
        // print_r($request);die();
        $location = $request->location;
        $category_id= $request->category_id;    
        $user_id= $request->user_id;    
        $group_name = $request->group_name;    
        $image = $request->image;    
        $about = $request->about;    
        $group_description = $request->group_description;    
        $purpose = $request->purpose;    
        $group_type = $request->group_type;    
        $group_status = 0; 
        // echo $image; die;
        if (!$location) {
                    $this->_error('Form error', 'Location is not specified.');
        }
        if (!$category_id) {
                    $this->_error('Form error', 'Category id is not specified.');
        }
        if (!$user_id) {
                    $this->_error('Form error', 'User id is not specified.');
        }
        if (!$group_name) {
                    $this->_error('Form error', 'Group name is not specified.');
        }
         if (!$image) {
                    $this->_error('Form error', 'image is not specified.');
        }
          if (!$about) {
                    $this->_error('Form error', 'About is not specified.');
        }
        if (!$group_description) {
                    $this->_error('Form error', 'Group description is not specified.');
        }
        if (!$purpose) {
                    $this->_error('Form error', 'Purpose is not specified.');
        }
        if (!$group_type) {
                    $this->_error('Form error', 'Group type is not specified.');
        }
        else
         {
            $activation_code =  $this->generateRandomString();
            $res=   $this->getLatLong($location); 
            $where = array('location'=>json_encode($res).$location,'category_id'=>$category_id,'user_id'=>$user_id,'name'=>$group_name,'image'=>$image,'about'=>$about,'description'=>$group_description,'purpose'=>$purpose,'group_type'=>$group_type,'group_status'=>$group_status,'activation_code'=>$activation_code);
            // print_r($where);die;
            $gid = $this->Core_Model->InsertRecord('group_list', $where);
            /////////email
            // print_r($get_email);die();
            $where_login = array('user_id' => $user_id);
            $aray_login = $this->Core_Model->selectsinglerecord('user', 'email', $where_login);
            $to = $aray_login->email;
            // $activation_code =  $this->generateRandomString();
            $subject = "Group activation";
             // print_r($to);die();
            // $user_id= $request->user_id; 
            // $uid = base64_encode($user_id);
            // print_r($uid);die();
            // $msg = site_url("Create_group/create_group/".rand(2,100));
            // print_r($msg);die();
            $msg = site_url("Create_group/activate_group/".$activation_code."/".$gid);
            $email = $this->email($to, $subject, $msg);
        }
            if (!empty($gid)) 
            {
                // echo  "yes";die();
                $this->res->success = true;
                return true;
            }
            $this->res->success = false;
            return false;

        $this->_output();
        exit();
    }
    
     function getLatLong($location) 
    {
        if (!empty($location)) 
        {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $location);
            //Send request and receive json data by address
            $geocodeFromAddr = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false&key=AIzaSyCCQzJ9DJLTRjrxLkRk6jaSrvcc5BfDtWM');
            $output = json_decode($geocodeFromAddr);
            //echo "<pre>"; print_r($output); die;
            //Get latitude and longitute from json data
            if (isset($output->results) && !empty($output->results))
             {
                $data['latitude'] = $output->results[0]->geometry->location->lat;
                $data['longitude'] = $output->results[0]->geometry->location->lng;
            }
        //Return latitude and longitude of the given address
            if (!empty($data))
             {
                return $data;
            } 
            else 
            {
                return false;
            }
        } 
        else 
        {
            return false;
        }
    }
//////////
    function email($to, $subject, $msg) 
    {
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );
        // $subject = "Group activation";
        // $body = $this->load->view('Common', $msg, TRUE);
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        // $this->email->from('info@mactosys.com', 'Pewny Parking');
        $this->email->from('info@mactosys.com', 'gettogether');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($msg);
        $this->email->send();
    }
////////////////
        function generateRandomString($length = 10) 
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) 
            {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    /////////////////////
        function activate_group($activation_code,$group_id){
        $activation_code;
        // echo "<br>";
        $group_id;
        // $group_status = $this->getSingleValue($id,'user','email_status');
        $where_login = array('id' => $group_id);
        $group_status = $this->Core_Model->selectsinglerecord('group_list', 'id,activation_code', $where_login);
        $code = $group_status->activation_code;
        // print_r($code);die();
        if ($code==$activation_code)
         {
            $group_status = 1;
            $where_update = array('id' => $group_id);
            $field_update = array('group_status'=>$group_status);
            $this->Core_Model->updateFields('group_list', $field_update, $where_update);
            // $this->res->success = true;
            // $this->res->message = "Email verified and group status has been activated.";
            //     return true;
            echo "Email verified and group status has been activated.";
        }
        else
        {
            // $this->res->success = false;
            // $this->res->message = "error and not verified.";
            //     return false;
            echo "Error and try again.";
        }
    }
        ////////////////
        function _output() 
        {
            $this->res->datetime = date('Y-m-d\TH:i:sP');
            echo json_encode($this->res);
        }
        function _error($error, $reason, $code = null) 
        {
            $this->res->success = false;
            if (isset($this->req->request))
             {
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
