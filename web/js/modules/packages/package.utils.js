define('packages/package.utils', ['underscore'], function() {
    _.mixin({
        captFirst: function(str){
            str = str == null ? '' : String(str);
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    });
});
