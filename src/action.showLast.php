<?php


if (!isset($gCms)) exit;

$db = &$gCms->GetDb();

$query = 'SELECT url FROM '.cms_db_prefix().'module_showroom_room WHERE state = ? ORDER BY date_submit DESC limit 0,?';
$param = array("00", 16);	
$result = $db->execute($query, $param);

$listeImg = array();
while ($row = $result->FetchRow())
{
	$url = $row['url'];
	
	$img = new stdclass;
	$img->url = $url;
	$img->miniature = $this->_getUrlCapture($url,"120");
	$img->img = $this->_getUrlCapture($url,"1024");
	$img->md5 = md5($url);
	$listeImg[] = $img;
}

// Formulaire de renseignement de la cle
$smarty->assign('listeImg' , $listeImg);
$smarty->assign_by_ref('module',$this);

//echo $this->ProcessTemplate('showLast.tpl');

#Display template
echo "<!-- Displaying SHOWROOM Module -->\n";
$template = 'showLast'.$this->GetPreference('current_showLast_template');
if (isset($params['template']))
  {
    $template = 'showLast'.$params['template'];
  }
echo $this->ProcessTemplateFromDatabase($template);
echo "<!-- END SHOWROOM Module -->\n";


?>