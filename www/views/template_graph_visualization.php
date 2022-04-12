<script src='<?php echo GRAPH_SMALL_VISUALIZATION; ?>' charset="utf-8"></script>

<script> 
var graphs_ = <?php echo json_encode($view_datasetGraphs, JSON_PRETTY_PRINT); ?>;
var args_ = <?php echo json_encode($view_datasetArguments, JSON_PRETTY_PRINT); ?>;
</script>

<?php 


$irow = 0;

foreach ($view_apiResponse as $record => $row) {

    echo "<div class='well col-md-offset-3 col-sm-4'>";

    echo "<table class='display'>";
    echo "<thead><tr>";

    foreach ($row as $index => $column) {
        if ($index != "edges") {
            echo "<th style='padding:15px;text-align: center;'>$index</th>";
        } else {
            echo "<th style='padding:15px;text-align: center;'>attack relations</th>";
        }
    }

    echo "</tr></thead>";
    
    echo "<tbody><tr>";
    foreach ($row as $index => $column) {

        if ($index == "edges") {
            echo "<td id='cell_$irow' width='100%' height='100' style='padding:15px;'><div id='graph_$irow' style='background-color: rgb(248, 248, 248);'></div></td>";
            ?> <script> 

            var id = <?php echo $irow; ?>;
            var dataset = <?php echo json_encode($row["dataset"]); ?>;
            var graph = <?php echo json_encode($row["name"]); ?>;
            var position = "graph_" + id;
            var cell = "cell_" + id;
            create(window.d3, window.saveAs, window.Blob, id, position, cell, dataset, graph) 

            </script> <?php
        } else {
            echo "<td style='padding: 0 15px;'>$column</td>";
        }
    }
    
    echo "</tr></table></div>";

    $irow++;
}

echo "</tbody></table>";
?>