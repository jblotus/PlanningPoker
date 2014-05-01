window.App = window.App || {};
(function(App, $, _, Backbone, Handlebars, Pusher) { 
  "use strict";
  
  App.onAjaxError = function() {
    window.alert('The was a problem communicating with the server.');
  };
  
  App.CurrentVoteModel = Backbone.Model.extend({
    defaults: {
      selected: 'abstain'
    }
  });
  App.VotingButtonsView = Backbone.View.extend({
    el: '#voting-buttons',
    events: {
      'click button' : 'handleVoteClick'
    },
    initialize: function() {
      this.listenTo(this.model, 'change:selected', this.render);
    },
    handleVoteClick: function(e) {
      this.model.set('selected', $(e.currentTarget).val(), 10);
    },
    render: function() {
      var selected = this.model.get('selected');
      
      this.$el.find('button').removeClass('active');
      this.$el.find('button[value=' + selected + ']').addClass('active');      
      return this;
    }
  });
  
  App.Story = Backbone.Model.extend({
    url: function() {
      return this.urlRoot;
    },
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
        },
        error: App.onAjaxError
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
    
    App.currentVoteModel = new App.CurrentVoteModel();
    
    App.votingButtonsView = new App.VotingButtonsView({
      model: App.currentVoteModel
    });
    App.votingButtonsView.render();
    
    //temporary autofill
    $('#pivotalProject').val(395571);
    $('#pivotalStoryNumber').val(67918638);
    
    //real time stuff
    App.pusher = new Pusher('7f733af21d17ca5e5083');
    App.Channels = {
      current_story: App.pusher.subscribe('current-story'),
      current_vote: App.pusher.subscribe('current-vote')
    }
    
    App.Channels.current_story.bind('loaded-current-story', function(data) {      
      App.currentStoryModel.fetch({
        data: {
          project_id: data.project_id,
          story_id: data.story_id
        },
        error: App.onAjaxError
      });
    });
    
    App.Channels.current_vote.bind('current client changed vote', function(data) {      
      App.currentVoteModel.set('selected', data.vote);
    });
  });
}(window.App, window.jQuery, window._, window.Backbone, window.Handlebars, window.Pusher));