var ShowThreadPage = Base.extend({

    _deleteMessageUrl: null,

    constructor: function ()
    {
        this.base();

        var that = this;
        $(".messenger_thread_message .delete_message_link").click(function (){
            var link = $(this);
            link.parent().hide();
            var id = link.attr("id");

            var data = {
                'messageId': id
            };

            $.post(that._deleteMessageUrl, data, handler(that, '_onDeleteMessageComplete'), 'json');

            return false;
        });
    },

    setDeleteMessageUrl: function (url)
    {
        this._deleteMessageUrl = url;
    },

    _onDeleteMessageComplete: function (response)
    {
    }
});

$(function ()
{
    var page = new ShowThreadPage();

    page.setDeleteMessageUrl(Routing.generate('delete_message'));
});
