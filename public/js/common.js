
//　サイドメニューの開閉
$(".menuOpenButton").click(function () {
    const sideMenu = $(".sideMenu");
    const meinContents = $(".meinContents");

    if (sideMenu.hasClass('open')) {
        $('.menuContents').css({
            display : 'none',
        });
        sideMenu.removeClass('open')
        meinContents.removeClass('sideMenuOpen')
    } else {
        sideMenu.addClass('open')
        meinContents.addClass('sideMenuOpen')
        $('.menuContents').css({
                display : 'block',
        });
    }
});


// サイドメニューのコンテンツをクリックした時
$(".parentMenu").click(function() {
    const id = $(this).attr('id')
    const childId = id.replace('parentMenu', 'childMenu')
    if ($(this).hasClass('open')) {
        $(this).removeClass('open')
        $('#' + childId).slideUp(200);
    }else{
        $(this).addClass('open')
        $('#' + childId).slideToggle(200, function() {});
    }
})




