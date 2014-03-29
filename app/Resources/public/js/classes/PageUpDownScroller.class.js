var PageUpDownScroller = Base.extend({

    _upButton: null,
    _downButton: null,
    _prevPos: null,

    constructor: function()
    {
        this.base();

        this._upButton =  $("#upButton");
        this._downButton =  $("#downButton");

        if ($(window).scrollTop() >= PageUpDownScroller.SHOW_UP_BUTTON_HEIGHT)
        {
            this._upButton.fadeIn();
        }

        $(window).scroll(handler(this, "_onWindowScroll"));

        this._upButton.click(handler(this, "_onUpButtonClick"));
        this._downButton.click(handler(this, "_onDownButtonClick"));
    },

    _onWindowScroll: function()
    {
        var windowScroll = $(window).scrollTop();
        if (windowScroll >= PageUpDownScroller.SHOW_UP_BUTTON_HEIGHT)
        {
            this._prevPos = null;
            this._upButton.fadeIn("slow");
        }
        else
        {
            this._upButton.fadeOut("slow")
        }

        if (windowScroll >= PageUpDownScroller.HIDE_DOWN_BUTTON_HEIGHT)
        {
            this._downButton.fadeOut("slow");
        }
        else if (this._prevPos)
        {
            this._downButton.fadeIn("slow");
        }
    },

    _onUpButtonClick: function()
    {
        this._prevPos = $(window).scrollTop();
        $("html,body").animate({scrollTop: 0}, 0);

        this._downButton.fadeIn("slow");
    },

    _onDownButtonClick: function()
    {
        this._downButton.fadeOut("slow");
        $("html,body").animate({scrollTop: this._prevPos}, 0)
    }
},{
    SHOW_UP_BUTTON_HEIGHT: 350,
    HIDE_DOWN_BUTTON_HEIGHT: 250
});

$(function()
{
    new PageUpDownScroller();
});
