(function($, _, Backbone, Handlebars) {  
  "use strict";  
  
  var Story = Backbone.Model.extend({
    urlRoot: '/get_pivotal_story'
  });
  
  var CurrentStoryView = Backbone.View.extend({
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
    
  var StoryInputView = Backbone.View.extend({    
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
    
    //set up templates
    var storyViewTemplate = Handlebars.compile($('#story-view-template').html() || '');
    CurrentStoryView.prototype.template = storyViewTemplate;
            
    var currentStoryModel = new Story();
    
    var currentStoryView = new CurrentStoryView({
      model: currentStoryModel
    });
    
    var storyInputView = new StoryInputView({
      model: currentStoryModel
    });    
    storyInputView.render();
    
    //temporary autofill
    $('#pivotalProject').val(395571);
    $('#pivotalStoryNumber').val(67918638);
  });
}(window.jQuery, window._, window.Backbone, window.Handlebars));