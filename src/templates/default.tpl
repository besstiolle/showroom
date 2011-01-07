<p></p>
{if $msgOk != null}<div style='width:50%;background:#080;color:#000;text-align:center;margin:auto;border-radius:5px;'>{$msgOk}</div>{/if}
{if $msgNOk != null}<div style='width:50%;background:#faa;color:#000;text-align:center;margin:auto;border-radius:5px;'>{$msgNOk}</div>{/if}

<fieldset><legend>{$module->Lang('title_form')}</legend>
{$formStart}

	{$module->Lang('text_form')}
	<p>http://{$formInputTextUrl}</p>
	<p>{$formDropDownCategorie}</p>
	{if $captcha != null}
	<p>{$captcha}</p>
	<p>Retapez la chaine captcha : {$formInputCaptcha}</p>
	{/if}
	{*<p>{$formAreaText}</p>*}
	<p>{$submit}</p>

{$formEnd}
</fieldset>

