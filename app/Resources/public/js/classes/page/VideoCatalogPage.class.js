var VideoCatalogPage = Base.extend({

    constructor: function()
    {
        this.base();

        $(".chapter-description-container a").click(function(){
            var link = $(this);
            var id = link.attr("id").replace("show_chapter_", "");

            $(".chapter-videos").addClass("hidden");
            $("#chapter_videos_" + id).removeClass("hidden");
            $(".chapter-description-container").removeClass("selected");
            link.parent().addClass("selected");

            $(".chapter-videos-count").html($(".chapter-videos-block-container li:visible").length);
        });
    }
});

$(function(){
    new VideoCatalogPage();
});
