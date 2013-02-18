define([], 
function() {

  var View = function() {
    // first render flag, if it's not first render we can
    // execute `render` in other way
    this.isFirstRender = true;

    this.getHtml = function(response) {
      // create handlebar template object
      var tmplCompiled = Handlebars.compile(this.tmpl);

      // return compiled html, by parsing passed json
      return tmplCompiled({
        data : response
      });
    },

    // render view
    this.render = function() {
      // if in child object we have custom render, use it instead
      if(!_.isUndefined(this.customRender)) { 
        this.customRender(); 
      }
      else {
        // fetch data and add new element to DOM
        if(this.isFirstRender) {
          this.collection.fetch({
            success: _.bind(function(collection, response) {
              this.isFirstRender = false;
              this.$el.html(this.getHtml(response));
            }, this)
          })
        }
        else {
          // change only `this.$el` html content
          this.$el.fadeOut();
          this.$el.html(this.getHtml(this.collection.toJSON()));
          this.$el.fadeIn();
        }
      }
    }
  };

  return View;
});