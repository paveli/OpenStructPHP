{* Smarty *}
{strip}

<!-- body -->

{* Здесь необходимо сделать разметку страницы *}
<div id="doc3">
	<div id="hd">
		{include file="header`$smarty.const.TPLEXT`"}
	</div>

	<div id="bd">
		{* Переменная $body устанавливается при вызове метода show класс Open_View *}
		{include file=$body}
	</div>

	<div id="ft">
		{include file="footer`$smarty.const.TPLEXT`"}
	</div>
</div>

<!-- /body -->

{/strip}