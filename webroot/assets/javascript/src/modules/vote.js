window.App = window.App || {};

(function(exports, $, Backbone) { 
  "use strict"; 
    
  exports.CurrentVoteModel = Backbone.Model.extend({
    defaults: {
      selected: 'abstain'
    }
  });  
  
  exports.VotingButtonsView = Backbone.View.extend({ 
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
  
}(window.App, window.jQuery, window.Backbone));