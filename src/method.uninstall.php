<?php
if (!isset($gCms)) exit;

$db =& $gCms->GetDb();

// remove the database module_showroom_room
$dict = NewDataDictionary( $db );
$sqlarray = $dict->DropTableSQL( cms_db_prefix()."module_showroom_room" );
$dict->ExecuteSQLArray($sqlarray);

// remove the sequence
$db->DropSequence( cms_db_prefix()."module_showroom_room_seq" );

$this->DeleteTemplate();
$this->RemovePreference();

$this->RemovePermission('Set ShowRoom Prefs');

// put mention into the admin log
$this->Audit( 0, $this->Lang('friendlyname'), $this->Lang('uninstalled'));
?>