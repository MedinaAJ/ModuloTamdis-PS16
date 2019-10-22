{*
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
 {if $montag}
    <br />
    <div class="panel">
        <fieldset id="detalletamdis">
            <div class="panel-heading">
                <img src="{$path}logo.png" alt="{l s='MPropio tamdis B2C' mod='modulo_tamdis'}" title="{l s='MPropio tamdis B2C' mod='modulo_tamdis'}" />
                {l s=' M-Propio TAMDIS:' mod='modulo_tamdis'}
            </div>
          <form id="form_tamdis" method="POST" action="{$request_uri}">
               <input type="hidden" name="module_dir" id="module_dir" value="{$path}" />
                <input type="hidden" name="uri" id="uri" value="{$request_uri}" />
                {if isset($module_instance->name)}
					<input type="hidden" name="module_non_ssl_href" id="module_non_ssl_href" value="{$module_instance->name}"/>
				{else}
					<input type="hidden" name="module_non_ssl_href" id="module_non_ssl_href" value=""/>
				{/if}
                <div class="form-group">
                    <div class="col-lg-12">
                   	  <p><label>{l s='tamdis platform pick-up place' mod='modulo_tamdis'}</label>
                      <select name="platform" id="platform" >
                      		<option {if $order_data.platform_tamdis==0}selected="selected"{/if} value="0">-- Seleccione pto recogida --</option>
                       	    <option {if $order_data.platform_tamdis==1}selected="selected"{/if} value="1">Tamdis Zaragoza</option>
                            <option {if $order_data.platform_tamdis==3}selected="selected"{/if} value="3">Tamdis Madrid</option>
                            <option {if $order_data.platform_tamdis==4}selected="selected"{/if} value="4">Tamdis Sevilla</option>
                            <option {if $order_data.platform_tamdis==5}selected="selected"{/if} value="5">Tamdis Asturias</option>
                            <option {if $order_data.platform_tamdis==8}selected="selected"{/if} value="8">Tamdis Valencia</option>
                            <option {if $order_data.platform_tamdis==9}selected="selected"{/if} value="9">Tamdis Barcelona</option>
                            <option {if $order_data.platform_tamdis==10}selected="selected"{/if} value="10">Tamdis Valladolid</option>
                            <option {if $order_data.platform_tamdis==23}selected="selected"{/if} value="23">Tamdis Badajoz</option>
                            <option {if $order_data.platform_tamdis==30}selected="selected"{/if} value="30">Tamdis Málaga</option>
                            <option {if $order_data.platform_tamdis==32}selected="selected"{/if} value="32">Tamdis León</option>
                            <option {if $order_data.platform_tamdis==36}selected="selected"{/if} value="36">Tamdis Vitoria</option>
                            <option {if $order_data.platform_tamdis==39}selected="selected"{/if} value="39">Tamdis La Coruña</option>
                            <option {if $order_data.platform_tamdis==40}selected="selected"{/if} value="40">Tamdis Salamanca</option>
                            <option {if $order_data.platform_tamdis==50}selected="selected"{/if} value="50">Tamdis Yecla</option>
                      </select></p>
                      <div>
                         <!--<div style="float:left;"><p id="nBultostamdis"><label>{l s='name pick-up place:' mod='modulo_tamdis'}</label><input type="text" name="namepickup" value="{$order_data.name_p}" /></p></div>-->
                         <div style="float:left;"><p id="nBultostamdis"><label>{l s='name pick-up place:' mod='modulo_tamdis'}</label><input type="text" name="namepickup" value="VENTADECOLCHONES.COM" /></p></div>
                         <!--<div style="float:left;"><p id="pBultostamdis"><label>{l s='address pick-up:' mod='modulo_tamdis'}</label><input type="text" name="addresspickup" value="{$order_data.address_p}" /></p></div>-->
                         <div style="float:left;"><p id="pBultostamdis"><label>{l s='address pick-up:' mod='modulo_tamdis'}</label><input type="text" name="addresspickup" value="Ctra. CM-412, Km. 41" /></p></div>
                         <!--<div style="float:left;"><p id="pBultostamdis"><label>{l s='city pick-up:' mod='modulo_tamdis'}</label><input type="text" name="citypickup" value="{$order_data.city_p}" /></p></div>-->
                         <div style="float:left;"><p id="pBultostamdis"><label>{l s='city pick-up:' mod='modulo_tamdis'}</label><input type="text" name="citypickup" value="Pozuelo de Calatrava" /></p></div>
                         <!--<p id="pBultostamdis"><label>{l s='code postal pick-up:' mod='modulo_tamdis'}</label><input type="text" name="cppickup" value="{$order_data.postel_p}" /></p>-->
                         <p id="pBultostamdis"><label>{l s='code postal pick-up:' mod='modulo_tamdis'}</label><input type="text" name="cppickup" value="13179" /></p>
                         <!--<div style="float:left;"><p id="pBultostamdis"><label>{l s='state pick-up:' mod='modulo_tamdis'}</label><input type="text" name="statepickup" value="{$order_data.state_p}" /></p></div>-->
                         <div style="float:left;"><p id="pBultostamdis"><label>{l s='state pick-up:' mod='modulo_tamdis'}</label><input type="text" name="statepickup" value="Ciudad Real" /></p></div>
                         <!--<div style="float:left;"><p id="pBultostamdis"><label>{l s='phone1 pick-up:' mod='modulo_tamdis'}</label><input type="text" name="phone1pickup" value="{$order_data.phone1_p}" /></p></div>-->
                         <div style="float:left;"><p id="pBultostamdis"><label>{l s='phone1 pick-up:' mod='modulo_tamdis'}</label><input type="text" name="phone1pickup" value="926840083" /></p></div>
                         <!--<div style="float:left;"><p id="pBultostamdis"><label>{l s='phone2 pick-up:' mod='modulo_tamdis'}</label><input type="text" name="phone2pickup" value="{$order_data.phone2_p}" /></p></div>-->
                         <div style="float:left;"><p id="pBultostamdis"><label>{l s='phone2 pick-up:' mod='modulo_tamdis'}</label><input type="text" name="phone2pickup" value="926840405" /></p></div>
                         <!--<p id="pBultostamdis"><label>{l s='mail pick-up:' mod='modulo_tamdis'}</label><input type="text" name="mailpickup" value="{$order_data.mail_p}" /></p>-->
                         <p id="pBultostamdis"><label>{l s='mail pick-up:' mod='modulo_tamdis'}</label><input type="text" name="mailpickup" value="pedro@ventadecolchones.com" /></p>
                         <div style="float:left;"><p id="pBultostamdis"><label>{l s='weight:' mod='modulo_tamdis'}</label><input type="text" name="weighttam" value="{$order_data.weight}" /></p></div>
                         <p id="pBultostamdis"><label>{l s='n packages:' mod='modulo_tamdis'}</label><input type="text" name="npackagestam" value="{$order_data.n_packages}" /></p>
                         <input type="hidden" name="tipoentreg" id="tipoentreg" value="1" />
                         <input type="hidden" name="entregECI" id="entregECI" value="2" />
                         <input type="hidden" name="adelentrega" id="adelentrega" value="2" />
                         <input type="hidden" name="coordtienda" id="coordtienda" value="2" />
                         <input type="hidden" name="opespecial" id="opespecial" value="2" />
                         <input type="hidden" name="grua" id="grua" value="2" />
                         <input type="hidden" name="desmontajesofa" id="desmontajesofa" value="2" />
                         <input type="hidden" name="repara" id="repara" value="2" />
                         <input type="hidden" name="retirada" id="retirada" value="2" />
                         <input type="hidden" name="ctrlcalid" id="ctrlcalid" value="2" />
                         <input type="hidden" name="aparcamient" id="aparcamient" value="2" />
                         <input type="hidden" name="recepci" id="recepci" value="1" />
                         <input type="hidden" name="retiradamueble" id="retiradamueble" value="3" />
                         <input type="hidden" name="ascensor" id="ascensor" value="2" />
                      </div>   
              
                    </div>
                </div>
               <!-- <div class="row">-->
                    <div class="col-lg-2">   
                        <button type="submit" id="submitBultos" name="submitBultos" class="buttonguardartamdis">
                            <i class="icon-save"></i>
                            {l s='Save' mod='modulo_tamdis'}
                        </button>
                    </div>
                    <div class="col-lg-2">    
                	<br/>
                        <a class="buttongenerartamdis" href="{$request_uri}&Labeltamdis=1" target="_self">{l s='Generate shipping' mod='modulo_tamdis'}</a>
                		
                   </div> 
                   <div class="col-lg-2">
               		<br/><br/>
               		</div>
               <div>
               	<br/><br/>
               </div>
                   
               <!-- </div>    -->
            </form>
        </fieldset> 
    </div> 
 {/if}   