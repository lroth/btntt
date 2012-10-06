define('widgets/widget.fbapi', [], function () {

    var fbInit = function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/pl_PL/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        };

    var init = function() {
        fbInit(document, 'script', 'facebook-jssdk');
    }

    return { init: init };
});