
var s 		= 0,									// section (starts on index)
	sects	= ["index","processor","memory","storage","graphics","input","output","network","sound","motherboard","cooling","power","case","software","images"];
	fromUrl	= sects.indexOf(window.location.href.split("#")[1]);

function init() {
	$("td>div:not(:first-child)").fadeOut(0);
	$("#nav a").eq(0).css({"background":"#eee","font-style":"italic"});

	if (fromUrl > 0) {
		$("#nav a").eq(fromUrl).trigger("click");
	}
}

$(function(){
	$("a").click(function(){
		var href	= $(this).attr("href").split("#")[1],	// name of target section
			sn		= sects.indexOf(href),					// index of new section
			sin		= $("td>div").eq(sn),					// section to fade in
			sout	= $("td>div").eq(s);					// section to fade out

		$("#nav a").eq(s).css({"background":"","font-style":""});
		$("#nav a").eq(sn).css({"background":"#eee","font-style":"italic"});
		sout.fadeOut(100,function(){
			sin.fadeIn(250);
		});

		s = sn;									// update section index variable
	});

	$("body").keydown(function(e){
		if (e.keyCode == 39) {
			$("#nav a").eq(s + 1).trigger("click");
		} else if (e.keyCode == 37 && s != 0) {
			$("#nav a").eq(s - 1).trigger("click");
		}
	});

	init();
});