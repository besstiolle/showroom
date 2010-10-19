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



if(empty($params['idImg']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID vide', 'active_tab' => 'sites'));

if(!isset($params['state']))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'nouvel &eacute;tat non renseign&eacute;', 'active_tab' => 'sites'));

	
$query = 'SELECT url from '.cms_db_prefix().'module_showroom_room where id = ?';
$param = array($params['idImg']);	
$url = $db->getOne($query, $param);

if(empty($url))
	$this->Redirect($id, 'defaultadmin', $returnid, array('msgNOK' => 'ID inconnue', 'active_tab' => 'sites'));

$queryUpdate = 'UPDATE '.cms_db_prefix().'module_showroom_room set state=? WHERE id = ?';
$param = array($params['state'],$params['idImg']);
$result = $db->Execute($queryUpdate, $param);
if ($result === false){die("Database error durant l'update de la donn&eacute;e!");}
	

$retour .= "'".$url."' est mis &agrave; jour avec succ&egrave;s<br>\n";

$this->Redirect($id, 'defaultadmin', $returnid, array('msgOk'=> $retour,'msgNOK' => $alert, 'active_tab' => 'sites'));
?>