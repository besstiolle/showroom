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

//Message de retour
if(isset($params['msgOk']))
	$smarty->assign('msgOk',$this->Lang($params['msgOk']));
if(isset($params['msgNOk']))
	$smarty->assign('msgNOk',$this->Lang($params['msgNOk']));	


//ajouter une categorie vide	
$arrayCategorie = array_merge(array("s&eacute;lectionnez la cat&eacute;gorie"=>"0"), $this->_getCategoriesForDropdown());

//On propose un captcha
if(isset($this->cms->modules['Captcha']))
{
	$captcha = &$this->getModuleInstance('Captcha'); 
	$captchafield = $captcha->getCaptcha();
} else
{
	$captchafield = "";
}
	
// Formulaire de renseignement de la cle
$smarty->assign('formStart' , $this->CreateFormStart($id, 'saveUrl', $returnid));
$smarty->assign('formInputTextUrl' ,$this->CreateInputText($id, 'url',(empty($params['url'])?'www.cmsmadesimple.fr':$params['url']), 42, '50'));
//$smarty->assign('formAreaText' ,$this->CreateTextArea($false, $id, $params['texte'], 'texte', '', '', '', '', $cols='80', $rows='15'));
$smarty->assign('captcha' ,$captchafield);
$smarty->assign('formInputCaptcha' ,$this->CreateInputText($id, 'captcha','', 20, '10'));
$smarty->assign('formDropDownCategorie' ,$this->CreateInputDropdown($id, 'categorie', $arrayCategorie, '' ,$params['categorie']));
$smarty->assign('submit' , $this->CreateInputSubmit($id, 'submit', $this->Lang('submit_url')));
$smarty->assign('formEnd' , $this->CreateFormEnd());
$smarty->assign_by_ref('module',$this);

//echo $this->ProcessTemplate('default.tpl');

#Display template
echo "<!-- Displaying SHOWROOM Module -->\n";
$template = 'form'.$this->GetPreference('current_form_template');
if (isset($params['template']))
  {
    $template = 'form'.$params['template'];
  }
echo $this->ProcessTemplateFromDatabase($template);
echo "<!-- END SHOWROOM Module -->\n";

?>