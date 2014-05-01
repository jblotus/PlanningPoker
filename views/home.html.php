<?php 
$templateData = $this->getData();
?>
<h1>Planning Poker</h1>
<p>
  This is the app.
</p>

<?php
  if ($templateData->showLoginLink) {
    ?>
    <a href="/login">click to login with gmail</a>
    <?php
  }
?>


<h3>Current Story</h3>
<ul id="current-story"> 
</ul>

<div id="voting-buttons" class="btn-group">
  <button type="button" class="btn btn-default" value="0">0</button>
  <button type="button" class="btn btn-default" value="1">1</button>
  <button type="button" class="btn btn-default" value="2">2</button>
  <button type="button" class="btn btn-default" value="3">3</button>
  <button type="button" class="btn btn-default" value="5">5</button>
  <button type="button" class="btn btn-default" value="8">8</button>
  <button type="button" class="btn btn-default" value="abstain">Abstain</button>
</div>

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