var SearchPage = Base.extend({

    constructor: function()
    {
        this.base();

        $(".chapter-description-container .chapter-link").click(function(){
            var link = $(this);
            var id = link.attr("id").replace("show_chapter_", "");

            $(".search-video-result-container").addClass("hidden");
            $(".search-video-chapter-" + id).removeClass("hidden");
            $(".chapter-description-container").removeClass("selected");
            link.closest(".chapter-description-container").addClass("selected");
        });
    }
});

$(function(){
    var searchPage = new SearchPage();
});

var VideosList = Base.extend({

    constructor: function()
    {
        this.base();
        var videoName = $(".video-title");
        var videoPreview = $(".chapter-video-preview-container");

        videoName.ellipsis({
            row: 2
        });

        videoName.mouseover(function(){
            $(this).addClass("over");
            jQuery($(this).parents().get(1)).find(".over-play-sign").show();
        });

        videoName.mouseout(function(){
            $(this).removeClass("over");
            jQuery($(this).parents().get(1)).find(".over-play-sign").hide();
        });

        videoPreview.mouseover(function(){
            $(this).find(".over-play-sign").show();
            jQuery($(this).parents().get(1)).find(".video-title").addClass("over");
        });

        videoPreview.mouseout(function(){
            $(this).find(".over-play-sign").hide();
            jQuery($(this).parents().get(1)).find(".video-title").removeClass("over");
        });
    }
});

$(function(){
    var videosList = new VideosList();
});