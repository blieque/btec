/* menu compatibility for older ios' mobile safari */
if (navigator.userAgent.match(/iPhone|iPod|iPad/)) {

    // element section globals
    elemCurtain = document.getElementById('mc');
    elemMask = document.getElementById('mtm');
    elemHeader = document.getElementsByTagName('header')[0];
    elemNav = document.getElementsByTagName('nav')[0];

    // menu starts closed
    menuOpen = false;

    // show curtain
    elemCurtain.style.height = '2000px';

    // menu open event
    elemHeader.onmousedown = function() {
        if (!menuOpen) {
            menuOpen = true;
            elemHeader.className = 'focus';

            // older versions of ios don't support css' ~ selector
            elemTempStyle = document.createElement('style');
            document.body.appendChild(elemTempStyle);
            elemTempStyle.innerHTML = '#mtm{width:4.4em;left:0}#mc{left:0;opa' +
            'city:.7;-webkit-transition:opacity .15s;transition:opacity .15s}';
        }
    };

    // menu close event
    elemCurtain.onclick =
    elemMask.onclick = function() {
        if (menuOpen) {
            menuOpen = false;
            elemHeader.className = '';

            elemTempStyle.parentNode.removeChild(elemTempStyle);
        }
    };

}
