/*
 * jQuery SelectBox Styler v1.0.1
 * http://dimox.name/styling-select-boxes-using-jquery-css/
 *
 * Copyright 2012 Dimox (http://dimox.name/)
 * Released under the MIT license.
 *
 * Date: 2012.10.07
 *
 */

(function($)
{
    $.fn.selectbox = function()
    {
        $(this).each(function()
        {
            var select = $(this);
            if (select.prev('span.selectbox').length < 1)
            {
                function doSelect()
                {
                    var option = select.find('option');
                    var optionSelected = option.filter(':selected');
                    var optionText = option.filter(':first').text();
                    if (optionSelected.length)
                    {
                        optionText = optionSelected.text();
                    }
                    var ddlist = '';
                    for (i = 0; i < option.length; i++)
                    {
                        var selected = '';
                        var disabled = ' class="disabled"';
                        if (option.eq(i).is(':selected'))
                        {
                            selected = ' class="selected sel"';
                        }
                        if (option.eq(i).is(':disabled'))
                        {
                            selected = disabled;
                        }
                        ddlist += '<li' + selected + '>' + option.eq(i).text() + '</li>';
                    }
                    var selectbox =
                        $('<span class="selectbox" style="display:inline-block;position:relative">' +
                            '<div class="select" style="float:left;position:relative;z-index:4"><div class="text">' + optionText + '</div>' +
                            '<b class="trigger"><i class="arrow"></i></b>' +
                            '</div>' +
                            '<div class="dropdown" style="position:absolute;z-index:9999;overflow:auto;overflow-x:hidden;list-style:none">' +
                            '<ul>' + ddlist + '</ul>' +
                            '</div>' +
                            '</span>');
                    select.before(selectbox).css({position: 'absolute', top: -9999});
                    var divSelect = selectbox.find('div.select');
                    divSelect.outerWidth(select.width());
                    var divText = selectbox.find('div.text');
                    var dropdown = selectbox.find('div.dropdown');
                    var li = dropdown.find('li');
                    var selectHeight = selectbox.actual('outerHeight');
                    if (dropdown.css('left') == 'auto')
                    {
                        dropdown.css({left: 0});
                    }
                    if (dropdown.css('top') == 'auto')
                    {
                        dropdown.css({top: selectHeight});
                    }
                    var liHeight = li.actual('outerHeight');

                    var position = dropdown.css('top');
                    dropdown.hide();
                    /* при клике на псевдоселекте */
                    divSelect.click(function()
                    {
                        /* умное позиционирование */
                        var topOffset = selectbox.offset().top;
                        var bottomOffset = $(window).height() - selectHeight - (topOffset - $(window).scrollTop());
                        if (bottomOffset < 0 || bottomOffset < liHeight * 6)
                        {
                            dropdown.height('auto').css({top: 'auto', bottom: position});
                            if (dropdown.actual('outerHeight') > topOffset - $(window).scrollTop() - 20)
                            {
                                dropdown.height(Math.floor((topOffset - $(window).scrollTop() - 20) / liHeight) * liHeight);
                            }
                        }
                        else if (bottomOffset > liHeight * 6)
                        {
                            dropdown.height('auto').css({bottom: 'auto', top: position});
                            if (dropdown.actual('outerHeight') > bottomOffset - 20)
                            {
                                dropdown.height(Math.floor((bottomOffset - 20) / liHeight) * liHeight);
                            }
                        }
                        $('span.selectbox').css({zIndex: 1}).removeClass('focused');
                        selectbox.css({zIndex: 7});
                        if (dropdown.is(':hidden'))
                        {
                            $('div.dropdown:visible').hide();
                            dropdown.show();
                        }
                        else
                        {
                            dropdown.hide();
                        }
                        return false;
                    });
                    /* при наведении курсора на пункт списка */
                    li.hover(function()
                    {
                        $(this).siblings().removeClass('selected');
                    });
                    var selectedText = li.filter('.selected').text();
                    /* при клике на пункт списка */
                    li.filter(':not(.disabled)').click(function()
                    {
                        var liText = $(this).text();
                        if (selectedText != liText)
                        {
                            $(this).addClass('selected sel').siblings().removeClass('selected sel');
                            option.removeAttr('selected').eq($(this).index()).attr('selected', true);
                            select.val(option.eq($(this).index()).val());
                            selectedText = liText;
                            divText.text(liText);
                            select.change();
                        }
                        dropdown.hide();
                    });
                    dropdown.mouseout(function()
                    {
                        dropdown.find('li.sel').addClass('selected');
                    });
                    /* фокус на селекте */
                    select.focus(function()
                    {
                        $('span.selectbox').removeClass('focused');
                        selectbox.addClass('focused');
                    })
                        /* меняем селект с клавиатуры */
                        .keyup(function()
                        {
                            divText.text(option.filter(':selected').text());
                            li.removeClass('selected sel').eq(option.filter(':selected').index()).addClass('selected sel');
                        });
                    /* прячем выпадающий список при клике за пределами селекта */
                    $(document).on('click', function(e)
                    {
                        if (!$(e.target).parents().hasClass('selectbox'))
                        {
                            dropdown.hide().find('li.sel').addClass('selected');
                            selectbox.removeClass('focused');
                        }
                    });
                }

                doSelect();
                // обновление при динамическом изменении
                select.on('refresh', function()
                {
                    select.prev().remove();
                    doSelect();
                })
            }
        });
    }
})(jQuery);