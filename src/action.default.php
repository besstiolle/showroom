<?php
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