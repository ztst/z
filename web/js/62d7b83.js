var ShareLinks = Base.extend({

    _statUrl: null,

    constructor: function()
    {
        this.base();

        this._initLinks();
    },

    setStatUrl: function(url)
    {
        this._statUrl = url;
    },

    _initLinks: function()
    {
        $(".social_share_link").click(handler(this, "_onShareButtonClick"));
    },

    _onShareButtonClick: function(event)
    {
        var link = $(event.target);
        var url = link.attr('href');
        var isOpened = window.open(url, '', 'toolbar=0,status=0,width=626,height=436');

        var network = link.attr("id").replace("video_social_link_", "");
        this._sendStatistics(network);

        return !isOpened;
    },

    _sendStatistics: function(network)
    {
        var data = {
            "network" : network
        };

        $.post(this._statUrl, data, handler(this, '_onStatisticsSaved'), 'json');
    },

    _onStatisticsSaved: function(response)
    {
    }
});

$(function()
{
    var links = new ShareLinks();

    var videoName = $("#videoName").val();
    links.setStatUrl(Routing.generate('post_video_to_social_network', {'videoName': videoName}));
});
