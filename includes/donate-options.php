<?php

function wp_donate_options_page() 
{ global $wpdb;
    ?>
	<?php 
	if($_GET['id']!='' && $_GET['action']=='delete')
	{ 
		$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."donate WHERE id = %d",$_GET['id']));
		header("Location:".site_url().'/wp-admin/admin.php?page=wp_donate');
			exit;
	}	
	elseif($_GET['id']!='')
	{
	?>
		<div id="wp-donate-tabs">
		  <h1 class="donate-title">WP Donate</h1>
		  <div id="wp-donate-tab-donorlist">
			<table class="wp-donate-donorlist" width="50%">
			  <thead>					 
				  <tr>
					<?php global $wpdb;
					$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate where id='".$_GET['id']."'" );						
					foreach($myrows as $myrows_value)
					{
					?>
					<tr>
						<td width="125"><b>First Name :</b></td>
						<td><?php echo $myrows_value->first_name;?></td>
					</tr>
					<tr>
						<td><b>Last Name :</b></td>							
						<td><?php echo $myrows_value->last_name;?></td>
					</tr>
					<tr>
						<td><b>Organization :</b></td>							
						<td><?php echo $myrows_value->organization;?></td>
					</tr>
					<tr>
						<td><b>Address :</b></td>							
						<td><?php echo $myrows_value->address;?></td>
					</tr>
					<tr>
						<td><b>City :</b></td>							
						<td><?php echo $myrows_value->city;?></td>
					</tr>
					<tr>
						<td><b>Country :</b></td>							
						<td><?php echo $myrows_value->country;?></td>
					</tr>
					<tr>
						<td><b>State :</b></td>							
						<td><?php echo $myrows_value->state;?></td>
					</tr>
					<tr>
						<td><b>Zip : </b></td>							
						<td><?php echo $myrows_value->zip;?></td>
					</tr>
					<tr>
						<td><b>Phone :</b></td>							
						<td><?php echo $myrows_value->phone;?></td>
					</tr>
					<tr>
						<td><b>Email :</b></td>							
						<td><?php echo $myrows_value->email;?></td>
					</tr>
					<?php /*?>
					<tr>
						<td><b>Donation Type :</b></td>							
						<td><?php echo $myrows_value->donation_type;?></td>
					</tr>
					<?php */?>					
					<tr>
						<td><b>Amount :</b></td>							
						<td>$<?php echo $myrows_value->amount;?></td>
					</tr>
					<tr>
						<td><b>Comment :</b></td>							
						<td><?php echo $myrows_value->comment;?></td>
					</tr>
					
					<tr>
						<td><b>Date :</b></td>							
						<td align="left"><?php echo $myrows_value->date;?></td>
					</tr>
					<tr>	
						<td><b>Action : </b></td>
						<td align="left"><?php if($myrows_value->status==1){echo "Complete";} else {echo "Pending";}?></td>
					</tr>
					<?php } ?>
					<tr>
						<td><input type="button" onclick=location.href='<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate' value="Back" /></td>
					</tr>
				  </tr>
			  </thead>
			</table>
		</div>
		</div>
	<?php
	}
	else
	{
	?>
    <script type="text/javascript">
        jQuery(function() {
            jQuery("#wp-donate-tabs").tabs();
        });
    </script>

    <div id="wp-donate-tabs">
        <h1 class="donate-title">WP Donate</h1>
        <ul id="wp-donate-tabs-nav">
            <li><a href="#wp-donate-tab-donorlist">Donor List</a></li>
            <li><a href="#wp-donate-tab-settings">Settings</a></li>
        </ul>
        <div style="clear:both"></div>
        <div id="wp-donate-tab-donorlist">
            <table class="wp-donate-donorlist" width="100%">
              <?php 
              global $wpdb;
				$myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate");
				if(count($myrows)>0)
				{
              ?>
              <thead>
				  <tr class="wp-donate-absolute">
                  <th align="left">Person</th>
                  <th align="left">Email</th>
                  <th align="left">Amount</th>
                  <th align="left">Date</th>
                  <th align="left">Comment</th>
                  <th align="left">Action</th>
              </tr>
				<?php 
					foreach($myrows as $myrows_value)
					{
					?>
					<tr>
						<td><a href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate&id=<?php echo $myrows_value->id;?>"><?php echo $myrows_value->first_name.' '.$myrows_value->last_name ;?></a></td>
						<td><?php echo $myrows_value->email;?></td>
						<td>$<?php echo $myrows_value->amount;?></td>
						<td><?php echo $myrows_value->date;?></td>
						<td><?php echo $myrows_value->comment;?></td>
						<td><a onclick="return confirm('Are you sure?')" href="<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate&action=delete&id=<?php echo $myrows_value->id;?>">Delete</a></td>
					</tr>
					<?php 
					} 
					?>
              </thead>
              <?php 
				}
				else
				{
				echo "No Record's Found.";	
				}
              ?>
            </table>
        </div>
        <div id="wp-donate-tab-settings">			
			<?php
			global $wpdb;
			$mysetting = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."donate_setting" );						
			?>
			<form action="<?php echo site_url();?>/wp-admin/admin.php?page=wp_donate" method="post" name="setting" id="setting">
				<input type="hidden" name="setting" value="1" />
				<ul style="color:#777">
					<li>
						<strong>Authorize.net Mode</strong> 
						<select class="inputbox" name="authnet_mode" id="paramsauthnet_mode">
							<option value="0" <?php if($mysetting[0]->mod==0){?> selected="selected" <?php }?> >Test Mode</option>
							<option <?php if($mysetting[0]->mod==1){?> selected="selected" <?php }?> value="1">Live Mode</option>
						</select>
					</li>
					<li>
						<strong>API Login</strong> 
						<input type="text" size="50" class="text_area" value="<?php echo $mysetting[0]->api_login;?>" id="paramsx_login" name="x_login">
					</li>
					<li>
						<strong>Transaction Key</strong> 
						<input type="text" class="text_area" value="<?php echo $mysetting[0]->key;?>" id="paramsx_tran_key" name="x_tran_key">
					</li>
					<br />
					<input type="submit" value="Submit" />					
				</ul>
            </form>
        </div>
        
    </div>
<?php
	}
}



?>
