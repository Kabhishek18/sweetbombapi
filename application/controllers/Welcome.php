<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Welcome extends CI_Controller {

	  public function __construct()
        {
            parent::__construct();
            $this->objOfJwt = new CreatorJwt();
            header('Content-Type: application/json');
            $this->load->model('user_model');
        }

	
	public function index()
	{

		$auth = AuthenticateServer();

		if (!empty($auth['authuser']) && !empty($auth['authpass'])):
			
			


			$data = json_decode(file_get_contents("php://input"));

			$returnData = [];
			if($_SERVER["REQUEST_METHOD"] != "POST"):
    		$returnData = MessageAlertStatus(0,404,'Page Not Found!');
    		// CHECKING EMPTY FIELDS
			elseif(!isset($data->email) 
			    || !isset($data->password)
			    || empty(trim($data->email))
			    || empty(trim($data->password))
			    ):

			    $fields = ['fields' => ['name','password']];
			    $returnData = MessageAlertStatus(0,422,'Please Fill in all Required Fields!',$fields);

			// IF THERE ARE NO EMPTY FIELDS THEN-
			else:
			    $email = trim($data->email);
			    $password = trim($data->password);

			    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
			    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
			        $returnData = MessageAlertStatus(0,422,'Invalid Email Address!');
			    
			    // IF PASSWORD IS LESS THAN 8 THE SHOW THE ERROR
			    elseif(strlen($password) < 8):
			        $returnData = MessageAlertStatus(0,422,'Your password must be at least 8 characters long!');

			    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
			    else:
			        	$result = $this->user_model->VerifyUser($email);
			         	if ($result == 0) {
			         		$returnData =MessageAlertStatus(0,422,'Email Id Does Not Exist');
			         	}
			         	else{

			         		$getuser = $this->user_model->GetUserEmail($email);
			         		$token = $getuser['register_token'];

			         		 $returnData = [
                        'success' => 1,
                        'message' => 'You have successfully logged in.',
                        'token' => $token
                   					 ];
			         	}

			    endif;

			endif;

			echo json_encode($returnData);
		else :
			$returnData = (MessageAlertStatus(1,404,'HTTP Authorization Not done'));
			echo json_encode($returnData);
		endif;	
		



	}


	public function CreateUser()
	{

		$auth = AuthenticateServer();

		if (!empty($auth['authuser']) && !empty($auth['authpass'])):
			
			


			$data = json_decode(file_get_contents("php://input"));

			$returnData = [];
			if($_SERVER["REQUEST_METHOD"] != "POST"):
    		$returnData = MessageAlertStatus(0,404,'Page Not Found!');
    		// CHECKING EMPTY FIELDS
			elseif(!isset($data->name) 
			    || !isset($data->email) 
			    || !isset($data->password)
			    || empty(trim($data->name))
			    || empty(trim($data->email))
			    || empty(trim($data->password))
			    ):

			    $fields = ['fields' => ['name','email','password']];
			    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

			// IF THERE ARE NO EMPTY FIELDS THEN-
			else:
			    
			    $name = trim($data->name);
			    $email = trim($data->email);
			    $password = trim($data->password);

			    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
			        $returnData = MessageAlertStatus(0,422,'Invalid Email Address!');
			    
			    elseif(strlen($password) < 8):
			        $returnData = MessageAlertStatus(0,422,'Your password must be at least 8 characters long!');

			    elseif(strlen($name) < 3):
			        $returnData = MessageAlertStatus(0,422,'Your name must be at least 3 characters long!');

			    else:

			         	$result = $this->user_model->VerifyUser($email);
			        var_dump($result);
			         	if ($result >= 1) {
			         		$returnData =MessageAlertStatus(0,422,'Email Id Already Exist');
			         	}
			         	else{


			         		$tokenData['uniqueId'] = '11';
			                $tokenData['role'] = 'alamgir';
			                $tokenData['timeStamp'] = Date('Y-m-d h:i:s');
			                $jwtToken = $this->objOfJwt->GenerateToken($tokenData);
			                $data->register_token= $jwtToken;
			         		$data->date_create =$tokenData['timeStamp'];
			         		$insert = $this->user_model->InserUser($data);
			         		if($insert)
			         		{
			      			  $returnData = MessageAlertStatus(1,201,'User Inserted Successfully');

			         		}
			         		else
			         		{
			      			  $returnData = MessageAlertStatus(0,422,'Not Able To Insert Data');
			         		}
			         	}

			    endif;
			    
			endif;

			echo json_encode($returnData);
		else :
			$returnData = (MessageAlertStatus(1,404,'HTTP Authorization Not done'));
			echo json_encode($returnData);
		endif;	

	}	


	 public function LoginToken	()
        {
                $tokenData['uniqueId'] = '11';
                $tokenData['role'] = 'alamgir';
                $tokenData['timeStamp'] = Date('Y-m-d h:i:s');
                $jwtToken = $this->objOfJwt->GenerateToken($tokenData);
                echo json_encode(array('Token'=>$jwtToken));
             }
         
       /*************Use for token then fetch the data**************/
             
        public function GetTokenData()
        {
        $received_Token = $this->input->request_headers('Authorization');
            try
            {
                $jwtData = $this->objOfJwt->DecodeToken($received_Token['Token']);
                echo json_encode($jwtData);
                }
                catch (Exception $e)
                {
                http_response_code('401');
                echo json_encode(array( "status" => false, "message" => $e->getMessage()));exit;
            }
        }



    public function TestCurl($value='')
        {
        	

		if ( isset($_SERVER['PHP_AUTH_USER']) 
         && $_SERVER['PHP_AUTH_USER'] == $this->username 
         && $_SERVER['PHP_AUTH_PW'] == $this->password )
        {

        	$url ='http://dummy.restapiexample.com/api/v1/employees';
			
			//Initiate cURL.
			$ch = curl_init($url);
			 
			//Specify the username and password using the CURLOPT_USERPWD option.
			curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");  


// 			curl_setopt($ch, CURLOPT_USERPWD,  'username:password');

		    curl_setopt($ch,CURLOPT_URL,$url);
		     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10); //Timeout after 10 seconds
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			 curl_setopt($ch,CURLOPT_HEADER, true); 
		 
		    $output=curl_exec($ch);
		 
		    curl_close($ch);
		    
		    print_r($output);
		    }
        else
        {
            header('WWW-Authenticate: Basic realm="My Realm" ' );
            header('HTTP/1.0 401 Unauthorized' );
            echo 'Authentication required';
            exit;
        }
        }    
}
