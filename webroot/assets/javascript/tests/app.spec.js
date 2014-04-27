describe('the planning poker app', function() {
  "use strict";
  
  describe('the story model', function() {
    var story;
    beforeEach(function() {
      story = new App.Story();
    });
    it('should have a custom url root', function() {
      expect(story.urlRoot).toBe('/get_pivotal_story');
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
        } 
      });
    });
  });
});