{**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @version  Release: 0.4.4
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="contenttab">
		
<fieldset>
	<legend>
		<img src="{$img_dir|escape:'htmlall':'UTF-8'}/logonew.png" />
 	</legend>
				<!--<div id="downloadmanual-dhl">
					<a id="manual_download" href="{$ps_base_uri}modulo_dhl/manual/modulo_dhl_manual.pdf" target="_blank" >
						<img src="{$img_path|escape:'htmlall':'UTF-8'}ico_descargar.png" alt="{l s='Manual' mod='modulo_dhl'}" /> {l s='Manual' mod='modulo_dhl'}
					</a>
				</div>-->
	<div id="dhl_module">
                						                  
		<!--<form action="index.php?controller={$current_controller|escape:'htmlall':'UTF-8'}&token={$token|escape:'htmlall':'UTF-8'}{$ps14_tab|escape:'htmlall':'UTF-8'}" method="post" id="formfilter" name="formfilter">-->
        <form action="{$current}&submitDescargarAlbaran=1&token={$token}" method="post" id="formfilter" name="formfilter">
        	<input class="btn btn-primary" type="submit" name="submitDescargarAlbaran" id="submitDescargarAlbaran" value="{l s='Descargar' mod='esp_dhl'}"/>
		</form>
									
		<ul class="configuration_tabs">
						
		</ul>
	</div>
</fieldset>	
			