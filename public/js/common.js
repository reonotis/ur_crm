window.addEventListener('DOMContentLoaded', function () {
    if ($(window).width() <= 768) {
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

function openSideMenu() {
    const sideMenu = $(".sideMenu");
    const meinContents = $(".meinContents");

    $('.menuContents').css({
        display: 'none',
    });
    sideMenu.removeClass('open')
    meinContents.removeClass('sideMenuOpen')
}

function closeSideMenu() {
    const sideMenu = $(".sideMenu");
    const meinContents = $(".meinContents");

    sideMenu.addClass('open')
    meinContents.addClass('sideMenuOpen')
    $('.menuContents').css({
        display: 'block',
    });
}

// サイドメニューのコンテンツをクリックした時
$(".parentMenu").click(function () {
    const id = $(this).attr('id')
    const childId = id.replace('parentMenu', 'childMenu')
    if ($(this).hasClass('open')) {
        $(this).removeClass('open')
        $('#' + childId).slideUp(200);
    } else {
        $(this).addClass('open')
        $('#' + childId).slideToggle(200, function () {
        });
    }
})

// フラッシュメッセージを削除する時
$('.flash-message-box-close').click(function () {
    $(this).parent().toggleClass('flash-message-hidden').slideUp();
})

// コンフィルムダイアログ
function confirmDialog(msg) {
    return (window.confirm(msg));
}

/**
 * @param {string} toFormat - 返却する日時のフォーマット
 * @param {string|null} tmpDate
 * @param {string|null} fromFormat
 * @returns {Date|null}
 */
function changeFormat(toFormat, tmpDate = "", fromFormat = "") {

    const date = createDate(tmpDate, fromFormat);
    if (!date) return;

    // 返却する日付のフォーマットを生成
    toFormat = toFormat.replace(/YYYY/, date.getFullYear());
    toFormat = toFormat.replace(/mm/g, ('0' + (date.getMonth() + 1)).slice(-2));
    toFormat = toFormat.replace(/dd/g, ('0' + date.getDate()).slice(-2));
    toFormat = toFormat.replace(/HH/g, ('0' + date.getHours()).slice(-2));
    toFormat = toFormat.replace(/ii/g, ('0' + date.getMinutes()).slice(-2));
    toFormat = toFormat.replace(/ss/g, ('0' + date.getSeconds()).slice(-2));
    toFormat = toFormat.replace(/SSS/g, ('00' + date.getMilliseconds()).slice(-3));

    return toFormat;
}

function createDate(tmpDate, fromFormat) {
    //何も渡されない場合は本日を返す
    if (tmpDate == null) {
        return new Date();
    }

    // フォーマットの指示がある場合はそのフォーマットで日時を作成する
    if (fromFormat) {
        let today = new Date();
        let year_str = today.getFullYear();
        let month_str = today.getMonth() + 1;
        let day_str = today.getDate()
        let hour_str = today.getHours();
        let minute_str = today.getMinutes();
        let second_str = today.getSeconds();

        // 年
        if (fromFormat.indexOf('YYYY') >= 0) {
            const idx_year = fromFormat.indexOf('YYYY');
            year_str = tmpDate.substring(idx_year, idx_year + 4)
        }

        // 月
        if (fromFormat.indexOf('mm') >= 0) {
            const idx_month = fromFormat.indexOf('mm');
            month_str = tmpDate.substring(idx_month, idx_month + 2)
        }

        // 日
        if (fromFormat.indexOf('dd') >= 0) {
            const idx_day = fromFormat.indexOf('dd');
            day_str = tmpDate.substring(idx_day, idx_day + 2)
        }

        // 時
        if (fromFormat.indexOf('HH') >= 0) {
            const idx_hour = fromFormat.indexOf('HH');
            hour_str = tmpDate.substring(idx_hour, idx_hour + 2);

        }

        // 分
        if (fromFormat.indexOf('ii') >= 0) {
            const idx_min = fromFormat.indexOf('ii');
            minute_str = tmpDate.substring(idx_min, idx_min + 2)
        }

        // 秒
        if (fromFormat.indexOf('ss') >= 0) {
            const idx_sec = fromFormat.indexOf('ss');
            second_str = tmpDate.substring(idx_sec, idx_sec + 2)
        }

        let date = new Date(year_str, month_str - 1, day_str, hour_str, minute_str, second_str);

        if (date !== "Invalid Date") return date;
    } else {
        // フォーマットの指示がないので、そのまま日付に変換する。
        let date = new Date(tmpDate);
        if (date !== "Invalid Date") return date;
    }
    alert('日付を生成できません');
}



