//This config is independent from project placement, paths are relative
var requireConfig = {
    waitSeconds : 7,
    urlArgs     : "bust=" + (new Date()).getTime(),
    baseUrl     : "js/modules",
    basePath    : document.URL.split('web')[0] + 'web/',

    //Alias for non-modules
    paths : {
        'jquery'              : '../vendors/jquery/jquery-1.8.0.min',
        'jquery-ui'           : '../vendors/jquery/jquery-ui-1.8.23.custom.min',
        'underscore'          : '../vendors/underscore-min',
        'd3js'                : '../vendors/d3',
        'bootstrap-modal'     : '../vendors/twitter-bootstrap/bootstrap-modal',
        'bootstrap-typeahead' : '../vendors/twitter-bootstrap/bootstrap-typeahead',
        'bootstrap-tooltip'   : '../vendors/twitter-bootstrap/bootstrap-tooltip',
        'bootstrap-popover'   : '../vendors/twitter-bootstrap/bootstrap-popover',
        'datepicker'          : '../vendors/datepicker/js/bootstrap-datepicker',
    },

    initConfig : function () {
        this.baseUrl = this.basePath + this.baseUrl;
        return this;
    }
}.initConfig();

require.config(requireConfig);

require(['packages/package.jquery', 'packages/package.utils'], function($, _) {
    require(['app', 'bootstrap-tooltip'], function (App) {
        //Add app object to global context, to handle from anywhere
        window.miApp = new App();

        //Bind onReady event with app.init() or if page is already loaded, just use app.init()
        (document.readyState !== 'complete') ? ($(document).ready(miApp.init)) : (miApp.init());
    });
});
