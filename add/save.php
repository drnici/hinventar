<?PHP
############################################################################################
$sys["root_path"] 	= "../../../../";
$sys["script"] 		= true;
$sys["nav_id"]		= 58;
############################################################################################
include ( $sys["root_path"] . "_global/header.php" );
############################################################################################

// Eingaben validieren
$person_id 			= htmlspecialchars ( mysql_escape_string ( $_POST["person_id"] ) );
$periode_id			= htmlspecialchars ( mysql_escape_string ( $_POST["periode_id"] ) );
$author_id			= $sys["user"]["person_id"];
$res_addtime		= time ( );
	
// Einzigartigkeit der Benutzer / Perioden-Kombination prüfen
$res_count 			= $db->fctCountData ( "bew_quali_res" , "`person_id` = '" . $person_id . "' AND `periode_id` = '" . $periode_id . "'" );

// Mindestens Person und Periode müssen ausgewählt werden (und die Kombination darf noch nicht existieren)
if ( !empty ( $person_id ) AND !empty ( $periode_id ) )
{
	if ( $res_count == 0 )
	{
		// Quali-Eintrag erstellen
		$db->fctSendQuery ( "INSERT INTO `bew_quali_res` (`person_id`,`periode_id`) VALUES (" . $person_id . "," . $periode_id . ")" );
		$res_id = mysql_insert_id ( );
		
		// Don't forget the history
		$db->fctSendQuery ( "INSERT INTO `bew_quali_history` (`res_id`,`person_id`,`history_time`,`history_text`) VALUES (" . $res_id . "," . $sys["user"]["person_id"] . "," . time ( ) . ",'Qualifikation erstellt')" );

		// Speicherung OK, weiterleiten zur Übersicht
		header ( "Location: ../?alert=add_ok&");
	}
	else
	{
		// die ausgewählte Kombination zwischen Benutzer und Periode existiert schon
		header ( "Location: ./?error=komb&person_id=" . $person_id . "&periode_id=" . $periode_id . "&" );
	}
}
else
{
	// nicht alle Felder ausgefüllt
	header ( "Location: ./?error=mand&person_id=" . $person_id . "&periode_id=" . $periode_id . "&" );
}

############################################################################################
include ( $sys["root_path"] . "_global/footer.php" );
############################################################################################
?>