{extends file="./users.tpl"}

{block name="content"}

<script type="text/javascript">
    aceAdmin.sort = function (sort, order) {
        var i, el;
        if (document.getElementById) {
            if ((el = document.getElementById('user_list_sort'))) el.value = sort;
            if ((el = document.getElementById('user_list_order'))) el.value = order;
            if ((el = document.getElementById('admin_form_seek'))) el.submit();
        }
    }

    aceAdmin.sortToggle = function (sort, order) {
        aceAdmin.sort(sort, (order == 1) ? 2 : 1);
    }

    aceAdmin.selectAllUsers = function (element) {
        if ($(element).prop('checked')) {
            $('table.users-list tr.selectable td.checkbox input[type=checkbox]').prop('checked', true);
            $('table.users-list tr.selectable').addClass('info');
        } else {
            $('table.users-list tr.selectable td.checkbox input[type=checkbox]').prop('checked', false);
            $('table.users-list tr.selectable').removeClass('info');
        }
        aceAdmin.user.select();
    }

    aceAdmin.selectIp = function (ip) {
        if (!$('#admin_form_seek_div').hasClass('in'))
            $('#admin_form_seek_div').collapse('show');
        $('input.ip-part').val('');
        $.each(ip.toString().split('.'), function(index, item){
            $('#user_filter_ip' + (index+1)).val(parseInt(item)).parents('.control-group').first().addClass('success');
        });
    }

    aceAdmin.splitIp = function () {
        $('td.ip-split').each(function () {
            var ip = $(this).text();
            if (ip) {
                var html = '';
                var parts = ip.split('.');
                for (var i = 0; i < parts.length; i++) {
                    if (!html) {
                        html = '<span class="ip-split-' + i + '">' + parts[i] + '</span>';
                    }
                    else {
                        html = '<span class="ip-split-' + i + '">' + html + '.' + parts[i] + '</span>';
                    }
                }
                $(this).html(html);
                $(this).find('span').each(function () {
                    $(this).mouseover(function (event) {
                        $(this).addClass('hover');
                        event.stopPropagation();
                    }).mouseout(function (event) {
                                $(this).removeClass('hover');
                                event.stopPropagation();
                            }).click(function(event){
                                event.stopPropagation();
                                $(this).addClass('hover');
                                aceAdmin.selectIp($(this).text());
                            });
                });
            }
        });
    }

    $(function () {
        aceAdmin.splitIp();
    });
</script>


<h3>{$oLang->_adm_users_list}</h3>

<ul class="nav nav-tabs">
    <li class="nav-tabs-add">
        <span><i class="icon-plus-sign icon-disabled"></i></span>
    </li>
    <li {if $sMode=='all' || $sMode==''}class="active"{/if}>
        <a href="{router page='admin'}users/list/">All users <span class="badge">{$aStat.count_all}</span></a>
    </li>
    <li {if $sMode=='admins'}class="active"{/if}>
        <a href="{router page='admin'}users/admins/">Admins <span class="badge">{$aStat.count_admins}</span></a>
    </li>
</ul>

    {if $aUserList}
        {include file="$sTemplatePath/inc.paging.tpl"}
        {include file="$sTemplatePathAction/table_users.tpl"}
        {include file="$sTemplatePath/inc.paging.tpl"}
    {else}
        {$oLang->user_empty}
    {/if}
{/block}