<?php
class Login extends DB_connection 
{
	var $connection;
	var $user_id;
	var $user_type;
	var $username;
	var $full_name;
	var $email;
	var $password;
	var $father_name;
	var $dob;
	var $temp_password;
	var $contact_no;
	var $emergency_contact_no;
	var $address;
	var $cnic;
	var $basic_salary;
	var $commission;
	var $joining_date;
	var $work_location;
	var $manager_id;
	var $status;
	var $datetime;
	var $added_by;

	public function __construct()
	{
		$this->connection 			=  	new DB_connection();
		$this->user_id				=	0;
		$this->user_type			=	0;
		$this->username				=	"";
		$this->full_name			=	"";
		$this->email				=	"";
		$this->password				=	"";
		$this->father_name			=	"";
		$this->dob					=	"";
		$this->temp_password		=	"";
		$this->contact_no			=	"";
		$this->emergency_contact_no	=	"";
		$this->address				=	"";
		$this->cnic					=	"";
		$this->basic_salary			=	"";
		$this->commission			=	"";
		$this->joining_date			=	"";
		$this->work_location		=	"";
		$this->manager_id			=	0;
		$this->status				=	0;
		$this->datetime				=	"";
		$this->added_by				=	0;
	}
    
	function generateSessionToken()
	{
			$data['_tokenVar']     = md5(uniqid(rand(), true));
			$_SESSION['_tokenVar'] = $data['_tokenVar'];
	}
	
	public function loginUser()
	{
		$DB			= 	new DB_connection();
		extract($_POST);
		$select 	= 	"SELECT qu.*, ut.type, ut.user_rights FROM `inv_qne_users` qu LEFT JOIN `inv_qne_user_type` ut ON qu.user_type = ut.type_id WHERE (qu.username = '" . mysqli_real_escape_string($DB->_connection, $username) . "' || qu.email = '" . mysqli_real_escape_string($DB->_connection, $username) . "') AND qu.status = 1";
		
		$conn		= 	$DB->query($select);
		$userInfo 	=	array();
		
		if(mysqli_num_rows($conn) > 0)
		{ 	
			$fet	=	mysqli_fetch_object($conn);
			
			if( ($fet->password == md5($password)) || ($fet->temp_password == md5($password)) )
			{
				$userInfo['user_id'] 		= 	$fet->user_id;
				$userInfo['user_type']		=	$fet->user_type;
				$userInfo['username']		=	$fet->username;
				$userInfo['full_name']		=	$fet->full_name;
				$userInfo['email']			=	$fet->email;
				$userInfo['password']		=	$fet->password;
				$userInfo['status']			=	$fet->status;
				$userInfo['datetime']		=	$fet->datetime;
				$userInfo['type']			=	$fet->type;
				$userInfo['user_rights']	=	$fet->user_rights;
				
				$_SESSION['sess_user_id']		=	$fet->user_id;
				$_SESSION['sess_user_type']		=	$fet->user_type;
				$_SESSION['sess_type']			=	$fet->type;
				$_SESSION['sess_username']		=	$fet->username;
				$_SESSION['sess_full_name']		=	$fet->full_name;
				$_SESSION['sess_email']			=	$fet->email;
				$_SESSION['sess_user_rights']	=	$fet->user_rights;
				return array(
					'success' => true,
					'message' => 'Login successful',
					'user_info' => $userInfo
				);
			}
			else
			{
				return array(
					'success' => false,
					'message' => 'Invalid credentials',
					'user_info' => null
				);
			}
		}
		else
		{
			return array(
				'success' => false,
				'message' => 'Invalid credentials',
				'user_info' => null
			);
		}
	}	
	
	public function logoutUser()
	{
		$_SESSION['sess_user_type']	=	"";
		$_SESSION['sess_username']	=	"";
		$_SESSION['sess_f_name']	=	"";
		$_SESSION['sess_l_name']	=	"";
		$_SESSION['sess_email']		=	"";
		unset($_SESSION);

		// Destroy all session data
		$_SESSION = array();
		//session_destroy();

		// Delete all cookies
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-3600, '/');
			}
		}

	}
	
	public function registerUser()
	{
		$DB			= 	new DB_connection();
		extract($_POST);
		$select 	= 	"SELECT * FROM `inv_qne_users` WHERE `username` = '" . mysqli_real_escape_string($DB->_connection, $username) . "' || `email` = '" . mysqli_real_escape_string($DB->_connection, $email) . "'";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) <= 0)
		{
			$insert	=	"INSERT INTO `inv_qne_users` (`user_id`, `user_type`, `username`, `f_name`, `l_name`, `email`, `password`, `status`, `datetime`) 
						VALUES ('', '0', '" . mysqli_real_escape_string($DB->_connection, $username) . "', '" . mysqli_real_escape_string($DB->_connection, $f_name) . "', '" . mysqli_real_escape_string($DB->_connection, $l_name) . "', '" . mysqli_real_escape_string($DB->_connection, $email) . "', '" . md5(mysqli_real_escape_string($DB->_connection, $password)) . "', '0', '" . date('Y-m-d H:i:s') . "')";
			$DB->query($insert);
			return 1;
		}
		else
		{
			$fetch	=	mysqli_fetch_object($conn);
			
			if($fetch->username == mysqli_real_escape_string($DB->_connection, $username))
			{
				return 0;
			}
			else
			if($fetch->email == mysqli_real_escape_string($DB->_connection, $email))
			{
				return 2;
			}
			
		}
	}
	
	public function resetPassword($email)
	{
		$DB			= 	new DB_connection();
		extract($_POST);
		$select 	= 	"SELECT * FROM `inv_qne_users` WHERE `username` = '" . mysqli_real_escape_string($DB->_connection, $username) . "' || `email` = '" . mysqli_real_escape_string($DB->_connection, $email) . "'";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) <= 0)
		{
			$fetch	=	mysqli_fetch_object($conn);
			
			$emailTo	=	$fetch->email;
		
			$headers 	= 	"MIME-Version: 1.0\n";
			$headers   .= 	"Content-type: text/html; charset=iso-8859-1\n";
			$headers   .= 	"From: The Vintage Bazar Inventory <info@thevintagebazar.com>\n";
			$headers   .= 	"X-Mailer: PHP's mail() Function\n";
			
			$subject 	= 	"Password Retrieval for The Vintage Bazar Inventory";
			
			$body		=	'<table  border="0" cellpadding="20" cellspacing="0" width="100%">
                    <tr>
											<td>
												<div> 
                          <br/><p style="color: #505050; font-family: Arial; font-size: 14px; line-height: 150%; text-align: left; padding-left: 10px;">
													<br/><strong>Dear '.$fetch->f_name . ' ' . $fetch->l_name . ',</strong><br /></p>
													<p style="color: #505050; font-family: Arial; font-size: 14px; line-height: 150%; text-align: left; padding-left: 10px;">Following are your login details:<br/></p>
													<p style="color: #505050; font-family: Arial; font-size: 14px; line-height: 150%; text-align: left; padding-left: 10px;"><strong>Your Email Address is :</strong> '.$fetch->email . '<br/></p>
													<p style="color: #505050; font-family: Arial; font-size: 14px; line-height: 150%; text-align: left; padding-left: 10px;"><strong>Your Password is :</strong> ' . base64_decode($fetch->password) . '<br/></p>
													<p style="color: #505050; font-family: Arial; font-size: 14px; line-height: 150%; text-align: left; padding-left: 10px;">If you are facing any problems, please feel free to contact our customer service support team.</p>
												</div>
											</td>
										</tr>
									</table>';

			if(@mail($to,$subject,$body,$headers))
			{
				return 1;
			}
		}
		else
		{
			return 0;
		}
	}
	
	function allUserType($type_id=0)
	{
		$DB			= 	new DB_connection();
		 $where		=	"";
		 
		if($user_id != 0)
		{
			 $where	=	" WHERE `type_id` = " . $type_id;
		}
		$select 	= 	"SELECT * FROM `inv_qne_user_type`" . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$userTypes 	= 	array();
			$c			=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$userTypes[$c]				=	new Login();
				$userTypes[$c]->type_id		=	$fetch->type_id;
				$userTypes[$c]->type		=	$fetch->type;
				$userTypes[$c]->status		=	$fetch->status;
				$c++;
			}
			return $userTypes;
		}
	}	
	
	function checkUsername($username)
	{
		$DB			= 	new DB_connection();

		$select 	= 	"SELECT * FROM `inv_qne_users` WHERE `username` = '" . $username . "'";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) <= 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function checkEmail($email)
	{
		$DB			= 	new DB_connection();

		$select 	= 	"SELECT * FROM `inv_qne_users` WHERE `email` = '" . $email . "'";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) <= 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
	
	function userbyID($user_id=0)
	{
		$DB			= 	new DB_connection();
		$where		=	"";
		 
		if($user_id != 0)
		{
			$where	.=	" WHERE u.`user_id` = " . $user_id;
		}
		$select 	= 	"SELECT u.*, ut.type, m.full_name AS manager_name FROM `inv_qne_users` u LEFT JOIN inv_qne_user_type ut ON u.user_type = ut.type_id LEFT JOIN inv_qne_users m ON u.manager_id = m.user_id " . $where;
		$conn		= 	$DB->query($select);
		$userInfo 	=	array();
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			
			$userInfo['user_id']				=	$fetch->user_id;
			$userInfo['user_type']				=	$fetch->user_type;
			$userInfo['user_role']				=	$fetch->type;
			$userInfo['manager_id']				=	$fetch->manager_id;
			$userInfo['username']				=	$fetch->username;
			$userInfo['email']					=	$fetch->email;
			$userInfo['profile_picture']		=	$fetch->profile_picture;
			// $userInfo['password']			=	base64_decode($fetch->password);
			$userInfo['status']					=	$fetch->status;
			$userInfo['datetime']				=	$fetch->datetime;
			$userInfo['full_name']				=	$fetch->full_name;
			$userInfo['father_name']			=	$fetch->father_name;
			$userInfo['dob']					=	$fetch->dob;
			$userInfo['temp_password']			=	$fetch->temp_password;
			$userInfo['contact_no']				=	$fetch->contact_no;
			$userInfo['emergency_contact_no'] 	=	$fetch->emergency_contact_no;
			$userInfo['address']				=	$fetch->address;
			$userInfo['cnic']					=	$fetch->cnic;
			$userInfo['basic_salary']			=	$fetch->basic_salary;
			$userInfo['commission']				=	$fetch->commission;
			$userInfo['joining_date']			=	$fetch->joining_date;
			$userInfo['work_location']			=	$fetch->work_location;
			$userInfo['manager_id']				=	$fetch->manager_id;
			$userInfo['manager_name']			=	$fetch->manager_name;
			$userInfo['added_by']				=	$fetch->added_by;
		}
		return $userInfo;
	}
	
	function allUsers($user_id=0, $manager_id=0, $start=0, $limit=0, $order="DESC")
	{
		$DB			= 	new DB_connection();
		$where		=	" WHERE 1=1 ";
		 
		if($user_id != 0)
		{
			$where	.=	" AND u.`user_id` = " . $user_id;
		}
		if($manager_id != 0)
		{
			$where	.=	" AND u.`manager_id` = " . $manager_id;
		}
		$where	.=	" AND u.`user_id` != 1";
		
		$select 	= 	"SELECT u.*, type, m.full_name AS manager_name FROM `inv_qne_users` u LEFT JOIN inv_qne_user_type ut ON u.user_type = ut.type_id LEFT JOIN inv_qne_users m ON u.manager_id = m.user_id " . $where . " ORDER BY u.user_id " . $order;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$users 	= 	array();
			$c		=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$users[$c]['user_id']				=	$fetch->user_id;
				$users[$c]['user_type']				=	$fetch->user_type;
				$users[$c]['user_role']				=	$fetch->type;
				$users[$c]['username']				=	$fetch->username;
				$users[$c]['email']					=	$fetch->email;
				$users[$c]['profile_picture']		=	$fetch->profile_picture;
				$users[$c]['full_name']				=	$fetch->full_name;
				$users[$c]['father_name']			=	$fetch->father_name;
				$users[$c]['dob']					=	$fetch->dob;
				$users[$c]['temp_password']			=	$fetch->temp_password;
				$users[$c]['contact_no']			=	$fetch->contact_no;
				$users[$c]['emergency_contact_no'] 	=	$fetch->emergency_contact_no;
				$users[$c]['address']				=	$fetch->address;
				$users[$c]['cnic']					=	$fetch->cnic;
				$users[$c]['basic_salary']			=	$fetch->basic_salary;
				$users[$c]['commission']			=	$fetch->commission;
				$users[$c]['joining_date']			=	$fetch->joining_date;
				$users[$c]['work_location']			=	$fetch->work_location;
				$users[$c]['manager_id']			=	$fetch->manager_id;
				$users[$c]['manager_name']			=	$fetch->manager_name;
				$users[$c]['status']				=	$fetch->status;
				$users[$c]['datetime']				=	$fetch->datetime;
				$users[$c]['added_by']				=	$fetch->added_by;
				$c++;
			}
			return $users;
		}
	}
	
	function getManagers()
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_users` WHERE `user_type` = 3";
		$conn		= 	$DB->query($select);
		$managers 	= 	array();

		if(mysqli_num_rows($conn) > 0)
		{
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$managers[$c]['user_id']				=	$fetch->user_id;
				$managers[$c]['user_type']				=	$fetch->user_type;
				$managers[$c]['user_role']				=	$fetch->type;
				$managers[$c]['username']				=	$fetch->username;
				$managers[$c]['email']					=	$fetch->email;
				$managers[$c]['full_name']				=	$fetch->full_name;
				$managers[$c]['father_name']			=	$fetch->father_name;
				$managers[$c]['dob']					=	$fetch->dob;
				$managers[$c]['temp_password']			=	$fetch->temp_password;
				$managers[$c]['contact_no']				=	$fetch->contact_no;
				$managers[$c]['emergency_contact_no'] 	=	$fetch->emergency_contact_no;
				$managers[$c]['address']				=	$fetch->address;
				$managers[$c]['cnic']					=	$fetch->cnic;
				$managers[$c]['basic_salary']			=	$fetch->basic_salary;
				$managers[$c]['commission']				=	$fetch->commission;
				$managers[$c]['joining_date']			=	$fetch->joining_date;
				$managers[$c]['work_location']			=	$fetch->work_location;
				$managers[$c]['manager_id']				=	$fetch->manager_id;
				$managers[$c]['manager_name']			=	$fetch->manager_name;
				$managers[$c]['status']					=	$fetch->status;
				$managers[$c]['datetime']				=	$fetch->datetime;
				$managers[$c]['added_by']				=	$fetch->added_by;
				$c++;
			}
		}
		return $managers;
	}
	
	function addUser($post)
	{
		$DB	= new DB_connection();
		
		// Basic user info
		$full_name			=	mysqli_real_escape_string($DB->_connection, $post['full_name']); 
		$email				=	mysqli_real_escape_string($DB->_connection, $post['email']);
		$user_role			=	mysqli_real_escape_string($DB->_connection, $post['user_role']);
		$father_name		=	mysqli_real_escape_string($DB->_connection, $post['father_name']);
		$dob				=	mysqli_real_escape_string($DB->_connection, $post['dob']);
		$temp_password		=	mysqli_real_escape_string($DB->_connection, $post['temp_pass']);
		$enc_temp_password	=	md5($temp_password);
		
		// Contact details
		$contact		=	mysqli_real_escape_string($DB->_connection, $post['contact']);
		$emergency_contact=	mysqli_real_escape_string($DB->_connection, $post['emergency_contact']);
		$address		=	mysqli_real_escape_string($DB->_connection, $post['address']);
		$cnic			=	mysqli_real_escape_string($DB->_connection, $post['cnic']);
		
		// Employment details
		$basic_salary	=	mysqli_real_escape_string($DB->_connection, $post['basic_salary']);
		$commission		=	mysqli_real_escape_string($DB->_connection, $post['commission']);
		$joining_date	=	mysqli_real_escape_string($DB->_connection, $post['joining_date']);
		$work_location	=	mysqli_real_escape_string($DB->_connection, $post['work_location']); 
		$manager_id		=	mysqli_real_escape_string($DB->_connection, empty($post['manager_id']) ? '0' : $post['manager_id']);

		// Generate username from full name
		$username		= 	strtolower(str_replace(' ', '', $full_name));
		
		// Generate random password
		$password		=	substr(md5(rand()), 0, 8);
		$enc_password	=	md5($password);
		
		// Map user role to user type ID
		$role_map = array(
			'Admin' => 2,
			'Manager' => 3, 
			'Sales' => 4,
			'Billing' => 5,
			'Support' => 6
		);
		$user_type = $role_map[$user_role];
		
		// Validate email uniqueness
		if(isset($email) && !empty($email)) {
			$select = "SELECT * FROM `inv_qne_users` WHERE `email` = '" . $email . "'";
			$conn = $DB->query($select);
			
			if(mysqli_num_rows($conn) > 0) {
				//return "Error: Email ID already exists.";
				return array(
					'success' => false,
					'message' => 'Email ID already exists'
				);
			}
		}
		
		// Insert user record
		$insert = "INSERT INTO `inv_qne_users` (
			`user_type`, `username`, `full_name`, `email`, `password`, 
			`father_name`, `dob`, `temp_password`, `contact_no`, `emergency_contact_no`,
			`address`, `cnic`, `basic_salary`, `commission`,
			`joining_date`, `work_location`, `manager_id`, `status`, `datetime`, `added_by`
		) VALUES (
			'" . $user_type . "', '" . $username . "', '" . $full_name . "', '" . $email . "', '" . $enc_password . "',
			'" . $father_name . "', '" . $dob . "', '" . $enc_temp_password . "', '" . $contact . "', '" . $emergency_contact . "',	'" . $address . "', '" . $cnic . "', '" . $basic_salary . "', '" . $commission . "',
			'" . $joining_date . "', '" . $work_location . "', '" . $manager_id . "', '1', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['sess_user_id'] . "'
		)";
		
		if($DB->query($insert)) {
			// Send welcome email with login credentials
			// $subject = "Welcome to " . SITE_NAME . " - Your Login Credentials";
			// $body = "Dear " . $full_name . ",\n\n";
			// $body .= "Your account has been created. Please use the following credentials to login:\n\n";
			// $body .= "Username: " . $username . "\n";
			// $body .= "Password: " . $password . "\n\n";
			// $body .= "Please change your password after first login.\n\n";
			// $body .= "Regards,\n" . SITE_NAME . " Team";
			
			// @mail($email, $subject, $body);
			
			//return true;
			return array(
				'success' => true,
				'message' => 'User added successfully',
				'data' => array(
					'user_id' => $DB->insert_id,
					'username' => $username,
					'email' => $email,
					'full_name' => $full_name
				)
			);

		} else {
			return array(
				'success' => false,
				'message' => 'User not added'
			);
		}
	}
	
	function editUser()
	{
		$DB = new DB_connection();
		extract($_POST);
		
		// Basic user info
		$user_id = mysqli_real_escape_string($DB->_connection, $user_id);
		$full_name = mysqli_real_escape_string($DB->_connection, $full_name);
		$email = mysqli_real_escape_string($DB->_connection, $email);
		$user_role = mysqli_real_escape_string($DB->_connection, $user_role);
		$status = mysqli_real_escape_string($DB->_connection, $status);
		
		// Personal details
		$father_name = mysqli_real_escape_string($DB->_connection, $father_name);
		$dob = mysqli_real_escape_string($DB->_connection, $dob);
		
		// Contact details
		$contact = mysqli_real_escape_string($DB->_connection, $contact);
		$emergency_contact = mysqli_real_escape_string($DB->_connection, $emergency_contact);
		$address = mysqli_real_escape_string($DB->_connection, $address);
		$cnic = mysqli_real_escape_string($DB->_connection, $cnic);
		
		// Employment details
		$basic_salary = mysqli_real_escape_string($DB->_connection, $basic_salary);
		$commission = mysqli_real_escape_string($DB->_connection, $commission);
		$joining_date = mysqli_real_escape_string($DB->_connection, $joining_date);
		$work_location = mysqli_real_escape_string($DB->_connection, $work_location);
		$manager_id = mysqli_real_escape_string($DB->_connection, empty($manager_id) ? '0' : $manager_id);

		// Profile picture
		// Handle profile picture upload
		if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
			$allowed_types = array('jpg', 'jpeg', 'png');
			$file_name = $_FILES['profile_picture']['name'];
			$file_size = $_FILES['profile_picture']['size'];
			$file_tmp = $_FILES['profile_picture']['tmp_name'];
			$file_type = $_FILES['profile_picture']['type'];
			$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

			// Validate file type
			if(!in_array($file_ext, $allowed_types)) {
				return "Invalid file type. Only JPG, JPEG and PNG files are allowed.";
			}

			// Validate file size (2MB max)
			if($file_size > 2097152) {
				return "File size must be less than 2MB";
			}

			// Generate unique filename
			$new_file_name = $user_id . '_' . time() . '.' . $file_ext;
			$upload_path = dirname(dirname(__FILE__)) . '/uploads/profile_pictures/';

			// Create directory if it doesn't exist
			if(!file_exists($upload_path)) {
				mkdir($upload_path, 0777, true);
			}

			// Move uploaded file
			if(move_uploaded_file($file_tmp, $upload_path . $new_file_name)) {
				// Delete old profile picture if exists
				$old_pic_query = "SELECT profile_picture FROM inv_qne_users WHERE user_id = '" . $user_id . "'";
				$old_pic_result = $DB->query($old_pic_query);
				if($old_pic_result && mysqli_num_rows($old_pic_result) > 0) {
					$old_pic = mysqli_fetch_object($old_pic_result)->profile_picture;
					if($old_pic && file_exists($upload_path . $old_pic)) {
						unlink($upload_path . $old_pic);
					}
				}

				// Update database with new filename
				$update_pic = "UPDATE inv_qne_users SET profile_picture = '" . $new_file_name . "' WHERE user_id = '" . $user_id . "'";
				$DB->query($update_pic);
			} else {
				return "Failed to upload profile picture";
			}
		}
		
		// Password handling
		$reset_password = isset($reset_password) ? mysqli_real_escape_string($DB->_connection, $reset_password) : '';
		
		// Map user role to user type ID
		$role_map = array(
			'Admin' => 2,
			'Manager' => 3, 
			'Sales' => 4,
			'Billing' => 5,
			'Support' => 6
		);
		$user_type = $role_map[$user_role];
		
		// Check if email already exists for another user
		if(isset($email) && !empty($email)) {
			$select = "SELECT * FROM `inv_qne_users` WHERE `email` = '" . $email . "' AND `user_id` != " . $user_id;
			$conn = $DB->query($select);
			
			if(mysqli_num_rows($conn) > 0) {
				return "Email ID already exists for another user";
			}
		}
		
		// Update password if provided
		if(!empty($reset_password)) {
			$enc_password = md5($reset_password);
			$update_password = "UPDATE `inv_qne_users` SET `password` = '" . $enc_password . "' WHERE `user_id` = '" . $user_id . "'";
			$DB->query($update_password);
		}
		
		// Update user record
		$update = "UPDATE `inv_qne_users` SET 
			`user_type` = '" . $user_type . "', 
			`full_name` = '" . $full_name . "', 
			`email` = '" . $email . "', 
			`father_name` = '" . $father_name . "', 
			`dob` = '" . $dob . "', 
			`contact_no` = '" . $contact . "', 
			`emergency_contact_no` = '" . $emergency_contact . "', 
			`address` = '" . $address . "', 
			`cnic` = '" . $cnic . "', 
			`basic_salary` = '" . $basic_salary . "', 
			`commission` = '" . $commission . "', 
			`joining_date` = '" . $joining_date . "', 
			`work_location` = '" . $work_location . "', 
			`manager_id` = '" . $manager_id . "', 
			`status` = '" . $status . "',
			`updated_by` = '" . $_SESSION['sess_user_id'] . "'
			WHERE `user_id` = '" . $user_id . "'";
		
		if($DB->query($update)) {
			return true;
		} else {
			return "Database error: " . mysqli_error($DB->_connection);
		}
	}

	function editProfile()
	{
		$DB = new DB_connection();
		extract($_POST);
		
		// Basic user info
		$user_id = mysqli_real_escape_string($DB->_connection, $user_id);
		$full_name = mysqli_real_escape_string($DB->_connection, $full_name);
		
		
		// Personal details
		$father_name = mysqli_real_escape_string($DB->_connection, $father_name);
		$dob = mysqli_real_escape_string($DB->_connection, $dob);

		// Profile picture
		// Handle profile picture upload
		if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
			$allowed_types = array('jpg', 'jpeg', 'png');
			$file_name = $_FILES['profile_picture']['name'];
			$file_size = $_FILES['profile_picture']['size'];
			$file_tmp = $_FILES['profile_picture']['tmp_name'];
			$file_type = $_FILES['profile_picture']['type'];
			$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

			// Validate file type
			if(!in_array($file_ext, $allowed_types)) {
				return "Invalid file type. Only JPG, JPEG and PNG files are allowed.";
			}

			// Validate file size (2MB max)
			if($file_size > 2097152) {
				return "File size must be less than 2MB";
			}

			// Generate unique filename
			$new_file_name = $user_id . '_' . time() . '.' . $file_ext;
			$upload_path = dirname(dirname(__FILE__)) . '/uploads/profile_pictures/';

			// Create directory if it doesn't exist
			if(!file_exists($upload_path)) {
				mkdir($upload_path, 0777, true);
			}

			// Move uploaded file
			if(move_uploaded_file($file_tmp, $upload_path . $new_file_name)) {
				// Delete old profile picture if exists
				$old_pic_query = "SELECT profile_picture FROM inv_qne_users WHERE user_id = '" . $user_id . "'";
				$old_pic_result = $DB->query($old_pic_query);
				if($old_pic_result && mysqli_num_rows($old_pic_result) > 0) {
					$old_pic = mysqli_fetch_object($old_pic_result)->profile_picture;
					if($old_pic && file_exists($upload_path . $old_pic)) {
						unlink($upload_path . $old_pic);
					}
				}

				// Update database with new filename
				$update_pic = "UPDATE inv_qne_users SET profile_picture = '" . $new_file_name . "' WHERE user_id = '" . $user_id . "'";
				$DB->query($update_pic);
			} else {
				return "Failed to upload profile picture";
			}
		}
		
		// Update user record
		$update = "UPDATE `inv_qne_users` SET 
			`full_name` = '" . $full_name . "', 
			`father_name` = '" . $father_name . "', 
			`dob` = '" . $dob . "'
			WHERE `user_id` = '" . $user_id . "'";
		
		if($DB->query($update)) {
			return true;
		} else {
			return "Database error: " . mysqli_error($DB->_connection);
		}
	}

	function editSuperAdminProfile($adminID)
	{
		$DB = new DB_connection();
		extract($_POST);
		
		// Basic user info
		$user_id = mysqli_real_escape_string($DB->_connection, $adminID);
		$full_name = mysqli_real_escape_string($DB->_connection, $full_name);
		$email = mysqli_real_escape_string($DB->_connection, $email);
		$address = mysqli_real_escape_string($DB->_connection, $address);
		$user_type = 1;
		$status = 1;

		// Check if email already exists for another user
		if(isset($email) && !empty($email)) {
			$select = "SELECT * FROM `inv_qne_users` WHERE `email` = '" . $email . "' AND `user_id` != " . $user_id;
			$conn = $DB->query($select);
			
			if(mysqli_num_rows($conn) > 0) {
				return "Email ID already exists for another user";
			}
		}
		
		// Update user record
		$update = "UPDATE `inv_qne_users` SET 
			`user_type` = '" . $user_type . "', 
			`full_name` = '" . $full_name . "', 
			`email` = '" . $email . "',
			`address` = '" . $address . "',
			`status` = '" . $status . "' 
			WHERE `user_id` = '" . $user_id . "'";
		
		if($DB->query($update)) {
			return true;
		} else {
			return "Database error: " . mysqli_error($DB->_connection);
		}
	}
	
	function changeProfilePassword($user_id=0)
	{
		$DB = new DB_connection();
		extract($_POST);
		
		$old_password = mysqli_real_escape_string($DB->_connection, $old_password);
		$new_password = mysqli_real_escape_string($DB->_connection, $new_password);
		$confirm_password = mysqli_real_escape_string($DB->_connection, $confirm_password);

		// Check if new password and confirm password match
		if($new_password !== $confirm_password) {
			return "Error: New password and confirm password do not match.";
		}
		
		$select = "SELECT * FROM `inv_qne_users` WHERE `user_id` = '" . $user_id . "'";
		$conn = $DB->query($select);
		$fetch = mysqli_fetch_object($conn);
		
		if($fetch->password != md5($old_password))
		{
			return "Error: Current password is incorrect.";
		} else {
			
			$update = "UPDATE `inv_qne_users` SET `password` = '" . md5($new_password) . "' WHERE `user_id` = '" . $user_id . "'";
			if($DB->query($update))
			{
				return "Success: Password updated successfully.";
			} else {
				return "Error: Password update failed.";
			}

		}
	}

	public function changeUserPassword($user_id, $post)
	{
	    $DB				= 	new DB_connection();
		$password		=	mysqli_real_escape_string($DB->_connection, $post['password']);
		$re_password	=	mysqli_real_escape_string($DB->_connection, $post['re_password']);
		$enc_password	=	md5($password);
		
		if( isset($password) && !empty($password) && $password != $re_password )
		{
		    return "Error: Passwords miss match.";
		}
		
		$update 	= 	"UPDATE `inv_qne_users` SET `password` = '" . $user_type . "' WHERE `user_id` = '" . $user_id . "'";
		if($DB->query($update))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getTotalUsers($manager_id=0)
	{
		$DB = new DB_connection();
		$where = "";
		if($manager_id != 0)
		{
			$where = " AND `manager_id` = " . $manager_id;
		}

		$select = "SELECT COUNT(*) as total_users FROM `inv_qne_users` WHERE `user_type` != 1 AND `user_type` != 2" . $where;
		$conn = $DB->query($select);
		$fetch = mysqli_fetch_object($conn);	
		$total_users = $fetch->total_users;
		return $total_users;
	}

	/*public function allCategory($parent_id=0)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_category` WHERE parent_id = " . $parent_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$categories 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$categories[$c]					=	new General();
				$categories[$c]->category_id	=	$fetch->category_id;
				$categories[$c]->parent_id		=	$fetch->parent_id;
				$categories[$c]->title			=	$fetch->title;
				$categories[$c]->url_title		=	$fetch->url_title;
				$categories[$c]->des			=	$fetch->des;
				$categories[$c]->sort_order		=	$fetch->sort_order;
				$categories[$c]->seotitle		=	$fetch->seotitle;
				$categories[$c]->metatags		=	$fetch->metatags;
				$categories[$c]->metadesc		=	$fetch->metadesc;
				$categories[$c]->status			=	$fetch->status;
				$categories[$c]->datetime		=	$fetch->datetime;
				$c++;
			}
			return $categories;
		}
	}*/

	function listChats() {
		try {
			$DB = new DB_connection();
			
			$userId = $_SESSION['sess_user_id'];

			$isAdmin = $_SESSION['sess_user_rights'] === 'admin' || $_SESSION['sess_user_rights'] === 'sub_admin';

			$select = 'SELECT chats.id, chats.sender_id, chats.receiver_id, chats.last_message, chats.created_at, sender.user_id AS sender_id, sender.full_name AS sender_name, sender.profile_picture AS sender_picture, receiver.user_id AS receiver_id, receiver.full_name AS receiver_name, receiver.profile_picture AS receiver_picture
			FROM chats 
			JOIN inv_qne_users sender 
			ON sender.user_id = chats.sender_id 
			JOIN inv_qne_users receiver 
			ON receiver.user_id = chats.receiver_id
			' . (!$isAdmin ? 'WHERE chats.sender_id = ' . $userId . ' OR chats.receiver_id = ' . $userId : '') . '
			ORDER BY chats.created_at DESC
			LIMIT 10';

			$chats = $DB->query($select);

			if (mysqli_num_rows($chats) > 0) {
				$html = '';

				while($chat = mysqli_fetch_object($chats)) {
					$displayName = $isAdmin
					? htmlspecialchars($chat->sender_name . ' ↔ ' . $chat->receiver_name)
					: htmlspecialchars($chat->sender_id == $userId ? $chat->receiver_name : $chat->sender_name);

					$profilePic = !empty($chat->sender_id == $userId ? $chat->receiver_picture : $chat->sender_picture)
					? str_replace('assets/' , '', ASSETS) . 'uploads/profile_pictures/' . ($chat->sender_id == $userId ? $chat->receiver_picture : $chat->sender_picture)
					: ASSETS . 'media/avatars/blank.png';

					$html .= '
						<div class="chat-user" data-sender-id="' . $chat->sender_id . '" data-receiver-id="' . $chat->receiver_id . '" data-user-id="' . $chat->user_id . '" data-user-name="' . htmlspecialchars($displayName) . '" data-user-image="' . $profilePic . '" data-chat-id="' . $chat->id . '" onClick="openChat(this)">
							<img src="' . $profilePic . '" class="avatar" />
							<div class="chat-info">
								<span class="chat-name">' . $displayName . '</span>
								<span class="chat-preview">'. substr($chat->last_message, 0, 30) .'....</span>
								<span class="chat-time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</span>
							</div>
						</div>
					';
				}

				return [
					'success' => true,
					'message' => 'Chats found successfully.',
					'data' => $html
				];
			}

			return [
				'success' => false,
				'message' => 'No Chats not found.',
				'data' => '<li class="list-group-item chat-user">No chats found</li>'
			];
		} catch (Exception $e) {
			return [
				'success' => false,
				'message' => $e->getMessage(),
				'data' => '<li class="list-group-item chat-user">No chats found</li>'
			];
		}
	}

	function searchUsers($search) {
    try {
			$DB = new DB_connection();
			
			$search = isset($search) ? mysqli_real_escape_string($DB->_connection, $search) : '';
			$userId = $_SESSION['sess_user_id'];
			$isAdmin = $_SESSION['sess_user_rights'] === 'admin' || $_SESSION['sess_user_rights'] === 'sub_admin';

			// First get matching users (excluding current user)
			$userQuery = "SELECT user_id, full_name, profile_picture 
			FROM inv_qne_users 
			WHERE status = 1 
			AND full_name LIKE '%$search%'
			AND user_id != '$userId'";
        
			$users = $DB->query($userQuery);
			$matchingUserIds = [];
			$userResults = [];

			while($user = mysqli_fetch_object($users)) {
				$matchingUserIds[] = $user->user_id;

				$userResults[$user->user_id] = $user;
			}

			if (empty($matchingUserIds)) {
				return [
					'success' => false,
					'message' => 'No matching users found',
					'data' => '<li class="list-group-item chat-user">No matching users found</li>'
				];
			}

			// Build the chat query based on user role
			$chatQuery = 'SELECT c.id, c.sender_id, c.receiver_id, c.last_message, c.created_at, s.user_id AS sender_id, s.full_name AS sender_name, s.profile_picture AS sender_picture, r.user_id AS receiver_id, r.full_name AS receiver_name, r.profile_picture AS receiver_picture
			FROM chats c
			JOIN inv_qne_users s ON s.user_id = c.sender_id
			JOIN inv_qne_users r ON r.user_id = c.receiver_id
			WHERE ';

			if ($isAdmin) {
				// Admin sees all chats involving matching users
				$chatQuery .= "(c.sender_id IN (" . implode(',', $matchingUserIds) . ") 
				OR c.receiver_id IN (" . implode(',', $matchingUserIds) . "))";
			} else {
				// Non-admin only sees chats between themselves and matching users
				$chatQuery .= "((c.sender_id = '$userId' AND c.receiver_id IN (" . implode(',', $matchingUserIds) . "))
				OR (c.receiver_id = '$userId' AND c.sender_id IN (" . implode(',', $matchingUserIds) . ")))";
			}

			$chatQuery .= ' ORDER BY c.created_at DESC LIMIT 50';
			$chats = $DB->query($chatQuery);

			$html = '';
			$processedChats = [];

			// 1. First display existing chats
			if (mysqli_num_rows($chats) > 0) {
				while($chat = mysqli_fetch_object($chats)) {
					$processedChats[] = $chat->id;
					
					$displayName = $isAdmin
					? htmlspecialchars($chat->sender_name . ' ↔ ' . $chat->receiver_name)
					: htmlspecialchars($chat->sender_id == $userId ? $chat->receiver_name : $chat->sender_name);

					$profilePic = !empty($chat->sender_id == $userId ? $chat->receiver_picture : $chat->sender_picture)
					? str_replace('assets/', '', ASSETS) . 'uploads/profile_pictures/' . 
						($chat->sender_id == $userId ? $chat->receiver_picture : $chat->sender_picture)
					: ASSETS . 'media/avatars/blank.png';

					$otherUserId = ($chat->sender_id == $userId) ? $chat->receiver_id : $chat->sender_id;

					$html .= '
						<div class="chat-user" data-sender-id="' . $chat->sender_id . '" data-receiver-id="' . $chat->receiver_id . '" data-user-id="' . $chat->user_id . '" data-user-name="' . htmlspecialchars($displayName) . '" data-user-image="' . $profilePic . '" data-chat-id="' . $chat->id . '" onClick="openChat(this)">
							<img src="' . $profilePic . '" class="avatar" />
							<div class="chat-info">
								<span class="chat-name">' . $displayName . '</span>
								<span class="chat-preview">'. substr($chat->last_message, 0, 30) .'....</span>
								<span class="chat-time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</span>
							</div>
						</div>
					';
				}
			}

			// 2. Then display matching users without existing chats
			foreach ($userResults as $user) {
				// Skip if this user already appears in chat results
				if (in_array($user->user_id, $processedChats)) continue;
				
				$displayName = htmlspecialchars($user->full_name);

				$profilePic = !empty($user->profile_picture)
				? str_replace('assets/', '', ASSETS) . 'uploads/profile_pictures/' . $user->profile_picture
				: ASSETS . 'media/avatars/blank.png';

				// For non-admins, show potential new chat partners
				if (!$isAdmin) {
					$html .= '
						<div class="chat-user" data-sender-id="' . $userId . '" data-receiver-id="' . $user->user_id . '" data-user-id="' . $user->user_id . '" data-user-name="' . htmlspecialchars($displayName) . '" data-user-image="' . $profilePic . '" data-chat-id="' . $chat->id . '" onClick="openChat(this)">
							<img src="' . $profilePic . '" class="avatar" />
							<div class="chat-info">
								<span class="chat-name">' . $displayName . '</span>
								<span class="chat-preview"></span>
								<span class="chat-time"></span>
							</div>
						</div>
					';
				} else { // For admins, show all matching users (they can see all possible chats)
					$html .= '
						<div class="chat-user" data-sender-id="' . $userId . '" data-receiver-id="' . $user->user_id . '" data-user-id="' . $user->user_id . '" data-user-name="' . htmlspecialchars($displayName) . '" data-user-image="' . $profilePic . '" data-chat-id="' . $chat->id . '" onClick="openChat(this)">
							<img src="' . $profilePic . '" class="avatar" />
							<div class="chat-info">
								<span class="chat-name">' . $displayName . '</span>
								<span class="chat-preview"></span>
								<span class="chat-time"></span>
							</div>
						</div>
					';
				}
			}

			return [
				'success' => true,
				'message' => 'Search results retrieved successfully',
				'data' => $html ?: '<li class="list-group-item chat-user">No results found</li>'
			];
    } catch (Exception $e) {
			return [
				'success' => false,
				'message' => $e->getMessage(),
				'data' => '<li class="list-group-item chat-user">Error searching users</li>'
			];
    }
	}

	function singleChat($chatId) {
		try {
			$DB = new DB_connection();
			
			$userId = $_SESSION['sess_user_id'];

			$isAdmin = $_SESSION['sess_user_rights'] === 'admin' || $_SESSION['sess_user_rights'] === 'sub_admin';

			$select = 'SELECT chat_messages.id as chat_message_id, chat_messages.chat_id, chat_messages.sender_id, chat_messages.message, chat_messages.is_file, chat_messages.created_at
			FROM chat_messages
			LEFT JOIN chats 
				ON chat_messages.chat_id = chats.id
			WHERE chat_messages.chat_id = ' . $chatId .'
			AND (' . ($isAdmin ? '1=1' : '(chats.sender_id = ' . $userId . ' OR chats.receiver_id = ' . $userId . ')') . ')
			ORDER BY chat_messages.created_at ASC';

			$chats = $DB->query($select);

			if (mysqli_num_rows($chats) > 0) {
				$html = '';

				$lastMessageId = null;

				while($chat = mysqli_fetch_object($chats)) {
					if ($chat->sender_id == $userId && !$chat->is_file) {
						$html .= '
							<div class="message sent">
								<div class="text">' . $chat->message . '</div>
								<div class="time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</div>
							</div>
						';
					} else if ($chat->sender_id != $userId && !$chat->is_file) {
						$html .= '
							<div class="message received">
								<div class="text">' . $chat->message . '</div>
								<div class="time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</div>
							</div>
						';
					} else if ($chat->sender_id == $userId && $chat->is_file) {
						$html .= '
							<div class="message sent">
								<div class="text">
									<i class="bi bi-paperclip me-1"></i>
									<a href="' . str_replace('assets/', '', ASSETS) . 'uploads/chat_files/' . $chat->message . '" target="_blank" class="text-white text-decoration-underline">' . $chat->message . '</a>
								</div>
								<div class="time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</div>
							</div>
						';
					} else if ($chat->sender_id != $userId && $chat->is_file) {
						$html .= '
							<div class="message received">
								<div class="text">
									<i class="bi bi-paperclip me-1"></i>
									<a href="' . str_replace('assets/', '', ASSETS) . 'uploads/chat_files/' . $chat->message . '" target="_blank" class="text-black text-decoration-underline">' . $chat->message . '</a>
								</div>
								<div class="time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</div>
							</div>
						';
					}

					$lastMessageId = $chat->chat_message_id;
				}

				return [
					'success' => true,
					'message' => 'Chats found successfully.',
					'data' => [
						'html' => $html,
						'last_message_id' => $lastMessageId
					]
				];
			}

			return [
				'success' => false,
				'message' => 'No Chats not found.',
				'data' => [
					'html' => '',
					'last_message_id' => null
				]
			];
		} catch (Exception $e) {
			return [
				'success' => false,
				'message' => $e->getMessage(),
				'data' => [
					'html' => '',
					'last_message_id' => null
				]
			];
		}
	}

	function sendMessage($post) {
		try {
			$DB = new DB_connection();
		
			$currentUserId = mysqli_real_escape_string($DB->_connection, $_SESSION['sess_user_id']);
			$senderId = mysqli_real_escape_string($DB->_connection, $post['sender_id']);
			$receiverId = mysqli_real_escape_string($DB->_connection, $post['receiver_id']);
			$message = mysqli_real_escape_string($DB->_connection, $post['message']);
			$createdAt = date('Y-m-d H:i:s');
			$isFile = 0;

			if (!empty($_FILES['file']['name'])) {
				$uploadPath = dirname(__FILE__) . '/../uploads/chat_files/';

				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777, true);
				}

				$originalName = $_FILES['file']['name'];
				$tmpName = $_FILES['file']['tmp_name'];
				$fileSize = $_FILES['file']['size'];

				if ($fileSize <= 10485760) {
					$ext = pathinfo($originalName, PATHINFO_EXTENSION);
					$uniqueName = uniqid() . '.' . $ext;

					if (move_uploaded_file($tmpName, $uploadPath . $uniqueName)) {
						$message = $uniqueName;

						$isFile = 1;
					}
				} else {
					return [
						'success' => false,
						'message' => 'File size should be less than or equals to 10mb.'
					];
				}
			}

			//Check if chat already exists
			$chatQuery = 'SELECT id, is_deleted FROM chats 
				WHERE (sender_id = ' . $senderId . ' AND receiver_id = ' . $receiverId . ') 
				OR (sender_id = ' . $receiverId . ' AND receiver_id = ' . $senderId . ')
				LIMIT 1
			';
			$chatResult = $DB->query($chatQuery);
		
			if ($chatResult && $chatResult->num_rows > 0) {
				$chatRow = $chatResult->fetch_assoc();

				$chatId = $chatRow['id'];

				//Check if chat is deleted
				if ($chatRow['is_deleted'] == 1) {
					$deleteMessages = 'DELETE FROM chat_messages 
					WHERE chat_id = ' . $chatId . '';

					if (!$DB->query($deleteMessages)) {
						return [
							'success' => false,
							'message' => 'Failed to update chat.'
						];
					}
				}
		
				//Update last message
				$updateChat = "UPDATE chats SET last_message = '$message', is_deleted = 0, created_at = '$createdAt' WHERE id = '$chatId'";
				
				if (!$DB->query($updateChat)) {
					return [
						'success' => false,
						'message' => 'Failed to update chat.'
					];
				}
			} else {
				//Create new chat
				$insertChat = "INSERT INTO chats (sender_id, receiver_id, last_message, created_at) VALUES ('$currentUserId', '$receiverId', '$message', '$createdAt')";

				if (!$DB->query($insertChat)) {
					return [
						'success' => false,
						'message' => 'Failed to create new chat.'
					];
				}

				$chatId = $DB->_connection->insert_id;
			}
			
			//Insert the message into chat_messages
			$insertMsg = "INSERT INTO chat_messages (chat_id, sender_id, message, is_file, is_read, created_at) VALUES ('$chatId', '$currentUserId', '$message', '$isFile', 0, '$createdAt')";

			if (!$DB->query($insertMsg)) {
				return [
					'success' => false,
					'message' => 'Message not sent.'
				];
			}

			$messageId = $DB->_connection->insert_id;

			if ($post['message']) {
				$html = '
					<div class="message sent">
						<div class="text">' . $message . '</div>
						<div class="time">' . date('D F Y - h:i A', strtotime($createdAt)) . '</div>
					</div>
				';
			} else {
				$html = '
					<div class="message sent">
						<div class="text">
							<i class="bi bi-paperclip me-1"></i>
							<a href="' . str_replace('assets/', '', ASSETS) . 'uploads/chat_files/' . $message . '" target="_blank" class="text-black text-decoration-underline">' . $message . '</a>
						</div>
						<div class="time">' . date('D F Y - h:i A', strtotime($createdAt)) . '</div>
					</div>
				';
			}
			
			return [
				'success' => true,
				'message' => 'Message sent successfully.',
				'data' => [
					'chat_id' => $chatId,
					'message_id' => $messageId,
					'sender_id' => $currentUserId,
					'receiver_id' => $receiverId,
					'message' => $message,
					'html' => $html
				]
			];
		} catch (Exception $e) {
			return [
				'success' => false,
				'message' => $e->getMessage()
			];
		}
	}

	function lastChatMessage($chatId) {
		try {
			$DB = new DB_connection();
			
			$userId = $_SESSION['sess_user_id'];

			$isAdmin = $_SESSION['sess_user_rights'] === 'admin' || $_SESSION['sess_user_rights'] === 'sub_admin';

			$select = 'SELECT chat_messages.id as chat_message_id, chat_messages.chat_id, chat_messages.sender_id, chat_messages.message, chat_messages.is_file, chat_messages.created_at
				FROM chat_messages
				LEFT JOIN chats 
					ON chat_messages.chat_id = chats.id
				WHERE chat_messages.chat_id = ' . $chatId .'
				AND (' . ($isAdmin ? '1=1' : '(chats.sender_id = ' . $userId . ' OR chats.receiver_id = ' . $userId . ')') . ')
				ORDER BY chat_messages.created_at DESC
				LIMIT 1;
			';

			$chat = $DB->query($select);

			if ($chat) {
				$chat = mysqli_fetch_object($chat);

				$isSelf = $chat->sender_id == $userId;

				if (!$chat->is_file) {
					$html = '
						<div class="message ' . ($isSelf ? 'sent' : 'received') . '">
							<div class="text">' . $chat->message . '</div>
							<div class="time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</div>
						</div>
					';
				} else {
					$html = '
						<div class="message ' . ($isSelf ? 'sent' : 'received') . '">
							<div class="text">
								<i class="bi bi-paperclip me-1"></i>
								<a href="' . str_replace('assets/', '', ASSETS) . 'uploads/chat_files/' . $chat->message . '" target="_blank" class="text-black text-decoration-underline">' . $chat->message . '</a>
							</div>
							<div class="time">' . date('D F Y - h:i A', strtotime($chat->created_at)) . '</div>
						</div>
					';
				}
	
				return [
					'success' => true,
					'message' => 'Last message retrieved successfully.',
					'data' => [
						'html' => $html,
						'message' => $chat->message,
						'last_message_id' => $chat->chat_message_id
					]
				];
			}

			return [
				'success' => false,
				'message' => 'No Chat not found.',
				'data' => [
					'html' => '',
					'last_message_id' => null
				]
			];
		} catch (Exception $e) {
			return [
				'success' => false,
				'message' => $e->getMessage(),
				'data' => [
					'html' => '',
					'last_message_id' => null
				]
			];
		}
	}

	function deleteChat($post) {
		try {
			$DB = new DB_connection();
		
			$currentUserId = mysqli_real_escape_string($DB->_connection, $_SESSION['sess_user_id']);
			$senderId = mysqli_real_escape_string($DB->_connection, $_SESSION['sender_id']);
			$receiverId = mysqli_real_escape_string($DB->_connection, $post['receiver_id']);
			$chatId = mysqli_real_escape_string($DB->_connection, $post['chat_id']);
			$message = 'Chat has been deleted by ' . $_SESSION['sess_full_name'];
			$createdAt = date('Y-m-d H:i:s');
			
			$isAdmin = $_SESSION['sess_user_rights'] === 'admin' || $_SESSION['sess_user_rights'] === 'sub_admin';
			
			//Check if chat already exists
			$select = 'SELECT id 
			FROM chats 
			WHERE id = ' . $chatId . '
			AND (' . ($isAdmin ? '1=1' : '(chats.sender_id = ' . $currentUserId . ' OR chats.receiver_id = ' . $currentUserId . ')') . ')
			';
			
			$chatResult = $DB->query($select);
		
			if (!$chatResult && $chatResult->num_rows < 1) {
				return [
					'success' => false,
					'message' => 'No chat found.'
				];
			}

			//Update last message
			$updateChat = "UPDATE chats SET last_message = '$message', is_deleted = 1, created_at = '$createdAt' WHERE id = '$chatId'";
				
			if (!$DB->query($updateChat)) {
				return [
					'success' => false,
					'message' => 'Failed to delete chat.'
				];
			}

			$deleteMessages = 'DELETE FROM chat_messages 
			WHERE chat_id = ' . $chatId . '';

			if (!$DB->query($deleteMessages)) {
				return [
					'success' => false,
					'message' => 'Failed to delete chat.'
				];
			}
			
			//Insert the message into chat_messages
			$insertMsg = "INSERT INTO chat_messages (chat_id, sender_id, message, is_file, is_read, created_at) VALUES ('$chatId', '$currentUserId', '$message', 0, 0, '$createdAt')";

			if (!$DB->query($insertMsg)) {
				return [
					'success' => false,
					'message' => 'Message not sent.'
				];
			}

			$messageId = $DB->_connection->insert_id;

			$html = '
				<div class="message deleted">
					<div class="text">' . $message . '</div>
					<div class="time">' . date('D F Y - h:i A', strtotime($createdAt)) . '</div>
				</div>
			';
			
			return [
				'success' => true,
				'message' => 'Chat deleted successfully.',
				'data' => [
					'chat_id' => $chatId,
					'message_id' => $messageId,
					'sender_id' => $currentUserId,
					'receiver_id' => $receiverId,
					'message' => $message,
					'html' => $html
				]
			];
		} catch (Exception $e) {
			return [
				'success' => false,
				'message' => $e->getMessage()
			];
		}
	}
}
?>