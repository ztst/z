var MessagesPage = Base.extend({

    _recipient: null,
    _sendMessageForm: null,
    _messageSending: null,
    _showPrevMessagesLink: null,

    _searchTab: null,

    constructor: function()
    {
        this.base();

        this._initOpenThreadLinks();

        this._loadCurrentThread();

        this._initFilterSelect();

        this._searchTab = new SearchUserTab();
        this._searchTab.addListener(SearchUserTab.event.OPEN_THREAD, this, this._openThread);

        $(document).find(".user-profile-page-list .message-body").ellipsis({
            row: 2
        });
        $(document).find(".user-profile-page-list .message-user-name").ellipsis({
            row: 3
        });
    },

    _initFilterSelect: function()
    {
        var gradeSelect = $("#messagesFilter");
        gradeSelect.selectbox();
        gradeSelect.change(function(){
            window.location.href = gradeSelect.val();
        });
    },

    _loadCurrentThread: function()
    {
        var recipientId = $("#recipientId").val();
        if (recipientId)
        {
            this._loadThread(recipientId);
        }
    },

    _loadThread: function(recipientId)
    {
        var url = Routing.generate('get_thread_ajax', {'userId': recipientId});
        $.post(url, null, handler(this, "_onThreadLoaded"), "json");
    },

    _initOpenThreadLinks: function()
    {
        var that = this;
        $(".open-thread-link").click(function()
        {
            var link = $(this);
            link.removeClass("unread-thread");
            var userId = link.attr("id").replace("openThreadLink", "");
            that._openThread(userId);
        });
    },

    _openThread: function(userId)
    {
        var watchThreadsTab = $("#watchThreadsTab");
        watchThreadsTab.closest("li").removeClass("hidden");
        watchThreadsTab.click();
        if (this._recipient != userId)
        {
            $(".thread-container").addClass("hidden");
            $(".thread-preloader").removeClass("hidden");
            this._loadThread(userId);
        }
        this._recipient = userId;
    },

    _onThreadLoaded: function(response)
    {
        if (response.success)
        {
            $(".thread-preloader").addClass("hidden");
            var threadContainer = $(".thread-container");
            threadContainer.removeClass("hidden");
            threadContainer.html(response.html);

            this._initMessageForm();

            this._showPrevMessagesLink = $("#showPrevMessagesLink");
            this._showPrevMessagesLink.click(handler(this, "_onShowPrevMessagesLinkClick"));
        }
    },

    _initMessageForm: function()
    {
        this._sendMessageForm = new SendMessageForm("sendMessageForm");
        this._sendMessageForm.addListener(BaseForm.event.SUBMITTED, this, this._onSendMessage);
    },

    _onSendMessage: function()
    {
        $(".not-self-message").removeClass("not-read-message");
        if (this._messageSending)
        {
            return false;
        }
        this._messageSending = true;

        var url = this._sendMessageForm.getAction();
        $.ajax({
            type: "POST",
            url: url,
            data: this._sendMessageForm.serialize(),
            success: handler(this, "_onMessageSent")
        });

        return false;
    },

    _onMessageSent: function(response)
    {
        this._messageSending = false;
        if (response.success)
        {
            $(".send-message-form-container").before(response.html);
            $("#openThreadLink" + response.participantId).remove();

            var threadList = $(".user-profile-page-list");
            threadList.prepend(response.threadHtml);
            threadList.find("li").removeClass("first").first().addClass("first");

            $(".count-threads").text(threadList.find("li").length);
            this._decrementCountThreads($(".not-read-threads-count"));

            this._sendMessageForm.clear();
            this._initOpenThreadLinks();

            $(".empty-pupils-message-container").addClass("hidden");
            $(".not-empty-pupils-container").removeClass("hidden");
        }
        else
        {
            alert("Сообщение не отправлено. Попробуйте еще раз.");
        }
    },

    _decrementCountThreads: function(elem)
    {
        var text = elem.text();
        var count = text.replace("(+", "").replace(")", "");
        --count;
        text = (count > 0) ? "+" + count : "";
        elem.text(text);

        return count > 0;
    },

    _onShowPrevMessagesLinkClick: function()
    {
        $.post($("#showPrevMessagesUrl").val(), null, handler(this, "_onPrevMessagesLoaded"), "json");
        $(".comments-preloader").removeClass("hidden");
        this._showPrevMessagesLink.closest(".show-more-link").addClass("hidden");

        return false;
    },

    _onPrevMessagesLoaded: function(response)
    {
        if (response.success)
        {
            $("#prevMessagesContainer").html(response.html);
            this._showPrevMessagesLink.closest(".show-more-link").remove();
            $(".comments-preloader").addClass("hidden");
        }
    }
},{
    SHOW_MORE_PUPILS_COUNT: 10
});

$(function()
{
    new MessagesPage();
});
