// variables
var clickAllowed = true,
	curr = 1,
	elemsSlides = document.querySelectorAll('.p>div'),
	slideCount = elemsSlides.length,
	elemPrev = document.querySelector('.p>nav>:first-child'),
	elemNext = document.querySelector('.p>nav>:last-child'),
	elemCurrent = document.querySelector('.p>nav>span>:first-child'),
	elemCount = document.querySelector('.p>nav>span>:last-child');

// functions
function updateCurrent(change) {
	curr += change;
	elemCurrent.innerHTML = curr;
}
function delayedReenable() {
	setTimeout(function(){
		clickAllowed = true;
	}, 250)
}

// events
elemPrev.onclick = function(){
	if (curr > 1 && clickAllowed) {
		clickAllowed = false;
		elemsSlides[curr - 1].style.left = '804px';
		elemsSlides[curr - 2].style.left = '0px';
		updateCurrent(-1);
		delayedReenable();
	}
};
elemNext.onclick = function(){
	if (curr < slideCount && clickAllowed) {
		clickAllowed = false;
		elemsSlides[curr - 1].style.left = '-804px';
		elemsSlides[curr].style.left = '0px';
		updateCurrent(1);
		delayedReenable();
	}
};

// runtime
elemCurrent.innerHTML = curr;
elemCount.innerHTML = slideCount;