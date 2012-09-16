// ace-admin.js

if (aceAdmin === undefined) {
    var aceAdmin = {
        store:{},

        init:function () {

        }
    };

    (function () {
        var $this = aceAdmin;

        $this.init = function () {
        };

        $this.uniqId = function () {
            return 'id-' + new Date().valueOf() + '-' + Math.floor(Math.random() * 1000000000);
        }

        $this.progressOn = function (element) {
            element = $(element);
            if (!element.data('adm-progress-store')) {
                element.data('adm-progress-store', element.html());
            }
            element.css('width', element.outerWidth());
            element.html('<i class="adm-progress"></i>');
        }

        $this.progressOff = function (element) {
            element = $(element);
            element.find('i.adm-progress').remove();
            if (element.data('adm-progress-store')) element.html(element.data('adm-progress-store'));
        }

        $this.plugin = {
            switch:function (pluginId, action) {
                var url = aRouter['admin'] + 'plugins/?plugin=' + pluginId + '&action=' + action + '&security_ls_key=' + LIVESTREET_SECURITY_KEY;
                document.location = url;
            },

            switchOn:function (pluginId) {
                return $this.plugin.switch(pluginId, 'activate');
            },

            switchOff:function (pluginId) {
                return $this.plugin.switch(pluginId, 'deactivate');
            }
        };

        // указательные окна
        $this.pointup = function (element, options) {
            options = $.extend({
                trigger:'manual',
                placement:'top',
                onCancel:function (e, popover) {
                    // nothing
                },
                onConfirm:function (e, popover) {
                    // nothing
                }
            }, options);
            if (!options.attr.id) options.attr.id = aceAdmin.uniqId();

            element = $(element);
            element.popover(options);
            element.data('popoverOptions', options);

            var popover = element.data('popover');
            var popoverElement = $(popover.tip());

            if (options.attr) {
                $.each(options.attr, function (key, value) {
                    switch (key) {
                        case 'class':
                            popoverElement.addClass(value);
                            break;
                        default:
                            popoverElement.prop(key, value);
                    }
                });
            }
            if (popover.getTitle() === false) {
                popoverElement.find('.popover-title').css({display:'none'});
            }

            $(document).on('click', '#' + options.attr.id + ".popover .confirm", {source:element}, function (e) {
                var opt = element.data('popoverOptions');
                if (opt.onConfirm) opt.onConfirm(e, $('#' + opt.attr.id));
                element.popover('hide');
            });
            $(document).on('click', '#' + options.attr.id + ".popover .cancel", {source:element}, function (e) {
                var opt = element.data('popoverOptions');
                if (opt.onCancel) opt.onCancel(e, $('#' + opt.attr.id));
                element.popover('hide');
            });

            return popoverElement;
        }

        $this.isEmpty = function (mixedVar) {
            return ((typeof mixedVar == 'undefined') || (mixedVar === null));
        }

        $this.init();
        if ($this.short) $ace = $this;
    })();
}

!function ($) {
    "use strict"; // jshint ;_;

    $.fn.pointup = function (option) {
        return $.fn.popover(option)
    }

}(window.jQuery);

!function ($) {
    "use strict"; // jshint ;_;

    $.fn.progressOn = function () {
        return aceAdmin.progressOn(this);
    }

    $.fn.progressOff = function () {
        return aceAdmin.progressOff(this);
    }

}(window.jQuery);

aceAdmin.vote = function (type, idTarget, value, viewElements, funcDone) {
    var options = {
        classes_action:{
            voted:'voted',
            plus:'plus',
            minus:'minus',
            positive:'positive',
            negative:'negative',
            quest:'quest'
        },
        classes_element:{
            voting:'voting',
            count:'count',
            total:'total',
            plus:'plus',
            minus:'minus'
        }
    }

    var typeVote = {
        user:{
            url:'admin/vote/user/',
            targetName:'idUser'
        }
    }

    var voteResult = function (result) {
        if (!result) {
            ls.msg.error('Error', 'Please try again later');
        }
        if (result.bStateError) {
            ls.msg.error(result.sMsgTitle, result.sMsg);
        } else {
            if (viewElements.skill) {
                $(viewElements.skill).text(result.iSkill);
                if (type == 'user' && $('user_skill_' + idTarget)) {
                    $('#user_skill_' + idTarget).text(result.iSkill);
                }
            }

            if (viewElements['rating']) {
                var view = $(viewElements['rating']);

                result.iRating = parseFloat(result.iRating);
                if (result.iRating > 0) {
                    result.iRating = '+' + result.iRating;
                } else if (result.iRating == 0) {
                    result.iRating = '0';
                }
                view.removeClass(options.classes_action.negative)
                    .removeClass(options.classes_action.positive)
                    .text(result.iRating);
                if (result.iRating < 0) {
                    view.addClass(options.classes_action.negative)
                } else {
                    view.addClass(options.classes_action.positive)
                }
            }

            if (viewElements['voteCount']) {
                $(viewElements.voteCount).text(result.iCountVote);
            }

            ls.msg.notice(result.sMsgTitle, result.sMsg);
        }
        if (funcDone) funcDone();
    }

    // do
    var $this = this;
    if (!typeVote[type]) {
        return false;
    }

    $this.idTarget = idTarget;
    $this.value = value;
    $this.type = type;

    var params = {}, more;
    params['value'] = value;
    params[typeVote[type].targetName] = idTarget;

    ls.ajax(typeVote[type].url, params, function (result) {
        if (!result) {
            ls.msg.error('Error', 'Please try again later');
        }
        if (result.bStateError) {
            ls.msg.error(result.sMsgTitle || 'Error', result.sMsg || 'Please try again later');
        } else {
            voteResult(result, $this);
        }
    }, more);

}

aceAdmin.switchFormToggle = function (id, show, single) {
    if (typeof id == 'string') {
        var el = $('#' + id);
    } else {
        var el = $(id);
        id = el.attr('id');
    }
    if (!single) {
        var group = el.parents('.switch-form-group').first();
        if (group.length) {
            group.find('.switch-form').each(function(){
                if (el.attr('id') != $(this).attr('id'))
                    aceAdmin.switchFormToggle($(this), false, true);
            });
        }
    }
    var btn = el.find('.btn.switch-form-button').removeClass('btn-gray');
    var form = el.find('.switch-form-content');
    if ((typeof show == 'undefined' ) || (show === null)) show = form.is(':hidden');
    if (show) {
        form.slideDown(function () {
            form.find('input[type=text]:first').focus();
        });
        btn.addClass('btn-gray');
        if (el.hasClass('switch-form-save')) $.cookie(id, 1);
    } else {
        form.slideUp();
        $.cookie(id, null);
    }
};

aceAdmin.switchFormShow = function (id) {
    return aceAdmin.switchFormToggle(id, true);
}

aceAdmin.switchFormHide = function (id) {
    return aceAdmin.switchFormToggle(id, false);
}

aceAdmin.inputFileStyle = function (id) {
    if (aceAdmin.isEmpty(id)) id = $('input.input-file');
    else id = $(id);

    id.each(function(){
        var inputFile = $(this);
        var wrapper = inputFile.css('opacity', 0)
            .wrap($('<div></div>').css({position: 'relative'}))
            .parent();
        var inner = $('<div></div>').css({position: 'absolute', left: 0, top: 0}).appendTo(wrapper);
        inner.append(
            $('<button></button>')
                .addClass('btn')
                .html(ls.lang.get('aceadminpanel_adm_select_file'))
        ).append($('<span class="input-text file-name"></span>'));
        inputFile.on('change', function(){
            var fileName = inputFile.val().replace(/^.*[\/\\]/g, '')
            wrapper.find('span.input-text.file-name').text(fileName);
        });
        wrapper.click(function(event){
            if (!event.target.type || event.target.type != 'file') inputFile.click();
        });
    });
}

aceAdmin.nativeUiCompatible = function() {
    /*
    $('button.button').addClass('btn');
    $('button.button-primary').addClass('btn-primary');

    $('ul.nav-pills-tabs').addClass('nav-tabs').find('li').each(function(){
        var link = $(this).data('type');
        if (link) {
            $(this).find('a').prop('href', '#' + link);
        }
    });
    */

    // Автокомплит
    ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
    ls.autocomplete.add($(".autocomplete-tags"), aRouter['ajax']+'autocompleter/tag/', false);
    ls.autocomplete.add($(".autocomplete-users-sep"), aRouter['ajax']+'autocompleter/user/', true);
    ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', false);

    // Всплывающие окна
    $('#window_upload_img').jqm();
    /*
    $('#window_login_form').jqm();
    $('#blog_delete_form').jqm({trigger: '#blog_delete_show'});
    $('#add_friend_form').jqm({trigger: '#add_friend_show'});
    $('#userfield_form').jqm();
    $('#favourite-form-tags').jqm();
    $('#modal_write').jqm({trigger: '.js-write-window-show'});
    $('#foto-resize').jqm({modal: true});
    $('#avatar-resize').jqm({modal: true});
    $('#userfield_form').jqm({toTop: true});
    $('#photoset-upload-form').jqm({trigger: '#photoset-start-upload'});
    */
}

$(function () {
    $('.switch-form').each(function () {
        var element = $(this);
        var id = element.attr('id');
        element.find('.switch-form-button').first().on('click', function () {
            aceAdmin.switchFormToggle(id);
        });
        //if ($.cookie(id)) aceAdmin.switchFormShow(id);
    });

    var nav_containers = $('.fix-on-container');
    nav_containers.each(function (index) {
        var container = $(this);
        var navbar = container.find('.navbar.fix-on-bottom').first();
        container.waypoint({
            handler:function (event, direction) {
                navbar.toggleClass('sticky-bottom', direction == 'up');
            },
            offset:'bottom-in-view'
        });
        navbar.css('width', navbar.width());
        if ($.waypoints('viewportHeight') + $('body').scrollTop() < navbar.offset().top - navbar.height()) {
            navbar.addClass('sticky-bottom');
        }
    });

    $('tr.selectable td.checkbox input[type=checkbox]').on('click', function (event) {
        //console.log($(this).prop('checked'), $(this).parents('tr'));
        if ($(this).prop('checked')) {
            $(this).parents('tr').first().addClass('info');
        } else {
            $(this).parents('tr').first().removeClass('info');
        }
    });

    aceAdmin.nativeUiCompatible();
    aceAdmin.inputFileStyle();
});

// EOF