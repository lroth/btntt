define('packages/package.jquery', ['jquery'], function($) {
    var $ = jQuery;

    require(['jquery-ui'], function(){});

    return $;
});