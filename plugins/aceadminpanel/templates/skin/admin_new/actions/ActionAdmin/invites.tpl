{extends file='index.tpl'}

{block name="content"}
<h3>{$oLang->settings_menu_invite}</h3>

{if $sMode == 'new'}
  {include file="$sTemplatePathAction/invites_new.tpl"}
{else}
  {include file="$sTemplatePathAction/invites_list.tpl"}
{/if}
{/block}