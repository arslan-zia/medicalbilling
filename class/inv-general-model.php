<?php
class General extends DB_connection 
{
	var $connection;
	var $designation_id;

	public function __construct()
	{
		$this->connection =  new DB_connection();
		$this->designation_id	=	0;
		$this->contact_id		=	0;
		$this->brand_id			=	0;
		$this->distributor_id	=	0;
	}

	public function allCategory($parent_id=0)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `category`";// WHERE parent_id = " . $parent_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$categories 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$categories[$c]					=	new General();
				$categories[$c]->category_id	=	$fetch->category_id;
				//$categories[$c]->parent_id		=	$fetch->parent_id;
				$categories[$c]->title			=	$fetch->title;
				$categories[$c]->url_title		=	$fetch->url_title;
				/*$categories[$c]->des			=	$fetch->des;
				$categories[$c]->sort_order		=	$fetch->sort_order;
				$categories[$c]->seotitle		=	$fetch->seotitle;
				$categories[$c]->metatags		=	$fetch->metatags;
				$categories[$c]->metadesc		=	$fetch->metadesc;
				$categories[$c]->status			=	$fetch->status;
				$categories[$c]->datetime		=	$fetch->datetime;*/
				$c++;
			}
			return $categories;
		}
	}

	public function getCountryCodes()
	{
		$country_code = array(
			"+93" => "🇦🇫 +93",
			"+355" => "🇦🇱 +355",
			"+213" => "🇩🇿 +213",
			"+1684" => "🇦🇸 +1684",
			"+376" => "🇦🇩 +376",
			"+244" => "🇦🇴 +244",
			"+1264" => "🇦🇮 +1264",
			"+672" => "🇦🇶 +672",
			"+1268" => "🇦🇬 +1268",
			"+54" => "🇦🇷 +54",
			"+374" => "🇦🇲 +374",
			"+297" => "🇦🇼 +297",
			"+61" => "🇦🇺 +61",
			"+43" => "🇦🇹 +43",
			"+994" => "🇦🇿 +994",
			"+1242" => "🇧🇸 +1242",
			"+973" => "🇧🇭 +973",
			"+880" => "🇧🇩 +880",
			"+1246" => "🇧🇧 +1246",
			"+375" => "🇧🇾 +375",
			"+32" => "🇧🇪 +32",
			"+501" => "🇧🇿 +501",
			"+229" => "🇧🇯 +229",
			"+1441" => "🇧🇲 +1441",
			"+975" => "🇧🇹 +975",
			"+591" => "🇧🇴 +591",
			"+387" => "🇧🇦 +387",
			"+267" => "🇧🇼 +267",
			"+55" => "🇧🇷 +55",
			"+673" => "🇧🇳 +673",
			"+359" => "🇧🇬 +359",
			"+226" => "🇧🇫 +226",
			"+257" => "🇧🇮 +257",
			"+855" => "🇰🇭 +855",
			"+237" => "🇨🇲 +237",
			"+1" => "🇨🇦 +1",
			"+238" => "🇨🇻 +238",
			"+1345" => "🇰🇾 +1345",
			"+236" => "🇨🇫 +236",
			"+235" => "🇹🇩 +235",
			"+56" => "🇨🇱 +56",
			"+86" => "🇨🇳 +86",
			"+57" => "🇨🇴 +57",
			"+269" => "🇰🇲 +269",
			"+242" => "🇨🇬 +242",
			"+243" => "🇨🇩 +243",
			"+682" => "🇨🇰 +682",
			"+506" => "🇨🇷 +506",
			"+225" => "🇨🇮 +225",
			"+385" => "🇭🇷 +385",
			"+53" => "🇨🇺 +53",
			"+357" => "🇨🇾 +357",
			"+420" => "🇨🇿 +420",
			"+45" => "🇩🇰 +45",
			"+253" => "🇩🇯 +253",
			"+1767" => "🇩🇲 +1767",
			"+1809" => "🇩🇴 +1809",
			"+593" => "🇪🇨 +593",
			"+20" => "🇪🇬 +20",
			"+503" => "🇸🇻 +503",
			"+240" => "🇬🇶 +240",
			"+291" => "🇪🇷 +291",
			"+372" => "🇪🇪 +372",
			"+251" => "🇪🇹 +251",
			"+500" => "🇫🇰 +500",
			"+298" => "🇫🇴 +298",
			"+679" => "🇫🇯 +679",
			"+358" => "🇫🇮 +358",
			"+33" => "🇫🇷 +33",
			"+594" => "🇬🇫 +594",
			"+689" => "🇵🇫 +689",
			"+241" => "🇬🇦 +241",
			"+220" => "🇬🇲 +220",
			"+995" => "🇬🇪 +995",
			"+49" => "🇩🇪 +49",
			"+233" => "🇬🇭 +233",
			"+350" => "🇬🇮 +350",
			"+30" => "🇬🇷 +30",
			"+299" => "🇬🇱 +299",
			"+1473" => "🇬🇩 +1473",
			"+590" => "🇬🇵 +590",
			"+1671" => "🇬🇺 +1671",
			"+502" => "🇬🇹 +502",
			"+224" => "🇬🇳 +224",
			"+245" => "🇬🇼 +245",
			"+592" => "🇬🇾 +592",
			"+509" => "🇭🇹 +509",
			"+504" => "🇭🇳 +504",
			"+852" => "🇭🇰 +852",
			"+36" => "🇭🇺 +36",
			"+354" => "🇮🇸 +354",
			"+91" => "🇮🇳 +91",
			"+62" => "🇮🇩 +62",
			"+98" => "🇮🇷 +98",
			"+964" => "🇮🇶 +964",
			"+353" => "🇮🇪 +353",
			"+972" => "🇮🇱 +972",
			"+39" => "🇮🇹 +39",
			"+1876" => "🇯🇲 +1876",
			"+81" => "🇯🇵 +81",
			"+962" => "🇯🇴 +962",
			"+7" => "🇰🇿 +7",
			"+254" => "🇰🇪 +254",
			"+686" => "🇰🇮 +686",
			"+850" => "🇰🇵 +850",
			"+82" => "🇰🇷 +82",
			"+965" => "🇰🇼 +965",
			"+996" => "🇰🇬 +996",
			"+856" => "🇱🇦 +856",
			"+371" => "🇱🇻 +371",
			"+961" => "🇱🇧 +961",
			"+266" => "🇱🇸 +266",
			"+231" => "🇱🇷 +231",
			"+218" => "🇱🇾 +218",
			"+423" => "🇱🇮 +423",
			"+370" => "🇱🇹 +370",
			"+352" => "🇱🇺 +352",
			"+853" => "🇲🇴 +853",
			"+389" => "🇲🇰 +389",
			"+261" => "🇲🇬 +261",
			"+265" => "🇲🇼 +265",
			"+60" => "🇲🇾 +60",
			"+960" => "🇲🇻 +960",
			"+223" => "🇲🇱 +223",
			"+356" => "🇲🇹 +356",
			"+692" => "🇲🇭 +692",
			"+596" => "🇲🇶 +596",
			"+222" => "🇲🇷 +222",
			"+230" => "🇲🇺 +230",
			"+262" => "🇾🇹 +262",
			"+52" => "🇲🇽 +52",
			"+691" => "🇫🇲 +691",
			"+373" => "🇲🇩 +373",
			"+377" => "🇲🇨 +377",
			"+976" => "🇲🇳 +976",
			"+382" => "🇲🇪 +382",
			"+1664" => "🇲🇸 +1664",
			"+212" => "🇲🇦 +212",
			"+258" => "🇲🇿 +258",
			"+95" => "🇲🇲 +95",
			"+264" => "🇳🇦 +264",
			"+674" => "🇳🇷 +674",
			"+977" => "🇳🇵 +977",
			"+31" => "🇳🇱 +31",
			"+687" => "🇳🇨 +687",
			"+64" => "🇳🇿 +64",
			"+505" => "🇳🇮 +505",
			"+227" => "🇳🇪 +227",
			"+234" => "🇳🇬 +234",
			"+683" => "🇳🇺 +683",
			"+672" => "🇳🇫 +672",
			"+1670" => "🇲🇵 +1670",
			"+47" => "🇳🇴 +47",
			"+968" => "🇴🇲 +968",
			"+92" => "🇵🇰 +92",
			"+680" => "🇵🇼 +680",
			"+970" => "🇵🇸 +970",
			"+507" => "🇵🇦 +507",
			"+675" => "🇵🇬 +675",
			"+595" => "🇵🇾 +595",
			"+51" => "🇵🇪 +51",
			"+63" => "🇵🇭 +63",
			"+48" => "🇵🇱 +48",
			"+351" => "🇵🇹 +351",
			"+1787" => "🇵🇷 +1787",
			"+974" => "🇶🇦 +974",
			"+262" => "🇷🇪 +262",
			"+40" => "🇷🇴 +40",
			"+7" => "🇷🇺 +7",
			"+250" => "🇷🇼 +250",
			"+590" => "🇧🇱 +590",
			"+290" => "🇸🇭 +290",
			"+1869" => "🇰🇳 +1869",
			"+1758" => "🇱🇨 +1758",
			"+590" => "🇲🇫 +590",
			"+508" => "🇵🇲 +508",
			"+1784" => "🇻🇨 +1784",
			"+685" => "🇼🇸 +685",
			"+378" => "🇸🇲 +378",
			"+239" => "🇸🇹 +239",
			"+966" => "🇸🇦 +966",
			"+221" => "🇸🇳 +221",
			"+381" => "🇷🇸 +381",
			"+248" => "🇸🇨 +248",
			"+232" => "🇸🇱 +232",
			"+65" => "🇸🇬 +65",
			"+421" => "🇸🇰 +421",
			"+386" => "🇸🇮 +386",
			"+677" => "🇸🇧 +677",
			"+252" => "🇸🇴 +252",
			"+27" => "🇿🇦 +27",
			"+211" => "🇸🇸 +211",
			"+34" => "🇪🇸 +34",
			"+94" => "🇱🇰 +94",
			"+249" => "🇸🇩 +249",
			"+597" => "🇸🇷 +597",
			"+268" => "🇸🇿 +268",
			"+46" => "🇸🇪 +46",
			"+41" => "🇨🇭 +41",
			"+963" => "🇸🇾 +963",
			"+886" => "🇹🇼 +886",
			"+992" => "🇹🇯 +992",
			"+255" => "🇹🇿 +255",
			"+66" => "🇹🇭 +66",
			"+670" => "🇹🇱 +670",
			"+228" => "🇹🇬 +228",
			"+690" => "🇹🇰 +690",
			"+676" => "🇹🇴 +676",
			"+1868" => "🇹🇹 +1868",
			"+216" => "🇹🇳 +216",
			"+90" => "🇹🇷 +90",
			"+993" => "🇹🇲 +993",
			"+1649" => "🇹🇨 +1649",
			"+688" => "🇹🇻 +688",
			"+256" => "🇺🇬 +256",
			"+380" => "🇺🇦 +380",
			"+971" => "🇦🇪 +971",
			"+44" => "🇬🇧 +44",
			"+1" => "🇺🇸 +1",
			"+598" => "🇺🇾 +598",
			"+998" => "🇺🇿 +998",
			"+678" => "🇻🇺 +678",
			"+379" => "🇻🇦 +379",
			"+58" => "🇻🇪 +58",
			"+84" => "🇻🇳 +84",
			"+1284" => "🇻🇬 +1284",
			"+1340" => "🇻🇮 +1340",
			"+681" => "🇼🇫 +681",
			"+967" => "🇾🇪 +967",
			"+260" => "🇿🇲 +260",
			"+263" => "🇿🇼 +263",
		);
		return $country_code;
	}

	public function toggleIPRestriction($ipRestrictionsStatus)
	{
		$DB			= 	new DB_connection();
		$update 	= 	"UPDATE `inv_qne_settings` SET `value` = '" . $ipRestrictionsStatus . "' WHERE `setting_key` = 'ip_restrictions'";
		$conn		= 	$DB->query($update);
		return true;
	}

	public function getIPRestrictionsStatus()
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT `value` FROM `inv_qne_settings` WHERE `setting_key` = 'ip_restrictions'";
		$conn		= 	$DB->query($select);
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			return $fetch->value;
		}
		return 0;
	}

	public function getIPSettings()
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT ips.*, u.full_name FROM `inv_qne_ips` ips LEFT JOIN `inv_qne_users` u ON ips.added_by = u.user_id ORDER BY `id` DESC";
		$conn		= 	$DB->query($select);
		$ipSettings	=	array();
		if(mysqli_num_rows($conn) > 0)
		{
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{
				$ipSettings[$c]['id']				=	$fetch->id;
				$ipSettings[$c]['ip_address']		=	$fetch->ip_address;
				$ipSettings[$c]['added_by']			=	$fetch->added_by;
				$ipSettings[$c]['added_by_name']	=	$fetch->full_name;
				$ipSettings[$c]['status']			=	$fetch->status;
				$c++;
			}
			return $ipSettings;
		}	
		return $ipSettings;
	}	

	public function deleteIPSetting($ipSettingID)
	{
		$DB			= 	new DB_connection();
		$delete 	= 	"DELETE FROM `inv_qne_ips` WHERE `id` = " . $ipSettingID;
		$conn		= 	$DB->query($delete);
		return true;
	}

	public function addIPAddress($ipAddress, $status, $addedBy)
	{
		$DB			= 	new DB_connection();
		$insert 	= 	"INSERT INTO `inv_qne_ips`(`ip_address`, `status`, `added_by`) VALUES ('" . $ipAddress . "', '" . $status . "', '" . $addedBy . "')";
		$conn		= 	$DB->query($insert);
		return true;
	}

	public function isIPAllowed($ipAddress)
	{
		$DB			= 	new DB_connection();
		// Check for wildcard ip access first
		$select 	= 	"SELECT * FROM `inv_qne_ips` WHERE `ip_address` = '*' AND `status` = 1";
		$conn		= 	$DB->query($select);
		if(mysqli_num_rows($conn) > 0)
		{
			return true;
		}

		$select 	= 	"SELECT * FROM `inv_qne_ips` WHERE `ip_address` = '" . $ipAddress . "' AND `status` = 1";
		$conn		= 	$DB->query($select);
		if(mysqli_num_rows($conn) > 0)
		{
			return true;
		}
		return false;
	}

	public function allSubCategory($parent_id=0)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `sub_category` WHERE category_id = " . $parent_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$categories 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$categories[$c]					=	new General();
				$categories[$c]->sub_cat_id	=	$fetch->sub_cat_id;
				$categories[$c]->title			=	$fetch->sub_cat_name;
				/*$categories[$c]->url_title		=	$fetch->url_title;
				$categories[$c]->des			=	$fetch->des;
				$categories[$c]->sort_order		=	$fetch->sort_order;
				$categories[$c]->seotitle		=	$fetch->seotitle;
				$categories[$c]->metatags		=	$fetch->metatags;
				$categories[$c]->metadesc		=	$fetch->metadesc;
				$categories[$c]->status			=	$fetch->status;
				$categories[$c]->datetime		=	$fetch->datetime;*/
				$c++;
			}
			return $categories;
		}
	}
    
    public function allSubSubCategory($parent_id=0)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `sub_sub_category` WHERE sub_cat_id = " . $parent_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$categories 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$categories[$c]					=	new General();
				$categories[$c]->id	=	$fetch->id;
				//$categories[$c]->parent_id		=	$fetch->parent_id;
				$categories[$c]->title			=	$fetch->title;
				//$categories[$c]->url_title		=	$fetch->url_title;
				/*$categories[$c]->des			=	$fetch->des;
				$categories[$c]->sort_order		=	$fetch->sort_order;
				$categories[$c]->seotitle		=	$fetch->seotitle;
				$categories[$c]->metatags		=	$fetch->metatags;
				$categories[$c]->metadesc		=	$fetch->metadesc;
				$categories[$c]->status			=	$fetch->status;
				$categories[$c]->datetime		=	$fetch->datetime;*/
				$c++;
			}
			return $categories;
		}
	}
    
    public function productExpiryDashboard($expirySpan)
	{
		$DB		= new DB_connection();
		
		if($start == 0 && $limit == 0)
		{
			$select = "SELECT count(product_id) as productCount, sum(qty) as Qty, sum(net_total) as Amount FROM `inv_qne_grn_po_details` gd LEFT JOIN `inv_qne_products` qp ON gd.product_id = qp.product_id LEFT JOIN `inv_qne_product_sku` ps ON gd.sku_id = ps.sku_id WHERE gd.`expiry_date` <= '" . $expirySpan . "' AND (gd.`status` != 'close' || gd.`status` != 'Close')";
		}
		$conn	= $DB->query($select);
		$rows	= mysqli_num_rows($conn);

		if($rows > 0)
		{
			$c = 0;
			$productSKU = array();

			$fetch = mysqli_fetch_object($conn);

			$this->expProductCount 	=	$fetch->productCount;
			$this->expProductQty 	=	$fetch->Qty;
			$this->expProductAmount	=	$fetch->Amount;
		}
	}
	
	public function companyByDistributor($distributor_id)
	{
		$DB			= 	new DB_connection();
		if($distributor_id == 0)
		{
			$select 	= 	"SELECT * FROM `inv_qne_company` iqc ORDER BY company ASC";
		}
		else
		{
			$select 	= 	"SELECT * FROM `inv_qne_company` iqc JOIN `inv_qne_company_distributor` iqcd ON iqc.company_id = iqcd.company_id WHERE iqcd.distributor_id = " . $distributor_id . " GROUP BY iqc.company_id ORDER BY iqc.company ASC";
		}
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$companies 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$companies[$c]				=	new General();
				$companies[$c]->company_id	=	$fetch->company_id;
				$companies[$c]->company		=	$fetch->company;
				$companies[$c]->website		=	$fetch->website;
				$companies[$c]->email		=	$fetch->email;
				$companies[$c]->status		=	$fetch->status;
				$companies[$c]->date		=	$fetch->date;
				$c++;
			}
			return $companies;
		}
	}
	
	public function brandsByCompany($company_id)
	{
		$DB			= 	new DB_connection();
		$where 		= 	'';
		$companies 	= 	array();
		if($company_id != '')
		{
			$where = 'WHERE iqb.company IN (' . $company_id . ')';
		
			//$select 	= 	"SELECT * FROM `inv_qne_brand` iqb " . $where . " ORDER BY brand ASC";
			echo $select 	= 	"SELECT * FROM `brand` iqb " . $where . " ORDER BY title ASC";
			$conn		= 	$DB->query($select);
			
			if(mysqli_num_rows($conn) > 0)
			{
				
				$c				=	0;
				while($fetch = mysqli_fetch_object($conn))
				{	
					$companies[$c]				=	new General();
					$companies[$c]->brand_id	=	$fetch->id;
					$companies[$c]->brand		=	$fetch->title;
					$companies[$c]->company		=	$fetch->company;
					//$companies[$c]->website		=	$fetch->website;
					//$companies[$c]->email		=	$fetch->email;
					$companies[$c]->status		=	$fetch->status;
					//$companies[$c]->date		=	$fetch->date;
					$c++;
				}
				
			}
		}
		return $companies;		
	}
	
	public function allCompany()
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_company` ORDER BY company ASC";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$companies 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$companies[$c]				=	new General();
				$companies[$c]->company_id	=	$fetch->company_id;
				$companies[$c]->company		=	$fetch->company;
				$companies[$c]->website		=	$fetch->website;
				$companies[$c]->email		=	$fetch->email;
				$companies[$c]->status		=	$fetch->status;
				$companies[$c]->date		=	$fetch->date;
				$c++;
			}
			return $companies;
		}
	}
	
	function addCompany($post)
	{
		$DB			= 	new DB_connection();
		
		$company	=	mysql_real_escape_string($post['company']);
		$website	=	mysql_real_escape_string($post['website']);
		$distributor=	mysql_real_escape_string($_POST['distributor']);
		$email		=	mysql_real_escape_string($post['email']);
		$status		=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"SELECT * FROM `inv_qne_company` WHERE `company` = '" . $company . "'";
		$conn		=	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			return 0;
		}
		else
		{
			$select 	= 	"INSERT INTO `inv_qne_company`(`company_id`, `company`, `website`, `email`, `status`, `date`) VALUES('', '" . $company . "', '" . $website . "', '" . $email . "', '" . $status . "', '" . date('Y-m-d') . "')";
			$DB->query($select);
			
			$company_id	=	mysql_insert_id();
	
			if(sizeof($_POST['distributor_depo']) > 0)
			{
				foreach($_POST['distributor_depo'] as $dis)
				{
					$distri	=	explode("_", $dis);
					$insert = 	"INSERT INTO `inv_qne_company_distributor`(`id`, `distributor_id`, `distributor_depo_id`, `company_id`, `status`) VALUES('', '" . $distri[0] . "', '" . $distri[1] . "', '" . $company_id . "', '1')";
					$DB->query($insert);
				}
				return 1;
			}	
			else
			{
				return 0;
			}
		}	
	}
	
	function editCompany($post)
	{
		$DB			= 	new DB_connection();
		$company_id	=	mysql_real_escape_string($post['company_id']);
		$company	=	mysql_real_escape_string($post['company']);
		$distributor=	mysql_real_escape_string($_POST['distributor']);
		$website	=	mysql_real_escape_string($post['website']);
		$email		=	mysql_real_escape_string($post['email']);
		$status		=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"SELECT * FROM `inv_qne_company` WHERE `company` = '" . $company . "' AND `company_id` != " . $company_id;
		$conn		=	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			return 0;
		}
		else
		{
			$update 		= 	"UPDATE `inv_qne_company` SET `company` = '" . $company . "', `website` = '" . $website . "', `email` = '" . $email . "', `status` = '" . $status . "' WHERE `company_id` = " . $company_id;
			if($DB->query($update))
			{
				if(sizeof($_POST['distributor_depo']) > 0)
				{
					$delete = 	"DELETE FROM `inv_qne_company_distributor` WHERE `company_id` = '" . $company_id . "'";
					$DB->query($delete);
					
					foreach($_POST['distributor_depo'] as $dis)
					{
						$distri	=	explode("_", $dis);
						$insert = 	"INSERT INTO `inv_qne_company_distributor`(`id`, `distributor_id`, `distributor_depo_id`, `company_id`, `status`) VALUES('', '" . $distri[0] . "', '" . $distri[1] . "', '" . $company_id . "', '1')";
						$DB->query($insert);
					}
					return true;
				}	
				return 1;
			}
			else
			{
				return 0;
			}
		}	
	}
	
	public function allDesignation()
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_designation` WHERE `status` = '1'";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$designations 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$designations[$c]					=	new General();
				$designations[$c]->designation_id	=	$fetch->designation_id;
				$designations[$c]->designation		=	$fetch->designation;
				$designations[$c]->status			=	$fetch->status;
				$designations[$c]->date				=	$fetch->date;
				$c++;
			}
			return $designations;
		}
	}

	public function allBrands($company=0)
	{
		$DB			= 	new DB_connection();
		
		$where 		= 	'';
		if($company != 0)
		{
			$where .= " AND `company_id` = " . $company;
		}
		//$select 	= 	"SELECT iqb.brand_id, iqb.brand, iqc.company, iqb.website, iqb.email, iqb.status, iqb.date FROM `inv_qne_brand` iqb LEFT JOIN `inv_qne_company` iqc ON iqb.company = iqc.company_id WHERE 1=1" . $where . " ORDER BY iqb.brand ASC";
		$select 	= 	"SELECT iqb.id as brand_id, iqb.title as brand, iqc.company FROM `brand` iqb LEFT JOIN `inv_qne_company` iqc ON iqb.company = iqc.company_id WHERE 1=1" . $where . " ORDER BY iqb.title ASC";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$brands 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$brands[$c]				=	new General();
				$brands[$c]->brand_id	=	$fetch->brand_id;
				$brands[$c]->brand		=	$fetch->brand;
				$brands[$c]->company    =	$fetch->company;
				/*$brands[$c]->website	=	$fetch->website;
				$brands[$c]->email		=	$fetch->email;
				$brands[$c]->status		=	$fetch->status;
				$brands[$c]->date		=	$fetch->date;*/
				$c++;
			}
			return $brands;
		}
	}
	
	public function allTaxes($tax_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where 		= 	'';
		if($tax_id != 0)
		{
			$where .=	" AND tax_id = " . $tax_id;
		}
		$select 	= 	"SELECT * FROM `inv_qne_tax` WHERE 1 = 1 " . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$taxes 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$taxes[$c]				=	new General();
				$taxes[$c]->tax_id		=	$fetch->tax_id;
				$taxes[$c]->tax			=	$fetch->tax;
				$taxes[$c]->tax_type    =	$fetch->tax_type;
				$taxes[$c]->tax_value	=	$fetch->tax_value;
				$taxes[$c]->from_date	=	$fetch->from_date;
				$taxes[$c]->to_date		=	$fetch->to_date;
				$taxes[$c]->status		=	$fetch->status;
				$taxes[$c]->added_date	=	$fetch->added_date;
				$c++;
			}
			return $taxes;
		}
	}
	
	function addTax($post)
	{
		$DB			= 	new DB_connection();
		$tax		=	mysql_real_escape_string($post['tax']);
		$tax_type	=	mysql_real_escape_string($post['tax_type']);
		$tax_value	=	mysql_real_escape_string($post['tax_value']);
		$from_date	=	mysql_real_escape_string($post['from_date']);
		$to_date	=	mysql_real_escape_string($post['to_date']);
		$status		=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"INSERT INTO `inv_qne_tax`(`tax_id`, `tax`, `tax_type`, `tax_value`, `from_date`, `to_date`, `status`, `added_date`) VALUES('', '" . $tax . "', '" . $tax_type . "', '" . $tax_value . "', '" . $from_date . "', '" . $to_date . "', '" . $status . "', '" . date('Y-m-d') . "')";
		if($DB->query($select))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function editTax($post)
	{
		$DB			= 	new DB_connection();
		$tax_id		=	mysql_real_escape_string($post['tax_id']);
		$tax		=	mysql_real_escape_string($post['tax']);
		$tax_type	=	mysql_real_escape_string($post['tax_type']);
		$tax_value	=	mysql_real_escape_string($post['tax_value']);
		$from_date	=	mysql_real_escape_string($post['from_date']);
		$to_date	=	mysql_real_escape_string($post['to_date']);
		$status		=	mysql_real_escape_string($post['status']);
		
		$update 	= 	"UPDATE `inv_qne_tax` SET `tax` = '" . $tax . "', `tax_type` = '" . $tax_type . "', `tax_value` = '" . $tax_value . "', `from_date` = '" . $from_date . "', `to_date` = '" . $to_date . "', `status` = '" . $status . "' WHERE `tax_id` = " . $tax_id;
		
		if($DB->query($update))
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function taxById($id)
	{
		$DB			= 	new DB_connection();
		
		$where 		= 	'';
		
		$select 	= 	"SELECT * FROM `inv_qne_tax` WHERE `tax_id` = '" . $id . "'";
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			
			$this->tax_id		=	$fetch->tax_id;
			$this->tax			=	$fetch->tax;
			$this->tax_type    	=	$fetch->tax_type;
			$this->tax_value	=	$fetch->tax_value;
			$this->from_date	=	$fetch->from_date;
			$this->to_date		=	$fetch->to_date;
			$this->status		=	$fetch->status;
			$this->added_date	=	$fetch->added_date;
		}
	}
	
	public function productType($type_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where = "";
		$join  = "";
		
		if($type_id != 0)
		{
			$where .= " AND product_type_id = " . $type_id;
		}
		$select 	= 	"SELECT * FROM `inv_qne_product_type` pt WHERE 1 = 1 " . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$pTypes 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$pTypes[$c]						=	new General();
				$pTypes[$c]->product_type_id	=	$fetch->product_type_id;
				$pTypes[$c]->product_type		=	$fetch->product_type;
				$pTypes[$c]->status				=	$fetch->status;
				$pTypes[$c]->date				=	$fetch->date;
				$c++;
			}
			return $pTypes;
		}
	}
	
	public function allWarehouse($warehouse_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where 		= 	'';
		if($warehouse_id != 0)
		{
			$where .=	" AND warehouse_id = " . $warehouse_id;
		}
		$select 	= 	"SELECT * FROM `inv_qne_warehouse` WHERE 1 = 1 " . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$warehouses 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$warehouses[$c]					=	new General();
				$warehouses[$c]->warehouse_id	=	$fetch->warehouse_id;
				$warehouses[$c]->warehouse		=	$fetch->warehouse;
				$warehouses[$c]->warehouse_code =	$fetch->warehouse_code;
				$warehouses[$c]->address		=	$fetch->address;
				$warehouses[$c]->city			=	$fetch->city;
				$warehouses[$c]->state			=	$fetch->state;
				$warehouses[$c]->zipcode   		=	$fetch->zipcode;
				$warehouses[$c]->phone			=	$fetch->phone;
				$warehouses[$c]->phone2			=	$fetch->phone2;
				$warehouses[$c]->contact_person	=	$fetch->contact_person;
				$warehouses[$c]->contact_person2=	$fetch->contact_person2;
				$warehouses[$c]->status			=	$fetch->status;
				$warehouses[$c]->date			=	$fetch->date;
				$c++;
			}
			return $warehouses;
		}
	}
	
	function addWarehouse($post)
	{
		$DB				= 	new DB_connection();
		$warehouse		=	mysql_real_escape_string($post['warehouse']);
		$warehouse_code	=	mysql_real_escape_string($post['warehouse_code']);
		$address		=	mysql_real_escape_string($post['address']);
		$city			=	mysql_real_escape_string($post['city']);
		$state			=	explode('_', mysql_real_escape_string($post['state']));
		$state			=	$state[1];
		$state			=	mysql_real_escape_string($post['state']);
		$zipcode		=	mysql_real_escape_string($post['zipcode']);
		$phone1			=	mysql_real_escape_string($post['phone1']);
		$phone2			=	mysql_real_escape_string($post['phone2']);
		$contact1		=	mysql_real_escape_string($post['contact1']);
		$contact2		=	mysql_real_escape_string($post['contact2']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"INSERT INTO `inv_qne_warehouse`(`warehouse_id`, `warehouse`, `warehouse_code`, `address`, `city`, `state`, `zipcode`, `phone`, `phone2`, `contact_person`, `contact_person2`, `status`, `date`) VALUES('', '" . $warehouse . "', '" . $warehouse_code . "', '" . $address . "', '" . $city . "', '" . $state . "', '" . $zipcode . "', '" . $phone1 . "', '" . $phone2 . "', '" . $contact1 . "', '" . $contact2 . "', '" . $status . "', '" . date('Y-m-d') . "')";
		if($DB->query($select))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function editWarehouse($post)
	{
		$DB				= 	new DB_connection();
		$warehouse_id	=	mysql_real_escape_string($post['warehouse_id']);
		$warehouse		=	mysql_real_escape_string($post['warehouse']);
		$warehouse_code	=	mysql_real_escape_string($post['warehouse_code']);
		$address		=	mysql_real_escape_string($post['address']);
		$city			=	mysql_real_escape_string($post['city']);
		$state			=	explode('_', mysql_real_escape_string($post['state']));
		$state			=	$state[1];
		$zipcode		=	mysql_real_escape_string($post['zipcode']);
		$phone1			=	mysql_real_escape_string($post['phone1']);
		$phone2			=	mysql_real_escape_string($post['phone2']);
		$contact1		=	mysql_real_escape_string($post['contact1']);
		$contact2		=	mysql_real_escape_string($post['contact2']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$update 		= 	"UPDATE `inv_qne_warehouse` SET `warehouse` = '" . $warehouse . "', `warehouse_code` = '" . $warehouse_code . "', `address` = '" . $address . "', `city` = '" . $city . "', `state` = '" . $state . "', `zipcode` = '" . $zipcode . "', `phone` = '" . $phone1 . "', `phone2` = '" . $phone2 . "', `contact_person` = '" . $contact1 . "', `contact_person2` = '" . $contact2 . "', `status` = '" . $status . "' WHERE `warehouse_id` = " . $warehouse_id;
		
		if($DB->query($update))
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function brandById($brand_id)
	{
		$DB			= 	new DB_connection();
		//$select 	= 	"SELECT * FROM `inv_qne_brand` WHERE `brand_id` = " . $brand_id;
		$select 	= 	"SELECT * FROM `brand` WHERE `id` = " . $brand_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			
			$this->brand_id			=	$fetch->id;
			$this->brand			=	$fetch->title;
			$this->company			=	$fetch->company;
			//$this->website			=	$fetch->website;
			//$this->email			=	$fetch->email;
			//$this->is_delete		=	$fetch->is_delete;
			$this->status			=	$fetch->status;
			//$this->date				=	$fetch->date;
		}
	}
	
	function addBrand($post)
	{
		$DB				= 	new DB_connection();
		$brand			=	mysql_real_escape_string($post['brand']);
		$company		=	mysql_real_escape_string($post['company']);
		//$distributor	=	mysql_real_escape_string($post['distributor']);
		//$website		=	mysql_real_escape_string($post['website']);
		//$email			=	mysql_real_escape_string($post['email']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"SELECT * FROM `brand` WHERE `title` = '" . $brand . "'";
		$conn		=	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			return 0;
		}
		else
		{
			//$insert 	= 	"INSERT INTO `inv_qne_brand`(`brand_id`, `brand`, `company`, `website`, `email`, `status`, `date`) VALUES('', '" . $brand . "', '" . $company . "', '" . $website . "', '" . $email . "', '" . $status . "', '" . date('Y-m-d') . "')";
			//$DB->query($insert);
			
			//$id = mysql_insert_id();
			
			$insert2 = 	"INSERT INTO `brand`(`company`, `title`, `img_alt_des`, `seotitle`, `metatags`, `metadesc`, `status`) VALUES('" . $company . "', '" . $brand . "', '" . $brand . "', '" . $brand . "', '" . $brand . "', '" . $brand . "', '" . $status . "')";
			$DB->query($insert2);
			return 1;
		}	
	}
	
	function editBrand($post)
	{
		$DB			= 	new DB_connection();
		$brand_id	=	mysql_real_escape_string($post['brand_id']);
		$brand		=	mysql_real_escape_string($post['brand']);
		$company	=	mysql_real_escape_string($post['company']);
		//$website	=	mysql_real_escape_string($post['website']);
		//$email		=	mysql_real_escape_string($post['email']);
		$status		=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"SELECT * FROM `brand` WHERE `title` = '" . $brand . "' AND `id` != " . $brand_id;
		$conn		=	$DB->query($select);
		
		/*if(mysqli_num_rows($conn) > 0)
		{
			return 0;
		}
		else
		{*/
			//$update = 	"UPDATE `inv_qne_brand` SET `brand` = '" . $brand . "', `company` = '" . $company . "', `website` = '" . $website . "', `email` = '" . $email . "', `status` = '" . $status . "' WHERE `brand_id` = " . $brand_id;
			//if($DB->query($update))
			//{
				$update2 = 	"UPDATE `brand` SET `company` = '" . $company . "', `title` = '" . $brand . "', `img_alt_des` = '" . $brand . "', `seotitle` = '" . $brand . "', `metatags` = '" . $brand . "', `metadesc` = '" . $brand . "', `status` = '" . $status . "' WHERE `id` = " . $brand_id;
				$DB->query($update2);
				return 1;
			//}
			//else
			//{
			//	return 0;
			//}
		//}	
	}
	
	public function allDistributor($company_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where = "";
		$join  = "";
		
		if($company_id != 0)
		{
			$join  .= " JOIN `inv_qne_company_distributor` idc ON iqd.distributor_id = idc.distributor_id";	
			$where .= " WHERE company_id = " . $company_id . " GROUP BY iqd.distributor_id";
		}
		$selectQry 	= 	"SELECT * FROM `inv_qne_distributor` iqd " . $join . " " . $where . " ORDER BY iqd.distributor ASC";
		$conn		= 	$DB->query($selectQry);
		$DB->done();
		//echo mysqli_num_rows($conn); exit();
		if(mysqli_num_rows($conn) > 0)
		{
			$distributorsList 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$distributorsList[$c]					=	new General();
				$distributorsList[$c]->distributor_id	=	$fetch->distributor_id;
				$distributorsList[$c]->distributor		=	$fetch->distributor;
				$distributorsList[$c]->website			=	$fetch->website;
				$distributorsList[$c]->email			=	$fetch->email;
				$distributorsList[$c]->ntn_number		=	$fetch->ntn_number;
				$distributorsList[$c]->strn_number		=	$fetch->strn_number;
				$distributorsList[$c]->status			=	$fetch->status;
				$distributorsList[$c]->date				=	$fetch->date;
				$c++;
			}
			return $distributorsList;
		}
	}
	
	public function distributorsList($distributor_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where = "";
		$join  = "";
		
		if($distributor_id != 0)
		{
			$where .= " AND distributor_id IN (" . $distributor_id . ")";
		}
		$select 	= 	"SELECT * FROM `inv_qne_distributor` WHERE 1 = 1 " . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$distributors 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$distributors[$c]					=	new General();
				$distributors[$c]->distributor_id	=	$fetch->distributor_id;
				$distributors[$c]->distributor		=	$fetch->distributor;
				$distributors[$c]->website			=	$fetch->website;
				$distributors[$c]->email			=	$fetch->email;
				$distributors[$c]->ntn_number		=	$fetch->ntn_number;
				$distributors[$c]->strn_number		=	$fetch->strn_number;
				$distributors[$c]->status			=	$fetch->status;
				$distributors[$c]->date				=	$fetch->date;
				$c++;
			}
			return $distributors;
		}
	}
	
	public function distributorDepo($distributor_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where = "";
		$join  = "";
		
		if($distributor_id != '')
		{
			$join  .= " JOIN `inv_qne_distributor_depo` idb ON iqd.distributor_id = idb.distributor_id";	
			$where .= " AND idb.distributor_id IN (" . $distributor_id . ")";
		}
		$select 	= 	"SELECT iqd.*, idb.branch_id, idb.branch, idb.address, idb.contact_person, idb.contact_number, idb.status as dstatus, idb.date as ddate FROM `inv_qne_distributor` iqd " . $join . " WHERE 1 = 1 " . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$distributors 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$distributors[$c]					=	new General();
				$distributors[$c]->distributor_id	=	$fetch->distributor_id;
				$distributors[$c]->distributor		=	$fetch->distributor;
				$distributors[$c]->website			=	$fetch->website;
				$distributors[$c]->email			=	$fetch->email;
				$distributors[$c]->ntn_number		=	$fetch->ntn_number;
				$distributors[$c]->strn_number		=	$fetch->strn_number;
				$distributors[$c]->status			=	$fetch->status;
				$distributors[$c]->date				=	$fetch->date;
				
				$distributors[$c]->branch_id		=	$fetch->branch_id;
				$distributors[$c]->branch			=	$fetch->branch;
				$distributors[$c]->address			=	$fetch->address;
				$distributors[$c]->contact_person	=	$fetch->contact_person;
				$distributors[$c]->contact_number	=	$fetch->contact_number;
				$distributors[$c]->status			=	$fetch->dstatus;
				$distributors[$c]->date				=	$fetch->ddate;
				$c++;
			}
			return $distributors;
		}
	}

	function addDistributor($post)
	{
		$DB				= 	new DB_connection();
		$distributor	=	mysql_real_escape_string($post['distributor']);
		$website		=	mysql_real_escape_string($post['website']);
		$email			=	mysql_real_escape_string($post['email']);
		$distributor	=	mysql_real_escape_string($post['distributor']);
		$ntn_number		=	mysql_real_escape_string($post['ntn']);
		$strn_number	=	mysql_real_escape_string($post['strn']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"INSERT INTO `inv_qne_distributor`(`distributor_id`, `distributor`, `website`, `email`, `ntn_number`, `strn_number`, `status`, `date`) VALUES('', '" . $distributor . "', '" . $website . "', '" . $email . "', '" . $ntn_number . "', '" . $strn_number . "', '" . $status . "', '" . date('Y-m-d') . "')";
		if($DB->query($select))
		{
			$distributor_id	=	mysql_insert_id();
			$select2 		= 	"INSERT INTO `inv_qne_distributor_depo`(`branch_id`, `distributor_id`, `branch`, `status`, `date`) VALUES('', '" . $distributor_id . "', '" . $distributor . "', '" . $status . "', '" . date('Y-m-d') . "')";
			$DB->query($select2);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function addDistributorBranch($post)
	{
		$DB				= 	new DB_connection();
		$distributor	=	mysql_real_escape_string($post['distributor']);
		$branch			=	mysql_real_escape_string($post['branch']);
		$address		=	mysql_real_escape_string($post['address']);
		$contact_person	=	mysql_real_escape_string($post['contact_person']);
		$contact_number	=	mysql_real_escape_string($post['contact_number']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"INSERT INTO `inv_qne_distributor_depo`(`branch_id`, `distributor_id`, `branch`, `address`, `contact_person`, `contact_number`, `status`, `date`) VALUES('', '" . $distributor . "', '" . $branch . "', '" . $address . "', '" . $contact_person . "', '" . $contact_number . "', '" . $status . "', '" . date('Y-m-d') . "')";
		if($DB->query($select))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function editDistributorBranch($post)
	{
		$DB				= 	new DB_connection();
		$branch_id		=	mysql_real_escape_string($post['branch_id']);
		$distributor	=	mysql_real_escape_string($post['distributor']);
		$branch			=	mysql_real_escape_string($post['branch']);
		$address		=	mysql_real_escape_string($post['address']);
		$contact_person	=	mysql_real_escape_string($post['contact_person']);
		$contact_number	=	mysql_real_escape_string($post['contact_number']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"UPDATE `inv_qne_distributor_depo` SET `distributor_id` = '" . $distributor . "', `branch` = '" . $branch . "', `address` = '" . $address . "', `contact_person` = '" . $contact_person . "', `contact_number` = '" . $contact_number . "', `status` = '" . $status . "' WHERE `branch_id` = '" . $branch_id . "'";
		if($DB->query($select))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function companyById($company_id)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_company` WHERE `company_id` = " . $company_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			
			$this->company_id		=	$fetch->company_id;
			$this->company			=	$fetch->company;
			$this->website			=	$fetch->website;
			$this->email			=	$fetch->email;
			$this->is_delete		=	$fetch->is_delete;
			$this->status			=	$fetch->status;
			$this->date				=	$fetch->date;
		}
	}
	
	public function distributorById($distributor_id)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_distributor` WHERE `distributor_id` = " . $distributor_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			
			$this->distributor_id	=	$fetch->distributor_id;
			$this->distributor		=	$fetch->distributor;
			$this->website			=	$fetch->website;
			$this->email			=	$fetch->email;
			$this->ntn_number		=	$fetch->ntn_number;
			$this->strn_number		=	$fetch->strn_number;
			$this->status			=	$fetch->status;
			$this->date				=	$fetch->date;
		}
	}
	
	public function depoByCompany($company_id)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_company_distributor` WHERE `company_id` = " . $company_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$depos 	= 	array();
			$c		=	0;
			
			while($fetch = mysqli_fetch_object($conn))
			{	
				$depos[$c]						=	new General();
				
				$depos[$c]->id					=	$fetch->id;
				$depos[$c]->distributor_id		=	$fetch->distributor_id;
				$depos[$c]->distributor_depo_id	=	$fetch->distributor_depo_id;
				$depos[$c]->company_id			=	$fetch->company_id;
				$depos[$c]->status				=	$fetch->status;
				$c++;
			}
			return $depos;
		}
	}
	
	public function depoById($branch_id)
	{
		$DB			= 	new DB_connection();
		$select 	= 	"SELECT * FROM `inv_qne_distributor_depo` WHERE `branch_id` = " . $branch_id;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fetch = mysqli_fetch_object($conn);
			
			$this->branch_id		=	$fetch->branch_id;
			$this->distributor_id	=	$fetch->distributor_id;
			$this->branch			=	$fetch->branch;
			$this->address			=	$fetch->address;
			$this->contact_person	=	$fetch->contact_person;
			$this->contact_number	=	$fetch->contact_number;
			$this->status			=	$fetch->status;
			$this->date				=	$fetch->date;
		}
	}
	
	public function allContacts($type='', $id)
	{
		$DB			= 	new DB_connection();
		
		$where = "";
		$joinState	="";
		if($type != '')
		{
			$where .= " AND type = '" . $type . "'";
		}

		$select 	= 	"SELECT iqc.contact_id, iqc.contact_name, iqc.contact_phone, iqc.contact_email, iqc.contact_mobile, iqc.id, iqc.type, dsg.designation, iqc.status, iqc.date FROM `inv_qne_contacts` iqc JOIN `inv_qne_designation` dsg ON iqc.designation = dsg.designation_id WHERE id = " . $id . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$allContacts 	= 	array();
			$c				=	0;
			
			while($fetch = mysqli_fetch_object($conn))
			{	
				$allContacts[$c]					=	new General();
				$allContacts[$c]->contact_id		=	$fetch->contact_id;
				$allContacts[$c]->contact_name		=	$fetch->contact_name;
				$allContacts[$c]->contact_phone		=	$fetch->contact_phone;
				$allContacts[$c]->contact_email		=	$fetch->contact_email;
				$allContacts[$c]->contact_mobile	=	$fetch->contact_mobile;
				$allContacts[$c]->id				=	$fetch->id;
				$allContacts[$c]->type				=	$fetch->type;
				$allContacts[$c]->designation		=	$fetch->designation;
				$allContacts[$c]->status			=	$fetch->status;
				$allContacts[$c]->date				=	$fetch->date;
				$c++;
			}
			return $allContacts;
		}
	}
	
	function addContact($post)
	{
		$DB				= 	new DB_connection();
		$person			=	mysql_real_escape_string($post['person']);
		$contact_phone	=	mysql_real_escape_string($post['phone']);
		$contact_email	=	mysql_real_escape_string($post['email']);
		$contact_mobile	=	mysql_real_escape_string($post['mobile']);
		$id				=	mysql_real_escape_string($post['company']);
		$type			=	mysql_real_escape_string($post['type']);
		$designation	=	mysql_real_escape_string($post['designation']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$select 	= 	"INSERT INTO `inv_qne_contacts`(`contact_id`, `contact_name`, `contact_phone`, `contact_email`, `contact_mobile`, `id`, `type`, `designation`, `status`, `date`) VALUES('', '" . $person . "', '" . $contact_phone . "', '" . $contact_email . "', '" . $contact_mobile . "', '" . $id . "', '" . $type . "', '" . $designation . "', '" . $status . "', '" . date('Y-m-d') . "')";
		if($DB->query($select))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function editContact($post)
	{
		$DB				= 	new DB_connection();
		$contact_id		=	mysql_real_escape_string($post['contact_id']);
		$person			=	mysql_real_escape_string($post['person']);
		$contact_phone	=	mysql_real_escape_string($post['phone']);
		$contact_email	=	mysql_real_escape_string($post['email']);
		$contact_mobile	=	mysql_real_escape_string($post['mobile']);
		$id				=	mysql_real_escape_string($post['company']);
		$type			=	mysql_real_escape_string($post['type']);
		$designation	=	mysql_real_escape_string($post['designation']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$update 		= 	"UPDATE `inv_qne_contacts` SET `contact_name` = '" . $person . "', `contact_phone` = '" . $contact_phone . "', `contact_email` = '" . $contact_email . "', `contact_mobile` = '" . $contact_mobile . "', `id` = '" . $id . "', `type` = '" . $type . "', `designation` = '" . $designation . "', `status` = '" . $status . "' WHERE `contact_id` = " . $contact_id;
		if($DB->query($update))
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	function editDistributor($post)
	{
		$DB				= 	new DB_connection();
		$distributor	=	mysql_real_escape_string($post['distributor']);
		$distributor_id	=	mysql_real_escape_string($post['distributor_id']);
		$website		=	mysql_real_escape_string($post['website']);
		$email			=	mysql_real_escape_string($post['email']);
		$ntn			=	mysql_real_escape_string($post['ntn']);
		$strn			=	mysql_real_escape_string($post['strn']);
		$status			=	mysql_real_escape_string($post['status']);
		
		$update 		= 	"UPDATE `inv_qne_distributor` SET `distributor` = '" . $distributor . "', `website` = '" . $website . "', `email` = '" . $email . "', `ntn_number` = '" . $ntn . "', `strn_number` = '" . $strn . "', `status` = '" . $status . "' WHERE `distributor_id` = " . $distributor_id;
		if($DB->query($update))
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}
	
	public function allStates($state_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where 		= 	'';
		if($state_id != 0)
		{
			$where .=	" AND state_id = " . $state_id;
		}
		$select 	= 	"SELECT * FROM `inv_qne_states` WHERE 1 = 1 " . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$states 	= 	array();
			$c				=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$states[$c]				=	new General();
				$states[$c]->state_id	=	$fetch->state_id;
				$states[$c]->state		=	$fetch->state;
				$states[$c]->status 	=	$fetch->status;
				$c++;
			}
			return $states;
		}
	}
	
	public function allCities($city_id=0, $state_id=0)
	{
		$DB			= 	new DB_connection();
		
		$where 		= 	'';
		if($state_id != 0)
		{
			$where .=	" AND state_id = " . $state_id;
		}
		
		if($city_id != 0)
		{
			$where .=	" AND city_id = " . $city_id;
		}
		$select 	= 	"SELECT * FROM `inv_qne_city` WHERE 1 = 1 " . $where;
		$conn		= 	$DB->query($select);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$cities 	= 	array();
			$c			=	0;
			while($fetch = mysqli_fetch_object($conn))
			{	
				$cities[$c]				=	new General();
				$cities[$c]->city_id	=	$fetch->city_id;
				$cities[$c]->state_id	=	$fetch->state_id;
				$cities[$c]->city		=	$fetch->city;
				$cities[$c]->status 	=	$fetch->status;
				$c++;
			}
			return $cities;
		}
	}

	function generatePO()
	{
		$DB			= 	new DB_connection();
		$conn 	=  $DB->query("SELECT po_number FROM `inv_qne_purchase_order` ORDER BY po_number DESC LIMIT 1");
		$serNum =  mysqli_num_rows($conn);
		if($serNum > 0)
		{
			$fet		=	mysqli_fetch_object($conn);
			$po_number	=	$fet->po_number;
			if(date('Ym') == substr($po_number,0,6))
			{
				$PONumber	=	$fet->po_number + 1;
			}
			else
			{
				$PONumber 	= date('Y').date('m')."0001";
			}
		}
		else
		{
			$PONumber 	= date('Y').date('m')."0001";
		}
		/*$serNum = 1;
		$characters 		= '0123456789';
		$charactersLength 	= strlen($characters);
		do
		{
			$PONumber 	= date('Y').date('m');
			for ($i = 0; $i < 4; $i++)
			{
				$PONumber .= $characters[rand(0, $charactersLength - 1)];
			}
			
			$serNum =  mysqli_num_rows($DB->query("SELECT po_number FROM `inv_qne_purchase_order` WHERE `po_number` = '" . $PONumber . "'"));
		}
		while($serNum > 0);*/
		return $PONumber;
	}
	
	function getPOVersion($PONumber)
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT version FROM `inv_qne_purchase_order` WHERE `po_number` = '" . $PONumber . "' ORDER BY po_number DESC LIMIT 1";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet	=	mysqli_fetch_object($conn);
			return $version	=	(int)$fet->version + 1;
		}
		else
		{
			return 0;
		}
	}
	
	function companyCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(company_id) as totalCompany FROM `inv_qne_company` WHERE `status` = '1' AND `is_delete` = 'n'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $company	= $fet->totalCompany;
		}
		else
		{
			return 0;
		}
	}
	
	function brandCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(*) as totalBrand FROM `inv_qne_brand` WHERE `status` = '1'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $brands = $fet->totalBrand;
		}
		else
		{
			return 0;
		}
	}
	
	public function productCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(product_id) as totalProduct FROM `inv_qne_products` WHERE `status` = '1' AND `is_delete` = 0";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $products = $fet->totalProduct;
		}
		else
		{
			return 0;
		}
	}

	public function productCountByLocation($locationID=0)
	{
		$DB			= 	new DB_connection();

		if($locationID != 0)
		{
			$sql 	=  "SELECT count(product_id) as totalProduct FROM `inv_qne_products` WHERE `status` = '1' AND `is_delete` = 0 AND `product_location` = " . $locationID;
		}
		else
		{
			$sql 	=  "SELECT count(product_id) as totalProduct FROM `inv_qne_products` WHERE `status` = '1' AND `is_delete` = 0";
		}
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $products = $fet->totalProduct;
		}
		else
		{
			return 0;
		}
	}
	
	public function orderCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(order_id) as totalOrders FROM `inv_qne_orders` WHERE `status` = '1'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $fet->totalOrders;
		}
		else
		{
			return 0;
		}
	}
	
	public function locationCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(location_id) as totalLocations FROM `inv_qne_locations` WHERE `status` = '1'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $locations = $fet->totalLocations;
		}
		else
		{
			return 0;
		}
	}
	
	public function binLocationCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(bin_id) as totalLocations FROM `inv_qne_bin_locations` WHERE `status` = '1'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $locations = $fet->totalLocations;
		}
		else
		{
			return 0;
		}
	}
	
	public function usersCount()
	{
		$DB		= 	new DB_connection();
		$sql 	=  "SELECT count(user_id) as totalUsers FROM `inv_qne_users` ";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $user_count = $fet->totalUsers;
		}
		else
		{
			return 0;
		}
	}
    
    function bundleCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(product_id) as totalProduct FROM `inv_qne_products` WHERE `status` = '1'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $products = $fet->totalProduct;
		}
		else
		{
			return 0;
		}
	}
	
	function productSKUCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(sku_id) as totalSKU FROM `inv_qne_product_sku` WHERE `status` = '1'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $products = $fet->totalSKU;
		}
		else
		{
			return 0;
		}
	}
    
    function productInActiveSKUCount()
	{
		$DB			= 	new DB_connection();
		$sql 	=  "SELECT count(sku_id) as totalSKU FROM `inv_qne_product_sku` WHERE `status` = '0'";
		$conn	=	$DB->query($sql);
		
		if(mysqli_num_rows($conn) > 0)
		{
			$fet = mysqli_fetch_object($conn);
			return $products = $fet->totalSKU;
		}
		else
		{
			return 0;
		}
	}
}
?>