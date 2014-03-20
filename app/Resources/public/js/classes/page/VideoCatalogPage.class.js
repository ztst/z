var VideoCatalogPage = Base.extend({

    _chapterList: null,

    constructor: function()
    {
        this.base();

        this._chapterList = new ChapterList();
    }
});

$(function(){
    new VideoCatalogPage();
});
