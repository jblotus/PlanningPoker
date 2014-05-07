window.App = window.App || {};

(function(exports, _, Backbone) { 
  "use strict";  
  
  exports.VoteSessionButtonsView = Backbone.View.extend({
    el: '#vote-session-buttons',    
    events: {
      'click .start' : 'startSession',
    },
    startSession: function() {
      exports.router.navigate('startSession', { trigger: true});
    },
    render: function() {
      return this;
    }
  });
  
}(window.App, window._, window.Backbone));