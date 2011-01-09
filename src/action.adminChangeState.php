<?php


if (!isset($gCms)) exit;



if(empty($params['idImg']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID vide', 'active_tab' => 'sites'));

if(!isset($params['state']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'nouvel &eacute;tat non renseign&eacute;', 'active_tab' => 'sites'));

	
$query = 'SELECT url from '.cms_db_prefix().'module_showroom_room where id = ?';
$param = array($params['idImg']);	
$url = $db->getOne($query, $param);

if(empty($url))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID inconnue', 'active_tab' => 'sites'));

$queryUpdate = 'UPDATE '.cms_db_prefix().'module_showroom_room set state=? WHERE id = ?';
$param = array($params['state'],$params['idImg']);
$result = $db->Execute($queryUpdate, $param);
if ($result === false){die("Database error durant l'update de la donn&eacute;e!");}
	

$retour .= "'".$url."' est mis &agrave; jour avec succ&egrave;s<br>\n";

$this->Redirect($id, 'defaultadmin', $returnid, array('msgOk'=> $retour,'msgNOK' => $alert, 'active_tab' => 'sites'));
?>