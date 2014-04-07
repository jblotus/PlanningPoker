(function($) {
  "use strict";
  $(document).ready(function() {
    $('#pivotal-story-loader').on('submit', function(e) {
      e.preventDefault();
        var projectId = $('input[name=project_id]').val();
        var storyId = $('input[name=story_id]').val();
        var promise = $.ajax({
            url: '/get_pivotal_story',
            type: 'get',
            data: {
                project_id: projectId,
                story_id: storyId
            }
        });
        
        
    });
  });
}(window.jQuery, window.Backbone, window.Handlebars));