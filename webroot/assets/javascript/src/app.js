(function($, _, Backbone, Handlebars) {  
  "use strict";  
  
  var Story = Backbone.Model.extend({});
  
  var StoryView = Backbone.View.extend({
    el: '#current-story',
    initialize: function() {
      this.listenTo(this.model, 'change', this.render);
    },
    render: function() {      
      var content = this.template(this.model.toJSON());
      console.log(content);
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
      var self = this;
      console.log(self);
      e.preventDefault();
      var projectId = this.$el.find('input[name=project_id]').val();
      var storyId = this.$el.find('input[name=story_id]').val();      
      
      var promise = $.ajax({
        url: '/get_pivotal_story',
        type: 'get',
        data: {
          project_id: projectId,
          story_id: storyId
        }
      });
      
      promise.done(function(data) {
        var story = new Story(data);
        var storyView = new StoryView({ model: story});          
        storyView.render();
      });
    },
    render: function() {
      console.log('rendering story input view');
      return this;
    }
  });
  
  $(document).ready(function() {
    var storyViewTemplate = Handlebars.compile($('#story-view-template').html() || '');
    StoryView.prototype.template = storyViewTemplate;
    
    var storyInputView = new StoryInputView();    
    storyInputView.render();
    
    //temporary autofill
    $('#pivotalProject').val(395571);
    $('#pivotalStoryNumber').val(67918638);
  });
}(window.jQuery, window._, window.Backbone, window.Handlebars));