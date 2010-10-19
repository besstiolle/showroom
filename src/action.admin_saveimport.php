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


	//Si d�j� en base
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