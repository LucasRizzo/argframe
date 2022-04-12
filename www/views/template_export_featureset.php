<script src='<?php echo ATTRIBUTE_RANGE_VISUALIZATION; ?>' charset="utf-8"></script>

<div class="container">
    <div class='well col-md-offset-3 col-md-6'>
        <form class="form-inline">
            <label for="sel1">&nbsp Select feature set: &nbsp</label>
            <select class="form-control" name="featureset" id="featureset">
                <?php foreach ($view_featuresets as $d) {
                    echo "<option value='" . $d . "'>" . $d . "</option>";
                }
                ?>
            </select>

            <div id="featuresetBox"></div>
            &nbsp &nbsp
            <button type="submit" class="btn btn-primary" id="exportjson">
            <span class="glyphicon glyphicon-export"></span> Export JSON </button>
        </form>
    </div>
</div>

<?php
foreach ($view_allFeaturesets as $record => $row) {
    if (empty($rowContent[$row["featureset"]][$row["attribute"]])) {
        $rowContent[$row["featureset"]][$row["attribute"]] = $row["a_level"] . "," . $row["a_from"] . "," . $row["a_to"];
    } else {
        $rowContent[$row["featureset"]][$row["attribute"]] .= "," . $row["a_level"] . "," . $row["a_from"] . "," . $row["a_to"];
    }
}
?>

<script>

var featuresets = <?php echo json_encode($rowContent, JSON_PRETTY_PRINT); ?>;
var conclusions_ = <?php echo json_encode($view_conclusionsByFeatureset, JSON_PRETTY_PRINT); ?>;
var firstFeatureset = <?php echo json_encode($view_featuresets[0], JSON_PRETTY_PRINT); ?>;

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

document.getElementById("featureset").addEventListener("change", function(event) {
    event.preventDefault();

    currentFeatureset = document.getElementById('featureset').options[document.getElementById('featureset').selectedIndex].text;

    addFeatureset(currentFeatureset);
});

addFeatureset(firstFeatureset);

document.getElementById("exportjson").addEventListener("click", function(event) {
    event.preventDefault();

    var currentFeatureset = document.getElementById('featureset').options[document.getElementById('featureset').selectedIndex].text;

    //var json = "<pre>{\"featureset\":\"" + currentFeatureset + "\",\n";
    var json = "{\"featureset\":\"" + currentFeatureset + "\",\n";
        json += "       \"attributes\":[\n";

    var i = 0;
    var ranges = [];
    var attributes = [];
    
    var size = Object.size(featuresets[currentFeatureset]);

    for (var att in featuresets[currentFeatureset]) {

        ranges[i] = String(featuresets[currentFeatureset][att]).split(",");
        attributes[i] = att;


        json += "              ";
        json += "[{\"name\":\"" + att + "\"},\n";
        json += "               {\"range\":\"" + ranges[i].length / 3 + "\"},\n";
        json += "               {\"from\":[\n";

        for (var j = 0; j < ranges[i].length; j = j + 3) {

            json += "                     ";

            json += "{\"value\":\"" + ranges[i][j+1] + "\"}";

            if (j + 3 < ranges[i].length) {
                json += ",\n";
            } else {
                json += "]},\n";
            }
        }

        json += "               {\"to\":[\n";

        for (var j = 0; j < ranges[i].length; j = j + 3) {

            json += "                     ";

            json += "{\"value\":\"" + ranges[i][j+2] + "\"}";

            if (j + 3 < ranges[i].length) {
                json += ",\n";
            } else {
                json += "]},\n";
            }
        }

        json += "               {\"level\":[\n";

        for (var j = 0; j < ranges[i].length; j = j + 3) {

            json += "                     ";

            json += "{\"value\":\"" + ranges[i][j] + "\"}";

            if (j + 3 < ranges[i].length) {
                json += ",\n";
            } else {
                if (i == size - 1) {
                    json += "]}\n";
                } else {
                    json += "]}],\n";
                }
            }
        }

        if (i == size - 1) {
            json += "              ]\n";
        }

        i++;
    }

    if (conclusions_[currentFeatureset].length > 0) {
        json += "       ],\n";
        json += "       \"conclusions\":[\n";

        for (var i = 0; i < conclusions_[currentFeatureset].length; i++) {
            json += "                     [{\"category\":\"" + conclusions_[currentFeatureset][i].conclusion + "\"},\n";
            json += "                      {\"from\":\"" + conclusions_[currentFeatureset][i].c_from + "\"},\n";
            json += "                      {\"to\":\"" + conclusions_[currentFeatureset][i].c_to + "\"}]";
            if (i == conclusions_[currentFeatureset].length - 1) {
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

function addFeatureset(featureset) {

    // Remove current featureset visualization
    $('#featuresetBox').remove();

    var table = "";

    table += "<div id='featuresetBox'>";
    table +=  "<table class='display'>";
    table +=  "<tbody>";
    table +=  "<tr><td width='100'><b>Attribute</b></td><td width='400' style='padding:15px;text-align: center;'><b>Level</b></td>";
    table +=  "</tr></body></table></div>";

    $('.well').append(table);

    var i = 0;
    var ranges = [];
    var attributes = [];

    for (var att in featuresets[featureset]) {
        ranges[i] = String(featuresets[featureset][att]).split(",");
        attributes[i] = att;
        i++;
    }

    for (var att = 0; att < ranges.length; att++) {
        var min = 100000000000000;
        var max = -100000000000000;

        for (var r = 0; r < ranges[att].length; r = r + 3) {
            if (parseFloat(ranges[att][r+1]) < min) {
                min = parseFloat(ranges[att][r+1]);
            }

            if (parseFloat(ranges[att][r+2]) > max) {
                max = parseFloat(ranges[att][r+2]);
            }
        }

        var id = featureset + "_" + attributes[att];

        $('#featuresetBox').append("<tr><td width='100' style='text-align: center;color: rgb(120, 0, 0)'>" + attributes[att] +
                                "</td><td id=\'" + id + "\' width='400' style='padding:5px;'></td></tr>");

        drawBars(id, ranges[att], min, max);
    }

    var rowConclusion = "<tr><td><br/><br/></td></tr><tr><td width='100'><b>Conclusions:</b></td>";

    if (conclusions_[featureset].length > 0) {
        rowConclusion += "<td style='color: rgb(0, 0, 174)'><font color='blue'> ";
        for (var c = 0; c < conclusions_[featureset].length; c++) {
            rowConclusion += conclusions_[featureset][c];
            if (c < conclusions_[featureset].length - 1) {
                rowConclusion += ", ";
            } else {
                rowConclusion += ".</font></td>";
            }
        }
    } else {
        rowConclusion += "<td>this featureset has no conclusions.</td>";
    }

    rowConclusion += "</tr>";

    $('#featuresetBox').append(rowConclusion);
}
</script>
