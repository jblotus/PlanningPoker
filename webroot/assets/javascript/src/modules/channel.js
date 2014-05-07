window.App = window.App || {};

(function(exports) { 
  "use strict";  
  
  exports.Channel = function(pusher) {
    this.pusher = pusher;
  }
  
  exports.Channel.prototype = {
    pusher: null,
    channel: null,
    subscribe: function(channel) {
      this.channel = this.pusher.subscribe(channel);
      return this;
    },
    
    bindUserEvents: function(usersCollection) {
      this.channel.bind('pusher:subscription_succeeded', function(members) {
        members.each(function(member) {
          var user = new usersCollection.model(member);
          usersCollection.add(user);        
        });
      });
      
      this.channel.bind('pusher:member_added', function(member) {
        var member = new usersCollection.model(member);
        usersCollection.add(member);      
      });
        
      this.channel.bind('pusher:member_removed', function(member) {
        var member = new usersCollection.model(member);
        usersCollection.remove(member);      
      });
    },
    
    bindOtherEvents: function() {
      exports.bind('loaded-current-story', function(data) {
      exports.currentStoryModel.fetch({
          data: {
            project_id: data.project_id,
            story_id: data.story_id
          },
          error: exports.onAjaxError
        });
      });
    
      exports.bind('changed-vote', function(data) {      
        exports.currentVoteModel.set('selected', data.selected);
      });
    }
  };
 
}(window.App, window._, window.Backbone));