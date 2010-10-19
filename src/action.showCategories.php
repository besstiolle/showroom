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
	
//	$libelleCategorieSansAccent = strtr($categories[$idcategorie],'ΰαβγδηθικλμνξορςστυφωϊϋόύÿΐΑΒΓΔΗΘΙΚΛΜΝΞΟΡÒΣΤΥΦΩΪΫάέ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

	$libelleCategorieSansAccent = html_entity_decode($categories[$idcategorie]);
	$libelleCategorieSansAccent = strtr($libelleCategorieSansAccent,'ΰαβγδηθικλμνξορςστυφωϊϋόύÿΐΑΒΓΔΗΘΙΚΛΜΝΞΟΡÒΣΤΥΦΩΪΫάέ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	
	$myCategorie = new stdclass;
	$myCategorie->compteur = $compteur;
	$myCategorie->categorie = $categories[$idcategorie];
	$myCategorie->link =  $this->CreateLink($id, 'showByCategorie', $returnid, $categories[$idcategorie], array('categorie'=>$idcategorie),'',false,false,'',false,"showroom/$idcategorie/$returnid/Realisations Cms Made Simple/$libelleCategorieSansAccent");
	$listeCategorie[] = $myCategorie;
}

// Formulaire de renseignement de la clι
$smarty->assign('listeCategorie' , $listeCategorie);
$smarty->assign_by_ref('module',$this);

echo $this->ProcessTemplate('showCategories.tpl');



?>