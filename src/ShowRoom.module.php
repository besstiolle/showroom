<?php
#-------------------------------------------------------------------------
# Module: Shotbot - API permettant de generer des miniatures de sites internet en divers format
#				Url du projet source : http://www.shotbot.fr
# Version: de Kevin Danezis Aka "Bess"
# Author can be join on the french forum : http://www.cmsmadesimple.fr/forum 
#        or by email : contact [plop] furie [plap] be
# Method: Shotbot.module.class
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://www.cmsmadesimple.fr/forum/viewtopic.php?id=2908
# The module's forge id : http://dev.cmsmadesimple.org/projects/shotbot
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
 
class ShowRoom extends CMSModule
{
  function GetName()
  {
    return get_class($this);
  }
  
  function GetFriendlyName()
  {
    return $this->Lang('friendlyname');
  }

  function GetVersion()
  {
    return '0.0.1';
  }
  
  function GetHelp()
  {
    return $this->Lang('help');
  }
  
  function GetAuthor()
  {
    return 'Kevin Danezis (Bess)';
  }

  function GetAuthorEmail()
  {
    return 'contact@furie.be';
  }
  
  function GetChangeLog()
  {
    return $this->Lang('changelog');
  }
  
  function IsPluginModule()
  {
    return true;
  }

  function HasAdmin()
  {
    return true;
  }

  function GetAdminSection()
  {
    return 'content';
  }

  function GetAdminDescription()
  {
    return $this->Lang('moddescription');
  }

  function VisibleToAdminUser()
  {
    return true;
  }
  
  function GetDependencies()
  {
    return array('Shotbot'=>'0.0.1');
  }

  function MinimumCMSVersion()
  {
    return "1.7.0";
  }
  
  function MaximumCMSVersion()
  {
    return "1.9";
  }
  
  function SetParameters()
  {
    //utilisation en {Showroom}
	$this->RegisterModulePlugin();
	
	//PrettyUrl
	$this->RegisterRoute('/showroom\/(?P<categorie>[0-9]+)\/(?P<returnid>[0-9]+)\/(?P<none2>[a-zA-Z_ ,]+)\/(?P<none>[a-zA-Z_ ,]+)$/',
		 array('action'=>'showByCategorie'));
	
	//Securite
	$this->RestrictUnknownParams();
	
	$this->CreateParameter('categorie', null, 'todo');
	$this->SetParameterType('categorie',CLEAN_INT);
	
	$this->CreateParameter('idImg', null, 'todo');
	$this->SetParameterType('idImg',CLEAN_INT);
	
	$this->CreateParameter('state', null, 'todo');
	$this->SetParameterType('state',CLEAN_INT);
	
	$this->CreateParameter('texte', null, 'todo');
	$this->SetParameterType('texte',CLEAN_STRING);
	
	$this->CreateParameter('url', null, 'todo');
	$this->SetParameterType('url',CLEAN_STRING);
	
	$this->CreateParameter('captcha', null, 'todo');
	$this->SetParameterType('captcha',CLEAN_STRING);
	
	$this->CreateParameter('submit', null, 'todo');
	$this->SetParameterType('submit',CLEAN_STRING);
	
	$this->CreateParameter('msgNOk', null, 'todo');
	$this->SetParameterType('msgNOk',CLEAN_STRING);
	
	$this->CreateParameter('msgOk', null, 'todo');
	$this->SetParameterType('msgOk',CLEAN_STRING);
	
	$this->CreateParameter('area', null, 'todo');
	$this->SetParameterType('area',CLEAN_STRING);
	
	//depotoire
	$this->CreateParameter('none', null, 'todo');
	$this->SetParameterType('none',CLEAN_STRING);
	$this->CreateParameter('none2', null, 'todo');
	$this->SetParameterType('none2',CLEAN_STRING);
	
	
  }
  
  function HandlesEvents()
  {
		return true;
  }
  
  function DoEvent($originator, $eventname, &$params)
	{
		$content = $params["content"];
				
		$script = '
		           <script language="javascript" type="text/javascript" src="./modules/ShowRoom/js/jquery.js"></script>
				   <script language="javascript" type="text/javascript" src="./modules/ShowRoom/js/fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
				   <script language="javascript" type="text/javascript" src="./modules/ShowRoom/js/fancybox/jquery.fancybox-1.3.1.js"></script>
                   <link rel="stylesheet" type="text/css" href="./modules/ShowRoom/js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
				   ';		
				
		if (function_exists('str_ireplace'))
			$params["content"] = str_ireplace('<head>', "<head>\n$script\n", $content);
		else
			$params["content"] = eregi_replace('<head>', "<head>\n$script\n", $content);
	}
  
  function InstallPostMessage()
  {
    return $this->Lang('postinstall',$this->GetVersion());
  }

  function UninstallPostMessage()
  {
    return $this->Lang('postuninstall');
  }
  
  function UninstallPreMessage()
  {
    return $this->Lang('really_uninstall');
  }

  function _shotbotApi()
  {
	global $gCms;
	$shotbotApi =& $gCms->modules["Shotbot"]['object'];
	if($shotbotApi == null)
	{
		echo "Shotbot module not found !";
		exit;
	}
	return $shotbotApi;
  }
  
  function _newUrlCapture($url)
  {
	return $this->_shotbotApi()->newUrlCapture($url);
  }
  
  function _getUrlCapture($url,$format = "120")
  {
	return $this->_shotbotApi()->getUrlCapture($url,$format);
  }
  
  function _updateUrlCapture($url)
  {
	return $this->_shotbotApi()->updateUrlCapture($url);
  }
  
  function _getUrlStatus()
  {
	return $this->_shotbotApi()->getUrlStatus();
  }
  
  function _getCategoriesForDropdown()
  {	
	return array_flip($this->_getCategories());
  }	
	
  function _getCategories()
  {	
	return array("1"=>"Administrations publiques, collectivit&eacute;s locales",
				"2"=>"Arts et culture",
				"3"=>"Associations, clubs, organisation caritative",
				"4"=>"Blogs et sites personnels",
				"5"=>"Entreprises, artisans, commer&ccedil;ant",
				"6"=>"&Eacute;v&egrave;nementiel",
				"7"=>"H&ocirc;tels, restaurants, gastronomie",
				"8"=>"Loisir/Tourisme",
				"9"=>"Open-source et Culture Libre",
				"10"=>"Politique",
				"11"=>"Presse, m&eacute;dias, tv, journaux",
				"12"=>"Sant&eacute;, beaut&eacute;, bien &ecirc;tre",
				"13"=>"Sports &amp; M&eacute;caniques");
	}
	/*
	echo "test de l'URL : ".$params['url']."<br/>";

$content = file_get_contents($params['url']);

if (preg_match('/<meta name="Generator" content="CMS Made Simple - Copyright/i', $content  )
    && preg_match('/Ted Kulp/i', $content  )) {
    echo "meta g&eacute;n&eacute;rator valide";
} else {
    echo "meta g&eacute;n&eacute;rator absent !";
    return ;
} 

$content = file_get_contents("http://validator.w3.org/check?uri=".$params['url']);

if (preg_match('/class="valid"/i', $content  )) {
    echo "HTML Valide";
} else if (preg_match('/class="invalid"/i', $content  )) {
    echo "HTML Invalide";
} else {
    echo "HTML ERREUR";
}

$content = file_get_contents("http://jigsaw.w3.org/css-validator/validator?uri=".$params['url']);

if (preg_match("/<div id='congrats'>/i", $content  )) {
    echo "CSS Valide";
} else if (preg_match("/<div id='errors'>/i", $content  )) {
    echo "CSS Invalide";
} else {
    echo "CSS ERREUR";
}

echo "<hr/>";



$content = file_get_contents("http://add.shotbot.net/k=344c596a55fb/".$params['url']);
echo "$content";

	*/
  
  
  
   function _getStates()
   {
	return array("0"=>"new",
					"1"=>"valid&eacute;",
					"2"=>"inactiv&eacute;");
  }
  
  function _getStatesError()
  {
	$array = $this->getState();
	unset($array["02"]);
	unset($array["04"]);
	unset($array["05"]);
	unset($array["07"]);
	unset($array["08"]);
	return $array;
  }
  
  function _getStatesProduction()
  {
	$array = $this->getState();
	return array($array["07"]);
  }
  
  function _isRegexUrl($url)
  {
	$regxUrl = "#(((https?|ftp)://(w{3}\.)?)(?<!www)(\w+-?)*\.([a-z]{2,4}))#";
	return preg_match($regxUrl, $url);
  }
  
  function _isOpenUrl($url)
  {
	$res = $this->_fopen($url);
	return ("KO" != $res);
  }
  
  function _isCmsMSsite($url)
  {
	$content = $this->_fopen($url.'/admin/login.php');
	return preg_match('/CMS Made Simple/i', $content); 
  }
  
  function _isLicenceCmsMSsite($url, $testLight = false)
  {
	$content = $this->_fopen($url);
	return preg_match('/<meta name="Generator" content="CMS Made Simple/i', $content); 
  }
    
  function _fopen($url)
  {
	$response = "";
	$handler = @fopen ($url, "r");
	if (!$handler) {return "KO";}
	while (!feof ($handler)) {$response .= fgets ($handler, 1024);}
	fclose($handler);
	return $response;
  }
  
  function _dbToDate($stringDate)
	{
		return mktime(substr($stringDate, 11,2),
					substr($stringDate, 14,2),
					substr($stringDate, 17,2),
					substr($stringDate, 5,2),
					substr($stringDate, 8,2),
					substr($stringDate, 0,4));
	}
  
	function _getTimeForDB($db)
	{
		return trim($db->DBTimeStamp(time()), "'");
	}
}
?>