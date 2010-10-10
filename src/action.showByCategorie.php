<?php

if (!isset($gCms)) exit;

$categories = $this->_getCategories();

if(empty($params['categorie']) || !isset($categories[$params['categorie']]))
	$this->Redirect($id, 'default', $returnid, array());

$db = &$gCms->GetDb();

//inclusion des catégories
require_once(dirname(__FILE__).'/action.showCategories.php');

$query = 'SELECT url FROM '.cms_db_prefix().'module_showroom_room WHERE state = ? AND id_category=? ORDER BY date_submit DESC limit 0,?';
$param = array("00", $params['categorie'], 99);	
$result = $db->execute($query, $param);

$listeImg = array();
while ($row = $result->FetchRow())
{
	$url = $row['url'];
	
	$img = new stdclass;
	$img->url = $url;
	$img->miniature = $this->_getUrlCapture($url,"320");
	$img->img = $this->_getUrlCapture($url,"1024");
	$listeImg[] = $img;
}

// Formulaire de renseignement de la clé
$smarty->assign('listeImg' , $listeImg);
$smarty->assign('categorie' , $categories[$params['categorie']]);

$smarty->assign_by_ref('module',$this);

echo $this->ProcessTemplate('showByCategorie.tpl');



?>