<?php require_once "semantics/dungAF.php"; 

ini_set('max_post_size', '100M');
ini_set('php_max_size', '100M');


?>

<div id="modalAccrualHelp" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Accrual help</h4>
            </div>

            <div class="modal-body">
                <b>Suppose the following set of 5 arguments:<br></b>
                <pre>&#8226 <i>A1</i>: mediumUpper [50, 66] effort &#8594; fittingPlus [50, 66]
&#8226 <i>A2</i>: mediumUpper [50, 66] performance &#8594; fittingMinus [49, 33]
&#8226 <i>A3</i>: low [0, 32] mentalDemand &#8594; underload [0, 32]
&#8226 <i>A4</i>: low [0, 32] temporalDemand &#8594; underload [0, 32]
&#8226 <i>A5</i>: low [0, 32] frustration &#8594; underload [0, 32]</pre>
                
                <b>In addition, suppose the following input values and respective computed values<font color="red">*</font> for each argument:<br></b>
<pre>&#8226 effort = 50, weighted_effort = 4, A1 = 50
&#8226 performance = 50, weighted_performace = 5, A2 = 49
&#8226 mentalDemand = 4, weighted_mentalDemand = 3, A3 = 4
&#8226 temporalDemand = 1, weighted_temporalDemand = 2, A4 = 1
&#8226 frustration = 1, weighted_frustration = 1, A5 = 1</pre>
                
                <b>In this case, the accrual options will work in the following way:<br></b>
                <ul>
                    <li><mark>Sum</mark>: <code>50 + 49 + 4 + 1 + 1 = 105</code> </li>
                    <li><mark>Average</mark>: <code>(50 + 49 + 4 + 1 + 1) / 5 = 21</code> </li>
                    <li><mark>Highest cardinality</mark>: <code>(4 + 1 + 1) / 3 = 2</code>. Average of values inferred by arguments with highest cardinality conclusion. In this case, conclusion cardinalities are: underload = 3, fittingMinus = 1, and fittingPlus = 1. </li>
                    <li><mark>Median</mark>: middle of the ordered conclusion values <code>1, 1, 4, 49, 50 = 4</code></li>
                    <li><mark>Weighted average</mark>: <code>(50 x 4 + 49 x 5 + 4 x 3 + 1 x 2 + 1 x 1) / (4 + 5 + 3 + 2 + 1) = 460 / 15 = 30.666</code> </option>
                    <li><mark>Highest conclusion</mark>: <code>66</code>, relative to argument A1 (despite calculated value of A1 being 50 in this example)</li>
                    <li><mark>Highest and weighted</mark>: <code>(4 x 3 + 1 x 2 + 1 x 1) / (3 + 2 + 1) = 2.5</code>. Weighted average of values inferred by arguments with highest cardinality conclusion. In this case, conclusion cardinalities are: underload = 3, fittingMinus = 1, and fittingPlus = 1.</li>
                </ul>
                
                <b><font color="red">*</font> Rule values are computed according to a linear relationship between premisses and conclusions. Operators AND and OR (if used in the arguments premises) are replaced by min and max. More information is on the <a href="<?php echo ADDRESS_PREFIX; ?>index.php/">documentation</a> page.</b>
                
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
                <div class="panel-heading">Arguments<span id="listArgumentsN"></span></div>
                <div class="panel-body"><span id="listArguments"></span></div>
                </div>

                <div class="panel panel-info">
                <div class="panel-heading">Attacks<span id="listAttacksN"></span></div>
                <div class="panel-body"><span id="listAttacks"></span></div>
                </div>

                <div class="panel panel-warning">
                <div class="panel-heading">Feature set<span id="listAttributesN"></span></div>
                <div class="panel-body"><span id="listAttributes"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modalListArgsFiltered" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of arguments</h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-success">
                <div class="panel-heading">Arguments<span id="listArgumentsNFiltered"></span></div>
                <div class="panel-body"><span id="listArgumentsFiltered"></span></div>
                </div>

                <div class="panel panel-info">
                <div class="panel-heading">Attacks<span id="listAttacksNFiltered"></span></div>
                <div class="panel-body"><span id="listAttacksFiltered"></span></div>
                </div>

                <div class="panel panel-warning">
                <div class="panel-heading">Feature set<span id="listAttributesNFiltered"></span></div>
                <div class="panel-body"><span id="listAttributesFiltered"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="modalExport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Export final accrual for whole dataset</h4>
            </div>

            <div class="modal-body">
                <form class="form-inline" id="formSemantics">
                    
                    <!-- 
                    This is a legacy option, which allows the import of weights for features. The weight of each feature
                    is used to calculate the weight of the arguments employing them. Consequently, arguments with lower 
                    weight cannot attack arguments with higher weight, and such attacks are removed. This option does not
                    work very well if a single argument employs multiple features. There is now a new option to import 
                    argument weights directly and use the concept of an inconsistency budget to determine which attacks 
                    should be kept. -->
                    <div class="form-group" style="display: none;">
                        <label class="checkbox-inline"><input id="strengthCheckBoxExport" name="strengthCheckBoxExport" type="checkbox" value="Empty" class="check2">Strength of arguments</label></br>
                        _______________________
                        <br>
                        <br>
                    </div>                   
                    <b>Semantics</b></br></br>
                    <div class="form-group">
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Expert System">Expert System</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Preferred">Preferred</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Categoriser">Rank based: Categoriser</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Grounded">Grounded</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Eager">Eager</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Ideal">Ideal</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Stable">Stable</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Semi-stable">Semi-stable</label></br>
                        <label class="checkbox-inline"><input name="semanticsExport" type="checkbox" value="Admissible">Admissible</label></br>
                    </div>
                    </br>
                    _______________________
                    </br>
                    <br>
                    <b>Accrual</b></br></br>
                    <div class="form-group">
                    
                        <label class="checkbox-inline"><input name="accrualExport" type="checkbox" value="Sum">Sum</label></br>
                        <label class="checkbox-inline"><input name="accrualExport" type="checkbox" value="Average">Average</label></br>
                        <label class="checkbox-inline"><input name="accrualExport" type="checkbox" value="Highest cardinality">Highest cardinality</label></br>
                        <label class="checkbox-inline"><input name="accrualExport" type="checkbox" value="Median">Median</label></br>
                        <label class="checkbox-inline"><input name="accrualExport" type="checkbox" value="Weighted average">Weighted average</label></br>
                        <label class="checkbox-inline"><input name="accrualExport" type="checkbox" value="Highest conclusion">Highest conclusion</label></br>
                        <label class="checkbox-inline"><input name="accrualExport" type="checkbox" value="Highest and weighted">Highest and weighted</label></br>
                        
                    </div>
                    <br>
                    <br>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="submit" id="exportFile" ><span class="glyphicon glyphicon-export"></span>&nbsp Export</button>

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-5 col-md-push-7">
    <div class="form-inline">
        <label for="file" style="float:left;margin-right:5px;">Import data for current feature set: &nbsp</label><input class="custom-file-input" id="files" type="file" accept=".csv" name="file" />
        <br>
        <label for="sel1">Select chunk size (<a href="#" data-toggle="popover" title="CSV chunk size" data-content="The imported file will be stored and processed locally. Any size file can be imported. However, it might need to be broken into chunks so the browser does not crash or freeze. If your file size is smaller than the default chunk size, just ignore this option. When computing the whole dataset (through the Export option), chunks will be processed one at a time, returning one file for each chunk. For example, if a 200mb file is imported, and the chunk size is set to 50mb, the file will be broken into 4 chunks. When exported, the results will be given in 4 files with only the first one containing a header.">?</a>): &nbsp</label>
        <select class="form-control" id="chunkSize">
            <option>50mb (default)</option>
            <option>5mb</option>
            <option>10mb</option>
            <option>25mb</option>
            <option>75mb</option>
            <option>100mb</option>
            <option>150mb</option>
            <option>200mb</option>
        </select>
    </div>

    <span id="overallControl" style="visibility: hidden"></span>

    <div id='datatable-sample'>
        <table id='featuresetTable' class='display' cellspacing='0' width='100%'>
            <thead><tr><td></td></td></thead><tbody><tr><td></td></tr></body>
        </table>
    </div>
    <br>
    <div class="col-md-12">
        <div class="well well-lg">
            <span id="maxTableWarning"></span>
            <form class="form-inline" id="formOverall">
                <!-- 
                This is a legacy option, which allows the import of weights for features in the dataset.
                The weight of each feature is used to calculate the weight of the arguments employing them. 
                Consequently, arguments with lower weight cannot attack arguments with higher weight, and such attacks 
                are removed. This option does not work very well if a single argument employs multiple features. There 
                is now a new option to import argument weights directly and use the concept of an inconsistency budget 
                to determine which attacks should be kept.  -->
                <div class="form-group" style="display: none;">
                    <label class="checkbox-inline"><input id="strengthCheckBox" name="strengthCheckBox" type="checkbox" value="Empty" class="check2">Strength of arguments</label></br>
                    _______________________
                    <br>
                    <br>
                </div>
            </form>
            
            <div class='form-inline'>
                <label for="sel1">Select accrual: &nbsp</label>
                <select class="form-control" id="accrualVisualization">
                    <option>Sum</option>
                    <option>Average</option>
                    <option>Highest cardinality</option>
                    <option>Median</option>
                    <option>Weighted average</option>
                    <option>Highest conclusion</option>
                    <!--<option>Absolute highest conclusion</option>-->
                    <!--<option># Conclusions</option>-->
                    <option>Highest and weighted</option>
                </select>
                <button type="button" onclick="showAccrualHelp()" class="btn btn-default"> <span class="glyphicon glyphicon glyphicon-question-sign"></span></button>
            </div>
            _______________________
            <br>
            <br>
            <form class="form-inline">
                <form class="form-inline" id="formOverall">
                    <div class="form-group">
                        <label class="checkbox-inline"><input id="overallCheckBox" name="overallCheckBox" type="checkbox" value="Empty" class="check1">Overall matches</label></br>
                        <span id="overallResults"></span>
                    </div>
                    <br>
                    <div id='resultsColumn'>
                        <br>
                        <b><kbd>Inference:</kbd> </b><a href="#" id='resultsContent' data-toggle="popover" data-html="true" data-content="Import data in order to compute extensions."></a>
                        <br>
                    </div>
                </form>
            </form>
        </div>
        
        <div class="well well-lg">
        <button class="btn btn-default btn-block" type="submit" id="export" disabled="true" ><span class="glyphicon glyphicon-export"></span>&nbsp Export</button>
        <span id="exportControl" style="visibility: hidden"></span>
        </div>
        
    </div>

    <div class="col-md-12 hidden" id="progressRow">
        <b>Exporting results</b>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" id="progressBar" role="progressbar" aria-valuenow="10"
            aria-valuemin="0" aria-valuemax="100" style="width:0%">
                 <p id="progressLabel"> 0% Complete</p>
            </div>
        </div>
    </div>
    <input id="row_input" type="hidden" value="">
</div>

<div class='col-md-7 col-md-pull-5' style='background-color: rgb(255, 255, 255);border-style: solid;border-width: 2px;border-radius: 3px' id='left-side'>

    <div id="toolboxbottomleft">
        <form class="form-inline">
            <label for="sel1">Select feature set: &nbsp</label>
            <select class="form-control confirmation-callback" name="featureset" id="featureset">
                <?php foreach ($view_featuresets as $d) {
                    echo "<option value='" . $d . "'>" . $d . "</option>";
                }
                ?>
            </select>
            <span id="data-download"></span>
            <label for="sel1">&nbsp &nbsp Select graph: &nbsp</label>
                <select class="form-control confirmation-callback" name="featuresetgraph" id="featuresetgraph">
            </select>
        </form>
    </div>

    <div id="toolboxtop">
        <!-- <input type="image" style="width: 120px;" disabled="true" id="copy-graph" title="copy graph with a new name" src="export-icon-7.png" alt="copy graph"> -->
    </div>

    <div id="toolboxinfo">
        <div class='form-inline'>
            <label for="sel1">Select semantics visualization: &nbsp</label>
            <select class="form-control" id="semanticsVisualization" disabled="true">
                <option>Preferred</option>
                <option>Grounded</option>
                <option>Rank based: Categoriser</option>
                <option>Eager</option>
                <option>Ideal</option>
                <option>Stable</option>
                <option>Semi-stable</option>
                <option>Activated</option>
                <option>Expert System</option>
                <option>Admissible</option>
            </select>
        </div>

        <style>
            /* Style of the range used for the inconsistency budget. Very weird. */
            input[type="range"] {
                -webkit-appearance: none;
                width: 100%;
                height: 8px;
                background: #ddd;
                outline: none;
                opacity: 0.7;
                transition: opacity .2s;
            }

            input[type="range"]:hover {
                opacity: 1;
            }

            input[type="range"]::-webkit-slider-runnable-track {
                width: 100%;
                height: 8px;
                cursor: pointer;
                background: darkgrey;
                border-radius: 4px;
            }

            input[type="range"]::-webkit-slider-thumb {
                height: 16px;
                width: 16px;
                border-radius: 50%;
                background: #555; /* Darker shade of grey for the thumb */
                cursor: pointer;
                -webkit-appearance: none;
                margin-top: -4px; /* Adjust the margin to align the thumb with the track */
            }

            input[type="range"]::-moz-range-track {
                width: 100%;
                height: 8px;
                cursor: pointer;
                background: darkgrey;
                border-radius: 4px;
            }

            input[type="range"]::-moz-range-thumb {
                height: 16px;
                width: 16px;
                border-radius: 50%;
                background: #555; /* Darker shade of grey for the thumb */
                cursor: pointer;
            }

            input[type="range"]::-ms-track {
                width: 100%;
                height: 8px;
                cursor: pointer;
                background: transparent;
                border-color: transparent;
                color: transparent;
            }

            input[type="range"]::-ms-fill-lower {
                background: darkgrey;
                border-radius: 4px;
            }

            input[type="range"]::-ms-fill-upper {
                background: darkgrey;
                border-radius: 4px;
            }

            input[type="range"]::-ms-thumb {
                height: 16px;
                width: 16px;
                border-radius: 50%;
                background: #555; /* Darker shade of grey for the thumb */
                cursor: pointer;
                margin-top: -4px; /* Adjust the margin to align the thumb with the track */
            }
        </style>

        <div class="form-inline" id="inconsistency-div" style="margin-top: 5px;">
            <label for="sel1" class="mr-2">Inconsistency budget: &nbsp </label>0
            <input type="range" id="inconsistencyRange" name="points2" style="box-shadow: none !important; display: inline-block; width: auto; vertical-align: middle;" class="form-range">
            <span id="total-inconsistency"></span> (<span id="current-inconsistency"></span>)
        </div>

        <div class='form-inline' style="visibility: hidden; margin-top: 5px;" id="extensions">

            <label for="sel1">Extension: &nbsp</label>
            <select class="form-control" id="extensionNumber">
            </select>
            <p id="nExtensions" style="display:inline"></p>
        </div>
    </div>

    <div id="toolboxtopleft">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <input id="listargs" type="image" title="whole knowledge base" src="list-args2.png" alt="list">
        <br>
        <input id="listargsFilter" type="image" title="activated knowledge base" src="listfilter.png" alt="list">
        <br>
        <br>
        <input id="zoomin" type="image" title="zoom in" src="plus.png" alt="zoom">
        <br>
        <input id="zoomout" type="image" title="zoom out" src="minus.png" alt="zoom">
    </div>
    <div id="toolboxtopright">
        <span id="warningfeatureset"></span>
        <svg height="75" width="200">
            <circle cx="160" cy="45" r="20" stroke="black" stroke-width="2" fill="none" stroke-dasharray="10 10"/>
            <text font-size="12" x="110" y="10" fill="black">No conclusion</text>
        </svg>
        <br>
        <svg height="75" width="200">
            <circle cx="160" cy="45" r="20" stroke="black" stroke-width="2" fill="#00FA9A"/>
            <text font-size="12" x="135" y="10" fill="black">Accepted</text>
        </svg>
        <br>
        <svg height="90" width="200">
            <circle cx="160" cy="60" r="20" stroke="black" stroke-width="2" fill="#ff9393"/>
            <text font-size="12" x="137" y="10" fill="black">Rejected</text>
            <text font-size="12" x="127" y="25" fill="black">Undecided</text>
        </svg>
        <br>
        <svg height="90" width="200">
            <circle cx="160" cy="55" r="20" stroke="black" stroke-width="2" fill="#a5c7ff" stroke-dasharray="10 10"/>
            <text font-size="12" x="107" y="10" fill="black">No conclustion</text>
            <text font-size="12" x="137" y="25" fill="black">activated</text>
        </svg>
        <br>
        
        
        <svg height="40" width="200">
            <defs>
                <marker id="arrow" markerWidth="10" markerHeight="10" refX="0" refY="2" orient="auto" markerUnits="strokeWidth">
                    <path d="M0,0 L0,4 L6,2 z" fill="#A3C493" />
                </marker>
            </defs>
            <text font-size="12" x="137" y="10" fill="black">Undercut</text>
            <line x1="182" y1="26" x2="152" y2="26" stroke="#A3C493" stroke-width="3" marker-end="url(#arrow)" />
        </svg>
        <br>
        <svg height="40" width="200">
            <defs>
                <marker id="arrow2" markerWidth="10" markerHeight="10" refX="0" refY="2" orient="auto" markerUnits="strokeWidth">
                    <path d="M0,0 L0,4 L6,2 z" fill="#FFC300" />
                </marker>
            </defs>
            <text font-size="12" x="127" y="10" fill="black">Undermine</text>
            <line x1="182" y1="26" x2="152" y2="26" stroke="#FFC300" stroke-width="3" marker-end="url(#arrow2)" />
        </svg>
        <br>
        <svg height="40" width="200">
            <defs>
                <marker id="arrow3" markerWidth="10" markerHeight="10" refX="0" refY="2" orient="auto" markerUnits="strokeWidth">
                    <path d="M0,0 L0,4 L6,2 z" fill="#FF5733" />
                </marker>
            </defs>
            <text font-size="12" x="139" y="10" fill="black">Rebuttal</text>
            <line x1="182" y1="26" x2="152" y2="26" stroke="#FF5733" stroke-width="3" marker-end="url(#arrow3)" />
        </svg>
        
    </div>

    <script>

    var graphs_ = <?php echo json_encode($view_featuresetGraphs, JSON_PRETTY_PRINT); ?>;

    var args_ = <?php echo json_encode($view_featuresetArguments, JSON_PRETTY_PRINT); ?>;

    var attributesByFeatureset_ = <?php echo json_encode($view_attributesByFeatureset, JSON_PRETTY_PRINT); ?>;

    var conclusionsByFeatureset_ = <?php echo json_encode($view_conclusionsByFeatureset, JSON_PRETTY_PRINT); ?>;
    
    var featuresets_ = <?php echo json_encode($view_featuresets, JSON_PRETTY_PRINT); ?>;

    var file_;

    var attributes_ = <?php echo json_encode($view_attributes, JSON_PRETTY_PRINT); ?>;

    </script>

    <script src=' <?php echo GRAPH_VISUALIZATION; ?> '></script>
</div>



<script>

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
    $("#editFeatureset").fadeOut(500);
});

//console.log(conclusionsByFeatureset_);

var addressCall_ = <?php echo json_encode(ADDRESS_CALL, JSON_PRETTY_PRINT); ?>;
var absoluteCall_ = <?php echo json_encode(ABSOLUTE_CALL, JSON_PRETTY_PRINT); ?>;

document.getElementById("listargs").addEventListener("click", function(){
    printArguments();
    printAttacks();
    printFeatureSet();
    $('#modalListArgs').modal('show');
});

document.getElementById("listargsFilter").addEventListener("click", function(){
    $('#modalListArgsFiltered').modal('show');
});

function printArguments() {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    select = document.getElementById('featuresetgraph');
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

    document.getElementById('listArguments').innerHTML = html;
    document.getElementById('listArgumentsN').innerHTML = " <b>(" + argumentsN.toString() + ")</b>";
}

function printAttacks() {
    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    select = document.getElementById('featuresetgraph');
    i = select.selectedIndex;

    var currentGraph = select.options[i].text;

    var html = "";
    var attacksN = 0;
    for (var i = 0; i < graphs_.length; i++) {

        if (graphs_[i].featureset != currentFeatureset || graphs_[i].name != currentGraph) {
            continue;
        }

        var graphObj = JSON.parse(graphs_[i].edges);

        for (var j = 0; j < graphObj.length; j++) {
            html += "<i>" + graphObj[j].source + "</i>  &rArr; " + 
                "<i>" + graphObj[j].target + "</i><br>";

            attacksN++;
        }

        //console.log(graphObj);
        break;
    }

    document.getElementById('listAttacks').innerHTML = html;
    document.getElementById('listAttacksN').innerHTML = " <b>(" + attacksN.toString() + ")</b>";
}

function printFeatureSet() {

    var select = document.getElementById('featureset'),
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

    document.getElementById('listAttributes').innerHTML = html;
    document.getElementById('listAttributesN').innerHTML = " <b>(" + attributesN.toString() + ")</b>";
}


function addGraphs() {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = null;
    if (i != -1) {
        currentFeatureset = select.options[i].text;

        if (currentFeatureset == "trust_features") {
            document.getElementById("data-download").setAttribute("class", "glyphicon glyphicon-download");
            document.getElementById("data-download").innerHTML = "&nbsp <a href='https://www.lucasrizzo.com/framework/data/journal_trust.zip'>Example data</a>";
        } else if (currentFeatureset == "fish_surf") {
            document.getElementById("data-download").setAttribute("class", "glyphicon glyphicon-download");
            document.getElementById("data-download").innerHTML = "&nbsp <a href='https://www.lucasrizzo.com/framework/data/toy_example.csv'>Example data</a>";
        } else {
            document.getElementById("data-download").innerHTML = "";
            document.getElementById("data-download").setAttribute("class", "");
        }

    } else {
    
        document.getElementById('files').disabled = true;
        document.getElementById('strengthCheckBox').disabled = true;
        document.getElementById('overallCheckBox').disabled = true;
        document.getElementById('export').disabled = true;
        document.getElementById('listargs').disabled = true;
        document.getElementById('listargsFilter').disabled = true;
        document.getElementById('zoomin').disabled = true;
        document.getElementById('zoomout').disabled = true;
        document.getElementById('featureset').disabled = true;
        document.getElementById('featuresetgraph').disabled = true;
        document.getElementById("data-download").innerHTML = "";
        document.getElementById("data-download").setAttribute("class", "");
    
        if (featuresets_.length > 0) {
            window.alert("Please create a new graph in the graphs page!", "No graphs error.");
        } else {
            // No feature set for current user. Disable menus
            window.alert("No feature set! Please create a feature set and a new graph before importing and computing data!", "No feature set and graph error.");
        }
    }
    
    


    var graphsList = document.getElementById("featuresetgraph");

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

    var event = new Event("change");
    graphsList.dispatchEvent(event);
}

addGraphs();
function showAccrualHelp() {
    $('#modalAccrualHelp').modal('show');
}

document.getElementById("featureset").addEventListener("change", function(event) {
    event.preventDefault();

    addGraphs();

    var columns = [];
    columns.push({sTitle: "", mData: "", aTargets: 0});

    // Update table
    var oTable = $('#datatable-sample').html('<table class="display" cellspacing="0" width="100%" id="featuresetTable"></table>').children('table').DataTable({
        "scrollX": true,
        "paging": false,
        "search": false,
        //"bFilter": false
        //"bLengthChange": false,
        "aoColumnDefs": columns,
        "aaData": []
        //"bDestroy": true
    });

    document.getElementById('files').value = "";

    document.getElementById('semanticsVisualization').disabled = true;
    document.getElementById('export').disabled = true;
    document.getElementById('strengthCheckBox').disabled = true;

    var checkboxes = document.getElementsByName("semanticsExport");
    //document.getElementById("timeLimit").value = 1;

    var checkboxesChecked = [];

    // loop over them all
    for (var i=0; i<checkboxes.length; i++) {
        // And stick the checked ones onto an array...
        checkboxes[i].checked = false;
    }

    allData_ = [];
    document.getElementById("overallResults").innerHTML = "";
    document.querySelectorAll('.check1')[0].checked = false;
    document.querySelectorAll('.check2')[0].checked = false;
});

$(document).ready(function() {

     if (isAPIAvailable()) {
    /*  Explaining the magic:
        But what about that event argument in the sayHello function — what is it and why does it matter? In all DOM event callbacks, jQuery passes an event 
        object argument which contains information about the event, such as precisely when and where it occurred, what type of event it was, which element the 
        event occurred on, and a plethora of other information. Of course you don't have to call it event; you could call it e or whatever you want to, but 
        event is a pretty  common convention. */
        $('#files').bind('change', handleFileSelect);
    }

    var columns = [];
    columns.push({sTitle: "", mData: "", aTargets: 0});

    var table = $('#featuresetTable').DataTable( {
        //"scrollY": "600px",
        "scrollX": true,
        "paging": false,
        "search": false,
        "aoColumnDefs": columns,
        "aaData": []
        //"bFilter": false
    } );

    $('#featuresetTable tbody').on( 'click', 'tr', function () {
        $('#featuresetTable tbody tr').removeClass('selected');
        $(this).toggleClass('selected');
    } );
 
    $('#button').click( function () {
        alert( table.rows('.selected').data().length +' row(s) selected' );
    } );
});

$('#accrualVisualization').change(function() {
    document.getElementById("overallResults").innerHTML = "";
    //document.querySelectorAll('.check1')[0].checked = false;
});


function isAPIAvailable() {
    // Check for the various File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        // Great success! All the File APIs are supported.
        return true;
    } else {
        alert("The browser you're using does not currently support\nthe HTML5 File API. As a result the file loading demo\nwon't work properly.");
        return false;
    }
}


var allData_ = [];
var iColumns = [];

function handleFileSelect(event) {

    var select = document.getElementById('chunkSize'),
        i = select.selectedIndex,
        chunkSize = select.options[i].text;
        
    Papa.LocalChunkSize = 50000000;
    
    if (chunkSize == "25mb") {
        Papa.LocalChunkSize = 25000000;
    } else if (chunkSize == "10mb") {
        Papa.LocalChunkSize = 10000000;
    } else if (chunkSize == "5mb") {
        Papa.LocalChunkSize = 5000000;
    } else if (chunkSize == "75mb") {
        Papa.LocalChunkSize = 75000000;
    } else if (chunkSize == "100mb") {
        Papa.LocalChunkSize = 100000000;
    } else if (chunkSize == "150mb") {
        Papa.LocalChunkSize = 150000000;
    } else if (chunkSize == "200mb") {
        Papa.LocalChunkSize = 200000000;
    }

    var size = 0;
    file_ = event.target.files[0];

    Papa.parse(event.target.files[0], {
        worker: true,
        header: true,
        skipEmptyLines: true,
        chunk: function(results, parser) {
            //console.log("Row data:", results.data);
            //console.log("Row errors:", results.errors);

            console.log(event.target.files[0].size);

            size += Papa.LocalChunkSize;

            // Only first 50mb are imported for table
            if (size <= Papa.LocalChunkSize) {
                allData_ = results.data;
                fillTable();

                if (event.target.files[0].size > Papa.LocalChunkSize) {
                    document.getElementById('maxTableWarning').innerHTML = "<font color=\"red\"><b>File greater than " + Papa.LocalChunkSize/1000000 + "mb. Showing first 1000 records.</b></font><br><br>";
                }

                parser.abort();
            }

        }
        /*complete: function(results) {

            console.log("Finished:", results.data);

            // Last row is only a counter
            //results.data.splice(-1,1);

            //console.log("Dataframe:", JSON.parse(JSON.stringify(results.data)));

            allData_ = results.data;
            delete window.results;
            fillTable();
        }*/
    });


    document.getElementById('semanticsVisualization').disabled = false;
    document.getElementById('export').disabled = false;
    document.getElementById('strengthCheckBox').disabled = false;

    /*Papa.parse(bigFile, {
    worker: true,
    step: function(row) {
        console.log("Row:", row.data);
    },
    complete: function() {
        console.log("All done!");
    }
    });*/


    /*var files = event.target.files; // FileList object
    var file = files[0];

    // read the file contents and chart the data
    chartFileData(file, function(parsed) {
        // Parsed will be passed inside chartFileData
        fillTable(parsed);
    });

    document.getElementById('semanticsVisualization').disabled = false;
    document.getElementById('export').disabled = false;*/
}

function chartFileData(fileToParse, callback) {

    var reader = new FileReader();
    reader.readAsText(fileToParse);
    reader.onload = function() {
        var csv = event.target.result;

        // https://github.com/evanplaice/jquery-csv
        var parsedData = $.csv.toArrays(csv, {
            onParseValue: $.csv.hooks.castToScalar
            });

            callback(parsedData);
    };
    reader.onerror = function() {
        alert('Unable to read ' + file.fileName);
    };
}


function fillTable() {

    var attributes = <?php echo json_encode($view_attributes, JSON_PRETTY_PRINT); ?>;

    //console.log(attributes);

    var select = document.getElementById('featureset'),
        i = select.selectedIndex,
        currentFeatureset = select.options[i].text;

    var ignoredColumns = "<ul>";
    var removeColumns = []
    var nColumns = 0;

    // New columns of the table
    var columns = [];
    // New table data

    // In case weights are importes check if all attributes have an associated
    // weight. In case not, data will not be allowed.
    var attributesImported = [];
    var weightsImported = [];

    var hasWeight = false;

    // Find columns that do not have an associate feature and remove them
    for (var key in allData_[0]) {

        if (key.indexOf("Weight_") !== -1) {
            // Remove prefix Weight_ to ease comparisons
            weightsImported.push(key.slice(7));
            hasWeight = true;
        } else if (key != "ID") {
            attributesImported.push(key);
        }

        for (var iAttr = 0; iAttr < attributes.length; iAttr++) {

            // Check whether header is a weight for an existing attribute
            var column = key;
            if (key.indexOf("Weight_") !== -1) {
                column = key.slice(7);
            }

            // Check if header has a relative attribute in the featureset
            var inFeatureset = attributes[iAttr].featureset == currentFeatureset && 
                            (attributes[iAttr].attribute === column ||
                             key.toUpperCase() === "ID" ||
                             key === "GroundTruth");


            if (inFeatureset) {
                // Create json columns according to the required fields of the table plugin
                columns.push({sTitle: key, mData: key, aTargets: [nColumns]});
                nColumns++;
                // Save which columns of the csv file will be imported
                //iColumns.push(iHeader);
                // Break since current colum header have already been saved
                break;
            }

            if (iAttr == attributes.length - 1) {
                // If all attributes were checked and header didn't match any of them it will not be imported.
                // Print list of not imported column
                // TODO: is this really necessary? Maybe import everything and using only valid data
                ignoredColumns += "<li>" + key + "</li>";
                removeColumns.push(key);
            }

            ignoredColumns += "</ul>"
        }
    }

    // Remove columns that do not have an associated feature
    for (var i = 0; i < removeColumns.length; i++) {
        for (var row = 0; row < allData_.length; row++) {
            delete allData_[row][removeColumns[i]];
        }
    }

    console.log("Data here");
    console.log(allData_);

    var error = 0;
    if (weightsImported.length > 0) {
        for (var i = 0; i < weightsImported.length; i++) {
            if (attributesImported.indexOf(weightsImported[i]) == -1) {
                window.alert("Weight_" + weightsImported[i] + " imported without feature " + weightsImported[i], "No relative feature error");

                error = 1;
            }
        }
    }

    if (error == 0) {
        if (ignoredColumns.length > 0) {
            window.alert("The following columns were ignored because they " +
                         "do not have a relative feature in the feature set: <br>" + ignoredColumns, "Import data alert!");
        }

        var maxTableHeight = (document.getElementById('left-side').clientHeight / 2).toString() + "px";

        if (allData_.length < 28) {
            maxTableHeight = (allData_.length * 5).toString + "px";
        }

        var maxRows = 1000;

        // Show maximum maxRows rows in the interface table
        if (allData_.length > maxRows) {
            var maxData = []
            for (var row = 0; row < maxRows; row++) {
                maxData.push(allData_[row]);
            }

            // Update table
            var oTable = $('#datatable-sample').html('<table class="display" cellspacing="0" width="100%" id="featuresetTable"></table>').children('table').DataTable({
                "scrollY": maxTableHeight,
                "scrollX": true,
                "paging": false,
                "search": false,
                //"bFilter": false
                //"bLengthChange": false,
                "aoColumnDefs": columns,
                "aaData": maxData
                //"bDestroy": true
            });
        } else {
            // Update table
            var oTable = $('#datatable-sample').html('<table class="display" cellspacing="0" width="100%" id="featuresetTable"></table>').children('table').DataTable({
                "scrollY": maxTableHeight,
                "scrollX": true,
                "paging": false,
                "search": false,
                //"bFilter": false
                //"bLengthChange": false,
                "aoColumnDefs": columns,
                "aaData": allData_
                //"bDestroy": true
            });
        }
    } else {
        var columns = [];
        columns.push({sTitle: "", mData: "", aTargets: 0});

        // Update table
        var oTable = $('#datatable-sample').html('<table class="display" cellspacing="0" width="100%" id="featuresetTable"></table>').children('table').DataTable({
            "scrollX": true,
            "paging": false,
            "search": false,
            //"bFilter": false
            //"bLengthChange": false,
            "aoColumnDefs": columns,
            "aaData": []
            //"bDestroy": true
        });
    }

    //allData = data;
    document.getElementById("overallResults").innerHTML = "";
    document.querySelectorAll('.check1')[0].checked = false;
    document.querySelectorAll('.check2')[0].checked = false;

    // Select rows script
    $('#featuresetTable tbody').on('click', 'tr', function () {
        $('#featuresetTable tbody tr').removeClass('selected');
        $(this).toggleClass('selected');
        document.getElementById('row_input').value = JSON.stringify(oTable.row(this).data());

        // Trigger event after update row data so graph can compute the semantics
        // for the new row
        var event = new Event('change');
        document.getElementById('row_input').dispatchEvent(event);
    });

    $('#semanticsVisualization').change(function() {
        var row = $('.selected');
        var rowData = JSON.stringify(oTable.row(row).data());
        if (row.length > 0) {
            document.getElementById('row_input').value = JSON.stringify(oTable.row(row).data());
            var event = new Event('change');
            document.getElementById('row_input').dispatchEvent(event);
        }
    });

    $('#accrualVisualization').change(function() {
        var row = $('.selected');
        var rowData = JSON.stringify(oTable.row(row).data());
        if (row.length > 0) {
            document.getElementById('row_input').value = JSON.stringify(oTable.row(row).data());
            var event = new Event('change');
            document.getElementById('row_input').dispatchEvent(event);
        }
    });

    $('input[type=checkbox][name=overallCheckBox]').change(function() {
        if (row.length > 0) {
            var event = new Event('change');
            document.getElementById('overallCheckBox').dispatchEvent(event);
        }
    });

    $('input[type=checkbox][name=strengthCheckBox]').change(function() {
        if (row.length > 0) {
            //console.log("oi1");
            if (! hasWeight) {
            //console.log("oi2");
                window.alert("No weights imported for any column. Strength of arguments can't be used. Import colum \"Weigh_<Column>\" to define features strenghts.", "No weights alert!");
            } else {
            //console.log("oi3");
                var event = new Event('change');
                document.getElementById('strengthCheckBox').dispatchEvent(event);
            }
        }
    });

    $('input[type=checkbox][name=strengthCheckBoxExport]').change(function() {
        if (row.length > 0) {
            if (! hasWeight) {
                window.alert("No weights imported for any column. Strength of arguments can't be used. Import colum \"Weigh_<Column>\" to define features strenghts.", "No weights alert!");
            }
        }
    });
}

</script>
