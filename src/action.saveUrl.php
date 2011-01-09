<?php
if (!isset($gCms)) exit;

//On verifie le captcha
if (isset($this->cms->modules['Captcha'])) {
	$captcha = &$this->getModuleInstance('Captcha');
	if (TRUE == empty($params['captcha']) || ! $captcha->CheckCaptcha($params['captcha'])) {
		$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'captcha_ko','url' => $params['url'], 'categorie'=>$params['categorie']));
	}
}


$categories = $this->_getCategories();

if(empty($params['url']))
	$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'url_empty','url' => $params['url'], 'categorie'=>$params['categorie']));

if(empty($params['categorie']) || empty($categories[$params['categorie']]))
	$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'categorie_empty','url' => $params['url'], 'categorie'=>$params['categorie']));
	


$url = 'http://'.$params['url'];
$categorie = $params['categorie'];

$query = 'SELECT count(*) from '.cms_db_prefix().'module_showroom_room where url = ?';
$param = array($url);	
$result = $db->getOne($query, $param);


//Si deja en base
if($result !=0)
	$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'url_already_exist','url' => $params['url'], 'categorie'=>$params['categorie']));

//Si pas un regex valide
if(!$this->_isRegexUrl($url))	
	$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'url_non_regex','url' => $params['url'], 'categorie'=>$params['categorie']));

//Si pas un site visible
if(!$this->_isOpenUrl($url))	
	$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'url_non_exist','url' => $params['url'], 'categorie'=>$params['categorie']));

//Si pas un CMSMS
//if(!$this->_isCmsMSsite($url))	
//	$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'url_non_cmsms','url' => $params['url'], 'categorie'=>$params['categorie']));
	
//Si licence pirate
if(!$this->_isLicenceCmsMSsite($url))	
	$this->Redirect($id, 'default', $returnid, array('msgNOk'=> 'url_licence_pirate','url' => $params['url'], 'categorie'=>$params['categorie']));

//Insertion en base
$queryInsert = 'INSERT INTO '.cms_db_prefix().'module_showroom_room (id, url, text, id_user, id_category, state, date_submit) values (?,?,?,?,?,?,?)';
$sid = $db->GenID(cms_db_prefix().'module_oscs_rapport_tmp_seq');
$param = array($sid, $url, '', 0, $categorie, "00", $this->_getTimeForDB($db));
$result = $db->Execute($queryInsert, $param);
if ($result === false){die("Database error durant l'insert de la donn&eacute;e!");}

//Demande de nouvelle miniature
$this->_fopen($this->_newUrlCapture($url));

$this->Redirect($id, 'default', $returnid, array('msgOk'=> 'url_added', 'url' => $params['url'], 'categorie'=>$params['categorie']));
?>