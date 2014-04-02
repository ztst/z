var BaseShareLinks = Base.extend({

    _statUrl: null,
    _id: null,

    constructor: function(id)
    {
        this.base();

        this._id = id;
        this._initLinks();
    },

    setStatUrl: function(url)
    {
        this._statUrl = url;
    },

    _initLinks: function()
    {
        $("a[id^=" + this._id + "]").click(handler(this, "_onShareButtonClick"));
    },

    _onShareButtonClick: function(event)
    {
        var link = $(event.target);
        var url = link.attr('href');
        var isOpened = window.open(url, '', 'toolbar=0,status=0,width=626,height=436');

        var network = link.attr("id").replace(this._id, "");
        this._sendStatistics(network);

        return !isOpened;
    },

    _sendStatistics: function(network)
    {
        if (!this._statUrl)
        {
            return;
        }

        var data = {
            "network" : network
        };

        $.post(this._statUrl, data, handler(this, '_onStatisticsSaved'), 'json');
    },

    _onStatisticsSaved: function(response)
    {
    }
});
