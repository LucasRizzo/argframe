<div class="container">

    <div id="modalHelp" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Graph help</h4>
                </div>

            <div class="modal-body">
                <b>Create your data set adding attributes, their respective levels and the possible conclusions.<br></b>
                <ul>
                    <li>You can add as many attributes as you want.</li>
                    <li>For each attribute you can add as many levels as you want.</li>
                    <li>Each level has a range.</li>
                    <!--<li>Ranges of the same attribute can not overlap.</li> -->
                    <li>Levels of the same attribute can not have the same name.</li>
                    <li>Each conclusion has a range.</li>
                    <li>Each conclusion range are in the form [X, Y], with X >= Y or Y >= X.</li>
                </ul>
                <br>
                <b>If you have a big feature set there is also an option in the menu for creating feature sets through a JSON file.</b>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header">
        <h1>Create feature set &nbsp &nbsp
            <button type="submit" form="form1" class="btn btn-primary"> <span class="glyphicon glyphicon glyphicon-floppy-disk"></span>&nbsp Save feature set</button>
            <button type="button" onclick="showHelp()" class="btn btn-primary"> <span class="glyphicon glyphicon glyphicon-question-sign"></span></button>
        </h1>
    </div>

    <form class="form-horizontal" action="index.php?action=insert" id="form1" method="post" role="form">

            <div class="form-group has-feedback page-header">
                <!-- <label class="control-label col-sm-2" for="name">feature set name: </label> -->
                <div class="col-md-10 col-md-push-1">
                    <div class="input-group">
                        <span class="input-group-addon"><b>Feature set name</b></span>
                        <input type="text" name="featureset" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control input-lg" id="name" placeholder="Enter feature set name">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
            <div class='col-md-6' id='left-side'>
                <div class="controls"> 
                    <div class="form-attribute" nrange="1">
                        <div class="form-group has-feedback span6" style="margin: 4px;">
                            <div class="input-group">
                                <span class="input-group-addon"><b>Attribute name</b></span>
                                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" name="attributename[]" placeholder="Attribute name">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <span class="input-group-btn">
                                    <button class="btn btn-success btn-add-attribute" type="button">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <input class="countrange" type="hidden" name="nrange[]" value="1">
                        <div class="controls-range span6" style="margin: 4px;">
                            <div class="form-group has-feedback span6" style="margin: 0px;">
                                <div class="col-sm-1">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Level &nbsp&nbsp</span>
                                    <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="30" required class="form-control" name="attributelevel[]" placeholder="Level">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <span class="input-group-btn"><button class="btn btn-primary btn-add-range" type="button"><span class="glyphicon glyphicon-plus"></span></button></span>
                                </div>
                            </div>
                            <div class="form-group has-feedback span6" style="margin: 0px;">
                                <div class="col-sm-1">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">Range&nbsp</span>
                                    <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" name="attributefrom[]" placeholder="From">
                                    <span class="input-group-btn" style="width:0px;"></span>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                    <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" name="attributeto[]" placeholder="To">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class='col-md-6' id='right-side'>
                <div class="form-conclusion" nrange="1">
                    <div class="controls-conclusion"> 
                        <div class="form-group has-feedback span6" style="margin: 4px;">
                            <div class="input-group">
                                <span class="input-group-addon"><b>Conclusion name</b></span>
                                <input type="text" pattern="^[a-zA-Z0-9]*$" maxlength="20" required class="form-control" name="conclusionname[]" placeholder="Category">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                 <span class="input-group-btn">
                                    <button class="btn btn-primary btn-add-conclusion" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                                </span>
                            </div>
                            <div class="span6" style="margin: 4px;">
                            <div class="col-sm-1">
                                </div>
                            <div class="input-group">
                                <span class="input-group-addon">Range&nbsp</span>
                                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" name="conclusionfrom[]" placeholder="From">
                                <span class="input-group-btn" style="width:0px;"></span>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                <input type="text" pattern="[-+]?(\d*[.])?\d+" maxlength="30" required class="form-control" name="conclusionto[]" placeholder="To">
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

</div>
<br>
<br>
<br>
<br>
<script>

function show() {

  var divToPrint=document.getElementById('divtoprint');

  window.alert(divToPrint.innerHTML);
}

// Initialize number of attributes and number of ranges for each attribute
var nAttributes = 1;
var totalRanges = 1;
var nRanges = [];
nRanges [nAttributes - 1] = 1;

$(function() //Document ready event
{
    // on attaches the click event to document child .btn-add-attribute.
    $(document).on('click', '.btn-add-attribute', function(e) //
    {
        e.preventDefault();

        var controlForm = $('.controls'),
            currentEntry = $(this).parents('.form-attribute:last'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        // Clear all inputs in the new form
        newEntry.find('input').val('');

        // Recover range size from current entry. Since the attribute was cloned
        // the range size initially is the same.
        newEntry.find('.countrange').attr("value", currentEntry.find('.countrange').attr("value"));

        $('.myeditable').editable();

        controlForm.find('.form-attribute:not(:last) .btn-add-attribute')
            .removeClass('btn-add-attribute').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="glyphicon glyphicon-minus"></span>');

        controlForm.find('form-attribute:last')
            .attr("data-toggle", "validator");
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.form-attribute:first').remove();

        e.preventDefault();
        return false;
    });

    // on attaches the click event to document child .btn-add-range.
    $(document).on('click', '.btn-add-range', function(e) //
    {
        e.preventDefault();

        var controlForm = $(this).parents('.form-attribute'),
            currentEntry = $(this).parents('.controls-range'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        // Add 1 to the current range counter
        var current = parseInt(controlForm.find('.countrange').attr("value")) + 1;
        controlForm.find('.countrange').attr("value", current);

        newEntry.find('input').val('');
        controlForm.find('.controls-range:not(:last) .btn-add-range')
            .removeClass('btn-add-range').addClass('btn-remove-range')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="glyphicon glyphicon-minus"></span>');
    }).on('click', '.btn-remove-range', function(e)
    {
        // Remove 1 from the current range counter
        var controlForm = $(this).parents('.form-attribute');
        var current = parseInt(controlForm.find('.countrange').attr("value")) - 1;
        controlForm.find('.countrange').attr("value", current);

        $(this).parents('.controls-range:first').remove();

        e.preventDefault();
        return false;
    });

    // on attaches the click event to document child .btn-add-range.
    $(document).on('click', '.btn-add-conclusion', function(e) //
    {
        e.preventDefault();

        var controlForm = $(this).parents('.form-conclusion'),
            currentEntry = $(this).parents('.controls-conclusion'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.controls-conclusion:not(:last) .btn-add-conclusion')
            .removeClass('btn-add-conclusion').addClass('btn-remove-conclusion')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="glyphicon glyphicon-minus"></span>');
    }).on('click', '.btn-remove-conclusion', function(e)
    {
        // Remove 1 from the current range counter
        $(this).parents('.controls-conclusion:first').remove();

        e.preventDefault();
        return false;
    });

    <?php if ($view_errorType == "create") {
    ?> // Generic modal included in the index page
        $('#messages').removeClass('hide').addClass('alert alert-danger alert-dismissible fade in').slideDown().show();
        $('#messages_content').html('<h4> <strong>Error!</strong> <?php echo $view_error; ?> </h4>');
        $('#modal').modal('show');
    <?php
    }
    ?>
});

function showHelp() {
    $('#modalHelp').modal('show');
}

/*
$.fn.editable.defaults.mode = 'inline';

$(document).ready(function() {
    $('.myeditable').editable();
});

function addAttribute() {
    var table = document.getElementById("mainTable");
    var rowAttribute = table.insertRow(3 + nAttributes - 1 + totalRanges - 1);
    var rowRange = table.insertRow(3 + nAttributes - 1 + totalRanges);

    var lineRange = 3 + nAttributes - 1 + totalRanges;

    nAttributes++;
    totalRanges++;
    nRanges[nAttributes - 1] = 1;


    var cell1 = rowAttribute.insertCell(0);
    var cell2 = rowAttribute.insertCell(1);
    cell1.innerHTML = "<b> Attribute " + nAttributes + "</b>";
    cell2.innerHTML = "<a href=\"#\" class=\"myeditable\" id=\"username\" data-type=\"text\" data-pk=\"1\" data-url=\"index.php?action=teste\" data-title=\"Enter username\">";


    var cell3 = rowRange.insertCell(0);
    cell3.align = "center";
    var cell4 = rowRange.insertCell(1);
    var cell5 = rowRange.insertCell(2);

    cell3.innerHTML = "<button type=\"button\" onclick=\"addRange(" + lineRange + ")\" form=\"form1\" class=\"btn btn-primary btn-success btn-sm\"> <span class=\"glyphicon glyphicon-plus\">&nbsp Add range</span></button>";
    cell4.innerHTML = "<b>Range</b>";
    cell5.innerHTML = "<a href=\"#\" class=\"myeditable\" id=\"username\" data-type=\"text\" data-pk=\"1\" data-url=\"index.php?action=teste\" data-title=\"Enter username\">";
}

function addRange(line) {
    var table = document.getElementById("mainTable");
    var lineRange = line + 1;
    var rowRange = table.insertRow(line);
    var cell3 = rowRange.insertCell(0);
    cell3.align = "center";
    var cell4 = rowRange.insertCell(1);
    var cell5 = rowRange.insertCell(2);

    cell3.innerHTML = "<button type=\"button\" onclick=\"removeRange(" + lineRange + ")\" form=\"form1\" class=\"btn btn-primary btn-danger btn-sm\"> <span class=\"glyphicon glyphicon-minus\">&nbsp Remove range</span></button>";
    cell4.innerHTML = "<b>Range</b>";
    cell5.innerHTML = "<a href=\"#\" class=\"myeditable\" id=\"username\" data-type=\"text\" data-pk=\"1\" data-url=\"index.php?action=teste\" data-title=\"Enter username\">";
}

/*
$('#name').editable({
    type: 'text',
    pk: 1,
    title: 'Enter username'
});

$('#from').editable({
    type: 'text',
    pk: 1,
    title: 'Enter username'
});

$('#to').editable({
    type: 'text',
    pk: 1,
    title: 'Enter username'
});

$('#level').editable({
    type: 'text',
    pk: 1,
    title: 'Enter username'
}); */
</script> 

















