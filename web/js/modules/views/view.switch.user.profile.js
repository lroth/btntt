define('views/view.switch.user.profile', [], function () {
    var userSwitchwer = {};

    userSwitchwer.init = function(el) {
        var switches = $(el).find('a.switch_content'),
            gallery  = $(el).find('ul.sharing_photo_list'),
            comments = $(el).find('table.tab_comments_list');

        switches.click(function(){
            switches.removeClass('active');
            gallery.toggle();
            comments.toggle();

            $(this).addClass('active');
            return false;
        });
    };

    return {
        init : userSwitchwer.init
    };
});