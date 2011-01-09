<?php

if (!isset($gCms)) exit;



if(empty($params['idImg']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID vide', 'active_tab' => 'sites'));

	
$query = 'SELECT url from '.cms_db_prefix().'module_showroom_room where id = ?';
$param = array($params['idImg']);	
$url = $db->getOne($query, $param);

if(empty($url))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID inconnue', 'active_tab' => 'sites'));

$alert = '';
	
//Si pas un regex valide
if(empty($alert) && !$this->_isRegexUrl($url))	
	$alert .= "'".$url."' n'est pas une url valide<br>\n";

//Si pas un site visible
if(empty($alert) && !$this->_isOpenUrl($url))	
	$alert .= "'".$url."' n'est pas une url accessible<br>\n";

//Si ce n'est pas une installation CMSMS
//if(empty($alert) && !$this->_isCmsMSsite($url))	
//	$alert .= "'".$url."' n'est pas une installation sous CMSMS<br>\n";
	
//Si pas un CMSMS
if(empty($alert) && !$this->_isLicenceCmsMSsite($url))	
	$alert .= "'".$url."' est une installation pirate<br/>\n";

if(empty($alert))
{
	$retour .= "'".$url."' est retest&eacute; avec succ&egrave;s<br>\n";
} else
{
	$queryUpdate = 'UPDATE '.cms_db_prefix().'module_showroom_room set state=? WHERE id = ?';
	$param = array(2,$params['idImg']);
	$result = $db->Execute($queryUpdate, $param);
	if ($result === false){die("Database error durant l'update de la donn&eacute;e!");}
	$alert .= "'".$url."' a &eacute;t&eacute; d&eacute;sactiv&eacute; automatiquement<br>\n";
}

$this->Redirect($id, 'defaultadmin', $returnid, array('msgOk'=> $retour,'msgNOK' => $alert, 'active_tab' => 'sites'));
?>