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
});