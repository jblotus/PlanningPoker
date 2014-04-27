window.App = window.App || {};
(function(App, $, _, Backbone, Handlebars) {  
  "use strict";  
  
  App.Story = Backbone.Model.extend({
    urlRoot: '/get_pivotal_story'
  });
  
  App.CurrentStoryView = Backbone.View.extend({
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
    
  App.StoryInputView = Backbone.View.extend({    
    el: '#pivotal-story-loader',
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
        }
      });
    },
    render: function() {
      return this;
    }
  });
  
  $(document).ready(function() {
    
    //set up templates here since they are on the dom
    App.storyViewTemplate = Handlebars.compile($('#story-view-template').html() || '');
    App.CurrentStoryView.prototype.template = App.storyViewTemplate;
            
    App.currentStoryModel = new App.Story();
    
    App.currentStoryView = new App.CurrentStoryView({
      model: App.currentStoryModel
    });
    
    App.storyInputView = new App.StoryInputView({
      model: App.currentStoryModel
    });    
    
    App.storyInputView.render();
    
    //temporary autofill
    $('#pivotalProject').val(395571);
    $('#pivotalStoryNumber').val(67918638);
  });
}(window.App, window.jQuery, window._, window.Backbone, window.Handlebars));