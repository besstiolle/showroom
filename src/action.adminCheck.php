<?php

if (!isset($gCms)) exit;



$categories = $this->_getCategories();

if(empty($params['area']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'area vide', 'active_tab' => 'check'));


$listeUrl = explode('<br />',nl2br($params['area']));

$alert = "";
$insert = "";
foreach($listeUrl as $url)
{
	$url = trim($url);
	
	$query = 'SELECT count(*) from '.cms_db_prefix().'module_showroom_room where url = ?';
	$param = array($url);	
	$result = $db->getOne($query, $param);


	//Si deja en base
	if($result !=0)
	{
		$alert .= "'".$url."' existe d&eacute;j&agrave; en base<br>\n";
		continue;
	}

	//Si pas un regex valide
	if(!$this->_isRegexUrl($url))	
	{
		$alert .= "'".$url."' n'est pas une url valide<br>\n";
		continue;
	}

	//Si pas un site visible
	if(!$this->_isOpenUrl($url))	
	{
		$alert .= "'".$url."' n'est pas une url accessible<br>\n";
		continue;
	}

	//Si ce n'est pas une installation CMSMS
	if(!$this->_isCmsMSsite($url))	
	{
		$alert .= "'".$url."' n'est 'apparement' pas une installation sous CMSMS (r&eacute;pertoire admin non trouv&eacute;)<br>\n";
		continue;
	}
	
	//Si pas un CMSMS sous licence
	if(!$this->_isLicenceCmsMSsite($url))	
	{
		$alert .= "'".$url."' est une installation pirate<br>\n";
		continue;
	}
	
	$msg .= "'".$url."' est apparement valide<br>\n";
}

$this->Redirect($id, 'defaultadmin', $returnid, array('msgOk'=> $msg,'msgNOK' => $alert, 'active_tab' => 'check'));
?>