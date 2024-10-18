function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = element.scrollHeight + "px";
}
jQuery(function ($) {
    $(document).on("click", ".js_modal", function () {
        $($(this).data("modal")).modal({ showClose: false });
    });
    $(document).on("click", ".js_modal2", function () {
        $($(this).data("modal")).modal();
    });
    $(document).on("click", ".btn_toogle_pass", function (e) {
        $(this).toggleClass("show");
        $(this).children().toggleClass("show");
        let input = $(this).prev();
        input.attr(
            "type",
            input.attr("type") === "password" ? "text" : "password"
        );
    });
    $(document).on("click", "#btn_action", function () {
        $(this).parents("#aside").toggleClass("hide");
        $(this).parents(".p_post").find(".ctRight").toggleClass("hide");
    });

    $(document).on('click', '.close-modal', function() {
        $(this).closest('.jquery-modal').removeClass('blocker');
    });

    $(window).click(function () {
        $("*").removeClass("openSel");
    });
    // select event //
    $(document).on("click", ".selectEvent,.btn_dots", function (e) {
        e.stopPropagation();
    });
    $(document).on("click", ".selectEvent span,.btn_dots span", function (e) {
        // e.stopPropagation();
        $(document).find(".openSel").removeClass("openSel");
        $(this).parent().addClass("openSel");
    });
    $(document).on("click", ".selectEvent ul li", function (e) {
        $(this).addClass("active");
        $(this).siblings().removeClass("active");
        $(this).closest(".selectEvent").removeClass("openSel");
    });

    $(document).on("change", ".list_bd_bd input:checkbox", function (e) {
        var _length = $(this)
            .closest(".list_bd_bd")
            .find("input:checkbox:checked").length;
        if (_length >= 1) {
            if ($(".list_bd_head_l").find(".btn_del").length == 0) {
                $(".list_bd_head_l").append(
                    '<div class="btn_del">× 選択した記事を削除</div>'
                );
            }
        } else {
            $(".list_bd_head_l .btn_del").remove();
        }
    });
    // end

    // tags
    $(document).on("click", ".tags span", function (e) {
        e.preventDefault();
        $(this).toggleClass("active");
    });
    // end tags

    $(".textareaOnload").each(function (k, el) {
        auto_grow($(el)[0]);
    });

    $(document).on("click", ".list_bd_bd ul li", function (e) {
        // $("#modal01").modal({ showClose: true });
    });
    $(document).on("click", ".list_bd_bd ul li .checkbox", function (e) {
        e.stopPropagation();
    });
    $(document).on("click", ".list_bd_bd ul li .btn_eye", function (e) {
        e.stopPropagation();
    });
});

function showSpinner() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('spinner').style.display = 'block';
}

function hideSpinner() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('spinner').style.display = 'none';
}
