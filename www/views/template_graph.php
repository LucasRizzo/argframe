<div class='col-md-5 col-md-push-7' id='righ-side'>
    <span id="breakexpand"></span>
    <form class="form-inline" id="deleteGraphForm" action="index.php?action=deleteGraph" method="post" role="form">
        <div class="controls"> 
            <input type="hidden" maxlength="2000" name="featuresetName" id="featuresetName">
            <input type="hidden" maxlength="2000" name="graphName" id="graphName">
        </div>
    </form>

    <form class="form-inline" id="editGraphForm" action="index.php?action=update" method="post" role="form">
        <!-- <input type="hidden" maxlength="40" name="semantic" id="hiddenSemantic"> -->
        <div class="controlsEdit"> 
            <input type="hidden" maxlength="2000" name="editFeaturesetName" id="editFeaturesetName">
            <input type="hidden" maxlength="2000" name="oldGraphName" id="oldGraphName">
            <input type="hidden" maxlength="2000" name="fontsize" id="fontsize">
            <div class="edit-form-arguments">
                <input type="hidden" maxlength="2000" name="editArgument[]">
                <input type="hidden" maxlength="40" name="editLabel[]">
                <input type="hidden" maxlength="40" name="editConclusion[]">
                <input type="hidden" maxlength="40" name="editWeight[]">
            </div>
            <div class="edit-form-graph">
                <input type="hidden" maxlength="40" name="editSourceLabel[]">
                <input type="hidden" maxlength="40" name="editTargetLabel[]">
                <input type="hidden" maxlength="40" name="editTypeLabel[]"> 
            </div>
            <div class="edit-form-position">
                <input type="hidden" maxlength="40" name="editX[]">
                <input type="hidden" maxlength="40" name="editY[]">
            </div>
        </div>
    </form>
</div>


<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modalTitle">Add new node</h4>
            </div>

            <div class="modal-body">
                <div class="well">
                <form class="form-inline">
                    <fieldset>
                    <legend>Build premisses</legend>
                    <label for="sel1">&nbsp Select attribute: &nbsp</label>
                    <select class="form-control" onchange="setEditLevels()" id="editAttributes">
                    </select>
                    <label for="sel1">&nbsp Select level: &nbsp</label>
                    <select class="form-control" id="editLevels" onchange="setEditLevelsRange()">
                    </select>
                    <span id="levelRange"></span>
                    <br><br>&nbsp
                    <form class="form-inline">
                        <button class="btn shadow-sm btn-primary" data-toggle="tooltip" data-placement="top"
                                id="editAddPremise" ><span class="glyphicon glyphicon-list-alt"></span> Add premise</button>
                        <label for="sel1">&nbsp Operators: &nbsp</label>
                        <button class="btn-xs btn-primary" title="AND" type="submit" id="editAndOperator" ><span>AND</span></button>
                        <button class="btn-xs btn-primary" title="OR" type="submit" id="editOrOperator" ><span>OR</span></button>
                        <!-- <input type="radio" name="operator" value="AND" checked id="editAndOperator" disabled="true"> AND &nbsp -->
                        <!-- <input type="radio" name="operator" value="OR" id="editOrOperator" disabled="true"> OR -->
                    </form>
                    <!-- <select class="form-control" id="editOperator" disabled="true">
                        <option>AND</option>
                        <option>OR</option>
                    </select> -->
                    <b>&nbsp Parentheses: </b>
                    <button class="btn-xs btn-primary" title="Left Parentheses" type="submit" id="leftParentheses" ><span>(</span></button>
                    <button class="btn-xs btn-primary" title="Right Parentheses" type="submit" id="rightParentheses" ><span>)</span></button>
                    <br><br>&nbsp
                    <button class="btn btn-danger" disabled="true" type="submit" id="editDeletePremise" ><span class="glyphicon glyphicon-erase"></span> Remove last inclusion</button>
                    </fieldset>
                </form>
                </div>

                <div class="well">
                <form class="form-inline">

                    <label for="sel1">&nbsp Set weight: &nbsp</label>
                    <input type="number" class="form-control" id="editWeight" placeholder="None">

                </form>
                </div>

                <div class="well">
                <form class="form-inline">

                    <label for="sel1">&nbsp Select conclusion: &nbsp</label>
                    <select class="form-control" onchange="editAddConclusion()" id="editConclusions"></select>
                    <b>&nbsp Range</b>:
                    from
                    <code id="editConclusionFrom" style="width:10%;">none</code>
                    to
                    <code id="editConclusionTo" style="width:10%;">none</code>
                    <button class="btn-xs btn-warning" disabled="true" title="Invert range" type="submit" id="editInvertRange" ><span class="glyphicon glyphicon-refresh"></span></button>
                </form>
                </div>

                <div class="well">
                <form class="form-inline">
                    <fieldset>
                    <legend>Current argument
                    </legend>
                    <div style="margin-left: 7px;margin-right: 6px;">
                        <textarea id="editCurrentArgument" style="width:100%;resize:both;overflow:auto;"></textarea>
                    </div>
                    <span id="warningArgument" hidden></span>
                    <span id="acceptArgumentFlag" hidden>0</span>
                    </fieldset>
                </form>
                </div>

                <div class="well">
                    <form class="form-inline">
                        <label for="sel1">&nbsp Node label: &nbsp</label>
                        <input type="text" class="form-control" id="editLabel" placeholder="Enter label">
                        <button class="btn btn-success pull-right" data-toggle="tooltip" data-placement="top" title="Please add at least one premise and a lable in order to create a new argument." 
                        id="editNewArgument"><span class="glyphicon glyphicon-plus"> New argument</span></button>
                    </form>
                </div>
            </div>

            <div class="modal-footer" id="modalFooter">
                <button class="btn btn-primary pull-left" data-toggle="tooltip" data-placement="top" title="Please select a node in order to edit." 
                id="editAddArgument"><span class="glyphicon glyphicon-refresh"> Update argument</span></button>

                <button type="submit" class="btn btn-danger" id="editDeleteNode"
                    data-toggle="tooltip" data-placement="top" title="Delete node" >
                <span class="glyphicon glyphicon glyphicon-remove"> Delete node </span></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class='col-md-12'>
    <div id="graphAlerts" class="hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div id="graphAlerts_content"></div>
    </div>
</div>


<div id="modalHelp" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Graph help</h4>
            </div>

            <div class="modal-body">
                <ul> 
                <li>Add nodes clicking in the + sign</li>
                <li>Move nodes by clicking and dragging them.</li>
                <li>To add an edge: hold <i>shift</i>, click on a node and drag it to the target node.</li>
                <li>To remove an edge click on it and press delete.</li>
                <li>Edit nodes by clicking on them.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modalListArgs" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of arguments</h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-success">
                <div class="panel-heading">Arguments<span id="editListArgumentsN"></span></div>
                <div class="panel-body"><span id="editListArguments"></span></div>
                </div>

                <div class="panel panel-info">
                <div class="panel-heading">Attacks<span id="editListAttacksN"></span></div>
                <div class="panel-body"><span id="editListAttacks"></span></div>
                </div>

                <div class="panel panel-warning">
                <div class="panel-heading">Feature set<span id="editListAttributesN"></span></div>
                <div class="panel-body"><span id="editListAttributes"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modalCopyGraph" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Copy graph with a new name</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline" action="index.php?action=copyGraph" method="post" role="form">
                    <input type="text" form="editGraphForm" class="form-control" name="copyNameGraph" id="copyNameGraph" style="width:75%;" placeholder="Enter new copy name">
                    &nbsp
                    <button type="submit" form="editGraphForm" class="btn btn-primary pull-right" id="editCopyGraph"
                    data-toggle="tooltip" data-placement="top" title="Copy current graph on the server with new name" >
                    <span class="glyphicon glyphicon glyphicon-copy"> Copy graph </span></button>
                    <!-- <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button> -->
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalNewGraph" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create empty graph</h4>
            </div>
             <div class="modal-body ">
                <form class="form-inline" action="index.php?action=newGraph" method="post" role="form">
                    <label for="sel1">Select feature set: &nbsp</label>
                    <select class="form-control" name="featuresetNewGraph" id="featuresetNewGraph">
                        <?php foreach ($view_featuresets as $d) {
                            echo "<option value='" . $d . "'>" . $d . "</option>";
                        }
                        ?>
                    </select>
                    
                    <input type="text" class="form-control" onchange="checkNewGraphName()" name="newGraph" id="newGraph" placeholder="Enter graph name">
                    
                    <button class="btn btn-primary pull-right" type="submit" id="saveNewGraph" data-toggle="tooltip"
                            data-placement="top" title="Create new empty graph">
                            <span class="glyphicon glyphicon glyphicon-file"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalAttackType" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Choose attack type</h4>
            </div>
             <div class="modal-body">
                <form class="form-inline">
                    <form class="form-inline" id="editAttackType">
                        <input type="radio" name="operator" value="none" checked id="noAttackType"> None
                        <br>
                        <input type="radio" name="operator" value="undercut" id="undercut"> Undercut
                        <br>
                        <input type="radio" name="operator" value="undermine" id="undermine"> Undermine
                        <br>
                        <input type="radio" name="operator" value="rebuttal" id="rebuttal"> Rebuttal
                    </form>
                </form>
            </div>
            <!-- <label for="sel1">Attack label: &nbsp</label>
                        <input form="editGraphForm" id="editGraphName" maxlength="8"
                            onchange="checkGraphName()" type="text" class="form-control"
                            required name="editGraphName" style="width:30%;"> -->
            <div class="modal-footer">
                        <button type="submit" class="btn btn-danger pull-left" id="editDeleteEdge"
                    data-toggle="tooltip" data-placement="top" title="Delete edge" >
                    <span class="glyphicon glyphicon glyphicon-remove"> Delete edge </span>
            </button>
                <button type="button" class="btn btn-primary" id="updateAttack">Ok</button>
            </div>
        </div>
    </div>
</div>

<span id="attackID"></span>
<span id="attackLabel"></span>


<div id="modalUpdateGraph" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rename graph</h4>
            </div>
             <div class="modal-body">
                <form class="form-inline">
                    <label for="sel1">Current name: &nbsp</label>
                        <input form="editGraphForm" id="editGraphName" maxlength="8"
                            onchange="checkGraphName()" type="text" class="form-control"
                            required name="editGraphName" style="width:50%;">
                    &nbsp
                    <button type="submit" form="editGraphForm" class="btn btn-primary pull-right" id="editRenameGraph"
                            data-toggle="tooltip" data-placement="top" title="Rename current graph" >
                    <span class="glyphicon glyphicon-floppy-disk"> Rename graph </span></button>
                    <!-- &nbsp
                    <button type="submit" form="editGraphForm" class="btn btn-danger" id="editDeletGraph"
                            data-toggle="tooltip" data-placement="top" title="Delete current graph from the server" >
                    <span class="glyphicon glyphicon glyphicon-remove"> Delete graph </span></button> -->
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal new featureset -->
<div id="modalUploadGraph" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload graph</h4>
            </div>
             <div class="modal-body ">
                <label for="comment">Upload graph with JSON code (<a href="#" data-toggle="popover" title="JSON example" data-html="true"
                data-content="<div class='thumbnail'><a href='example_json_rules.png' target='_blank'><img src='example_json_rules.png' alt='JSON EXAMPLE' style='width:100%;heigth:100%'>
                <div class='caption'>
                    <p>Graphs can be created through the use of JSON files. Click on the figure for an example of JSON code and resulting graph.</p>
                </div></div>">?</a>):</label>&nbsp; &nbsp;<button class="btn-xs btn-primary" id="uploadNewJSONGraph" data-toggle="tooltip"
                        data-placement="top" title="Upload graph with JSON code">
                        <span class="glyphicon glyphicon-upload">&nbsp; Upload JSON </span>
                </button>
                <form class="form-inline">
                <textarea class="form-control" rows="10" style="width:100%;" name="jsoncodegraph" id="jsoncodegraph"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>

<div class='col-md-12' style='background-color:rgb(255, 255, 255);border-style: solid;border-width: 2px;border-radius: 3px' id='left-side'>

    <div id="toolbox">
      <input id="node-input" type="image" title="add node" style="width: 60px;" src="plus-icon-red-2.png" alt="upload graph">
      &nbsp
      <input id="rebuttals-input" type="image" title="add rebuttals" style="width: 45px;height:45px;" src="expand-arrows.png" alt="add rebuttals">
      <input type="file" id="hidden-file-upload">
    </div>
    <div id="toolboxbottomright">
        <form class="form-inline ">
            <input id="exapand-graph" type="image" title="hide menu" style='float:left;' src="right.png" alt="hide menu">
            <label id="editFeaturesetLabel"  for="sel1">&nbsp &nbsp Select feature set: &nbsp</label>
            <select class="form-control" name="featureset" id="editFeatureset">
                <?php foreach ($view_featuresetsWithGraphs as $d) {
                    echo "<option value='" . $d . "'>" . $d . "</option>";
                }
                ?>
            </select>
            <label for="sel1" id="editFeaturesetgraphLabel" >&nbsp Select graph: &nbsp</label>
                <select class="form-control" name="editFeaturesetgraph" id="editFeaturesetgraph">
            </select>
        </form>
    </div>

    <div id="toolboxtopleft">
        <input id="new-empty-graph" type="image" title="create empty graph" src="new-graph.png" alt="create empty grah">
        &nbsp
        <input id="rename-graph" type="image" title="rename graph" src="rename.png" alt="rename graph">
        &nbsp
        <input type="image" id="copy-graph" title="copy graph with a new name" src="copy.png" alt="copy graph">
        <input type="image" id="download-input" title="download graph" src="download.png" alt="download graph">
        <input type="image" id="upload-graph" title="upload graph" src="upload.png" alt="upload graph">
        <input type="image" id="editDeletGraph" title="delete graph" src="trash-icon.png" alt="delete graph">
        <input type="image" id="help-graph" title="help" src="question-icon.png" alt="help">
        <!-- Needs to edit graphEdition.js to work -->
        <!--<label class="checkbox-inline"><input name="automaticallysave" id="automaticallysave" type="checkbox" value="save" ><i> &nbsp Save automatically </i></label>-->
    </div>

    <div id="toolboxleft">
        <input id="listargs" type="image" title="list args" src="list-args2.png" alt="list">
        <br>
        <br>
        <input id="zoomin" type="image" title="zoom in" src="plus.png" alt="zoom">
        <br>
        <input id="zoomout" type="image" title="zoom out" src="minus.png" alt="zoom">
        <br>
        <br>
        <input type="image" id="aplus" title="increase font" src="aplus.png" alt="increase font">
        <br>
        <input type="image" id="aminus" title="decrease font" src="aminus.png" alt="decrease font">
        <br>
        <br>
    </div>

    <div id="toolboxtopright">
        <span id="warningfeatureset"></span>
        <span id="warningsaved"></span>
        <input id="save-graph" type="image" title="save graph" src="save-graph.png" alt="save grah" hidden>
        <span id="warningrenamed"></span>
    </div>
    <div id="toolboxtoprightbottom">
        <br>
        <svg height="17" width="200">
            <text x="100" y="15" fill="black">No conclusion</text>
        </svg>
        <br>
        <svg height="55" width="200">
            <circle cx="165" cy="25" r="20" stroke="black" stroke-width="2" fill="none" stroke-dasharray="10 10"/>
        </svg>
        <br>
        <svg height="40" width="200">
            <defs>
                <marker id="arrow" markerWidth="10" markerHeight="10" refX="0" refY="2" orient="auto" markerUnits="strokeWidth">
                    <path d="M0,0 L0,4 L6,2 z" fill="#A3C493" />
                </marker>
            </defs>
            <text x="130" y="10" fill="black">Undercut</text>
            <line x1="182" y1="26" x2="152" y2="26" stroke="#A3C493" stroke-width="3" marker-end="url(#arrow)" />
        </svg>
        <br>
        <svg height="40" width="200">
            <defs>
                <marker id="arrow2" markerWidth="10" markerHeight="10" refX="0" refY="2" orient="auto" markerUnits="strokeWidth">
                    <path d="M0,0 L0,4 L6,2 z" fill="#FFC300" />
                </marker>
            </defs>
            <text x="120" y="10" fill="black">Undermine</text>
            <line x1="182" y1="26" x2="152" y2="26" stroke="#FFC300" stroke-width="3" marker-end="url(#arrow2)" />
        </svg>
        <br>
        <svg height="40" width="200">
            <defs>
                <marker id="arrow3" markerWidth="10" markerHeight="10" refX="0" refY="2" orient="auto" markerUnits="strokeWidth">
                    <path d="M0,0 L0,4 L6,2 z" fill="#FF5733" />
                </marker>
            </defs>
            <text x="132" y="10" fill="black">Rebuttal</text>
            <line x1="182" y1="26" x2="152" y2="26" stroke="#FF5733" stroke-width="3" marker-end="url(#arrow3)" />
        </svg>
    </div>

    <script>

    var levels_ = <?php echo json_encode($view_levels, JSON_PRETTY_PRINT); ?>;

    var graphs_ = <?php echo json_encode($view_featuresetGraphs, JSON_PRETTY_PRINT); ?>;

    var args_ = <?php echo json_encode($view_featuresetArguments, JSON_PRETTY_PRINT); ?>;

    var conclusions_ = <?php echo json_encode($view_conclusionsByFeatureset, JSON_PRETTY_PRINT); ?>;

    var featuresets_ = <?php echo json_encode($view_featuresets, JSON_PRETTY_PRINT); ?>;

    var attributesByFeatureset_ = <?php echo json_encode($view_attributesByFeatureset, JSON_PRETTY_PRINT); ?>;

    var conclusionsByFeatureset_ = <?php echo json_encode($view_conclusionsByFeatureset, JSON_PRETTY_PRINT); ?>;

    </script>

    <script src=' <?php echo GRAPH_EDITION; ?> '></script>
</div>

<script>

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});


<?php if ($view_successType == "createGraph") {?>
var featureset = <?php echo json_encode($view_currentFeatureset, JSON_PRETTY_PRINT); ?>;
var graph =  <?php echo json_encode($view_currentGraph, JSON_PRETTY_PRINT); ?>;
<?php } ?>

var expand = false;

function checkGraphName(){
    var currentGraph = document.getElementById('editGraphName').value;
    var select = document.getElementById('editFeatureset'),
    i = select.selectedIndex;

    if (i != -1) {
        currentFeatureset = select.options[i].text;
        for (var i = 0; i < graphs_.length; i++) {
            if (graphs_[i].featureset == currentFeatureset && graphs_[i].name == currentGraph) {
                window.alert("Graph name for current feature set already exists. Please choose another graph name.", "Duplicate name error.")
                document.getElementById('editGraphName').value = "";
                break;
            }
        }
    }
}

function checkNewGraphName(){
    var currentGraph = document.getElementById('newGraph').value;
    var select = document.getElementById('featuresetNewGraph'),
    i = select.selectedIndex;

    if (i != -1) {
        currentFeatureset = select.options[i].text;
        for (var i = 0; i < graphs_.length; i++) {
            if (graphs_[i].featureset == currentFeatureset && graphs_[i].name == currentGraph) {
                window.alert("Graph name for current feature set already exists. Please choose another graph name.", "Duplicate name error.")
                document.getElementById('newGraph').value = "";
                break;
            }
        }
    }
}

document.getElementById("editInvertRange").addEventListener("click", function(event){
    event.preventDefault();
    var currentArgument = document.getElementById('editCurrentArgument');
    var premiseAndConclusion = String(currentArgument.value).split(" -> ");
    var conclusionsList = document.getElementById('editConclusions');
    // Temporarily remove conclusion from argument;
    currentArgument.value = premiseAndConclusion[0];
    var currentConclusion = premiseAndConclusion[1];
    var from = "";
    var to = "";
    var begin = 0;
    var end = 0;
    while (currentConclusion[begin] != "[") {
        begin++;
    }
    end = begin + 1;
    begin++; // Start of "from"
    while (currentConclusion[end] != ",") {
        end++;
    }
    end--; //End of "from"
    for (var i = begin; i <= end; i++) {
        from += currentConclusion[i];
    }
    // "to" start after end plus " ,";
    begin = end + 3; // Begin of "to";
    end = begin + 1;
    while (currentConclusion[end] != "]") {
        end++;
    }
    end--; // End of "to";
    for (var i = begin; i <= end; i++) {
        to += currentConclusion[i];
    }
    selectedConclusion = conclusionsList.options[conclusionsList.selectedIndex].text;
    currentArgument.value += " -> " + selectedConclusion + " [" + to + ", " + from + "]";
    document.getElementById("editConclusionFrom").innerHTML = to;
    document.getElementById("editConclusionTo").innerHTML = from;
});

document.getElementById("editFeatureset").addEventListener("change", function(event) {
    event.preventDefault();
    document.getElementById('editCurrentArgument').value = "";
    document.getElementById('editLabel').value = "";
    document.getElementById('editGraphName').value = "";

    addEditGraphs();
});

function validateArgument(argument) {

    if(argument.indexOf("AND") == 0 || argument.indexOf("OR") == 0) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Argument can not start with operators!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if(argument.indexOf("AND OR") != -1 || argument.indexOf("OR AND") != -1 ||
       argument.indexOf("OR OR") != -1 || argument.indexOf("AND AND") != -1) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Opperators wrongly connected!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (argument.indexOf("\" \"") != -1) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Attributes connected without operator!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (argument.indexOf("OR )") != -1 || argument.indexOf("AND )") != -1) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Operator wrongly connected to closing parateses!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (argument.indexOf("\" (") != -1 || argument.indexOf("\"(") != -1) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Opening parenteses after attribute without operator!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (argument.indexOf(") \"") != -1 || argument.indexOf(")\"") != -1) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Attribute wrongly connected to opperator!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (argument.indexOf(") (") != -1) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Parentheses connected withou operator!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (argument.indexOf("( )") != -1) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Parentheses without attributes inside!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (argument[argument.length - 1] == "(" || argument[argument.length - 1] == "R"
        || argument[argument.length - 1] == "D") {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Argument incomplete!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if ((argument.split(")").length + argument.split("(").length) % 2 != 0) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Argument incomplete!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    //Remove levels and evaluate possible true sets
    var searchBooleanAttr = "";
    // Auxiliar array to find levels of attributes in the true set
    var searchBooleanLevels = "";

    console.log(argument);
    // Start after first "
    var i = -1;
    do {

        i++;
        // Find first level
        while (i < argument.length && argument[i] != "\"") {
            searchBooleanAttr += argument[i];
            searchBooleanLevels += argument[i];
            i++;
        }

        if (i >= argument.length - 1) {
            break;
        }

        // find where level ends
        while (argument[i] != " " && i < argument.length - 1) {
            // Save level
            i++;
            
            if (argument[i] != " ") {
               searchBooleanLevels += argument[i]; 
            }
        }
        
        //console.log("2");
        //console.log(searchBooleanLevels);

        if (i >= argument.length - 1) {
            break;
        }

        // First letter of attribute
        i++;

        if (i >= argument.length - 1) {
            break;
        }

        // Copy attribute after level
        while (argument[i] != "\"" && i < argument.length - 1) {
            searchBooleanAttr += argument[i];
            i++;
        }
        
        //console.log("1");
        //console.log(searchBooleanAttr);
        
    // Continue while there is another attribute
    } while(i < argument.length - 1);

    console.log("s: " + searchBooleanAttr);
    console.log("l: " + searchBooleanLevels);

    var parsedQuery = parseBooleanQuery(searchBooleanAttr);
    var parsedQueryLevels = parseBooleanQuery(searchBooleanLevels);

    if (parsedQuery == undefined) {
        document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Argument incomplete!</font>';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    if (parsedQuery.length == 0) {
        document.getElementById('warningArgument').innerHTML = '';
        document.getElementById('warningArgument').hidden = false;
        document.getElementById('acceptArgumentFlag').innerHTML = "0";
        document.getElementById("editAddArgument").disabled = true;
        document.getElementById("editNewArgument").disabled = true;
        return;
    }

    //console.log(parsedQuery);

    for (var i = 0; i < parsedQuery.length; i++) {
        for (var j = 0; j < parsedQuery[i].length; j++) {
            //console.log(parsedQuery[i][j].indexOf(" OR"));
            if (parsedQuery[i][j].indexOf(" OR") != -1 || parsedQuery[i][j].indexOf(" AND") != -1) {
                document.getElementById('warningArgument').innerHTML = '<font color=\"red\">Argument incomplete!</font>';
                document.getElementById('warningArgument').hidden = false;
                document.getElementById('acceptArgumentFlag').innerHTML = "0";
                document.getElementById("editAddArgument").disabled = true;
                document.getElementById("editNewArgument").disabled = true;
                return;
            }
        }
    }

    document.getElementById('warningArgument').innerHTML = '<i>Possible true sets</i><br>';

    for (var i = 0; i < parsedQuery.length; i++) {
    
        var attrSplitAnd = parsedQuery[i].toString().split(",");
        var levelSplitAnd = parsedQueryLevels[i].toString().split(",");
        
        var trueSets = "";
        for (var j = 0; j < attrSplitAnd.length; j++) {
            trueSets += levelSplitAnd[j] + " " + attrSplitAnd[j];
            if (j < attrSplitAnd.length - 1) {
                trueSets += " AND ";
            }
        }
    
        document.getElementById('warningArgument').innerHTML += String(i + 1) + '. ' + trueSets + "<br>";
    }

    document.getElementById('warningArgument').hidden = false;

    document.getElementById('acceptArgumentFlag').innerHTML = "1";
    document.getElementById("editAddArgument").disabled = false;
    document.getElementById("editNewArgument").disabled = false;
}

document.getElementById("editAddPremise").addEventListener("click", function(event){

    event.preventDefault();

    document.getElementById('editDeletePremise').disabled = false;

    var select = document.getElementById('editAttributes'),
    i = select.selectedIndex,
    currentAttribute = select.options[i].text;

    var select = document.getElementById('editLevels'),
    i = select.selectedIndex,
    currentLevel = select.options[i].text;

    var currentArgument = document.getElementById('editCurrentArgument');

    var premiseAndConclusion = String(currentArgument.value).split(" -> ");
    currentArgument.value = premiseAndConclusion[0];

    // There is no conclusion. Just add new premise to the end of argument
    if (premiseAndConclusion.length == 1 || currentArgument.value == "") {
        currentArgument.value += " \"" + currentLevel + " " + currentAttribute + "\"";
    // There is a conclusion. Add new argument before conclusion.
    } else {
        currentArgument.value = premiseAndConclusion[0] + " \"" + currentLevel + " " + currentAttribute + "\"";
    }

    currentArgument.value = fixSpaces(currentArgument.value);
    validateArgument(currentArgument.value);

    if (premiseAndConclusion.length == 2) {
        currentArgument.value += " -> " + premiseAndConclusion[1]
    }
});

document.getElementById("leftParentheses").addEventListener("click", function(event){

    event.preventDefault();

    var currentArgument = document.getElementById('editCurrentArgument');

    var premiseAndConclusion = String(currentArgument.value).split(" -> ");

    currentArgument.value = premiseAndConclusion[0];
    currentArgument.value += " (";

    currentArgument.value = String(currentArgument.value).replace("  ", " ");
    if (String(currentArgument.value)[0] == " ") {
        currentArgument.value = String(currentArgument.value).slice(1);
    }

    currentArgument.value = fixSpaces(currentArgument.value);
    validateArgument(currentArgument.value);

    if (premiseAndConclusion.length == 2) {
        currentArgument.value +=  " -> " + premiseAndConclusion[1]
    }
});

document.getElementById("rightParentheses").addEventListener("click", function(event){

    event.preventDefault();

    var currentArgument = document.getElementById('editCurrentArgument');

    var premiseAndConclusion = String(currentArgument.value).split(" -> ");
    currentArgument.value = premiseAndConclusion[0];

    currentArgument.value += " )";

    currentArgument.value = String(currentArgument.value).replace("  ", " ");
    if (String(currentArgument.value)[0] == " ") {
        currentArgument.value = String(currentArgument.value).slice(1);
    }

    currentArgument.value = fixSpaces(currentArgument.value);
    validateArgument(currentArgument.value);

    if (premiseAndConclusion.length == 2) {
        currentArgument.value +=  " -> " + premiseAndConclusion[1]
    }
});

document.getElementById("editAndOperator").addEventListener("click", function(event){

    event.preventDefault();

    var currentArgument = document.getElementById('editCurrentArgument');

    var premiseAndConclusion = String(currentArgument.value).split(" -> ");
    currentArgument.value = premiseAndConclusion[0];
    currentArgument.value += " AND";

    currentArgument.value = String(currentArgument.value).replace("  ", " ");
    if (String(currentArgument.value)[0] == " ") {
        currentArgument.value = String(currentArgument.value).slice(1);
    }

    currentArgument.value = fixSpaces(currentArgument.value);
    validateArgument(currentArgument.value);

    if (premiseAndConclusion.length == 2) {
        currentArgument.value +=  " -> " + premiseAndConclusion[1]
    }
});

document.getElementById("editOrOperator").addEventListener("click", function(event){

    event.preventDefault();

    var currentArgument = document.getElementById('editCurrentArgument');

    var premiseAndConclusion = String(currentArgument.value).split(" -> ");
    currentArgument.value = premiseAndConclusion[0];
    currentArgument.value += " OR";

    currentArgument.value = String(currentArgument.value).replace("  ", " ");
    if (String(currentArgument.value)[0] == " ") {
        currentArgument.value = String(currentArgument.value).slice(1);
    }

    currentArgument.value = fixSpaces(currentArgument.value);
    validateArgument(currentArgument.value);

    if (premiseAndConclusion.length == 2) {
        currentArgument.value +=  " -> " + premiseAndConclusion[1]
    }
});

document.getElementById("editDeletePremise").addEventListener("click", function(event){

    event.preventDefault();

    var currentArgument = document.getElementById('editCurrentArgument').value;

    var premiseAndConclusion = String(currentArgument).split(" -> ");

    if (premiseAndConclusion.length == 2) {
        currentArgument = premiseAndConclusion[0];
    }

    if (currentArgument.length == 0) {
        return;
    }

    // Last inclusion is an attribute
    if (currentArgument[currentArgument.length - 1] == "\"") {

        // Find begenning of attribute
        i = currentArgument.length - 1;
        var characteres = 1;
        do {
            i--;
            characteres++;
        } while (currentArgument[i] != "\"");

        //console.log(characteres);

        currentArgument = currentArgument.slice(0, -characteres);
    // Last inclusion is a parateses
    } else if (currentArgument[currentArgument.length - 1] == "(" || 
        currentArgument[currentArgument.length - 1] == ")") {
        currentArgument = currentArgument.slice(0, -1);
    // Last inclusion is OR operator
    } else if (currentArgument[currentArgument.length - 1] == "R") {
        currentArgument = currentArgument.slice(0, -2);
        // Last inclusion is AND operator
    } else if (currentArgument[currentArgument.length - 1] == "D") {
        currentArgument = currentArgument.slice(0, -3);
    }

    if (currentArgument[currentArgument.length - 1] == " ") {
        currentArgument = currentArgument.slice(0, -1);
    }

    if (currentArgument.length == 0) {
        document.getElementById('warningArgument').innerHTML = '';
        document.getElementById('warningArgument').hidden = true;
    }

    if (currentArgument.length == 0 && premiseAndConclusion.length == 2) {
        document.getElementById('editCurrentArgument').value = "";

        // Set conclusion list to none
        var conclusionsList = document.getElementById("editConclusions");
        for (var i = 0; i < conclusionsList.options.length; i++) {
            if (conclusionsList.options[i].text == "None") {
                conclusionsList.selectedIndex = i;
                return;
            }
        }

        return;
    }

    if (premiseAndConclusion.length == 2) {
        document.getElementById('editCurrentArgument').value = currentArgument + " -> " + premiseAndConclusion[1];
    } else {
        document.getElementById('editCurrentArgument').value = currentArgument;
    }

    validateArgument(currentArgument);
});


function addEditGraphs() {

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    //console.log(featuresets_);

    var currentFeatureset = null;
    if (i != -1) {
        currentFeatureset = select.options[i].text;
    } else if (featuresets_.length > 0) {
        document.getElementById('warningfeatureset').innerHTML = '<font color=\"red\">Please create a new graph by clicking on the first button on the left.</font>';
    } else {
        // No feature set for current user. Disable menus
        document.getElementById('node-input').disabled = true;
        document.getElementById('rebuttals-input').disabled = true;
        document.getElementById('exapand-graph').disabled = true;
        document.getElementById('editFeatureset').disabled = true;
        document.getElementById('editFeaturesetgraph').disabled = true;
        document.getElementById('new-empty-graph').disabled = true;
        document.getElementById('rename-graph').disabled = true;
        document.getElementById('copy-graph').disabled = true;
        document.getElementById('upload-graph').disabled = true;
        document.getElementById('download-input').disabled = true;
        document.getElementById('editDeletGraph').disabled = true;
        document.getElementById('help-graph').disabled = true;
        document.getElementById('zoomin').disabled = true;
        document.getElementById('zoomout').disabled = true;
        document.getElementById('aplus').disabled = true;
        document.getElementById('aminus').disabled = true;
        document.getElementById('toolbox').id = 'toolboxdisabled';
        document.getElementById('toolboxbottomright').id = 'toolboxbottomrightdisabled';
        document.getElementById('toolboxtopleft').id = 'toolboxtopleftdisabled';
        document.getElementById('toolboxleft').id = 'toolboxleftdisabled';
        document.getElementById('warningfeatureset').innerHTML = '<font color=\"red\">No feature set! Please create a feature set in order to build new graphs.</font>';
    }

    var graphsList = document.getElementById("editFeaturesetgraph");

    // Clean current list
    for (var i = graphsList.length - 1; i >= 0; i--) {
        graphsList.remove(i);
    }

    for (var i = 0; i < graphs_.length; i++) {
        if (graphs_[i].featureset == currentFeatureset) {
            var option = document.createElement("option");
            option.text = graphs_[i].name;
            graphsList.add(option);
        }
    }

    if (graphsList.length == 0) {
        // Refresh empty list in case no graph was added.
        graphsList.selectedIndex = -1;
    }

    var select = document.getElementById('editFeaturesetgraph'),
    i = select.selectedIndex;
    if (i =! -1) {
        currentGraph = select.options[i].text;
        document.getElementById('editGraphName').value = currentGraph;
    } else {
        document.getElementById('editGraphName').value = "";
    }

    var event = new Event("change");
    graphsList.dispatchEvent(event);

    setEditNodeForm();
    editSetConclusions();
}

function editSetConclusions() {

    // Set attributes
    var conclusionsList = document.getElementById("editConclusions");

    // Clean current list
    for (var i = conclusionsList.length - 1; i >= 0; i--) {
        conclusionsList.remove(i);
    }

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    if (i == -1) return;

    currentFeatureset = select.options[i].text;

    var option = document.createElement("option");
        option.text = "None";
        conclusionsList.add(option);

    if (conclusions_[currentFeatureset].length == 0) {
        document.getElementById("editConclusions").disabled = true;
        return;
    }

    document.getElementById("editConclusions").disabled = false;
    for (var i = 0; i < conclusions_[currentFeatureset].length; i++) {
            var option = document.createElement("option");
            option.text = conclusions_[currentFeatureset][i].conclusion;
            conclusionsList.add(option);
    }
}

function setEditNodeForm() {

    // Set attributes
    var attributesList = document.getElementById("editAttributes");

    // Clean current list
    for (var i = attributesList.length - 1; i >= 0; i--) {
        attributesList.remove(i);
    }

    var attributes = <?php echo json_encode($view_attributes, JSON_PRETTY_PRINT); ?>;

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    if (i != -1) {
        currentFeatureset = select.options[i].text;
        for (var i = 0; i < attributes.length; i++) {
            if (attributes[i].featureset == currentFeatureset) {
                var option = document.createElement("option");
                option.text = attributes[i].attribute;
                attributesList.add(option);
            }
        }
    }

    setEditLevels();
}

function setEditLevels() {

    var levelsList = document.getElementById("editLevels");

    document.getElementById('levelRange').innerHTML = "";

    for (var i = levelsList.length - 1; i >= 0; i--) {
        levelsList.remove(i);
    }

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    if (i != -1) {
        currentFeatureset = select.options[i].text;

        var select = document.getElementById('editAttributes'),
        i = select.selectedIndex,
        currentAttribute = select.options[i].text;

        for (var i = 0; i < levels_.length; i++) {
            if (levels_[i].featureset == currentFeatureset && levels_[i].attribute == currentAttribute) {
                var option = document.createElement("option");
                option.text = levels_[i].a_level;
                levelsList.add(option);
            }
        }

        setEditLevelsRange();
    }
}

function setEditLevelsRange() {

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    if (i == -1)
        return;

    currentFeatureset = select.options[i].text;

    var select = document.getElementById('editAttributes'),
        i = select.selectedIndex;

    if (i == -1)
        return;

    currentAttribute = select.options[i].text;

    var select = document.getElementById('editLevels'),
        i = select.selectedIndex;

    if (i == -1)
        return;

    var currentLevel = select.options[i].text;

    for (var i = 0; i < levels_.length; i++) {
        if (levels_[i].featureset == currentFeatureset && 
            levels_[i].attribute == currentAttribute && 
            levels_[i].a_level == currentLevel) {
            document.getElementById('levelRange').innerHTML = " (from " +  levels_[i].a_from + " to " + levels_[i].a_to + ")";
            return;
        }
    }
}

function editAddConclusion() {

    var currentArgument = document.getElementById('editCurrentArgument');
    var conclusionsList = document.getElementById('editConclusions');

    // Check if there is a premise before adding any conclusion
    if (currentArgument.value == "") {
        window.alert("Please add at least one premise before choosing a conclusion.", "No premise error.");

        for (var i = 0; i < conclusionsList.options.length; i++) {
            if (conclusionsList.options[i].text == "None") {
                conclusionsList.selectedIndex = i;
                return;
            }
        }
    }

    var currentConclusion = conclusionsList.options[conclusionsList.selectedIndex].text;

    var premiseAndConclusion = String(currentArgument.value).split(" -> ");

    // There was no previous conclusion
    if (premiseAndConclusion.length == 1) {
        document.getElementById('editCurrentArgument').value += " -> " + currentConclusion;

        var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex,
        currentFeatureset = select.options[i].text;

        for (var i = 0; i < conclusions_[currentFeatureset].length; i++) {
            if (currentConclusion == conclusions_[currentFeatureset][i].conclusion) {
                document.getElementById("editConclusionFrom").innerHTML = conclusions_[currentFeatureset][i].c_from;
                document.getElementById("editConclusionTo").innerHTML = conclusions_[currentFeatureset][i].c_to;
                break;
            }
        }

        currentArgument.value += " [" + conclusions_[currentFeatureset][i].c_from + ", " +
                                    conclusions_[currentFeatureset][i].c_to + "]";

        document.getElementById('editInvertRange').disabled = false;
    // There was a previous conclusion and we need to remove that
    } else {
        if (currentConclusion == "None") {
            // Conclusion changed but to none, in this case the conclusion will
            // be removed from the argument and no conclusion will be added.
            currentArgument.value = premiseAndConclusion[0];

            document.getElementById("editConclusionFrom").innerHTML = "none";
            document.getElementById("editConclusionTo").innerHTML = "none";

            document.getElementById('editInvertRange').disabled = true;
        } else {
            // New argument will keep the premises and include a new concusion.
            currentArgument.value = premiseAndConclusion[0] + " -> " + currentConclusion;

            var select = document.getElementById('editFeatureset'),
            i = select.selectedIndex,
            currentFeatureset = select.options[i].text;

            for (var i = 0; i < conclusions_[currentFeatureset].length; i++) {
                if (currentConclusion == conclusions_[currentFeatureset][i].conclusion) {
                    document.getElementById("editConclusionFrom").innerHTML = conclusions_[currentFeatureset][i].c_from;
                    document.getElementById("editConclusionTo").innerHTML = conclusions_[currentFeatureset][i].c_to;
                    break;
                }
            }

            currentArgument.value += " [" + conclusions_[currentFeatureset][i].c_from + ", " +
                                     conclusions_[currentFeatureset][i].c_to + "]";

            document.getElementById('editInvertRange').disabled = false;
        }
    }
}

function setPrevious(){
    <?php if ($view_successType == "createGraph") {
    ?> 
        $('#warningrenamed').html('<font color=\"red\"> <?php echo $view_success; ?> </font>');
        setTimeout(function() {$('#warningrenamed').html('');}, 5000);

        // Set current graph as the graph created
        var featuresetEl = document.getElementById('editFeatureset');
        var index;
        for (var i=0; i<featuresetEl.length;i++) {
            if (featuresetEl[i].childNodes[0].nodeValue === featureset){
                featuresetEl.selectedIndex = i;
                break;
            }
        }

        var event = new Event("change");
        featuresetEl.dispatchEvent(event);

        var graphEl = document.getElementById('editFeaturesetgraph');
        var index;
        for (var i=0; i<graphEl.length;i++) {
            if (graphEl[i].childNodes[0].nodeValue === graph){
                graphEl.selectedIndex = i;
                break;
            }
        }

        var event = new Event("change");
        graphEl.dispatchEvent(event);

    <?php
    } else if ($view_successType == "deleteGraph") {
    ?> // Generic modal included in the index page
        $('#warningrenamed').html('<font color=\"red\"> <?php echo $view_success; ?> </font>');
        setTimeout(function() {$('#warningrenamed').html('');}, 5000);
        //$('#modal').modal('show');
        /*$("#modal").alert();
        $("#modal").fadeTo(2000, 500).slideUp(500, function(){
            $("#modal").slideUp(500);
        });*/
    <?php
    }
    ?>
}

// Initial edit form state
addEditGraphs();
setPrevious();
newEditionGrah();

document.getElementById("editFeaturesetgraph").addEventListener("change", function(event) {
    newEditionGrah();
});

document.getElementById("listargs").addEventListener("click", function(){
    printArguments();
    printAttacks();
    printFeatureSet();
    $('#modalListArgs').modal('show');
});

function printArguments() {

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    select = document.getElementById('editFeaturesetgraph');
    i = select.selectedIndex;

    var currentGraph = select.options[i].text;

    var html = "";
    var argumentsN = 0;
    for (var i = 0; i < args_.length; i++) {
        if (args_[i].featureset != currentFeatureset || 
            args_[i].graph != currentGraph) {
            continue;
        }

        if (args_[i].conclusion != "NULL") {
            html += "<i>" + args_[i].label + "</i>: " + 
                    args_[i].argument + " <b>&#8594;</b> " +
                    args_[i].conclusion + "<br>";
        } else {
            html += "<i>" + args_[i].label + "</i>: " + 
                    args_[i].argument + "<br>";
        }

        argumentsN++;
    }

    document.getElementById('editListArguments').innerHTML = html;
    document.getElementById('editListArgumentsN').innerHTML = " <b>(" + argumentsN.toString() + ")</b>";
}

function printAttacks() {
    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    select = document.getElementById('editFeaturesetgraph');
    i = select.selectedIndex;

    var currentGraph = select.options[i].text;

    var html = "";
    var attacksN = 0;
    for (var i = 0; i < graphs_.length; i++) {

        if (graphs_[i].featureset != currentFeatureset || graphs_[i].name != currentGraph) {
            continue;
        }

        var graphObj = JSON.parse(graphs_[i].edges);
        
        var rebuttalsAdded = [];

        for (var j = 0; j < graphObj.length; j++) {
        
            var arrow = " &rArr; ";
            var skip = false;
            if (graphObj[j].type == "rebuttal") {
                for (var k = 0; k < rebuttalsAdded.length; k++) {                
                    // It has been added already
                    if (rebuttalsAdded[k].source == graphObj[j].target && rebuttalsAdded[k].target == graphObj[j].source) {
                        skip = true;
                        break;
                    }
                }
            
                arrow = " &lArr;&rArr; ";
                
                rebuttalsAdded.push({source: graphObj[j].source, target: graphObj[j].target});
            }
            
            if (skip) {
                continue;
            }

            html += "<i>" + graphObj[j].source + "</i>" + arrow + 
                "<i>" + graphObj[j].target + "</i><br>";
                attacksN++;
        }

        //console.log(graphObj);
        break;
    }

    document.getElementById('editListAttacks').innerHTML = html;
    document.getElementById('editListAttacksN').innerHTML = " <b>(" + attacksN.toString() + ")</b>";
}

function printFeatureSet() {

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    var html = "";

    // Run through all attributes and their respective level in order
    // to find the current premise's range
    var currentAttribute = attributesByFeatureset_[currentFeatureset][0].attribute;
    html += "<i>Attribute</i>: " + attributesByFeatureset_[currentFeatureset][0].attribute + "<br>";
    var attributesN = 1;
    for (var attr = 0; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {

        if (attributesByFeatureset_[currentFeatureset][attr].attribute != currentAttribute) {
            html += "<br><i>Attribute</i>: " + attributesByFeatureset_[currentFeatureset][attr].attribute + "<br>";
            currentAttribute = attributesByFeatureset_[currentFeatureset][attr].attribute;
            attributesN++;
        }

        html += "<i>Level</i>: " + attributesByFeatureset_[currentFeatureset][attr].a_level +
                ", <i>From:</i> " + attributesByFeatureset_[currentFeatureset][attr].a_from +
                ", <i>To:</i> " + attributesByFeatureset_[currentFeatureset][attr].a_to +
                "<br>";
    }

    for (var conc = 0; conc < conclusionsByFeatureset_[currentFeatureset].length; conc++) {
        if (conc == 0) {
            html += "<br>";
        }

        html += "<i>Conclusion</i>: " + conclusionsByFeatureset_[currentFeatureset][conc].conclusion + "<br>" +
                "<i>From:</i> " + conclusionsByFeatureset_[currentFeatureset][conc].c_from +
                ", <i>To:</i> " + conclusionsByFeatureset_[currentFeatureset][conc].c_to + "<br>"; 

        if (conc != conclusionsByFeatureset_[currentFeatureset].length - 1) {
            html += "<br>";
        }

        attributesN++;
    }

    document.getElementById('editListAttributes').innerHTML = html;
    document.getElementById('editListAttributesN').innerHTML = " <b>(" + attributesN.toString() + ")</b>";
}


</script> 




