<?php
#-------------------------------------------------------------------------
# Module: OpenStatisticsCommunity - un client lege envoyant toute une serie de 
#         statistiques de maniere anonyme sur l'utilisation faites de 
#         Cms Made Simple. Pour toute information, consultez la page d'accueil 
#         du projet : http://www.cmsmadesimple.fr/statistiques
# Version: beta de Kevin Danezis Aka "Bess"
# Author can be join on the french forum : http://www.cmsmadesimple.fr/forum 
#        or by email : statistiques [plop] cmsmadesimple [plap] fr
# Method: action.defaultadmin.class
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


// Verification de la permission
if (! $this->CheckPermission('Set Open Statistics Community Prefs')) {
  return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
}

$tab = '';
if (FALSE == empty($params['active_tab'])) $tab = $params['active_tab'];

$key = $this->getKey();
$config =& $gCms->GetConfig();
$url = $config['root_url'];
if(strpos($url, 'localhost') !== FALSE)
{
	$url = 'http://www.cmsmadesimple.fr';
}


// Formulaire de renseignement de la cl�
$smarty->assign('formKeyStart' , $this->CreateFormStart($id, 'adminSavePrefs', $returnid));
$smarty->assign('formKeyInputText' ,$this->CreateInputText($id, 'key', $key, '', 15));
$smarty->assign('submitKey' , $this->CreateInputSubmit($id, 'submitKey', $this->Lang('submit_config')));
$smarty->assign('formKeyEnd' , $this->CreateFormEnd());

// Formulaire de test de la cl�
$smarty->assign('formUrlStart' , $this->CreateFormStart($id, 'adminTestKey', $returnid));
$smarty->assign('formUrlInputText' ,$this->CreateInputText($id, 'url', $url, 30, 150));
$smarty->assign('formUrlInputHidden' ,$this->CreateInputHidden($id, 'create', '1'));
$smarty->assign('submitUrl' , $this->CreateInputSubmit($id, 'submitUrl', $this->Lang('submit_test')));
$smarty->assign('formUrlEnd' , $this->CreateFormEnd());

// pass a reference to the module, so smarty has access to module methods
$smarty->assign_by_ref('mod',$this);
$smarty->assign('key' , $key);

echo $this->ProcessTemplate('admindefault.tpl');
?>