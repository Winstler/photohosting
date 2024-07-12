function getInnerHeight() {
	return window.innerHeight;
}
function getInnerWidth() {
	return window.innerWidth;
}
function getScrollTop() {
	return $(window).scrollTop();
}
function getOffsetTop(e) {
	return e.offset().top;
}
function getElementHeight(e) {
	return e.height();
}
var block_show = false;

function isElementSeen(e) {
	if (getOffsetTop(e) - (getScrollTop() + getInnerHeight()) <= 0 && getScrollTop() < (getOffsetTop(e) + getElementHeight(e))) {
		return true;
	}
	else {
		return false;
	}
}
const target = $("#showmore");
const gallery = document.querySelector(".gallery");
const curUser = gallery.getAttribute("currentuser");
console.log(curUser);


function scrollMore() {

	if (block_show) {
		return false;
	}

	if (isElementSeen(target)) {
		var page = target.attr('data-page');
		page++;
		block_show = true;

		$.ajax({
			url: '../mainPage/ajax.php?page=' + page,
			method: "get",
			dataType: 'html',
			success: function (data) {
				$('.gallery').append(data);
				block_show = false;
			}
		});

		target.attr('data-page', page);
		if (page == target.attr('data-max')) {
			target.remove();
		}
		if (target.attr('data-max') == 0) {
			target.remove();
			$("body").append("<div class='container'><div class= 'sucess main'>Тут нічого немає</div></div>")
		}
	}
}


$(window).scroll(function () {
	scrollMore();
});

$(document).ready(function () {
	if (target.attr('data-max') == 1 || target.attr('data-max') == 0) {
		target.remove();
	}
});

function download(src, name) {
	let link = document.createElement("a");
	link.setAttribute("href", src);
	link.setAttribute("download", name + ".png");
	link.click();
}

