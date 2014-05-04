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
    className: 'voting-buttons btn-grp',
    events: {
      'click button' : 'handleVoteClick'
    },
    initialize: function() {
      this.listenTo(this.model, 'change:selected', this.render);
    },
    handleVoteClick: function(e) {
      var selected = parseInt($(e.currentTarget).val(), 10) || 'abstain';
      this.model.set('selected', selected);
        $.post('/backend/pusher', {
            event: 'changed-vote',
            channel: 'presence-planning-poker',
            event_data: {
                'selected' : selected
            }
        });
    },
    render: function() {
      var selected = this.model.get('selected'); 
      this.$el.html(this.template()); 
      
      this.$el.find('button').removeClass('active');
      this.$el.find('button[value=' + selected + ']').addClass('active');
      this.$el.appendTo($('#my-cards'));
      return this;
    }
  });
  
  App.Story = Backbone.Model.extend({
    url: function() {
      return this.urlRoot;
    },
    urlRoot: '/backend/get_pivotal_story'
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
    
  App.Router = Backbone.Router.extend({
    routes: {
      '' : 'defaultRoute',
      '/currentStory/:id' : 'currentStoryRoute'
    },
    
    defaultRoute: function() {
        
      App.votingButtonsView = new App.VotingButtonsView({
        model: App.currentVoteModel
      });
      App.votingButtonsView.render();
    
      App.usersCollection = new App.UsersCollection();
      
      App.connectedUsersView = new App.ConnectedUsersView({
        collection: App.usersCollection
      });     
      
        
      //temporary autofill
      $('#pivotalProject').val(395571);
      $('#pivotalStoryNumber').val(67918638);
    },
    currentStoryRoute: function() {
      
    }
  });
  
  App.User = Backbone.Model.extend();
  App.UsersCollection = Backbone.Collection.extend({
    model: App.User
  });
  App.ConnectedUsersView = Backbone.View.extend({
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
  
  $(document).ready(function() {
    
    //set up templates here since they are on the dom
    App.storyViewTemplate = Handlebars.compile($('#story-view-template').html() || '');
    App.CurrentStoryView.prototype.template = App.storyViewTemplate;
    
    
    App.votingButtonsViewTemplate = Handlebars.compile($('#voting-buttons-view-template').html() || '');
    App.VotingButtonsView.prototype.template = App.votingButtonsViewTemplate;
            
    App.currentStoryModel = new App.Story();
    
    App.currentStoryView = new App.CurrentStoryView({
      model: App.currentStoryModel
    });
    
    App.storyInputView = new App.StoryInputView({
      model: App.currentStoryModel
    });    
    
    App.storyInputView.render();
    
    App.currentVoteModel = new App.CurrentVoteModel();
    
    
    //real time stuff
    App.pusher = new Pusher('7f733af21d17ca5e5083', {
      authEndpoint: '/backend/authpusher'
    });
    App.Channel = App.pusher.subscribe('presence-planning-poker')    
    
    App.Channel.bind('loaded-current-story', function(data) {
      App.currentStoryModel.fetch({
        data: {
          project_id: data.project_id,
          story_id: data.story_id
        },
        error: App.onAjaxError
      });
    });
    
    App.Channel.bind('changed-vote', function(data) {      
      App.currentVoteModel.set('selected', data.selected);
    });
      
    App.Channel.bind('pusher:subscription_succeeded', function(members) {
      members.each(function(member) {
        var user = new App.User(member);
        App.usersCollection.add(user);        
      });
    });
      
    App.Channel.bind('pusher:member_added', function(member) {
      var member = new App.User(member);
      App.usersCollection.add(member);      
    });
      
    App.Channel.bind('pusher:member_removed', function(member) {
      var member = new App.User(member);
      App.usersCollection.remove(member);      
    });
    
    App.router = new App.Router();
    Backbone.history.start();
  });
}(window.App, window.jQuery, window._, window.Backbone, window.Handlebars, window.Pusher));