var userid = $(".pub").attr("userID");
var authorid = $(".pub").attr("authorID");
if (userid) {
    if (userid != authorid) {
        setTimeout(plusView, 3000);
    }
}
var id = $(".pub").attr("pubID");
var isLiked = $(".pub").attr("isLiked");
var likes = Number($(".pub").attr("likes"));
var canBeDeleted = $(".pub").attr("canBeDeleted");
console.log(canBeDeleted);
if (canBeDeleted == "true") {
    $(".but").css({ "display": "block" })
}

if (isLiked == "true") {
    isLiked = true;
    $(".isLiked").html(`★ ${likes}`);
}
else if (isLiked == "false") {
    isLiked = false;
    $(".isLiked").html(`☆ ${likes}`);
}
else {
    isLiked = null;
    $(".errorL").html(`Для додавання фото до свого альбому зробіть <a href = '../login/login.php'>Вхід</a>`);
}

console.log(isLiked);
if (isLiked == null) {
    $(".buttons").css({ 'display': "none" })
}
function plusView() {
    $.ajax({
        url: '../publication/publicationAjax.php?pubID=' + id,
        method: "get",
        data: { event: "view" },
        success: function (data) {
            console.log(data);
        }
    });
    var views = Number($(".pub").attr("views")) + 1;
    $(".views").html(`<div class="views">Перегляди: <span class="innerH"> ${views}</div></span>`);

}
$(".like").click(function () {
    if (isLiked == null) {
        return;
    }
    isLiked = !isLiked;
    if (isLiked == true) {
        $.ajax({
            url: '../publication/publicationAjax.php?pubID=' + id,
            method: "get",
            data: {
                event: "like",
            },
            success: function (data) {
                console.log(data);
            }
        });
        likes++;
        $(".isLiked").html(`★ ${likes}`);
    }
    else {
        $.ajax({
            url: '../publication/publicationAjax.php?pubID=' + id,
            method: "get",
            data: {
                event: "unlike",
            },
            success: function (data) {
                console.log(data);
            }
        });
        likes--;
        $(".isLiked").html(`☆ ${likes}`);
    }
});

function download(src, name) {
    let link = document.createElement("a");
    link.setAttribute("href", src);
    link.setAttribute("download", name + ".png");
    link.click();
}

function copylink(link) {
    navigator.clipboard.writeText(link);
    document.getElementById("copyl").innerHTML = "Скопійовано!";
}