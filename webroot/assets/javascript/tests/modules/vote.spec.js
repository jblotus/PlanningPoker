describe('the vote module', function() {
  "use strict";
  
  describe('the current vote model', function() {
    var currentVoteModel;
    beforeEach(function() {
      currentVoteModel = new App.CurrentVoteModel();
    });
      //fix me
    xit('should default selected attribute to "abstain"', function() {
      expect(currentVoteModel.get('selected')).toBe('abstain');
    });
  });
  
  describe('the voting buttons view', function() {
    var $rootElement,
        votingButtonsView,
        model;
    
    beforeEach(function() {
      $rootElement = $('<div id="voting-buttons"><button value="abstain">abstain</button><button value="3">3</button></div>');
      model = new App.CurrentVoteModel();
      votingButtonsView = new App.VotingButtonsView({
        el: $rootElement,
        model: model
      });
    });
      //fixme
   xit('should bind to #voting-buttons by default', function() {
      expect(App.VotingButtonsView.prototype.el).toBe('#voting-buttons');
    });
      //fixme
    xit('should update the current vote model with the users vote', function() {
      expect(model.get('selected')).toBe('abstain');
      $rootElement.find('button[value=3]').click();
      expect(model.get('selected')).toBe("3");
      $rootElement.find('button[value=abstain]').click();
      expect(model.get('selected')).toBe("abstain");
    });
     
      //fixme
    xit('should make sure the correct button is active/highlighted when the vote changes', function() {
      votingButtonsView.render();
      expect($rootElement.find('button[value=abstain]').hasClass('active')).toBeTruthy();
      model.set('selected', 3);
      expect($rootElement.find('button[value=abstain]').hasClass('active')).not.toBeTruthy();
      expect($rootElement.find('button[value=3]').hasClass('active')).toBeTruthy();
    });
  });
});