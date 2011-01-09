<?php

if (!isset($gCms)) exit;

$categories = $this->_getCategories();

if(empty($params['categorie']) || !isset($categories[$params['categorie']]))
	$this->Redirect($id, 'default', $returnid, array());

$db = &$gCms->GetDb();

$query = 'SELECT url FROM '.cms_db_prefix().'module_showroom_room WHERE state = ? AND id_category=? ORDER BY id_category ASC limit 0,?';
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
	$img->md5 = md5($url);
	$listeImg[] = $img;
}

// Formulaire de renseignement de la cle
$smarty->assign('listeImg' , $listeImg);
$smarty->assign('categorie' , $categories[$params['categorie']]);

//Url de retour
$smarty->assign('backlink', $this->CreateReturnLink($id, $returnid, '', array(), true));

$smarty->assign_by_ref('module',$this);

//echo $this->ProcessTemplate('showByCategorie.tpl');

#Display template
echo "<!-- Displaying SHOWROOM Module -->\n";
$template = 'showByCategorie'.$this->GetPreference('current_showByCategorie_template');
if (isset($params['template']))
  {
    $template = 'showByCategorie'.$params['template'];
  }
echo $this->ProcessTemplateFromDatabase($template);
echo "<!-- END SHOWROOM Module -->\n";


?>