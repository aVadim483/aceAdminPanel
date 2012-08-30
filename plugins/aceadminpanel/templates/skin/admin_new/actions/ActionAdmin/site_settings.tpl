{extends file='index.tpl'}

{block name="content"}

<h3>{$oLang->_adm_settings_title}</h3>
<ul class="nav nav-tabs">
    <li {if $sMenuNavItemSelect=='base'}class="active"{/if}><a
            href="{router page='admin'}site/settings/base/">{$oLang->adm_settings_base}</a></li>
    <li {if $sMenuNavItemSelect=='sys'}class="active"{/if}><a
            href="{router page='admin'}site/settings/sys/">{$oLang->adm_settings_sys}</a></li>
    <li {if $sMenuNavItemSelect=='acl'}class="active"{/if}><a
            href="{router page='admin'}site/settings/acl/">{$oLang->adm_settings_acl}</a></li>
</ul>

<form action="" method="POST">

    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>
    {foreach from=$aFields key=sConfigKey item=aItem}
    <p class="offset1">
        {if $aItem.type=='section'}
            {assign var="sTitle" value="$sConfigKey"}
            <h4 class="form-actions">{$oLang->Get("`$sTitle`")}</h4>
        {else}
            {if $aItem.type=='checkbox'}
                <label class="checkbox {if ($aItem.value)}checked{/if}">{$oLang->Get("$sConfigKey")}:
                    <input type="{$aItem.type}" id="{$sConfigKey}" name="{$sConfigKey}" value="1" class="{$aItem.class}"
                           {if ($aItem.value)}checked{/if} />
                </label>
            {elseif $aItem.type=='select'}
                <label for="{$sConfigKey}">{$oLang->Get("$sConfigKey")}:</label>
                <select id="{$sConfigKey}" name="{$sConfigKey}" class="{$aItem.class}">
                    {foreach from=$aItem.options item=sOption}
                        <option value="{$sOption}" {if $sOption==$aItem.value}selected{/if}>{$sOption}</option>
                    {/foreach}
                </select>
            {else}
                <label for="{$sConfigKey}">{$oLang->Get("$sConfigKey")}:</label>
                <input type="{$aItem.type}" id="{$sConfigKey}" name="{$sConfigKey}" value="{$aItem.value}"
                       class="{$aItem.class}"/><br/>
            {/if}
            <!-- span class="form_note">{$oLang->Get("adm_set_`$sConfigKey`_notice")}</span -->
        {/if}
        </p>
    {/foreach}

    <div class="form-actions fix-on-container">
        <div class="navbar fix-on-bottom">
            <div class="navbar-inner">
                <div class="container">
                    <input type="submit" name="submit_data_save" value="{$oLang->_adm_save}" class="btn btn-primary pull-right"/>
                </div>
            </div>
        </div>
    </div>

</form>

<script type="text/javascript">
    /*
    $(function () {
        var nav_containers = $('.fix-on-container');
        nav_containers.each(function(index){
            var container = $(this);
            var navbar = container.find('.navbar.fix-on-bottom').first();
            container.waypoint({
                handler:function (event, direction) {
                    navbar.toggleClass('sticky-bottom', direction == 'up');
                },
                offset: 'bottom-in-view'
            });
            navbar.css('width', navbar.width());
            if ($.waypoints('viewportHeight') + $('body').scrollTop() < navbar.offset().top-navbar.outerHeight()) {
                navbar.addClass('sticky-bottom');
            }
        });
    });
    */
</script>

{/block}