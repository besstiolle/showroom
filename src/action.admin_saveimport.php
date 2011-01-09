<?php

if (!isset($gCms)) exit;



$categories = $this->_getCategories();

if(empty($params['area']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'area vide', 'active_tab' => 'import'));

if(empty($params['categorie']) || empty($categories[$params['categorie']]))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'categorie vide', 'active_tab' => 'import'));



$listeUrl = explode('<br />',nl2br($params['area']));
$categorie = $params['categorie'];

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
	//if(!$this->_isCmsMSsite($url))	
	//{
	//	$alert .= "'".$url."' n'est pas une installation sous CMSMS<br>\n";
	//	continue;
	//}

	//Si pas un CMSMS
	if(!$this->_isLicenceCmsMSsite($url))	
	{
		$alert .= "'".$url."' est une installation pirate<br>\n";
		continue;
	}

	//Insertion en base
	$queryInsert = 'INSERT INTO '.cms_db_prefix().'module_showroom_room (id, url, text, id_user, id_category, state, date_submit) values (?,?,?,?,?,?,?)';
	$sid = $db->GenID(cms_db_prefix().'module_oscs_rapport_tmp_seq');
	$param = array($sid, $url, '', 0, $categorie, "00", $this->_getTimeForDB($db));
	$result = $db->Execute($queryInsert, $param);
	if ($result === false){die("Database error durant l'insert de la donn&eacute;e!");}

	//Demande de nouvelle miniature
	$this->_fopen($this->_newUrlCapture($url));
	
	$insert .= "'".$url."' est ajout&eacute;e avec succ&egrave;s<br>\n";
}

$this->Redirect($id, 'defaultadmin', $returnid, array('msgOk'=> $insert,'msgNOK' => $alert, 'active_tab' => 'import'));
?>