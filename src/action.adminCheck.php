
<?php
#-------------------------------------------------------------------------
# Module: OpenStatisticsCommunity - un client lege envoyant toute une serie de 
#         statistiques de maniere anonyme sur l'utilisation faites de 
#         Cms Made Simple. Pour toute information, consultez la page d'accueil 
#         du projet : http://www.cmsmadesimple.fr/statistiques
# Version: beta de Kevin Danezis Aka "Bess"
# Author can be join on the french forum : http://www.cmsmadesimple.fr/forum 
#        or by email : statistiques [plop] cmsmadesimple [plap] fr
# Method: action.save_pref
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://www.cmsmadesimple.fr/forum/viewtopic.php?id=2908
# The module's forge id : http://dev.cmsmadesimple.org/projects/osc
# The statistiques homepage is: http://www.cmsmadesimple.fr/statistiques
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


	//Si d&eacute;jà en base
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