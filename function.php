<?php

//function.php

function base_url()
{
	// http://localhost/library_management_system/
	return 'http://localhost/library_management_system/';
}

function is_admin_login()
{
	if(isset($_SESSION['admin_id']))
	{
		return true;
	}
	return false;
}

function is_user_login()
{
	if(isset($_SESSION['user_id']))
	{
		return true;
	}
	return false;
}

function set_timezone($connect)
{
	$query = "
	SELECT library_timezone FROM lms_setting 
	LIMIT 1
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		date_default_timezone_set($row["library_timezone"]);
	}
}

function get_date_time($connect)
{
	set_timezone($connect);

	return date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
}

function get_one_day_fines($connect)
{
	$output = 0;
	$query = "
	SELECT library_one_day_fine FROM lms_setting 
	LIMIT 1
	";
	$result = $connect->query($query);
	foreach($result as $row)
	{
		$output = $row["library_one_day_fine"];
	}
	return $output;
}

function get_currency_symbol($connect)
{
	$output = '';
	$query = "
	SELECT library_currency FROM lms_setting 
	LIMIT 1
	";
	$result = $connect->query($query);
	foreach($result as $row)
	{
		$currency_data = currency_array();
		foreach($currency_data as $currency)
		{
			if($currency["code"] == $row['library_currency'])
			{
				$output = '<span style="font-family: DejaVu Sans;">' . $currency["symbol"] . '</span>&nbsp;';
			}
		}		
	}
	return $output;
}

function get_book_issue_limit_per_user($connect)
{
	$output = '';
	$query = "
	SELECT library_issue_total_book_per_user FROM lms_setting 
	LIMIT 1
	";
	$result = $connect->query($query);
	foreach($result as $row)
	{
		$output = $row["library_issue_total_book_per_user"];
	}
	return $output;
}

function get_total_book_issue_per_user($connect, $user_unique_id)
{
	$output = 0;

	$query = "
	SELECT COUNT(issue_book_id) AS Total FROM lms_issue_book 
	WHERE user_id = '".$user_unique_id."' 
	AND book_issue_status = 'Issue'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$output = $row["Total"];
	}
	return $output;
}

function get_total_book_issue_day($connect)
{
	$output = 0;

	$query = "
	SELECT library_total_book_issue_day FROM lms_setting 
	LIMIT 1
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$output = $row["library_total_book_issue_day"];
	}
	return $output;
}

function convert_data($string, $action = 'encrypt')
{
	$encrypt_method = "AES-256-CBC";
	$secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
	$secret_iv = '5fgf5HJ5g27'; // user define secret key
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
	if ($action == 'encrypt') 
	{
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	    $output = base64_encode($output);
	} 
	else if ($action == 'decrypt') 
	{
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}
	return $output;
}

function currency_array()
	{
		$currencies = array(
			array('code'=> 'ALL',
			'countryname'=> 'Albania',
			'name'=> 'Albanian lek',
			'symbol'=> 'L'),

			array('code'=> 'AFN',
			    'countryname'=> 'Afghanistan',
			    'name'=> 'Afghanistan Afghani',
			    'symbol'=> '&#1547;'),

			array('code'=> 'ARS',
			    'countryname'=> 'Argentina',
			    'name'=> 'Argentine Peso',
			    'symbol'=> '&#36;'),

			array('code'=> 'AWG',
			    'countryname'=> 'Aruba',
			    'name'=> 'Aruban florin',
			    'symbol'=> '&#402;'),

			array('code'=> 'AUD',
			    'countryname'=> 'Australia',
			    'name'=> 'Australian Dollar',
			    'symbol'=> '&#65;&#36;'),

			array('code'=> 'AZN',
			    'countryname'=> 'Azerbaijan',
			    'name'=> 'Azerbaijani Manat',
			    'symbol'=> '&#8380;'),

			array('code'=> 'BSD',
			    'countryname'=> 'The Bahamas',
			    'name'=> 'Bahamas Dollar',
			    'symbol'=> '&#66;&#36;'),

			array('code'=> 'BBD',
			    'countryname'=> 'Barbados',
			    'name'=> 'Barbados Dollar',
			    'symbol'=> '&#66;&#100;&#115;&#36;'),

			array('code'=> 'BDT',
			    'countryname'=> 'People\'s Republic of Bangladesh',
			    'name'=> 'Bangladeshi taka',
			    'symbol'=> '&#2547;'),

			array('code'=> 'BYN',
			    'countryname'=> 'Belarus',
			    'name'=> 'Belarus Ruble',
			    'symbol'=> '&#66;&#114;'),

			array('code'=> 'BZD',
			    'countryname'=> 'Belize',
			    'name'=> 'Belize Dollar',
			    'symbol'=> '&#66;&#90;&#36;'),

			array('code'=> 'BMD',
			    'countryname'=> 'British Overseas Territory of Bermuda',
			    'name'=> 'Bermudian Dollar',
			    'symbol'=> '&#66;&#68;&#36;'),

			array('code'=> 'BOP',
			    'countryname'=> 'Bolivia',
			    'name'=> 'Boliviano',
			    'symbol'=> '&#66;&#115;'),

			array('code'=> 'BAM',
			    'countryname'=> 'Bosnia and Herzegovina',
			    'name'=> 'Bosnia-Herzegovina Convertible Marka',
			    'symbol'=> '&#75;&#77;'),

			array('code'=> 'BWP',
			    'countryname'=> 'Botswana',
			    'name'=> 'Botswana pula',
			    'symbol'=> '&#80;'),

			array('code'=> 'BGN',
			    'countryname'=> 'Bulgaria',
			    'name'=> 'Bulgarian lev',
			    'symbol'=> '&#1083;&#1074;'),

			array('code'=> 'BRL',
			    'countryname'=> 'Brazil',
			    'name'=> 'Brazilian real',
			    'symbol'=> '&#82;&#36;'),

			array('code'=> 'BND',
			    'countryname'=> 'Sultanate of Brunei',
			    'name'=> 'Brunei dollar',
			    'symbol'=> '&#66;&#36;'),

			array('code'=> 'KHR',
			    'countryname'=> 'Cambodia',
			    'name'=> 'Cambodian riel',
			    'symbol'=> '&#6107;'),

			array('code'=> 'CAD',
			    'countryname'=> 'Canada',
			    'name'=> 'Canadian dollar',
			    'symbol'=> '&#67;&#36;'),

			array('code'=> 'KYD',
			    'countryname'=> 'Cayman Islands',
			    'name'=> 'Cayman Islands dollar',
			    'symbol'=> '&#36;'),

			array('code'=> 'CLP',
			    'countryname'=> 'Chile',
			    'name'=> 'Chilean peso',
			    'symbol'=> '&#36;'),

			array('code'=> 'CNY',
			    'countryname'=> 'China',
			    'name'=> 'Chinese Yuan Renminbi',
			    'symbol'=> '&#165;'),

			array('code'=> 'COP',
			    'countryname'=> 'Colombia',
			    'name'=> 'Colombian peso',
			    'symbol'=> '&#36;'),

			array('code'=> 'CRC',
			    'countryname'=> 'Costa Rica',
			    'name'=> 'Costa Rican colón',
			    'symbol'=> '&#8353;'),

			array('code'=> 'HRK',
			    'countryname'=> 'Croatia',
			    'name'=> 'Croatian kuna',
			    'symbol'=> '&#107;&#110;'),

			array('code'=> 'CUP',
			    'countryname'=> 'Cuba',
			    'name'=> 'Cuban peso',
			    'symbol'=> '&#8369;'),

			array('code'=> 'CZK',
			    'countryname'=> 'Czech Republic',
			    'name'=> 'Czech koruna',
			    'symbol'=> '&#75;&#269;'),

			array('code'=> 'DKK',
			    'countryname'=> 'Denmark, Greenland, and the Faroe Islands',
			    'name'=> 'Danish krone',
			    'symbol'=> '&#107;&#114;'),

			array('code'=> 'DOP',
			    'countryname'=> 'Dominican Republic',
			    'name'=> 'Dominican peso',
			    'symbol'=> '&#82;&#68;&#36;'),

			array('code'=> 'XCD',
			    'countryname'=> 'Antigua and Barbuda, Commonwealth of Dominica, Grenada, Montserrat, St. Kitts and Nevis, Saint Lucia and St. Vincent and the Grenadines',
			    'name'=> 'Eastern Caribbean dollar',
			    'symbol'=> '&#36;'),

			array('code'=> 'EGP',
			    'countryname'=> 'Egypt',
			    'name'=> 'Egyptian pound',
			    'symbol'=> '&#163;'),

			array('code'=> 'SVC',
			    'countryname'=> 'El Salvador',
			    'name'=> 'Salvadoran colón',
			    'symbol'=> '&#36;'),

			array('code'=> 'EEK',
			    'countryname'=> 'Estonia',
			    'name'=> 'Estonian kroon',
			    'symbol'=> '&#75;&#114;'),

			array('code'=> 'EUR',
			    'countryname'=> 'European Union, Italy, Belgium, Bulgaria, Croatia, Cyprus, Czechia, Denmark, Estonia, Finland, France, Germany, Greece, Hungary, Ireland, Latvia, Lithuania, Luxembourg, Malta, Netherlands, Poland, Portugal, Romania, Slovakia, Slovenia, Spain, Sweden',
			    'name'=> 'Euro',
			    'symbol'=> '&#8364;'),

			array('code'=> 'FKP',
			    'countryname'=> 'Falkland Islands',
			    'name'=> 'Falkland Islands (Malvinas) Pound',
			    'symbol'=> '&#70;&#75;&#163;'),

			array('code'=> 'FJD',
			    'countryname'=> 'Fiji',
			    'name'=> 'Fijian dollar',
			    'symbol'=> '&#70;&#74;&#36;'),

			array('code'=> 'GHC',
			    'countryname'=> 'Ghana',
			    'name'=> 'Ghanaian cedi',
			    'symbol'=> '&#71;&#72;&#162;'),

			array('code'=> 'GIP',
			    'countryname'=> 'Gibraltar',
			    'name'=> 'Gibraltar pound',
			    'symbol'=> '&#163;'),

			array('code'=> 'GTQ',
			    'countryname'=> 'Guatemala',
			    'name'=> 'Guatemalan quetzal',
			    'symbol'=> '&#81;'),

			array('code'=> 'GGP',
			    'countryname'=> 'Guernsey',
			    'name'=> 'Guernsey pound',
			    'symbol'=> '&#81;'),

			array('code'=> 'GYD',
			    'countryname'=> 'Guyana',
			    'name'=> 'Guyanese dollar',
			    'symbol'=> '&#71;&#89;&#36;'),

			array('code'=> 'HNL',
			    'countryname'=> 'Honduras',
			    'name'=> 'Honduran lempira',
			    'symbol'=> '&#76;'),

			array('code'=> 'HKD',
			    'countryname'=> 'Hong Kong',
			    'name'=> 'Hong Kong dollar',
			    'symbol'=> '&#72;&#75;&#36;'),

			array('code'=> 'HUF',
			    'countryname'=> 'Hungary',
			    'name'=> 'Hungarian forint',
			    'symbol'=> '&#70;&#116;'),

			array('code'=> 'ISK',
			    'countryname'=> 'Iceland',
			    'name'=> 'Icelandic króna',
			    'symbol'=> '&#237;&#107;&#114;'),

			array('code'=> 'INR',
			    'countryname'=> 'India',
			    'name'=> 'Indian rupee',
			    'symbol'=> '&#8377;'),

			array('code'=> 'IDR',
			    'countryname'=> 'Indonesia',
			    'name'=> 'Indonesian rupiah',
			    'symbol'=> '&#82;&#112;'),

			array('code'=> 'IRR',
			    'countryname'=> 'Iran',
			    'name'=> 'Iranian rial',
			    'symbol'=> '&#65020;'),

			array('code'=> 'IMP',
			    'countryname'=> 'Isle of Man',
			    'name'=> 'Manx pound',
			    'symbol'=> '&#163;'),

			array('code'=> 'ILS',
			    'countryname'=> 'Israel, Palestinian territories of the West Bank and the Gaza Strip',
			    'name'=> 'Israeli Shekel',
			    'symbol'=> '&#8362;'),

			array('code'=> 'JMD',
			    'countryname'=> 'Jamaica',
			    'name'=> 'Jamaican dollar',
			    'symbol'=> '&#74;&#36;'),

			array('code'=> 'JPY',
			    'countryname'=> 'Japan',
			    'name'=> 'Japanese yen',
			    'symbol'=> '&#165;'),

			array('code'=> 'JEP',
			    'countryname'=> 'Jersey',
			    'name'=> 'Jersey pound',
			    'symbol'=> '&#163;'),

			array('code'=> 'KZT',
			    'countryname'=> 'Kazakhstan',
			    'name'=> 'Kazakhstani tenge',
			    'symbol'=> '&#8376;'),

			array('code'=> 'KPW',
			    'countryname'=> 'North Korea',
			    'name'=> 'North Korean won',
			    'symbol'=> '&#8361;'),

			array('code'=> 'KPW',
			    'countryname'=> 'South Korea',
			    'name'=> 'South Korean won',
			    'symbol'=> '&#8361;'),

			array('code'=> 'KGS',
			    'countryname'=> 'Kyrgyz Republic',
			    'name'=> 'Kyrgyzstani som',
			    'symbol'=> '&#1083;&#1074;'),

			array('code'=> 'LAK',
			    'countryname'=> 'Laos',
			    'name'=> 'Lao kip',
			    'symbol'=> '&#8365;'),

			array('code'=> 'LAK',
			    'countryname'=> 'Laos',
			    'name'=> 'Latvian lats',
			    'symbol'=> '&#8364;'),

			array('code'=> 'LVL',
			    'countryname'=> 'Laos',
			    'name'=> 'Latvian lats',
			    'symbol'=> '&#8364;'),

			array('code'=> 'LBP',
			    'countryname'=> 'Lebanon',
			    'name'=> 'Lebanese pound',
			    'symbol'=> '&#76;&#163;'),

			array('code'=> 'LRD',
			    'countryname'=> 'Liberia',
			    'name'=> 'Liberian dollar',
			    'symbol'=> '&#76;&#68;&#36;'),

			array('code'=> 'LTL',
			    'countryname'=> 'Lithuania',
			    'name'=> 'Lithuanian litas',
			    'symbol'=> '&#8364;'),

			array('code'=> 'MKD',
			    'countryname'=> 'North Macedonia',
			    'name'=> 'Macedonian denar',
			    'symbol'=> '&#1076;&#1077;&#1085;'),

			array('code'=> 'MYR',
			    'countryname'=> 'Malaysia',
			    'name'=> 'Malaysian ringgit',
			    'symbol'=> '&#82;&#77;'),

			array('code'=> 'MUR',
			    'countryname'=> 'Mauritius',
			    'name'=> 'Mauritian rupee',
			    'symbol'=> '&#82;&#115;'),

			array('code'=> 'MXN',
			    'countryname'=> 'Mexico',
			    'name'=> 'Mexican peso',
			    'symbol'=> '&#77;&#101;&#120;&#36;'),

			array('code'=> 'MNT',
			    'countryname'=> 'Mongolia',
			    'name'=> 'Mongolian tögrög',
			    'symbol'=> '&#8366;'),

			array('code'=> 'MZN',
			    'countryname'=> 'Mozambique',
			    'name'=> 'Mozambican metical',
			    'symbol'=> '&#77;&#84;'),

			array('code'=> 'NAD',
			    'countryname'=> 'Namibia',
			    'name'=> 'Namibian dollar',
			    'symbol'=> '&#78;&#36;'),

			array('code'=> 'NPR',
			    'countryname'=> 'Federal Democratic Republic of Nepal',
			    'name'=> 'Nepalese rupee',
			    'symbol'=> '&#82;&#115;&#46;'),

			array('code'=> 'ANG',
			    'countryname'=> 'Curaçao and Sint Maarten',
			    'name'=> 'Netherlands Antillean guilder',
			    'symbol'=> '&#402;'),

			array('code'=> 'NZD',
			    'countryname'=> 'New Zealand, the Cook Islands, Niue, the Ross Dependency, Tokelau, the Pitcairn Islands',
			    'name'=> 'New Zealand dollar',
			    'symbol'=> '&#36;'),
			
			array('code'=> 'NIO',
			    'countryname'=> 'Nicaragua',
			    'name'=> 'Nicaraguan córdoba',
			    'symbol'=> '&#67;&#36;'),

			array('code'=> 'NGN',
			    'countryname'=> 'Nigeria',
			    'name'=> 'Nigerian naira',
			    'symbol'=> '&#8358;'),

			array('code'=> 'NOK',
			    'countryname'=> 'Norway and its dependent territories',
			    'name'=> 'Norwegian krone',
			    'symbol'=> '&#107;&#114;'),

			array('code'=> 'OMR',
			    'countryname'=> 'Oman',
			    'name'=> 'Omani rial',
			    'symbol'=> '&#65020;'),

			array('code'=> 'PKR',
			    'countryname'=> 'Pakistan',
			    'name'=> 'Pakistani rupee',
			    'symbol'=> '&#82;&#115;'),

			array('code'=> 'PAB',
			    'countryname'=> 'Panama',
			    'name'=> 'Panamanian balboa',
			    'symbol'=> '&#66;&#47;&#46;'),

			array('code'=> 'PYG',
			    'countryname'=> 'Paraguay',
			    'name'=> 'Paraguayan Guaraní',
			    'symbol'=> '&#8370;'),

			array('code'=> 'PEN',
			    'countryname'=> 'Peru',
			    'name'=> 'Sol',
			    'symbol'=> '&#83;&#47;&#46;'),

			array('code'=> 'PHP',
			    'countryname'=> 'Philippines',
			    'name'=> 'Philippine peso',
			    'symbol'=> '&#8369;'),

			array('code'=> 'PLN',
			    'countryname'=> 'Poland',
			    'name'=> 'Polish złoty',
			    'symbol'=> '&#122;&#322;'),

			array('code'=> 'QAR',
			    'countryname'=> 'State of Qatar',
			    'name'=> 'Qatari Riyal',
			    'symbol'=> '&#65020;'),

			array('code'=> 'RON',
			    'countryname'=> 'Romania',
			    'name'=> 'Romanian leu (Leu românesc)',
			    'symbol'=> '&#76;'),

			array('code'=> 'RUB',
			    'countryname'=> 'Russian Federation, Abkhazia and South Ossetia, Donetsk and Luhansk',
			    'name'=> 'Russian ruble',
			    'symbol'=> '&#8381;'),

			array('code'=> 'SHP',
			    'countryname'=> 'Saint Helena, Ascension and Tristan da Cunha',
			    'name'=> 'Saint Helena pound',
			    'symbol'=> '&#163;'),

			array('code'=> 'SAR',
			    'countryname'=> 'Saudi Arabia',
			    'name'=> 'Saudi riyal',
			    'symbol'=> '&#65020;'),

			array('code'=> 'RSD',
			    'countryname'=> 'Serbia',
			    'name'=> 'Serbian dinar',
			    'symbol'=> '&#100;&#105;&#110;'),

			array('code'=> 'SCR',
			    'countryname'=> 'Seychelles',
			    'name'=> 'Seychellois rupee',
			    'symbol'=> '&#82;&#115;'),

			array('code'=> 'SGD',
			    'countryname'=> 'Singapore',
			    'name'=> 'Singapore dollar',
			    'symbol'=> '&#83;&#36;'),

			array('code'=> 'SBD',
			    'countryname'=> 'Solomon Islands',
			    'name'=> 'Solomon Islands dollar',
			    'symbol'=> '&#83;&#73;&#36;'),

			array('code'=> 'SOS',
			    'countryname'=> 'Somalia',
			    'name'=> 'Somali shilling',
			    'symbol'=> '&#83;&#104;&#46;&#83;&#111;'),

			array('code'=> 'ZAR',
			    'countryname'=> 'South Africa',
			    'name'=> 'South African rand',
			    'symbol'=> '&#82;'),

			array('code'=> 'LKR',
			    'countryname'=> 'Sri Lanka',
			    'name'=> 'Sri Lankan rupee',
			    'symbol'=> '&#82;&#115;'),
		
			array('code'=> 'SEK',
			    'countryname'=> 'Sweden',
			    'name'=> 'Swedish krona',
			    'symbol'=> '&#107;&#114;'),


			array('code'=> 'CHF',
			    'countryname'=> 'Switzerland',
			    'name'=> 'Swiss franc',
			    'symbol'=> '&#67;&#72;&#102;'),

			array('code'=> 'SRD',
			    'countryname'=> 'Suriname',
			    'name'=> 'Suriname Dollar',
			    'symbol'=> '&#83;&#114;&#36;'),

			array('code'=> 'SYP',
			    'countryname'=> 'Syria',
			    'name'=> 'Syrian pound',
			    'symbol'=> '&#163;&#83;'),

			array('code'=> 'TWD',
			    'countryname'=> 'Taiwan',
			    'name'=> 'New Taiwan dollar',
			    'symbol'=> '&#78;&#84;&#36;'),

			array('code'=> 'THB',
			    'countryname'=> 'Thailand',
			    'name'=> 'Thai baht',
			    'symbol'=> '&#3647;'),


			array('code'=> 'TTD',
			    'countryname'=> 'Trinidad and Tobago',
			    'name'=> 'Trinidad and Tobago dollar',
			    'symbol'=> '&#84;&#84;&#36;'),

			array('code'=> 'TRY',
			    'countryname'=> 'Turkey, Turkish Republic of Northern Cyprus',
			    'name'=> 'Turkey Lira',
			    'symbol'=> '&#8378;'),

			array('code'=> 'TVD',
			    'countryname'=> 'Tuvalu',
			    'name'=> 'Tuvaluan dollar',
			    'symbol'=> '&#84;&#86;&#36;'),

			array('code'=> 'UAH',
			    'countryname'=> 'Ukraine',
			    'name'=> 'Ukrainian hryvnia',
			    'symbol'=> '&#8372;'),

			array('code'=> 'GBP',
			    'countryname'=> 'United Kingdom, Jersey, Guernsey, the Isle of Man, Gibraltar, South Georgia and the South Sandwich Islands, the British Antarctic Territory, and Tristan da Cunha',
			    'name'=> 'Pound sterling',
			    'symbol'=> '&#163;'),

			array('code'=> 'UGX',
			    'countryname'=> 'Uganda',
			    'name'=> 'Ugandan shilling',
			    'symbol'=> '&#85;&#83;&#104;'),

			array('code'=> 'USD',
			    'countryname'=> 'United States',
			    'name'=> 'United States dollar',
			    'symbol'=> '&#36;'),

			array('code'=> 'UYU',
			    'countryname'=> 'Uruguayan',
			    'name'=> 'Peso Uruguayolar',
			    'symbol'=> '&#36;&#85;'),

			array('code'=> 'UZS',
			    'countryname'=> 'Uzbekistan',
			    'name'=> 'Uzbekistani soʻm',
			    'symbol'=> '&#1083;&#1074;'),

			array('code'=> 'VEF',
			    'countryname'=> 'Venezuela',
			    'name'=> 'Venezuelan bolívar',
			    'symbol'=> '&#66;&#115;'),

			array('code'=> 'VND',
			    'countryname'=> 'Vietnam',
			    'name'=> 'Vietnamese dong (Đồng)',
			    'symbol'=> '&#8363;'),

			array('code'=> 'VND',
			    'countryname'=> 'Yemen',
			    'name'=> 'Yemeni rial',
			    'symbol'=> '&#65020;'),

			array('code'=> 'ZWD',
			    'countryname'=> 'Zimbabwe',
			    'name'=> 'Zimbabwean dollar',
			    'symbol'=> '&#90;&#36;'),
		);
		
		return $currencies;
	}

	function Currency_list()
	{
		$html = '
			<option value="">Select Currency</option>
		';
		$data = currency_array();
		foreach($data as $row)
		{
			$html .= '<option value="'.$row["code"].'">'.$row["name"].'</option>';
		}
		return $html;
	}

	function Timezone_list()
	{
		$timezones = array(
		    'America/Adak' => '(GMT-10:00) America/Adak (Hawaii-Aleutian Standard Time)',
			'America/Atka' => '(GMT-10:00) America/Atka (Hawaii-Aleutian Standard Time)',
			'America/Anchorage' => '(GMT-9:00) America/Anchorage (Alaska Standard Time)',
			'America/Juneau' => '(GMT-9:00) America/Juneau (Alaska Standard Time)',
			'America/Nome' => '(GMT-9:00) America/Nome (Alaska Standard Time)',
			'America/Yakutat' => '(GMT-9:00) America/Yakutat (Alaska Standard Time)',
			'America/Dawson' => '(GMT-8:00) America/Dawson (Pacific Standard Time)',
			'America/Ensenada' => '(GMT-8:00) America/Ensenada (Pacific Standard Time)',
			'America/Los_Angeles' => '(GMT-8:00) America/Los_Angeles (Pacific Standard Time)',
			'America/Tijuana' => '(GMT-8:00) America/Tijuana (Pacific Standard Time)',
			'America/Vancouver' => '(GMT-8:00) America/Vancouver (Pacific Standard Time)',
			'America/Whitehorse' => '(GMT-8:00) America/Whitehorse (Pacific Standard Time)',
			'Canada/Pacific' => '(GMT-8:00) Canada/Pacific (Pacific Standard Time)',
			'Canada/Yukon' => '(GMT-8:00) Canada/Yukon (Pacific Standard Time)',
			'Mexico/BajaNorte' => '(GMT-8:00) Mexico/BajaNorte (Pacific Standard Time)',
			'America/Boise' => '(GMT-7:00) America/Boise (Mountain Standard Time)',
			'America/Cambridge_Bay' => '(GMT-7:00) America/Cambridge_Bay (Mountain Standard Time)',
			'America/Chihuahua' => '(GMT-7:00) America/Chihuahua (Mountain Standard Time)',
			'America/Dawson_Creek' => '(GMT-7:00) America/Dawson_Creek (Mountain Standard Time)',
			'America/Denver' => '(GMT-7:00) America/Denver (Mountain Standard Time)',
			'America/Edmonton' => '(GMT-7:00) America/Edmonton (Mountain Standard Time)',
			'America/Hermosillo' => '(GMT-7:00) America/Hermosillo (Mountain Standard Time)',
			'America/Inuvik' => '(GMT-7:00) America/Inuvik (Mountain Standard Time)',
			'America/Mazatlan' => '(GMT-7:00) America/Mazatlan (Mountain Standard Time)',
			'America/Phoenix' => '(GMT-7:00) America/Phoenix (Mountain Standard Time)',
			'America/Shiprock' => '(GMT-7:00) America/Shiprock (Mountain Standard Time)',
			'America/Yellowknife' => '(GMT-7:00) America/Yellowknife (Mountain Standard Time)',
			'Canada/Mountain' => '(GMT-7:00) Canada/Mountain (Mountain Standard Time)',
			'Mexico/BajaSur' => '(GMT-7:00) Mexico/BajaSur (Mountain Standard Time)',
			'America/Belize' => '(GMT-6:00) America/Belize (Central Standard Time)',
			'America/Cancun' => '(GMT-6:00) America/Cancun (Central Standard Time)',
			'America/Chicago' => '(GMT-6:00) America/Chicago (Central Standard Time)',
			'America/Costa_Rica' => '(GMT-6:00) America/Costa_Rica (Central Standard Time)',
			'America/El_Salvador' => '(GMT-6:00) America/El_Salvador (Central Standard Time)',
			'America/Guatemala' => '(GMT-6:00) America/Guatemala (Central Standard Time)',
			'America/Knox_IN' => '(GMT-6:00) America/Knox_IN (Central Standard Time)',
			'America/Managua' => '(GMT-6:00) America/Managua (Central Standard Time)',
			'America/Menominee' => '(GMT-6:00) America/Menominee (Central Standard Time)',
			'America/Merida' => '(GMT-6:00) America/Merida (Central Standard Time)',
			'America/Mexico_City' => '(GMT-6:00) America/Mexico_City (Central Standard Time)',
			'America/Monterrey' => '(GMT-6:00) America/Monterrey (Central Standard Time)',
			'America/Rainy_River' => '(GMT-6:00) America/Rainy_River (Central Standard Time)',
			'America/Rankin_Inlet' => '(GMT-6:00) America/Rankin_Inlet (Central Standard Time)',
			'America/Regina' => '(GMT-6:00) America/Regina (Central Standard Time)',
			'America/Swift_Current' => '(GMT-6:00) America/Swift_Current (Central Standard Time)',
			'America/Tegucigalpa' => '(GMT-6:00) America/Tegucigalpa (Central Standard Time)',
			'America/Winnipeg' => '(GMT-6:00) America/Winnipeg (Central Standard Time)',
			'Canada/Central' => '(GMT-6:00) Canada/Central (Central Standard Time)',
			'Canada/East-Saskatchewan' => '(GMT-6:00) Canada/East-Saskatchewan (Central Standard Time)',
			'Canada/Saskatchewan' => '(GMT-6:00) Canada/Saskatchewan (Central Standard Time)',
			'Chile/EasterIsland' => '(GMT-6:00) Chile/EasterIsland (Easter Is. Time)',
			'Mexico/General' => '(GMT-6:00) Mexico/General (Central Standard Time)',
			'America/Atikokan' => '(GMT-5:00) America/Atikokan (Eastern Standard Time)',
			'America/Bogota' => '(GMT-5:00) America/Bogota (Colombia Time)',
			'America/Cayman' => '(GMT-5:00) America/Cayman (Eastern Standard Time)',
			'America/Coral_Harbour' => '(GMT-5:00) America/Coral_Harbour (Eastern Standard Time)',
			'America/Detroit' => '(GMT-5:00) America/Detroit (Eastern Standard Time)',
			'America/Fort_Wayne' => '(GMT-5:00) America/Fort_Wayne (Eastern Standard Time)',
			'America/Grand_Turk' => '(GMT-5:00) America/Grand_Turk (Eastern Standard Time)',
			'America/Guayaquil' => '(GMT-5:00) America/Guayaquil (Ecuador Time)',
			'America/Havana' => '(GMT-5:00) America/Havana (Cuba Standard Time)',
			'America/Indianapolis' => '(GMT-5:00) America/Indianapolis (Eastern Standard Time)',
			'America/Iqaluit' => '(GMT-5:00) America/Iqaluit (Eastern Standard Time)',
			'America/Jamaica' => '(GMT-5:00) America/Jamaica (Eastern Standard Time)',
			'America/Lima' => '(GMT-5:00) America/Lima (Peru Time)',
			'America/Louisville' => '(GMT-5:00) America/Louisville (Eastern Standard Time)',
			'America/Montreal' => '(GMT-5:00) America/Montreal (Eastern Standard Time)',
			'America/Nassau' => '(GMT-5:00) America/Nassau (Eastern Standard Time)',
			'America/New_York' => '(GMT-5:00) America/New_York (Eastern Standard Time)',
			'America/Nipigon' => '(GMT-5:00) America/Nipigon (Eastern Standard Time)',
			'America/Panama' => '(GMT-5:00) America/Panama (Eastern Standard Time)',
			'America/Pangnirtung' => '(GMT-5:00) America/Pangnirtung (Eastern Standard Time)',
			'America/Port-au-Prince' => '(GMT-5:00) America/Port-au-Prince (Eastern Standard Time)',
			'America/Resolute' => '(GMT-5:00) America/Resolute (Eastern Standard Time)',
			'America/Thunder_Bay' => '(GMT-5:00) America/Thunder_Bay (Eastern Standard Time)',
			'America/Toronto' => '(GMT-5:00) America/Toronto (Eastern Standard Time)',
			'Canada/Eastern' => '(GMT-5:00) Canada/Eastern (Eastern Standard Time)',
			'America/Caracas' => '(GMT-4:-30) America/Caracas (Venezuela Time)',
			'America/Anguilla' => '(GMT-4:00) America/Anguilla (Atlantic Standard Time)',
			'America/Antigua' => '(GMT-4:00) America/Antigua (Atlantic Standard Time)',
			'America/Aruba' => '(GMT-4:00) America/Aruba (Atlantic Standard Time)',
			'America/Asuncion' => '(GMT-4:00) America/Asuncion (Paraguay Time)',
			'America/Barbados' => '(GMT-4:00) America/Barbados (Atlantic Standard Time)',
			'America/Blanc-Sablon' => '(GMT-4:00) America/Blanc-Sablon (Atlantic Standard Time)',
			'America/Boa_Vista' => '(GMT-4:00) America/Boa_Vista (Amazon Time)',
			'America/Campo_Grande' => '(GMT-4:00) America/Campo_Grande (Amazon Time)',
			'America/Cuiaba' => '(GMT-4:00) America/Cuiaba (Amazon Time)',
			'America/Curacao' => '(GMT-4:00) America/Curacao (Atlantic Standard Time)',
			'America/Dominica' => '(GMT-4:00) America/Dominica (Atlantic Standard Time)',
			'America/Eirunepe' => '(GMT-4:00) America/Eirunepe (Amazon Time)',
			'America/Glace_Bay' => '(GMT-4:00) America/Glace_Bay (Atlantic Standard Time)',
			'America/Goose_Bay' => '(GMT-4:00) America/Goose_Bay (Atlantic Standard Time)',
			'America/Grenada' => '(GMT-4:00) America/Grenada (Atlantic Standard Time)',
			'America/Guadeloupe' => '(GMT-4:00) America/Guadeloupe (Atlantic Standard Time)',
			'America/Guyana' => '(GMT-4:00) America/Guyana (Guyana Time)',
			'America/Halifax' => '(GMT-4:00) America/Halifax (Atlantic Standard Time)',
			'America/La_Paz' => '(GMT-4:00) America/La_Paz (Bolivia Time)',
			'America/Manaus' => '(GMT-4:00) America/Manaus (Amazon Time)',
			'America/Marigot' => '(GMT-4:00) America/Marigot (Atlantic Standard Time)',
			'America/Martinique' => '(GMT-4:00) America/Martinique (Atlantic Standard Time)',
			'America/Moncton' => '(GMT-4:00) America/Moncton (Atlantic Standard Time)',
			'America/Montserrat' => '(GMT-4:00) America/Montserrat (Atlantic Standard Time)',
			'America/Port_of_Spain' => '(GMT-4:00) America/Port_of_Spain (Atlantic Standard Time)',
			'America/Porto_Acre' => '(GMT-4:00) America/Porto_Acre (Amazon Time)',
			'America/Porto_Velho' => '(GMT-4:00) America/Porto_Velho (Amazon Time)',
			'America/Puerto_Rico' => '(GMT-4:00) America/Puerto_Rico (Atlantic Standard Time)',
			'America/Rio_Branco' => '(GMT-4:00) America/Rio_Branco (Amazon Time)',
			'America/Santiago' => '(GMT-4:00) America/Santiago (Chile Time)',
			'America/Santo_Domingo' => '(GMT-4:00) America/Santo_Domingo (Atlantic Standard Time)',
			'America/St_Barthelemy' => '(GMT-4:00) America/St_Barthelemy (Atlantic Standard Time)',
			'America/St_Kitts' => '(GMT-4:00) America/St_Kitts (Atlantic Standard Time)',
			'America/St_Lucia' => '(GMT-4:00) America/St_Lucia (Atlantic Standard Time)',
			'America/St_Thomas' => '(GMT-4:00) America/St_Thomas (Atlantic Standard Time)',
			'America/St_Vincent' => '(GMT-4:00) America/St_Vincent (Atlantic Standard Time)',
			'America/Thule' => '(GMT-4:00) America/Thule (Atlantic Standard Time)',
			'America/Tortola' => '(GMT-4:00) America/Tortola (Atlantic Standard Time)',
			'America/Virgin' => '(GMT-4:00) America/Virgin (Atlantic Standard Time)',
			'Antarctica/Palmer' => '(GMT-4:00) Antarctica/Palmer (Chile Time)',
			'Atlantic/Bermuda' => '(GMT-4:00) Atlantic/Bermuda (Atlantic Standard Time)',
			'Atlantic/Stanley' => '(GMT-4:00) Atlantic/Stanley (Falkland Is. Time)',
			'Brazil/Acre' => '(GMT-4:00) Brazil/Acre (Amazon Time)',
			'Brazil/West' => '(GMT-4:00) Brazil/West (Amazon Time)',
			'Canada/Atlantic' => '(GMT-4:00) Canada/Atlantic (Atlantic Standard Time)',
			'Chile/Continental' => '(GMT-4:00) Chile/Continental (Chile Time)',
			'America/St_Johns' => '(GMT-3:-30) America/St_Johns (Newfoundland Standard Time)',
			'Canada/Newfoundland' => '(GMT-3:-30) Canada/Newfoundland (Newfoundland Standard Time)',
			'America/Araguaina' => '(GMT-3:00) America/Araguaina (Brasilia Time)',
			'America/Bahia' => '(GMT-3:00) America/Bahia (Brasilia Time)',
			'America/Belem' => '(GMT-3:00) America/Belem (Brasilia Time)',
			'America/Buenos_Aires' => '(GMT-3:00) America/Buenos_Aires (Argentine Time)',
			'America/Catamarca' => '(GMT-3:00) America/Catamarca (Argentine Time)',
			'America/Cayenne' => '(GMT-3:00) America/Cayenne (French Guiana Time)',
			'America/Cordoba' => '(GMT-3:00) America/Cordoba (Argentine Time)',
			'America/Fortaleza' => '(GMT-3:00) America/Fortaleza (Brasilia Time)',
			'America/Godthab' => '(GMT-3:00) America/Godthab (Western Greenland Time)',
			'America/Jujuy' => '(GMT-3:00) America/Jujuy (Argentine Time)',
			'America/Maceio' => '(GMT-3:00) America/Maceio (Brasilia Time)',
			'America/Mendoza' => '(GMT-3:00) America/Mendoza (Argentine Time)',
			'America/Miquelon' => '(GMT-3:00) America/Miquelon (Pierre & Miquelon Standard Time)',
			'America/Montevideo' => '(GMT-3:00) America/Montevideo (Uruguay Time)',
			'America/Paramaribo' => '(GMT-3:00) America/Paramaribo (Suriname Time)',
			'America/Recife' => '(GMT-3:00) America/Recife (Brasilia Time)',
			'America/Rosario' => '(GMT-3:00) America/Rosario (Argentine Time)',
			'America/Santarem' => '(GMT-3:00) America/Santarem (Brasilia Time)',
			'America/Sao_Paulo' => '(GMT-3:00) America/Sao_Paulo (Brasilia Time)',
			'Antarctica/Rothera' => '(GMT-3:00) Antarctica/Rothera (Rothera Time)',
			'Brazil/East' => '(GMT-3:00) Brazil/East (Brasilia Time)',
			'America/Noronha' => '(GMT-2:00) America/Noronha (Fernando de Noronha Time)',
			'Atlantic/South_Georgia' => '(GMT-2:00) Atlantic/South_Georgia (South Georgia Standard Time)',
			'Brazil/DeNoronha' => '(GMT-2:00) Brazil/DeNoronha (Fernando de Noronha Time)',
			'America/Scoresbysund' => '(GMT-1:00) America/Scoresbysund (Eastern Greenland Time)',
			'Atlantic/Azores' => '(GMT-1:00) Atlantic/Azores (Azores Time)',
			'Atlantic/Cape_Verde' => '(GMT-1:00) Atlantic/Cape_Verde (Cape Verde Time)',
			'Africa/Abidjan' => '(GMT+0:00) Africa/Abidjan (Greenwich Mean Time)',
			'Africa/Accra' => '(GMT+0:00) Africa/Accra (Ghana Mean Time)',
			'Africa/Bamako' => '(GMT+0:00) Africa/Bamako (Greenwich Mean Time)',
			'Africa/Banjul' => '(GMT+0:00) Africa/Banjul (Greenwich Mean Time)',
			'Africa/Bissau' => '(GMT+0:00) Africa/Bissau (Greenwich Mean Time)',
			'Africa/Casablanca' => '(GMT+0:00) Africa/Casablanca (Western European Time)',
			'Africa/Conakry' => '(GMT+0:00) Africa/Conakry (Greenwich Mean Time)',
			'Africa/Dakar' => '(GMT+0:00) Africa/Dakar (Greenwich Mean Time)',
			'Africa/El_Aaiun' => '(GMT+0:00) Africa/El_Aaiun (Western European Time)',
			'Africa/Freetown' => '(GMT+0:00) Africa/Freetown (Greenwich Mean Time)',
			'Africa/Lome' => '(GMT+0:00) Africa/Lome (Greenwich Mean Time)',
			'Africa/Monrovia' => '(GMT+0:00) Africa/Monrovia (Greenwich Mean Time)',
			'Africa/Nouakchott' => '(GMT+0:00) Africa/Nouakchott (Greenwich Mean Time)',
			'Africa/Ouagadougou' => '(GMT+0:00) Africa/Ouagadougou (Greenwich Mean Time)',
			'Africa/Sao_Tome' => '(GMT+0:00) Africa/Sao_Tome (Greenwich Mean Time)',
			'Africa/Timbuktu' => '(GMT+0:00) Africa/Timbuktu (Greenwich Mean Time)',
			'America/Danmarkshavn' => '(GMT+0:00) America/Danmarkshavn (Greenwich Mean Time)',
			'Atlantic/Canary' => '(GMT+0:00) Atlantic/Canary (Western European Time)',
			'Atlantic/Faeroe' => '(GMT+0:00) Atlantic/Faeroe (Western European Time)',
			'Atlantic/Faroe' => '(GMT+0:00) Atlantic/Faroe (Western European Time)',
			'Atlantic/Madeira' => '(GMT+0:00) Atlantic/Madeira (Western European Time)',
			'Atlantic/Reykjavik' => '(GMT+0:00) Atlantic/Reykjavik (Greenwich Mean Time)',
			'Atlantic/St_Helena' => '(GMT+0:00) Atlantic/St_Helena (Greenwich Mean Time)',
			'Europe/Belfast' => '(GMT+0:00) Europe/Belfast (Greenwich Mean Time)',
			'Europe/Dublin' => '(GMT+0:00) Europe/Dublin (Greenwich Mean Time)',
			'Europe/Guernsey' => '(GMT+0:00) Europe/Guernsey (Greenwich Mean Time)',
			'Europe/Isle_of_Man' => '(GMT+0:00) Europe/Isle_of_Man (Greenwich Mean Time)',
			'Europe/Jersey' => '(GMT+0:00) Europe/Jersey (Greenwich Mean Time)',
			'Europe/Lisbon' => '(GMT+0:00) Europe/Lisbon (Western European Time)',
			'Europe/London' => '(GMT+0:00) Europe/London (Greenwich Mean Time)',
			'Africa/Algiers' => '(GMT+1:00) Africa/Algiers (Central European Time)',
			'Africa/Bangui' => '(GMT+1:00) Africa/Bangui (Western African Time)',
			'Africa/Brazzaville' => '(GMT+1:00) Africa/Brazzaville (Western African Time)',
			'Africa/Ceuta' => '(GMT+1:00) Africa/Ceuta (Central European Time)',
			'Africa/Douala' => '(GMT+1:00) Africa/Douala (Western African Time)',
			'Africa/Kinshasa' => '(GMT+1:00) Africa/Kinshasa (Western African Time)',
			'Africa/Lagos' => '(GMT+1:00) Africa/Lagos (Western African Time)',
			'Africa/Libreville' => '(GMT+1:00) Africa/Libreville (Western African Time)',
			'Africa/Luanda' => '(GMT+1:00) Africa/Luanda (Western African Time)',
			'Africa/Malabo' => '(GMT+1:00) Africa/Malabo (Western African Time)',
			'Africa/Ndjamena' => '(GMT+1:00) Africa/Ndjamena (Western African Time)',
			'Africa/Niamey' => '(GMT+1:00) Africa/Niamey (Western African Time)',
			'Africa/Porto-Novo' => '(GMT+1:00) Africa/Porto-Novo (Western African Time)',
			'Africa/Tunis' => '(GMT+1:00) Africa/Tunis (Central European Time)',
			'Africa/Windhoek' => '(GMT+1:00) Africa/Windhoek (Western African Time)',
			'Arctic/Longyearbyen' => '(GMT+1:00) Arctic/Longyearbyen (Central European Time)',
			'Atlantic/Jan_Mayen' => '(GMT+1:00) Atlantic/Jan_Mayen (Central European Time)',
			'Europe/Amsterdam' => '(GMT+1:00) Europe/Amsterdam (Central European Time)',
			'Europe/Andorra' => '(GMT+1:00) Europe/Andorra (Central European Time)',
			'Europe/Belgrade' => '(GMT+1:00) Europe/Belgrade (Central European Time)',
			'Europe/Berlin' => '(GMT+1:00) Europe/Berlin (Central European Time)',
			'Europe/Bratislava' => '(GMT+1:00) Europe/Bratislava (Central European Time)',
			'Europe/Brussels' => '(GMT+1:00) Europe/Brussels (Central European Time)',
			'Europe/Budapest' => '(GMT+1:00) Europe/Budapest (Central European Time)',
			'Europe/Copenhagen' => '(GMT+1:00) Europe/Copenhagen (Central European Time)',
			'Europe/Gibraltar' => '(GMT+1:00) Europe/Gibraltar (Central European Time)',
			'Europe/Ljubljana' => '(GMT+1:00) Europe/Ljubljana (Central European Time)',
			'Europe/Luxembourg' => '(GMT+1:00) Europe/Luxembourg (Central European Time)',
			'Europe/Madrid' => '(GMT+1:00) Europe/Madrid (Central European Time)',
			'Europe/Malta' => '(GMT+1:00) Europe/Malta (Central European Time)',
			'Europe/Monaco' => '(GMT+1:00) Europe/Monaco (Central European Time)',
			'Europe/Oslo' => '(GMT+1:00) Europe/Oslo (Central European Time)',
			'Europe/Paris' => '(GMT+1:00) Europe/Paris (Central European Time)',
			'Europe/Podgorica' => '(GMT+1:00) Europe/Podgorica (Central European Time)',
			'Europe/Prague' => '(GMT+1:00) Europe/Prague (Central European Time)',
			'Europe/Rome' => '(GMT+1:00) Europe/Rome (Central European Time)',
			'Europe/San_Marino' => '(GMT+1:00) Europe/San_Marino (Central European Time)',
			'Europe/Sarajevo' => '(GMT+1:00) Europe/Sarajevo (Central European Time)',
			'Europe/Skopje' => '(GMT+1:00) Europe/Skopje (Central European Time)',
			'Europe/Stockholm' => '(GMT+1:00) Europe/Stockholm (Central European Time)',
			'Europe/Tirane' => '(GMT+1:00) Europe/Tirane (Central European Time)',
			'Europe/Vaduz' => '(GMT+1:00) Europe/Vaduz (Central European Time)',
			'Europe/Vatican' => '(GMT+1:00) Europe/Vatican (Central European Time)',
			'Europe/Vienna' => '(GMT+1:00) Europe/Vienna (Central European Time)',
			'Europe/Warsaw' => '(GMT+1:00) Europe/Warsaw (Central European Time)',
			'Europe/Zagreb' => '(GMT+1:00) Europe/Zagreb (Central European Time)',
			'Europe/Zurich' => '(GMT+1:00) Europe/Zurich (Central European Time)',
			'Africa/Blantyre' => '(GMT+2:00) Africa/Blantyre (Central African Time)',
			'Africa/Bujumbura' => '(GMT+2:00) Africa/Bujumbura (Central African Time)',
			'Africa/Cairo' => '(GMT+2:00) Africa/Cairo (Eastern European Time)',
			'Africa/Gaborone' => '(GMT+2:00) Africa/Gaborone (Central African Time)',
			'Africa/Harare' => '(GMT+2:00) Africa/Harare (Central African Time)',
			'Africa/Johannesburg' => '(GMT+2:00) Africa/Johannesburg (South Africa Standard Time)',
			'Africa/Kigali' => '(GMT+2:00) Africa/Kigali (Central African Time)',
			'Africa/Lubumbashi' => '(GMT+2:00) Africa/Lubumbashi (Central African Time)',
			'Africa/Lusaka' => '(GMT+2:00) Africa/Lusaka (Central African Time)',
			'Africa/Maputo' => '(GMT+2:00) Africa/Maputo (Central African Time)',
			'Africa/Maseru' => '(GMT+2:00) Africa/Maseru (South Africa Standard Time)',
			'Africa/Mbabane' => '(GMT+2:00) Africa/Mbabane (South Africa Standard Time)',
			'Africa/Tripoli' => '(GMT+2:00) Africa/Tripoli (Eastern European Time)',
			'Asia/Amman' => '(GMT+2:00) Asia/Amman (Eastern European Time)',
			'Asia/Beirut' => '(GMT+2:00) Asia/Beirut (Eastern European Time)',
			'Asia/Damascus' => '(GMT+2:00) Asia/Damascus (Eastern European Time)',
			'Asia/Gaza' => '(GMT+2:00) Asia/Gaza (Eastern European Time)',
			'Asia/Istanbul' => '(GMT+2:00) Asia/Istanbul (Eastern European Time)',
			'Asia/Jerusalem' => '(GMT+2:00) Asia/Jerusalem (Israel Standard Time)',
			'Asia/Nicosia' => '(GMT+2:00) Asia/Nicosia (Eastern European Time)',
			'Asia/Tel_Aviv' => '(GMT+2:00) Asia/Tel_Aviv (Israel Standard Time)',
			'Europe/Athens' => '(GMT+2:00) Europe/Athens (Eastern European Time)',
			'Europe/Bucharest' => '(GMT+2:00) Europe/Bucharest (Eastern European Time)',
			'Europe/Chisinau' => '(GMT+2:00) Europe/Chisinau (Eastern European Time)',
			'Europe/Helsinki' => '(GMT+2:00) Europe/Helsinki (Eastern European Time)',
			'Europe/Istanbul' => '(GMT+2:00) Europe/Istanbul (Eastern European Time)',
			'Europe/Kaliningrad' => '(GMT+2:00) Europe/Kaliningrad (Eastern European Time)',
			'Europe/Kiev' => '(GMT+2:00) Europe/Kiev (Eastern European Time)',
			'Europe/Mariehamn' => '(GMT+2:00) Europe/Mariehamn (Eastern European Time)',
			'Europe/Minsk' => '(GMT+2:00) Europe/Minsk (Eastern European Time)',
			'Europe/Nicosia' => '(GMT+2:00) Europe/Nicosia (Eastern European Time)',
			'Europe/Riga' => '(GMT+2:00) Europe/Riga (Eastern European Time)',
			'Europe/Simferopol' => '(GMT+2:00) Europe/Simferopol (Eastern European Time)',
			'Europe/Sofia' => '(GMT+2:00) Europe/Sofia (Eastern European Time)',
			'Europe/Tallinn' => '(GMT+2:00) Europe/Tallinn (Eastern European Time)',
			'Europe/Tiraspol' => '(GMT+2:00) Europe/Tiraspol (Eastern European Time)',
			'Europe/Uzhgorod' => '(GMT+2:00) Europe/Uzhgorod (Eastern European Time)',
			'Europe/Vilnius' => '(GMT+2:00) Europe/Vilnius (Eastern European Time)',
			'Europe/Zaporozhye' => '(GMT+2:00) Europe/Zaporozhye (Eastern European Time)',
			'Africa/Addis_Ababa' => '(GMT+3:00) Africa/Addis_Ababa (Eastern African Time)',
			'Africa/Asmara' => '(GMT+3:00) Africa/Asmara (Eastern African Time)',
			'Africa/Asmera' => '(GMT+3:00) Africa/Asmera (Eastern African Time)',
			'Africa/Dar_es_Salaam' => '(GMT+3:00) Africa/Dar_es_Salaam (Eastern African Time)',
			'Africa/Djibouti' => '(GMT+3:00) Africa/Djibouti (Eastern African Time)',
			'Africa/Kampala' => '(GMT+3:00) Africa/Kampala (Eastern African Time)',
			'Africa/Khartoum' => '(GMT+3:00) Africa/Khartoum (Eastern African Time)',
			'Africa/Mogadishu' => '(GMT+3:00) Africa/Mogadishu (Eastern African Time)',
			'Africa/Nairobi' => '(GMT+3:00) Africa/Nairobi (Eastern African Time)',
			'Antarctica/Syowa' => '(GMT+3:00) Antarctica/Syowa (Syowa Time)',
			'Asia/Aden' => '(GMT+3:00) Asia/Aden (Arabia Standard Time)',
			'Asia/Baghdad' => '(GMT+3:00) Asia/Baghdad (Arabia Standard Time)',
			'Asia/Bahrain' => '(GMT+3:00) Asia/Bahrain (Arabia Standard Time)',
			'Asia/Kuwait' => '(GMT+3:00) Asia/Kuwait (Arabia Standard Time)',
			'Asia/Qatar' => '(GMT+3:00) Asia/Qatar (Arabia Standard Time)',
			'Europe/Moscow' => '(GMT+3:00) Europe/Moscow (Moscow Standard Time)',
			'Europe/Volgograd' => '(GMT+3:00) Europe/Volgograd (Volgograd Time)',
			'Indian/Antananarivo' => '(GMT+3:00) Indian/Antananarivo (Eastern African Time)',
			'Indian/Comoro' => '(GMT+3:00) Indian/Comoro (Eastern African Time)',
			'Indian/Mayotte' => '(GMT+3:00) Indian/Mayotte (Eastern African Time)',
			'Asia/Tehran' => '(GMT+3:30) Asia/Tehran (Iran Standard Time)',
			'Asia/Baku' => '(GMT+4:00) Asia/Baku (Azerbaijan Time)',
			'Asia/Dubai' => '(GMT+4:00) Asia/Dubai (Gulf Standard Time)',
			'Asia/Muscat' => '(GMT+4:00) Asia/Muscat (Gulf Standard Time)',
			'Asia/Tbilisi' => '(GMT+4:00) Asia/Tbilisi (Georgia Time)',
			'Asia/Yerevan' => '(GMT+4:00) Asia/Yerevan (Armenia Time)',
			'Europe/Samara' => '(GMT+4:00) Europe/Samara (Samara Time)',
			'Indian/Mahe' => '(GMT+4:00) Indian/Mahe (Seychelles Time)',
			'Indian/Mauritius' => '(GMT+4:00) Indian/Mauritius (Mauritius Time)',
			'Indian/Reunion' => '(GMT+4:00) Indian/Reunion (Reunion Time)',
			'Asia/Kabul' => '(GMT+4:30) Asia/Kabul (Afghanistan Time)',
			'Asia/Aqtau' => '(GMT+5:00) Asia/Aqtau (Aqtau Time)',
			'Asia/Aqtobe' => '(GMT+5:00) Asia/Aqtobe (Aqtobe Time)',
			'Asia/Ashgabat' => '(GMT+5:00) Asia/Ashgabat (Turkmenistan Time)',
			'Asia/Ashkhabad' => '(GMT+5:00) Asia/Ashkhabad (Turkmenistan Time)',
			'Asia/Dushanbe' => '(GMT+5:00) Asia/Dushanbe (Tajikistan Time)',
			'Asia/Karachi' => '(GMT+5:00) Asia/Karachi (Pakistan Time)',
			'Asia/Oral' => '(GMT+5:00) Asia/Oral (Oral Time)',
			'Asia/Samarkand' => '(GMT+5:00) Asia/Samarkand (Uzbekistan Time)',
			'Asia/Tashkent' => '(GMT+5:00) Asia/Tashkent (Uzbekistan Time)',
			'Asia/Yekaterinburg' => '(GMT+5:00) Asia/Yekaterinburg (Yekaterinburg Time)',
			'Indian/Kerguelen' => '(GMT+5:00) Indian/Kerguelen (French Southern & Antarctic Lands Time)',
			'Indian/Maldives' => '(GMT+5:00) Indian/Maldives (Maldives Time)',
			'Asia/Calcutta' => '(GMT+5:30) Asia/Calcutta (India Standard Time)',
			'Asia/Colombo' => '(GMT+5:30) Asia/Colombo (India Standard Time)',
			'Asia/Kolkata' => '(GMT+5:30) Asia/Kolkata (India Standard Time)',
			'Asia/Katmandu' => '(GMT+5:45) Asia/Katmandu (Nepal Time)',
			'Antarctica/Mawson' => '(GMT+6:00) Antarctica/Mawson (Mawson Time)',
			'Antarctica/Vostok' => '(GMT+6:00) Antarctica/Vostok (Vostok Time)',
			'Asia/Almaty' => '(GMT+6:00) Asia/Almaty (Alma-Ata Time)',
			'Asia/Bishkek' => '(GMT+6:00) Asia/Bishkek (Kirgizstan Time)',
			'Asia/Dacca' => '(GMT+6:00) Asia/Dacca (Bangladesh Time)',
			'Asia/Dhaka' => '(GMT+6:00) Asia/Dhaka (Bangladesh Time)',
			'Asia/Novosibirsk' => '(GMT+6:00) Asia/Novosibirsk (Novosibirsk Time)',
			'Asia/Omsk' => '(GMT+6:00) Asia/Omsk (Omsk Time)',
			'Asia/Qyzylorda' => '(GMT+6:00) Asia/Qyzylorda (Qyzylorda Time)',
			'Asia/Thimbu' => '(GMT+6:00) Asia/Thimbu (Bhutan Time)',
			'Asia/Thimphu' => '(GMT+6:00) Asia/Thimphu (Bhutan Time)',
			'Indian/Chagos' => '(GMT+6:00) Indian/Chagos (Indian Ocean Territory Time)',
			'Asia/Rangoon' => '(GMT+6:30) Asia/Rangoon (Myanmar Time)',
			'Indian/Cocos' => '(GMT+6:30) Indian/Cocos (Cocos Islands Time)',
			'Antarctica/Davis' => '(GMT+7:00) Antarctica/Davis (Davis Time)',
			'Asia/Bangkok' => '(GMT+7:00) Asia/Bangkok (Indochina Time)',
			'Asia/Ho_Chi_Minh' => '(GMT+7:00) Asia/Ho_Chi_Minh (Indochina Time)',
			'Asia/Hovd' => '(GMT+7:00) Asia/Hovd (Hovd Time)',
			'Asia/Jakarta' => '(GMT+7:00) Asia/Jakarta (West Indonesia Time)',
			'Asia/Krasnoyarsk' => '(GMT+7:00) Asia/Krasnoyarsk (Krasnoyarsk Time)',
			'Asia/Phnom_Penh' => '(GMT+7:00) Asia/Phnom_Penh (Indochina Time)',
			'Asia/Pontianak' => '(GMT+7:00) Asia/Pontianak (West Indonesia Time)',
			'Asia/Saigon' => '(GMT+7:00) Asia/Saigon (Indochina Time)',
			'Asia/Vientiane' => '(GMT+7:00) Asia/Vientiane (Indochina Time)',
			'Indian/Christmas' => '(GMT+7:00) Indian/Christmas (Christmas Island Time)',
			'Antarctica/Casey' => '(GMT+8:00) Antarctica/Casey (Western Standard Time (Australia))',
			'Asia/Brunei' => '(GMT+8:00) Asia/Brunei (Brunei Time)',
			'Asia/Choibalsan' => '(GMT+8:00) Asia/Choibalsan (Choibalsan Time)',
			'Asia/Chongqing' => '(GMT+8:00) Asia/Chongqing (China Standard Time)',
			'Asia/Chungking' => '(GMT+8:00) Asia/Chungking (China Standard Time)',
			'Asia/Harbin' => '(GMT+8:00) Asia/Harbin (China Standard Time)',
			'Asia/Hong_Kong' => '(GMT+8:00) Asia/Hong_Kong (Hong Kong Time)',
			'Asia/Irkutsk' => '(GMT+8:00) Asia/Irkutsk (Irkutsk Time)',
			'Asia/Kashgar' => '(GMT+8:00) Asia/Kashgar (China Standard Time)',
			'Asia/Kuala_Lumpur' => '(GMT+8:00) Asia/Kuala_Lumpur (Malaysia Time)',
			'Asia/Kuching' => '(GMT+8:00) Asia/Kuching (Malaysia Time)',
			'Asia/Macao' => '(GMT+8:00) Asia/Macao (China Standard Time)',
			'Asia/Macau' => '(GMT+8:00) Asia/Macau (China Standard Time)',
			'Asia/Makassar' => '(GMT+8:00) Asia/Makassar (Central Indonesia Time)',
			'Asia/Manila' => '(GMT+8:00) Asia/Manila (Philippines Time)',
			'Asia/Shanghai' => '(GMT+8:00) Asia/Shanghai (China Standard Time)',
			'Asia/Singapore' => '(GMT+8:00) Asia/Singapore (Singapore Time)',
			'Asia/Taipei' => '(GMT+8:00) Asia/Taipei (China Standard Time)',
			'Asia/Ujung_Pandang' => '(GMT+8:00) Asia/Ujung_Pandang (Central Indonesia Time)',
			'Asia/Ulaanbaatar' => '(GMT+8:00) Asia/Ulaanbaatar (Ulaanbaatar Time)',
			'Asia/Ulan_Bator' => '(GMT+8:00) Asia/Ulan_Bator (Ulaanbaatar Time)',
			'Asia/Urumqi' => '(GMT+8:00) Asia/Urumqi (China Standard Time)',
			'Australia/Perth' => '(GMT+8:00) Australia/Perth (Western Standard Time (Australia))',
			'Australia/West' => '(GMT+8:00) Australia/West (Western Standard Time (Australia))',
			'Australia/Eucla' => '(GMT+8:45) Australia/Eucla (Central Western Standard Time (Australia))',
			'Asia/Dili' => '(GMT+9:00) Asia/Dili (Timor-Leste Time)',
			'Asia/Jayapura' => '(GMT+9:00) Asia/Jayapura (East Indonesia Time)',
			'Asia/Pyongyang' => '(GMT+9:00) Asia/Pyongyang (Korea Standard Time)',
			'Asia/Seoul' => '(GMT+9:00) Asia/Seoul (Korea Standard Time)',
			'Asia/Tokyo' => '(GMT+9:00) Asia/Tokyo (Japan Standard Time)',
			'Asia/Yakutsk' => '(GMT+9:00) Asia/Yakutsk (Yakutsk Time)',
			'Australia/Adelaide' => '(GMT+9:30) Australia/Adelaide (Central Standard Time (South Australia))',
			'Australia/Broken_Hill' => '(GMT+9:30) Australia/Broken_Hill (Central Standard Time (South Australia/New South Wales))',
			'Australia/Darwin' => '(GMT+9:30) Australia/Darwin (Central Standard Time (Northern Territory))',
			'Australia/North' => '(GMT+9:30) Australia/North (Central Standard Time (Northern Territory))',
			'Australia/South' => '(GMT+9:30) Australia/South (Central Standard Time (South Australia))',
			'Australia/Yancowinna' => '(GMT+9:30) Australia/Yancowinna (Central Standard Time (South Australia/New South Wales))',
			'Antarctica/DumontDUrville' => '(GMT+10:00) Antarctica/DumontDUrville (Dumont-d\'Urville Time)',
			'Asia/Sakhalin' => '(GMT+10:00) Asia/Sakhalin (Sakhalin Time)',
			'Asia/Vladivostok' => '(GMT+10:00) Asia/Vladivostok (Vladivostok Time)',
			'Australia/ACT' => '(GMT+10:00) Australia/ACT (Eastern Standard Time (New South Wales))',
			'Australia/Brisbane' => '(GMT+10:00) Australia/Brisbane (Eastern Standard Time (Queensland))',
			'Australia/Canberra' => '(GMT+10:00) Australia/Canberra (Eastern Standard Time (New South Wales))',
			'Australia/Currie' => '(GMT+10:00) Australia/Currie (Eastern Standard Time (New South Wales))',
			'Australia/Hobart' => '(GMT+10:00) Australia/Hobart (Eastern Standard Time (Tasmania))',
			'Australia/Lindeman' => '(GMT+10:00) Australia/Lindeman (Eastern Standard Time (Queensland))',
			'Australia/Melbourne' => '(GMT+10:00) Australia/Melbourne (Eastern Standard Time (Victoria))',
			'Australia/NSW' => '(GMT+10:00) Australia/NSW (Eastern Standard Time (New South Wales))',
			'Australia/Queensland' => '(GMT+10:00) Australia/Queensland (Eastern Standard Time (Queensland))',
			'Australia/Sydney' => '(GMT+10:00) Australia/Sydney (Eastern Standard Time (New South Wales))',
			'Australia/Tasmania' => '(GMT+10:00) Australia/Tasmania (Eastern Standard Time (Tasmania))',
			'Australia/Victoria' => '(GMT+10:00) Australia/Victoria (Eastern Standard Time (Victoria))',
			'Australia/LHI' => '(GMT+10:30) Australia/LHI (Lord Howe Standard Time)',
			'Australia/Lord_Howe' => '(GMT+10:30) Australia/Lord_Howe (Lord Howe Standard Time)',
			'Asia/Magadan' => '(GMT+11:00) Asia/Magadan (Magadan Time)',
			'Antarctica/McMurdo' => '(GMT+12:00) Antarctica/McMurdo (New Zealand Standard Time)',
			'Antarctica/South_Pole' => '(GMT+12:00) Antarctica/South_Pole (New Zealand Standard Time)',
			'Asia/Anadyr' => '(GMT+12:00) Asia/Anadyr (Anadyr Time)',
			'Asia/Kamchatka' => '(GMT+12:00) Asia/Kamchatka (Petropavlovsk-Kamchatski Time)'
		);

		$html = '<option value="">Select Timezone</option>';
		foreach($timezones as $keys => $values)
		{
			$html .= '<option value="'.$keys.'">'.$values.'</option>';
		}
		
		return $html;
	}

function fill_author($connect)
{
	$query = "
	SELECT author_name FROM lms_author 
	WHERE author_status = 'Enable' 
	ORDER BY author_name ASC
	";

	$result = $connect->query($query);

	$output = '<option value="">Select Author</option>';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["author_name"].'">'.$row["author_name"].'</option>';
	}

	return $output;
}

function fill_category($connect)
{
	$query = "
	SELECT category_name FROM lms_category 
	WHERE category_status = 'Enable' 
	ORDER BY category_name ASC
	";

	$result = $connect->query($query);

	$output = '<option value="">Select Category</option>';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["category_name"].'">'.$row["category_name"].'</option>';
	}

	return $output;
}

function fill_location_rack($connect)
{
	$query = "
	SELECT location_rack_name FROM lms_location_rack 
	WHERE location_rack_status = 'Enable' 
	ORDER BY location_rack_name ASC
	";

	$result = $connect->query($query);

	$output = '<option value="">Select Location Rack</option>';

	foreach($result as $row)
	{
		$output .= '<option value="'.$row["location_rack_name"].'">'.$row["location_rack_name"].'</option>';
	}

	return $output;
}

function Count_total_issue_book_number($connect)
{
	$total = 0;

	$query = "SELECT COUNT(issue_book_id) AS Total FROM lms_issue_book";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}

	return $total;
}

function Count_total_returned_book_number($connect)
{
	$total = 0;

	$query = "
	SELECT COUNT(issue_book_id) AS Total FROM lms_issue_book 
	WHERE book_issue_status = 'Return'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}

	return $total;
}

function Count_total_not_returned_book_number($connect)
{
	$total = 0;

	$query = "
	SELECT COUNT(issue_book_id) AS Total FROM lms_issue_book 
	WHERE book_issue_status = 'Not Return'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}

	return $total;
}

function Count_total_fines_received($connect)
{
	$total = 0;

	$query = "
	SELECT SUM(book_fines) AS Total FROM lms_issue_book 
	WHERE book_issue_status = 'Return'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}

	return $total;
}

function Count_total_book_number($connect)
{
	$total = 0;

	$query = "
	SELECT COUNT(book_id) AS Total FROM lms_book 
	WHERE book_status = 'Enable'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}

	return $total;
}

function Count_total_author_number($connect)
{
	$total = 0;

	$query = "
	SELECT COUNT(author_id) AS Total FROM lms_author 
	WHERE author_status = 'Enable'
	";

	$result  = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}

	return $total;
}

function Count_total_category_number($connect)
{
	$total = 0;

	$query = "
	SELECT COUNT(category_id) AS Total FROM lms_category 
	WHERE category_status = 'Enable'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}
	return $total;
}

function Count_total_location_rack_number($connect)
{
	$total = 0;

	$query = "
	SELECT COUNT(location_rack_id) AS Total FROM lms_location_rack 
	WHERE location_rack_status = 'Enable'
	";

	$result = $connect->query($query);

	foreach($result as $row)
	{
		$total = $row["Total"];
	}

	return $total;
}

?>