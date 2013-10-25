<?php
function authorizepayment($METHOD_TO_USE,$REQUEST,$address1='',$city='',$state='',$country='',$zip='',$email='',$paypalprice)
{
	if (!checkCreditCard($REQUEST['x_card_num'], $REQUEST['card_type'], $ccerror, $ccerrortext))
	{
		$_SESSION['donate_msg'] = 'Please enter a valid '.$REQUEST['x_card_num'].' number.';
		return false;
	}
	else
	{
		if ($METHOD_TO_USE == "AIM")
		{
			$transaction = new AuthorizeNetAIM;
			$transaction->setSandbox(AUTHORIZENET_SANDBOX);
			$transaction->setFields(
				array(
				'amount' => $REQUEST['amount'],
				'card_num' => $REQUEST['x_card_num'],
				'exp_date' => $REQUEST['exp_month'].'/'.$REQUEST['exp_year'],
				'first_name' => $REQUEST['first_name'],
				'last_name' => $REQUEST['last_name'],
				'address' => $REQUEST['address'],
				'city' => $REQUEST['city'],
				'state' => $REQUEST['state'],
				'country' => $REQUEST['country'],
				'zip' => $REQUEST['zip'],
				'email' => $REQUEST['email'],
				)
			);
			$response = $transaction->authorizeAndCapture();
			if ($response->approved)
			{
				$_SESSION['donate_msg'] = $response->response_reason_text;
				return true;
			}
			else
			{
				//echo "1";
				$_SESSION['donate_msg'] = $response->response_reason_text;				
			}
		}
		elseif (count($REQUEST))
		{
			$response = new AuthorizeNetSIM;
			if ($response->isAuthorizeNet())
			{
				if ($response->approved)
				{
					// Transaction approved! Do your logic here.
					// Redirect the user back to your site.
					//$return_url = $site_root . 'thank_you_page.php?transaction_id=' .$response->transaction_id;
					//echo "2";
					
					return true;
				}
				else
				{
					// There was a problem. Do your logic here.
					// Redirect the user back to your site.
					$_SESSION['donate_msg'] = $response->response_reason_text;
				   // echo "3";
					header("Location:".site_url().$_SERVER['REQUEST_URI']);
					exit;
		
				}
				echo AuthorizeNetDPM::getRelayResponseSnippet($return_url);
			}
			else
			{
				//echo "4";
				$_SESSION['donate_msg'] =  "MD5 Hash failed. Check to make sure your MD5 Setting matches the one in";				
			}
		}
		
	}
}

function checkCreditCard($cardnumber, $cardname, &$errornumber, &$errortext) {

	// Define the cards we support. You may add additional card types.
	//  Name:      As in the selection box of the form - must be same as user's
	//  Length:    List of possible valid lengths of the card number for the card
	//  prefixes:  List of possible prefixes for the card
	//  checkdigit Boolean to say whether there is a check digit
	// Don't forget - all but the last array definition needs a comma separator!

	$cards = array(array('name' => 'AMEX',
			'length' => '15',
			'prefixes' => '34,37',
			'checkdigit' => true
		),
		array('name' => 'Diners Club Carte Blanche',
			'length' => '14',
			'prefixes' => '300,301,302,303,304,305',
			'checkdigit' => true
		),
		array('name' => 'DINERS',
			'length' => '14,16',
			'prefixes' => '305,36,38,54,55',
			'checkdigit' => true
		),
		array('name' => 'Discover',
			'length' => '16',
			'prefixes' => '6011,622,64,65',
			'checkdigit' => true
		),
		array('name' => 'Diners Club Enroute',
			'length' => '15',
			'prefixes' => '2014,2149',
			'checkdigit' => true
		),
		array('name' => 'JCB',
			'length' => '16',
			'prefixes' => '35',
			'checkdigit' => true
		),
		array('name' => 'Maestro',
			'length' => '12,13,14,15,16,18,19',
			'prefixes' => '5018,5020,5038,6304,6759,6761',
			'checkdigit' => true
		),
		array('name' => 'MASTERCARD',
			'length' => '16',
			'prefixes' => '51,52,53,54,55',
			'checkdigit' => true
		),
		array('name' => 'Solo',
			'length' => '16,18,19',
			'prefixes' => '6334,6767',
			'checkdigit' => true
		),
		array('name' => 'Switch',
			'length' => '16,18,19',
			'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759',
			'checkdigit' => true
		),
		array('name' => 'VISA',
			'length' => '16',
			'prefixes' => '4',
			'checkdigit' => true
		),
		array('name' => 'VISA Electron',
			'length' => '16',
			'prefixes' => '417500,4917,4913,4508,4844',
			'checkdigit' => true
		),
		array('name' => 'LaserCard',
			'length' => '16,17,18,19',
			'prefixes' => '6304,6706,6771,6709',
			'checkdigit' => true
		)
	);

	$ccErrorNo = 0;

	$ccErrors [0] = 'Please enter a valid ' . $cardname . ' number.';
	$ccErrors [1] = "No card number provided";
	$ccErrors [2] = "Credit card number has invalid format";
	$ccErrors [3] = "Credit card number is invalid";
	$ccErrors [4] = "Credit card number is wrong length";

	// Establish card type
	$cardType = -1;
	for ($i = 0; $i < sizeof($cards); $i++) {

		// See if it is this card (ignoring the case of the string)
		if (strtolower($cardname) == strtolower($cards[$i]['name'])) {
			$cardType = $i;
			break;
		}
	}

	// If card type not found, report an error
	if ($cardType == -1) {
		$errornumber = 0;
		$errortext = $ccErrors [$errornumber];
		return false;
	}

	// Ensure that the user has provided a credit card number
	if (strlen($cardnumber) == 0) {
		$errornumber = 1;
		$errortext = $ccErrors [$errornumber];
		return false;
	}

	// Remove any spaces from the credit card number
	$cardNo = str_replace(' ', '', $cardnumber);

	// Check that the number is numeric and of the right sort of length.
	if (!preg_match("/^[0-9]{13,19}$/", $cardNo)) {
		$errornumber = 2;
		$errortext = $ccErrors [$errornumber];
		return false;
	}

	// Now check the modulus 10 check digit - if required
	if ($cards[$cardType]['checkdigit']) {
		$checksum = 0;                                  // running checksum total
		$mychar = "";                                   // next char to process
		$j = 1;                                         // takes value of 1 or 2
		// Process each digit one by one starting at the right
		for ($i = strlen($cardNo) - 1; $i >= 0; $i--) {

			// Extract the next digit and multiply by 1 or 2 on alternative digits.
			$calc = $cardNo{$i} * $j;

			// If the result is in two digits add 1 to the checksum total
			if ($calc > 9) {
				$checksum = $checksum + 1;
				$calc = $calc - 10;
			}

			// Add the units element to the checksum total
			$checksum = $checksum + $calc;

			// Switch the value of j
			if ($j == 1) {
				$j = 2;
			} else {
				$j = 1;
			};
		}

		// All done - if checksum is divisible by 10, it is a valid modulus 10.
		// If not, report an error.
		if ($checksum % 10 != 0) {
			$errornumber = 3;
			$errortext = $ccErrors [$errornumber];
			return false;
		}
	}

	// The following are the card-specific checks we undertake.
	// Load an array with the valid prefixes for this card
	$prefix = explode(',', $cards[$cardType]['prefixes']);

	// Now see if any of them match what we have in the card number
	$PrefixValid = false;
	for ($i = 0; $i < sizeof($prefix); $i++) {
		$exp = '/^' . $prefix[$i] . '/';
		if (preg_match($exp, $cardNo)) {
			$PrefixValid = true;
			break;
		}
	}

	// If it isn't a valid prefix there's no point at looking at the length
	if (!$PrefixValid) {
		$errornumber = 3;
		$errortext = $ccErrors [$errornumber];
		return false;
	}

	// See if the length is valid for this card
	$LengthValid = false;
	$lengths = explode(',', $cards[$cardType]['length']);
	for ($j = 0; $j < sizeof($lengths); $j++) {
		if (strlen($cardNo) == $lengths[$j]) {
			$LengthValid = true;
			break;
		}
	}

	// See if all is OK by seeing if the length was valid.
	if (!$LengthValid) {
		$errornumber = 4;
		$errortext = $ccErrors [$errornumber];
		return false;
	};

	// The credit card is in the required format.
	return true;
}


?>
