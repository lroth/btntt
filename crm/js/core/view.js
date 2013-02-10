define([], 
function() {

  var View = function() {
    this.isFirstRender = true;

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
        if(this.isFirstRender) {
          this.collection.fetch({
            success: _.bind(function(collection, response) {
              this.isFirstRender = false;
              this.$el.html(this.getHtml(response));
            }, this)
          })
        }
        else {
          this.$el.fadeOut();
          this.$el.html(this.getHtml(this.collection.toJSON()));
          this.$el.fadeIn();
        }
      }
    }
  };

  return View;
});