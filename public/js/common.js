window.addEventListener('DOMContentLoaded', function(){
    var windowWidth = $(window).width();
    var windowSm = 768;
    if (windowWidth <= windowSm) {
        openSideMenu();
    } else {
        closeSideMenu();
    }
});

//　サイドメニューの開閉
$(".menuOpenButton").click(function () {
    const sideMenu = $(".sideMenu");
    if (sideMenu.hasClass('open')) {
        openSideMenu();
    } else {
        closeSideMenu();
    }
});
function openSideMenu(){
    const sideMenu = $(".sideMenu");
    const meinContents = $(".meinContents");

    $('.menuContents').css({
        display : 'none',
    });
    sideMenu.removeClass('open')
    meinContents.removeClass('sideMenuOpen')
}
function closeSideMenu(){
    const sideMenu = $(".sideMenu");
    const meinContents = $(".meinContents");

    sideMenu.addClass('open')
    meinContents.addClass('sideMenuOpen')
    $('.menuContents').css({
        display : 'block',
    });
}

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

// フラッシュメッセージを削除する時
$('.flash-message-box-close').click(function() {
    $(this).parent().toggleClass('flash-message-hidden').slideUp();
})







