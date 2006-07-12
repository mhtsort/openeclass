<?

$langFiles = 'admin';

include '../../include/baseTheme.php';
include('../../include/lib/textLib.inc.php');

check_admin();

$nameTools = $langAdminAn;
$tool_content = "";

// default language
if (!isset($localize)) $localize='el';

// choose the database tables
if (isset($localize) and $localize == 'en') {
	$table_title = 'en_title';
	$table_content = 'en_body';
	$table_comment = 'en_comment';
} else {
	$table_title = 'gr_title';
	$table_content = 'gr_body';
	$table_comment = 'gr_comment';
}


// display settings 
	$displayAnnouncementList = true;
	$displayForm = true;

	// delete announcement command 
	if (isset($delete) && $delete) {
		$result =  db_query("DELETE FROM admin_announcements WHERE id='$delete'", $mysqlMainDb);
		$message = $langAdminAnnDel;
	}

	// moddify announcement command 
	if (isset($modify) && $modify) {
		$result = db_query("SELECT * FROM admin_announcements WHERE id='$modify'",$mysqlMainDb);
		$myrow = mysql_fetch_array($result);

		if ($myrow) {
			$AnnouncementToModify = $myrow['id'];
			$titleToModify = $myrow[$table_title];
			$contentToModify = $myrow[$table_content];
			$commentToModify = $myrow[$table_comment];
			$visibleToModify = $myrow['visible'];
			$displayAnnouncementList = true;
		}
	}

	// submit announcement command 
	if (isset($submitAnnouncement) && $submitAnnouncement) {
		// modify announcement 
		if($id) {
			if (isset($visible)) {
				db_query("UPDATE admin_announcements 
					SET $table_title='$title', $table_content='$newContent', $table_comment='$comment', visible='V', date=NOW() WHERE id=$id",$mysqlMainDb);
					
			} else {
				db_query("UPDATE admin_announcements 
				SET $table_title='$title', $table_content='$newContent', $table_comment='$comment', visible='I', date=NOW() WHERE id=$id",$mysqlMainDb);
				}
			$message = $langAdminAnnModify;
		}
		// add new announcement 
		else {
			// insert announcement 
			if (isset($visible)) {
			db_query("INSERT INTO admin_announcements 
					SET $table_title = '$title', $table_content = '$newContent', $table_comment = '$comment', date = NOW()");
				} else {
			db_query("INSERT INTO admin_announcements 
					SET $table_title = '$title', $table_content = '$newContent', $table_comment = '$comment', visible='I', date = NOW()");
				}
					$message = "$langAdminAnnAdd";
		}	// else
	}	// if $submit announcement

	// 	action message
	
	if (isset($message) && $message) {
		$tool_content .=  "<table><tbody><tr><td class=\"success\">$message</td></tr></tbody></table><br/>";
		$displayAnnouncementList = true;//do not show announcements
		$displayForm  = false;//do not show form
	}

	//	display form
	if ($displayForm ==  true && (@$addAnnouce==1 || isset($modify))) {

		// display add announcement command
		$tool_content .= "<form method='post' action='$_SERVER[PHP_SELF]?localize=$localize'>";
			if (isset($modify)) {
				$tool_content .= "$langAdminModifAnn";
			} else {
				$tool_content .=  "<p><b>".$langAdminAddAnn."</b></p><br>";
			}
		
		if (!isset($AnnouncementToModify)) $AnnouncementToModify ="";
		if (!isset($contentToModify))	$contentToModify ="";
		if (!isset($titleToModify))	$titleToModify ="";
		if (!isset($commentToModify))	$commentToModify ="";

		$tool_content .= "<table>";
		$tool_content .= "<tr><td>$langAdminAnnTitle</td></tr>";
		@$tool_content .= "<tr><td><input type=\"text\" name='title' value='$titleToModify' size='50'>";
		if (isset($visibleToModify) and $visibleToModify == 'V') 
				$tool_content .= "$langAdminAnVis : <input type=checkbox value=\"1\" name=\"visible\" checked></td></tr>";
		else		
				$tool_content .= "$langAdminAnVis : <input type=checkbox value=\"1\" name=\"visible\"></td></tr>";
		$tool_content .= "<tr><td>$langAdminAnnBody</td></tr>";
		@$tool_content .= "<tr><td><textarea name='newContent' value='$contentToModify' rows='20' cols='96'>$contentToModify</textarea></td></tr>";
		$tool_content .= "<tr><td><input type=\"hidden\" name=\"id\" value=\"".$AnnouncementToModify."\"></td></tr>";
		$tool_content .= "<tr><td>$langAdminAnnComm</td></tr>";
		@$tool_content .= "<tr><td><textarea name='comment' value='$comment' rows='2' cols='80'>$commentToModify</textarea></td></tr>";	
		$tool_content .= "<tr><td><input type=\"Submit\" name=\"submitAnnouncement\" value=\"$langOk\"></td></tr></table></form>";
		$tool_content .= "<br><br>";
	}

	// display admin announcements 
		if ($displayAnnouncementList == true) {
			$result = db_query("SELECT * FROM admin_announcements ORDER BY id DESC", $mysqlMainDb);
			$announcementNumber = mysql_num_rows($result);
			if (@$addAnnouce != 1) {
					$tool_content .= "<a href=\"".$_SERVER['PHP_SELF']."?addAnnouce=1&localize=$localize\">".$langAdminAddAnn."</a>";
			}
			$tool_content .=  "<table width=\"99%\">";
			if ($announcementNumber>0) {
				$tool_content .= "<thead><tr><th width=\"99%\">$langAdminAn</th>";
				$tool_content .= "</tr></thead>";
			}
		while ($myrow = mysql_fetch_array($result)) {
			$content = make_clickable($myrow[$table_content]);
			$content = nl2br($content);
			$tool_content .=  "<tbody><tr class='odd'><td>".$myrow[$table_title]." (".$langAdminAnnMes." ".$myrow['date'].")</td>";
			// display announcements content
			$tool_content .= "</tr>
				<tr><td colspan=2>".$content."<br>
				<a href=\"$_SERVER[PHP_SELF]?modify=".$myrow['id']."&localize=".$localize."\">
			  <img src=\"../../images/edit.gif\" border=\"0\" alt=\"".$langModify."\"></a>
			  <a href=\"$_SERVER[PHP_SELF]?delete=".$myrow['id']."&localize=".$localize."\">
			  <img src=\"../../images/delete.gif\" border=\"0\" alt=\"".$langDelete."\"></a>
			  </td></tr>";
			$tool_content .= "<tr><td>".$myrow[$table_comment]."</td></tr>";
			// blank line
			$tool_content .= "<tr><td>&nbsp;</td></tr>";
		}	// end while ($myrow = mysql_fetch_array($result))
		$tool_content .= "</tbody></table>";
	}	// end: if ($displayAnnoucementList == true)


if((@$addAnnouce == 1 || isset($modify))) {
	draw($tool_content, 3, 'announcements', $head_content, $body_action);
} else {
	draw($tool_content, 3, 'admin');
}
?>			
