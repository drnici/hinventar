<?PHP
############################################################################################
$sys["root_path"] 	= "../../../../";
$sys["script"] 		= false;
$sys["nav_id"]		= 58;
############################################################################################
include ( $sys["root_path"] . "_global/header.php" );
############################################################################################
$sys["page_title"] 	= "Qualifikation erstellen";
############################################################################################

if ( isset ( $_GET["error"] ) )
{
	$person_id 			= htmlspecialchars ( mysql_escape_string ( $_GET["person_id"] ) );
	$periode_id			= htmlspecialchars ( mysql_escape_string ( $_GET["periode_id"] ) );
	
	if ( $_GET["error"] == "mand" )
	{
		echo ( "<p class=\"warning\"><b>Fehler</b>: Sie haben nicht alle nötigen Felder ausgefüllt.</p>\n" );
	}
	if ( $_GET["error"] == "komb" )
	{
		echo ( "<p class=\"warning\"><b>Fehler</b>: Die von Ihnen gewählte Kombination aus Person und Zeitraum existiert bereits.</p>\n" );
	}
}
else
{
	$person_id 			= 0;
	$periode_id			= 0;
}
?>

<form action="save.php" method="post" name="quali_add">

    <table>
    <tr>
    <th>Person</th>
    <th>*</th>
    <td>
        <select name="person_id" size="1">
        <option value="">..</option>
        <?PHP
        $person_result = $db->fctSendQuery ( "SELECT cp.person_id, cp.person_vorname, cp.person_name FROM `core_person` AS cp WHERE cp.role_id = 1 AND ( cp.person_s_semester = 1 OR cp.person_s_semester = 2 ) AND `beruf_id` = 1 AND cp.person_deactivation = 0 ORDER BY cp.person_vorname ASC, cp.person_name ASC" );
        
        while ( $person_data = mysql_fetch_array ( $person_result ) )
        {
            $s = "";
            if ( $person_id == $person_data["person_id"] ) $s = " selected=\"selected\"";
        
            echo ( "<option value=\"" . $person_data["person_id"] . "\"" . $s . ">" . $person_data["person_vorname"] . " " . $person_data["person_name"] . "</option>\n" );
        }
        ?>
        </select>
    </td>
    </tr>
    <tr>
    <th>Zeitraum</th>
    <th>*</th>
    <td>
		<?PHP
        $periode_result = $db->fctSendQuery ( "SELECT * FROM `bew_quali_periode`" );
        while ( $periode_data = mysql_fetch_array ( $periode_result ) )
        {
            $c = "";
            if ( $periode_id == $periode_data["periode_id"] ) $c = " checked=\"checked\"";
        
            echo ( "<input type=\"radio\" name=\"periode_id\" value=\"" . $periode_data["periode_id"] . "\" class=\"width_auto\"" . $c . " /> " . $periode_data["periode_title"] . " (" . $periode_data["periode_start"] . " - " . $periode_data["periode_end"] . ")<br />\n" );
        }
        ?>
    </td>
    </tr>
    </table>
    
    <p>
        <input type="submit" name="btn" value="Qualifikation erstellen" class="btn" />
        <input type="button" name="btn" value="Abbrechen" class="btn" onclick="self.location.href='../';" />
    </p>

</form>

<?PHP
############################################################################################
include ( $sys["root_path"] . "_global/footer.php" );
############################################################################################
?>