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

aceAdmin.formToggle = function (id, save) {
    var forms = $('.sidebar .users-form');
    $('.sidebar .btn.users-form').removeClass('btn-gray');
    forms.each(function (index, el) {
        var form = $(el).find('form:first');
        el = $(el);
        if (el.attr('id') == id && form.is(':hidden')) {
            form.slideDown(function () {
                if ($(this).not(':hidden')) {
                    $(this).find('input[type=text]:first').focus();
                }
            });
            el.find('button.btn:first').addClass('btn-gray');
            $.cookie(id, 1);
        } else {
            form.slideUp();
            if (save) $.cookie(el.attr('id'), null);
        }
    });
};

$(function () {
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
});
