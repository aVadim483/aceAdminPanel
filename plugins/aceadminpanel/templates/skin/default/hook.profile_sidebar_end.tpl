{if $oUserCurrent and $oUserCurrent->isAdministrator()}
<section class="block block-type-profile-nav">
    <ul class="nav nav-profile">
        <li><a href="{router page="admin"}users/profile/{$oUserProfile->getLogin()}">{$aLang.plugin.aceadminpanel.adm_user_profile_link}</a></li>
    </ul>
</section>
{/if}