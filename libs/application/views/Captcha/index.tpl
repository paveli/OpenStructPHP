{* Smarty *}
{strip}

<!-- captcha -->

<div class="captcha" style="width: {$config->get('captcha', 'width')}px; height: {$config->get('captcha', 'height')}px;">
	<img src="/captcha/draw/">
	<div onclick="Captcha.Redraw(this, {$config->get('captcha', 'delay')});">
		<span>{$text->get('Redraw')}</span>
	</div>
</div>

<!-- Cache time: {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"} -->
<!-- Dynamic time: {dynamic}{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}{/dynamic} -->
<!-- /captcha -->

{/strip}