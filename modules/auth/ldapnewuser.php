<?php
/**=============================================================================
       	GUnet e-Class 2.0 
        E-learning and Course Management Program  
================================================================================
       	Copyright(c) 2003-2006  Greek Universities Network - GUnet
        A full copyright notice can be read in "/info/copyright.txt".
        
       	Authors:    Costas Tsibanis <k.tsibanis@noc.uoa.gr>
        	    Yannis Exidaridis <jexi@noc.uoa.gr> 
      		    Alexandros Diamantidis <adia@noc.uoa.gr> 

        For a full list of contributors, see "credits.txt".  
     
        This program is a free software under the terms of the GNU 
        (General Public License) as published by the Free Software 
        Foundation. See the GNU License for more details. 
        The full license can be read in "license.txt".
     
       	Contact address: GUnet Asynchronous Teleteaching Group, 
        Network Operations Center, University of Athens, 
        Panepistimiopolis Ilissia, 15784, Athens, Greece
        eMail: eclassadmin@gunet.gr
==============================================================================*/

/**===========================================================================
	ldapnewuser.php
* @version $Id$
	@authors list: Karatzidis Stratos <kstratos@uom.gr>
		       Vagelis Pitsioygas <vagpits@uom.gr>
==============================================================================        
  @Description: Introductory file that displays a form, requesting 
  from the user/prof to enter the account settings and authenticate
  him/her against the predefined method of the platform

 	
==============================================================================
*/

include '../../include/baseTheme.php';
include 'auth.inc.php';

$navigation[]= array ("url"=>"registration.php", "name"=> "$langReg");

// Initialise $tool_content
$tool_content = "";

// Main body
$auth = isset($_GET['auth'])?$_GET['auth']:'';
$authmethods = get_auth_active_methods();

if(!in_array($auth,$authmethods))		// means try to hack,attack
{
	die("$langInvalidAuth");
}
$msg = get_auth_info($auth);
$settings = get_auth_settings($auth);
if(!empty($msg)) $nameTools = "$langNewUserAccountÁctivation ($msg)";

$tool_content .= "
			
			<table cellspacing='0' cellpadding='0' width=\"50%\">
			<tr>
				<td>
				<FIELDSET>
	  			<LEGEND>$langUserExistingAccount</LEGEND>
				<form method=\"POST\" action=\"ldapsearch.php\">
				<table cellspacing='1' cellpadding='1' width=\"100%\">
				<tr>
					<td width=\"45%\">$langAuthUserName</td>
					<td><input type=\"text\" name=\"ldap_email\"></td>
				</tr>
				<tr>
					<td>$langAuthPassword</td>
					<td><input type=\"password\" name=\"ldap_passwd\"></td>
				</tr>
				<tr colspan=2>
					<td>
					<br>
				";

$tool_content .= "
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				    <td>
					<input type=\"hidden\" name=\"auth\" value=\"".$auth."\">
					<input type=\"submit\" name=\"is_submit\" value=\"".$reg."\">
					</td>
				</tr>
				<tr>
					<td colspan=\"2\">&nbsp;</td>
				</tr>
				<tr>
					<th colspan=\"2\" align=center>".$settings['auth_instructions']."</th>
				</table>
			</form>
			</FIELDSET>
			</td>
		</tr>
		</table>
			";

draw($tool_content,0,'auth');
?>
