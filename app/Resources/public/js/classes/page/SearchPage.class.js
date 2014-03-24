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

        $(".custom-select .dropdown-menu li").click(function(){
            $($(this).parent().parent()).find(".dropdown-toggle .custom-select-label").html($(this).find("a").html());
        });
    }
});

$(function(){
    var searchPage = new SearchPage();
});
