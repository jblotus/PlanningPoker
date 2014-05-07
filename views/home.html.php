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
    <a href="/backend/login">click to login with gmail</a>
    <?php
  }
?>

<div id="main">
  <div class="row">
    <div class="col-md-2">
      <h4>Connected Users</h4>
      <ul id="connected-users">
      </ul>
    </div>
    
    <div id="story-area" class="col-md-6">
      <script type="text/x-handlebars" id="story-area-template">
      <h3>Current Story</h3>
      <ul id="current-story"></ul>
      
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
      </script>
    </div>
 
  </div>

  <div class="row top-buffer">
    <div class="col-md-2">
    
    </div>
    
    <div id="my-cards" class="col-md-6">
      <h4>My Cards</h4>
    </div>
    

  </div>

  <div class="row top-buffer">
    <div class="col-md-2">
      
    </div>
    <div class="btn-grp col-md-6">
      <button type="button" class="btn btn-info" value="cast">Finished Voting</button>
    </div>
  </div>
  
  <div class="top-buffer row">
    
  </div>
  
  <div id="vote-session-buttons">
    <button class="start btn">Start Session</button>
  </div>
  
</div>


<script type="text/x-handlebars" id="voting-buttons-view-template">  

  <button type="button" class="btn btn-default" value="0">0</button>
  <button type="button" class="btn btn-default" value="1">1</button>
  <button type="button" class="btn btn-default" value="2">2</button>
  <button type="button" class="btn btn-default" value="3">3</button>
  <button type="button" class="btn btn-default" value="5">5</button>
  <button type="button" class="btn btn-default" value="8">8</button>
  <button type="button" class="btn btn-default" value="abstain">Abstain</button>   
</script>


<script type="text/x-handlebars" id="story-view-template">  
    <li>Story Name: {{name}}</li>
    <li>Description: {{description}}</li>
</script>