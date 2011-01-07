<?php
#-------------------------------------------------------------------------
# Module: ShowRoom - Module proposant un systeme de showroom publique avec 
#			possibilite d'ajouter des urls de maniere annonyme pour le CMS
#           Cms Made Simple
#			
#  Attention : ce projet a ete cree specifiquement pour le site www.cmsmadesimple.fr 
#				Toute utilisation de votre part necessitera des modifications dans les fichiers !
#
# Version: de Kevin Danezis Aka "Bess"
# Author can be join on the french forum : http://www.cmsmadesimple.fr/forum 
#        or by email : contact [plop] furie [plap] be
#
# The discussion page around the module : http://www.cmsmadesimple.fr/forum/viewtopic.php?id=2958
# The author's git page is : http://github.com/besstiolle
# The module's git page is : http://github.com/besstiolle/showroom
# The module's demo page is : http://www.cmsmadesimple.fr/showroom
#
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
#-------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#-------------------------------------------------------------------------

if (!isset($gCms)) exit;


// Verification de la permission
if (! $this->CheckPermission('Set ShowRoom Prefs')) {
  return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
}

(empty($params['active_tab'])?$tab = '':$tab = $params['active_tab']);
(empty($params['categorieSites'])?$filtreCategorie = null:$filtreCategorie = $params['categorieSites']);

$smarty->assign('msgOk',(isset($params['msgOk'])?$params['msgOk']:null));
$smarty->assign('msgNOK',(isset($params['msgNOK'])?$params['msgNOK']:null));


$arrayCategorie = $this->_getCategoriesForDropdown();
$categories = $this->_getCategories();
$status = $this->_getStates();

//On ajoute l'onglet Contenu + Autres
$tab_header = $this->StartTabHeaders();
$tab_header.= $this->SetTabHeader('sites',$this->Lang('title_sites'),('sites' == $tab)?true:false);
$tab_header.= $this->SetTabHeader('import',$this->Lang('title_import'),('import' == $tab)?true:false);
$tab_header.= $this->SetTabHeader('check',$this->Lang('title_check'),('check' == $tab)?true:false);
$tab_header.= $this->SetTabHeader('templateform',$this->Lang('title_template_form'));//,('templateform' == $tab)?true:false);
$tab_header.= $this->SetTabHeader('templateshowbycategorie',$this->Lang('title_template_showByCategorie'));//,('templateshowbycategorie' == $tab)?true:false);
$tab_header.= $this->SetTabHeader('templateshowcategories',$this->Lang('title_template_showCategories'));//,('templateshowcategories' == $tab)?true:false);
$tab_header.= $this->SetTabHeader('templateshowlast',$this->Lang('title_template_showLast'));//,('templateshowlast' == $tab)?true:false);

$tab_header.= $this->EndTabHeaders();

$smarty->assign('tabs_start',$tab_header.$this->StartTabContent());
$smarty->assign('tab_end',$this->EndTab());

//Contenu de l'onglet contenu
$smarty->assign('sitesTpl',$this->StartTab('sites', $params));
$smarty->assign('tpl_form',$this->StartTab('templateform', $params));
$smarty->assign('tpl_showByCategorie',$this->StartTab('templateshowbycategorie', $params));
$smarty->assign('tpl_showCategories',$this->StartTab('templateshowcategories', $params));
$smarty->assign('tpl_showLast',$this->StartTab('templateshowlast', $params));
$smarty->assign('importTpl',$this->StartTab('import', $params));
$smarty->assign('checkTpl',$this->StartTab('check', $params));
$smarty->assign('tabs_end',$this->EndTabContent());

// pass a reference to the module, so smarty has access to module methods
$smarty->assign_by_ref('module',$this);

 /***************************************/
 // Liste des Gabarits
   /*
   * Part of the multiple database template functionality
   * this function provides an interface for adding, editing,
   * deleting and marking active all templates that match
   * a prefix.
   *
   * @param id = module id (pass in the value from doaction)
   * @param returnid = destination page id
   * @param prefix = the template prefix
   * @param defaulttemplatepref = The name of the preference containing the system default template
   * @param active_tab = The tab to return to
   * @param defaultprefname = The name of the preference that contains the name of the current default template
   * @param title = Title text to display in the add/edit template form
   * @param inf = Information text to display in the add/edit template form
   * @param destaction = The action to return to.
   */
 $smarty->assign('listeGabaritForm',$this->ShowTemplateList($id,$returnid,'form',
										 'default_form_template_contents',
										 'templateform',
										 'current_form_template',
										 $this->Lang('addedit_template_form')));
 $smarty->assign('listeGabaritShowByCategorie',$this->ShowTemplateList($id,$returnid,'showByCategorie',
										 'default_showByCategorie_template_contents',
										 'templateshowbycategorie',
										 'current_showByCategorie_template',
										 $this->Lang('addedit_template_showByCategorie')));
 $smarty->assign('listeGabaritShowCategories',$this->ShowTemplateList($id,$returnid,'showCategories',
										 'default_showCategories_template_contents',
										 'templateshowcategories',
										 'current_showCategories_template',
										 $this->Lang('addedit_template_showCategories')));
 $smarty->assign('listeGabaritShowLast',$this->ShowTemplateList($id,$returnid,'showLast',
										 'default_showLast_template_contents',
										 'templateshowlast',
										 'current_showLast_template',
										 $this->Lang('addedit_template_showLast')));

 /***************************************/
 // Statut du compte Shotbot
 $arrayStatut = substr($this->_fopen($this->_getUrlStatus()),3);
 $arrayStatut = explode(':',$arrayStatut);
 $statut = new stdclass;
 $statut->used = $arrayStatut[1];
 $statut->total = $arrayStatut[0];
 $smarty->assign('statut_shotbot',$statut);

 /*************************************/
 // Recuperation des sites par default les 20 derniers
 
$smarty->assign('startFormFiltre' ,$this->CreateFormStart($id, 'defaultadmin', $returnid));
$smarty->assign('endFormFiltre' , $this->CreateFormEnd());
$smarty->assign('formDropDownCategorieSites' ,$this->CreateInputDropdown($id, 'categorieSites', $arrayCategorie,-1,$filtreCategorie));
$smarty->assign('submitFiltre' ,$this->CreateInputSubmit($id, 'filtresubmit', $value='filtrer'));
$smarty->assign('resetFiltre' ,$this->CreateLink($id, 'defaultadmin', $returnid, 'tout afficher', array()));

if($filtreCategorie == null)
{
	$query = 'SELECT id, url, id_category, state FROM '.cms_db_prefix().'module_showroom_room ORDER BY date_submit DESC limit 0,?';
	$param = array(20);	
} else
{
	$query = 'SELECT id, url, id_category, state FROM '.cms_db_prefix().'module_showroom_room where id_category = ? ORDER BY date_submit DESC limit 0,?';
	$param = array($filtreCategorie, 20);	
}
$result = $db->execute($query, $param);

$listeImg = array();
$i = 0;
while ($row = $result->FetchRow())
{
	$url = $row['url'];
	$myid =  $row['id'];
	$idcategory = $row['id_category'];
	$state = $row['state'];
	
	$img = new stdclass;
	$img->id = $myid;
	$img->url = $url;
	$img->categorie = (isset($categories[$idcategory])?$categories[$idcategory]:"XXXXXXXX");
	$img->state = (isset($status[$state])?$status[$state]:"XXX : $state");
	
	$img->miniature = $this->_getUrlCapture($url,"120");
	$img->img = $this->_getUrlCapture($url,"1024");
	$img->linkDelete = $this->CreateLink($id, 'adminDelete', $returnid, 'supprimer', array('idImg' => $myid ), 'Etes vous certain de vouloir supprimer?');
	$img->linkRefresh = $this->CreateLink($id, 'adminRefresh', $returnid, 'refresh miniature', array('idImg' => $myid ));
	$img->linkRetest = $this->CreateLink($id, 'adminRetest', $returnid, 'retester le site', array('idImg' => $myid ));
	$img->linkEdition = $this->CreateLink($id, 'adminEdition', $returnid, 'modifier', array('idImg' => $myid ));
	if($state != 0)
		$img->linkStatNew = $this->CreateLink($id, 'adminChangeState', $returnid, 'D&eacute;finir &agrave; "nouveau"', array('idImg' => $myid,'state' => '0'));
	if($state != 1)
		$img->linkStatOk = $this->CreateLink($id, 'adminChangeState', $returnid, 'Valider le site', array('idImg' => $myid,'state' => '1'));
	if($state != 2)
		$img->linkStatKo = $this->CreateLink($id, 'adminChangeState', $returnid, 'D&eacute;sactiver le site', array('idImg' => $myid,'state' => '2'));
	$img->rowclass = "row".(($i++%2)+1);
	$listeImg[] = $img;
}

// Formulaire de renseignement de la cle
$smarty->assign('listeImg' , $listeImg);
$smarty->assign_by_ref('module',$this);

 /*************************************/
 // formulaire d'insertion de masse
$smarty->assign('startFormImport',$this->CreateFormStart($id, 'admin_saveimport', $returnid));
$smarty->assign('formDropDownCategorieImport' ,$this->CreateInputDropdown($id, 'categorie', $arrayCategorie,0));
$smarty->assign('areaImport',$this->CreateTextArea(false,$id,'','area'));
$smarty->assign('submitImport',$this->CreateInputSubmit($id, 'areasubmit', 'enregistrer'));
$smarty->assign('endFormImport',$this->CreateFormEnd());

 /*************************************/
 // formulaire de check en masse
$smarty->assign('startFormCheck',$this->CreateFormStart($id, 'adminCheck', $returnid));
$smarty->assign('areaCheck',$this->CreateTextArea(false,$id,'','area'));
$smarty->assign('submitCheck',$this->CreateInputSubmit($id, 'checksubmit', 'valider les sites'));
$smarty->assign('endFormCheck',$this->CreateFormEnd());

echo $this->ProcessTemplate('defaultadmin.tpl');
?>