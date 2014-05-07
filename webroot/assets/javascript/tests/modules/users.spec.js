describe('the users module', function() {
  "use strict";
   
  describe('the connected users view', function() {
    var app,
        collection;
    
    beforeEach(function() {
      collection = new Backbone.Collection();
      
      spyOn(App.ConnectedUsersView.prototype, 'render').andCallThrough();
      app = new App.ConnectedUsersView({
        collection: collection
      });
    });
    
    it('should have #connected-users as default element', function() {
      expect(App.ConnectedUsersView.prototype.el).toBe('#connected-users');
    });
    it('should have a template like', function() {
      expect(App.ConnectedUsersView.prototype.template({ id: 'foo'})).toBe('<li>foo</li>');
    });
    
    it('should listen to the user collection when something is added or removed', function() {
      collection.add({ id: 'fooooo' });
      collection.remove({ id: 'fooooo' });
      expect(App.ConnectedUsersView.prototype.render).toHaveBeenCalled();
      expect(App.ConnectedUsersView.prototype.render.callCount).toBe(2);
    });
  });
});