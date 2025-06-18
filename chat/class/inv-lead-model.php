<?php
class Lead extends DB_connection 
{
	var $connection;
	var $lead_id;
	var $customer_name;
	var $email;
	var $contact_no;
	var $company_name;
	var $dob;
	var $ssn;
	var $res_address;
	var $billing_address;
	var $sale_amount;
	var $discount;
	var $merchant;
	var $date;
	var $payment_method;
	var $card_number;
	var $exp_month;
	var $exp_year;
	var $cvv;
	var $account_no;
	var $routing_no;
	var $checking_no;
	var $paypal_link;
	var $stripe_link;
	var $square_link;
	var $custom_payment_link;
	var $sales_person;
	var $added_date;
	var $modify_date;
	var $added_by;
	var $status;

	public function __construct()
	{
		$this->connection 	=  	new DB_connection();
		
		$this->lead_id				=	0;
		$this->customer_name		=	"";
		$this->email				=	"";
		$this->contact_no			=	"";
		$this->company_name			=	"";
		$this->dob					=	"";
		$this->ssn					=	"";
		$this->res_address			=	"";
		$this->billing_address		=	"";
		$this->sale_amount			=	0;
		$this->discount				=	0;
		$this->merchant				=	"";
		$this->date					=	"";
		$this->payment_method		=	0;
		$this->card_number			=	"";
		$this->exp_month			=	0;
		$this->exp_year				=	0;
		$this->cvv					=	0;
		$this->account_no			=	"";
		$this->routing_no			=	0;
		$this->checking_no			=	0;
		$this->paypal_link			=	"";
		$this->stripe_link			=	"";
		$this->square_link			=	"";
		$this->custom_payment_link	=	"";
		$this->sales_person			=	0;
		$this->added_date			=	"";
		$this->modify_date			=	"";
		$this->added_by				=	0;
		$this->status				=	0;
		
	}

	function insertDummyLeads($count = 50) {
		$DB = new DB_connection();
		$dummyNames = array(
			"John Doe", "Jane Smith", "Alice Johnson", "Robert Brown", "Michael Davis",
			"Emily Wilson", "David Martinez", "Sarah Anderson", "James Taylor", "Linda Thomas",
			"Daniel Jackson", "Laura White", "Matthew Harris", "Jessica Martin", "Andrew Thompson",
			"Karen Garcia", "Joshua Martinez", "Nancy Robinson", "Ryan Clark", "Betty Rodriguez",
			"Paul Lewis", "Sandra Lee", "Mark Walker", "Ashley Hall", "Steven Allen",
			"Kimberly Young", "Brian King", "Donna Wright", "Kevin Scott", "Carol Green",
			"Jason Adams", "Michelle Baker", "Jeffrey Gonzalez", "Patricia Nelson", "Eric Carter",
			"Deborah Mitchell", "Jacob Perez", "Shirley Roberts", "Gary Turner", "Amy Phillips",
			"Stephen Campbell", "Angela Parker", "Larry Evans", "Melissa Edwards", "Scott Collins",
			"Rebecca Stewart", "Brandon Sanchez", "Laura Morris", "Justin Rogers", "Rachel Reed"
		);

		for ($i = 0; $i < $count; $i++) {
			$name = $dummyNames[$i];
			$email = strtolower(str_replace(' ', '.', $name)) . "@yopmail.com";
			$contact_no = "1234567890";
			$company_name = "Dummy Company";
			$dob = "1990-01-01";
			$ssn = "123-45-6789";
			$res_address = "123 Dummy St, Dummy City, DC";
			$billing_address = "123 Dummy St, Dummy City, DC";
			$sale_amount = rand(1000, 5000);
			$discount = rand(0, 100);
			$merchant = "Dummy Merchant";
			$date = date("Y-m-d");
			$payment_method = rand(1, 3);
			$card_number = "4111111111111111";
			$exp_month = rand(1, 12);
			$exp_year = rand(2023, 2030);
			$cvv = rand(100, 999);
			$account_no = "123456789";
			$routing_no = "987654321";
			$checking_no = "123456789";
			$paypal_link = "https://paypal.me/dummy";
			$stripe_link = "https://stripe.com/dummy";
			$square_link = "https://squareup.com/dummy";
			$custom_payment_link = "https://custompayment.com/dummy";
			$added_date = date("Y-m-d H:i:s");
			$modify_date = date("Y-m-d H:i:s");
			$added_by = 1;
			$status = 1;

			$insert = "INSERT INTO `inv_qne_leads` (`customer_name`, `email`, `contact_no`, `company_name`, `dob`, `ssn`, `res_address`, `billing_address`, `sale_amount`, `discount`, `merchant`, `date`, `payment_method`, `card_number`, `exp_month`, `exp_year`, `cvv`, `account_no`, `routing_no`, `checking_no`, `paypal_link`, `stripe_link`, `square_link`, `custom_payment_link`, `sales_person`, `added_date`, `modify_date`, `added_by`, `status`) 
					   VALUES ('$name', '$email', '$contact_no', '$company_name', '$dob', '$ssn', '$res_address', '$billing_address', '$sale_amount', '$discount', '$merchant', '$date', '$payment_method', '$card_number', '$exp_month', '$exp_year', '$cvv', '$account_no', '$routing_no', '$checking_no', '$paypal_link', '$stripe_link', '$square_link', '$custom_payment_link', '$sales_person', '$added_date', '$modify_date', '$added_by', '$status')";

			$DB->query($insert);
		}
	}
    
	function getAllLeads($lead_id=0, $lead_added_by=0, $start=0, $limit=0, $order="DESC")
	{
		$DB = new DB_connection();
		$where = " WHERE 1=1 ";

		if ($lead_id != 0) {
			$where .= " AND `lead_id` = " . $lead_id;
		}

		if(is_array($lead_added_by) && sizeof($lead_added_by) > 0) // for manager's data
		{
			$where .= " AND `sales_person` IN (" . implode(",", $lead_added_by) . ")";
		} else
		if( !is_array($lead_added_by) && $lead_added_by > 0) // for sales person's data
		{
			$where .= " AND `sales_person` = " . $lead_added_by;
		}

		$order_by = " ORDER BY `lead_id` " . $order;

		$select = "SELECT l.*, u.full_name FROM `inv_qne_leads` l LEFT JOIN `inv_qne_users` u ON l.sales_person = u.user_id " . $where . $order_by;
		$conn = $DB->query($select);
		$leads = array();

		if (mysqli_num_rows($conn) > 0) {
			$c = 0;
			while ($fetch = mysqli_fetch_object($conn)) {
				$leads[$c]['lead_id'] 				= 	$fetch->lead_id;
				$leads[$c]['customer_name'] 		= 	$fetch->customer_name;
				$leads[$c]['email'] 				= 	$fetch->email;
				$leads[$c]['contact_no'] 			= 	$fetch->contact_no;
				$leads[$c]['company_name'] 			= 	$fetch->company_name;
				$leads[$c]['dob'] 					= 	$fetch->dob;
				$leads[$c]['ssn'] 					= 	$fetch->ssn;
				$leads[$c]['res_address'] 			= 	$fetch->res_address;
				$leads[$c]['billing_address'] 		= 	$fetch->billing_address;
				$leads[$c]['sale_amount'] 			= 	$fetch->sale_amount;
				$leads[$c]['discount'] 				= 	$fetch->discount;
				$leads[$c]['merchant'] 				= 	$fetch->merchant;
				$leads[$c]['date'] 					= 	$fetch->date;
				$leads[$c]['payment_method'] 		= 	$fetch->payment_method;
				$leads[$c]['card_number'] 			= 	$fetch->card_number;
				$leads[$c]['exp_month'] 			= 	$fetch->exp_month;
				$leads[$c]['exp_year'] 				= 	$fetch->exp_year;
				$leads[$c]['cvv'] 					= 	$fetch->cvv;
				$leads[$c]['account_no'] 			= 	$fetch->account_no;
				$leads[$c]['routing_no'] 			= 	$fetch->routing_no;
				$leads[$c]['checking_no'] 			= 	$fetch->checking_no;
				$leads[$c]['paypal_link'] 			= 	$fetch->paypal_link;
				$leads[$c]['stripe_link'] 			= 	$fetch->stripe_link;
				$leads[$c]['square_link'] 			= 	$fetch->square_link;
				$leads[$c]['custom_payment_link'] 	= 	$fetch->custom_payment_link;
				$leads[$c]['sales_person'] 			= 	$fetch->sales_person;
				$leads[$c]['sales_person_name'] 	= 	$fetch->full_name;
				$leads[$c]['added_date'] 			= 	$fetch->added_date;
				$leads[$c]['modify_date'] 			= 	$fetch->modify_date;
				$leads[$c]['added_by'] 				= 	$fetch->added_by;
				$leads[$c]['status'] 				= 	$fetch->status;
				$c++;
			}
			
		}
		return $leads;
	}
	
	function getLeadByID($lead_id = 0)
	{
		$DB			= 	new DB_connection();
		$where		=	"";
		 
		if($lead_id != 0)
		{
			$where	.=	" WHERE l.lead_id = " . $lead_id;
		}
		
		$select 	= 	"SELECT l.*, u.full_name, u.profile_picture, u.manager_id FROM `inv_qne_leads` l LEFT JOIN `inv_qne_users` u ON l.sales_person = u.user_id " . $where;
		$conn		= 	$DB->query($select);
		$leadInfo 	=	array();
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			
			$leadInfo['lead_id']				=	$fetch->lead_id;
			$leadInfo['customer_name']			=	$fetch->customer_name;
			$leadInfo['email']					=	$fetch->email;
			$leadInfo['contact_no']				=	$fetch->contact_no;
			$leadInfo['company_name']			=	$fetch->company_name;
			$leadInfo['dob']					=	$fetch->dob;
			$leadInfo['ssn']					=	$fetch->ssn;
			$leadInfo['res_address']			=	$fetch->res_address;
			$leadInfo['billing_address']		=	$fetch->billing_address;
			$leadInfo['sale_amount']			=	$fetch->sale_amount;
			$leadInfo['discount']				=	$fetch->discount;
			$leadInfo['merchant']				=	$fetch->merchant;
			$leadInfo['date']					=	$fetch->date;
			$leadInfo['payment_method']			=	$fetch->payment_method;
			$leadInfo['card_number']			=	$fetch->card_number;
			$leadInfo['exp_month']				=	$fetch->exp_month;
			$leadInfo['exp_year']				=	$fetch->exp_year;
			$leadInfo['cvv']					=	$fetch->cvv;
			$leadInfo['account_no']				=	$fetch->account_no;
			$leadInfo['routing_no']				=	$fetch->routing_no;
			$leadInfo['checking_no']			=	$fetch->checking_no;
			$leadInfo['paypal_link']			=	$fetch->paypal_link;
			$leadInfo['stripe_link']			=	$fetch->stripe_link;
			$leadInfo['square_link']			=	$fetch->square_link;
			$leadInfo['custom_payment_link']	=	$fetch->custom_payment_link;
			$leadInfo['sales_person']			=	$fetch->sales_person;
			$leadInfo['sales_person_name']		=	$fetch->full_name;
			$leadInfo['sales_person_picture']	=	$fetch->profile_picture;
			$leadInfo['manager_id']				=	$fetch->manager_id;
			$leadInfo['added_date']				=	$fetch->added_date;
			$leadInfo['modify_date']			=	$fetch->modify_date;
			$leadInfo['added_by']				=	$fetch->added_by;
			$leadInfo['status']					=	$fetch->status;
		}
		return $leadInfo;
	}
	
	public function addLead($post)
	{
		$DB	= new DB_connection();
		
		// Lead details
		$customer_name		=	mysqli_real_escape_string($DB->_connection, $post['customer_name']); 
		$email				=	mysqli_real_escape_string($DB->_connection, $post['email']);
		$contact_no			=	mysqli_real_escape_string($DB->_connection, $post['phone']);
		$company_name		=	mysqli_real_escape_string($DB->_connection, $post['company_name']);
		$dob				=	mysqli_real_escape_string($DB->_connection, $post['dob']);
		$ssn				=	mysqli_real_escape_string($DB->_connection, $post['ssn']);
		$res_address		=	mysqli_real_escape_string($DB->_connection, $post['res_address']);
		$billing_address	=	mysqli_real_escape_string($DB->_connection, $post['billing_address']);
		$sale_amount		=	mysqli_real_escape_string($DB->_connection, $post['sale_amount']);
		$discount			=	mysqli_real_escape_string($DB->_connection, $post['discount']);
		$merchant			=	mysqli_real_escape_string($DB->_connection, $post['merchant']);
		$date				=	mysqli_real_escape_string($DB->_connection, $post['sale_date']);
		$payment_method		=	mysqli_real_escape_string($DB->_connection, $post['payment_method']);
		$card_number		=	mysqli_real_escape_string($DB->_connection, $post['card_number']);
		$exp_month			=	mysqli_real_escape_string($DB->_connection, $post['expiry_month']);
		$exp_year			=	mysqli_real_escape_string($DB->_connection, $post['expiry_year']);
		$cvv				=	mysqli_real_escape_string($DB->_connection, $post['cvv']);
		$account_no			=	mysqli_real_escape_string($DB->_connection, $post['account_no']);
		$routing_no			=	mysqli_real_escape_string($DB->_connection, $post['routing_no']);
		$checking_no		=	mysqli_real_escape_string($DB->_connection, $post['checking_no']);
		$paypal_link		=	mysqli_real_escape_string($DB->_connection, $post['paypal']);
		$stripe_link		=	mysqli_real_escape_string($DB->_connection, $post['stripe']);
		$square_link		=	mysqli_real_escape_string($DB->_connection, $post['square']);
		$custom_payment_link=	mysqli_real_escape_string($DB->_connection, $post['custom_link']);
		$sales_person		=	mysqli_real_escape_string($DB->_connection, $post['sales_person']) ? mysqli_real_escape_string($DB->_connection, $post['sales_person']) : $_SESSION['sess_user_id'];
		$added_by			=	$_SESSION['sess_user_id'];
		
		// Insert lead record
		$insert = "INSERT INTO `inv_qne_leads` (
			`customer_name`, `email`, `contact_no`, `company_name`, `dob`, `ssn`, 
			`res_address`, `billing_address`, `sale_amount`, `discount`, `merchant`, 
			`date`, `payment_method`, `card_number`, `exp_month`, `exp_year`, `cvv`, 
			`account_no`, `routing_no`, `checking_no`, `paypal_link`, `stripe_link`, 
			`square_link`, `custom_payment_link`, `sales_person`, `added_date`, `added_by`, `status`
		) VALUES (
			'" . $customer_name . "', '" . $email . "', '" . $contact_no . "', '" . $company_name . "', '" . $dob . "', '" . $ssn . "',
			'" . $res_address . "', '" . $billing_address . "', '" . $sale_amount . "', '" . $discount . "', '" . $merchant . "',
			'" . $date . "', '" . $payment_method . "', '" . $card_number . "', '" . $exp_month . "', '" . $exp_year . "', '" . $cvv . "',
			'" . $account_no . "', '" . $routing_no . "', '" . $checking_no . "', '" . $paypal_link . "', '" . $stripe_link . "',
			'" . $square_link . "', '" . $custom_payment_link . "', '" . $sales_person . "', '" . date('Y-m-d H:i:s') . "', '" . $added_by . "', '1'
		)";
		
		if($DB->query($insert)) {
			$lead_id = mysqli_insert_id($DB->_connection);
			return $lead_id;
		} else {
			return false;
		}
	}
	
	public function editLead($lead_id, $post)
	{
		$DB = new DB_connection();
		extract($post);
		
		// Lead details
		$customer_name 		= 	mysqli_real_escape_string($DB->_connection, $customer_name);
		$email 				= 	mysqli_real_escape_string($DB->_connection, $email);
		$contact_no 		= 	mysqli_real_escape_string($DB->_connection, $contact_no);
		$company_name 		= 	mysqli_real_escape_string($DB->_connection, $company_name);
		$dob 				= 	mysqli_real_escape_string($DB->_connection, $dob);
		$ssn 				= 	mysqli_real_escape_string($DB->_connection, $ssn);
		$res_address 		= 	mysqli_real_escape_string($DB->_connection, $res_address);
		$billing_address 	= 	mysqli_real_escape_string($DB->_connection, $billing_address);
		$sale_amount 		= 	mysqli_real_escape_string($DB->_connection, $sale_amount);
		$discount 			= 	mysqli_real_escape_string($DB->_connection, $discount);
		$merchant 			= 	mysqli_real_escape_string($DB->_connection, $merchant);
		$date 				= 	mysqli_real_escape_string($DB->_connection, $date);
		$payment_method 	= 	mysqli_real_escape_string($DB->_connection, $payment_method);
		$card_number 		= 	mysqli_real_escape_string($DB->_connection, $card_number);
		$exp_month 			= 	mysqli_real_escape_string($DB->_connection, $expiry_month);
		$exp_year 			= 	mysqli_real_escape_string($DB->_connection, $expiry_year);
		$cvv 				= 	mysqli_real_escape_string($DB->_connection, $cvv);
		$account_no 		= 	mysqli_real_escape_string($DB->_connection, $account_no);
		$routing_no 		= 	mysqli_real_escape_string($DB->_connection, $routing_no);
		$checking_no 		= 	mysqli_real_escape_string($DB->_connection, $checking_no);
		$paypal_link 		= 	mysqli_real_escape_string($DB->_connection, $paypal_link);
		$stripe_link 		= 	mysqli_real_escape_string($DB->_connection, $stripe_link);
		$square_link 		= 	mysqli_real_escape_string($DB->_connection, $square_link);
		$custom_payment_link = 	mysqli_real_escape_string($DB->_connection, $custom_payment_link);
		$sales_person		=	mysqli_real_escape_string($DB->_connection, $sales_person);
		$status 			= 	mysqli_real_escape_string($DB->_connection, $status);
		
		// Update lead record
		$update = "UPDATE `inv_qne_leads` SET 
			`customer_name` = '" . $customer_name . "', 
			`email` = '" . $email . "', 
			`contact_no` = '" . $contact_no . "', 
			`company_name` = '" . $company_name . "', 
			`dob` = '" . $dob . "', 
			`ssn` = '" . $ssn . "', 
			`res_address` = '" . $res_address . "', 
			`billing_address` = '" . $billing_address . "', 
			`sale_amount` = '" . $sale_amount . "', 
			`discount` = '" . $discount . "', 
			`merchant` = '" . $merchant . "', 
			`date` = '" . $date . "', 
			`payment_method` = '" . $payment_method . "', 
			`card_number` = '" . $card_number . "', 
			`exp_month` = '" . $exp_month . "', 
			`exp_year` = '" . $exp_year . "', 
			`cvv` = '" . $cvv . "', 
			`account_no` = '" . $account_no . "', 
			`routing_no` = '" . $routing_no . "', 
			`checking_no` = '" . $checking_no . "', 
			`paypal_link` = '" . $paypal_link . "', 
			`stripe_link` = '" . $stripe_link . "', 
			`square_link` = '" . $square_link . "', 
			`custom_payment_link` = '" . $custom_payment_link . "', 
			`sales_person` = '" . $sales_person . "', 
			`status` = '" . $status . "' 
			WHERE `lead_id` = '" . $lead_id . "'";
		
		if($DB->query($update)) {
			return true;
		} else {
			return "Database error: " . mysqli_error($DB->_connection);
		}
	}

	public function deleteLead($lead_id)
	{
		$DB = new DB_connection();
		$delete = "DELETE FROM `inv_qne_leads` WHERE `lead_id` = '" . $lead_id . "'";
		$DB->query($delete);
		return true;
	}

	public function getLeadCount($user_id=0)
	{
		$DB = new DB_connection();
		$where = "";

		if(is_array($user_id) && sizeof($user_id) > 0) // for manager's data
		{
			$where = " WHERE `sales_person` IN (" . implode(",", $user_id) . ")";
		} else
		if( !is_array($user_id) && $user_id > 0) // for sales person's data
		{
			$where = " WHERE `sales_person` = " . $user_id;
		}

		$select = "SELECT COUNT(*) as leads_count FROM `inv_qne_leads`" . $where;
		$conn = $DB->query($select);	
		$fetch = mysqli_fetch_object($conn);
		$leads_count = $fetch->leads_count;
		return $leads_count;
	}

	public function getTotalSaleAmount($user_id=0)
	{
		$DB = new DB_connection();
		$where = "";

		if(is_array($user_id)) // for manager's data
		{
			$where = " WHERE `sales_person` IN (" . implode(",", $user_id) . ")";
		} else
		if( !is_array($user_id) && $user_id > 0) // for sales person's data
		{
			$where = " WHERE `sales_person` = " . $user_id;
		}

		$select = "SELECT SUM(sale_amount) as total_sale_amount FROM `inv_qne_leads`" . $where;
		$conn = $DB->query($select);
		$fetch = mysqli_fetch_object($conn);
		$total_sale_amount = $fetch->total_sale_amount;
		return $total_sale_amount;
	}

	public function getTotalDiscountAmount($user_id=0)
	{
		$DB = new DB_connection();
		$where = "";

		if(is_array($user_id)) // for manager's data
		{
			$where = " WHERE `sales_person` IN (" . implode(",", $user_id) . ")";
		} else
		if( !is_array($user_id) && $user_id > 0) // for sales person's data
		{
			$where = " WHERE `sales_person` = " . $user_id;
		}

		$select = "SELECT SUM(discount) as total_discount_amount FROM `inv_qne_leads`" . $where;
		$conn = $DB->query($select);
		$fetch = mysqli_fetch_object($conn);
		$total_discount_amount = $fetch->total_discount_amount;
		return $total_discount_amount;
	}

	public function getTotalSaleAmountByDateRange($user_id=0, $start_date, $end_date)
	{
		$DB = new DB_connection();
		$where = "";

		if(is_array($user_id)) // for manager's data
		{
			$where = " WHERE `sales_person` IN (" . implode(",", $user_id) . ")";
		} else
		if($user_id >= 0) // for sales person's data
		{
			$where = " WHERE `sales_person` = " . $user_id;
		}

		$select = "SELECT SUM(sale_amount) as total_sale_amount FROM `inv_qne_leads`" . $where . " WHERE `date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		$conn = $DB->query($select);
		$fetch = mysqli_fetch_object($conn);
		$total_sale_amount = $fetch->total_sale_amount;
		return $total_sale_amount;
	}
}
?>
