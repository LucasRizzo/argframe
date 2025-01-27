<form class="form-inline ">
    <div class="well well-lg">
        &nbsp;&nbsp;<span data-toggle="tooltip" title="Create featureset"><input style = "opacity: 1;margin-bottom: -8px;" id="new-feaureset" type="image" title="create featureset" src="new-graph.png" alt="create featureset"></span>
        
        <!--&nbsp;&nbsp;<span data-toggle="tooltip" title="Create featureset with JSON code"><input style = "opacity: 1;margin-bottom: -8px;" id="new-feaureset-json" type="image" title="create featureset with json code" src="json.png" alt="create featureset with json code"></span>-->
        
        &nbsp;&nbsp;<span data-toggle="tooltip" title="Copy featureset with a new name"><input style = "opacity: 1;margin-bottom: -8px;" type="image" 
        id="copy-featureset" title="copy featureset with a new name" src="copy.png" alt="copy featureset"></span>
        
        &nbsp;&nbsp;<span data-toggle="tooltip" title="Rename featureset"><input style = "opacity: 1;margin-bottom: -8px;" type="image" 
        id="rename-featureset" title="rename featureset with a new name" src="rename.png" alt="rename featureset"></span>
        
        &nbsp;&nbsp;<span data-toggle="tooltip" title="Download featureset"><input style = "opacity: 1;margin-bottom: -8px;" type="image" id="download-featureset" title="download featureset" src="download-icon.png" alt="download featureset"></span>
        
        &nbsp;&nbsp;<span data-toggle="tooltip" title="Delete featureset"><input style = "opacity: 1;margin-bottom: -8px;" type="image" id="delete-featureset" title="delete featureset" src="trash-icon.png" alt="delete featureset"></span>
        
        &nbsp;&nbsp;&nbsp;&nbsp;<label id="featuresetLabel"  for="sel1">Select feature set: &nbsp</label>
        <select class="form-control" name="featureset" id="featureset">
            <?php foreach ($view_allUserFeaturesets as $d) {
                echo "<option value='" . $d . "'>" . $d . "</option>";
            } ?>
        </select>
    </div>

    <!-- Table of features -->
    <div class="panel panel-warning">
    <div class="panel-heading"><b>Feature set</b><span id="featuresN"></span>&nbsp;&nbsp;
    <a href="#" data-toggle="tooltip" title="Add new feature"><button type="button" class="btn-xs btn-success btn-xs" id="create-feature"><span class="glyphicon glyphicon-plus"></button></a></div>
    <div class="panel-body"><span id="features"></span></div>
    </div>
    
    <!-- Table of conclusions -->
    <div class="panel panel-info">
    <div class="panel-heading"><b>Conclusion set</b><span id="conclusionsN"></span>&nbsp;&nbsp;
    <a href="#" data-toggle="tooltip" title="Add new conclusion"><button type="button" class="btn-xs btn-success btn-xs" id="create-conclusion"><span class="glyphicon glyphicon-plus"></button></a></div>
    <div class="panel-body"><span id="conclusions"></span></div>
    </div>
</form>


<!-- Modal update features -->
<div id="updateFeatureModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update feature</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                <label for="featureNameNew">Feature:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="featureNameNew">
            </div>
            <div class="form-group">
                <label for="levelNameNew">Level:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="levelNameNew">
            </div>
            
            <div class="form-group">
                <label for="fromValueNew">From:</label>
                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" id="fromValueNew">
            </div>
            
            <div class="form-group">
                <label for="toValueNew">To:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="toValueNew">
            </div>
            
            <span id="featureNameOld" hidden></span>
            <span id="levelNameOld" hidden></span>
            <span id="fromValueOld" hidden></span>
            <span id="toValueOld" hidden></span>
            
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary pull-left" data-toggle="tooltip" data-placement="top" title="Please select a node in order to edit." 
                id="updateFeature"><span class="glyphicon glyphicon-edit">&nbsp; Update feature</span></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal conclusion -->
<div id="updateConclusionModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update conclusion</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                <label for="conclusionNameNew">Conclusion:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="conclusionNameNew">
            </div>
            
            <div class="form-group">
                <label for="fromConclusionValueNew">From:</label>
                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" id="fromConclusionValueNew">
            </div>
            
            <div class="form-group">
                <label for="toConclusionValueNew">To:</label>
                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" id="toConclusionValueNew">
            </div>
            
            <span id="conclusionNameOld" hidden></span>
            <span id="fromConclusionValueOld" hidden></span>
            <span id="toConclusionValueOld" hidden></span>
            
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary pull-left" data-toggle="tooltip" data-placement="top" title="Please select a node in order to edit." 
                id="updateConclusion"><span class="glyphicon glyphicon-edit">&nbsp; Update conclusion</span></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal new featureset -->
<div id="modalNewFeatureset" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create feature set</h4>
            </div>
             <div class="modal-body ">
                <form class="form-inline">
                <input type="text" style="width:60%;" class="form-control" name="new-featureset-name" id="new-featureset-name" placeholder="Enter feature set name">
                &nbsp
                <button class="btn btn-primary pull-right" id="saveNewFeatureset" data-toggle="tooltip"
                        data-placement="top" title="Create new empty feature set">
                        <span class="glyphicon glyphicon-file">&nbsp; Create empyt feature set </span>
                </button>
                </form>
                <br>
                
            <!--     (<a href="#" data-toggle="popover" title="JSON example" data-html="true"
                data-content="<a href='json_example.png' target='_blank'><img src='json_example.png' alt='JSON EXAMPLE' style='width:100%'>
                <div class='caption'>
                    <p>Big featuresets can be created through the use of JSON files. In the figure, an example of JSON and resulting featureset.</p>
                </div>">?</a>)-->
                
                <label for="comment">... or create with JSON code (<a href="#" data-toggle="popover" title="JSON example" data-html="true"
                data-content="<div class='thumbnail'><a href='json_example.png' target='_blank'><img src='json_example.png' alt='JSON EXAMPLE' style='width:100%;heigth:100%'>
                <div class='caption'>
                    <p>Feature sets can be created through the use of JSON files. Click on the figure for an example of JSON code and resulting feature set.</p>
                </div></div>">?</a>):</label>&nbsp; &nbsp;<button class="btn-xs btn-primary" id="saveNewJSONFeatureset" data-toggle="tooltip"
                        data-placement="top" title="Create feature set with JSON code">
                        <span class="glyphicon glyphicon-upload">&nbsp; Upload JSON </span>
                </button>
                <form class="form-inline">
                <textarea class="form-control" rows="10" style="width:100%;" name="jsoncode" id="jsoncode"></textarea>
                </form>
                
            </div>
        </div>
    </div>
</div>

<!-- Modal copy featureset -->
<div id="modalCopyFeatureset" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Copy feature set with new name</h4>
            </div>
             <div class="modal-body ">
                <form class="form-inline"">
                <input type="text" style="width:50%;" class="form-control" name="copy-featureset-name" id="copy-featureset-name" placeholder="Enter feature set copy name">
                &nbsp
                <button class="btn btn-primary pull-right" id="saveCopyFeatureset" data-toggle="tooltip"
                        data-placement="top" title="Copy feature set with new name">
                        <span class="glyphicon glyphicon-duplicate">&nbsp; Copy feature set with new name </span>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal rename featureset -->
<div id="modalRenameFeatureset" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rename feature set</h4>
            </div>
             <div class="modal-body ">
                <form class="form-inline"">
                <input type="text" style="width:50%;" class="form-control" name="rename-featureset-name" id="rename-featureset-name" placeholder="Enter feature set new name">
                &nbsp
                <button class="btn btn-primary pull-right" id="saveRenameFeatureset" data-toggle="tooltip"
                        data-placement="top" title="Rename featureset">
                        <span class="glyphicon glyphicon-ok-circle">&nbsp; Rename feature set</span>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal create feature -->
<div id="createFeatureModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create feature</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                <label for="featureName">Feature:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="featureName">
            </div>
            <div class="form-group">
                <label for="levelName">Level:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="levelName">
            </div>
            
            <div class="form-group">
                <label for="fromValue">From:</label>
                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" id="fromValue">
            </div>
            
            <div class="form-group">
                <label for="toValue">To:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="toValue">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success pull-left" data-toggle="tooltip" data-placement="top" title="Create feature." 
                id="saveNewFeature"><span class="glyphicon glyphicon-plus">&nbsp; Create feature</span></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal create conclusion -->
<div id="createConclusionModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create conclusion</h4>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                <label for="conclusionName">Conclusion:</label>
                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" id="conclusionName">
            </div>
            
            <div class="form-group">
                <label for="fromConclusionValue">From:</label>
                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" id="fromConclusionValue">
            </div>
            
            <div class="form-group">
                <label for="toConclusionValue">To:</label>
                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" id="toConclusionValue">
            </div>
            
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success pull-left" data-toggle="tooltip" data-placement="top" title="Create conclusion." 
                id="createConclusion"><span class="glyphicon glyphicon-plus">&nbsp; Create conclusion</span></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});


var attributesByFeatureset_ = <?php echo json_encode($view_attributesByFeatureset, JSON_PRETTY_PRINT); ?>;

var conclusionsByFeatureset_ = <?php echo json_encode($view_conclusionsByFeatureset, JSON_PRETTY_PRINT); ?>;

document.getElementById("create-conclusion").addEventListener("click", function(e){
    event.preventDefault();
    $('#createConclusionModal').modal('show');
});

document.getElementById("delete-featureset").addEventListener("click", function(e){

    e.preventDefault();
    
    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
    
    confirm("Are you sure you want to delete \"" + currentFeatureset + "\"? Arguments and graphs built using this featureset (if any) will also be deleted.", "Confirm deletion.", "OK", function (result) {
    
        if (! result) {
            return;
        }
                    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == "ok") {
                        delete attributesByFeatureset_[currentFeatureset];
                        delete conclusionsByFeatureset_[currentFeatureset];
                        
                        var x = document.getElementById("featureset");
                        var option = document.createElement("option");
                        
                        for (var i = 0; i < x.length; i++) {
                            if (x.options[i].text == currentFeatureset) {
                                x.remove(i);
                                break;
                            }
                        }
                        
                        x.selectedIndex = 0;
                        
                        var event = new Event('change');
                        x.dispatchEvent(event);
                } else {                
                    window.alert(this.responseText, "Wrong response error.");
                }
            }
        };
        xmlhttp.open("GET","index.php/deleteFeatureset/" + currentFeatureset, true);
        xmlhttp.send();
    }); 
});
    
                  


document.getElementById("createConclusion").addEventListener("click", function(e){

    e.preventDefault();
    
    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
    
    var conclusion = currentFeatureset + ";"
    
    conclusion += document.getElementById("conclusionName").value + ":" +
                           document.getElementById("fromConclusionValue").value + ":" +
                           document.getElementById("toConclusionValue").value;

    console.log(conclusion);
    
     var xmlhttp = new XMLHttpRequest();
     xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "ok") {

                if (attributesByFeatureset_[currentFeatureset] === undefined) {
                    attributesByFeatureset_[currentFeatureset] = new Array(0);
                    conclusionsByFeatureset_[currentFeatureset] = new Array(0);
                }

                conclusionsByFeatureset_[currentFeatureset].push({conclusion: document.getElementById("conclusionName").value,
                                                                    c_from: document.getElementById("fromConclusionValue").value,
                                                                    c_to: document.getElementById("toConclusionValue").value});
                printFeatureSet();
                // Clear form
                document.getElementById("conclusionName").value = "";
                document.getElementById("fromConclusionValue").value = "";
                document.getElementById("toConclusionValue").value = "";
                $('#createConclusionModal').modal('hide');
            } else {                
                window.alert(this.responseText, "Wrong response error.");
            }
        }
     };
     xmlhttp.open("GET","index.php/createConclusion/" + conclusion, true);
     xmlhttp.send();
});


document.getElementById("create-feature").addEventListener("click", function(e){
    event.preventDefault();
    $('#createFeatureModal').modal('show');
});


$('#createFeatureModal').on('show.bs.modal', function(e) {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    //In case a new level is being created.
    var featureName = $(e.relatedTarget).data('feature-name');
    
    if (typeof featureName == "undefined") {
        return;
    } else {
        document.getElementById("featureName").value = featureName;
        document.getElementById("featureName").disabled = true;
    }
});

$('#createFeatureModal').on('hide.bs.modal', function(e) {

    document.getElementById("featureName").value = "";
    document.getElementById("featureName").disabled = false;
});

document.getElementById("saveNewFeature").addEventListener("click", function(e){

    e.preventDefault();
    
    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
    
    var featureString = currentFeatureset + ";"
    
    featureString += document.getElementById("featureName").value + ":" +
                     document.getElementById("levelName").value + ":" +
                     document.getElementById("fromValue").value + ":" +
                     document.getElementById("toValue").value;
    
     var xmlhttp = new XMLHttpRequest();
     xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "ok") {

                if (attributesByFeatureset_[currentFeatureset] === undefined) {
                    attributesByFeatureset_[currentFeatureset] = new Array(0);
                    conclusionsByFeatureset_[currentFeatureset] = new Array(0);
                }
                
                attributesByFeatureset_[currentFeatureset].push({attribute: document.getElementById("featureName").value,
                                                                 a_level: document.getElementById("levelName").value,
                                                                 a_from: document.getElementById("fromValue").value,
                                                                 a_to: document.getElementById("toValue").value});
                console.log(attributesByFeatureset_);
                printFeatureSet();
                document.getElementById("featureName").value = "";
                document.getElementById("levelName").value = "";
                document.getElementById("fromValue").value = "";
                document.getElementById("toValue").value = "";               
                $('#createFeatureModal').modal('hide');
            } else {
                window.alert(this.responseText, "Wrong response error.");
            }
        }
     };
     xmlhttp.open("GET","index.php/createFeature/" + featureString, true);
     xmlhttp.send();
});

document.getElementById("new-feaureset").addEventListener("click", function(e){
    event.preventDefault();
    $('#modalNewFeatureset').modal('show');
});

document.getElementById("copy-featureset").addEventListener("click", function(e){
    event.preventDefault();
    $('#modalCopyFeatureset').modal('show');
});

document.getElementById("rename-featureset").addEventListener("click", function(e){
    event.preventDefault();
    $('#modalRenameFeatureset').modal('show');
});

document.getElementById("saveCopyFeatureset").addEventListener("click", function(e){

    e.preventDefault();
    var new_name = document.getElementById('copy-featureset-name').value;
    
    if(new_name.length == 0) {
        window.alert("Please enter a name.", "No name error.");
        return;
    }
    
    var select = document.getElementById('featureset'),
         i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            current_featuresets = this.responseText.split(",");
            
            for (var i = 0; i < current_featuresets.length; i++) {
                if (new_name == current_featuresets[i]) {
                    window.alert("Featureset with this name already exists. Please choose a new name.", "Duplicate name error.");
                    return;
                }
            }
            
            var xmlhttpCreate = new XMLHttpRequest();
            
            xmlhttpCreate.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    
                    if (this.responseText != "ok") {
                        window.alert("Error in the database. Featureset not created!", "Wrong response error.");
                        return;
                    }
                    
                    attributesByFeatureset_[new_name] = attributesByFeatureset_[currentFeatureset];
                    conclusionsByFeatureset_[new_name] = conclusionsByFeatureset_[currentFeatureset] 
                    
                    var x = document.getElementById("featureset");
                    var option = document.createElement("option");
                    option.text = new_name;
                    option.value = new_name;
                    x.add(option);
                    
                    x.selectedIndex = x.options.length - 1;
                    
                    var event = new Event('change');
                    x.dispatchEvent(event);
                    
                    document.getElementById('copy-featureset-name').value = "";
                    $('#modalCopyFeatureset').modal('hide');
                }
            }
            
            xmlhttpCreate.open("GET","index.php/copyFeatureset/" + new_name + ";" + currentFeatureset, true);
            xmlhttpCreate.send();
        }
    };
    xmlhttp.open("GET","index.php/getFeaturesetsNames", true);
    xmlhttp.send();
});


document.getElementById("saveRenameFeatureset").addEventListener("click", function(e){

    e.preventDefault();
    var new_name = document.getElementById('rename-featureset-name').value;
    
    if(new_name.length == 0) {
        window.alert("Please enter a name.", "Empty name error.");
        return;
    }
    
    var select = document.getElementById('featureset'),
         i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            current_featuresets = this.responseText.split(",");
            
            for (var i = 0; i < current_featuresets.length; i++) {
                if (new_name == current_featuresets[i]) {
                    window.alert("Featureset with this name already exists. Please choose a new name.", "Duplicate name error.");
                    return;
                }
            }
            
            var xmlhttpCreate = new XMLHttpRequest();
            
            xmlhttpCreate.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    
                    if (this.responseText != "ok") {
                        window.alert("Error in the database. Featureset not created!", "Wrong response error.");
                        return;
                    }
                    
                    attributesByFeatureset_[new_name] = attributesByFeatureset_[currentFeatureset];
                    conclusionsByFeatureset_[new_name] = conclusionsByFeatureset_[currentFeatureset];
                    
                    delete attributesByFeatureset_[currentFeatureset];
                    delete conclusionsByFeatureset_[currentFeatureset];
                    
                    var x = document.getElementById("featureset");
                    var option = document.createElement("option");
                    option.text = new_name;
                    option.value = new_name;
                    x.add(option);
                    
                    x.selectedIndex = x.options.length - 1;
                    
                    for (var i = 0; i < x.length; i++) {
                        if (x.options[i].text == currentFeatureset) {
                            x.remove(i);
                            break;
                        }
                    }
                    
                    var event = new Event('change');
                    x.dispatchEvent(event);
                    
                    document.getElementById('rename-featureset-name').value = "";
                    $('#modalRenameFeatureset').modal('hide');
                }
            }
            
            xmlhttpCreate.open("GET","index.php/renameFeatureset/" + new_name + ";" + currentFeatureset, true);
            xmlhttpCreate.send();
        }
    };
    xmlhttp.open("GET","index.php/getFeaturesetsNames", true);
    xmlhttp.send();
});



document.getElementById("saveNewFeatureset").addEventListener("click", function(e){

    e.preventDefault();
    var new_name = document.getElementById('new-featureset-name').value;
    
    if(new_name.length == 0) {
        window.alert("Please enter a name.", "No name error.");
        return;
    }
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            current_featuresets = this.responseText.split(",");
            
            for (var i = 0; i < current_featuresets.length; i++) {
                if (new_name == current_featuresets[i]) {
                    window.alert("Featureset with this name already exists. Please choose a new name.", "Duplicate name error.");
                    return;
                }
            }
            
            var xmlhttpCreate = new XMLHttpRequest();
            
            xmlhttpCreate.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText != "ok") {
                        window.alert("Error in the database. Featureset not created!", "Wrong response error.");
                        return;
                    }
                    
                    
                    attributesByFeatureset_[new_name] = new Array(0);
                    conclusionsByFeatureset_[new_name] = new Array(0);
                    
                    var x = document.getElementById("featureset");
                    var option = document.createElement("option");
                    option.text = new_name;
                    option.value = new_name;
                    x.add(option);
                    
                    x.selectedIndex = x.options.length - 1;
                    
                    var event = new Event('change');
                    x.dispatchEvent(event);
                    
                    document.getElementById('new-featureset-name').value = "";
                    $('#modalNewFeatureset').modal('hide');
                }
            }
            
            xmlhttpCreate.open("GET","index.php/newFeatureset/" + new_name, true);
            xmlhttpCreate.send();
        }
    };
    xmlhttp.open("GET","index.php/getFeaturesetsNames", true);
    xmlhttp.send();
});

document.getElementById("saveNewJSONFeatureset").addEventListener("click", function(e){

    e.preventDefault();
    var jsonCode = document.getElementById('jsoncode').value;
    
    if(jsonCode.length == 0) {
        window.alert("Please enter some code in the text area.", "Empty code error.");
        return;
    }
    
    var featuresetParse;
    try {
        featuresetParse = JSON.parse(jsonCode);
    } catch(err) {
        window.alert("Syntax error in imported code. Please check your JSON syntax.", "Syntax error.");
        return;
    }
    
    // Check if range match number of from, to, and level
    try {
        for (var i = 0; i < featuresetParse.attributes.length; i++) {
            var name = featuresetParse.attributes[i][0].name;
            console.log(featuresetParse.attributes[i][1]);
            
            if (featuresetParse.attributes[i][1].range != featuresetParse.attributes[i][2].from.length) {
                window.alert("Range value does not match number of from fields.", "Logical error.");
                return;
            }
            
            if (featuresetParse.attributes[i][1].range != featuresetParse.attributes[i][3].to.length) {
                window.alert("Range value does not match number of to fields.", "Logical error.");
                return;
            }
            
            if (featuresetParse.attributes[i][1].range != featuresetParse.attributes[i][4].level.length) {
                window.alert("Range value does not match number of level fields.", "Logical error.");
                return;
            }
        }
    } catch(err) {
        window.alert("Wrong fields or missing fields in imported code. Please check example.", "Formatting error.");
        return;
    }
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            
            current_featuresets = this.responseText.split(",");
            
            for (var i = 0; i < current_featuresets.length; i++) {
                if (featuresetParse.featureset == current_featuresets[i]) {
                    window.alert("Featureset with this name already exists. Please choose a new name.", "Duplicate name error.");
                    return;
                }
            }
            
            
            var oReq = new XMLHttpRequest();
            oReq.open("POST", "index.php/newFeaturesetJSON", true);

            oReq.onreadystatechange = function() {
                if (oReq.readyState == 4 && oReq.status == 200) {
                    if (this.responseText != "ok") {
                        window.alert(this.responseText, "Wrong response error.");
                        return;
                    }
                    
                    //console.log(featuresetParse);

                    // update javascript functions from featuresetParse
                    attributesByFeatureset_[featuresetParse.featureset] = new Array(0);
                    conclusionsByFeatureset_[featuresetParse.featureset] = new Array(0);
                    
                    for (var i = 0; i < featuresetParse.attributes.length; i++) {
                        var name = featuresetParse.attributes[i][0].name;
                        console.log(featuresetParse.attributes[i][1]);
                        for (var j = 0; j < parseInt(featuresetParse.attributes[i][1].range); j++) {
                            var a_from = featuresetParse.attributes[i][2].from[j].value;
                            var a_to = featuresetParse.attributes[i][3].to[j].value;
                            var a_level = featuresetParse.attributes[i][4].level[j].value;
                            console.log(a_level);
                            console.log(a_from);
                            console.log(a_to);
                            attributesByFeatureset_[featuresetParse.featureset].push({attribute: name,
                                                                                      a_level: a_level,
                                                                                      a_from: a_from,
                                                                                      a_to: a_to});
                        }
                    }
                    
                    for (var i = 0; i < featuresetParse.conclusions.length; i++) {
                    
                        conclusionsByFeatureset_[featuresetParse.featureset].push({conclusion: featuresetParse.conclusions[i][0].category,
                                                                                   c_from: featuresetParse.conclusions[i][1].from,
                                                                                   c_to: featuresetParse.conclusions[i][2].to});
                    }
                    
                    var x = document.getElementById("featureset");
                    var option = document.createElement("option");
                    option.text = featuresetParse.featureset;
                    option.value = featuresetParse.featureset;
                    x.add(option);
                    
                    x.selectedIndex = x.options.length - 1;
                    
                    var event = new Event('change');
                    x.dispatchEvent(event);
                    
                    document.getElementById('jsoncode').value = "";
                    $('#modalNewFeatureset').modal('hide');
                }
            };

            oReq.send(jsonCode);
        }
    };
    xmlhttp.open("GET","index.php/getFeaturesetsNames", true);
    xmlhttp.send();
});

document.getElementById("featureset").addEventListener("change", function(event) {
    event.preventDefault();
    printFeatureSet();
});

function printFeatureSet() {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    var html_features = "<table class=\"table table-striped\"><thead><tr>";
    html_features += "<th>Feature</th>";
    html_features += "<th>Level</th>";
    html_features += "<th>From</th>";
    html_features += "<th>To</th>";
    html_features += "<th>Actions</th>";
    html_features += "</tr></thead>";
    html_features += "<tbody>";
    
    // Not empty featureset
    var featuresN = 0;
    var rows = 0;
    if (attributesByFeatureset_[currentFeatureset] !== undefined && attributesByFeatureset_[currentFeatureset].length > 0) {
        var currentAttribute = attributesByFeatureset_[currentFeatureset][0].attribute;
        featuresN = 1
        rows = attributesByFeatureset_[currentFeatureset].length;
        html_features += "<tr><td>" + attributesByFeatureset_[currentFeatureset][0].attribute + "</td>";
        for (var attr = 0; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {

            if (attributesByFeatureset_[currentFeatureset][attr].attribute != currentAttribute) {
                html_features += "<tr><td>" + attributesByFeatureset_[currentFeatureset][attr].attribute + "</td>";
                currentAttribute = attributesByFeatureset_[currentFeatureset][attr].attribute;
                featuresN++;
            } else if (attr > 0) {
                html_features += "<tr><td>" + attributesByFeatureset_[currentFeatureset][attr].attribute + "</td>";
            }

            html_features += "<td>" + attributesByFeatureset_[currentFeatureset][attr].a_level +
                    "</td><td>" + attributesByFeatureset_[currentFeatureset][attr].a_from +
                    "</td><td>" + attributesByFeatureset_[currentFeatureset][attr].a_to +
                    "</td>";
            
            // Actions
            html_features += "<td> <a data-toggle=\"modal\" data-feature-id=\"" + attr.toString() + 
                            "\" href=\"#updateFeatureModal\"><span data-toggle=\"tooltip\" title=\"Edit feature or level\"><span class=\"glyphicon glyphicon-edit\"></span></span></a>";
                            
            html_features += "&nbsp; &nbsp; <a data-toggle=\"modal\" data-feature-name=\"" + attributesByFeatureset_[currentFeatureset][attr].attribute + 
                            "\" href=\"#createFeatureModal\"><span data-toggle=\"tooltip\" title=\"Add new level\"><span class=\"glyphicon glyphicon-tasks\"></span></span></a>"
            html_features += "&nbsp; &nbsp; <a href=\"#\" onclick=\"deleteFeature(" + attr.toString() + ")\"><span data-toggle=\"tooltip\" title=\"Delete feature level\"><span class=\"glyphicon glyphicon-trash\"></span></span></a>";
            html_features += "</td></tr>";
        }
    }
    
    html_features += "</tbody></table>";
    
    var html_conclusions = "<table class=\"table table-striped\"><thead><tr>";
    html_conclusions += "<th>Conclusion</th>";
    html_conclusions += "<th>From</th>";
    html_conclusions += "<th>To</th>";
    html_conclusions += "<th>Actions</th>";
    html_conclusions += "</tr></thead>";
    html_conclusions += "<tbody>";

    
    var conclusionN = 0;
    if (conclusionsByFeatureset_[currentFeatureset] !== undefined) {
        for (var conc = 0; conc < conclusionsByFeatureset_[currentFeatureset].length; conc++) {

            html_conclusions += "<tr><td>" + conclusionsByFeatureset_[currentFeatureset][conc].conclusion + "</td>" +
                    "<td>" + conclusionsByFeatureset_[currentFeatureset][conc].c_from +
                    "</td><td>" + conclusionsByFeatureset_[currentFeatureset][conc].c_to + "</td>";

            html_conclusions += "<td><a data-toggle=\"modal\" data-conclusion-id=\"" + conc.toString() + 
                                "\" href=\"#updateConclusionModal\"><span data-toggle=\"tooltip\" title=\"Edit conclusion\"><span class=\"glyphicon glyphicon-edit\"></span></span></a>";
            html_conclusions += "&nbsp; &nbsp; <a href=\"#\" onclick=\"deleteConclusion(" + conc.toString() + ")\"><span data-toggle=\"tooltip\" title=\"Delete conclusion\"><span class=\"glyphicon glyphicon-trash\"></span></span></a>";
            html_conclusions += "</td></tr>";

            conclusionN++
        }
    }


    document.getElementById('features').innerHTML = html_features;
    if (rows > featuresN) {
        document.getElementById('featuresN').innerHTML = " (" + featuresN.toString() + " features with multiple levels)";
    } else {
        document.getElementById('featuresN').innerHTML = " (" + featuresN.toString() + " features)";
    }
    
    document.getElementById('conclusions').innerHTML = html_conclusions;
    document.getElementById('conclusionsN').innerHTML = " (" + conclusionN.toString() + " conclusions)";
    
    $('[data-toggle="tooltip"]').tooltip();
}


function deleteConclusion(index) {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    conclusion = conclusionsByFeatureset_[currentFeatureset][index].conclusion;
    
    
    confirm("Are you sure you want to delete \"" + conclusion + "\"? Arguments containing this conclusion (if any) will also be deleted in their respective graphs.", "Confirm deletion.", "OK", function(result) {
    
        if (! result) {
            return;
        }
        
        var conclusionString = currentFeatureset + ";";
        conclusionString += conclusion;
                    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != "ok") {
                    window.alert(this.responseText, "Wrong response error.");
                } else {
                    conclusionsByFeatureset_[currentFeatureset].splice(index, 1);
                    printFeatureSet();
                }
            }
        };
        xmlhttp.open("GET","index.php/deleteConclusion/" + conclusionString, true);
        xmlhttp.send();
    });    
}


function deleteFeature(index) {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    feature = attributesByFeatureset_[currentFeatureset][index].attribute;
    level = attributesByFeatureset_[currentFeatureset][index].a_level;

    
    confirm("Are you sure you want to delete \"" + level + " " + feature + "\"? Arguments containing this feature level (if any) will also be deleted in their respective graphs.", "Confirm deletion.", "OK", function(result) {
    
        if (! result) {
            return;
        }
        
        var featureString = currentFeatureset + ";";
        featureString += feature + ":" + level;
                    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != "ok") {
                    window.alert(this.responseText, "Wrong response error.");
                } else {
                    attributesByFeatureset_[currentFeatureset].splice(index, 1);
                    printFeatureSet();
                }
            }
        };
        xmlhttp.open("GET","index.php/deleteFeature/" + featureString, true);
        xmlhttp.send();
    });
}

//triggered when modal is about to be shown
$('#updateFeatureModal').on('show.bs.modal', function(e) {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    //get data-id attribute of the clicked element
    var featureID = $(e.relatedTarget).data('feature-id');

    //populate the textbox
    $(e.currentTarget).find('input[id="featureNameNew"]').val(attributesByFeatureset_[currentFeatureset][featureID].attribute);
    $(e.currentTarget).find('input[id="levelNameNew"]').val(attributesByFeatureset_[currentFeatureset][featureID].a_level);
    $(e.currentTarget).find('input[id="fromValueNew"]').val(attributesByFeatureset_[currentFeatureset][featureID].a_from);
    $(e.currentTarget).find('input[id="toValueNew"]').val(attributesByFeatureset_[currentFeatureset][featureID].a_to);
    
    document.getElementById("featureNameOld").innerHTML = attributesByFeatureset_[currentFeatureset][featureID].attribute;
    document.getElementById("levelNameOld").innerHTML = attributesByFeatureset_[currentFeatureset][featureID].a_level;
    document.getElementById("fromValueOld").innerHTML = attributesByFeatureset_[currentFeatureset][featureID].a_from;
    document.getElementById("toValueOld").innerHTML = attributesByFeatureset_[currentFeatureset][featureID].a_to;
});

document.getElementById("updateFeature").addEventListener("click", function(e){

    e.preventDefault();
    
    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
    
    var featureOldAndNew = currentFeatureset + ";"
    
    featureOldAndNew += document.getElementById("featureNameOld").innerHTML + ":" +
                        document.getElementById("levelNameOld").innerHTML + ":" +
                        document.getElementById("fromValueOld").innerHTML + ":" +
                        document.getElementById("toValueOld").innerHTML + ";"
    
    featureOldAndNew += document.getElementById("featureNameNew").value + ":" +
                        document.getElementById("levelNameNew").value + ":" +
                        document.getElementById("fromValueNew").value + ":" +
                        document.getElementById("toValueNew").value;
    
    //console.log(document.getElementById("featureNameNew").value);
    
     var xmlhttp = new XMLHttpRequest();
     xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "ok") {

                for (var attr = 0; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {
                    
                    if (attributesByFeatureset_[currentFeatureset][attr].attribute == document.getElementById("featureNameOld").innerHTML &&
                        attributesByFeatureset_[currentFeatureset][attr].a_level == document.getElementById("levelNameOld").innerHTML) {
                        
                        attributesByFeatureset_[currentFeatureset][attr].attribute = document.getElementById("featureNameNew").value;
                        attributesByFeatureset_[currentFeatureset][attr].a_level = document.getElementById("levelNameNew").value;
                        attributesByFeatureset_[currentFeatureset][attr].a_from = document.getElementById("fromValueNew").value;
                        attributesByFeatureset_[currentFeatureset][attr].a_to = document.getElementById("toValueNew").value;
                        printFeatureSet();
                        $('#updateFeatureModal').modal('hide');
                        break;
                    }
                }
            } else {
                document.getElementById("featureNameNew").value = document.getElementById("featureNameOld").innerHTML;
                document.getElementById("levelNameNew").value = document.getElementById("levelNameOld").innerHTML;
                document.getElementById("fromValueNew").value = document.getElementById("fromValueOld").innerHTML;
                document.getElementById("toValueNew").value = document.getElementById("toValueOld").innerHTML;
                
                window.alert(this.responseText, "Wrong response error.");
            }
        }
     };
     xmlhttp.open("GET","index.php/updateFeature/" + featureOldAndNew, true);
     xmlhttp.send();
});


$('#updateConclusionModal').on('show.bs.modal', function(e) {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    //get data-id attribute of the clicked element
    var conclusionID = $(e.relatedTarget).data('conclusion-id');

    //populate the textbox
    $(e.currentTarget).find('input[id="conclusionNameNew"]').val(conclusionsByFeatureset_[currentFeatureset][conclusionID].conclusion);
    $(e.currentTarget).find('input[id="fromConclusionValueNew"]').val(conclusionsByFeatureset_[currentFeatureset][conclusionID].c_from);
    $(e.currentTarget).find('input[id="toConclusionValueNew"]').val(conclusionsByFeatureset_[currentFeatureset][conclusionID].c_to);
    
    document.getElementById("conclusionNameOld").innerHTML = conclusionsByFeatureset_[currentFeatureset][conclusionID].conclusion;
    document.getElementById("fromConclusionValueOld").innerHTML = conclusionsByFeatureset_[currentFeatureset][conclusionID].c_from;
    document.getElementById("toConclusionValueOld").innerHTML = conclusionsByFeatureset_[currentFeatureset][conclusionID].c_to;
});


document.getElementById("updateConclusion").addEventListener("click", function(e){

    e.preventDefault();
    
    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
    
    var conclusionOldAndNew = currentFeatureset + ";"
    
    conclusionOldAndNew += document.getElementById("conclusionNameOld").innerHTML + ":" +
                           document.getElementById("fromConclusionValueOld").innerHTML + ":" +
                           document.getElementById("toConclusionValueOld").innerHTML + ";";
    
    conclusionOldAndNew += document.getElementById("conclusionNameNew").value + ":" +
                           document.getElementById("fromConclusionValueNew").value + ":" +
                           document.getElementById("toConclusionValueNew").value;
    
     var xmlhttp = new XMLHttpRequest();
     xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "ok") {
                for (var conc = 0; conc < conclusionsByFeatureset_[currentFeatureset].length; conc++) {

                    if (conclusionsByFeatureset_[currentFeatureset][conc].conclusion == document.getElementById("conclusionNameOld").innerHTML) {
                        conclusionsByFeatureset_[currentFeatureset][conc].conclusion = document.getElementById("conclusionNameNew").value;
                        conclusionsByFeatureset_[currentFeatureset][conc].c_from = document.getElementById("fromConclusionValueNew").value;
                        conclusionsByFeatureset_[currentFeatureset][conc].c_to = document.getElementById("toConclusionValueNew").value;
                        printFeatureSet();
                        $('#updateConclusionModal').modal('hide');
                        break;
                    }
                }
            } else {
                document.getElementById("conclusionNameNew").value = document.getElementById("conclusionNameOld").innerHTML;
                document.getElementById("fromConclusionValueNew").value = document.getElementById("fromConclusionValueOld").innerHTML;
                document.getElementById("toConclusionValueNew").value = document.getElementById("toConclusionValueOld").innerHTML;
                
                window.alert(this.responseText, "Wrong response error.");
            }
        }
     };
     xmlhttp.open("GET","index.php/updateConclusion/" + conclusionOldAndNew, true);
     xmlhttp.send();
});

document.getElementById("download-featureset").addEventListener("click", function(event) {
    event.preventDefault();

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    //var json = "<pre>{\"featureset\":\"" + currentFeatureset + "\",\n";
    var json = "{\"featureset\":\"" + currentFeatureset + "\",\n";
        json += "       \"attributes\":[\n";

    var i = 0;
    var ranges = [];
    var attributes = [];
    
    var size = attributesByFeatureset_[currentFeatureset].length;
    
    var attrDone = [];
    
    // Number of attributes regardless of number of levels
    var attrN = 1;
    for (var attr = 1; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {
        if (attributesByFeatureset_[currentFeatureset][attr].attribute != attributesByFeatureset_[currentFeatureset][attr - 1].attribute) {
            attrN++;
        }
    }
    
    console.log(attrN);
    
    for (var attr = 0; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {
        
        // Attributes that have all its levels already printed do not need to be iterated again
        if (attrDone.includes(attributesByFeatureset_[currentFeatureset][attr].attribute)) {
            continue;
        }
    
        // Number of levels in current attribute
        var rangeN = 0;
        for (var i = 0; i < attributesByFeatureset_[currentFeatureset].length; i++) {
            if (attributesByFeatureset_[currentFeatureset][i].attribute == attributesByFeatureset_[currentFeatureset][attr].attribute) {
                rangeN++;
            }
        }

        json += "              ";
        json += "[{\"name\":\"" + attributesByFeatureset_[currentFeatureset][attr].attribute + "\"},\n";
        json += "               {\"range\":\"" + rangeN + "\"},\n";
        json += "               {\"from\":[\n";
        
        var fromN = 0;
        for (var i = 0; i < attributesByFeatureset_[currentFeatureset].length; i++) {
        
            if (attributesByFeatureset_[currentFeatureset][i].attribute != attributesByFeatureset_[currentFeatureset][attr].attribute) {
                continue;
            }
            
            fromN++;

            json += "                     ";

            json += "{\"value\":\"" + attributesByFeatureset_[currentFeatureset][i].a_from + "\"}";

            if (fromN < rangeN) {
                json += ",\n";
            } else {
                json += "]},\n";
            }
        }

        json += "               {\"to\":[\n";
        
        var toN = 0;
        for (var i = 0; i < attributesByFeatureset_[currentFeatureset].length; i++) {
        
            if (attributesByFeatureset_[currentFeatureset][i].attribute != attributesByFeatureset_[currentFeatureset][attr].attribute) {
                continue;
            }
            
            toN++;

            json += "                     ";

            json += "{\"value\":\"" + attributesByFeatureset_[currentFeatureset][i].a_to + "\"}";

            if (toN < rangeN) {
                json += ",\n";
            } else {
                json += "]},\n";
            }
        }

        json += "               {\"level\":[\n";
    
        var levelN = 0;
        for (var i = 0; i < attributesByFeatureset_[currentFeatureset].length; i++) {
        
            if (attributesByFeatureset_[currentFeatureset][i].attribute != attributesByFeatureset_[currentFeatureset][attr].attribute) {
                continue;
            }
            
            levelN++;

            json += "                     ";

            json += "{\"value\":\"" + attributesByFeatureset_[currentFeatureset][i].a_level + "\"}";

            if (levelN < rangeN) {
                json += ",\n";
            } else {
                if (attrN == attrDone.length + 1) {
                    json += "]}\n";
                } else {
                    json += "]}],\n";
                }
            }
        }

        if (attrN == attrDone.length + 1) {
            json += "              ]\n";
        }
        
        attrDone.push(attributesByFeatureset_[currentFeatureset][attr].attribute);
    }

    if (conclusionsByFeatureset_[currentFeatureset].length > 0) {
        json += "       ],\n";
        json += "       \"conclusions\":[\n";

        for (var i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
            json += "                     [{\"category\":\"" + conclusionsByFeatureset_[currentFeatureset][i].conclusion + "\"},\n";
            json += "                      {\"from\":\"" + conclusionsByFeatureset_[currentFeatureset][i].c_from + "\"},\n";
            json += "                      {\"to\":\"" + conclusionsByFeatureset_[currentFeatureset][i].c_to + "\"}]";
            if (i == conclusionsByFeatureset_[currentFeatureset].length - 1) {
                json += "]\n";
            } else {
                json += ",\n";
            }
        }
    } else {
        json += "       ]\n";
    }

    //json += "}</pre>";
    json += "}";
    
    // Small example
    /* {"featureset":"aaaqw",
            "attributes":[[{"name":"effort"},
                           {"range":"2"},
                           {"from":[{"value":"0"},
                                    {"value":"11"}]},
                            {"to":[{"value":"10"},
                                   {"value":"20"}]},
                            {"level":[{"value":"level1"},
                                      {"value":"level2"}]}],
                           [{"name":"motivation"},
                            {"range":"2"},
                            {"from":[{"value":"0"},
                                     {"value":"10"}]},
                            {"to":[{"value":"9"},
                                   {"value":"15"}]},
                            {"level":[{"value":"fraco"},
                                      {"value":"medio"}]}
                           ]
                         ],
            "conclusions":[[{"category":"underload"},
                            {"from":"0"},
                            {"to":"30"}],
                           [{"category":"fitting"},
                            {"from":"30"},
                            {"to":"60"}],
                           [{"category":"overload"},
                            {"from":"60"},
                            {"to":"100"}]]
        } */
    
    var e = document.createElement('a');
    e.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(json));
    e.setAttribute('download', currentFeatureset + ".json");

    e.style.display = 'none';
    document.body.appendChild(e);

    e.click();

    document.body.removeChild(e);
});



// Print first feature set
printFeatureSet();


</script>
