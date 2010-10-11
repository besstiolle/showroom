{literal}
  <style type="text/css" media="screen">
	#last-title { text-align: left; }
	#last-title b { display: block; margin-right: 80px; }
	#last-title span { float: right; }
  </style>
  <script type="text/javascript" language="javascript">
  $(document).ready(function(){
 
  function formatTitle(title, currentArray, currentIndex, currentOpts) {
    return '<div id="last-title"><span><a href="javascript:;" onclick="$.fancybox.close();"><img src="./modules/ShowRoom/js/fancybox/fancy_close.png" /></a></span>' + (title && title.length ? '<b>' + title + '</b>' : '' ) + 'Image ' + (currentIndex + 1) + ' of ' + currentArray.length + '</div>';
	}

	$(".last").fancybox({
		'showCloseButton'	: false,
		'titlePosition' 	: 'inside',
		'titleFormat'		: formatTitle
	});
	
  });  
  </script>

{/literal}

<p></p>
<h2>{$module->Lang('title_last')}</h2>
{if count($listeImg) == 0}
	<p>Aucun site n'est encore enregistr&eacute;</p>
{else}
	{foreach from=$listeImg item=img}
		<a class="last" rel="last" title="{$img->url}" href="{$img->img}">
			<img alt='{$entry->url} est r&eacute;alis&eacute; avec Cms Made Simple' id='{$img->md5}' alt="last" src="{$img->miniature}">
		</a>
	{/foreach}
{/if}