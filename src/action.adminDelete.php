<?php


if (!isset($gCms)) exit;



if(empty($params['idImg']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID vide', 'active_tab' => 'sites'));

	
$query = 'SELECT url from '.cms_db_prefix().'module_showroom_room where id = ?';
$param = array($params['idImg']);	
$url = $db->getOne($query, $param);

if(empty($url))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID inconnue', 'active_tab' => 'sites'));

$queryDelete = 'DELETE FROM '.cms_db_prefix().'module_showroom_room  WHERE id = ?';
$param = array($params['idImg']);
$result = $db->Execute($queryDelete, $param);
if ($result === false){die("Database error durant la suppression de la donn&eacute;e!");}
	

$retour .= "'".$url."' est supprim&eacute; avec succ&egrave;s<br>\n";

$this->Redirect($id, 'defaultadmin', $returnid, array('msgOk'=> $retour,'msgNOK' => $alert, 'active_tab' => 'sites'));
?>