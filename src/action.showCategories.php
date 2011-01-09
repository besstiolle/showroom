<?php

if (!isset($gCms)) exit;

$db = &$gCms->GetDb();

$query = 'SELECT count(*) as compteur, id_category FROM '.cms_db_prefix().'module_showroom_room WHERE state = ? GROUP BY id_category ORDER BY date_submit DESC limit 0,?';
$param = array("00", 99);	
$result = $db->execute($query, $param);

$listeCategorie = array();
$categories = $this->_getCategories();
while ($row = $result->FetchRow())
{
	$compteur = $row['compteur'];
	$idcategorie = $row['id_category']; 

	$libelleCategorieSansAccent = html_entity_decode($categories[$idcategorie]);
	$libelleCategorieSansAccent = strtr($libelleCategorieSansAccent,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	$libelleCategorieSansAccent = preg_replace('/[^a-z0-9-_]+/i','_',$libelleCategorieSansAccent);
    $libelleCategorieSansAccent = trim($libelleCategorieSansAccent, '_');

	
	$myCategorie = new stdclass;
	$myCategorie->compteur = $compteur;
	$myCategorie->categorie = $categories[$idcategorie];
	$myCategorie->link =  $this->CreateLink($id, 'showByCategorie', $returnid, $categories[$idcategorie], array('categorie'=>$idcategorie),'',false,false,'',false,"showroom/$idcategorie/$returnid/Realisations_Cms_Made_Simple/$libelleCategorieSansAccent");
	$listeCategorie[] = $myCategorie;
}

// Formulaire de renseignement de la cle
$smarty->assign('listeCategorie' , $listeCategorie);
$smarty->assign_by_ref('module',$this);

//echo $this->ProcessTemplate('showCategories.tpl');

#Display template
echo "<!-- Displaying SHOWROOM Module -->\n";
$template = 'showCategories'.$this->GetPreference('current_showCategories_template');
if (isset($params['template']))
  {
    $template = 'showCategories'.$params['template'];
  }
echo $this->ProcessTemplateFromDatabase($template);
echo "<!-- END SHOWROOM Module -->\n";

?>