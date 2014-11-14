<?php ob_start();
/**
 * Display Donate Form
 *
 * @return string Donate Form
 *
 * @since 1.0
 *
*/
	global $wpdb;
    if(isset($_REQUEST['action']))
    {
    if($_REQUEST['action']=='submitdonate')
    {		
		$first_name = $_REQUEST['first_name'];
		$last_name = $_REQUEST['last_name'];
		$organization = $_REQUEST['organization'];
		$address = $_REQUEST['address'];
		$city = $_REQUEST['city'];
		$country = $_REQUEST['country'];
		$state = $_REQUEST['state'];
		$zip = $_REQUEST['zip'];
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];		
		$donation_type = $_REQUEST['donation_type'];
		$r_frequency = $_REQUEST['r_frequency'];
		$r_times = $_REQUEST['r_times'];
		$amount = $_REQUEST['amount'];
		$card_type = $_REQUEST['card_type'];
		$x_card_num = $_REQUEST['x_card_num'];
		$exp_month = $_REQUEST['exp_month'];
		$exp_year = $_REQUEST['exp_year'];
		$x_card_code = $_REQUEST['x_card_code'];
		$comment = $_REQUEST['comment'];
		$payment_method = $_REQUEST['payment_method'];
		
		// Adjust this to point to the Authorize.Net PHP SDK
		include dirname(__FILE__) .'/../anet_php_sdk/AuthorizeNet.php';

		$METHOD_TO_USE = "AIM";
		$mysetting = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate_setting" );
		define("AUTHORIZENET_API_LOGIN_ID",$mysetting[0]->api_login);    // Add your API LOGIN ID
		define("AUTHORIZENET_TRANSACTION_KEY",$mysetting[0]->key); // Add your API transaction key
		if($mysetting[0]->mod==0){
		define("AUTHORIZENET_SANDBOX",true);       // Set to false to test against production
		define("TEST_REQUEST", true);           // You may want to set to true if testing against production
		}
		else
		{
			define("AUTHORIZENET_SANDBOX",false);       // Set to false to test against production
			define("TEST_REQUEST", false);  
		}
		// You only need to adjust the two variables below if testing DPM
		define("AUTHORIZENET_MD5_SETTING","");                // Add your MD5 Setting.

		$REQUEST = $_REQUEST;
		if($paypalp = authorizepayment($METHOD_TO_USE,$REQUEST,$address1,$city,$state,$country,$zipcode,$emailaddress,$plan))
		{
			$sql = "INSERT INTO `".$wpdb->prefix."donate` (`first_name`, `last_name`, `organization`, `address`, `city`, `country`, `state`, `zip`, `phone`, `email`, `donation_type`, `amount`, `comment`, `status`,`date`) 
			VALUES ('".$first_name."', '".$last_name."','".$organization."','".$address."','".$city."','".$country."','".$state."','".$zip."','".$phone."','".$email."','".$donation_type."','".$amount."','".$comment."', '1',now());";
			$wpdb->query($sql);
			
			$post = get_post($post->ID);
			$slug = $post->post_name;
			
			header("Location:".site_url().$_SERVER['REQUEST_URI']);
			exit;
		}
		else
		{
			header("Location:".site_url().$_SERVER['REQUEST_URI']);
			exit;
		}
		
	}
	}

function wp_donate_form() {
    ob_start();
        global $wpdb;
    ?>
    	<form method="post" name="donate_form" id="donate_form" action="<?php echo site_url().$_SERVER['REQUEST_URI'];?>" autocomplete="off">
			<input type="hidden" name="action" value="submitdonate" />
			<table width="100%" cellspacing="3" cellpadding="3">
				<tr><td style="color:red;" colspan="4"><?php echo $_SESSION['donate_msg']; $_SESSION['donate_msg']='';?></td></tr>
				<tr>
					<td colspan="2" class="msg">
						<p>You may use this form to make an online donation. Your gift will go to the area of greatest need. If you wish your donation to be designated for a particular area or program, please note your wishes in the comment box.</p>							 						
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading"><b>Donor information</b></td>
				</tr>					
				<tr>			
					<td class="title_cell">First name<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="first_name" id="first_name" value="" size="25" />
					</td>
				</tr>
				<tr>			
					<td class="title_cell">Last name<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="last_name" value="" size="25" />
					</td>
				</tr>
				<tr>			
					<td class="title_cell">Organization</td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="organization" value="" size="30" />
					</td>
				</tr>
				<tr>			
					<td class="title_cell">Address<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="address" value="" size="50" />
					</td>
				</tr>	
				<tr>			
					<td class="title_cell">City<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="city" value="" size="15" />
					</td>
				</tr>		
				<tr>			
					<td class="title_cell">Country<span class="required">*</span></td>
					<td class="field_cell">
						<select id="country" name="country" onchange="updateStateList();" >
						<option value="">Select country</option>
						<option value="Afghanistan">Afghanistan</option>
						<option value="Albania">Albania</option>
						<option value="Algeria">Algeria</option>
						<option value="American Samoa">American Samoa</option>
						<option value="Andorra">Andorra</option>
						<option value="Angola">Angola</option>
						<option value="Anguilla">Anguilla</option>
						<option value="Antarctica">Antarctica</option>
						<option value="Antigua and Barbuda">Antigua and Barbuda</option>
						<option value="Argentina">Argentina</option>
						<option value="Armenia">Armenia</option>
						<option value="Aruba">Aruba</option>
						<option value="Australia">Australia</option>
						<option value="Austria">Austria</option>
						<option value="Azerbaijan">Azerbaijan</option>
						<option value="Bahamas">Bahamas</option>
						<option value="Bahrain">Bahrain</option>
						<option value="Bangladesh">Bangladesh</option>
						<option value="Barbados">Barbados</option>
						<option value="Belarus">Belarus</option>
						<option value="Belgium">Belgium</option>
						<option value="Belize">Belize</option>
						<option value="Benin">Benin</option>
						<option value="Bermuda">Bermuda</option>
						<option value="Bhutan">Bhutan</option>
						<option value="Bolivia">Bolivia</option>
						<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
						<option value="Botswana">Botswana</option>
						<option value="Bouvet Island">Bouvet Island</option>
						<option value="Brazil">Brazil</option>
						<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
						<option value="Brunei Darussalam">Brunei Darussalam</option>
						<option value="Bulgaria">Bulgaria</option>
						<option value="Burkina Faso">Burkina Faso</option>
						<option value="Burundi">Burundi</option>
						<option value="Cambodia">Cambodia</option>
						<option value="Cameroon">Cameroon</option>
						<option value="Canada">Canada</option>
						<option value="Canary Islands">Canary Islands</option>
						<option value="Cape Verde">Cape Verde</option>
						<option value="Cayman Islands">Cayman Islands</option>
						<option value="Central African Republic">Central African Republic</option>
						<option value="Chad">Chad</option>
						<option value="Chile">Chile</option>
						<option value="China">China</option>
						<option value="Christmas Island">Christmas Island</option>
						<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
						<option value="Colombia">Colombia</option>
						<option value="Comoros">Comoros</option>
						<option value="Congo">Congo</option>
						<option value="Cook Islands">Cook Islands</option>
						<option value="Costa Rica">Costa Rica</option>
						<option value="Cote D'Ivoire">Cote D'Ivoire</option>
						<option value="Croatia">Croatia</option>
						<option value="Cuba">Cuba</option>
						<option value="Cyprus">Cyprus</option>
						<option value="Czech Republic">Czech Republic</option>
						<option value="Denmark">Denmark</option>
						<option value="Djibouti">Djibouti</option>
						<option value="Dominica">Dominica</option>
						<option value="Dominican Republic">Dominican Republic</option>
						<option value="East Timor">East Timor</option>
						<option value="East Timor">East Timor</option>
						<option value="Ecuador">Ecuador</option>
						<option value="Egypt">Egypt</option>
						<option value="El Salvador">El Salvador</option>
						<option value="Equatorial Guinea">Equatorial Guinea</option>
						<option value="Eritrea">Eritrea</option>
						<option value="Estonia">Estonia</option>
						<option value="Ethiopia">Ethiopia</option>
						<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
						<option value="Faroe Islands">Faroe Islands</option>
						<option value="Fiji">Fiji</option>
						<option value="Finland">Finland</option>
						<option value="France">France</option>
						<option value="France, Metropolitan">France, Metropolitan</option>
						<option value="French Guiana">French Guiana</option>
						<option value="French Polynesia">French Polynesia</option>
						<option value="French Southern Territories">French Southern Territories</option>
						<option value="Gabon">Gabon</option>
						<option value="Gambia">Gambia</option>
						<option value="Georgia">Georgia</option>
						<option value="Germany">Germany</option>
						<option value="Ghana">Ghana</option>
						<option value="Gibraltar">Gibraltar</option>
						<option value="Greece">Greece</option>
						<option value="Greenland">Greenland</option>
						<option value="Grenada">Grenada</option>
						<option value="Guadeloupe">Guadeloupe</option>
						<option value="Guam">Guam</option>
						<option value="Guatemala">Guatemala</option>
						<option value="Guinea">Guinea</option>
						<option value="Guinea-bissau">Guinea-bissau</option>
						<option value="Guyana">Guyana</option>
						<option value="Haiti">Haiti</option>
						<option value="Heard and Mc Donald Islands">Heard and Mc Donald Islands</option>
						<option value="Honduras">Honduras</option>
						<option value="Hong Kong">Hong Kong</option>
						<option value="Hungary">Hungary</option>
						<option value="Iceland">Iceland</option>
						<option value="India">India</option>
						<option value="Indonesia">Indonesia</option>
						<option value="Iran (Islamic Republic of)">Iran (Islamic Republic of)</option>
						<option value="Iraq">Iraq</option>
						<option value="Ireland">Ireland</option>
						<option value="Israel">Israel</option>
						<option value="Italy">Italy</option>
						<option value="Jamaica">Jamaica</option>
						<option value="Japan">Japan</option>
						<option value="Jersey">Jersey</option>
						<option value="Jordan">Jordan</option>
						<option value="Kazakhstan">Kazakhstan</option>
						<option value="Kenya">Kenya</option>
						<option value="Kiribati">Kiribati</option>
						<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
						<option value="Korea, Republic of">Korea, Republic of</option>
						<option value="Kuwait">Kuwait</option>
						<option value="Kyrgyzstan">Kyrgyzstan</option>
						<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
						<option value="Latvia">Latvia</option>
						<option value="Lebanon">Lebanon</option>
						<option value="Lesotho">Lesotho</option>
						<option value="Liberia">Liberia</option>
						<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
						<option value="Liechtenstein">Liechtenstein</option>
						<option value="Lithuania">Lithuania</option>
						<option value="Luxembourg">Luxembourg</option>
						<option value="Macau">Macau</option>
						<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
						<option value="Madagascar">Madagascar</option>
						<option value="Malawi">Malawi</option>
						<option value="Malaysia">Malaysia</option>
						<option value="Maldives">Maldives</option>
						<option value="Mali">Mali</option>
						<option value="Malta">Malta</option>
						<option value="Marshall Islands">Marshall Islands</option>
						<option value="Martinique">Martinique</option>
						<option value="Mauritania">Mauritania</option>
						<option value="Mauritius">Mauritius</option>
						<option value="Mayotte">Mayotte</option>
						<option value="Mexico">Mexico</option>
						<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
						<option value="Moldova, Republic of">Moldova, Republic of</option>
						<option value="Monaco">Monaco</option>
						<option value="Mongolia">Mongolia</option>
						<option value="Montenegro">Montenegro</option>
						<option value="Montserrat">Montserrat</option>
						<option value="Morocco">Morocco</option>
						<option value="Mozambique">Mozambique</option>
						<option value="Myanmar">Myanmar</option>
						<option value="Namibia">Namibia</option>
						<option value="Nauru">Nauru</option>
						<option value="Nepal">Nepal</option>
						<option value="Netherlands">Netherlands</option>
						<option value="Netherlands Antilles">Netherlands Antilles</option>
						<option value="New Caledonia">New Caledonia</option>
						<option value="New Zealand">New Zealand</option>
						<option value="Nicaragua">Nicaragua</option>
						<option value="Niger">Niger</option>
						<option value="Nigeria">Nigeria</option>
						<option value="Niue">Niue</option>
						<option value="Norfolk Island">Norfolk Island</option>
						<option value="Northern Mariana Islands">Northern Mariana Islands</option>
						<option value="Norway">Norway</option>
						<option value="Oman">Oman</option>
						<option value="Pakistan">Pakistan</option>
						<option value="Palau">Palau</option>
						<option value="Panama">Panama</option>
						<option value="Papua New Guinea">Papua New Guinea</option>
						<option value="Paraguay">Paraguay</option>
						<option value="Peru">Peru</option>
						<option value="Philippines">Philippines</option>
						<option value="Pitcairn">Pitcairn</option>
						<option value="Poland">Poland</option>
						<option value="Portugal">Portugal</option>
						<option value="Puerto Rico">Puerto Rico</option>
						<option value="Qatar">Qatar</option>
						<option value="Reunion">Reunion</option>
						<option value="Romania">Romania</option>
						<option value="Russian Federation">Russian Federation</option>
						<option value="Rwanda">Rwanda</option>
						<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
						<option value="Saint Lucia">Saint Lucia</option>
						<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
						<option value="Samoa">Samoa</option>
						<option value="San Marino">San Marino</option>
						<option value="Sao Tome and Principe">Sao Tome and Principe</option>
						<option value="Saudi Arabia">Saudi Arabia</option>
						<option value="Senegal">Senegal</option>
						<option value="Serbia">Serbia</option>
						<option value="Seychelles">Seychelles</option>
						<option value="Sierra Leone">Sierra Leone</option>
						<option value="Singapore">Singapore</option>
						<option value="Slovakia (Slovak Republic)">Slovakia (Slovak Republic)</option>
						<option value="Slovenia">Slovenia</option>
						<option value="Solomon Islands">Solomon Islands</option>
						<option value="Somalia">Somalia</option>
						<option value="South Africa">South Africa</option>
						<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
						<option value="Spain">Spain</option>
						<option value="Sri Lanka">Sri Lanka</option>
						<option value="St. Barthelemy">St. Barthelemy</option>
						<option value="St. Eustatius">St. Eustatius</option>
						<option value="St. Helena">St. Helena</option>
						<option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
						<option value="Sudan">Sudan</option>
						<option value="Suriname">Suriname</option>
						<option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
						<option value="Swaziland">Swaziland</option>
						<option value="Sweden">Sweden</option>
						<option value="Switzerland">Switzerland</option>
						<option value="Syrian Arab Republic">Syrian Arab Republic</option>
						<option value="Taiwan">Taiwan</option>
						<option value="Tajikistan">Tajikistan</option>
						<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
						<option value="Thailand">Thailand</option>
						<option value="The Democratic Republic of Congo">The Democratic Republic of Congo</option>
						<option value="Togo">Togo</option>
						<option value="Tokelau">Tokelau</option>
						<option value="Tonga">Tonga</option>
						<option value="Trinidad and Tobago">Trinidad and Tobago</option>
						<option value="Tunisia">Tunisia</option>
						<option value="Turkey">Turkey</option>
						<option value="Turkmenistan">Turkmenistan</option>
						<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
						<option value="Tuvalu">Tuvalu</option>
						<option value="Uganda">Uganda</option>
						<option value="Ukraine">Ukraine</option>
						<option value="United Arab Emirates">United Arab Emirates</option>
						<option value="United Kingdom">United Kingdom</option>
						<option value="United States" selected="selected">United States</option>
						<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
						<option value="Uruguay">Uruguay</option>
						<option value="Uzbekistan">Uzbekistan</option>
						<option value="Vanuatu">Vanuatu</option>
						<option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
						<option value="Venezuela">Venezuela</option>
						<option value="Viet Nam">Viet Nam</option>
						<option value="Virgin Islands (British)">Virgin Islands (British)</option>
						<option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option>
						<option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
						<option value="Western Sahara">Western Sahara</option>
						<option value="Yemen">Yemen</option>
						<option value="Zambia">Zambia</option>
						<option value="Zimbabwe">Zimbabwe</option>
					</select>
					</td>
				</tr>	
				<tr>			
					<td class="title_cell">State<span class="required">*</span></td>
					<td class="field_cell">
						<select id="state" name="state" class="">
						<option value="" selected="selected">Select State</option>
						<option value="AK">Alaska</option>
						<option value="AL">Alabama</option>
						<option value="AR">Arkansas</option>
						<option value="AZ">Arizona</option>
						<option value="CA">California</option>
						<option value="CO">Colorado</option>
						<option value="CT">Connecticut</option>
						<option value="DC">District Of Columbia</option>
						<option value="DE">Delaware</option>
						<option value="FL">Florida</option>
						<option value="GA">Georgia</option>
						<option value="HI">Hawaii</option>
						<option value="IA">Iowa</option>
						<option value="ID">Idaho</option>
						<option value="IL">Illinois</option>
						<option value="IN">Indiana</option>
						<option value="KS">Kansas</option>
						<option value="KY">Kentucky</option>
						<option value="LA">Louisiana</option>
						<option value="MA">Massachusetts</option>
						<option value="MD">Maryland</option>
						<option value="ME">Maine</option>
						<option value="MI">Michigan</option>
						<option value="MN">Minnesota</option>
						<option value="MO">Missouri</option>
						<option value="MS">Mississippi</option>
						<option value="MT">Montana</option>
						<option value="NC">North Carolina</option>
						<option value="ND">North Dakota</option>
						<option value="NE">Nebraska</option>
						<option value="NH">New Hampshire</option>
						<option value="NJ">New Jersey</option>
						<option value="NM">New Mexico</option>
						<option value="NV">Nevada</option>
						<option value="NY">New York</option>
						<option value="OH">Ohio</option>
						<option value="OK">Oklahoma</option>
						<option value="OR">Oregon</option>
						<option value="PA">Pennsylvania</option>
						<option value="RI">Rhode Island</option>
						<option value="SC">South Carolina</option>
						<option value="SD">South Dakota</option>
						<option value="TN">Tennessee</option>
						<option value="TX">Texas</option>
						<option value="UT">Utah</option>
						<option value="VA">Virginia</option>
						<option value="VT">Vermont</option>
						<option value="WA">Washington</option>
						<option value="WI">Wisconsin</option>
						<option value="WV">West Virginia</option>
						<option value="WY">Wyoming</option>
					</select>
					</td>
				</tr>
				<tr>			
					<td class="title_cell">Zip<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="zip" value="" size="15" />
					</td>
				</tr>
				<tr>			
					<td class="title_cell">Phone<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="phone" value="" size="15" />
					</td>
				</tr>																					
				<tr>			
					<td class="title_cell">Email<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" class="inputbox" name="email" value="" size="40" />
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading"><strong>Donation Information</strong></td>			
				</tr>
				
				
				<tr>
					<td class="title_cell" valign="top">Amount</td>				
					<td id="amount_container">
						$ <input type="text" class="inputbox" name="amount" value="" onchange="deSelectRadio();" size="10" />
					</td>
				</tr>		
				<tr id="tr_card_type">
					<td class="title_cell">Card type<span class="required">*</span></td>
					<td class="field_cell">
						<select id="card_type" name="card_type" class="inputbox" >
							<option value="Visa">Visa</option>
							<option value="MasterCard">MasterCard</option>
							<option value="Discover">Discover</option>
							<option value="Amex">American Express</option>
						</select>
					</td>
				</tr>				
				<tr id="tr_card_number" >
					<td class="title_cell">Credit Card Number<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" name="x_card_num" class="inputbox" onkeyup="checkNumber(this)" value="" size="20" />
					</td>
				</tr>
				<tr id="tr_exp_date" >
					<td class="title_cell">Expiration Date<span class="required">*</span>
					</td>
					<td class="field_cell">					
						<select name="exp_month" class="inputbox exp_month" >
							<option value="1" <?php if(date('m')=='01'){?>  selected="selected"<?php }?>>01</option>
							<option value="2" <?php if(date('m')=='02'){?>  selected="selected"<?php }?>>02</option>
							<option value="3" <?php if(date('m')=='03'){?>  selected="selected"<?php }?>>03</option>
							<option value="4" <?php if(date('m')=='04'){?>  selected="selected"<?php }?>>04</option>
							<option value="5" <?php if(date('m')=='05'){?>  selected="selected"<?php }?>>05</option>
							<option value="6" <?php if(date('m')=='06'){?>  selected="selected"<?php }?>>06</option>
							<option value="7" <?php if(date('m')=='07'){?>  selected="selected"<?php }?>>07</option>
							<option value="8" <?php if(date('m')=='08'){?>  selected="selected"<?php }?>>08</option>
							<option value="9" <?php if(date('m')=='09'){?>  selected="selected"<?php }?>>09</option>
							<option value="10" <?php if(date('m')=='10'){?>  selected="selected"<?php }?>>10</option>
							<option value="11" <?php if(date('m')=='11'){?>  selected="selected"<?php }?>>11</option>
							<option value="12" <?php if(date('m')=='12'){?>  selected="selected"<?php }?>>12</option>
						</select> / 
						<select id="exp_year" name="exp_year" class="inputbox exp_month" >
							<?php $year = date(Y,time()); $num = 1;
								while ( $num <= 7 ) {
									echo '<option value="' . $year .'">' . $year . '</option>';$year++; $num++;
								}
							?>
						</select>
					</td>
				</tr>
				<tr id="tr_cvv_code" >
					<td class="title_cell">Card (CVV) Code<span class="required">*</span></td>
					<td class="field_cell">
						<input type="text" name="x_card_code" class="inputbox" onKeyUp="checkNumber(this)" value="" size="20" />
					</td>
				</tr>
								
				
				<tr>			
					<td class="title_cell">Comment</td>
					<td class="field_cell">
						<textarea rows="7" cols="50" name="comment" class="inputbox"></textarea>
					</td>
				</tr>														
				<tr>
					<td colspan="2" align="left">
						<input type="button" class="button donate_btn_submit" name="btnSubmit" value="Donate Now" onclick="checkData();">
					</td>
				</tr>										
			</table>
			<input type="hidden" name="payment_method" value="os_authnet" />
			</form>
			<script type="text/javascript">
				var recurrings = new Array();
				recurrings[1] = 1 ;
				recurrings[2] = 1 ;
				recurrings[3] = 1 ;
				recurrings[4] = 1 ;
				recurrings[5] = 1 ;
				var countryIds = new Array(); 
				countryIds[0] = 1;
				countryIds[1] = 2;
				countryIds[2] = 3;
				countryIds[3] = 4;
				countryIds[4] = 5;
				countryIds[5] = 6;
				countryIds[6] = 7;
				countryIds[7] = 8;
				countryIds[8] = 9;
				countryIds[9] = 10;
				countryIds[10] = 11;
				countryIds[11] = 12;
				countryIds[12] = 13;
				countryIds[13] = 14;
				countryIds[14] = 15;
				countryIds[15] = 16;
				countryIds[16] = 17;
				countryIds[17] = 18;
				countryIds[18] = 19;
				countryIds[19] = 20;
				countryIds[20] = 21;
				countryIds[21] = 22;
				countryIds[22] = 23;
				countryIds[23] = 24;
				countryIds[24] = 25;
				countryIds[25] = 26;
				countryIds[26] = 27;
				countryIds[27] = 28;
				countryIds[28] = 29;
				countryIds[29] = 30;
				countryIds[30] = 31;
				countryIds[31] = 32;
				countryIds[32] = 33;
				countryIds[33] = 34;
				countryIds[34] = 35;
				countryIds[35] = 36;
				countryIds[36] = 37;
				countryIds[37] = 38;
				countryIds[38] = 39;
				countryIds[39] = 40;
				countryIds[40] = 41;
				countryIds[41] = 42;
				countryIds[42] = 43;
				countryIds[43] = 44;
				countryIds[44] = 45;
				countryIds[45] = 46;
				countryIds[46] = 47;
				countryIds[47] = 48;
				countryIds[48] = 49;
				countryIds[49] = 50;
				countryIds[50] = 51;
				countryIds[51] = 52;
				countryIds[52] = 53;
				countryIds[53] = 54;
				countryIds[54] = 55;
				countryIds[55] = 56;
				countryIds[56] = 57;
				countryIds[57] = 58;
				countryIds[58] = 59;
				countryIds[59] = 60;
				countryIds[60] = 61;
				countryIds[61] = 62;
				countryIds[62] = 63;
				countryIds[63] = 64;
				countryIds[64] = 65;
				countryIds[65] = 66;
				countryIds[66] = 67;
				countryIds[67] = 68;
				countryIds[68] = 69;
				countryIds[69] = 70;
				countryIds[70] = 71;
				countryIds[71] = 72;
				countryIds[72] = 73;
				countryIds[73] = 74;
				countryIds[74] = 75;
				countryIds[75] = 76;
				countryIds[76] = 77;
				countryIds[77] = 78;
				countryIds[78] = 79;
				countryIds[79] = 80;
				countryIds[80] = 81;
				countryIds[81] = 82;
				countryIds[82] = 83;
				countryIds[83] = 84;
				countryIds[84] = 85;
				countryIds[85] = 86;
				countryIds[86] = 87;
				countryIds[87] = 88;
				countryIds[88] = 89;
				countryIds[89] = 90;
				countryIds[90] = 91;
				countryIds[91] = 92;
				countryIds[92] = 93;
				countryIds[93] = 94;
				countryIds[94] = 95;
				countryIds[95] = 96;
				countryIds[96] = 97;
				countryIds[97] = 98;
				countryIds[98] = 99;
				countryIds[99] = 100;
				countryIds[100] = 101;
				countryIds[101] = 102;
				countryIds[102] = 103;
				countryIds[103] = 104;
				countryIds[104] = 105;
				countryIds[105] = 106;
				countryIds[106] = 107;
				countryIds[107] = 108;
				countryIds[108] = 109;
				countryIds[109] = 110;
				countryIds[110] = 111;
				countryIds[111] = 112;
				countryIds[112] = 113;
				countryIds[113] = 114;
				countryIds[114] = 115;
				countryIds[115] = 116;
				countryIds[116] = 117;
				countryIds[117] = 118;
				countryIds[118] = 119;
				countryIds[119] = 120;
				countryIds[120] = 121;
				countryIds[121] = 122;
				countryIds[122] = 123;
				countryIds[123] = 124;
				countryIds[124] = 125;
				countryIds[125] = 126;
				countryIds[126] = 127;
				countryIds[127] = 128;
				countryIds[128] = 129;
				countryIds[129] = 130;
				countryIds[130] = 131;
				countryIds[131] = 132;
				countryIds[132] = 133;
				countryIds[133] = 134;
				countryIds[134] = 135;
				countryIds[135] = 136;
				countryIds[136] = 137;
				countryIds[137] = 138;
				countryIds[138] = 139;
				countryIds[139] = 140;
				countryIds[140] = 141;
				countryIds[141] = 142;
				countryIds[142] = 143;
				countryIds[143] = 144;
				countryIds[144] = 145;
				countryIds[145] = 146;
				countryIds[146] = 147;
				countryIds[147] = 148;
				countryIds[148] = 149;
				countryIds[149] = 150;
				countryIds[150] = 151;
				countryIds[151] = 152;
				countryIds[152] = 153;
				countryIds[153] = 154;
				countryIds[154] = 155;
				countryIds[155] = 156;
				countryIds[156] = 157;
				countryIds[157] = 158;
				countryIds[158] = 159;
				countryIds[159] = 160;
				countryIds[160] = 161;
				countryIds[161] = 162;
				countryIds[162] = 163;
				countryIds[163] = 164;
				countryIds[164] = 165;
				countryIds[165] = 166;
				countryIds[166] = 167;
				countryIds[167] = 168;
				countryIds[168] = 169;
				countryIds[169] = 170;
				countryIds[170] = 171;
				countryIds[171] = 172;
				countryIds[172] = 173;
				countryIds[173] = 174;
				countryIds[174] = 175;
				countryIds[175] = 176;
				countryIds[176] = 177;
				countryIds[177] = 178;
				countryIds[178] = 179;
				countryIds[179] = 180;
				countryIds[180] = 181;
				countryIds[181] = 182;
				countryIds[182] = 183;
				countryIds[183] = 184;
				countryIds[184] = 185;
				countryIds[185] = 186;
				countryIds[186] = 187;
				countryIds[187] = 188;
				countryIds[188] = 189;
				countryIds[189] = 190;
				countryIds[190] = 191;
				countryIds[191] = 192;
				countryIds[192] = 193;
				countryIds[193] = 194;
				countryIds[194] = 195;
				countryIds[195] = 196;
				countryIds[196] = 197;
				countryIds[197] = 198;
				countryIds[198] = 199;
				countryIds[199] = 200;
				countryIds[200] = 201;
				countryIds[201] = 202;
				countryIds[202] = 203;
				countryIds[203] = 204;
				countryIds[204] = 205;
				countryIds[205] = 206;
				countryIds[206] = 207;
				countryIds[207] = 208;
				countryIds[208] = 209;
				countryIds[209] = 210;
				countryIds[210] = 211;
				countryIds[211] = 212;
				countryIds[212] = 213;
				countryIds[213] = 214;
				countryIds[214] = 215;
				countryIds[215] = 216;
				countryIds[216] = 217;
				countryIds[217] = 218;
				countryIds[218] = 219;
				countryIds[219] = 220;
				countryIds[220] = 221;
				countryIds[221] = 222;
				countryIds[222] = 223;
				countryIds[223] = 224;
				countryIds[224] = 225;
				countryIds[225] = 226;
				countryIds[226] = 227;
				countryIds[227] = 228;
				countryIds[228] = 229;
				countryIds[229] = 230;
				countryIds[230] = 231;
				countryIds[231] = 232;
				countryIds[232] = 233;
				countryIds[233] = 234;
				countryIds[234] = 235;
				countryIds[235] = 236;
				countryIds[236] = 237;
				countryIds[237] = 238;
				countryIds[238] = 239;
				countryIds[239] = 240;
				countryIds[240] = 241;
				countryIds[241] = 242;
				countryIds[242] = 243;
				countryIds[243] = 244;
				countryIds[244] = 245;
				var countryNames = new Array(); 
				countryNames[0]= "Afghanistan"
				countryNames[1]= "Albania"
				countryNames[2]= "Algeria"
				countryNames[3]= "American Samoa"
				countryNames[4]= "Andorra"
				countryNames[5]= "Angola"
				countryNames[6]= "Anguilla"
				countryNames[7]= "Antarctica"
				countryNames[8]= "Antigua and Barbuda"
				countryNames[9]= "Argentina"
				countryNames[10]= "Armenia"
				countryNames[11]= "Aruba"
				countryNames[12]= "Australia"
				countryNames[13]= "Austria"
				countryNames[14]= "Azerbaijan"
				countryNames[15]= "Bahamas"
				countryNames[16]= "Bahrain"
				countryNames[17]= "Bangladesh"
				countryNames[18]= "Barbados"
				countryNames[19]= "Belarus"
				countryNames[20]= "Belgium"
				countryNames[21]= "Belize"
				countryNames[22]= "Benin"
				countryNames[23]= "Bermuda"
				countryNames[24]= "Bhutan"
				countryNames[25]= "Bolivia"
				countryNames[26]= "Bosnia and Herzegowina"
				countryNames[27]= "Botswana"
				countryNames[28]= "Bouvet Island"
				countryNames[29]= "Brazil"
				countryNames[30]= "British Indian Ocean Territory"
				countryNames[31]= "Brunei Darussalam"
				countryNames[32]= "Bulgaria"
				countryNames[33]= "Burkina Faso"
				countryNames[34]= "Burundi"
				countryNames[35]= "Cambodia"
				countryNames[36]= "Cameroon"
				countryNames[37]= "Canada"
				countryNames[38]= "Cape Verde"
				countryNames[39]= "Cayman Islands"
				countryNames[40]= "Central African Republic"
				countryNames[41]= "Chad"
				countryNames[42]= "Chile"
				countryNames[43]= "China"
				countryNames[44]= "Christmas Island"
				countryNames[45]= "Cocos (Keeling) Islands"
				countryNames[46]= "Colombia"
				countryNames[47]= "Comoros"
				countryNames[48]= "Congo"
				countryNames[49]= "Cook Islands"
				countryNames[50]= "Costa Rica"
				countryNames[51]= "Cote D'Ivoire"
				countryNames[52]= "Croatia"
				countryNames[53]= "Cuba"
				countryNames[54]= "Cyprus"
				countryNames[55]= "Czech Republic"
				countryNames[56]= "Denmark"
				countryNames[57]= "Djibouti"
				countryNames[58]= "Dominica"
				countryNames[59]= "Dominican Republic"
				countryNames[60]= "East Timor"
				countryNames[61]= "Ecuador"
				countryNames[62]= "Egypt"
				countryNames[63]= "El Salvador"
				countryNames[64]= "Equatorial Guinea"
				countryNames[65]= "Eritrea"
				countryNames[66]= "Estonia"
				countryNames[67]= "Ethiopia"
				countryNames[68]= "Falkland Islands (Malvinas)"
				countryNames[69]= "Faroe Islands"
				countryNames[70]= "Fiji"
				countryNames[71]= "Finland"
				countryNames[72]= "France"
				countryNames[73]= "France, Metropolitan"
				countryNames[74]= "French Guiana"
				countryNames[75]= "French Polynesia"
				countryNames[76]= "French Southern Territories"
				countryNames[77]= "Gabon"
				countryNames[78]= "Gambia"
				countryNames[79]= "Georgia"
				countryNames[80]= "Germany"
				countryNames[81]= "Ghana"
				countryNames[82]= "Gibraltar"
				countryNames[83]= "Greece"
				countryNames[84]= "Greenland"
				countryNames[85]= "Grenada"
				countryNames[86]= "Guadeloupe"
				countryNames[87]= "Guam"
				countryNames[88]= "Guatemala"
				countryNames[89]= "Guinea"
				countryNames[90]= "Guinea-bissau"
				countryNames[91]= "Guyana"
				countryNames[92]= "Haiti"
				countryNames[93]= "Heard and Mc Donald Islands"
				countryNames[94]= "Honduras"
				countryNames[95]= "Hong Kong"
				countryNames[96]= "Hungary"
				countryNames[97]= "Iceland"
				countryNames[98]= "India"
				countryNames[99]= "Indonesia"
				countryNames[100]= "Iran (Islamic Republic of)"
				countryNames[101]= "Iraq"
				countryNames[102]= "Ireland"
				countryNames[103]= "Israel"
				countryNames[104]= "Italy"
				countryNames[105]= "Jamaica"
				countryNames[106]= "Japan"
				countryNames[107]= "Jordan"
				countryNames[108]= "Kazakhstan"
				countryNames[109]= "Kenya"
				countryNames[110]= "Kiribati"
				countryNames[111]= "Korea, Democratic People's Republic of"
				countryNames[112]= "Korea, Republic of"
				countryNames[113]= "Kuwait"
				countryNames[114]= "Kyrgyzstan"
				countryNames[115]= "Lao People's Democratic Republic"
				countryNames[116]= "Latvia"
				countryNames[117]= "Lebanon"
				countryNames[118]= "Lesotho"
				countryNames[119]= "Liberia"
				countryNames[120]= "Libyan Arab Jamahiriya"
				countryNames[121]= "Liechtenstein"
				countryNames[122]= "Lithuania"
				countryNames[123]= "Luxembourg"
				countryNames[124]= "Macau"
				countryNames[125]= "Macedonia, The Former Yugoslav Republic of"
				countryNames[126]= "Madagascar"
				countryNames[127]= "Malawi"
				countryNames[128]= "Malaysia"
				countryNames[129]= "Maldives"
				countryNames[130]= "Mali"
				countryNames[131]= "Malta"
				countryNames[132]= "Marshall Islands"
				countryNames[133]= "Martinique"
				countryNames[134]= "Mauritania"
				countryNames[135]= "Mauritius"
				countryNames[136]= "Mayotte"
				countryNames[137]= "Mexico"
				countryNames[138]= "Micronesia, Federated States of"
				countryNames[139]= "Moldova, Republic of"
				countryNames[140]= "Monaco"
				countryNames[141]= "Mongolia"
				countryNames[142]= "Montserrat"
				countryNames[143]= "Morocco"
				countryNames[144]= "Mozambique"
				countryNames[145]= "Myanmar"
				countryNames[146]= "Namibia"
				countryNames[147]= "Nauru"
				countryNames[148]= "Nepal"
				countryNames[149]= "Netherlands"
				countryNames[150]= "Netherlands Antilles"
				countryNames[151]= "New Caledonia"
				countryNames[152]= "New Zealand"
				countryNames[153]= "Nicaragua"
				countryNames[154]= "Niger"
				countryNames[155]= "Nigeria"
				countryNames[156]= "Niue"
				countryNames[157]= "Norfolk Island"
				countryNames[158]= "Northern Mariana Islands"
				countryNames[159]= "Norway"
				countryNames[160]= "Oman"
				countryNames[161]= "Pakistan"
				countryNames[162]= "Palau"
				countryNames[163]= "Panama"
				countryNames[164]= "Papua New Guinea"
				countryNames[165]= "Paraguay"
				countryNames[166]= "Peru"
				countryNames[167]= "Philippines"
				countryNames[168]= "Pitcairn"
				countryNames[169]= "Poland"
				countryNames[170]= "Portugal"
				countryNames[171]= "Puerto Rico"
				countryNames[172]= "Qatar"
				countryNames[173]= "Reunion"
				countryNames[174]= "Romania"
				countryNames[175]= "Russian Federation"
				countryNames[176]= "Rwanda"
				countryNames[177]= "Saint Kitts and Nevis"
				countryNames[178]= "Saint Lucia"
				countryNames[179]= "Saint Vincent and the Grenadines"
				countryNames[180]= "Samoa"
				countryNames[181]= "San Marino"
				countryNames[182]= "Sao Tome and Principe"
				countryNames[183]= "Saudi Arabia"
				countryNames[184]= "Senegal"
				countryNames[185]= "Seychelles"
				countryNames[186]= "Sierra Leone"
				countryNames[187]= "Singapore"
				countryNames[188]= "Slovakia (Slovak Republic)"
				countryNames[189]= "Slovenia"
				countryNames[190]= "Solomon Islands"
				countryNames[191]= "Somalia"
				countryNames[192]= "South Africa"
				countryNames[193]= "South Georgia and the South Sandwich Islands"
				countryNames[194]= "Spain"
				countryNames[195]= "Sri Lanka"
				countryNames[196]= "St. Helena"
				countryNames[197]= "St. Pierre and Miquelon"
				countryNames[198]= "Sudan"
				countryNames[199]= "Suriname"
				countryNames[200]= "Svalbard and Jan Mayen Islands"
				countryNames[201]= "Swaziland"
				countryNames[202]= "Sweden"
				countryNames[203]= "Switzerland"
				countryNames[204]= "Syrian Arab Republic"
				countryNames[205]= "Taiwan"
				countryNames[206]= "Tajikistan"
				countryNames[207]= "Tanzania, United Republic of"
				countryNames[208]= "Thailand"
				countryNames[209]= "Togo"
				countryNames[210]= "Tokelau"
				countryNames[211]= "Tonga"
				countryNames[212]= "Trinidad and Tobago"
				countryNames[213]= "Tunisia"
				countryNames[214]= "Turkey"
				countryNames[215]= "Turkmenistan"
				countryNames[216]= "Turks and Caicos Islands"
				countryNames[217]= "Tuvalu"
				countryNames[218]= "Uganda"
				countryNames[219]= "Ukraine"
				countryNames[220]= "United Arab Emirates"
				countryNames[221]= "United Kingdom"
				countryNames[222]= "United States"
				countryNames[223]= "United States Minor Outlying Islands"
				countryNames[224]= "Uruguay"
				countryNames[225]= "Uzbekistan"
				countryNames[226]= "Vanuatu"
				countryNames[227]= "Vatican City State (Holy See)"
				countryNames[228]= "Venezuela"
				countryNames[229]= "Viet Nam"
				countryNames[230]= "Virgin Islands (British)"
				countryNames[231]= "Virgin Islands (U.S.)"
				countryNames[232]= "Wallis and Futuna Islands"
				countryNames[233]= "Western Sahara"
				countryNames[234]= "Yemen"
				countryNames[235]= "Serbia"
				countryNames[236]= "The Democratic Republic of Congo"
				countryNames[237]= "Zambia"
				countryNames[238]= "Zimbabwe"
				countryNames[239]= "East Timor"
				countryNames[240]= "Jersey"
				countryNames[241]= "St. Barthelemy"
				countryNames[242]= "St. Eustatius"
				countryNames[243]= "Canary Islands"
				countryNames[244]= "Montenegro"
				var stateList = new Array();
				stateList[223] = "AL:Alabama,AK:Alaska,AZ:Arizona,AR:Arkansas,CA:California,CO:Colorado,CT:Connecticut,DE:Delaware,DC:District Of Columbia,FL:Florida,GA:Georgia,HI:Hawaii,ID:Idaho,IL:Illinois,IN:Indiana,IA:Iowa,KS:Kansas,KY:Kentucky,LA:Louisiana,ME:Maine,MD:Maryland,MA:Massachusetts,MI:Michigan,MN:Minnesota,MS:Mississippi,MO:Missouri,MT:Montana,NE:Nebraska,NV:Nevada,NH:New Hampshire,NJ:New Jersey,NM:New Mexico,NY:New York,NC:North Carolina,ND:North Dakota,OH:Ohio,OK:Oklahoma,OR:Oregon,PA:Pennsylvania,RI:Rhode Island,SC:South Carolina,SD:South Dakota,TN:Tennessee,TX:Texas,UT:Utah,VT:Vermont,VA:Virginia,WA:Washington,WV:West Virginia,WI:Wisconsin,WY:Wyoming";
				stateList[38] = "AB:Alberta,BC:British Columbia,MB:Manitoba,NB:New Brunswick,NL:Newfoundland and Labrador,NT:Northwest Territories,NS:Nova Scotia,NU:Nunavut,ON:Ontario,PE:Prince Edward Island,QC:Quebec,SK:Saskatchewan,YT:Yukon";
				stateList[222] = "EN:England,NI:Northern Ireland,SD:Scotland,WS:Wales";
				stateList[13] = "AC:Australian Capital Territory,NS:New South Wales,NT:Northern Territory,QL:Queensland,SA:South Australia,TS:Tasmania,VI:Victoria,WA:Western Australia";
				stateList[138] = "AG:Aguascalientes,BN:Baja California Norte,BS:Baja California Sur,CA:Campeche,CS:Chiapas,CH:Chihuahua,CO:Coahuila,CM:Colima,DF:Distrito Federal,DO:Durango,GO:Guanajuato,GU:Guerrero,HI:Hidalgo,JA:Jalisco,EM:M,MI:Michoac,MO:Morelos,NY:Nayarit,NL:Nuevo Le,OA:Oaxaca,PU:Puebla,QU:Quer,QR:Quintana Roo,SP:San Luis Potos,SI:Sinaloa,SO:Sonora,TA:Tabasco,TM:Tamaulipas,TX:Tlaxcala,VZ:Veracruz,YU:Yucat,ZA:Zacatecas";
				stateList[30] = "AC:Acre,AL:Alagoas,AP:Amap,AM:Amazonas,BA:Bah,CE:Cear,DF:Distrito Federal,ES:Espirito Santo,GO:Goi,MA:Maranh,MT:Mato Grosso,MS:Mato Grosso do Sul,MG:Minas Gera,PR:Paran,PB:Para,PA:Par,PE:Pernambuco,PI:Piau,RN:Rio Grande do Norte,RS:Rio Grande do Sul,RJ:Rio de Janeiro,RO:Rond,RR:Roraima,SC:Santa Catarina,SE:Sergipe,SP:S,TO:Tocantins";
				stateList[44] = "34:Anhui,11:Beijing,50:Chongqing,35:Fujian,62:Gansu,44:Guangdong,45:Guangxi Zhuang,52:Guizhou,46:Hainan,13:Hebei,23:Heilongjiang,41:Henan,42:Hubei,43:Hunan,32:Jiangsu,36:Jiangxi,22:Jilin,21:Liaoning,15:Nei Mongol,64:Ningxia Hui,63:Qinghai,37:Shandong,31:Shanghai,61:Shaanxi,51:Sichuan,12:Tianjin,65:Xinjiang Uygur,54:Xizang,53:Yunnan,33:Zhejiang";
				stateList[104] = "IL:Israel,GZ:Gaza Strip,WB:West Bank";
				stateList[151] = "SM:St. Maarten,BN:Bonaire,CR:Curacao";
				stateList[175] = "AB:Alba,AR:Arad,AG:Arges,BC:Bacau,BH:Bihor,BN:Bistrita-Nasaud,BT:Botosani,BR:Braila,BV:Brasov,B:Bucuresti,BZ:Buzau,CL:Calarasi,CS:Caras Severin,CJ:Cluj,CT:Constanta,CV:Covasna,DB:Dambovita,DJ:Dolj,GL:Galati,GR:Giurgiu,GJ:Gorj,HR:Hargita,HD:Hunedoara,IL:Ialomita,IS:Iasi,IF:Ilfov,MM:Maramures,MH:Mehedinti,MS:Mures,NT:Neamt,OT:Olt,PH:Prahova,SJ:Salaj,SM:Satu Mare,SB:Sibiu,SV:Suceava,TR:Teleorman,TM:Timis,TL:Tulcea,VL:Valcea,VS:Vaslui,VN:Vrancea";
				stateList[105] = "AG:Agrigento,AL:Alessandria,AN:Ancona,AO:Aosta,AR:Arezzo,AP:Ascoli Piceno,AT:Asti,AV:Avellino,BA:Bari,BL:Belluno,BN:Benevento,BG:Bergamo,BI:Biella,BO:Bologna,BZ:Bolzano,BS:Brescia,BR:Brindisi,CA:Cagliari,CL:Caltanissetta,CB:Campobasso,CI:Carbonia-Iglesias,CE:Caserta,CT:Catania,CZ:Catanzaro,CH:Chieti,CO:Como,CS:Cosenza,CR:Cremona,KR:Crotone,CN:Cuneo,EN:Enna,FE:Ferrara,FI:Firenze,FG:Foggia,FC:Forli-Cesena,FR:Frosinone,GE:Genova,GO:Gorizia,GR:Grosseto,IM:Imperia,IS:Isernia,AQ:L'Aquila,SP:La Spezia,LT:Latina,LE:Lecce,LC:Lecco,LI:Livorno,LO:Lodi,LU:Lucca,MC:Macerata,MN:Mantova,MS:Massa-Carrara,MT:Matera,VS:Medio Campidano,ME:Messina,MI:Milano,MO:Modena,NA:Napoli,NO:Novara,NU:Nuoro,OG:Ogliastra,OT:Olbia-Tempio,OR:Oristano,PD:Padova,PA:Palermo,PR:Parma,PV:Pavia,PG:Perugia,PU:Pesaro e Urbino,PE:Pescara,PC:Piacenza,PI:Pisa,PT:Pistoia,PN:Pordenone,PZ:Potenza,PO:Prato,RG:Ragusa,RA:Ravenna,RC:Reggio Calabria,RE:Reggio Emilia,RI:Rieti,RN:Rimini,RM:Roma,RO:Rovigo,SA:Salerno,SS:Sassari,SV:Savona,SI:Siena,SR:Siracusa,SO:Sondrio,TA:Taranto,TE:Teramo,TR:Terni,TO:Torino,TP:Trapani,TN:Trento,TV:Treviso,TS:Trieste,UD:Udine,VA:Varese,VE:Venezia,VB:Verbano Cusio Ossola,VC:Vercelli,VR:Verona,VV:Vibo Valenzia,VI:Vicenza,VT:Viterbo";
				stateList[195] = "15:A Coru,01:Alava,02:Albacete,03:Alicante,04:Almeria,33:Asturias,05:Avila,06:Badajoz,07:Baleares,08:Barcelona,09:Burgos,10:Caceres,11:Cadiz,39:Cantabria,12:Castellon,51:Ceuta,13:Ciudad Real,14:Cordoba,16:Cuenca,17:Girona,18:Granada,19:Guadalajara,20:Guipuzcoa,21:Huelva,22:Huesca,23:Jaen,26:La Rioja,35:Las Palmas,24:Leon,25:Lleida,27:Lugo,28:Madrid,29:Malaga,52:Melilla,30:Murcia,31:Navarra,32:Ourense,34:Palencia,36:Pontevedra,37:Salamanca,38:Santa Cruz de Tenerife,40:Segovia,41:Sevilla,42:Soria,43:Tarragona,44:Teruel,45:Toledo,46:Valencia,47:Valladolid,48:Vizcaya,49:Zamora,50:Zaragoza";
				stateList[11] = "AG:Aragatsotn,AR:Ararat,AV:Armavir,GR:Gegharkunik,KT:Kotayk,LO:Lori,SH:Shirak,SU:Syunik,TV:Tavush,VD:Vayots-Dzor,ER:Yerevan";
				stateList[99] = "AI:Andaman & Nicobar Islands,AN:Andhra Pradesh,AR:Arunachal Pradesh,AS:Assam,BI:Bihar,CA:Chandigarh,CH:Chhatisgarh,DD:Dadra & Nagar Haveli,DA:Daman & Diu,DE:Delhi,GO:Goa,GU:Gujarat,HA:Haryana,HI:Himachal Pradesh,JA:Jammu & Kashmir,JH:Jharkhand,KA:Karnataka,KE:Kerala,LA:Lakshadweep,MD:Madhya Pradesh,MH:Maharashtra,MN:Manipur,ME:Meghalaya,MI:Mizoram,NA:Nagaland,OR:Orissa,PO:Pondicherry,PU:Punjab,RA:Rajasthan,SI:Sikkim,TA:Tamil Nadu,TR:Tripura,UA:Uttaranchal,UT:Uttar Pradesh,WE:West Bengal";
				stateList[101] = "BO:Ahmadi va Kohkiluyeh,AR:Ardabil,AG:Azarbayjan-e Gharbi,AS:Azarbayjan-e Sharqi,BU:Bushehr,CM:Chaharmahal va Bakhtiari,ES:Esfahan,FA:Fars,GI:Gilan,GO:Gorgan,HA:Hamadan,HO:Hormozgan,IL:Ilam,KE:Kerman,BA:Kermanshah,KJ:Khorasan-e Junoubi,KR:Khorasan-e Razavi,KS:Khorasan-e Shomali,KH:Khuzestan,KO:Kordestan,LO:Lorestan,MR:Markazi,MZ:Mazandaran,QA:Qazvin,QO:Qom,SE:Semnan,SB:Sistan va Baluchestan,TE:Tehran,YA:Yazd,ZA:Zanjan";
				methods = new PaymentMethods();
				method = new PaymentMethod('os_authnet',1,0,1,0, 1);
				methods.Add(method);
				var currentCampaign = 0 ;
				function checkData() {
					var form = document.donate_form;			
					var minimumAmount = 1 ;
					var maximumAmount = 100000 ;
								
					if (form.first_name.value == '') {
						alert("Please enter your first name");
						form.first_name.focus();
						return ;
					}						
					if (form.last_name.value=="") {
						alert("Please enter your last name");
						form.last_name.focus();
						return;
					}						
					if (form.address.value=="") {
						alert("Please enter your address");
						form.address.focus();
						return;	
					}						
					if (form.city.value == "") {
						alert("Please enter your city");
						form.city.focus();
						return;	
					}						
					if (form.state.length > 1) {
						if (form.state.value =="") {
							alert("Please enter your state");
							form.state.focus();
							return;	
						}
					}															
					if (form.zip.value == "") {
						alert("Please enter your zip");
						form.zip.focus();
						return;
					}						
					if (form.country.value == "") {
						alert("Please enter your country");
						form.country.focus();
						return;	
					}				
					if (form.phone.value == "") {
						alert("Please enter your phone");
						form.phone.focus();
						return;
					}						
										
					if (form.email.value == '') {
						alert("Please enter your email");
						form.email.focus();
						return;
					}
								
					var emailFilter = /^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i
					var ret = emailFilter.test(form.email.value);
					if (!ret) {
						alert("Please enter a valid email");
						form.email.focus();
						return;
					}									
					var amountValid = false ;
					var amount = 0 ;
					if (form.rd_amount) {
					if (form.rd_amount.length) {
							for (var i = 0 ; i < form.rd_amount.length ; i++) {
								if(form.rd_amount[i].checked == true) {
									amountValid = true ;
									amount = form.rd_amount[i].value ;
								}	
							}	
						} else if (form.rd_amount.checked == true) {
							amountValid = true ;
							amount = form.rd_amount.value ;
						}
															
					}

					if (!amountValid) {							
						if (parseFloat(form.amount.value)) {
							amountValid = true;
							amount = form.amount.value ;	
						}				
					}		
										
														
					if (!amountValid) {
						var msg;
							msg = "Please choose from pre-defined amounts or enter your own amount in the textbox";
						alert(msg);
						return;	
					}			


					if (parseFloat(amount) < minimumAmount) {
						alert("Minimum donation amount allowed is : $" + minimumAmount);
						form.amount.focus();
						form.amount.focus();
						return ;
					}

					if ((maximumAmount >0) && (parseFloat(amount) > maximumAmount)) {
						alert("Maximum donation amount allowed is : $" + maximumAmount);
						form.amount.focus();
						return ;
					}

					
					
				
													
													
					var paymentMethod = "";
					paymentMethod = "os_authnet";
										
					method = methods.Find(paymentMethod);				
					//Check payment method page
					if (method.getCreditCard()) {
						if (form.x_card_num.value == "") {
							alert("Please enter creditcard number");
							form.x_card_num.focus();
							return;					
						}					
						if (form.x_card_code.value == "") {
							alert("Please enter card code");
							form.x_card_code.focus();
							return ;
						}
					}
					if (method.getCardHolderName()) {
						if (form.card_holder_name.value == '') {
							alert("JE_ENTER_CARD_HOLDER_NAME");
							form.card_holde_name.focus();
							return ;
						}
					}			
					//This check is only used for echeck payment gateway
					if (paymentMethod == 'os_echeck') {
						if (form.x_bank_aba_code.value == '') {
							alert("Please enter Bank ABA Routing Number");
							form.x_bank_aba_code.focus();
							return ;
						}				
						if (form.x_bank_acct_num.value == '') {
							alert("Please enter Bank Account Number");
							form.x_bank_aba_code.focus();
							return ;
						}				
						if (form.x_bank_name.value == '') {
							alert("Please enter Bank Name");
							form.x_bank_name.focus();
							return ;		
						}				
						if (form.x_bank_acct_name.value == '') {
							alert("Please enter Account Holder Name");
							form.x_bank_acct_name.focus();
							return ;
						}				
					}				
					
						form.submit();
																												
				}			
											
				function checkNumber(txtName)
				{			
					var num = txtName.value			
					if(isNaN(num))			
					{			
						alert("Only number is accepted");			
						txtName.value = "";			
						txtName.focus();			
					}			
				}


				function changeDonationType() {
					var form = document.donate_form ;
					var trFrequecy = document.getElementById('tr_frequency');
					var trNumberDonatons = document.getElementById('tr_number_donations');								
					if (form.donation_type[0].checked == true) {
						trFrequecy.style.display = 'none' ;
						if (trNumberDonatons)
							trNumberDonatons.style.display = 'none' ;
					} else {
						trFrequecy.style.display = '' ;
						if (trNumberDonatons)
							trNumberDonatons.style.display = '' ;
					}	
				}	

				function deSelectRadio() {
					var form = document.donate_form ;
					form.amount.value = form.amount.value.replace(',', '') ;
					if (parseFloat(form.amount.value)) {
						if(form.rd_amount) {
						if (form.rd_amount.length) {
							for(var i =0 ; i < form.rd_amount.length ; i++) {
								form.rd_amount[i].checked = false ;
							}
						} else {
							form.rd_amount.checked = false ;
						}	
														
						}	
					} else {
						form.amount.value = '';
					}
				}

				function clearTextbox() {
					var form = document.donate_form ;
					if (form.amount)
						form.amount.value = '';	
				}		
						
				function displayRecurring(show) {	
					var form = document.donate_form ;		
					var trDonationType = document.getElementById('donation_type') ;
					if (!trDonationType)  
						return ;			
					var trFrequency = document.getElementById('tr_frequency');
					var trNumberDonations = document.getElementById('tr_number_donations') ;
					if (show) {
						trDonationType.style.display = '';
						if (form.donation_type[1].checked) {
							trFrequency.style.display = '';
							if (trNumberDonations) {
								trNumberDonations.style.display = '';
							}
						}				
					} else {
						trDonationType.style.display = 'none';
						trFrequency.style.display = 'none';
						if (trNumberDonations) {
							trNumberDonations.style.display = 'none';
						}
					}			
				}


				function checkCampaignRecurring() {					
					var form = document.donate_form ;
					var show = 1 ;
					var paymentMethod = "";
										paymentMethod = "os_authnet";
							
					method = methods.Find(paymentMethod);
					if (!method.getEnableRecurring()) {
						show = 0 ;
					} else {
						if (form.campaign_id.value > 0)
							show = recurrings [form.campaign_id.value] ;
					}							
					displayRecurring(show);
				}
							
				function updateAmount() {
					var form = document.donate_form ;
					var campaignId = form.campaign_id.value ;

					//Check to enable and disable recurring
					var show = 1 ;
					if (campaignId)
						show = recurrings [campaignId] ;		
					displayRecurring(show);						
								
				}
				function updateStateList() {
					var form = document.donate_form ;
					//First of all, we need to empty the state dropdown
					var list = form.state ;

					// empty the list
					for (i = 1 ; i < list.options.length ; i++) {
						list.options[i] = null;
					}
					list.length = 1 ;
					var i = 0;
					//Get the country index
					var country = form.country.value ;			
					if (country != '') {
						//Find index of the country
						for (var i = 0 ; i < countryNames.length ; i++) {
							if (countryNames[i] == country) {						
								break ;
							}
						}
						//We will find the states
						var countryId = countryIds[i] ;				
						var stateNames = stateList[countryId]; ;
						if (stateNames) {
							var arrStates = stateNames.split(',');
							i = 1 ;
							var state = '';
							var stateName = '' ;
							for (var j = 0 ; j < arrStates.length ; j++) {
								state = arrStates[j] ;
								stateName = state.split(':');
								opt = new Option();
								opt.value = stateName[0];
								opt.text = stateName[1];
								list.options[i++] = opt;
							}
							list.lenght = i ;
						}								
					}					
				}

				</script>	
			
<?php
    $output = apply_filters( 'wp_donate_filter_form', ob_get_contents());
    ob_end_clean();

    return $output;
}
?>
