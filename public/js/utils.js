function copyToClipboard(text) {
    const aux = document.createElement("input");
    aux.setAttribute("value", text);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
}

function Scroll(id) {
    const target = $('#' + id);
    return {
        toTop: function() {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 1000);
        },
        toVisible: function() {
            var offset = target.offset().top - $(window).scrollTop();

            if (offset > window.innerHeight) {
                // Not in view so scroll to it
                $('html,body').animate({scrollTop: offset}, 1000);
            }
        }
    }
}

String.prototype.replaceAll = function(from, to) {
    return this.split(from).join(to);
};