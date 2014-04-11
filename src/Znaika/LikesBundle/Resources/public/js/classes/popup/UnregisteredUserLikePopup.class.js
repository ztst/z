var UnregisteredUserLikePopup = Base.extend({

    constructor: function()
    {
        this.base();
        this._initButtons();
    },

    open: function()
    {
        $.magnificPopup.open({
            items: {
                src: '#unregisteredUserLikePopup',
                type: 'inline'
            }
        }, 0);
    },

    _initButtons: function()
    {
        $("#registerForLikeButton").click(handler(this, "_onRegisterButtonClick"));
        $("#loginForLikeButton").click(handler(this, "_onLoginButtonClick"));
    },

    _onRegisterButtonClick: function()
    {
        $.magnificPopup.close();
        $(".registration-button").click();

        return false;
    },

    _onLoginButtonClick: function()
    {
        $.magnificPopup.close();
        $(".login-button").click();

        return false;
    }
},{
    _instance: null,

    getInstance: function()
    {
        if (!UnregisteredUserLikePopup._instance)
        {
            UnregisteredUserLikePopup._instance = new UnregisteredUserLikePopup();
        }

        return UnregisteredUserLikePopup._instance;
    }
});
