<?php
echo "<div class='col-md-7' id='left-side'>";
echo "<table id='datasetTable' class='display' cellspacing='0' width='100%'>";
echo "<thead><tr>";
// Print dataSet header
$index = 1;
foreach ($view_dataSetHeader as $column) {
    echo "<th>$column</th>";
    $index++;
}

echo "<tbody>";
foreach ($view_dataSet as $recordNumber => $row) {
    $index = 1;
    // Print dataSet values
    $ds = "<tr>";

    foreach ($row as $column) {
        if ($index == 1) {
            $ds .= "<td>$column</td>";

            // Build html for each expert system result. This will be displayed at the right side.
            $expert =  "<div id='right-side-forecast-" . $column . "' style='display: none;'>";
            $expert .= "<p id='results-forecast-" . $column . "'><br>" . $view_reasoningsForecast[$column] . "</p>";
            $expert .= "</div>";

            $expert .= "<div id='right-side-notforecast-" . $column . "' style='display: none;'>";
            $expert .= "<p id='results-notforecast-" . $column . "'>
                        <br>" . $view_reasoningsNotForecast[$column] . "</p>";
            $expert .= "</div>";

            for ($i = 0; $i < N_SYSTEMS; $i++) {
                $nHeuristic = $i + 1;
                $expert .= "<div id='right-side-heuristic-" . $column . "-" . $i . "' style='display: none;'>";
                $expert .= "<p id='results-heuristic-" . $column . "-" . $i . "'>
                            <br><span class='label label-success'><b><font size=2'>Heuristic " . $nHeuristic  . "</font></b></span><br><br>" . 
                            $view_reasoningsHeuristic[$column][$i] . "<br>
                            Final index: " . $view_indexes[$column][$i] . "</p>";
                $expert .= "</div>";
            }

            echo $expert;

        } else {
            if ($index <= 24) {
                $ds .= "<td>$column</td>";
            } else {
                $ds .= "<td>" . number_format((float)$column, 2, '.', '') . "</td>";
            }
        }

        $index++;
    }

    echo $ds;

    echo "</tr>";
}
echo "</tbody></table></div>"; 
?>
<br>
<div class="panel-group col-md-4" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse1"> Forecast rules</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body"><p id='results-forecast'>Please select an instance from the table</p></div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse2">
        Rebutting and undercutting rules</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body"><p id='results-notforecast'>Please select an instance from the table</p></div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse3">
        Heuristics</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">
        <?php
        echo "<p id='results-heuristic-0'>Please select an instance from the table</p>";
        for ($i = 1; $i < N_SYSTEMS; $i++) {
            echo "<p id='results-heuristic-" . $i . "'></p>";
        }
        ?>
        </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function() {
     var table = $('#datasetTable').DataTable( {
        "scrollY": "600px",
        "scrollX": true,
        "paging": false,
        "search": false,
        "bFilter": false
    } );

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible(!column.visible());
    } );

    $('#datasetTable tbody').on( 'click', 'tr', function () {
          $('#datasetTable tbody tr').removeClass('selected');
        $(this).toggleClass('selected');
        var data = table.row( this ).data();
        document.getElementById("results-forecast").innerHTML = document.getElementById("results-forecast-" + data[0]).innerHTML;
        document.getElementById("results-notforecast").innerHTML = document.getElementById("results-notforecast-" + data[0]).innerHTML;
        document.getElementById("results-heuristic-0").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-0").innerHTML;
        document.getElementById("results-heuristic-1").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-1").innerHTML;
        document.getElementById("results-heuristic-2").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-2").innerHTML;
        document.getElementById("results-heuristic-3").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-3").innerHTML;
        document.getElementById("results-heuristic-4").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-4").innerHTML;
        document.getElementById("results-heuristic-5").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-5").innerHTML;
        document.getElementById("results-heuristic-6").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-6").innerHTML;
        document.getElementById("results-heuristic-7").innerHTML = document.getElementById("results-heuristic-" + data[0] + "-7").innerHTML;
    } );
 
    $('#button').click( function () {
        alert( table.rows('.selected').data().length +' row(s) selected' );
    } );
} );

</script>