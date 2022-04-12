<script src='<?php echo ATTRIBUTE_RANGE_VISUALIZATION; ?>' charset="utf-8"></script>

<div class="container">
    <div class='well col-md-offset-3 col-md-6'>
        <form class="form-inline">
            <label for="sel1">&nbsp Select dataset: &nbsp</label>
            <select class="form-control" name="dataset" id="dataset">
                <?php foreach ($view_datasets as $d) {
                    echo "<option value='" . $d . "'>" . $d . "</option>";
                }
                ?>
            </select>

            <div id="datasetBox"></div>
            &nbsp &nbsp
            <button type="submit" class="btn btn-primary" id="exportjson">
            <span class="glyphicon glyphicon-export"></span> Export JSON </button>
        </form>
    </div>
</div>

<?php
foreach ($view_allDatasets as $record => $row) {
    if (empty($rowContent[$row["dataset"]][$row["attribute"]])) {
        $rowContent[$row["dataset"]][$row["attribute"]] = $row["a_level"] . "," . $row["a_from"] . "," . $row["a_to"];
    } else {
        $rowContent[$row["dataset"]][$row["attribute"]] .= "," . $row["a_level"] . "," . $row["a_from"] . "," . $row["a_to"];
    }
}
?>

<script>

var datasets = <?php echo json_encode($rowContent, JSON_PRETTY_PRINT); ?>;
var conclusions_ = <?php echo json_encode($view_conclusionsByDataset, JSON_PRETTY_PRINT); ?>;
var firstDataset = <?php echo json_encode($view_datasets[0], JSON_PRETTY_PRINT); ?>;

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

document.getElementById("dataset").addEventListener("change", function(event) {
    event.preventDefault();

    currentDataSet = document.getElementById('dataset').options[document.getElementById('dataset').selectedIndex].text;

    addDataset(currentDataSet);
});

addDataset(firstDataset);

document.getElementById("exportjson").addEventListener("click", function(event) {
    event.preventDefault();

    currentDataSet = document.getElementById('dataset').options[document.getElementById('dataset').selectedIndex].text;

    var json = "<pre>{\"dataset\":\"" + currentDataSet + "\",<br>";
        json += "       \"attributes\":[<br>";

    var i = 0;
    var ranges = [];
    var attributes = [];
    
    var size = Object.size(datasets[currentDataSet]);

    for (var att in datasets[currentDataSet]) {

        ranges[i] = String(datasets[currentDataSet][att]).split(",");
        attributes[i] = att;


        json += "              ";
        json += "[{\"name\":\"" + att + "\"},<br>";
        json += "               {\"range\":\"" + ranges[i].length / 3 + "\"},<br>";
        json += "               {\"from\":[<br>";

        for (var j = 0; j < ranges[i].length; j = j + 3) {

            json += "                     ";

            json += "{\"value\":\"" + ranges[i][j+1] + "\"}";

            if (j + 3 < ranges[i].length) {
                json += ",<br>";
            } else {
                json += "]},<br>";
            }
        }

        json += "               {\"to\":[<br>";

        for (var j = 0; j < ranges[i].length; j = j + 3) {

            json += "                     ";

            json += "{\"value\":\"" + ranges[i][j+2] + "\"}";

            if (j + 3 < ranges[i].length) {
                json += ",<br>";
            } else {
                json += "]},<br>";
            }
        }

        json += "               {\"level\":[<br>";

        for (var j = 0; j < ranges[i].length; j = j + 3) {

            json += "                     ";

            json += "{\"value\":\"" + ranges[i][j] + "\"}";

            if (j + 3 < ranges[i].length) {
                json += ",<br>";
            } else {
                if (i == size - 1) {
                    json += "]}<br>";
                } else {
                    json += "]}],<br>";
                }
            }
        }

        if (i == size - 1) {
            json += "              ]<br>";
        }

        i++;
    }

    if (conclusions_[currentDataSet].length > 0) {
        json += "       ],<br>";
        json += "       \"conclusions\":[<br>";

        for (var i = 0; i < conclusions_[currentDataSet].length; i++) {
            json += "                     {\"category\":\"" + conclusions_[currentDataSet][i] + "\"}";
            if (i == conclusions_[currentDataSet].length - 1) {
                json += "<br>                     ]<br>";
            } else {
                json += ",<br>";
            }
        }
    } else {
        json += "       ]<br>";
    }

    json += "}</pre>";
    
    /*
    {"dataset":"aaaqw",
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
            "conclusions":[{"category":"underload"},
                           {"category":"fitting"},
                           {"category":"overload"}]
            }*/

    window.open('data:text/html;charset=utf-8,' +
    encodeURIComponent( // Escape for URL formatting
        json
    ));
});

function addDataset(dataset) {

    // Remove current dataset visualization
    $('#datasetBox').remove();

    var table = "";

    table += "<div id='datasetBox'>";
    table +=  "<table class='display'>";
    table +=  "<tbody>";
    table +=  "<tr><td width='100'><b>Attribute</b></td><td width='400' style='padding:15px;text-align: center;'><b>Level</b></td>";
    table +=  "</tr></body></table></div>";

    $('.well').append(table);

    var i = 0;
    var ranges = [];
    var attributes = [];

    for (var att in datasets[dataset]) {
        ranges[i] = String(datasets[dataset][att]).split(",");
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

        var id = dataset + "_" + attributes[att];

        $('#datasetBox').append("<tr><td width='100' style='text-align: center;color: rgb(120, 0, 0)'>" + attributes[att] +
                                "</td><td id=\'" + id + "\' width='400' style='padding:5px;'></td></tr>");

        drawBars(id, ranges[att], min, max);
    }

    var rowConclusion = "<tr><td><br/><br/></td></tr><tr><td width='100'><b>Conclusions:</b></td>";

    if (conclusions_[dataset].length > 0) {
        rowConclusion += "<td style='color: rgb(0, 0, 174)'><font color='blue'> ";
        for (var c = 0; c < conclusions_[dataset].length; c++) {
            rowConclusion += conclusions_[dataset][c];
            if (c < conclusions_[dataset].length - 1) {
                rowConclusion += ", ";
            } else {
                rowConclusion += ".</font></td>";
            }
        }
    } else {
        rowConclusion += "<td>this dataset has no conclusions.</td>";
    }

    rowConclusion += "</tr>";

    $('#datasetBox').append(rowConclusion);
}
</script>
