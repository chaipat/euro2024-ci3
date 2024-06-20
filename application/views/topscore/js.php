$("ul#tab-salvo li").click(function(){
    if (!$(this).hasClass("tab-active")) {
        var tabNum = $(this).index();
        var nthChild = tabNum+1;
        $("ul#tab-salvo li.tab-active").removeClass("tab-active");
        $(this).addClass("tab-active");
        $("ul#tab-data li.tab-active").removeClass("tab-active");
        $("ul#tab-data li:nth-child("+nthChild+")").addClass("tab-active");
    }
});