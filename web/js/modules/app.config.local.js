define('app.config', ['app.config.local'], function (appConfigLocal) {
    var config = {
        bundles : {

        },

        request : {
            'statusCode' : {
                400 : function() {},
                403 : function() {},
                501 : function() {}
            },

            httpCode : {
                400  : 'Bad request - missed parameters',
                403  : 'You are not authorized',
                501  : 'Requested method is not implemented'
            }
        },

        basePath : (requireConfig && requireConfig.basePath) ? requireConfig.basePath : null,

        consoleDebug   : true,
        templatesPath  : 'templates',
        templatesExt   : 'tmpl'
    };

    //Extended by $, because _ doesn't have reccursive extend
    return $.extend(true, {}, config, appConfigLocal);
});