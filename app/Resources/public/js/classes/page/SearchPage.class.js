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

        $('.dropdown-toggle').dropdown();

        $(".filter .dropdown-menu li").click(function(){
            $($(this).parents().get(1)).find(".dropdown-toggle .filter-label").html($(this).find("a").html());
        });
    }
});

$(function(){
    var searchPage = new SearchPage();
});
