window.App = window.App || {};
(function(App, $, _, Backbone, Handlebars, Pusher) { 
  "use strict";
  
  App.onAjaxError = function() {
    window.alert('The was a problem communicating with the server.');
  };

  


    

    
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