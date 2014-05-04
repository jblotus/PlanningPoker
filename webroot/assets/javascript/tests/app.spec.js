describe('the planning poker app', function() {
  "use strict";
  
  describe('the ajax error handler', function() {
    beforeEach(function() {
      spyOn(window, 'alert');
    });
    
    it('should alert the user with a generic message if the ajax call failed', function() {
      App.onAjaxError();
      expect(window.alert).toHaveBeenCalledWith('The was a problem communicating with the server.');
    });
  });
  
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
  
  describe('the pusherapp events connector', function() {
    it('should listen to change events on the current vote model and send them out the channel');
    it('should change the current vote model when an event comes in from the channel');
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
  
  describe('the story model', function() {
    var story;
    beforeEach(function() {
      story = new App.Story();
    });
    it('should have a custom url root that passes no id', function() {      
      expect(story.urlRoot).toBe('/backend/get_pivotal_story');
      expect(story.url()).toBe(story.urlRoot);
    });
  });
  
  describe('the current story view', function() {
    var model,
        view;
    beforeEach(function() {
      spyOn(App.CurrentStoryView.prototype, 'render').andCallThrough();
      model = new Backbone.Model();
      view = new App.CurrentStoryView({
        model: model
      });
    });
    it('should render when the model changes', function() {
      model.trigger('change');      
      expect(App.CurrentStoryView.prototype.render).toHaveBeenCalled();
    });
  });
  
  describe('the story input view', function() {
    var $rootElement,
        $pivotalProjectId,
        $pivotalStoryId,
        model,
        view;
    
    beforeEach(function() {
      $rootElement = $('<form></form>');
      $pivotalProjectId = $('<input name="project_id">');
      $pivotalStoryId = $('<input name="story_id">');
      
      $rootElement.append($pivotalProjectId);
      $rootElement.append($pivotalStoryId);
      
      spyOn(Backbone.Model.prototype, 'fetch');
      
      var model = new Backbone.Model();
      view = new App.StoryInputView({
        el: $rootElement,
        model: model
      });
    });
    
    it('should fetch a story from pivotal when submit is clicked', function() {      
      $pivotalProjectId.val(123);
      $pivotalStoryId.val(456);
      $rootElement.trigger('submit');
      expect(Backbone.Model.prototype.fetch).toHaveBeenCalledWith({
        data: {
          project_id: '123',
          story_id: '456'
        },
        error: App.onAjaxError
      });
    });
  });
});