<form class="form-inline ">
    <div class="well well-lg">
        <label id="featuresetLabel"  for="sel1">Select feature set: &nbsp</label>
        <select class="form-control" name="featureset" id="featureset">
            <?php foreach ($view_featuresetsWithGraphs as $d) {
                echo "<option value='" . $d . "'>" . $d . "</option>";
            } ?>
        </select>
        <label for="sel1" id="featuresetgraphLabel" >&nbsp Select graph: &nbsp</label>
            <select class="form-control" name="featuresetgraph" id="featuresetgraph">
        </select>
    </div>

    <div class="panel panel-success">
    <div class="panel-heading">Arguments<span id="argumentsN"></span></div>
      <div class="panel-body"><span id="arguments"></span></div>
    </div>

    <div class="panel panel-info">
    <div class="panel-heading">Attacks<span id="attacksN"></span></div>
      <div class="panel-body"><span id="attacks"></span></div>
    </div>

    <div class="panel panel-warning">
    <div class="panel-heading">Feature set<span id="attributesN"></span></div>
      <div class="panel-body"><span id="attributes"></span></div>
    </div>
</form>

<script>

var levels_ = <?php echo json_encode($view_levels, JSON_PRETTY_PRINT); ?>;

var graphs_ = <?php echo json_encode($view_featuresetGraphs, JSON_PRETTY_PRINT); ?>;

var args_ = <?php echo json_encode($view_featuresetArguments, JSON_PRETTY_PRINT); ?>;

var conclusions_ = <?php echo json_encode($view_conclusionsByFeatureset, JSON_PRETTY_PRINT); ?>;

var featuresets_ = <?php echo json_encode($view_featuresets, JSON_PRETTY_PRINT); ?>;

var attributesByFeatureset_ = <?php echo json_encode($view_attributesByFeatureset, JSON_PRETTY_PRINT); ?>;

var conclusionsByFeatureset_ = <?php echo json_encode($view_conclusionsByFeatureset, JSON_PRETTY_PRINT); ?>;

document.getElementById("featureset").addEventListener("change", function(event) {
    event.preventDefault();
    addGraphs();
});

document.getElementById("featuresetgraph").addEventListener("change", function(event) {
    event.preventDefault();
    printArguments();
    printAttacks();
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

    document.getElementById('arguments').innerHTML = html;
    document.getElementById('argumentsN').innerHTML = " <b>(" + argumentsN.toString() + ")</b>";
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

        break;
    }

    document.getElementById('attacks').innerHTML = html;
    document.getElementById('attacksN').innerHTML = " <b>(" + attacksN.toString() + ")</b>";
}

function printFeatureSet() {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;

    var html = "";

    // Run through all attributes and their respective level in order
    // to find the current premise's range
    var currentAttribute = attributesByFeatureset_[currentFeatureset][0].attribute;
    var attributesN = 1;
    html += "<i>Attribute</i>: " + attributesByFeatureset_[currentFeatureset][0].attribute + "<br>";
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

        attributesN++
    }


    document.getElementById('attributes').innerHTML = html;
    document.getElementById('attributesN').innerHTML = " <b>(" + attributesN.toString() + ")</b>";
}

function addGraphs() {

    var select = document.getElementById('featureset'),
        i = select.selectedIndex;

    var currentFeatureset = select.options[i].text;
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

    printFeatureSet();
    printArguments();
    printAttacks();
}

// Initial graph list
addGraphs();
printFeatureSet();
printArguments();
printAttacks();

</script>