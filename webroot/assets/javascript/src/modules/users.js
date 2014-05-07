window.App = window.App || {};
(function(exports, Backbone, Handlebars) { 
  "use strict"; 
  
  exports.User = Backbone.Model.extend({
  });
  
  exports.UsersCollection = Backbone.Collection.extend({
    model: exports.User
  });
  exports.ConnectedUsersView = Backbone.View.extend({
    el: '#connected-users',    
    template: Handlebars.compile('<li>{{id}}</li>'),
    initialize: function() {
      this.listenTo(this.collection, 'add', this.render);
      this.listenTo(this.collection, 'remove', this.render);
    },
    render: function() {
      
      var self = this, 
          markup = '';

      this.collection.forEach(function(model) {    
        markup += self.template(model.toJSON());
      });      
      this.$el.html(markup);
    }
  });
}(window.App, window.Backbone, window.Handlebars));