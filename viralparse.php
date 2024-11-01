<?
/*
Plugin Name: ViralParse.Com Viral Link Creator
Plugin URI: http://viralparse.com/plugin/
Description: Generate thousands of traffic everyday using a special viral link and protect your contents from invalid visits. ViralParse Plugin give you the possibility to viral your marketing thought social networks with Share-To-Visits System, easy to use and simple just five minutes to start getting unlimited traffic for free and for ever
Version: 1.0
Author: ViralParse Developers
Author URI: https://viralparse.com
License: GPL
*/

if (!function_exists('is_admin'))
	{
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
	}

ERROR_REPORTING(0);
/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'viralparse_install');
/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'viralparse_remove');

function viralparse_install()
	{
	/* Creates new database field */
	add_option("viralparse_data", 'Default', '', 'yes');
	}

function viralparse_remove()
	{
	/* Deletes the database field */
	delete_option('viralparse_data');
	delete_option('wpp_access_code');
	}

add_action('admin_menu', 'ViralPage_Page');
add_action('admin_head', 'ViralPage_CSS');
$FAVICON = plugins_url('contents/favicon.ico', __FILE__);

function ViralPage_Page()
	{
	add_menu_page('Create Your Viral Link', 'ViralParse', 'administrator', 'viralparse', 'ViralParse_html_content', $FAVICON, '4.9');
	add_submenu_page('viralparse', 'Create Viral Links', 'Create Viral Links', 'administrator', 'viralparse');
	add_submenu_page('viralparse', 'Your Viral Links', 'Your Viral Links', 'administrator', 'virallinks', 'ViralLinks_html_content');
	}

require ('secure.php');

?>
<?php

function ViralPage_CSS()
	{ ?>
<style>
.start {  height: 100%; background:#FFF; margin:0 0 0 -19px; padding: 20px 15px; font-size:18px; }
.pp { font-size:16px; font-family: 'Comic Sans MS', Arial; width: 700px; }
.Ttable { margin:5px 0; height:100%; }
#sep { border-left: 1px dashed #000; border-right: 1px dashed #000; width:300px;  vertical-align:text-top;   }
#fst { min-width:700px; text-align: left; }
#bene { font-size:16px; margin:10px 16px; color:#000; }
#error { background: #FFDFDF; color:#FF0000; font-weight: bold; font-size: 14px; border:1px solid #FF0000; width: 650px; padding: 10px 25px; margin:20px auto;}
#alert { background: #D6FFCF; color:#1CBF00; font-weight: bold; font-size: 14px; border:1px solid #1CBF00; width: 650px; padding: 10px 25px; margin:20px auto;}
</style>
<link rel="shortcut icon" type="image/x-icon" href="<?php
	echo plugins_url('contents/favicon.ico', __FILE__) ?>" />
<script>
function HELP(){
window.alert("Examples :  \n   1- Link URL : http://viralparse.com/ (must start with http:// ) \n   2- Picture URL : http://example.com/image.jpg ( must be a URL ) \n   3- Link Title : (more than 30 characters ) \n   4- Link Description : ( More than 50 characters ) ");
}
</script>
<?php
	} ?>
<?php

function ViralParse_html_content()
	{
	if (isset($_POST['submit']) && (get_option('wpp_access_code', 'NOWPP') == 'NOWPP'))
		{
		$verti = get_option('admin_email');
		if (!empty($verti))
			{
			$email = get_option('admin_email');
			}
		  else
			{
			$email = 'admin@' . $_SERVER['SERVER_NAME'];
			}

		if (filter_var($_POST['blogurl'], FILTER_VALIDATE_URL))
			{
			$blogurl = $_POST['blogurl'];
			}
		  else
			{
			$blogurl = 'NotSet';
			}

		if (!empty($_POST['fullname']) AND !empty($_POST['blogname']) AND !empty($_POST['blogurl']) AND ($email != 'NotSet') AND ($blogurl != 'NotSet'))
			{
			$RESPONSE = VPregister($email, $_POST['blogname'], get_option('siteurl') , $_POST['fullname']);
			if (strlen(trim($RESPONSE)) == 50)
				{
				add_option('wpp_access_code', $RESPONSE);
				$ERROR = '<div id="alert">SUCCESS : Your Blog has been registered and linked with ViralParse.Com .<BR />Your WPP Access Code : <b>' . $RESPONSE . '</b> <br />(SAVE IT)</div>';
				}
			  else
				{
				$ERROR = '<div id="error">ERROR : Register Failed. check if you have already an account with this data.<BR />' . $RESPONSE . '</div>';
				}
			}
		  else
			{
			$ERROR = '<div id="error">ERROR : Please fill the form with your correct informations { Email, URL }</div>';
			}
		}

	if (isset($_POST['submitwpp']) && (get_option('wpp_access_code', 'NOWPP') == 'NOWPP'))
		{
		$acs = trim($_POST['wpp_access_code']);
		if (strlen($acs) == 50)
			{
			$ISCONNECTED = VPconnect($acs);
			if (trim($ISCONNECTED) == 'CONNECTED')
				{
				add_option('wpp_access_code', $_POST['wpp_access_code']);
				$ERROR = '<div id="alert">SUCCESS : Your Blog has been registered and linked with ViralParse.Com .<BR />Your WPP Access Code : <b>' . $_POST['wpp_access_code'] . '</b> <br />(SAVE IT)</div>';
				}
			  else
				{
				$ERROR = '<div id="error">ERROR : WPP Access Code not valid.</div>';
				}
			}
		  else
			{
			$ERROR = '<div id="error">ERROR : Please Provide a correct WPP Access Code or Create a new one.</div>';
			}
		}

	if (isset($_POST['create']) && (get_option('wpp_access_code', 'NOWPP') != 'NOWPP'))
		{
		$SOCIAL = $_POST['social'];
		if (!empty($SOCIAL))
			{
			$N = count($SOCIAL);
			for ($i = 0; $i < $N; $i++)
				{
				$SN.= $SOCIAL[$i] . ', ';
				}

			if (filter_var($_POST['link_url'], FILTER_VALIDATE_URL) && @getimagesize($_POST['link_picture']) && strlen($_POST['link_title']) > 30 && strlen($_POST['link_description']) > 50)
				{
				$siteurl = get_option('siteurl');
				$wpp_access_code = get_option('wpp_access_code');
				$ISCREATED = VPcreate($_POST['link_url'], $_POST['link_title'], $_POST['link_picture'], $_POST['link_description'], $SN, $wpp_access_code);
				if ($ISCREATED != 'ERROR' AND !empty($ISCREATED))
					{
					if ($ISCREATED != 'TIMEWAIT')
						{
						if ($ISCREATED != 'EXIST')
							{
							$ERROR = '<div id="alert">Your Viral Link Has Been Created.<br />Your Viral Link : <a href="' . $ISCREATED . '" target="_blanc">' . $ISCREATED . '</a>  (START SHARING IT)</div>';
							}
						  else
							{
							$ERROR = '<div id="error">ERROR : Link URL already used by you.</div>';
							}
						}
					  else
						{
						$ERROR = '<div id="error">ERROR : Please wait at least 30 minutes to create another viral link.</div>';
						}
					}
				  else
					{
					$ERROR = '<div id="error">ERROR : Creating viral link failed please try later. ' . $ISCREATED . '</div>';
					}
				}
			  else
				{
				$ERROR = '<div id="error">ERROR : Please provide a correct data : <a href="#" onclick="return HELP();">Click Here</a>.</div>';
				}
			}
		  else
			{
			$ERROR = '<div id="error">ERROR : Please select at least one social network.</div>';
			}
		}

?>
<div class="start">
   <img src="<?php
	echo plugins_url('contents/logo.png', __FILE__) ?>" />
   <div style="float: right; font-size:25px; margin-right: 280px;">ViralParse.Com => Traffic => Sales => Money
   <br />
   <input type="text" style="width:450px; text-align: center; background: #6FFF75; padding:10px 2px; color:#000; border:1px dashed #000; border-radius:10px; margin-top:10px; box-shadow:0 0 5px #000;" value="<?php
	echo get_option('wpp_access_code', 'Connect Your Wordpress Plugin With ViralParse.Com') ?>"/>
   </div>
   <br />
   <table class="Ttable">
      <tr valign="top">
         <td id="fst">
            <p class="pp">
               Viral your business and get thousands of traffic everyday.
               <br />Make all your posts go viral via different social networks available in less than 5 min.
               <br />Getting traffic will be easy as 1,2,3 with ViralParse.Com - <a target="_blanc" href="http://viralparse.com/how.php">How ViralParse.Com Work</a>
               <?php
	echo $ERROR
?>
			   <?php
	if (get_option('wpp_access_code', 'NOWPP') == 'NOWPP')
		{ ?>
               <!---------- PLUGIN ACTIVATED ----------->
            <div style="background: #E7E7E7; box-shadow: 0px 0px 25px #999; width:600px; text-align:left;margin:0 auto; color:#000; border:1px solid #F5891E; padding: 10px 15px; border-radius:15px;">
               <h3 align="center" style="color: #F5891E;">Please activate your plugin</h3>
               <h4>Create your WPP Access Code</h4>
               <form name="reister" method="post">
                  <table style="margin:0 auto;">
                     <tr>
                        <td>Full Name</td>
                        <td><input size="50" type="text" name="fullname" placeholder="Full Name" required /></td>
                     </tr>
                     <tr>
                        <td>Blog Name</td>
                        <td><input size="50" type="text" name="blogname" placeholder="Blog Name" value="<?php
		echo get_option('blogname') ?>" required /></td>
                     </tr>
                     <?php
		if (filter_var(get_option('admin_email') , FILTER_VALIDATE_EMAIL) == false)
			{ ?>
                     <tr>
                        <td>E-mail</td>
                        <td><input size="50" type="text" name="email" placeholder="E-mail" required /></td>
                     </tr>
                     <?php
			} ?>
                     <tr>
                        <td>Blog URL</td>
                        <td><input size="50" type="text" name="blogurl" placeholder="Blog URL" value="<?php
		echo get_option('siteurl') ?>" required /></td>
                     </tr>
                     <tr>
                        <td></td>
                        <td><input style="width:430px;" type="submit" name="submit" value="REGISTER MY BLOG"></td>
                     </tr>
                  </table>
				  </form>
                  <h4>Already have a WPP ACCESS CODE ?</h4>
				  <form name="reister" method="post">
                  <table style="margin:0 auto;">
                     <tr>
                        <td>WPP Access Code</td>
                        <td><input size="50" type="text" name="wpp_access_code" placeholder="WPP Access Code" required /></td>
                     </tr>
                     <tr>
                        <td></td>
                        <td><input style="width:430px;" type="submit" name="submitwpp" value="REGISTER MY BLOG"></td>
                     </tr>
                  </table>
               </form>
            </div>
            <!-------- END PLUGIN ACTIVATED --------->
            <?php
		}
	  else
		{ ?>
			<!---------- VIRAL LINK CREATION ----------->
			<form method="POST" name="createVL">
            <div style="background: #E7E7E7; box-shadow: 0px 0px 25px #999; width:600px; text-align:left;margin:0 auto; color:#000; border:1px solid #F5891E; padding: 10px 15px; border-radius:15px;">
			<h3 align="center" style="color: #F5891E;">Create your Viral Link</h3>
            <h4><a href="admin.php?page=virallinks">Show Your Viral Links</a></h4>
			
			<table style="margin:0 auto; font-size:16px;">
                     <tr valign="top">
                        <td>Social Networks</td>
                        <td>
						<INPUT TYPE="CHECKBOX" VALUE="FB" NAME="social[]" checked />Facebook
						<INPUT TYPE="CHECKBOX" VALUE="TW" NAME="social[]" checked />Twitter
						<INPUT TYPE="CHECKBOX" VALUE="LK" NAME="social[]" checked />LinkedIn
						<INPUT TYPE="CHECKBOX" VALUE="GP" NAME="social[]" checked />Google+
						<INPUT TYPE="CHECKBOX" VALUE="VK" NAME="social[]" checked />Vk.Com
						</td>
                     </tr>
                     <tr>
                        <td>Link URL</td>
                        <td><input size="50" type="text" name="link_url" placeholder="http://" value="<?php
		echo $_POST['link_url'] ?>" required /></td>
                     </tr>
                     <tr>
                        <td>Picture URL</td>
                        <td><input size="50" type="text" name="link_picture" placeholder="http://" value="<?php
		echo $_POST['link_picture'] ?>" required /></td>
                     </tr>
					 <tr>
                        <td>Link Title</td>
                        <td><input size="50" type="text" name="link_title" placeholder="Link Title" value="<?php
		echo $_POST['link_title'] ?>" required /></td>
                     </tr>
					 <tr>
                        <td>Link Descriptions</td>
                        <td><input size="50" type="text" name="link_description" placeholder="Link Description" value="<?php
		echo $_POST['link_description'] ?>" required /></td>
                     </tr>
                     <tr>
                        <td></td>
                        <td><input style="width:430px;" type="submit" name="create" value="CREATE MY VIRAL LINK"></td>
                     </tr>
                  </table>
				  
			</div>
			</form>
			<!-------- END VIRAL LINK CREATION --------->
            <?php
		} ?>
            <br />
            <h3>Benefits :</h3>
            There are more benefits with ViralParse.Com<br />
            <div id="bene">
               <img src="<?php
	echo plugins_url('contents/validate.png', __FILE__) ?>" /> 100% FREE AND EASY no special accounts or membership and easy to use.<br />
               <img src="<?php
	echo plugins_url('contents/validate.png', __FILE__) ?>" /> Protect your URL via ViralParse.Com with SSL and avoid being banned from social networks ( For SPAM ). <br />
               <img src="<?php
	echo plugins_url('contents/validate.png', __FILE__) ?>" /> You don't need to share your links everyday or pay advertisement or exchange traffic.<br />
               <img src="<?php
	echo plugins_url('contents/validate.png', __FILE__) ?>" /> It is 100% automatic and real, Only real visitors can visit your website and read your articles/posts.<br />
               <img src="<?php
	echo plugins_url('contents/validate.png', __FILE__) ?>" /> Get more targeted traffic with the Share-to-Visit System provided by ViralParse.Com<br />
               <img src="<?php
	echo plugins_url('contents/validate.png', __FILE__) ?>" /> Easy access from plugin and less time creation.<br />
            </div>
            </p>
         </td>
         <td id="sep">
		 <!-- This Line is Used to Get News and Notifications from ViralParse.Com Server -->
		 <div style="margin:0 auto; width:290px;">
<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fviralparse&amp;width=290&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=472913679497409" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:290px; height:258px;" allowTransparency="true"></iframe>
</div>
		 </td>
      </tr>
   </table>
   <div style="text-align:center; font-size:12px; color: #999;">&copy; Copyright 2013 - 2016 <a target="_blanc" href="http://viralparse.com" style="text-align:center; font-size:12px; color: #999;">ViralParse.Com</a></div>
</div>
<?php
	}

function ViralLinks_html_content()
	{
	set_time_limit(180);
	$ACC = get_option('wpp_access_code', 'NOWPP');
	if ($ACC != 'NOWPP')
		{
		$contents = VPlinks($ACC);
		$DATAS = explode('<br />', $contents);
		unset($DATAS[COUNT($DATAS) - 1]);
		}

?>
<div class="start">
   <img src="<?php
	echo plugins_url('contents/logo.png', __FILE__) ?>" />
   <div style="float: right; font-size:25px; margin-right: 280px;">ViralParse.Com => Traffic => Sales => Money
   <br />
   <input type="text" style="width:450px; text-align: center; background: #6FFF75; padding:10px 2px; color:#000; border:1px dashed #000; border-radius:10px; margin-top:10px; box-shadow:0 0 5px #000;" value="<?php
	echo get_option('wpp_access_code', 'Connect Your Wordpress Plugin With ViralParse.Com') ?>"/>
   </div>
   <br />
   
   <table class="Ttable">
      <tr valign="top">
         <td id="fst">
		 <p class="pp">
		 Remember don't miss to share your links in the social networks to keep it working.<br />
		 All your viral links data are available here.
		 </p>
		 <?php
	if (get_option('wpp_access_code', 'NOWPP') != 'NOWPP')
		{ ?>
		 <div style="background: #E7E7E7; box-shadow: 0px 0px 25px #999; width:720px; text-align:left;margin:0 auto; color:#000; border:1px solid #F5891E; padding: 10px 15px; border-radius:15px;">
		 <h3 align="center" style="color: #F5891E;">Your Viral Links List</h3>
		 <table style="border:1px solid #000; font-size: 14px; width:100%; border-collapse: collapse;" border="1">
		 <tr style="text-align:center; font-weight:bold;"><td>Viral Link</td><td>URL</td><td>Title</td></tr>
		 <?php
		if (empty($DATAS))
			{ ?>
		 <tr><td colspan="3" align="center">No Data Available</td></tr>
		 <?php
			}
		  else
			{
			foreach($DATAS as $L)
				{
				$LD = explode(';', $L);
?>
		 <tr valign="top"><td style="width:250px;"><input style="width:250px; box-shadow:0px 0px 10px #000;" type="text" value="<?php
				echo $LD[0] ?>" onclick="this.select()" /></td><td><?php
				echo $LD[1] ?></td><td width="20"><textarea STYLE="box-shadow:0px 0px 10px #000;" cols="20" rows="4"><?php
				echo $LD[2] ?></textarea></td></tr>
		 <?php
				}
			} ?>
		 </table>
		 
		 </div>
		 <?php
		}
	  else
		{ ?>
   Please Activate your WordPress Plugin <a href="admin.php?page=viralparse">Click Here To Activate Your ViralParse.Com with a WPP Access Code</a>
   <?php
		} ?>
		 
		 </td>
		 <td id="sep">
		 <div style="margin:0 auto; width:290px;">
<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fviralparse&amp;width=290&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=472913679497409" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:290px; height:258px;" allowTransparency="true"></iframe>
</div>
		 </td>
	  </tr>
   </table>
   
   <div style="text-align:center; font-size:12px; color: #999;">&copy; Copyright 2013 - 2016 <a target="_blanc" href="http://viralparse.com" style="text-align:center; font-size:12px; color: #999;">ViralParse.Com</a></div>
   
   </div>
<?php
}
?>