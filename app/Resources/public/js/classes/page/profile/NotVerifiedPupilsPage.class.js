var NotVerifiedPupilsPage = Base.extend({

    _currentPupil: null,

    constructor: function()
    {
        this.base();

        var that = this;
        $(".approve-user-link").click(function()
        {
            var id = $(this).closest("li").attr("id").replace("userContainer", "");
            that._onApproveLinkClick(id);
        });
        $(".delete-user-link").click(function()
        {
            var id = $(this).closest("li").attr("id").replace("userContainer", "");
            that._onDeleteLinkClick(id);
        });
        $(".approve-all-user-link").click(handler(this, "_onApproveAllClick"));

        $(".show-more-user-link").click(handler(this, "_onShowMoreUsersClick"));

        this._initOpenUsersLinks();
        this._initUserProfileForm();
    },

    _onShowMoreUsersClick: function()
    {
        var currentIndex = $(".not-verified-users").children("li:visible:last").index();
        var nextIndex = currentIndex + NotVerifiedPupilsPage.SHOW_MORE_PUPILS_COUNT;
        $(".not-verified-users li:lt(" + nextIndex + ")").removeClass("hidden");
        this._updateShowMoreButton();
    },

    _updateShowMoreButton: function()
    {
        if ($(".not-verified-users li:visible").is(":last-child"))
        {
            $(".show-more-link:visible").remove();
        }
    },

    _onApproveAllClick: function()
    {
        var ids = [];
        $("[id^=userContainer]:visible").each(function()
        {
            var id = $(this).attr("id").replace("userContainer", "");
            ids.push(id);
        });

        var data = {ids: ids};
        var url = Routing.generate("approve_users");

        $.post(url, data, handler(this, "_onUserStatusChanged"), "json");
    },

    _onDeleteLinkClick: function(id, reason)
    {
        var ids = [id];
        var data = {ids: ids};
        if (reason !== undefined)
        {
            data.reason = reason;
        }
        var url = Routing.generate("delete_users");

        $.post(url, data, handler(this, "_onUserStatusChanged"), "json");
    },

    _onApproveLinkClick: function(id)
    {
        var ids = [id];
        var data = {ids: ids};
        var url = Routing.generate("approve_users");

        $.post(url, data, handler(this, "_onUserStatusChanged"), "json");
    },

    _onUserStatusChanged: function(response)
    {
        if (response.success)
        {
            for (var i in response.ids)
            {
                var userId = response.ids[i];
                $("#userContainer" + userId).remove();

                $("#videoTab").click();
                var userProfileContent = $("#userProfileContent" + userId);
                if (userProfileContent.length)
                {
                    userProfileContent.remove();
                    $(".pupil-tab").addClass("hidden");
                }

                this._decrementCountQuestions($(".tab-header .list-count-container"));
                var hasQuestions = this._decrementCountUsers($(".not-verified-pupils-count"));

                if (!hasQuestions)
                {
                    $("#openUserProfile" + userId).closest("li").remove();
                    $("#userTab").click();
                }
                else
                {
                    $(".not-verified-users li:hidden:first").removeClass("hidden");
                    this._updateShowMoreButton();
                }
            }
        }
    },

    _decrementCountUsers: function(elem)
    {
        var text = elem.text();
        var count = text.replace("(+", "").replace(")", "");
        --count;
        text = (count > 0) ? "+" + count : "";
        elem.text(text);

        return count > 0;
    },

    _initOpenUsersLinks: function()
    {
        var that = this;
        $(".show-user-profile-link").click(function()
        {
            var link = $(this);
            var userId = link.attr("id").replace("openUserProfile", "");
            var pupilTab = $("#pupilTab");
            $(".pupil-tab").removeClass("hidden");
            pupilTab.click();

            if (that._currentPupil != userId)
            {
                $(".profile-container").addClass("hidden");
                $(".profile-preloader").removeClass("hidden");

                var url = Routing.generate('get_user_profile_ajax', {'userId': userId});
                $.post(url, null, handler(that, "_onUserProfileLoaded"), "json");
            }
            that._currentPupil = userId;
        });
    },

    _onUserProfileLoaded: function(response)
    {
        if (response.success)
        {
            $(".profile-preloader").addClass("hidden");
            var profileContainer = $(".profile-container");
            profileContainer.removeClass("hidden");
            profileContainer.html(response.html);

            this._initUserProfileForm();
        }
    },

    _initUserProfileForm: function()
    {
        var id = $("#userProfileId").val();
        var approveLink = $("#approveUser" + id);
        var deleteLink = $(".ban-with-reason-button");
        var showDeleteDropdownLink = $(".ban-with-reason-dropdown-link");

        var that = this;

        approveLink.click(function(){
            that._onApproveLinkClick(id);
        });

        deleteLink.click(function(){
            var reason = $(this).attr("id").replace("reason", "");
            that._onDeleteLinkClick(id, reason);
        });

        showDeleteDropdownLink.click(function(){
            $(this).next(".ban-reasons-list-container ").toggleClass("hidden");
        });
    }
},{
    SHOW_MORE_PUPILS_COUNT: 10
});

$(function()
{
    new NotVerifiedPupilsPage();
});
