
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



if(empty($params['idImg']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID vide', 'active_tab' => 'sites'));

	
$query = 'SELECT url from '.cms_db_prefix().'module_showroom_room where id = ?';
$param = array($params['idImg']);	
$url = $db->getOne($query, $param);

if(empty($url))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID inconnue', 'active_tab' => 'sites'));

$alert = '';
	
//Si pas un regex valide
if(empty($alert) && !$this->_isRegexUrl($url))	
	$alert .= "'".$url."' n'est pas une url valide<br>\n";

//Si pas un site visible
if(empty($alert) && !$this->_isOpenUrl($url))	
	$alert .= "'".$url."' n'est pas une url accessible<br>\n";

//Si ce n'est pas une installation CMSMS
//if(empty($alert) && !$this->_isCmsMSsite($url))	
//	$alert .= "'".$url."' n'est pas une installation sous CMSMS<br>\n";
	
//Si pas un CMSMS
if(empty($alert) && !$this->_isLicenceCmsMSsite($url))	
	$alert .= "'".$url."' est une installation pirate<br/>\n";

if(empty($alert))
{
	$retour .= "'".$url."' est retest&eacute; avec succ&egrave;s<br>\n";
} else
{
	$queryUpdate = 'UPDATE '.cms_db_prefix().'module_showroom_room set state=? WHERE id = ?';
	$param = array(2,$params['idImg']);
	$result = $db->Execute($queryUpdate, $param);
	if ($result === false){die("Database error durant l'update de la donn&eacute;e!");}
	$alert .= "'".$url."' a &eacute;t&eacute; d&eacute;sactiv&eacute; automatiquement<br>\n";
}

$this->Redirect($id, 'defaultadmin', $returnid, array('msgOk'=> $retour,'msgNOK' => $alert, 'active_tab' => 'sites'));
?>