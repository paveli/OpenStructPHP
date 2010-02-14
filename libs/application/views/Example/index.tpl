{* Smarty *}
{strip}

<!-- example/index -->

<div>
	<h1>{$text->dget('example', 'Example of Open_Struct')}</h1>
	<p>{$text->dget('example', 'You can see example of <b>Open_Benchmark::timetest()</b> method and example of <b>Open_Exception</b> throwing and handling at the top of the page.')}</p>
	<p>{$text->dget('example', 'See the source code file for this controller at <b>%s</b> for more complete understanding of things happened here.')|sprintf:"application/controllers/Example.php"}</p>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Controller"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Time got from the global variable to avoid multiple calls of <b>time()</b> function')}:&nbsp;{$globalTime|date_format:"%c"}</p>
		<p>{$text->dget('example', 'Value of argument <b>%s</b> passed to invoked controller\'s method')|sprintf:"page"}:&nbsp;{$argumentPage}</p>
		<p>{$text->dget('example', 'Value of argument <b>%s</b> passed to invoked controller\'s method')|sprintf:"span"}:&nbsp;{$argumentSpan}</p>
		<p>{$text->dget('example', 'Value of argument <b>%s</b> got via the controller\'s method <b>%s</b>')|sprintf:"page":"Open_Controller::getArgument()"}:&nbsp;{$argumentPageAnother}</p>
		<p>{$text->dget('example', 'Value of argument <b>%s</b> got via the controller\'s method <b>%s</b>')|sprintf:"span":"Open_Controller::getArgument()"}:&nbsp;{$argumentSpanAnother}</p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Config"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Value <b>%s</b> from config <b>%s</b> recieved from %s')|sprintf:"something":"example":"PHP"}:&nbsp;{$configSomething}</p>
		<p>{$text->dget('example', 'Value <b>%s</b> from config <b>%s</b> recieved from %s')|sprintf:"something":"example":"Smarty"}:&nbsp;{$config->get('example', 'something')}</p>
		<p>{$text->dget('example', 'Value set and then got from the config')}:&nbsp;{$configSetExample}</span>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Input"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Value <b>%s</b> got from <b>%s</b> data via <b>%s</b> method')|sprintf:"exampleGet":"GET":"Open_Input::get()"}:&nbsp;{$exampleGet}</p>
		<p>{$text->dget('example', 'Value <b>%s</b> got from <b>%s</b> data via <b>%s</b> method')|sprintf:"examplePost":"POST":"Open_Input::post()"}:&nbsp;{$examplePost}</p>
		<p>{$text->dget('example', 'Value <b>%s</b> got from <b>%s</b> data via <b>%s</b> method')|sprintf:"exampleCookie":"COOKIE":"Open_Input::cookie()"}:&nbsp;{$exampleCookie}</p>

		<p><b>{$text->dget('example', 'Client\'s IP')}</b>:&nbsp;{$ip}</p>
		<p><b>{$text->dget('example', 'Base URL')}</b>:&nbsp;{$base}</p>
		<p><b>{$text->dget('example', 'URL')}</b>:&nbsp;{$url}</p>
		<p><b>{$text->dget('example', 'URI')}</b>:&nbsp;{$uri}</p>
		<p><b>{$text->dget('example', 'Path')}</b>:&nbsp;{$path}</p>
		<p><b>{$text->dget('example', 'Path without locale')}</b>:&nbsp;{$path_no_locale}</p>
		<p><b>{$text->dget('example', 'Input locale')}</b>:&nbsp;{$input_locale}</p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Router"}</h3>
	<div style="padding-left: 20px;">
		<p><b>{$text->dget('example', 'Section #%d')|sprintf:0}</b>:&nbsp;{$section0}</p>
		<p><b>{$text->dget('example', 'Routed section #%d')|sprintf:0}</b>:&nbsp;{$sectionRouted0}</p>

		<p><b>{$text->dget('example', 'Work locale')}</b>:&nbsp;{$locale}</p>
		<p><b>{$text->dget('example', 'Controller')}</b>:&nbsp;{$controller}</p>
		<p><b>{$text->dget('example', 'Method')}</b>:&nbsp;{$method}</p>

		<p>{$text->dget('example', 'Link created from %s')|sprintf:"PHP"}:&nbsp;<a href="{$link}">{$link}</a></p>
		{assign var=link value=$router->link(true, 'home', true, true, false)}
		<p>{$text->dget('example', 'Link created from %s')|sprintf:"Smarty"}:&nbsp;<a href="{$link}">{$link}</a></p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Captcha"}</h3>
	<div style="padding-left: 20px;">
		{include file="Captcha/index"|cat:$smarty.const.TPLEXT}
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Model"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Data recieved from the DB with <b>%s</b>')|sprintf:"ExampleModel"}</p>
		{pagination pattern="first" link=$pagination.link amount=$pagination.amount span=$pagination.span current=$pagination.current}
		<table>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Date</th>
			</tr>
			{foreach from=$result item=row}
				<tr>
					<td>{$row->id}</td>
					<td>{$row->name}</td>
					<td>{$row->date|date_format:"%c"}</td>
				</tr>
			{/foreach}
		</table>
		<p><b>{$text->dget('example', 'Found rows')}</b>:&nbsp;{$foundRows}</p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Text"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Result of <b>%s</b> method called from %s')|sprintf:"Open_Text::get()":"PHP"}:&nbsp;{$textGet}</p>
		<p>{$text->dget('example', 'Result of <b>%s</b> method called from %s')|sprintf:"Open_Text::dget()":"PHP"}:&nbsp;{$textDget}</p>
		<p>{$text->dget('example', 'Result of <b>%s</b> method called from %s')|sprintf:"Open_Text::nget()":"PHP"}:&nbsp;{$textNget}</p>
		<p>{$text->dget('example', 'Result of <b>%s</b> method called from %s')|sprintf:"Open_Text::dnget()":"PHP"}:&nbsp;{$textDnget}</p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Session"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Number of visits stored in the session')}:&nbsp;{$sessionNumberOfVisits}</p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Cache"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Value stored in the cache')}:&nbsp;{$cachedValue}</p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Convert"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'String converted by base64 algorithm')}:&nbsp;<b>{$convertedToBase64}</b></p>
		<p>{$text->dget('example', 'Reverse converted string by base64 algorithm')}:&nbsp;<b>{$convertedFromBase64}</b></p>
		<p>{$text->dget('example', 'String converted from camel to undescore')}:&nbsp;<b>{$convertedCamelToUnderscore}</b></p>
		<p>{$text->dget('example', 'Reverse converted string from underscore to camel')}:&nbsp;<b>{$convertedUnderscoreToCamel}</b></p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Color"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Experimental color')}:&nbsp;<b>{$color}</b></p>
		<p>{$text->dget('example', 'Hexadecimal color value')}:&nbsp;<b>{$colorHex}</b></p>
		<p>{$text->dget('example', 'Inverted color')}:&nbsp;<b>{$invertedColorHex}</b></p>
		<p>{$text->dget('example', 'Grayscaled color')}:&nbsp;<b>{$grayscaledColorHex}</b></p>
		<p>{$text->dget('example', 'RGB array')}:&nbsp;<b>array({$rgbColor.0}, {$rgbColor.1}, {$rgbColor.2});</b></p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Security"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'String before XSS-clean')}:&nbsp;<b>{$stringBeforeXssClean|htmlentities}</b></p>
		<p>{$text->dget('example', 'String after XSS-clean')}:&nbsp;<b>{$stringAfterXssClean}</b></p>
		<p>{$text->dget('example', 'Easy encrypted string')}:&nbsp;<b>{$easyEncryptedString}</b></p>
		<p>{$text->dget('example', 'Easy decrypted string')}:&nbsp;<b>{$easyDecryptedString}</b></p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Acl"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Role <b>%s</b> is %s to perform action <b>%s</b> on <b>%s</b> resource')|sprintf:"ACL_ROLE_GUEST":$aclIsAllowed:"ACL_ACTION_SHOW":"ACL_RESOURCE_CAPTCHA"}</p>
		<p>{$text->dget('example', 'Role <b>%s</b> is %s to perform action <b>%s</b> on <b>%s</b> resource')|sprintf:"ACL_ROLE_GUEST":$aclIsDenied:"ACL_ACTION_SHOW":"ACL_RESOURCE_CAPTCHA"}</p>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Validation"}</h3>
	<div style="padding-left: 20px;">
		<p><b>{$text->dget('example', 'Validation result')}</b>:</p>
		<pre>{$validationResult}</pre>
	</div>

	<h3>{$text->dget('example', 'Work with %s')|sprintf:"Open_Benchmark"}</h3>
	<div style="padding-left: 20px;">
		<p>{$text->dget('example', 'Time taken to execute this method')}:&nbsp;<b>{dynamic}{$benchmarkExampleIndexElapsed|numberFormat:6}{/dynamic}</b>s</p>
	</div>
</div>

<!-- Cache time: {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"} -->
<!-- Dynamic time: {dynamic}{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}{/dynamic} -->
<!-- /example/index -->

{/strip}