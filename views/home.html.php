<h1>Planning Poker</h1>
<p>
  This is the app.
</p>

<h3>Current Story</h3>
<ul id="current-story"> 
</ul>

<form role="form" id="pivotal-story-loader">
  <div class="form-group">
    <label for="pivotalProject">Pivotal Tracker Project ID</label>
    <input type="input" class="form-control" id="pivotalProject" name="project_id" placeholder="Enter Project #">
  </div>
  <div class="form-group">
    <label for="pivotalStoryNumber">Pivotal Tracker Story ID</label>
    <input type="input" class="form-control" id="pivotalStoryNumber" name="story_id" placeholder="Enter Story #">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>

<script type="text/x-handlebars" id="story-view-template">  
    <li>Story Name: {{name}}</li>
    <li>Description: {{description}}</li>
</script>