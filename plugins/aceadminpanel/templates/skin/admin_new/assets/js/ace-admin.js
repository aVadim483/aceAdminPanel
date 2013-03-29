// ace-admin.js
var aceAdmin = aceAdmin || {};

(function ($) {
    aceAdmin = {
        store:{},

        init:function () {

        }
    };

    var $this = aceAdmin;

    $this.init = function () {
    };

    $this.uniqId = function () {
        return 'id-' + new Date().valueOf() + '-' + Math.floor(Math.random() * 1000000000);
    }

    $this.progressOn = function (element, progressClass) {
        element = $(element);
        if (!element.data('adm-progress-store')) {
            element.data('adm-progress-store', element.html());
        }
        element.css('width', element.outerWidth());
        //element.css('height', element.outerHeight());
        if (!progressClass) progressClass = 'adm-progress';
        element.html('<i class="' + progressClass + '"></i>');
    }

    $this.progressOff = function (element) {
        element = $(element);
        element.find('i.adm-progress').remove();
        if (element.data('adm-progress-store')) element.html(element.data('adm-progress-store'));
    }

    $this.plugin = {
        turn:function (pluginId, action) {
            var url = aRouter['admin'] + 'plugins/?plugin=' + pluginId + '&action=' + action + '&security_ls_key=' + LIVESTREET_SECURITY_KEY;
            document.location = url;
        },

        turnOn:function (pluginId) {
            return $this.plugin.turn(pluginId, 'activate');
        },

        turnOff:function (pluginId) {
            return $this.plugin.turn(pluginId, 'deactivate');
        }
    }

    $this.popoverExt = function (element, options) {
        options = $.extend({
            attr:{ },
            css:{ },
            events:{ }
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
        if (options.css) {
            $.each(options.css, function (key, value) {
                popoverElement.css(key, value);
            });
        }
        $.each(options.events, function (key, func) {
            $(element).on(key, function (e) {
                func.apply(this, [e]);
            });
        });
        if (popover.getTitle() === false) {
            popoverElement.find('.popover-title').css({display:'none'});
        }
        return popoverElement;
    }

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
        var popoverElement = aceAdmin.popoverExt(element, options);

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

    $this.getPopover = function (element) {
        var popover = element.data('popover');
        if (popover) {
            var popoverElement = $(popover.tip());
            return popoverElement;
        }
    }

    $this.isEmpty = function (mixedVar) {
        return ((typeof mixedVar == 'undefined') || (mixedVar === null));
    }

})(jQuery);

$(function () {
    aceAdmin.init();
});

!function ($) {
    "use strict"; // jshint ;_;

    $.fn.isVisible = function () {
        var element = $(this);
        return (element.css('display') != 'none' && element.parent().length);
    }

}(window.jQuery);

!function ($) {
    "use strict"; // jshint ;_;

    $.fn.setPopover = function (options) {
        return aceAdmin.popoverExt(this, options);
    }

}(window.jQuery);

!function ($) {
    "use strict"; // jshint ;_;

    $.fn.getPopover = function () {
        return aceAdmin.getPopover(this);
    }

}(window.jQuery);

!function ($) {
    "use strict"; // jshint ;_;

    $.fn.pointup = function (options) {
        return $.fn.popover(options)
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

aceAdmin.selectAllRows = function (element, func) {
    var table = $(element).parents('table').first();
    if ($(element).prop('checked')) {
        $(table).find('tr.selectable td.checkbox input[type=checkbox]').prop('checked', true);
        $(table).find('tr.selectable').addClass('info');
    } else {
        $(table).find('tr.selectable td.checkbox input[type=checkbox]').prop('checked', false);
        $(table).find('tr.selectable').removeClass('info');
    }
    if (func) func(element);
}

aceAdmin.inputFileStyle = function (id) {
    if (aceAdmin.isEmpty(id)) id = $('input.input-file');
    else id = $(id);

    id.each(function () {
        var inputFile = $(this);
        var wrapper = inputFile.css('opacity', 0)
            .wrap($('<div></div>').css({position:'relative'}))
            .parent();
        var inner = $('<div></div>').css({position:'absolute', left:0, top:0}).appendTo(wrapper);
        inner.append(
            $('<button></button>')
                .addClass('btn')
                .html(ls.lang.get('aceadminpanel_adm_select_file'))
        ).append($('<span class="input-text file-name"></span>'));
        inputFile.on('change', function () {
            var fileName = inputFile.val().replace(/^.*[\/\\]/g, '')
            wrapper.find('span.input-text.file-name').text(fileName);
        });
        wrapper.click(function (event) {
            if (!event.target.type || event.target.type != 'file') inputFile.click();
        });
    });
}

aceAdmin.getCallstack = function () {
    var callstack = [];
    var isCallstackPopulated = false;
    try {
        i.dont.exist += 0; //does not exist - that's the point
    } catch (e) {
        if (e.stack) { //Firefox
            var lines = e.stack.split("\n");
            for (var i = 0, len = lines.length; i < len; i++) {
                if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
                    callstack.push(lines[i]);
                }
            }
            //Remove call to printStackTrace()
            callstack.shift();
            isCallstackPopulated = true;
        }
        else if (window.opera && e.message) { //Opera
            var lines = e.message.split("\n");
            for (var i = 0, len = lines.length; i < len; i++) {
                if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
                    var entry = lines[i];
                    //Append next line also since it has the file info
                    if (lines[i + 1]) {
                        entry += " at " + lines[i + 1];
                        i++;
                    }
                    callstack.push(entry);
                }
            }
            //Remove call to printStackTrace()
            callstack.shift();
            isCallstackPopulated = true;
        }
    }
    if (!isCallstackPopulated) { //IE and Safari
        var currentFunction = arguments.callee.caller;
        while (currentFunction) {
            var fn = currentFunction.toString();
            //If we can't get the function name set to "anonymous"
            var fname = fn.substring(fn.indexOf("function") + 8, fn.indexOf("(")) || "anonymous";
            callstack.push(fname);
            currentFunction = currentFunction.caller;
        }
    }
    return callstack;
}

// IE8 плохо воспринимает 'delete'
aceAdmin.blog = {
    del:function (msg, name, blog_id) {
        if (name) msg = msg.replace('%%blog%%', name);
        if (confirm(msg)) {
            var url = DIR_WEB_ROOT + '/admin/blogs/delete/?blog_id=' + blog_id + '&security_ls_key=' + LIVESTREET_SECURITY_KEY;
            document.location.href = url;
            return true;
        }
        return false;
    }
}

if (!aceAdmin.topic) aceAdmin.topic = {};

aceAdmin.topic = {
    del:function (msg, name, topic_id) {
        if (name) msg = msg.replace('%%topic%%', name);
        if (confirm(msg)) {
            var url = DIR_WEB_ROOT + '/admin/topics/delete/?topic_id=' + topic_id + '&security_ls_key=' + LIVESTREET_SECURITY_KEY;
            document.location.href = url;
            return true;
        }
        return false;
    }
}


aceAdmin.nativeUiCompatible = function () {
    // Автокомплит
    ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax'] + 'autocompleter/tag/', true);
    ls.autocomplete.add($(".autocomplete-tags"), aRouter['ajax'] + 'autocompleter/tag/', false);
    ls.autocomplete.add($(".autocomplete-users-sep"), aRouter['ajax'] + 'autocompleter/user/', true);
    ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax'] + 'autocompleter/user/', false);

    // Всплывающие окна
    $('#window_upload_img').jqm();
    $('#userfield_form').jqm({toTop:true});
    /*
     $('#window_login_form').jqm();
     $('#blog_delete_form').jqm({trigger: '#blog_delete_show'});
     $('#add_friend_form').jqm({trigger: '#add_friend_show'});
     $('#userfield_form').jqm();
     $('#favourite-form-tags').jqm();
     $('#modal_write').jqm({trigger: '.js-write-window-show'});
     $('#foto-resize').jqm({modal: true});
     $('#avatar-resize').jqm({modal: true});
     $('#photoset-upload-form').jqm({trigger: '#photoset-start-upload'});
     */
};

/**
 * Объект, аналогичный window.location, который позволяет манипулировать URL
 *
 * http://www.google.com:80/search?q=javascript#test
 *
 * hash         - часть URL, которая идет после символа решетки '#', включая символ '#' - #test
 * host         - хост и порт                       www.google.com:80
 * href         - весь URL                          http://www.google.com:80/search?q=javascript#test
 * hostname     - хост (без порта)                  www.google.com
 * pathname     - строка пути (относительно хоста)  /search
 * port         - номер порта                       80
 * protocol     - протокол                          http:
 * search       - часть адреса после символа ?, включая символ ?     ?q=javascript
 *
 * Based on http://habrahabr.ru/post/65407/ by Kottenator
 */
// Прячем всю реализацию в замыкание. Так надо для скрытия служебных функций parseURL и updateURL.
    (function () {

        aceAdmin.url = function (url) {
            // Собственно, данные. Для каждого нового объекта URL - свои, естественно.
            var href, protocol, host, hostname, port, pathname, search, hash;

            // Минус данного подхода - нам приходится определять методы в конструкторе, а не в прототипе.
            // Get/set href - при set вызываем parseURL.call(this),
            // т.е. внешняя функция parseURL обрабатывает объект типа URL - this.
            this.href = function (val) {
                if (typeof val != "undefined") {
                    href = val;
                    parseURL.call(this);
                }
                return href;
            }

            // Get/set protocol
            // Подобно set href, set protocol вызывает updateURL.call(this), который обновляет все параметры.
            this.protocol = function (val) {
                if (typeof val != "undefined") {
                    // Плюшка - если protocol не задан, берём из window.location
                    if (!val)
                        val = protocol || window.location.protocol;
                    protocol = val;
                    updateURL.call(this);
                }
                return protocol;
            }

            // Get/set host
            // Здесь особенность в том, что host, hostname и port - связаны между собой.
            // Поэтому надо делать дополнительную работу при set host.
            this.host = function (val) {
                if (typeof val != "undefined") {
                    val = val || '';
                    var v = val.split(':');
                    var h = v[0], p = v[1] || '';
                    host = val;
                    hostname = h;
                    port = p;
                    updateURL.call(this);
                }
                return host;
            }

            // Get/set hostname
            // Опять учитываем связку host, hostname и port.
            this.hostname = function (val) {
                if (typeof val != "undefined") {
                    if (!val)
                        val = hostname || window.location.hostname;
                    hostname = val;
                    host = val + (("" + port) ? ":" + port : "");
                    updateURL.call(this);
                }
                return hostname;
            }

            // Get/set port
            // Опять учитываем связку host, hostname и port.
            this.port = function (val) {
                if (typeof val != "undefined") {
                    port = val;
                    host = hostname + (("" + port) ? ":" + port : "");
                    updateURL.call(this);
                }
                return port;
            }

            // Get/set pathname
            // Имеется возможность использования relative pathname, т.е. если мы будем set'ить pathname,
            // и новое значение не будет начинаться с '/', то дополнится текущее.
            this.pathname = function (val) {
                if (typeof val != "undefined") {
                    if (val.indexOf("/") != 0) { // relative url
                        var _p = (pathname || window.location.pathname).split("/");
                        _p[_p.length - 1] = val;
                        val = _p.join("/");
                    }
                    pathname = val;
                    updateURL.call(this);
                }
                return pathname;
            }

            // Get/set search
            this.search = function (val) {
                if (typeof val != "undefined") {
                    search = val;
                }
                return search;
            }

            // Get/set hash
            this.hash = function (val) {
                if (typeof val != "undefined") {
                    hash = val;
                }
                return hash;
            }

            url = url || window.location.href;
            parseURL.call(this, url);
        }

        aceAdmin.url.prototype = {
            /**
             * Есть такой метод у window.location. Переход по заданому URL.
             */
            assign:function (url) {
                parseURL.call(this, url);
                window.location.assign(this.href());
            },

            /**
             * Есть такой метод у window.location. Переход по заданому URL, но без внесения в history
             */
            replace:function (url) {
                parseURL.call(this, url);
                window.location.replace(this.href());
            }
        }

        // Служебная функция, которая разбирает URL на части
        function parseURL(url) {
            if (this._innerUse)
                return;

            url = url || this.href();
            var pattern = "^(([^:/\\?#]+):)?(//(([^:/\\?#]*)(?::([^/\\?#]*))?))?([^\\?#]*)(\\?([^#]*))?(#(.*))?$";
            var rx = new RegExp(pattern);
            var parts = rx.exec(url);

            // Prevent infinite recursion
            this._innerUse = true;

            this.href(parts[0] || "");
            this.protocol(parts[1] || "");
            //this.host(parts[4] || "");
            this.hostname(parts[5] || "");
            this.port(parts[6] || "");
            this.pathname(parts[7] || "/");
            this.search(parts[8] || "");
            this.hash(parts[10] || "");

            delete this._innerUse;

            updateURL.call(this);
        }

        // Служебная функция, которая обновляет URL при изменении любой части
        function updateURL() {
            if (this._innerUse)
                return;

            // Prevent infinite recursion
            this._innerUse = true;

            this.href(this.protocol() + '//' + this.host() + this.pathname() + this.search() + this.hash());

            delete this._innerUse;
        }

    })();


$(function () {
    $('.switch-form').each(function () {
        var element = $(this);
        var id = element.attr('id');
        element.find('.switch-form-button').click(function (e) {
            //console.log(this);
            e.stopPropagation();
        });
    });
    $('.accordion-body').each(function () {
        $(this).on('button').each(function () {
            var id = $(this).attr('id');
            var but = $(this).parents('.accordion-group').find('.accordion-heading button');
            if (but.data('toggle') == 'collapse' && (but.data('target') == '#' + id)) {
                $(this).on('show', function () {
                    but.addClass('btn-gray');
                });
                $(this).on('shown', function () {
                    $(this).find('form input[type=text]').first().focus();
                    if ($(this).hasClass('collapse-save')) $.cookie(id, 1);
                });
                $(this).on('hide', function () {
                    but.removeClass('btn-gray');
                    $.cookie(id, null);
                });
            }
            if ($.cookie(id)) $('#' + id).collapse('show');
        });
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
        if ($(this).prop('checked')) {
            $(this).parents('tr').first().addClass('info');
        } else {
            $(this).parents('tr').first().removeClass('info');
        }
    });

    aceAdmin.nativeUiCompatible();
    aceAdmin.inputFileStyle();
    if ($.fn.tooltip) $('[rel="tooltip"]').tooltip();
});

var $ace = aceAdmin || {};
// EOF
