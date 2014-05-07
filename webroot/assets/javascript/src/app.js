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
    
    App.votingButtonsView = new App.VotingButtonsView({
      model: App.currentVoteModel
    });
    App.votingButtonsView.render();
    
    App.usersCollection = new App.UsersCollection();
    
    App.connectedUsersView = new App.ConnectedUsersView({
      collection: App.usersCollection
    });     
    
    //real time stuff
    App.pusher = new Pusher('7f733af21d17ca5e5083', {
      authEndpoint: '/backend/authpusher'
    });
             

    
    App.channel = new App.Channel(App.pusher);
    App.channel.subscribe('presence-planning-poker') ;    
    App.channel.bindUserEvents(App.usersCollection);

    
    App.router = new App.Router();
    Backbone.history.start();
  });
}(window.App, window.jQuery, window._, window.Backbone, window.Handlebars, window.Pusher));