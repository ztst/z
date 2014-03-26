var NotVerifiedCommentsPage = Base.extend({

    constructor: function()
    {
        this.base();

        this._initOpenCommentsLinks();
        this._initCommentButtons();
    },

    _initCommentButtons: function()
    {
        var that = this;
        $(".approve-comment-link").click(function()
        {
            var id = $(this).closest("li").attr("id").replace("commentContainer", "");
            that._onApproveLinkClick(id);
        });
        $(".delete-comment-link").click(function()
        {
            var id = $(this).closest("li").attr("id").replace("commentContainer", "");
            that._onDeleteLinkClick(id);
        });
        $(".approve-all-comment-link").click(handler(this, "_onApproveAllClick"));

        $(".show-more-comment-link").click(handler(this, "_onShowMoreCommentsClick"));
    },

    _onShowMoreCommentsClick: function()
    {
        var container = $("[id^=videoComments]:visible");
        var currentIndex = container.find(".video-comments").children("li:visible:last").index();
        var nextIndex = currentIndex + NotVerifiedCommentsPage.SHOW_MORE_COMMENTS_COUNT;
        container.find(".video-comments li:lt(" + nextIndex + ")").removeClass("hidden");
        this._updateShowMoreButton();
    },

    _updateShowMoreButton: function()
    {
        if ($("[id^=videoComments]:visible").find(".video-comments li:visible").is(":last-child"))
        {
            $(".show-more-comment-link:visible").remove();
        }
    },

    _onApproveAllClick: function()
    {
        var ids = [];
        $("[id^=commentContainer]:visible").each(function()
        {
            var id = $(this).attr("id").replace("commentContainer", "");
            ids.push(id);
        });

        var data = {ids: ids};
        var url = Routing.generate("approve_comments");

        $.post(url, data, handler(this, "_onCommentStatusChanged"), "json");
    },

    _onDeleteLinkClick: function(id)
    {
        var ids = [id];
        var data = {ids: ids};
        var url = Routing.generate("delete_comments");

        $.post(url, data, handler(this, "_onCommentStatusChanged"), "json");
    },

    _onApproveLinkClick: function(id)
    {
        var ids = [id];
        var data = {ids: ids};
        var url = Routing.generate("approve_comments");

        $.post(url, data, handler(this, "_onCommentStatusChanged"), "json");
    },

    _onCommentStatusChanged: function(response)
    {
        if (response.success)
        {
            var videoId = response.videoId;

            for (var i in response.ids)
            {
                var commentId = response.ids[i];
                $("#commentContainer" + commentId).remove();
                var openVideoCommentLink = $("#openVideoCommentLink" + videoId);
                this._decrementCountComments($("#videoComments" + videoId + " .not-verified-comments-count"));
                this._decrementCountComments($(".profile-sidebar-menu .not-verified-comments-count"));
                var hasQuestions = this._decrementCountComments(openVideoCommentLink.next(".not-verified-comments-count"));

                if (!hasQuestions)
                {
                    openVideoCommentLink.closest("li").remove();
                    $(".comments-tab").addClass("hidden");
                    $("#videoTab").click();
                }
                else
                {
                    $("#videoComments" + videoId + " .video-comments li:hidden:first").removeClass("hidden");
                    this._updateShowMoreButton();
                }
            }
        }
    },

    _decrementCountComments: function(elem)
    {
        var text = elem.text();
        var count = text.replace("(+", "").replace(")", "");
        --count;
        text = (count > 0) ? "+" + count : "";
        elem.text(text);

        return count > 0;
    },

    _initOpenCommentsLinks: function()
    {
        var that = this;
        $(".open-video-comment-link").click(function()
        {
            $(".profile-preloader").removeClass("hidden");
            var link = $(this);
            var videoId = link.attr("id").replace("openVideoCommentLink", "");
            var countCommentText = link.find(".not-verified-comments-count").html();
            var commentTab = $("#commentTab");
            commentTab.find(".not-verified-comments-count").html(countCommentText);
            $(".comments-tab").removeClass("hidden");
            $(".video-comments-container").addClass("hidden");
            commentTab.click();

            var commentsContainer = $("#videoComments" + videoId);
            if (commentsContainer.length)
            {
                $(".profile-preloader").addClass("hidden");
                commentsContainer.removeClass("hidden");
            }
            else
            {
                var url = Routing.generate('get_video_not_verified_comments_ajax', {'videoId': videoId});
                $.post(url, null, handler(that, "_onVideoCommentsLoadedLoaded"), "json");
            }
        });
    },

    _onVideoCommentsLoadedLoaded: function(response)
    {
        if (response.success)
        {
            $(".comment-container").append(response.html);
            $("#videoComments" + response.videoId).removeClass("hidden");
            $(".profile-preloader").addClass("hidden");
            this._initCommentButtons();
        }
    }
},{
    SHOW_MORE_COMMENTS_COUNT: 10
});

$(function()
{
    new NotVerifiedCommentsPage();
});
