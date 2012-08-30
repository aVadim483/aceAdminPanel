<div class="block white">
    <div class="tl">
        <div class="tr"></div>
    </div>
    <div class="cl">
        <div class="cr">
            <h1>aceAdminPanel &darr;</h1>

            <div style="margin-left:20px;">
            {$oLang->adm_text_about}<br/>
            {if $sMenuItemSelect=='' OR $sMenuItemSelect=='info' OR $sMenuItemSelect=='site'}
            {$oLang->adm_text_donate}
                <div style="font-size: 1em; display: block;position: relative;left: -20px; padding-top: 10px;">
                    <form style="margin: 0; padding: 0;" action="https://money.yandex.ru/charity.xml" method="post" target="_blank">
                        <input type="hidden" name="to" value="41001176375531"/>
                        <input type="hidden" name="CompanyName" value="За aceAdminPanel"/>
                        <input type="hidden" name="CompanyLink" value="http://livestreetcms.com/addons/view/132/"/>
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <div style="background: url(https://img.yandex.net/i/li-uncolorer-rt.gif) no-repeat right top #FFFFFF;">
                                        <div style="background: url(https://img.yandex.net/i/li-uncolorer-rb.gif) no-repeat right bottom;">
                                            <div style="background: url(https://img.yandex.net/i/li-uncolorer-lb.gif) no-repeat left bottom;">
                                                <div style="background: url(https://img.yandex.net/i/li-uncolorer-lt.gif) no-repeat left top; margin-right: 10px; padding: 10px 0 0 10px;">
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td>
                                                                <table border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td><input type="submit" value="Поощрить Яндекс.Деньгами"
                                                                                   style="margin-right: 5px;"/></td>
                                                                        <td><input type="text" id="CompanySum"
                                                                                   name="CompanySum" value="99" size="3"
                                                                                   style="margin-right: 5px;"/></td>
                                                                        <td nowrap="nowrap"
                                                                            style=""
                                                                            valign="bottom"><strong>руб.
                                                                            </strong></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td width="90" rowspan="3" valign="bottom"></td>
                                                        </tr>
                                                        <tr>
                                                            <td nowrap="nowrap"
                                                                style="">
                                                                на счет <span
                                                                    style="color: #006600; font-weight: bold;">41001176375531</span>&nbsp;(<a
                                                                    href="http://livestreetcms.com/addons/view/132/"><span
                                                                    style="color: #666666; text-decoration: underline;">За aceAdminPanel</span></a>)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="https://img.yandex.net/i/x.gif" width="1"
                                                                     height="10"/></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                {/if}
            </div>
        </div>
    </div>
    <div class="bl">
        <div class="br"></div>
    </div>
</div>
