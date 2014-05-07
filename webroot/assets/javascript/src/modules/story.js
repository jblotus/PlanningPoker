window.App = window.App || {};

(function(exports, _, Backbone) { 
  "use strict";
  
  exports.Story = Backbone.Model.extend({
    url: function() {
      return this.urlRoot;
    },
    urlRoot: '/backend/get_pivotal_story'
  });
  
    
  exports.CurrentStoryView = Backbone.View.extend({
    el: '#current-story',
    initialize: function() {
      this.listenTo(this.model, 'change', this.render);      
    },
    render: function() {      
      var content = this.template(this.model.toJSON());      
      this.$el.html(content);
      return this;
    }
  });

  exports.StoryInputView = Backbone.View.extend({
    el: '#story-area',
    initialize: function() {
      _.bindAll(this, ['submit']);
    },
    events: {
      'submit' : 'submit',
    },
    submit: function(e) {
      e.preventDefault();
      var projectId = this.$el.find('input[name=project_id]').val();
      var storyId = this.$el.find('input[name=story_id]').val();
      
      this.model.fetch({
        data: {
          project_id: projectId,
          story_id: storyId
        },
        error: exports.onAjaxError
      });
    },
    render: function() {
      this.$el.html(this.template({}));
      return this;
    }
  });
  
}(window.App, window._, window.Backbone));