{include file='header.tpl'}

{if isset($org)}
    <div class='page-header'><h1>
    {if $org->getName() != ''}
        {$org->getName()}
    {else}
        {Localisation::getTranslation('common_organisation_profile')}
    {/if}
    <small>{Localisation::getTranslation('org_private_profile_alter_profile_here')}</small>
    {assign var="org_id" value=$org->getId()}
        <a href="{urlFor name="org-public-profile" options="org_id.$org_id"}" class="pull-right btn btn-primary">
            <i class="icon-list icon-white"></i> {Localisation::getTranslation('org_private_profile_public_profile')}
            </a>
        </h1>
    </div>
{else}
    header({urlFor name='home'});
{/if}

{include file="handle-flash-messages.tpl"}

{if isset($errorOccured)}
    <tr>
        <td colspan="2">
            <div class="alert alert-error">
                <h3>{Localisation::getTranslation('common_please_correct_errors')}</h3>
                <ol>
                {foreach from=$errorList item=error}
                    <li>{$error}</li>
                {/foreach}
                </ol>
            </div> 
        </td>
    </tr>
{/if}
{assign var="org_id" value=$org->getId()}
    <form method='post' action='{urlFor name='org-private-profile' options="org_id.$org_id"}' class='well' accept-charset="utf-8">
        <table>
            <tr valign="top" align="center">
                <td colspan="2" style="font-weight: bold; text-align: center; padding-bottom: 10px">
                    {Localisation::getTranslation('org_private_profile_organisation_visible_by_all')}...
                    <hr/>
                </td>

                <td width="50%">
                    <label for='orgName'><strong>{Localisation::getTranslation('common_organisation_name')} <span style="color: red">*</span></strong></label>
                    <input type='text' name='orgName' id='orgName' style="width: 80%"
                    {if $org->getName() != ''}
                       value="{$org->getName()}"
                    {else}
                        placeholder="{Localisation::getTranslation('org_private_profile_your_organisation_name')}"
                    {/if}
                    />

                    <label for='biography'><strong>{Localisation::getTranslation('org_private_profile_organisation_overview')} <span style="color: red">*</span></strong></label>
                    <textarea name='biography' id='biography' cols='40' rows='10' style="width: 80%"
                    {if is_null($org->getBiography()) || $org->getBiography() == ''}
                        placeholder="{Localisation::getTranslation('org_private_profile_enter_organisation_biography_here')}"
                    {/if}
                    >{if $org->getBiography() != ''}{TemplateHelper::uiCleanseNewlineAndTabs($org->getBiography())}{/if}</textarea>

                    <label for='activitys'><strong>{Localisation::getTranslation('org_private_profile_organisation_activity')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                    <select name='activitys[]' multiple id='activitys' style="width: 80%">
                        <option value=""></option>
                        {foreach from=$activitys item=activity}
                            <option value="{$activity['code']}" {if $activity['selected']}selected="selected"{/if}>{$activity['value']}</option>
                        {/foreach}
                    </select>

                    <label for='homepage'><strong>{Localisation::getTranslation('org_private_profile_organisation_website')}</strong></label>
                    <input type='text' name='homepage' id='homepage' style="width: 80%"
                    {if !is_null($org->getHomepage()) && $org->getHomepage() != ''}
                        value="{$org->getHomepage()}"
                    {else}
                        placeholder="http://"
                    {/if}
                    />

                    <label for='facebook'><strong>{Localisation::getTranslation('org_private_profile_organisation_facebook')}</strong></label>
                    <input type='text' name='facebook' id='facebook' style="width: 80%"
                    {if $org2->getFacebook() != ''}
                        value="{$org2->getFacebook()}"
                    {else}
                        placeholder="http://"
                    {/if}
                    />

                    <label for='linkedin'><strong>{Localisation::getTranslation('org_private_profile_organisation_linkedin')}</strong></label>
                    <input type='text' name='linkedin' id='linkedin' style="width: 80%"
                    {if $org2->getLinkedin() != ''}
                        value="{$org2->getLinkedin()}"
                    {else}
                        placeholder="http://"
                    {/if}
                    />

                    <label for='email'><strong>{Localisation::getTranslation('org_private_profile_organisation_email_volunteers')}</strong></label>
                    <input type='text' name='email' id='email' style="width: 80%"
                    {if !is_null($org->getEmail()) && $org->getEmail() != ''}
                         value="{$org->getEmail()}"
                    {else}
                        placeholder="{Localisation::getTranslation('org_private_profile_organisationexamplecom')}"
                    {/if}
                    />
                </td>

                <td width="50%">
                    <label for='address'><strong>{Localisation::getTranslation('common_address')}</strong></label>
                    <textarea name='address' id='address' cols='40' rows='7' style="width: 80%">
                    {if $org->getAddress() != ''}{TemplateHelper::uiCleanseNewlineAndTabs($org->getAddress())}{/if}</textarea>

                    <label for='city'><strong>{Localisation::getTranslation('common_city')}</strong></label>
                    <input type='text' name='city' id='city' style="width: 80%"
                    {if $org->getCity() != ''}
                         value="{$org->getCity()}"
                    {/if}
                    />

                    <label for='country'><strong>{Localisation::getTranslation('common_country')}</strong></label>
                    <input type='text' name='country' id='country' style="width: 80%"
                    {if $org->getCountry() != ''}
                         value="{$org->getCountry()}"
                    {/if}
                    />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold; text-align: center; padding-bottom: 10px">
                    <hr/>
                    {Localisation::getTranslation('common_regional_focus')}
                </td>
            </tr>  
            <tr align="center">
                <td colspan="2">
                    <table> 
                        <thead>
                            <th>{Localisation::getTranslation('common_africa')}</th>
                            <th>{Localisation::getTranslation('common_asia')}</th>
                            <th>{Localisation::getTranslation('common_australia')}</th>
                            <th>{Localisation::getTranslation('common_europe')}</th>
                            <th>{Localisation::getTranslation('common_north_america')}</th>
                            <th>{Localisation::getTranslation('common_south_america')}</th>                       
                        </thead>
                        <tr align="center">
                            <td style="width: 15%"><input id="africa" name="africa" type="checkbox" {if strstr($org->getRegionalFocus(), "Africa")} checked="true" {/if} /></td>   
                            <td style="width: 15%"><input id="asia" name="asia" type="checkbox" {if strstr($org->getRegionalFocus(), "Asia")} checked="true" {/if} /></td> 
                            <td style="width: 15%"><input id="australia" name="australia" type="checkbox" {if strstr($org->getRegionalFocus(), "Australia")} checked="true" {/if} /></td> 
                            <td style="width: 15%"><input id="europe" name="europe" type="checkbox" {if strstr($org->getRegionalFocus(), "Europe")} checked="true" {/if}/></td> 
                            <td style="width: 15%"><input id="northAmerica" name="northAmerica" type="checkbox" {if strstr($org->getRegionalFocus(), "North-America")} checked="true" {/if} /></td> 
                            <td style="width: 15%"><input id="southAmerica" name="southAmerica" type="checkbox" {if strstr($org->getRegionalFocus(), "South-America")} checked="true" {/if} /></td> 
                        </tr>

                        <tr align="center">
                            <td colspan="2" style="font-weight: bold; text-align: center; padding-bottom: 10px">
                                {Localisation::getTranslation('org_private_profile_organisation_visible_by_members')}...
                                <hr/>
                            </td>

                            <td colspan="2">
                                <label for='primarycontactname'><strong>{Localisation::getTranslation('org_private_profile_organisation_primary_contact_name')} <span style="color: red">*</span></strong></label>
                                <input type='text' name='primarycontactname' id='primarycontactname' style="width: 80%"
                                {if $org2->getPrimaryContactName() != ''}
                                     value="{$org2->getPrimaryContactName()}"
                                {/if}
                                />

                                <label for='primarycontacttitle'><strong>{Localisation::getTranslation('org_private_profile_organisation_primary_contact_title')}</strong></label>
                                <input type='text' name='primarycontacttitle' id='primarycontacttitle' style="width: 80%"
                                {if $org2->getPrimaryContactTitle() != ''}
                                     value="{$org2->getPrimaryContactTitle()}"
                                {/if}
                                />

                                <label for='primarycontactemail'><strong>{Localisation::getTranslation('org_private_profile_organisation_primary_contact_email')} <span style="color: red">*</span></strong></label>
                                <input type='text' name='primarycontactemail' id='primarycontactemail' style="width: 80%"
                                {if $org2->getPrimaryContactEmail() != ''}
                                    value="{$org2->getPrimaryContactEmail()}"
                                {else}
                                    placeholder="{Localisation::getTranslation('org_private_profile_organisationexamplecom')}"
                                {/if}
                                />

                                <label for='primarycontactphone'><strong>{Localisation::getTranslation('org_private_profile_organisation_primary_contact_phone')}</strong></label>
                                <input type='text' name='primarycontactphone' id='primarycontactphone' style="width: 80%"
                                {if $org2->getPrimaryContactPhone() != ''}
                                     value="{$org2->getPrimaryContactPhone()}"
                                {/if}
                                />

                                <label for='othercontacts'><strong>{Localisation::getTranslation('org_private_profile_organisation_other_contacts')}</strong></label>
                                <textarea name='othercontacts' id='othercontacts' cols='40' rows='7' style="width: 80%">
                                {if $org2->getOtherContacts() != ''}{TemplateHelper::uiCleanseNewlineAndTabs($org2->getOtherContacts())}{/if}</textarea>

                                <label for='structure'><strong>{Localisation::getTranslation('org_private_profile_organisation_structure')}</strong></label>
                                <textarea name='structure' id='structure' cols='40' rows='10' style="width: 80%">
                                {if $org2->getStructure() != ''}{TemplateHelper::uiCleanseNewlineAndTabs($org2->getStructure())}{/if}</textarea>

                                <label for='affiliations'><strong>{Localisation::getTranslation('org_private_profile_organisation_affiliations')}</strong></label>
                                <textarea name='affiliations' id='affiliations' cols='40' rows='10' style="width: 80%">
                                {if $org2->getAffiliations() != ''}{TemplateHelper::uiCleanseNewlineAndTabs($org2->getAffiliations())}{/if}</textarea>

                                <label for='urlvideo1'><strong>{Localisation::getTranslation('org_private_profile_organisation_url_video_1')}</strong></label>
                                <input type='text' name='urlvideo1' id='urlvideo1' style="width: 80%"
                                {if $org2->getUrlVideo1() != ''}
                                    value="{$org2->getUrlVideo1()}"
                                {else}
                                    placeholder="http://"
                                {/if}
                                />

                                <label for='urlvideo2'><strong>{Localisation::getTranslation('org_private_profile_organisation_url_video_2')}</strong></label>
                                <input type='text' name='urlvideo2' id='urlvideo2' style="width: 80%"
                                {if $org2->getUrlVideo2() != ''}
                                    value="{$org2->getUrlVideo2()}"
                                {else}
                                    placeholder="http://"
                                {/if}
                                />

                                <label for='urlvideo3'><strong>{Localisation::getTranslation('org_private_profile_organisation_url_video_3')}</strong></label>
                                <input type='text' name='urlvideo3' id='urlvideo3' style="width: 80%"
                                {if $org2->getUrlVideo3() != ''}
                                    value="{$org2->getUrlVideo3()}"
                                {else}
                                    placeholder="http://"
                                {/if}
                                />

                                <label for='employees'><strong>{Localisation::getTranslation('org_private_profile_organisation_employee')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='employees[]' multiple id='employees' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$employees item=employee}
                                        <option value="{$employee['code']}" {if $employee['selected']}selected="selected"{/if}>{$employee['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='fundings'><strong>{Localisation::getTranslation('org_private_profile_organisation_funding')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='fundings[]' multiple id='fundings' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$fundings item=funding}
                                        <option value="{$funding['code']}" {if $funding['selected']}selected="selected"{/if}>{$funding['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='finds'><strong>{Localisation::getTranslation('org_private_profile_organisation_find')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='finds[]' multiple id='finds' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$finds item=find}
                                        <option value="{$find['code']}" {if $find['selected']}selected="selected"{/if}>{$find['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='translations'><strong>{Localisation::getTranslation('org_private_profile_organisation_translation')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='translations[]' multiple id='translations' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$translations item=translation}
                                        <option value="{$translation['code']}" {if $translation['selected']}selected="selected"{/if}>{$translation['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='requests'><strong>{Localisation::getTranslation('org_private_profile_organisation_request')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='requests[]' multiple id='requests' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$requests item=request}
                                        <option value="{$request['code']}" {if $request['selected']}selected="selected"{/if}>{$request['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='contents'><strong>{Localisation::getTranslation('org_private_profile_organisation_content')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='contents[]' multiple id='contents' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$contents item=content}
                                        <option value="{$content['code']}" {if $content['selected']}selected="selected"{/if}>{$content['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='subjectmatters'><strong>{Localisation::getTranslation('org_private_profile_organisation_subject_matters')}</strong></label>
                                <textarea name='subjectmatters' id='subjectmatters' cols='40' rows='7' style="width: 80%">
                                {if $org2->getSubjectMatters() != ''}{TemplateHelper::uiCleanseNewlineAndTabs($org2->getSubjectMatters())}{/if}</textarea>

                                <label for='pages'><strong>{Localisation::getTranslation('org_private_profile_organisation_pages')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='pages[]' multiple id='pages' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$pages item=page}
                                        <option value="{$page['code']}" {if $page['selected']}selected="selected"{/if}>{$page['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='sources'><strong>{Localisation::getTranslation('org_private_profile_organisation_source')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='sources[]' multiple id='sources' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$sources item=source}
                                        <option value="{$source['code']}" {if $source['selected']}selected="selected"{/if}>{$source['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='targets'><strong>{Localisation::getTranslation('org_private_profile_organisation_target')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='targets[]' multiple id='targets' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$targets item=target}
                                        <option value="{$target['code']}" {if $target['selected']}selected="selected"{/if}>{$target['value']}</option>
                                    {/foreach}
                                </select>

                                <label for='oftens'><strong>{Localisation::getTranslation('org_private_profile_organisation_often')}</strong>{Localisation::getTranslation('org_private_profile_organisation_multiple')}</label>
                                <select name='oftens[]' multiple id='oftens' style="width: 80%">
                                    <option value=""></option>
                                    {foreach from=$oftens item=often}
                                        <option value="{$often['code']}" {if $often['selected']}selected="selected"{/if}>{$often['value']}</option>
                                    {/foreach}
                                </select>
                                <hr/>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
            <tr>                
                <td colspan="2" style="padding-bottom: 20px"><hr/></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button type='submit' class='btn btn-primary' name='updateOrgDetails'>
                        <i class="icon-refresh icon-white"></i> {Localisation::getTranslation('org_private_profile_update_organisation_details')}
                    </button>
                    {if isset($orgAdmin)}
                        <button type="submit" class="btn btn-inverse" value="{$org_id}" name="deleteId"
                                onclick="return confirm('{Localisation::getTranslation('org_private_profile_confirm_delete')}');"> 
                            <i class="icon-fire icon-white"></i> {Localisation::getTranslation('org_private_profile_delete_organisation')}
                        </button>
                    {/if}
                </td>
            </tr>
  
        </table>
    </form>    



{include file='footer.tpl'}
