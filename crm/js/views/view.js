define([], 
function() {

  var View = function() {
    this.getHtml = function(response) {
      var tmplCompiled = Handlebars.compile(this.tmpl);

      return tmplCompiled({
        data : response
      });
    },

    this.render = function() {
      if(!_.isUndefined(this.customRender)) { 
        this.customRender(); 
      }
      else {
        this.collection.fetch({
        success: _.bind(function(collection, response) {
          this.$el.append(this.getHtml(response));
        }, this)
      })
      }
    }
  };

  return View;
});