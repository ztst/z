var ShareVideoLinks = BaseShareLinks.extend({
});

$(function()
{
    var links = new ShareVideoLinks("video_social_link_");

    var videoName = $("#videoName").val();
    links.setStatUrl(Routing.generate('post_video_to_social_network', {'videoName': videoName}));
});
