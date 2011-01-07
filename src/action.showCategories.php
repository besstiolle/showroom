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