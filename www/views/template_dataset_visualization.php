<script src='<?php echo ATTRIBUTE_RANGE_VISUALIZATION; ?>' charset="utf-8"></script>

<?php 

foreach ($view_apiResponse as $record => $row) {
    if (empty($rowContent[$row["dataset"]][$row["attribute"]])) {
        $rowContent[$row["dataset"]][$row["attribute"]] = $row["a_level"] . "," . $row["a_from"] . "," . $row["a_to"];
    } else {
        $rowContent[$row["dataset"]][$row["attribute"]] .= "," . $row["a_level"] . "," . $row["a_from"] . "," . $row["a_to"];
    }
}

foreach ($rowContent as $dataset => $attribute) {

    echo "<div class='well col-md-offset-3 col-sm-5'>";
    echo "<table class='display'>";
    echo "<thead><tr>";
    echo "<td width='200'><b>Dataset: </b>" . $dataset . "</td></tr></thead><tbody>";
    echo "<tr><td width='50'><b>Attribute</b></td><td width='400' style='padding:15px;text-align: center;'><b>Level</b></td>";
    echo "</tr>";
    //$ranges = array();
    $i = 0;

    $ranges = array();

    foreach ($attribute as $att => $value) {
        $ranges[$i] = explode (",", $value);
        $attribute[$i] = $att;
        $i++;
    }

    for ($att = 0; $att < count ($ranges); $att++) {
        $min = INF;
        $max = -INF;

        for ($r = 0; $r < count ($ranges[$att]); $r = $r + 3) {
            if (floatval($ranges[$att][$r+1]) < $min) {
                $min = floatval($ranges[$att][$r+1]);
            }
            if (floatval($ranges[$att][$r+2]) > $max) {
                $max = floatval($ranges[$att][$r+2]);
            }
        }

        $id = $dataset . "_" . $attribute[$att];

        echo "<tr><td style='text-align: center;color: rgb(120, 0, 0)'>$attribute[$att]</td>";
        echo "<td id='$id' width=80% style='padding:5px;'></td></tr>";
        ?> <script> 

        var position = <?php echo json_encode($id); ?>;
        var attribute = <?php echo json_encode($ranges[$att]);?>;
        var min = <?php echo json_encode($min); ?>;
        var max = <?php echo json_encode($max); ?>;

        drawBars(position, attribute, min, max);

        </script> <?php
    }

    echo "<tr><td><br/><br/></td></tr><tr><td width='50'><b>Conclusions:</b></td>";

    if (count($view_conclusionsByDataset[$dataset]) > 0) {
        echo "<td style='color: rgb(0, 0, 90)'>";
        $conclusions = "<font> ";
        foreach ($view_conclusionsByDataset[$dataset] as $key => $value) {

            $conclusions .= $value["conclusion"] . " [" . $value["c_from"] . ", " . $value["c_to"] . "]";

            if ($key < count($view_conclusionsByDataset[$dataset]) - 1) {
                $conclusions .= "<br>";
            } else {
                $conclusions .= "</font>";
            }
        }

        echo $conclusions . "</td>";
    } else {
        echo "<td>this dataset has no conclusions.";
    }
    echo "</tr>";

    if ($view_apiResponseGraphs != "NULL") {

        ?>
        <script src='<?php echo GRAPH_SMALL_VISUALIZATION; ?>' charset="utf-8"></script> 
        <script> 
            var graphs_ = <?php echo json_encode($view_datasetGraphs, JSON_PRETTY_PRINT); ?>;
            var args_ = <?php echo json_encode($view_datasetArguments, JSON_PRETTY_PRINT); ?>;
        </script>

        <?php 

        $printHeader = true;;
        $cellCounter = 0;

        foreach ($view_apiResponseGraphs as $record => $row) {

            if ($row["dataset"] != $dataset) {
                $cellCounter++;
                continue;
            }

            echo "<tr>";

            if ($printHeader) {
                echo "<tr><td><br/><br/></td></tr><td width=50%><b>Graphs</td></tr><tr>";
                foreach ($row as $index => $column) {
                    if ($index == "name") {
                        echo "<td><b>Name</b></td>";
                    }

                    if ($index == "edges") {
                        echo "<td style='padding:15px;text-align: center;'><b>Attack Relations</b></td>";
                    }
                }
            }

            echo "</tr><tr>";

            foreach ($row as $index => $column) {

                if ($index == "edges") {
                    echo "<td id='cell_$cellCounter' width=50% height='100' style='padding:15px;'>" .
                         "<div id='graph_$cellCounter' style='background-color: rgb(248, 248, 248);border-style: solid;border-width: 2px;border-radius: 3px'></div></td>";

                    ?> <script> 

                    var id = <?php echo $cellCounter; ?>;
                    var dataset = <?php echo json_encode($row["dataset"]); ?>;
                    var graph = <?php echo json_encode($row["name"]); ?>;
                    var position = "graph_" + id;
                    var cell = "cell_" + id;
                    create(window.d3, window.saveAs, window.Blob, id, position, cell, dataset, graph) 

                    </script> <?php
                } else if ($index == "name") {
                    echo "<td style='padding: 0 15px;text-align: center;color: rgb(0, 72, 0)'>$column</td>";
                }
            }

            echo "</tr>";

            $printHeader = false;
            $cellCounter++;
        }
    }

    echo "</tbody></table></div>";
}
?>