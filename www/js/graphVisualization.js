function fixSpaces(argument) {
    argument = argument.replace("  ", " ");
    if (argument[0] == " ") {
        argument = argument.slice(1);
    }

    argument = argument.replace(/\s\(\s/g, "(");
    argument = argument.replace(/\s\(/g, "(");
    argument = argument.replace(/\(\s/g, "(");
    argument = argument.replace(/\s\)\s/g, ")");
    argument = argument.replace(/\s\)/g, ")");
    argument = argument.replace(/\)\s/g, ")");
    argument = argument.replace(/AND\(/g, "AND (");
    argument = argument.replace(/OR\(/g, "OR (");
    argument = argument.replace(/\)AND/g, ") AND");
    argument = argument.replace(/\)OR/g, ") OR");

    return argument;
}

//document.onload = (function(d3, saveAs, Blob){
function create(d3, saveAs, Blob) {
    "use strict";

    // define graphcreator object
    var GraphCreator = function (svg, nodes, edges) {
        var thisGraph = this;

        thisGraph.idct = 0;
        thisGraph.nodes = nodes || [];
        thisGraph.edges = edges || [];

        thisGraph.zoomScale = 1;

        thisGraph.state = {
            selectedNode: null,
            selectedEdge: null,
            mouseDownNode: null,
            mouseDownLink: null,
            justDragged: false,
            justScaleTransGraph: false,
            lastKeyDown: -1,
            shiftNodeDrag: false,
            selectedText: null,
        };

        // define arrow markers for graph links
        var defs = svg.append("svg:defs");
        defs.append("svg:marker")
            .attr("id", "end-arrow")
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", "8.5")
            .attr("markerWidth", 3.5)
            .attr("markerHeight", 3.5)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5");

        // Undercut
        defs.append("svg:marker")
            .attr("id", "end-arrow-red")
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", "8.5")
            .attr("markerWidth", 3.5)
            .attr("markerHeight", 3.5)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5")
            .attr("fill", "#A3C493");

        // Undermine
        defs.append("svg:marker")
            .attr("id", "end-arrow-blue")
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", "8.5")
            .attr("markerWidth", 3.5)
            .attr("markerHeight", 3.5)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5")
            .attr("fill", "#FFC300");

        // Rebuttal
        defs.append("svg:marker")
            .attr("id", "end-arrow-green")
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", "8.5")
            .attr("markerWidth", 3.5)
            .attr("markerHeight", 3.5)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5")
            .attr("fill", "#FF5733");
            //.attr("fill", "#000000");

        // Hovered
        defs.append("svg:marker")
            .attr("id", "end-arrow-hover")
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", "8.5")
            .attr("markerWidth", 3.5)
            .attr("markerHeight", 3.5)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5")
            .attr("fill", "#5EC4CC");

        // Selected
        defs.append("svg:marker")
            .attr("id", "end-arrow-selected")
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", "8.5")
            .attr("markerWidth", 3.5)
            .attr("markerHeight", 3.5)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5")
            .attr("fill", "#E5ACF7");

        // define arrow markers for leading arrow
        defs.append("svg:marker")
            .attr("id", "mark-end-arrow")
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", "7")
            .attr("markerWidth", 3.5)
            .attr("markerHeight", 3.5)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5");

        thisGraph.svg = svg;
        thisGraph.svgG = svg
            .append("g")
            .classed(thisGraph.consts.graphClass, true);
        var svgG = thisGraph.svgG;

        // displayed when dragging between nodes
        thisGraph.dragLine = svgG
            .append("svg:path")
            .attr("class", "link dragline hidden")
            .attr("d", "M0,0L0,0")
            .style("marker-end", "url(#mark-end-arrow)");

        // svg nodes and edges
        thisGraph.paths = svgG.append("g").selectAll("g");
        thisGraph.circles = svgG.append("g").selectAll("g");

        thisGraph.drag = d3.behavior
            .drag()
            .origin(function (d) {
                return { x: d.x, y: d.y };
            })
            .on("drag", function (args) {
                thisGraph.state.justDragged = true;
                thisGraph.dragmove.call(thisGraph, args);
            })
            .on("dragend", function () {
                // todo check if edge-mode is selected
            });

        // listen for key events
        d3.select(window)
            .on("keydown", function () {
                thisGraph.svgKeyDown.call(thisGraph);
            })
            .on("keyup", function () {
                thisGraph.svgKeyUp.call(thisGraph);
            });

        svg.on("mousedown", function (d) {
            thisGraph.svgMouseDown.call(thisGraph, d);
        });
        svg.on("mouseup", function (d) {
            thisGraph.svgMouseUp.call(thisGraph, d);
        });

        // listen for dragging
        var dragSvg = d3.behavior
            .zoom()
            .on("zoom", function () {
                if (d3.event.sourceEvent.shiftKey) {
                    // TODO  the internal d3 state is still changing
                    return false;
                } else {
                    thisGraph.zoomed.call(thisGraph);
                }
                return true;
            })
            .on("zoomstart", function () {
                var ael = d3.select("#" + thisGraph.consts.activeEditId).node();
                if (ael) {
                    ael.blur();
                }
                if (!d3.event.sourceEvent.shiftKey)
                    d3.select("body").style("cursor", "move");
            })
            .on("zoomend", function () {
                d3.select("body").style("cursor", "auto");
            });

        svg.call(dragSvg).on("dblclick.zoom", null);

        // listen for resize
        window.onresize = function () {
            thisGraph.updateWindow(svg);
        };
    };

    GraphCreator.prototype.setIdCt = function (idct) {
        this.idct = idct;
    };

    GraphCreator.prototype.consts = {
        selectedClass: "selected",
        connectClass: "connect-node",
        circleGClass: "conceptG",
        acceptedGClass: "acceptedG",
        onlyActivatedGClass: "onlyActivatedG",
        activatedGClass: "activated",
        deactivatedGClass: "nonactivated",
        deniedGClass: "deniedG",
        initialGClass: "initialG",
        graphClass: "graph",
        activeEditId: "active-editing",
        BACKSPACE_KEY: 8,
        DELETE_KEY: 46,
        ENTER_KEY: 13,
        nodeRadius: 50,
    };

    /* PROTOTYPE FUNCTIONS */

    GraphCreator.prototype.dragmove = function (d) {
        var thisGraph = this;
        if (thisGraph.state.shiftNodeDrag) {
            thisGraph.dragLine.attr(
                "d",
                "M" +
                    d.x +
                    "," +
                    d.y +
                    "L" +
                    d3.mouse(thisGraph.svgG.node())[0] +
                    "," +
                    d3.mouse(this.svgG.node())[1]
            );
        } else {
            d.x += d3.event.dx;
            d.y += d3.event.dy;
            thisGraph.updateGraph();
        }
    };

    /* insert svg line breaks: taken from http://stackoverflow.com/questions/13241475/how-do-i-include-newlines-in-labels-in-d3-charts */
    GraphCreator.prototype.insertTitleLinebreaks = function (gEl, title) {
        var words = title.split(/\s+/g),
            nwords = words.length;
        var el = gEl
            .append("text")
            .attr("text-anchor", "middle")
            .attr("dy", "-" + (nwords - 1) * 7.5);

        for (var i = 0; i < words.length; i++) {
            var tspan = el.append("tspan").text(words[i]);
            if (i > 0) {
                tspan.attr("x", 0).attr("dy", "15");
            }
        }
    };

    GraphCreator.prototype.styleTitle = function (gEl, title) {
        var words = title.split(/\s+/g),
            nwords = words.length;
        var el = gEl
            .append("text")
            .attr("text-anchor", "middle")
            .attr("dy", "-" + (nwords - 1) * 7.5);

        for (var i = 0; i < words.length; i++) {
            var tspan = el.append("tspan").text(words[i]);
            if (i > 0) {
                tspan.attr("x", 0).attr("dy", "15");
            } else {
                tspan.attr("font-weight", "bold");
                tspan.attr("font-size", "30px");
            }
        }
    };

    // remove edges associated with a node
    GraphCreator.prototype.spliceLinksForNode = function (node) {
        var thisGraph = this,
            toSplice = thisGraph.edges.filter(function (l) {
                return l.source === node || l.target === node;
            });
        toSplice.map(function (l) {
            thisGraph.edges.splice(thisGraph.edges.indexOf(l), 1);
        });
    };

    GraphCreator.prototype.replaceSelectEdge = function (d3Path, edgeData) {
        var thisGraph = this;
        d3Path.classed(thisGraph.consts.selectedClass, true);
        if (thisGraph.state.selectedEdge) {
            thisGraph.removeSelectFromEdge();
        }
        thisGraph.state.selectedEdge = edgeData;
        d3Path.style("marker-end", "url(#end-arrow-selected)");
    };

    GraphCreator.prototype.replaceSelectNode = function (d3Node, nodeData) {
        var thisGraph = this;
        d3Node.classed(this.consts.selectedClass, true);
        if (thisGraph.state.selectedNode) {
            thisGraph.removeSelectFromNode();
        }
        thisGraph.state.selectedNode = nodeData;
    };

    GraphCreator.prototype.removeSelectFromNode = function () {
        var thisGraph = this;
        thisGraph.circles
            .filter(function (cd) {
                return cd.id === thisGraph.state.selectedNode.id;
            })
            .classed(thisGraph.consts.selectedClass, false);
        thisGraph.state.selectedNode = null;
    };

    GraphCreator.prototype.removeSelectFromEdge = function () {
        var thisGraph = this;
        thisGraph.paths
            .filter(function (cd) {
                return cd === thisGraph.state.selectedEdge;
            })
            .classed(thisGraph.consts.selectedClass, false);
        thisGraph.state.selectedEdge = null;
        d3Path.style("marker-end", "url(#end-arrow)");
    };

    GraphCreator.prototype.pathMouseDown = function (d3path, d) {
        var thisGraph = this,
            state = thisGraph.state;
        d3.event.stopPropagation();
        state.mouseDownLink = d;

        if (state.selectedNode) {
            thisGraph.removeSelectFromNode();
        }

        var prevEdge = state.selectedEdge;
        if (!prevEdge || prevEdge !== d) {
            thisGraph.replaceSelectEdge(d3path, d);
        } else {
            thisGraph.removeSelectFromEdge();
        }
    };

    // mousedown on node
    GraphCreator.prototype.circleMouseDown = function (d3node, d) {
        var thisGraph = this,
            state = thisGraph.state;
        d3.event.stopPropagation();
        state.mouseDownNode = d;
        /*if (d3.event.shiftKey){
        state.shiftNodeDrag = d3.event.shiftKey;
        // reposition dragged directed edge
        thisGraph.dragLine.classed('hidden', false)
            .attr('d', 'M' + d.x + ',' + d.y + 'L' + d.x + ',' + d.y);
        return;
        }*/
    };

    /* place editable text on node in place of svg text */
    GraphCreator.prototype.changeTextOfNode = function (d3node, d) {
        var thisGraph = this,
            consts = thisGraph.consts,
            htmlEl = d3node.node();
        d3node.selectAll("text").remove();
        var nodeBCR = htmlEl.getBoundingClientRect(),
            curScale = nodeBCR.width / consts.nodeRadius,
            placePad = 5 * curScale,
            useHW =
                curScale > 1 ? nodeBCR.width * 0.71 : consts.nodeRadius * 1.42;
        // replace with editableconent text
        var d3txt = thisGraph.svg
            .selectAll("foreignObject")
            .data([d])
            .enter()
            .append("foreignObject")
            .attr("x", nodeBCR.left + placePad)
            .attr("y", nodeBCR.top + placePad)
            .attr("height", 2 * useHW)
            .attr("width", useHW)
            .append("xhtml:p")
            .attr("id", consts.activeEditId)
            .attr("contentEditable", "true")
            .text(d.title)
            .on("mousedown", function (d) {
                d3.event.stopPropagation();
            })
            .on("keydown", function (d) {
                d3.event.stopPropagation();
                if (
                    d3.event.keyCode == consts.ENTER_KEY &&
                    !d3.event.shiftKey
                ) {
                    this.blur();
                }
            })
            .on("blur", function (d) {
                d.title = this.textContent;
                thisGraph.insertTitleLinebreaks(d3node, d.title);
                d3.select(this.parentElement).remove();
            });
        return d3txt;
    };

    // mouseup on nodes
    GraphCreator.prototype.circleMouseUp = function (d3node, d) {
        var thisGraph = this,
            state = thisGraph.state,
            consts = thisGraph.consts;
        // reset the states
        state.shiftNodeDrag = false;
        d3node.classed(consts.connectClass, false);

        var mouseDownNode = state.mouseDownNode;

        if (!mouseDownNode) return;

        thisGraph.dragLine.classed("hidden", true);

        if (mouseDownNode !== d) {
            // we're in a different node: create new edge for mousedown edge and add to graph
            var newEdge = { source: mouseDownNode, target: d };
            var filtRes = thisGraph.paths.filter(function (d) {
                /*if (d.source === newEdge.target && d.target === newEdge.source){
                thisGraph.edges.splice(thisGraph.edges.indexOf(d), 1);
                }*/
                return (
                    d.source === newEdge.source && d.target === newEdge.target
                );
            });
            if (!filtRes[0].length) {
                thisGraph.edges.push(newEdge);
                thisGraph.updateGraph();
            }
        } else {
            // we're in the same node
            if (state.justDragged) {
                // dragged, not clicked
                state.justDragged = false;
            } else {
                // clicked, not dragged
                if (d3.event.shiftKey) {
                    // shift-clicked node: edit text content
                    /*var d3txt = thisGraph.changeTextOfNode(d3node, d);
                    var txtNode = d3txt.node();
                    thisGraph.selectElementContents(txtNode);
                    txtNode.focus();*/
                } else {
                    if (state.selectedEdge) {
                        thisGraph.removeSelectFromEdge();
                    }
                    var prevNode = state.selectedNode;

                    if (!prevNode || prevNode.id !== d.id) {
                        thisGraph.replaceSelectNode(d3node, d);
                    } else {
                        thisGraph.removeSelectFromNode();
                    }
                }
            }
        }
        state.mouseDownNode = null;
        return;
    }; // end of circles mouseup

    // mousedown on main svg
    GraphCreator.prototype.svgMouseDown = function () {
        this.state.graphMouseDown = true;
    };

    // mouseup on main svg
    GraphCreator.prototype.svgMouseUp = function () {
        var thisGraph = this,
            state = thisGraph.state;
        if (state.justScaleTransGraph) {
            // dragged not clicked
            state.justScaleTransGraph = false;
        } /* else if (state.graphMouseDown && d3.event.shiftKey){
            // clicked not dragged from svg
            var xycoords = d3.mouse(thisGraph.svgG.node()),
                d = {id: thisGraph.idct++, title: "new concept", x: xycoords[0], y: xycoords[1]};
            thisGraph.nodes.push(d);
            thisGraph.updateGraph();
            // make title of text immediently editable
            var d3txt = thisGraph.changeTextOfNode(thisGraph.circles.filter(function(dval){
                return dval.id === d.id;
            }), d),
                txtNode = d3txt.node();
            thisGraph.selectElementContents(txtNode);
            txtNode.focus();
        }*/ else if (state.shiftNodeDrag) {
            // dragged from node
            state.shiftNodeDrag = false;
            thisGraph.dragLine.classed("hidden", true);
        }

        state.graphMouseDown = false;
    };

    // keydown on main svg
    GraphCreator.prototype.svgKeyDown = function () {
        var thisGraph = this,
            state = thisGraph.state,
            consts = thisGraph.consts;
        // make sure repeated key presses don't register for each keydown
        if (state.lastKeyDown !== -1) return;

        state.lastKeyDown = d3.event.keyCode;
        var selectedNode = state.selectedNode,
            selectedEdge = state.selectedEdge;

        /*switch(d3.event.keyCode) {
            case consts.BACKSPACE_KEY:
            /*case consts.DELETE_KEY:
            d3.event.preventDefault();
            if (selectedNode){
                thisGraph.nodes.splice(thisGraph.nodes.indexOf(selectedNode), 1);
                thisGraph.spliceLinksForNode(selectedNode);
                state.selectedNode = null;
                thisGraph.updateGraph();
            } else if (selectedEdge){
                thisGraph.edges.splice(thisGraph.edges.indexOf(selectedEdge), 1);
                state.selectedEdge = null;
                thisGraph.updateGraph();
            }
            break;
        }*/
    };

    GraphCreator.prototype.svgKeyUp = function () {
        this.state.lastKeyDown = -1;
    };

    GraphCreator.prototype.active = function (node, row, currentFeatureset) {
        // Break premises by the logical operator
        // In case more operators are included this method has to be updated
        node.value = 0;
        // Argument weight according to premises weight (if they exist)
        node.premise_weight = 1;
        // Calculate minimum and maximum premisse value in order to normalize
        // it with conclusion range
        // http://stats.stackexchange.com/questions/70801/how-to-normalize-data-to-0-1-range
        var minimumPremisseValue = 0,
            maximumPremisseValue = 0;
        node.activated = false;

        var premiseAndConclusion = String(node.tooltip).split(" -> ");
        var hasConclusion =
            premiseAndConclusion.length == 2 &&
            premiseAndConclusion[1] != "NULL";

        var conclusionLabel = "";
        var conclusionFrom = "";
        var conclusionTo = "";

        // Check if there are weight columns in the data (important for calculating nodes values)
        var noPremiseWeights = true;
        for (var key in row) {
            if (key.startsWith("Weight_")) {
                noPremiseWeights = false;
                break;
            }
        }

        // If there is a conclusion get its range
        if (hasConclusion) {
            var begin = 0;
            while (premiseAndConclusion[1][begin] != " ") {
                conclusionLabel += premiseAndConclusion[1][begin];
                begin++;
            }

            begin += 2; //Begin of "from" in conclusion;
            var end = begin + 1;

            while (premiseAndConclusion[1][end] != ",") {
                end++;
            }

            end--; // end of "from";

            for (var i = begin; i <= end; i++) {
                conclusionFrom += premiseAndConclusion[1][i];
            }

            begin = end + 3; //Begin of "to";
            end = begin + 1;

            while (premiseAndConclusion[1][end] != "]") {
                end++;
            }

            end--; // End of "to";

            for (var i = begin; i <= end; i++) {
                conclusionTo += premiseAndConclusion[1][i];
            }

            conclusionFrom = parseFloat(conclusionFrom);
            conclusionTo = parseFloat(conclusionTo);
        }

        //Remove levels and evaluate possible true sets
        var searchBooleanAttr = "";
        // Auxiliar array to find levels of attributes in the true set
        var searchBooleanLevels = "";

        var i = -1;
        do {
            i++;
            // Find first level
            while (i < premiseAndConclusion[0].length && premiseAndConclusion[0][i] != '"') {
                searchBooleanAttr += premiseAndConclusion[0][i];
                searchBooleanLevels += premiseAndConclusion[0][i];
                i++;
            }

            if (i >= premiseAndConclusion[0].length - 1) {
                break;
            }

            // find where level ends
            while (premiseAndConclusion[0][i] != " " && i < premiseAndConclusion[0].length - 1) {
                // Save level
                i++;
                if (premiseAndConclusion[0][i] != " ") {
                    searchBooleanLevels += premiseAndConclusion[0][i];
                }
            }

            if (i >= premiseAndConclusion[0].length - 1) {
                break;
            }

            // First letter of attribute
            i++;

            if (i >= premiseAndConclusion[0].length - 1) {
                break;
            }

            // Copy attribute after level
            while (premiseAndConclusion[0][i] != '"' && i < premiseAndConclusion[0].length - 1) {
                searchBooleanAttr += premiseAndConclusion[0][i];
                i++;
            }
            // Continue while there is another attribute
        } while (i < premiseAndConclusion[0].length - 1);

        searchBooleanAttr = fixSpaces(searchBooleanAttr);
        searchBooleanLevels = fixSpaces(searchBooleanLevels);

        // Example: var string = "high Arousal" OR "low Frustration" OR
        //                       "high ContextBias" AND "mediumLower Skill"
        // searchBooleanAttr = Arousal OR Frustration OR ContextBias AND Skill
        // searchBooleanLevels = high OR low OR high AND mediumLower

        // True set
        // Example: parsedQuery = [[Arousal], [Frustration], [ContextBias, Skill]]
        var parsedQuery = parseBooleanQuery(searchBooleanAttr);

        // Equivalent true set but with levels
        // Example: parsedQueryLevels = [[high], [low], [high, mediumLower]]
        var parsedQueryLevels = parseBooleanQuery(searchBooleanLevels);

        // Find activated attributes
        var premises = [];
        for (var i = 0; i < parsedQuery.length; i++) {
            for (var j = 0; j < parsedQuery[i].length; j++) {
                premises.push(parsedQueryLevels[i][j]);
                premises.push(parsedQuery[i][j]);
            }
        }

        // Example: premises = [high, Arousal, low, Frustration,
        //                      high, ContextBias, mediumLower, Skill]
        // var activatedAttributes = [];
        // var activatedLevels = [];
        // Keep all activated attributes and respective levels in a dictionary.
        // For example, allActivated[Arousal] = high.
        let allActivated = {};

        // Find activated premisses
        for (var premI = 0; premI < premises.length - 1; premI += 2) {
            var level = premises[premI];
            var attribute = premises[premI + 1];

            // Attribute not imported
            if (row[attribute] == undefined || row[attribute] == "?") {
                continue;
            }

            // Run through all attributes and their respective level in order
            // to find the activates premise's attributes
            for (var attr = 0; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {
                var correctAttributeLevel = 
                    attributesByFeatureset_[currentFeatureset][attr].attribute == attribute &&
                    attributesByFeatureset_[currentFeatureset][attr].a_level == level;

                if (! correctAttributeLevel) {
                    continue;
                }

                // Check if table data is inside range
                var notInsideRange =
                    parseFloat(row[attribute]) > parseFloat(attributesByFeatureset_[currentFeatureset][attr].a_to) ||
                    parseFloat(row[attribute]) < parseFloat(attributesByFeatureset_[currentFeatureset][attr] .a_from);
                if (notInsideRange) {
                    continue;
                } else {
                    allActivated[attribute] = level;
                }
            }
        }

        // Find if there is a set of activated attributes able to evaluate the
        // premisse as true
        // Go through all sets of possible attributes that evalutes the premisse
        // as true
        var trueSets = [];
        for (var i = 0; i < parsedQuery.length; i++) {
            // For each set check if all elements are in the activates attributes
            // set
            for (var j = 0; j < parsedQuery[i].length; j++) {
                // If one of the attributes is not in the activated set go to
                // the next set of possibilites

                if (allActivated[parsedQuery[i][j]] == undefined) {
                    break;
                }

                if (
                    allActivated[parsedQuery[i][j]] != parsedQueryLevels[i][j]
                ) {
                    break;
                }

                // If here then all attributes in the set are activated and
                // node can be activated
                if (j == parsedQuery[i].length - 1) {
                    node.activated = true;

                    // Node is activated and the value is the conclusion. Can return here.
                    if (noPremiseWeights && Math.abs(conclusionFrom - conclusionTo) < 0.00001) {
                        node.value = conclusionTo;
                        return true;
                    }

                    trueSets.push(i);
                }
            }
        }

        // Example: parsedQuery = [[Arousal], [Frustration], [ContextBias, Skill]]
        // Example: parsedQueryLevels = [[high], [low], [high, mediumLower]]
        // Example all nodes activated: trueSets = [0, 1, 2]

        // If node was not activated there is no need to calculate its value
        // and the function can return false
        if (node.activated == false) {
            return false;
        }

        // If node has no conclusion there is no need to calculate its value
        // and the function can return true
        if (! hasConclusion) {
            return true;
        }

        // Find mininmum and maximum ranges of activated true sets
        var trueSetsMinMax = [];
        var trueSetsValues = [];
        var trueSetsWeights = [];
        // Example: parsedQuery = [[Arousal], [Frustration], [ContextBias, Skill]]
        // Example: parsedQueryLevels = [[high], [low], [high, mediumLower]]
        // Example all nodes activated: trueSets = [0, 1, 2]
        // Example: trueSetsMinMax = [[minArousal, maxArousal], [minFrustrations, maxFrustration],
        //                            [max(minContextBias, minSkills), max(maxContextBias, maxSkill)]]
        // Example: trueSetsValues = [max(Arousal), max(Frustration), max(ContextBias, Skill)]
        // Example: trueSetsWeights = [max(Weight_Arousal), max(Weight_Frustration), max(Weight_ContextBias, Weight_Skill)]
        for (var i = 0; i < trueSets.length; i++) {
            for (var trueAttr = 0; trueAttr < parsedQuery[trueSets[i]].length; trueAttr++) {
                var attribute = parsedQuery[trueSets[i]][trueAttr];
                var level = parsedQueryLevels[trueSets[i]][trueAttr];

                // Find attribute in dataset
                for (var attr = 0; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {
                    if (attributesByFeatureset_[currentFeatureset][attr].attribute != attribute ||
                        attributesByFeatureset_[currentFeatureset][attr].a_level != level) {
                        continue;
                    }

                    if (trueSetsMinMax[i] == undefined) {
                        trueSetsMinMax.push([parseFloat(attributesByFeatureset_[currentFeatureset][attr].a_from),
                            parseFloat(attributesByFeatureset_[currentFeatureset][attr].a_to),
                        ]);

                        // Save associated value and weight to this range
                        trueSetsValues.push(parseFloat(row[attribute]));

                        if (typeof row["Weight_" + attribute] !== "undefined") {
                            trueSetsWeights.push(parseFloat(row["Weight_" + attribute]));
                        } else {
                            trueSetsWeights.push(1);
                        }
                    } else {
                        var min = Math.max(
                            trueSetsMinMax[i][0],
                            parseFloat(attributesByFeatureset_[currentFeatureset][attr].a_from)
                        );
                        var max = Math.max(
                            trueSetsMinMax[i][1],
                            parseFloat(attributesByFeatureset_[currentFeatureset][attr].a_to)
                        );

                        trueSetsMinMax[i][0] = min;
                        trueSetsMinMax[i][1] = max;

                        // Save associated value and weight in case it is the max
                        if (parseFloat(row[attribute]) > trueSetsValues[i]) {
                            trueSetsValues[i] = parseFloat(row[attribute]);
                            // If one weight is defined all others are
                            if (typeof row["Weight_" + attribute] !== "undefined") {
                                if (row["Weight_" + attribute] > trueSetsWeights[i]) {
                                    trueSetsWeights[i] = parseFloat(row["Weight_" + attribute]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // True sets have been aggreagate by the AND (max) operator
        // Now they are aggregated by the OR (min) operator
        minimumPremisseValue = Number.MAX_VALUE;
        maximumPremisseValue = Number.MAX_VALUE;
        node.value = Number.MAX_VALUE;
        for (var i = 0; i < trueSetsMinMax.length; i++) {
            minimumPremisseValue = Math.min(
                minimumPremisseValue,
                trueSetsMinMax[i][0]
            );
            maximumPremisseValue = Math.min(
                maximumPremisseValue,
                trueSetsMinMax[i][1]
            );
            // Choose the true set with maximum value
            if (trueSetsValues[i] < node.value) {
                node.value = trueSetsValues[i];
                node.premise_weight = trueSetsWeights[i];
            }
        }

        if (
            maximumPremisseValue - minimumPremisseValue != 0 &&
            conclusionTo - conclusionFrom != 0
        ) {
            node.value =
                ((conclusionTo - conclusionFrom) /
                    (maximumPremisseValue - minimumPremisseValue)) *
                    (node.value - maximumPremisseValue) +
                conclusionTo;
        } else {
            // An example of this case is [1, 1] -> [8, 10] or [1, 10] -> [8, 8]
            // The first makes no sense so the max value of the conclusion
            // is chosen arbitrarily. The second makes sense but we need the
            // nodes weight so it is set here and not before the weight calculation.
            node.value = conclusionTo;
        }

        return true;
    };

    GraphCreator.prototype.getActivatedJson = function () {
        var thisGraph = graph,
            consts = thisGraph.consts,
            state = thisGraph.state;

        var edges = thisGraph.edges;

        var jsonActivated = "";

        // Include edges
        thisGraph.circles.each(function (d) {
            if (d.activated) {
                jsonActivated += d.title + ",";
            }
        });

        // Remove last coma
        jsonActivated = jsonActivated.substring(0, jsonActivated.length - 1);

        return jsonActivated;
    };

    GraphCreator.prototype.isAttackValid = function (nodeSource, nodeTarget, toFile) {
        // Check if attack is valid based if there are conclusions and weight of premises.
        // It DOES NOT check if attack is in budget.
        var premiseAndConclusion = String(nodeSource.tooltip).split(" -> ");
        // Only arguments with a conclusion will have a value
        var hasConclusionSource =
            premiseAndConclusion.length == 2 &&
            premiseAndConclusion[1] != "NULL";

        premiseAndConclusion = String(nodeTarget.tooltip).split(" -> ");
        // Only arguments with a conclusion will have a value
        var hasConclusionTarget =
            premiseAndConclusion.length == 2 &&
            premiseAndConclusion[1] != "NULL";

        var strenthOfArguments;
        if (!toFile) {
            strenthOfArguments = document.getElementById("strengthCheckBox").checked;
        } else {
            strenthOfArguments = document.getElementById("strengthCheckBoxExport").checked;
        }

        // If target or souce have no conclusion the
        // attack is activated regardless of weights.
        // If they both have conclusions the source weight
        // has to be greater than the target weight for
        // the attack to be activated.
        if (! hasConclusionSource ||
            ! hasConclusionTarget ||
            (hasConclusionSource && hasConclusionTarget &&
                (nodeSource.premise_weight >= nodeTarget.premise_weight || ! strenthOfArguments))) {
            return true;
        }

        return false;
    };

    GraphCreator.prototype.getStringGraph = function (toFile = false) {
        var thisGraph = graph,
            consts = thisGraph.consts,
            state = thisGraph.state;

        var edges = thisGraph.edges;

        var stringGraph = "";

        // Include edges
        thisGraph.circles.each(function (td) {
            edges.forEach(function (val, i) {
                if (val.target.id == td.id && td.activated) {
                    var sourceCircle = thisGraph.circles.filter(function (sd) {
                        return sd.id === val.source.id;
                    });

                    sourceCircle.each(function (sd) {
                        if (sd.activated) {
                            if (graph.isAttackValid(sd, td, toFile) && val.in_budget) {
                                stringGraph += sd.title + "," + td.title + ",";
                            }
                        }
                    });
                }
            });
        });

        stringGraph = stringGraph.substring(0, stringGraph.length - 1);

        // Include isolated nodes
        var first = true;
        thisGraph.circles.each(function (d) {
            if (d.degree == 0 && d.activated) {
                if (first) {
                    stringGraph += ":" + d.title + ",";
                    first = false;
                } else {
                    stringGraph += d.title + ",";
                }
            }
        });

        // Remove last coma if an isolated node was inserted
        if (!first) {
            stringGraph = stringGraph.substring(0, stringGraph.length - 1);
        }

        //console.log(stringGraph);

        return stringGraph;
    };

    GraphCreator.prototype.activeAll = function (row, currentFeatureset, colors, toFile) {
        if (colors == undefined) {
            colors = true;
        }

        var thisGraph = this,
            consts = thisGraph.consts;

        var edges = thisGraph.edges;

        thisGraph.circles.each(function (d) {
            var currentNode = d3.select(this);
            if (colors) {
                currentNode.classed(consts.circleGClass, true);
                currentNode.classed(consts.deniedGClass, false);
                currentNode.classed(consts.acceptedGClass, false);
                currentNode.classed(consts.onlyActivatedGClass, false);
            }

            // Set node as active or not based on the row
            thisGraph.active(d, row, currentFeatureset);
        });

        var htmlArgs = ""; // To list them all in a modal
        var htmlAttacks = ""; // To list themm all in a modal
        var argumentsN = 0;
        var attacksN = 0;

        thisGraph.circles.each(function (d) {
            if (d.activated) {
                d.degree = 0;

                var premiseAndConclusion = String(d.tooltip).split(" -> ");
                var hasConclusion =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                if (hasConclusion != "NULL") {
                    htmlArgs += "<i>" + d.title + "</i>: " + premiseAndConclusion[0] + " <b>&#8594;</b> " + premiseAndConclusion[1] + "<br>";
                } else {
                    htmlArgs += "<i>" + d.title + "</i>: " + d.tooltip + "<br>";
                }

                argumentsN++;

                edges.forEach(function (edge, i) {
                    // Count edges in which d is the target
                    if (edge.target.id == d.id) {
                        var sourceCircle = thisGraph.circles.filter(function (sd) {
                            return sd.id === edge.source.id;
                        });

                        sourceCircle.each(function (sd) {
                            if (sd.activated) {
                                if (graph.isAttackValid(sd, d, toFile) && edge.in_budget) {
                                    htmlAttacks += "<i>" + sd.title + "</i>  &rArr; <i>" + d.title + "</i><br>";
                                    attacksN++;
                                    d.degree++;
                                }
                            }
                        });
                    }

                    // Count edges in which d is the source
                    if (edge.source.id == d.id) {
                        var targetCircle = thisGraph.circles.filter(function (td) {
                            return td.id === edge.target.id;
                        });

                        targetCircle.each(function (td) {
                            if (td.activated) {
                                if (graph.isAttackValid(d, td, toFile) && edge.in_budget) {
                                    htmlAttacks += "<i>" + d.title + "</i>  &rArr; <i>" + td.title + "</i><br>";
                                    attacksN++;
                                    d.degree++;
                                }
                            }
                        });
                    }
                });
            }
        });

        document.getElementById("listArgumentsFiltered").innerHTML = htmlArgs;
        document.getElementById("listArgumentsNFiltered").innerHTML = " <b>(" + argumentsN.toString() + ")</b>";
        document.getElementById("listAttacksFiltered").innerHTML = htmlAttacks;
        document.getElementById("listAttacksNFiltered").innerHTML = " <b>(" + attacksN.toString() + ")</b>";
    };

    GraphCreator.prototype.semanticsPerRow = function (
        extension,
        toFile = false,
        onlyActivated = false,
        rankBased = false
    ) {
        var thisGraph = this,
            consts = thisGraph.consts,
            state = thisGraph.state;

        var edges = thisGraph.edges;
        var result;

        // Set all paths as non activated.
        thisGraph.paths.each(function (d) {
            d3.select(this).style("opacity", 0.15);
        });

        // Set opacity of all nodes as activated
        thisGraph.circles.each(function (d) {
            d3.select(this).style("opacity", 1.0);
        });

        //var activatedNodes = "<b>Acvitaded arguments:</b> ";
        /*var notActivadedNodes = "<br><b>Not activated arguments:</b> ";
        var reasoning = "<br><br><b>Reasoning:</b><br>";
        var argsIndex = "<br><b>Acrual</b><br>Arguments that contributed for the final index: ";*/
        var accrual = "";

        //var nActivated = 0;
        /*var nNotActivated = 0;
        var emptyReasoning = true;*/
        // Overall number of accepted arguments
        var nAccepted = 0;
        // Arguments accepted for each conclusion
        var nConclusions = [];
        // Conclusion with the maximum right side range
        var highestConclustion = Number.MIN_VALUE;
        // Sum of accepted arguments values for each conclusion
        var acceptedValue = [];
        var weights = [];
        var overall = 0;
        var overallWeighted = 0;
        var sumWeights = 0;
        // Vector of values accepted to calculate the median
        var valuesAccepted = [];

        for (i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
            nConclusions[i] = 0;
            acceptedValue[i] = 0;
        }

        $("#results").remove();

        var strenthOfArguments;

        if (! toFile) {
            strenthOfArguments = document.getElementById("strengthCheckBox").checked;
        } else {
            strenthOfArguments = document.getElementById("strengthCheckBoxExport").checked;
        }

        // Find activated edges and nodes and change their styles
        thisGraph.circles.each(function (sd) {
            var currentNode = d3.select(this);
            if (sd.activated) {
                console.log(sd.title + ": " + sd.value);

                var premiseAndConclusion = String(sd.tooltip).split(" -> ");
                // Only arguments with a conclusion will have a value
                var hasConclusionSource =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                // Nodes without conclusion might have their internal structure
                // activated. However, at this point they will only be considered
                // if they are the source of an attack
                if (!hasConclusionSource) {
                    //currentNode.style("opacity", 0.40);
                }

                edges.forEach(function (edge) {
                    if (edge.source.id == sd.id) {
                        thisGraph.circles.each(function (td) {
                            if (td.activated && edge.target.id == td.id) {
                                var premiseAndConclusion = String(td.tooltip).split(" -> ");
                                // Only arguments with a conclusion will have a value
                                var hasConclusionTarget =
                                    premiseAndConclusion.length == 2 &&
                                    premiseAndConclusion[1] != "NULL";

                                // If target or souce have no conclusion the
                                // attack is activated regardless of weights.
                                // If they both have conclusions the source weight
                                // has to be greater than the target weight for
                                // the attack to be activated.
                                if (! hasConclusionSource || 
                                    ! hasConclusionTarget ||
                                     (hasConclusionSource && hasConclusionTarget && (sd.premise_weight >= td.premise_weight || ! strenthOfArguments))) {

                                    var path = thisGraph.paths.filter(function (pd) {
                                        //console.log("s: " + sd.title + ", t: " + td.title);
                                        return (pd.source.id == edge.source.id && pd.target.id == edge.target.id
                                        );
                                    });
                                    
                                    if (edge.in_budget) {
                                        path.style("opacity", 1.0);
                                    }

                                    if (! hasConclusionSource) {
                                        currentNode.style("opacity", 1.0);
                                    }
                                }
                            }
                        });
                    }
                });
            } else {
                currentNode.style("opacity", 0.4);
                //currentNode.style("opacity", 0.00);
            }
        });

        var original = extension;
        var onlyConclusions = ",";

        if (rankBased) {
            var i = 0;
            var previousValue;
            var allLabels = "";
            for (var prop in extension) {
                allLabels += String(prop) + ",";

                // First value is always inserted
                var premiseAndConclusion;

                thisGraph.circles.filter(function (d) {
                    if (d.title == String(prop)) {
                        premiseAndConclusion = d.tooltip;
                    }
                });

                premiseAndConclusion =
                    String(premiseAndConclusion).split(" -> ");
                var hasConclusion =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                if (!hasConclusion) {
                    continue;
                }

                if (i == 0) {
                    onlyConclusions += String(prop) + ",";
                    previousValue = extension[prop];
                    // If next value equal previous value it is also inserted
                } else if (Math.abs(previousValue - extension[prop]) < 0.000001) {
                    onlyConclusions += "," + String(prop) + ",";
                    // If new greater value has been found restart extension
                } else if (previousValue < extension[prop]) {
                    onlyConclusions = "," + String(prop) + ",";
                    previousValue = extension[prop];
                }
                i++;
            }

            // Remove last comma cause it will be inserted again below
            allLabels = allLabels.slice(0, -1);
            extension = allLabels;
        }

        extension = "," + extension + ",";
        thisGraph.circles.each(function (d) {
            var currentNode = d3.select(this);

            if (extension.indexOf("," + d.title + ",") != -1) {
                var premiseAndConclusion = String(d.tooltip).split(" -> ");
                // Only arguments with a conclusion will have a value
                var hasConclusion =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                // If it is rankBased not all nodes in the extension are accepted.
                // Only the nodes with a conclusion and highest rank are accepted.
                if ((hasConclusion && !rankBased) || (rankBased && onlyConclusions.indexOf("," + d.title + ",") != -1)) {
                    valuesAccepted[nAccepted] = d.value;
                    nAccepted++;

                    var conclusionNoRange = "";

                    for (var i = 0; i < premiseAndConclusion[1].length; i++) {
                        if (premiseAndConclusion[1][i] != " ") {
                            conclusionNoRange += premiseAndConclusion[1][i];
                        } else {
                            break;
                        }
                    }

                    var indexConclusion = 0;

                    for (var i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
                        if (conclusionsByFeatureset_[currentFeatureset][i].conclusion == conclusionNoRange) {
                            if (parseFloat(conclusionsByFeatureset_[currentFeatureset][i].c_to) > highestConclustion) {
                                highestConclustion = parseFloat(conclusionsByFeatureset_[currentFeatureset][i].c_to);
                            }
                            indexConclusion = i;
                            break;
                        }
                    }

                    nConclusions[indexConclusion]++;
                    acceptedValue[indexConclusion] += d.value;
                    overall += d.value;
                    overallWeighted += d.value * d.weight;
                    sumWeights += d.weight;
                }

                currentNode.classed(consts.circleGClass, false);
                currentNode.classed(consts.deniedGClass, false);

                if (!hasConclusion) {
                    // If it has no conclusion the color will change only if there
                    // is an activated attack from this node
                    var hasTarget = false;
                    edges.forEach(function (edge, i) {
                        if (edge.source.id == d.id) {
                            thisGraph.circles.each(function (td) {
                                if (td.activated && edge.target.id == td.id) {
                                    currentNode.classed(
                                        consts.onlyActivatedGClass,
                                        true
                                    );
                                    currentNode.classed(
                                        consts.acceptedGClass,
                                        false
                                    );
                                    hasTarget = true;
                                }
                            });
                        }
                    });

                    if (!hasTarget) {
                        currentNode.classed(consts.circleGClass, true);
                    }
                } else if (onlyActivated) {
                    currentNode.classed(consts.onlyActivatedGClass, true);
                    currentNode.classed(consts.acceptedGClass, false);
                } else {
                    if (!rankBased ||(rankBased && onlyConclusions.indexOf("," + d.title + ",") != -1)) {
                        currentNode.classed(consts.acceptedGClass, true);
                        currentNode.classed(consts.onlyActivatedGClass, false);
                    }

                    if (rankBased && onlyConclusions.indexOf("," + d.title + ",") == -1) {
                        currentNode.classed(consts.circleGClass, false);
                        currentNode.classed(consts.deniedGClass, true);
                        currentNode.classed(consts.acceptedGClass, false);
                        currentNode.classed(consts.onlyActivatedGClass, false);
                    }
                }
            } else if (
                extension.indexOf("," + d.title + ",") == -1 &&
                d.activated
            ) {
                currentNode.classed(consts.circleGClass, false);
                currentNode.classed(consts.deniedGClass, true);
                currentNode.classed(consts.acceptedGClass, false);
                currentNode.classed(consts.onlyActivatedGClass, false);
            }
        });

        var numberConclusions = "";
        for (i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
            numberConclusions += String(nConclusions[i]) + ":" + conclusionsByFeatureset_[currentFeatureset][i].conclusion;
            if (i < conclusionsByFeatureset_[currentFeatureset].length - 1) {
                numberConclusions += ";";
            }
        }

        var iHighestCardConclusion = [];
        iHighestCardConclusion[0] = 0;
        var overallHCC = acceptedValue[0] / nConclusions[0];
        var nHigh = 1;
        for (i = 1; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
            if (nConclusions[i] > nConclusions[iHighestCardConclusion[0]]) {
                iHighestCardConclusion = [];
                iHighestCardConclusion[0] = i;
                overallHCC = acceptedValue[i] / nConclusions[i];
                nHigh = 1;
            } else if (
                nConclusions[i] == nConclusions[iHighestCardConclusion[0]]
            ) {
                overallHCC =
                    (overallHCC * nHigh + acceptedValue[i] / nConclusions[i]) /
                    (nHigh + 1);
                iHighestCardConclusion[nHigh] = i;
                nHigh++;
            }
        }

        // Find the weighted average of the arguments who belong to the highest
        // conclusions
        var overallWeightedFiltered = 0;
        var sumWeightedFiltered = 0;

        thisGraph.circles.each(function (d) {
            var currentNode = d3.select(this);
            if (extension.indexOf("," + d.title + ",") != -1) {
                var premiseAndConclusion = String(d.tooltip).split(" -> ");
                // Only arguments with a conclusion will have a value
                var hasConclusion =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                // If it is rankBased not all nodes in the extension are accepted.
                // Only the nodes with a conclusion and highest rank are accepted.
                if ((hasConclusion && !rankBased) || (rankBased && onlyConclusions.indexOf("," + d.title + ",") != -1)) {
                    var conclusionNoRange = "";
                    for (var i = 0; i < premiseAndConclusion[1].length; i++) {
                        if (premiseAndConclusion[1][i] != " ") {
                            conclusionNoRange += premiseAndConclusion[1][i];
                        } else {
                            break;
                        }
                    }

                    console.log("Is there range? " + conclusionNoRange);

                    for (i = 0; i < iHighestCardConclusion.length; i++) {
                        var highestConclusion =
                            conclusionsByFeatureset_[currentFeatureset][
                                iHighestCardConclusion[i]
                            ].conclusion;
                        if (conclusionNoRange == highestConclusion) {
                            overallWeightedFiltered += d.value * d.weight;
                            sumWeightedFiltered += d.weight;
                            console.log("O +:" + d.value + " * " + d.weight);
                        }
                    }
                }
            }
        });

        // Find number of conclusions that are not in the highest cardinality set(s)
        var nNotHigh = 0;
        for (i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
            if (nConclusions[i] < nConclusions[iHighestCardConclusion[0]]) {
                nNotHigh += nConclusions[i];
            }
        }

        if (nAccepted > 0) {
            // Round index to two decimals
            for (var i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
                if (i > 0) {
                    accrual += "<br>";
                }

                if (nConclusions[i] > 0) {
                    acceptedValue[i] /= nConclusions[i];

                    var numberParts = String(acceptedValue[i]).split(".");
                    if (numberParts.length > 1) {
                        acceptedValue[i] =
                            numberParts[0] + "." + numberParts[1].slice(0, 2);
                    }

                    accrual +=
                        "Average arguments with <i>" +
                        String(
                            conclusionsByFeatureset_[currentFeatureset][i]
                                .conclusion
                        ) +
                        "</i> (" +
                        String(nConclusions[i]) +
                        ") conclusion: " +
                        String(acceptedValue[i]);
                } else {
                    accrual +=
                        "No arguments with <i>" +
                        String(
                            conclusionsByFeatureset_[currentFeatureset][i]
                                .conclusion
                        ) +
                        "</i> conclusion.";
                }
            }
            if (rankBased) {
                accrual += "<br><br>Argument ranking:<br>";
                for (var key in original) {
                    accrual += "<i>" + key + "</i>: " + original[key] + "<br>";
                }

                // Remove last <br>
                accrual = accrual.slice(0, -4);
            }

            if (!toFile) {
                var select = document.getElementById("accrualVisualization"),
                    i = select.selectedIndex,
                    currentAggregation = select.options[i].text;

                if (currentAggregation == "Average") {
                    overall /= nAccepted;
                } else if (currentAggregation == "Highest cardinality") {
                    overall = overallHCC;
                } else if (currentAggregation == "Median") {
                    valuesAccepted.sort((a, b) => a - b);
                    let lowMiddle = Math.floor((valuesAccepted.length - 1) / 2);
                    let highMiddle = Math.ceil((valuesAccepted.length - 1) / 2);
                    overall =
                        (valuesAccepted[lowMiddle] +
                            valuesAccepted[highMiddle]) /
                        2;
                } else if (currentAggregation == "Weighted average") {
                    overallWeighted /= sumWeights;
                    console.log("sum: " + sumWeights);
                    overall = overallWeighted;
                } else if (currentAggregation == "Highest conclusion") {
                    overall = highestConclustion;
                } else if (
                    currentAggregation == "Absolute highest conclusion"
                ) {
                    overall = overallHCC - nNotHigh;
                } else if (currentAggregation == "Highest and weighted") {
                    if (sumWeightedFiltered > 0) {
                        overall = overallWeightedFiltered / sumWeightedFiltered;
                    } else {
                        overall = "None";
                    }
                }
            } else {
                if (document.getElementById("accrualAverageExport").checked) {
                    overall /= nAccepted;
                } else if (
                    document.getElementById("cardinalityAverageExport").checked
                ) {
                    overall = overallHCC;
                } else if (document.getElementById("medianExport").checked) {
                    valuesAccepted.sort((a, b) => a - b);
                    let lowMiddle = Math.floor((valuesAccepted.length - 1) / 2);
                    let highMiddle = Math.ceil((valuesAccepted.length - 1) / 2);
                    overall =
                        (valuesAccepted[lowMiddle] +
                            valuesAccepted[highMiddle]) /
                        2;
                } else if (document.getElementById("weightedExport").checked) {
                    overallWeighted /= sumWeights;
                    overall = overallWeighted;
                } else if (
                    document.getElementById("highestConclusionExport").checked
                ) {
                    overall = highestConclustion;
                } else if (
                    document.getElementById("absoluteConclusionExport").checked
                ) {
                    overall = overallHCC - nNotHigh;
                } else if (
                    document.getElementById("highestWeightedExport").checked
                ) {
                    if (sumWeightedFiltered > 0) {
                        overall = overallWeightedFiltered / sumWeightedFiltered;
                    } else {
                        overall = "None";
                    }
                }
            }

            var numberParts = String(overall).split(".");
            if (numberParts.length > 1) {
                overall = numberParts[0] + "." + numberParts[1].slice(0, 2);
            }

            var select = document.getElementById("accrualVisualization"),
                i = select.selectedIndex,
                currentAggregation = select.options[i].text;

            if (
                (toFile &&
                    !document.getElementById("nConclusionsExport").checked) ||
                (!toFile && currentAggregation != "# Conclusions")
            ) {
                result = String(overall);
            } else {
                result = numberConclusions;
            }

            accrual += "<br><br>Average of all accepted arguments: " + result;
        } else {
            accrual += "<br>No argument was accepted";
        }

        thisGraph.circles.exit().remove();
        //$('#resultsColumn').append("<p id='results'>" + accrual + "</p>");
        var popover = $("#resultsContent")
            .attr("data-content", accrual)
            .html(result)
            .data("bs.popover");
        popover.setContent();
        popover.$tip.addClass(popover.options.placement);
        //$('#resultsContent').html(accrual");
        //console.log("Visible: " + result);
        return result;
    };

    GraphCreator.prototype.semanticsPerRowInvisible = function (
        extension,
        accrual,
        rankBased = false
    ) {
        var thisGraph = this,
            consts = thisGraph.consts,
            state = thisGraph.state;

        var edges = thisGraph.edges;
        var result;
        var overallString = "";

        // Overall number of accepted arguments
        var nAccepted = 0;
        // Arguments accepted for each conclusion
        var nConclusions = [];
        // Conclusion with the maximum right side range
        var highestConclustion = Number.MIN_VALUE;
        // Sum of accepted arguments values for each conclusion
        var acceptedValue = [];
        var weights = [];
        var overall = 0;
        var overallWeighted = 0;
        var sumWeights = 0;
        // Vector of values accepted to calculate the median
        var valuesAccepted = [];

        for (i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
            nConclusions[i] = 0;
            acceptedValue[i] = 0;
        }

        var original = extension;
        var onlyConclusions = ",";

        if (rankBased) {
            var i = 0;
            var previousValue;
            var allLabels = "";
            for (var prop in extension) {
                allLabels += String(prop) + ",";

                // First value is always inserted
                var premiseAndConclusion;

                thisGraph.circles.filter(function (d) {
                    if (d.title == String(prop)) {
                        premiseAndConclusion = d.tooltip;
                    }
                });

                premiseAndConclusion =
                    String(premiseAndConclusion).split(" -> ");
                var hasConclusion =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                if (!hasConclusion) {
                    continue;
                }

                if (i == 0) {
                    onlyConclusions += String(prop) + ",";
                    previousValue = extension[prop];
                    // If next value equal previous value it is also inserted
                } else if (Math.abs(previousValue - extension[prop]) < 0.000001) {
                    onlyConclusions += "," + String(prop) + ",";
                    // If new greater value has been found restart extension
                } else if (previousValue < extension[prop]) {
                    onlyConclusions = "," + String(prop) + ",";
                    previousValue = extension[prop];
                }
                i++;
            }

            // Remove last comma cause it will be inserted again below
            allLabels = allLabels.slice(0, -1);
            extension = allLabels;
        }

        extension = "," + extension + ",";
        thisGraph.circles.each(function (d) {
            var currentNode = d3.select(this);

            if (extension.indexOf("," + d.title + ",") != -1) {
                var premiseAndConclusion = String(d.tooltip).split(" -> ");
                // Only arguments with a conclusion will have a value
                var hasConclusion =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                // If it is rankBased not all nodes in the extension are accepted.
                // Only the nodes with a conclusion and highest rank are accepted.
                if ((hasConclusion && !rankBased) || (rankBased && onlyConclusions.indexOf("," + d.title + ",") != -1)) {

                    valuesAccepted[nAccepted] = d.value;
                    nAccepted++;

                    var conclusionNoRange = ""; // Conclusion level

                    for (var i = 0; i < premiseAndConclusion[1].length; i++) {
                        if (premiseAndConclusion[1][i] != " ") {
                            conclusionNoRange += premiseAndConclusion[1][i];
                        } else {
                            break;
                        }
                    }

                    var indexConclusion = 0;
                    //console.log("data");
                    //console.log(conclusionsByFeatureset_[currentFeatureset]);
                    for (var i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
                        if (conclusionsByFeatureset_[currentFeatureset][i].conclusion == conclusionNoRange) {
                            //console.log(conclusionsByFeatureset_[currentFeatureset][i].c_to);
                            if (parseFloat(conclusionsByFeatureset_[currentFeatureset][i].c_to) > highestConclustion) {
                                highestConclustion = parseFloat(conclusionsByFeatureset_[currentFeatureset][i].c_to);
                            }
                            //console.log("h: " + highestConclustion);
                            indexConclusion = i;
                            break;
                        }
                    }

                    nConclusions[indexConclusion]++;
                    acceptedValue[indexConclusion] += d.value;
                    overall += d.value;
                    overallWeighted += d.value * d.weight;
                    sumWeights += d.weight;
                }
            }
        });

        // Define string with the number of arguments by conclusions
        var numberConclusions = "";
        for (i = 0; i < conclusionsByFeatureset_[currentFeatureset].length; i++) {
            numberConclusions +=
                String(nConclusions[i]) +
                ":" +
                conclusionsByFeatureset_[currentFeatureset][i].conclusion;
            if (i < conclusionsByFeatureset_[currentFeatureset].length - 1) {
                numberConclusions += ";";
            }
        }

        var iHighestCardConclusion = [];
        iHighestCardConclusion[0] = 0;
        var overallHCC = acceptedValue[0] / nConclusions[0];
        var nHigh = 1;
        for (
            i = 1;
            i < conclusionsByFeatureset_[currentFeatureset].length;
            i++
        ) {
            if (nConclusions[i] > nConclusions[iHighestCardConclusion[0]]) {
                iHighestCardConclusion = [];
                iHighestCardConclusion[0] = i;
                overallHCC = acceptedValue[i] / nConclusions[i];
                nHigh = 1;
            } else if (
                nConclusions[i] == nConclusions[iHighestCardConclusion[0]]
            ) {
                overallHCC =
                    (overallHCC * nHigh + acceptedValue[i] / nConclusions[i]) /
                    (nHigh + 1);
                iHighestCardConclusion[nHigh] = i;
                nHigh++;
            }
        }

        // Find the weighted average of the arguments who belong to the highest
        // conclusions
        var overallWeightedFiltered = 0;
        var sumWeightedFiltered = 0;

        thisGraph.circles.each(function (d) {
            var currentNode = d3.select(this);
            if (extension.indexOf("," + d.title + ",") != -1) {
                var premiseAndConclusion = String(d.tooltip).split(" -> ");
                // Only arguments with a conclusion will have a value
                var hasConclusion =
                    premiseAndConclusion.length == 2 &&
                    premiseAndConclusion[1] != "NULL";

                // If it is rankBased not all nodes in the extension are accepted.
                // Only the nodes with a conclusion and highest rank are accepted.
                if (
                    (hasConclusion && !rankBased) ||
                    (rankBased &&
                        onlyConclusions.indexOf("," + d.title + ",") != -1)
                ) {
                    var conclusionNoRange = "";
                    for (var i = 0; i < premiseAndConclusion[1].length; i++) {
                        if (premiseAndConclusion[1][i] != " ") {
                            conclusionNoRange += premiseAndConclusion[1][i];
                        } else {
                            break;
                        }
                    }

                    for (i = 0; i < iHighestCardConclusion.length; i++) {
                        var highestConclusion =
                            conclusionsByFeatureset_[currentFeatureset][
                                iHighestCardConclusion[i]
                            ].conclusion;
                        if (conclusionNoRange == highestConclusion) {
                            overallWeightedFiltered += d.value * d.weight;
                            sumWeightedFiltered += d.weight;
                        }
                    }
                }
            }
        });

        // Find number of conclusions that are not in the highest cardinality set(s)
        var nNotHigh = 0;
        for (
            i = 0;
            i < conclusionsByFeatureset_[currentFeatureset].length;
            i++
        ) {
            if (nConclusions[i] < nConclusions[iHighestCardConclusion[0]]) {
                nNotHigh += nConclusions[i];
            }
        }

        if (nAccepted > 0) {
            // Round index to two decimals
            for (
                var i = 0;
                i < conclusionsByFeatureset_[currentFeatureset].length;
                i++
            ) {
                if (nConclusions[i] > 0) {
                    acceptedValue[i] /= nConclusions[i];

                    var numberParts = String(acceptedValue[i]).split(".");
                    if (numberParts.length > 1) {
                        acceptedValue[i] =
                            numberParts[0] + "." + numberParts[1].slice(0, 2);
                    }
                }
            }

            /* result = "";
            
            var average = overall / nAccepted;
            var highestCardinality = overallHCC;
            
            overallWeighted /= sumWeights;
            var weightedAverage = overallWeighted;
            
            var highestWeighted;
            if (sumWeightedFiltered > 0) {
                highestWeighted = overallWeightedFiltered / sumWeightedFiltered;
            } else {
                highestWeighted = "None";
            }
            
            //h3 h1 h4 h2
            result += String(average) + "," + String(highestCardinality) + "," + String(weightedAverage) + "," + String(highestWeighted); */

            for (var i = 0; i < accrual.length; i++) {
                if (accrual[i] == "Sum") {
                    var numberParts = String(overall).split(".");
                    if (numberParts.length > 1) {
                        overall =
                            numberParts[0] + "." + numberParts[1].slice(0, 9);
                    }

                    overallString += "," + String(overall);
                }

                if (accrual[i] == "Average") {
                    var average = overall / nAccepted;

                    var numberParts = String(average).split(".");
                    if (numberParts.length > 1) {
                        average =
                            numberParts[0] + "." + numberParts[1].slice(0, 9);
                    }

                    overallString += "," + String(average);
                    continue;
                }

                if (accrual[i] == "Highest cardinality") {
                    var numberParts = String(overallHCC).split(".");
                    if (numberParts.length > 1) {
                        overallHCC =
                            numberParts[0] + "." + numberParts[1].slice(0, 9);
                    }

                    overallString += "," + String(overallHCC);
                    continue;
                }

                if (accrual[i] == "Median") {
                    valuesAccepted.sort((a, b) => a - b);
                    let lowMiddle = Math.floor((valuesAccepted.length - 1) / 2);
                    let highMiddle = Math.ceil((valuesAccepted.length - 1) / 2);
                    var median =
                        (valuesAccepted[lowMiddle] +
                            valuesAccepted[highMiddle]) /
                        2;

                    var numberParts = String(median).split(".");
                    if (numberParts.length > 1) {
                        median =
                            numberParts[0] + "." + numberParts[1].slice(0, 9);
                    }

                    overallString += "," + String(median);
                    continue;
                }

                if (accrual[i] == "Weighted average") {
                    overallWeighted /= sumWeights;

                    var numberParts = String(overallWeighted).split(".");
                    if (numberParts.length > 1) {
                        overallWeighted =
                            numberParts[0] + "." + numberParts[1].slice(0, 9);
                    }

                    overallString += "," + String(overallWeighted);
                    continue;
                }

                if (accrual[i] == "Highest conclusion") {
                    var numberParts = String(highestConclustion).split(".");
                    if (numberParts.length > 1) {
                        highestConclustion =
                            numberParts[0] + "." + numberParts[1].slice(0, 9);
                    }

                    overallString += "," + String(highestConclustion);
                    continue;
                }

                if (accrual[i] == "Highest and weighted") {
                    if (sumWeightedFiltered > 0) {
                        overallWeightedFiltered =
                            overallWeightedFiltered / sumWeightedFiltered;

                        var numberParts = String(overallWeightedFiltered).split(
                            "."
                        );
                        if (numberParts.length > 1) {
                            overallWeightedFiltered =
                                numberParts[0] +
                                "." +
                                numberParts[1].slice(0, 9);
                        }
                    } else {
                        overallWeightedFiltered = "None";
                    }

                    overallString += "," + String(overallWeightedFiltered);
                    continue;
                }
            }

            //             if (document.getElementById("accrualAverageExport").checked) {
            //                 overall /= nAccepted;
            //             } else if (document.getElementById("cardinalityAverageExport").checked) {
            //                 overall = overallHCC;
            //             } else if (document.getElementById("medianExport").checked) {
            //                 valuesAccepted.sort((a, b) => a - b);
            //                 let lowMiddle = Math.floor((valuesAccepted.length - 1) / 2);
            //                 let highMiddle = Math.ceil((valuesAccepted.length - 1) / 2);
            //                 overall = (valuesAccepted[lowMiddle] + valuesAccepted[highMiddle]) / 2;
            //             } else if (document.getElementById("weightedExport").checked) {
            //                 overallWeighted /= sumWeights;
            //                 overall = overallWeighted;
            //             } else if (document.getElementById("highestConclusionExport").checked) {
            //                 overall = highestConclustion;
            //             } else if (document.getElementById("absoluteConclusionExport").checked) {
            //                 overall = overallHCC - nNotHigh;
            //             } else if (document.getElementById("highestWeightedExport").checked) {
            //                 if (sumWeightedFiltered > 0) {
            //                     overall = overallWeightedFiltered / sumWeightedFiltered;
            //                 } else {
            //                     overall = "None";
            //                 }
            //             }
            //
            //             var numberParts = String(overall).split(".");
            //             if (numberParts.length > 1) {
            //                 overall = numberParts[0] + "." + numberParts[1].slice(0, 9);
            //             }

            //             if (! document.getElementById("nConclusionsExport").checked) {
            //                 result = String(overall);
            //             } else {
            //                 result = numberConclusions;
            //             }
        }

        thisGraph.circles.exit().remove();

        //console.log("Invisible: " + result);

        //remove first comma;
        overallString = overallString.substring(1);
        return overallString;
    };

    GraphCreator.prototype.exportAllSql = function (
        currentFeatureset,
        semantics,
        timeLimit,
        invisible = false,
        savetoServer = false,
        parser = undefined,
        size = 0
    ) {
        //document.getElementById('progressRow').className = "col-md-12";

        var maxComputation = 1000;

        console.log("Alldata: ", allData_.length);

        var semanticsAndAccrual = String(semantics).split("-");

        // Remove first caracter
        var semanticsVector = String(semanticsAndAccrual[0].slice(1)).split(
            ","
        );
        var accrualVector = String(semanticsAndAccrual[1]).split("*");

        var emptySemantics = "[";

        for (var i = 0; i < semanticsVector.length; i++) {
            if (semanticsVector[i] == "expert") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "grouded") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "eager") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "ideal") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "preferred") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "stable") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "semistable") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "admissible") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "categoriser") {
                emptySemantics += "[],";
            }
        }

        emptySemantics = emptySemantics.slice(0, -1);
        emptySemantics += "]";

        var url = addressCall_ + "deleteComputations";
        var request = new XMLHttpRequest();
        // When semantics has been computed on server
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                // Save computations from 0 until maxComputation records in SQL.
                // If more than maxComputation records then recursive call
                saveComputations(0, maxComputation);
            }
        };

        request.open("GET", url);
        request.send();

        function saveComputations(from, to) {
            var thisGraph = this;
            var allGraphStrings = [];

            var dataString = "";
            for (var i = from; i < Math.min(to, allData_.length); i++) {
                graph.activeAll(
                    allData_[i],
                    currentFeatureset,
                    !invisible,
                    true
                );

                if (
                    String(graph.getStringGraph(true) + semanticsAndAccrual[0])
                        .length > 0
                ) {
                    dataString +=
                        graph.getStringGraph(true) + semanticsAndAccrual[0];
                } else {
                    dataString += emptySemantics;
                }

                if (i < Math.min(to, allData_.length) - 1) {
                    dataString += ";;";
                }
            }

            var dataPost = new FormData();
            dataPost.append("data", dataString);
            var request = window.XMLHttpRequest
                ? new XMLHttpRequest()
                : new activeXObject("Microsoft.XMLHTTP");
            request.open("post", addressCall_ + "saveComputations", true);
            request.send(dataPost);

            // When file has been saved on server
            request.onreadystatechange = function () {
                //Call a function when the state changes.
                if (request.readyState == 4 && request.status == 200) {
                    if (savetoServer) {
                        var percentage = parseFloat(
                            ((to / allData_.length) * 100) / 2
                        ).toFixed(2);
                        console.log(percentage + "%");
                        //document.getElementById('exportControlPercentage').innerHTML = " (" + percentage + "%).";
                    }
                    if (to < allData_.length) {
                        from = to;
                        to += maxComputation;
                        saveComputations(from, to);
                    } else {
                        var url = addressCall_ + "getComputations";
                        var computationsRequest = new XMLHttpRequest();
                        // When semantics has been computed on server
                        computationsRequest.onreadystatechange = function () {
                            if (
                                computationsRequest.readyState == 4 &&
                                computationsRequest.status == 200
                            ) {
                                var data = [];
                                var extensions = JSON.parse(
                                    computationsRequest.responseText
                                );

                                var from = 0;
                                var to = maxComputation;

                                for (var key in extensions) {
                                    // ???
                                    extensions[key].extensions =
                                        "[" + extensions[key].extensions;

                                    getSemanticsIndex(
                                        extensions[key].extensions,
                                        semanticsVector,
                                        accrualVector,
                                        from,
                                        to,
                                        data
                                    );

                                    from = to;
                                    to += maxComputation;

                                    if (savetoServer) {
                                        var percentage = Math.min(
                                            ((to / allData_.length) * 100) / 2 +
                                                50,
                                            100
                                        );
                                        percentage =
                                            parseFloat(percentage).toFixed(2);
                                        console.log(percentage + "%");
                                    }
                                }

                                var row = [];
                                row.push("ID");

                                for (var key in allData_[0]) {
                                    if (key == "GroundTruth") {
                                        row.push("GroundTruth");
                                        break;
                                    }
                                }

                                // Semantics header
                                for (
                                    var i = 0;
                                    i < semanticsVector.length;
                                    i++
                                ) {
                                    if (semanticsVector[i] == "expert") {
                                        //row.push("Expert System");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Expert System - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (
                                        semanticsVector[i] == "grounded"
                                    ) {
                                        //row.push("Grounded Semantics");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Grounded Semantics - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (semanticsVector[i] == "eager") {
                                        //row.push("Eager Semantics");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Eager Semantics - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (semanticsVector[i] == "ideal") {
                                        //row.push("Ideal Semantics");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Ideal Semantics - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (
                                        semanticsVector[i] == "preferred"
                                    ) {
                                        //row.push("Preferred Semantics");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            //console.log("Preferred Semantics - " + accrualVector[j]);
                                            row.push(
                                                "Preferred Semantics - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (semanticsVector[i] == "stable") {
                                        //row.push("Stable Semantics");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Stable Semantics - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (
                                        semanticsVector[i] == "semistable"
                                    ) {
                                        //row.push("Semi-stable Semantics");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Semi-stable Semantics - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (
                                        semanticsVector[i] == "admissible"
                                    ) {
                                        //row.push("Admissible Semantics");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Admissible Semantics - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    } else if (
                                        semanticsVector[i] == "categoriser"
                                    ) {
                                        //row.push("Categoriser");
                                        for (
                                            var j = 0;
                                            j < accrualVector.length;
                                            j++
                                        ) {
                                            row.push(
                                                "Categoriser - " +
                                                    accrualVector[j]
                                            );
                                        }
                                    }
                                }

                                var url = addressCall_ + "deleteComputations";
                                var request = new XMLHttpRequest();
                                // When semantics has been computed on server
                                request.onreadystatechange = function () {
                                    if (
                                        request.readyState == 4 &&
                                        request.status == 200
                                    ) {
                                        // Header of the csv in the first row
                                        console.log(data);

                                        if (
                                            !savetoServer ||
                                            (savetoServer &&
                                                size <= Papa.LocalChunkSize)
                                        ) {
                                            data.unshift(row);
                                        }

                                        // Pass name of the csv to be saved at the
                                        // beginning of the data
                                        var csvContent = ""; //file_.name + "_" + String(size) + "*";//"data:text/csv;charset=utf-8,";

                                        data.forEach(function (infoArray, i) {
                                            var dataString =
                                                infoArray.join(",");
                                            csvContent +=
                                                i < data.length
                                                    ? dataString + "\n"
                                                    : dataString;
                                        });

                                        var csvData = new Blob([csvContent], {
                                            type: "text/csv",
                                        }); //new way

                                        if (!savetoServer) {
                                            var csvUrl =
                                                URL.createObjectURL(csvData);

                                            var link =
                                                document.createElement("a");
                                            link.setAttribute("href", csvUrl);

                                            var currentGraph =
                                                document.getElementById(
                                                    "featuresetgraph"
                                                ).value;
                                            var select =
                                                    document.getElementById(
                                                        "featureset"
                                                    ),
                                                i = select.selectedIndex;

                                            var currentFeatureset =
                                                select.options[i].text;

                                            link.setAttribute(
                                                "download",
                                                currentFeatureset +
                                                    "_" +
                                                    currentGraph +
                                                    ".csv"
                                            );
                                            document.body.appendChild(link); // Required for FF

                                            link.click();
                                        } else {
                                            var csvUrl =
                                                URL.createObjectURL(csvData);
                                            var link =
                                                document.createElement("a");
                                            link.setAttribute("href", csvUrl);
                                            var aux = size / 1000000;
                                            var zeroFilled = (
                                                "000" + aux
                                            ).substr(-3);

                                            link.setAttribute(
                                                "download",
                                                file_.name +
                                                    "_" +
                                                    String(zeroFilled) +
                                                    "mb.csv"
                                            );
                                            document.body.appendChild(link); // Required for FF

                                            link.click();

                                            if (parser != undefined) {
                                                parser.resume();
                                            }
                                        }

                                        if (!savetoServer) {
                                            console.log(
                                                "File should be ready..."
                                            );
                                        }
                                    }
                                };

                                request.open("GET", url);
                                request.send();
                            }
                        };

                        computationsRequest.open("GET", url);
                        computationsRequest.send();
                    }
                }
            };
        }

        function getSemanticsIndex(
            extensions,
            semanticsVector,
            accrualVector,
            from,
            to,
            data
        ) {
            var enxtensionVector = String(extensions.slice(1)).split(";;");

            // ???
            //enxtensionVector[0] = "[" + enxtensionVector[0];

            for (
                var index = from;
                index < Math.min(to, allData_.length);
                index++
            ) {
                // Fill values that are not semantic indexes
                var row = [];
                //console.log(allData_[index]);
                for (var key in allData_[index]) {
                    if (key.toUpperCase() == "ID" || key == "GroundTruth") {
                        row.push(allData_[index][key]);
                    }
                }

                graph.activeAll(
                    allData_[index],
                    currentFeatureset,
                    !invisible,
                    true
                );

                //console.log("a: ", enxtensionVector[index - from]);
                var jsonExtension = JSON.parse(enxtensionVector[index - from]);

                //console.log(semanticsVector);
                for (var ei = 0; ei < semanticsVector.length; ei++) {
                    // Expert system, grounded, eager or ideal. Only one extension
                    if (
                        jsonExtension[ei]
                            .toString()
                            .search("Maximum execution time") != -1
                    ) {
                        var timeLimit = "";
                        for (var i = 0; i < accrualVector.length; i++) {
                            timeLimit += "Time limit,";
                        }
                        timeLimit.slice(0, -1);

                        row.push(timeLimit);
                        continue;
                    }

                    if (
                        jsonExtension[ei]
                            .toString()
                            .search("Allowed memory size") != -1
                    ) {
                        var memoryLimit = "";
                        for (var i = 0; i < accrualVector.length; i++) {
                            memoryLimit += "Memory limit,";
                        }
                        memoryLimit.slice(0, -1);

                        row.push(memoryLimit);

                        continue;
                    }

                    // Unique extension semantics
                    if (semanticsVector[ei] == "categoriser") {
                        if (!invisible) {
                            row.push(
                                graph.semanticsPerRow(
                                    jsonExtension[ei][0],
                                    true,
                                    false,
                                    true
                                )
                            );
                        } else {
                            row.push(
                                graph.semanticsPerRowInvisible(
                                    jsonExtension[ei][0],
                                    accrualVector,
                                    true
                                )
                            );
                        }
                    } else if (
                        semanticsVector[ei] == "grounded" ||
                        semanticsVector[ei] == "expert" ||
                        semanticsVector[ei] == "eager" ||
                        semanticsVector[ei] == "ideal"
                    ) {
                        if (!invisible) {
                            row.push(
                                graph.semanticsPerRow(jsonExtension[ei], true)
                            );
                        } else {
                            row.push(
                                graph.semanticsPerRowInvisible(
                                    jsonExtension[ei],
                                    accrualVector
                                )
                            );
                        }
                    // Not unique extension semantics
                    } else {
                        var sameSizeExtension = 0;

                        // Final index for each accrual when there are multiple extensions of the same size
                        var finalIndex = [];
                        for (var i = 0; i < accrualVector.length; i++) {
                            finalIndex[i] = 0;
                        }

                        if (jsonExtension[ei].length == 1 && jsonExtension[ei][0].length == 0) {
                            // There is no extension. Push undefined so it won't appear in the csv
                            if (!invisible) {
                                row.push(
                                    graph.semanticsPerRow(
                                        jsonExtension[ei][0],
                                        true
                                    )
                                );
                            } else {
                                row.push(
                                    graph.semanticsPerRowInvisible(
                                        jsonExtension[ei][0],
                                        accrualVector
                                    )
                                );
                            }
                            break;
                        }

                        if (jsonExtension[ei][0] == undefined) {
                            if (!invisible) {
                                row.push(graph.semanticsPerRow("[]", true));
                            } else {
                                row.push(
                                    graph.semanticsPerRowInvisible(
                                        "[]",
                                        accrualVector
                                    )
                                );
                            }
                            break;
                        }

                        var maxSize = jsonExtension[ei][0].length;

                        for (var ej = 1; ej < jsonExtension[ei].length; ej++) {
                            if (jsonExtension[ei][ej].length > maxSize) {
                                maxSize = jsonExtension[ei][ej].length;
                            }
                        }

                        for (var ej = 0; ej < jsonExtension[ei].length; ej++) {
                            if (jsonExtension[ei][ej].length == maxSize) {
                                sameSizeExtension++;
                                if (!invisible) {
                                    // each index is correspondent to an accrual option selected
                                    var indexes = graph
                                        .semanticsPerRow(
                                            jsonExtension[ei][ej],
                                            true
                                        )
                                        .split(",");
                                    for (
                                        var i = 0;
                                        i < accrualVector.length;
                                        i++
                                    ) {
                                        finalIndex[i] += parseFloat(indexes[i]);
                                    }
                                } else {
                                    // each index is correspondent to an accrual option selected
                                    var indexes = graph
                                        .semanticsPerRowInvisible(
                                            jsonExtension[ei][ej],
                                            accrualVector
                                        )
                                        .split(",");
                                    for (
                                        var i = 0;
                                        i < accrualVector.length;
                                        i++
                                    ) {
                                        finalIndex[i] += parseFloat(indexes[i]);
                                    }
                                }
                            }
                        }

                        var finalIndexString = "";
                        for (var i = 0; i < accrualVector.length; i++) {
                            finalIndex[i] /= sameSizeExtension;
                            finalIndexString += finalIndex[i].toString() + ",";
                        }
                        finalIndexString = finalIndexString.slice(0, -1);
                        row.push(finalIndexString.toString());
                    }
                }
                
                //console.log(row);
                data.push(row);
            }

            return data;
        }
    };

    GraphCreator.prototype.overallMatches = function (
        currentFeatureset,
        semantics,
        timeLimit
    ) {
        //document.getElementById('progressRow').className = "col-md-12";

        var thisGraph = this;

        var data = [];
        // Write attributes names in the first line
        var row = [];
        var allGraphStrings = [];

        for (var i = 0; i < allData_.length; i++) {
            graph.activeAll(allData_[i], currentFeatureset, false, false);
            allGraphStrings.push("" + graph.getStringGraph());
        }

        // Remove first caracter
        var semanticsVector = String(semantics.slice(1)).split(",");

        var emptySemantics = "[";

        for (var i = 0; i < semanticsVector.length; i++) {
            if (semanticsVector[i] == "expert") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "grouded") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "eager") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "ideal") {
                emptySemantics += "[],";
            } else if (semanticsVector[i] == "preferred") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "stable") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "semistable") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "admissible") {
                emptySemantics += "[[]],";
            } else if (semanticsVector[i] == "categoriser") {
                emptySemantics += "[],";
            }
        }

        emptySemantics = emptySemantics.slice(0, -1);
        emptySemantics += "]";

        pushExtensionIndexes(
            allGraphStrings,
            0,
            allData_.length,
            data,
            emptySemantics,
            semanticsVector,
            semantics,
            getSemanticsIndex
        );

        function pushExtensionIndexes(
            allGraphStrings,
            index,
            nRows,
            data,
            emptySemantics,
            semanticsVector,
            semantics,
            callback
        ) {
            getExtensionRecursive(
                addressCall_ +
                    "allSemantics/" +
                    allGraphStrings[index] +
                    semantics,
                index,
                nRows,
                data,
                allGraphStrings,
                emptySemantics,
                semanticsVector,
                semantics,
                callback
            );
        }

        function getExtensionRecursive(
            url,
            index,
            nRows,
            data,
            allGraphStrings,
            emptySemantics,
            semanticsVector,
            semantics,
            callback
        ) {
            // How can I use this callback?
            if (url.length > String(addressCall_ + "allSemantics/").length) {
                var request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        return callback(
                            request.responseText,
                            index,
                            nRows,
                            data,
                            allGraphStrings,
                            emptySemantics,
                            semanticsVector,
                            semantics,
                            callback
                        ); // Another callback here
                    }
                };

                request.open("GET", url);
                request.send();
            } else {
                // Expert system, gounded, eager, ideal, preferred, stable, semi-stable, admissible, categoriser
                // return callback("[[],[],[],[],[[]],[[]],[[]],[[]],[]]", index, nRows, data, allGraphStrings, callback); // Another callback here
                // Expert system, gounded, eager, preferred
                return callback(
                    emptySemantics,
                    index,
                    nRows,
                    data,
                    allGraphStrings,
                    emptySemantics,
                    semanticsVector,
                    semantics,
                    callback
                ); // Another callback here
            }
        }

        function getSemanticsIndex(
            extension,
            index,
            nRows,
            data,
            allGraphStrings,
            emptySemantics,
            semanticsVector,
            semantics,
            callback
        ) {
            // Fill values that are not semantic indexes
            var row = [];
            //console.log(allData_[index]);
            for (var key in allData_[index]) {
                if (key == "ID" || key == "GroundTruth") {
                    row.push(allData_[index][key]);
                }
            }

            graph.activeAll(allData_[index], currentFeatureset, false);

            //console.log(extension);

            var jsonExtension = JSON.parse(extension);

            for (var ei = 0; ei < semanticsVector.length; ei++) {
                // Expert system, grounded, eager or ideal. Only one extension
                if (
                    jsonExtension[ei]
                        .toString()
                        .search("Maximum execution time") != -1
                ) {
                    row.push("Time limit");
                    continue;
                }

                if (
                    jsonExtension[ei]
                        .toString()
                        .search("Allowed memory size") != -1
                ) {
                    row.push("Memory limit");
                    continue;
                }

                var select = document.getElementById("accrualVisualization"),
                    i = select.selectedIndex,
                    currentAggregation = select.options[i].text;

                // Unique extension semantics
                if (semanticsVector[ei] == "categoriser") {
                    row.push(
                        graph.semanticsPerRowInvisible(
                            jsonExtension[ei][0],
                            [currentAggregation],
                            true
                        )
                    );
                } else if (
                    semanticsVector[ei] == "grounded" ||
                    semanticsVector[ei] == "expert" ||
                    semanticsVector[ei] == "eager" ||
                    semanticsVector[ei] == "ideal"
                ) {
                    row.push(
                        graph.semanticsPerRowInvisible(jsonExtension[ei], [
                            currentAggregation,
                        ])
                    );
                    // Not unique extension semantics
                } else {
                    var sameSizeExtension = 0;
                    var finalIndex = 0;

                    if (
                        jsonExtension[ei].length == 1 &&
                        jsonExtension[ei][0].length == 0
                    ) {
                        // There is no extension. Push undefined so it won't
                        // appear in the csv
                        row.push(
                            graph.semanticsPerRowInvisible(
                                jsonExtension[ei][0],
                                [currentAggregation]
                            )
                        );
                        break;
                    }

                    if (jsonExtension[ei][0] == undefined) {
                        row.push(
                            graph.semanticsPerRowInvisible("[]", [
                                currentAggregation,
                            ])
                        );
                        break;
                    }

                    var maxSize = jsonExtension[ei][0].length;

                    for (var ej = 1; ej < jsonExtension[ei].length; ej++) {
                        if (jsonExtension[ei][ej].length > maxSize) {
                            maxSize = jsonExtension[ei][ej].length;
                        }
                    }

                    for (var ej = 0; ej < jsonExtension[ei].length; ej++) {
                        if (jsonExtension[ei][ej].length == maxSize) {
                            sameSizeExtension++;
                            finalIndex += parseFloat(
                                graph.semanticsPerRowInvisible(
                                    jsonExtension[ei][ej],
                                    [currentAggregation]
                                )
                            );
                        }
                    }

                    finalIndex /= sameSizeExtension;

                    row.push(finalIndex.toString());
                }
            }

            data.push(row);

            if (index + 1 < nRows) {
                var progress = Math.ceil((index / nRows) * 100);
                //document.getElementById('progressBar').setAttribute("style", "width:" + progress.toString() + "%");
                //document.getElementById('progressLabel').innerHTML = progress.toString() + "% (Complete)";
                //console.log(semantics);

                document.getElementById("overallResults").innerHTML =
                    progress.toString() + "%";

                getExtensionRecursive(
                    addressCall_ +
                        "allSemantics/" +
                        allGraphStrings[index + 1] +
                        semantics,
                    index + 1,
                    nRows,
                    data,
                    allGraphStrings,
                    emptySemantics,
                    semanticsVector,
                    semantics,
                    callback
                );
            } else {
                row = [];

                // Push header in the first position
                for (var key in allData_[0]) {
                    if (key == "ID" || key == "GroundTruth") {
                        row.push(key);
                    }
                }

                // Semantics header
                for (var i = 0; i < semanticsVector.length; i++) {
                    if (semanticsVector[i] == "expert") {
                        row.push("Expert System");
                    } else if (semanticsVector[i] == "grounded") {
                        row.push("Grounded Semantics");
                    } else if (semanticsVector[i] == "eager") {
                        row.push("Eager Semantics");
                    } else if (semanticsVector[i] == "ideal") {
                        row.push("Ideal Semantics");
                    } else if (semanticsVector[i] == "preferred") {
                        row.push("Preferred Semantics");
                    } else if (semanticsVector[i] == "stable") {
                        row.push("Stable Semantics");
                    } else if (semanticsVector[i] == "semistable") {
                        row.push("Semi-stable Semantics");
                    } else if (semanticsVector[i] == "admissible") {
                        row.push("Admissible Semantics");
                    } else if (semanticsVector[i] == "categoriser") {
                        row.push("Categoriser");
                    }
                }

                data.unshift(row);
                var groundTruth;
                var preferred = 0;
                var grounded = 0;
                var categoriser = 0;

                var preferred_i;
                var grounded_i;
                var categoriser_i;

                // Get columns indexes
                for (var i = 0; i < data[0].length; i++) {
                    if (data[0][i] == "GroundTruth") {
                        groundTruth = i;
                    } else if (data[0][i] == "Preferred Semantics") {
                        preferred_i = i;
                    } else if (data[0][i] == "Categoriser") {
                        categoriser_i = i;
                    } else if (data[0][i] == "Grounded Semantics") {
                        grounded_i = i;
                    }
                }

                for (var i = 1; i < data.length; i++) {
                    if (
                        Math.abs(
                            data[i][groundTruth] - data[i][categoriser_i]
                        ) < 0.0001
                    ) {
                        categoriser++;
                    }

                    if (
                        Math.abs(data[i][groundTruth] - data[i][preferred_i]) <
                        0.0001
                    ) {
                        preferred++;
                    }

                    if (
                        Math.abs(data[i][groundTruth] - data[i][grounded_i]) <
                        0.0001
                    ) {
                        grounded++;
                    }
                }

                categoriser = (100 * categoriser) / (data.length - 1);
                preferred = (100 * preferred) / (data.length - 1);
                grounded = (100 * grounded) / (data.length - 1);

                var numberParts = String(categoriser).split(".");
                if (numberParts.length > 1) {
                    categoriser =
                        numberParts[0] + "." + numberParts[1].slice(0, 2);
                }

                var numberParts = String(grounded).split(".");
                if (numberParts.length > 1) {
                    grounded =
                        numberParts[0] + "." + numberParts[1].slice(0, 2);
                }

                var numberParts = String(preferred).split(".");
                if (numberParts.length > 1) {
                    preferred =
                        numberParts[0] + "." + numberParts[1].slice(0, 2);
                }

                document.getElementById("overallResults").innerHTML =
                    "Preferred: " +
                    preferred.toString() +
                    "%<br>" +
                    "Grounded: " +
                    grounded.toString() +
                    "%<br>" +
                    "Categoriser: " +
                    categoriser.toString() +
                    "%";
            }
        }
    };

    // call to propagate changes to graph
    GraphCreator.prototype.updateGraph = function () {
        var thisGraph = this,
            consts = thisGraph.consts,
            state = thisGraph.state;

        thisGraph.paths = thisGraph.paths.data(thisGraph.edges, function (d) {
            return String(d.source.id) + "+" + String(d.target.id);
        });

        var paths = thisGraph.paths;

        // update existing paths
        paths
            .style("marker-end", function (d) {
                if (d.type == "undermine") {
                    return "url(#end-arrow-blue)";
                } else if (d.type == "undercut") {
                    return "url(#end-arrow-red)";
                } else if (d.type == "rebuttal") {
                    return "url(#end-arrow-green)";
                } else {
                    return "url(#end-arrow)";
                }
            })
            .classed(consts.selectedClass, function (d) {
                return d === state.selectedEdge;
            })
            .attr("d", function (d) {
                var operator1 = 1;
                var operator2 = -1;
                if (d.target.x < d.source.x) {
                    operator1 = -1;
                    operator2 = 1;
                }

                // Find the points in the border of the circle given their origin
                var angle = Math.atan(
                    (d.target.y - d.source.y) / (d.target.x - d.source.x)
                );
                var x_source =
                    d.source.x +
                    operator1 * consts.nodeRadius * Math.cos(angle);
                var y_source =
                    d.source.y +
                    operator1 * consts.nodeRadius * Math.sin(angle);

                var x_target =
                    d.target.x +
                    operator2 * consts.nodeRadius * Math.cos(angle);
                var y_target =
                    d.target.y +
                    operator2 * consts.nodeRadius * Math.sin(angle);

                return ("M" + x_source + "," + y_source + "L" + x_target + "," + y_target);
            });

        // add new paths
        paths
            .enter()
            .append("path")
            .style("marker-end", function (d) {
                if (d.type == "undermine") {
                    return "url(#end-arrow-blue)";
                } else if (d.type == "undercut") {
                    return "url(#end-arrow-red)";
                } else if (d.type == "rebuttal") {
                    return "url(#end-arrow-green)";
                } else {
                    return "url(#end-arrow)";
                }
            })
            .on("mouseover", function (d) {
                if (thisGraph.state.selectedEdge != d) {
                    d3.select(this).style(
                        "marker-end",
                        "url(#end-arrow-hover)"
                    );
                } else {
                    d3.select(this).style(
                        "marker-end",
                        "url(#end-arrow-selected)"
                    );
                }
            })
            .on("mouseout", function (d) {
                if (thisGraph.state.selectedEdge == d) {
                    d3.select(this).style(
                        "marker-end",
                        "url(#end-arrow-selected)"
                    );
                } else if (d.type == "undermine") {
                    d3.select(this).style("marker-end", "url(#end-arrow-blue)");
                } else if (d.type == "undercut") {
                    d3.select(this).style("marker-end", "url(#end-arrow-red)");
                } else if (d.type == "rebuttal") {
                    d3.select(this).style(
                        "marker-end",
                        "url(#end-arrow-green)"
                    );
                } else {
                    d3.select(this).style("marker-end", "url(#end-arrow)");
                }
            })
            .classed("linkBlue", function (d) {
                return d.type == "undermine";
            })
            .classed("linkRed", function (d) {
                return d.type == "undercut";
            })
            .classed("linkGreen", function (d) {
                return d.type == "rebuttal";
            })
            .classed("link", function (d) {
                return d.type == "none";
            })
            .attr("d", function (d) {
                var operator1 = 1;
                var operator2 = -1;
                if (d.target.x < d.source.x) {
                    operator1 = -1;
                    operator2 = 1;
                }

                // Find the points in the border of the circle given their origin
                var angle = Math.atan(
                    (d.target.y - d.source.y) / (d.target.x - d.source.x)
                );
                var x_source =
                    d.source.x +
                    operator1 * consts.nodeRadius * Math.cos(angle);
                var y_source =
                    d.source.y +
                    operator1 * consts.nodeRadius * Math.sin(angle);

                var x_target =
                    d.target.x +
                    operator2 * consts.nodeRadius * Math.cos(angle);
                var y_target =
                    d.target.y +
                    operator2 * consts.nodeRadius * Math.sin(angle);

                return ("M" + x_source + "," + y_source + "L" + x_target + "," + y_target);
            });
        /*.on("mousedown", function(d){
            thisGraph.pathMouseDown.call(thisGraph, d3.select(this), d);
        })
        .on("mouseup", function(d){
            state.mouseDownLink = null;
            if (thisGraph.state.selectedEdge == d) {
                d3.select(this).style('marker-end', "url(#end-arrow-selected)");
            } else {
                d3.select(this).style('marker-end', "url(#end-arrow-hover)");
            }
        });*/

        // Split rebuttals and change opacity if not in budget
        edges.forEach(function (val, i) {
            var originalPath = thisGraph.paths.filter(function (d) {
                return (
                    d.source.id == val.source.id && d.target.id == val.target.id
                );
            });

            if (! val.in_budget) {
                originalPath.style("opacity", 0.15);
            } else {
                originalPath.style("opacity", 1);
            }

            var reversePath = thisGraph.paths.filter(function (d) {
                return (
                    d.source.id == val.target.id && d.target.id == val.source.id
                );
            });

            if (reversePath[0].length > 0) {
                // Translate path to distantiate from other attack in the rebuttal
                originalPath
                    .attr("transform", "translate(0, 8)")
                    .style("marker-end", function (d) {
                        if (d.type == "undermine") {
                            return "url(#end-arrow-blue)";
                        } else if (d.type == "undercut") {
                            return "url(#end-arrow-red)";
                        } else if (d.type == "rebuttal") {
                            return "url(#end-arrow-green)";
                        } else {
                            return "url(#end-arrow)";
                        }
                    });

                // Translate path to distantiate from other attack in the rebuttal
                reversePath
                    .attr("transform", "translate(0, -8)")
                    .style("marker-end", function (d) {
                        if (d.type == "undermine") {
                            return "url(#end-arrow-blue)";
                        } else if (d.type == "undercut") {
                            return "url(#end-arrow-red)";
                        } else if (d.type == "rebuttal") {
                            return "url(#end-arrow-green)";
                        } else {
                            return "url(#end-arrow)";
                        }
                    });
            }
        });

        // remove old links
        paths.exit().remove();

        // update existing nodes
        thisGraph.circles = thisGraph.circles.data(
            thisGraph.nodes,
            function (d) {
                return d.id;
            }
        );
        thisGraph.circles
            .attr("transform", function (d) {
                return "translate(" + d.x + "," + d.y + ")";
            })
            .style("stroke-dasharray", function (d) {
                var premiseAndConclusion = String(d.tooltip).split(" -> ");
                if (premiseAndConclusion.length != 2) {
                    return "10 10";
                } else {
                    return "0 0";
                }
            });

        // add new nodes
        var newGs = thisGraph.circles.enter().append("g");

        var tooltip = d3
            .select("body")
            .append("div")
            .style("position", "absolute")
            .style("z-index", "10")
            .style("background-color", "white")
            .style("border", "1px solid black")
            .style("border-radius", "4px")
            .style("opacity", "0.85")
            .style("visibility", "hidden")
            .style("padding", "4px");

        newGs
            .classed(consts.circleGClass, true)
            .attr("transform", function (d) {
                return "translate(" + d.x + "," + d.y + ")";
            })
            .style("stroke-dasharray", function (d) {
                var premiseAndConclusion = String(d.tooltip).split(" -> ");
                if (premiseAndConclusion.length != 2) {
                    return "10 10";
                } else {
                    return "0 0";
                }
            })
            .on("mouseover", function (d) {
                if (state.shiftNodeDrag) {
                    d3.select(this).classed(consts.connectClass, true);
                }

                var nSourceActive = 0;
                var sourceActive = "";
                var nTargetActive = 0;
                var targetActive = "";
                var nSourceOriginal = 0;
                var sourceOriginal = "";
                var nTargetOriginal = 0;
                var targetOriginal = "";
                edges.forEach(function (val, i) {
                    if (val.target.id == d.id) {
                        var sourceCircle = thisGraph.circles.filter(function (
                            sd
                        ) {
                            return sd.id === val.source.id;
                        });

                        nSourceOriginal++;

                        sourceCircle.each(function (sd) {
                            sourceOriginal += sd.title + ", ";

                            if (sd.activated && graph.isAttackValid(sd, d, false) && val.in_budget) {
                                nTargetActive++;
                                targetActive += sd.title + ", ";
                            }
                        });
                    }

                    if (val.source.id == d.id) {
                        var targetCircle = thisGraph.circles.filter(function (td) {
                            return td.id === val.target.id;
                        });

                        nTargetOriginal++;

                        targetCircle.each(function (td) {
                            targetOriginal += td.title + ", ";

                            if (td.activated && graph.isAttackValid(d, td, false) && val.in_budget) {
                                nSourceActive++;
                                sourceActive += td.title + ", ";
                            }
                        });
                    }
                });

                // Remove commas
                if (sourceOriginal.length > 0) {
                    sourceOriginal = sourceOriginal.slice(0, -2);
                }

                if (targetActive.length > 0) {
                    targetActive.slice(0, -2);
                }

                if (targetOriginal.length > 0) {
                    targetOriginal = targetOriginal.slice(0, -2);
                }

                if (sourceActive.length > 0) {
                    sourceActive.slice(0, -2);
                }

                var weight = "None"
                if (!isNaN(d.weight)) {
                    weight = d.weight;
                }

                tooltip.style("visibility", "visible");

                tooltip.selectAll("text").remove();
                tooltip.append("text");

                tooltip
                    .selectAll("text")
                    .html(
                        "<b>" +
                            d.title +
                            "</b>: " +
                            d.tooltip +
                            "<br/>" + 
                            "<b>Weight</b>: " + weight + "<br/><br/>" +
                            "<b>Attacks info</b><br><i># Source attack activated:</i> " +
                            String(nSourceActive) +
                            ". " +
                            sourceActive +
                            "<br/><i># Target attack activated:</i> " +
                            String(nTargetActive) +
                            ". " +
                            targetActive +
                            "<br/><br/>" +
                            "<i>All source attacks (" + String(nSourceOriginal) + "):</i> " +
                            sourceOriginal +
                            "<br/><i>All target attacks(" + String(nTargetOriginal) + "):</i> " +
                            targetOriginal +
                            "<br/>" +
                            thisGraph.getLevelsDescription(d.tooltip) +
                            "<br/>"
                    );

                tooltip.style("visibility", "visible");
            })
            .on("mousemove", function (d) {
                return tooltip
                    .style("top", event.pageY - 10 + "px")
                    .style("left", event.pageX + 10 + "px");
            })
            .on("mouseout", function (d) {
                d3.select(this).classed(consts.connectClass, false);
                return tooltip.style("visibility", "hidden");
            })
            .on("mousedown", function (d) {
                thisGraph.circleMouseDown.call(thisGraph, d3.select(this), d);
            })
            .on("mouseup", function (d) {
                thisGraph.circleMouseUp.call(thisGraph, d3.select(this), d);
            })
            .call(thisGraph.drag);

        newGs.append("circle").attr("r", String(consts.nodeRadius));

        newGs.each(function (d) {
            //thisGraph.insertTitleLinebreaks(d3.select(this), d.title);
            thisGraph.styleTitle(d3.select(this), d.title);
        });

        // remove old nodes
        thisGraph.circles.exit().remove();
    };

    GraphCreator.prototype.get_from_to = function(level, attribute) {
        // Given a level-attribute pair, find the corresponding numeric range
        // of the level for the current feature set selected.
        var select = document.getElementById('featureset'),
        i = select.selectedIndex;

        if (i == -1) {
            return;
        }

        currentFeatureset = select.options[i].text;

        // Run through all attributes and their respective level in order
        // to find the current premise's range
        for (var attr = 0; attr < attributesByFeatureset_[currentFeatureset].length; attr++) {

            var correctAttributeLevel = attributesByFeatureset_[currentFeatureset][attr].attribute == attribute &&
                                        attributesByFeatureset_[currentFeatureset][attr].a_level == level;

            if (correctAttributeLevel) {

                var from = attributesByFeatureset_[currentFeatureset][attr].a_from;
                var to = attributesByFeatureset_[currentFeatureset][attr].a_to;

                return [from, to];
            }
        }
    }


    GraphCreator.prototype.getLevelsDescription = function(argument) {

        var result = "<br><b>Attributes used</b><br>";
        
        // Remove conclusion
        var premiseAndConclusion = String(argument).split(" -> ");
        argument = premiseAndConclusion[0];

        // Run through all premises of the node's argument
        var premises = argument.split(" AND ");

        // Dictionary to be built with attributes as keys, and numeric levels as values
        var attribute_values = {};

        // Each premise can have zero or many OR clauses. For example "('low fatigue' OR 'high fatigue')"
        for(let premise of premises){

            // Remove parathesis and quotes from the premise
            premise = premise.replace("(", "").replace(")", "").replace(/"/g, "");

            // Check if premise has OR operator
            if (premise.includes(" OR ")) {

                // Break the premise in all the level-attribute pairs
                var allLevelandAttributes = premise.split(" OR ");

                // Get the attribute of the premise (it should be the same attribute for all level-attribute pairs)
                var attribute = allLevelandAttributes[1].split(" ")[1];

                // Concatenate all the levels (as ranges) of this attribute to the tooltip
                for (let levelAndAttribute of allLevelandAttributes) {

                    // Split level-attribute pairs
                    levelAndAttribute = levelAndAttribute.split(" ")
                    
                    if (! attribute_values[levelAndAttribute[1]]) {
                        attribute_values[levelAndAttribute[1]] = []
                    }

                    // Get the min-max of the level of the attribtue
                    var from_to = graph.get_from_to(levelAndAttribute[0], levelAndAttribute[1]);
                    
                    // From and to are the same. Categorical level
                    if (Math.abs(from_to[0] - from_to[1]) < 0.00001) {
                        attribute_values[levelAndAttribute[1]].push(from_to[0])
                    } else {
                        attribute_values[levelAndAttribute[1]].push(from_to)
                    }
                }

            } else {  // There is no " OR " operator. It is a single level-attribute pair
                var level = premise.split(" ")[0];
                var attribute = premise.split(" ")[1];
                var from_to = graph.get_from_to(level, attribute);

                if (! attribute_values[attribute]) {
                    attribute_values[attribute] = []
                }

                if (Math.abs(from_to[0] - from_to[1]) < 0.00001) {
                    attribute_values[attribute].push(from_to[0]);
                } else {
                    attribute_values[attribute].push(from_to);
                }
            }
        }

        // Go over the dictionary and add it to the correspoding resulting html
        Object.keys(attribute_values).forEach(key => {
            result += "<i>" + key + "</i>: ";

            for (let level of attribute_values[key]) {
                if (Array.isArray(level) ) {
                    result += "(" + level[0] + ", " + level[1] + "), ";
                } else {
                    result += level + ", ";
                }
            }

            // Remove last ", "
            result = result.slice(0, -2);
            result += "<br>";

        });

        return result;
    }

    GraphCreator.prototype.zoomed = function () {
        this.state.justScaleTransGraph = true;
        d3.select("." + this.consts.graphClass).attr(
            "transform",
            "translate(" +
                d3.event.translate +
                ") scale(" +
                d3.event.scale +
                ")"
        );

        this.zoomScale = d3.event.scale;
    };

    GraphCreator.prototype.setFontSize = function (gEl, title, fontSize) {
        gEl.select("tspan").attr("font-size", fontSize + "px");
    };

    GraphCreator.prototype.updateWindow = function (svg) {
        var docEl = document.documentElement,
            bodyEl = document.getElementById("left-side");
        var x = bodyEl.offsetWidth * 0.95; //Size of div column
        var y = window.innerHeight || docEl.clientHeight || bodyEl.clientHeight;
        y = y * 0.73;
        svg.attr("width", x).attr("height", y);
    };

    GraphCreator.prototype.updateInconsistencyBudget = function(new_budget) {
        console.log("Here!");
        var current_budget = 0;
        thisGraph.edges.some(function(val) {
            val.in_budget = false;
            // Keep adding while the budget allows
            if (current_budget + val.inconsistency <= new_budget) {
                current_budget += val.inconsistency;
                val.in_budget = true;
            }
        });

        // Update graph with new attacks in budget
        thisGraph.updateGraph();

        // Trigger a new calculation if the row in the data is selected
        var element = document.querySelector('#featuresetTable tbody tr');
        if (element.classList.contains('selected')) {
            var event = new Event("change");
            document.getElementById("row_input").dispatchEvent(event);
        }
    }

    /**** MAIN ****/

    var docEl = document.documentElement,
        bodyEl = document.getElementById("left-side");

    var width = bodyEl.offsetWidth * 0.95, //Size of div column
        height =
            window.innerHeight || docEl.clientHeight || bodyEl.clientHeight;

    height = height * 0.73;

    // initial node data
    var nodes = [],
        edges = [],
        indexNode = [],
        id = 0,
        jsonEdges = "",
        semantic = "",
        viewX = 0,
        viewY = 0,
        viewWidth = width,
        viewHeight = height;

    var select = document.getElementById("featureset"),
        i = select.selectedIndex;

    var currentFeatureset = null;
    if (i != -1) {
        currentFeatureset = select.options[i].text;
    }

    var select = document.getElementById("featuresetgraph"),
        i = select.selectedIndex;

    var mostLeftNode, mostRightNode, mostUpNode, mostBottomNode;

    if (i != -1 && currentFeatureset != null) {
        var currentGraph = select.options[i].text;

        // Get semantic and edges
        for (var i = 0; i < graphs_.length; i++) {
            if (
                graphs_[i].featureset == currentFeatureset &&
                graphs_[i].name == currentGraph
            ) {
                jsonEdges = JSON.parse(graphs_[i].edges);
                semantic = graphs_[i].semantic;
            }
        }

        for (var i = 0; i < args_.length; i++) {
            if (
                args_[i].featureset == currentFeatureset &&
                args_[i].graph == currentGraph
            ) {
                var tooltip;
                if (args_[i].conclusion && args_[i].conclusion != "NULL") {
                    tooltip = args_[i].argument + " -> " + args_[i].conclusion;
                    //console.log(args_[i].conclusion);
                } else {
                    tooltip = args_[i].argument;
                }

                nodes.push({
                    id: id,
                    title: args_[i].label,
                    x: parseFloat(args_[i].x),
                    y: parseFloat(args_[i].y),
                    tooltip: tooltip,
                    weight: parseFloat(args_[i].weight),
                });

                indexNode.push(args_[i].label);

                if (id == 0) {
                    mostLeftNode = parseFloat(args_[i].x);
                    mostRightNode = parseFloat(args_[i].x);
                    mostUpNode = parseFloat(args_[i].y);
                    mostBottomNode = parseFloat(args_[i].y);
                } else {
                    if (parseFloat(args_[i].x) < mostLeftNode) {
                        mostLeftNode = parseFloat(args_[i].x);
                    }

                    if (parseFloat(args_[i].x) > mostRightNode) {
                        mostRightNode = parseFloat(args_[i].x);
                    }

                    if (parseFloat(args_[i].y) > mostUpNode) {
                        mostUpNode = parseFloat(args_[i].y);
                    }

                    if (parseFloat(args_[i].y) < mostBottomNode) {
                        mostBottomNode = parseFloat(args_[i].y);
                    }
                }

                id++;
            }
        }

        viewX = mostLeftNode - 100;
        viewY = mostBottomNode - 100;

        viewWidth = mostRightNode - mostLeftNode + 400;
        viewHeight = mostUpNode - mostBottomNode + 400;

        // Calculate total inconsistency (if there are node weigths)
        // The inconsistency is the sum of weights of an argument attacking the other.
        // The total inconsistency is the sum of all inconsistency between all the attacks
        var total_inconsistency = 0; 

        for (var i = 0; i < jsonEdges.length; i++) {
            //FIXME: find a more intelligent way of getting the indexes by the labels
            var indexSource = 0,
                indexTarget = 0;

            var inconsistency = NaN;

            for (var j = 0; j < indexNode.length; j++) {
                if (indexNode[j] == jsonEdges[i].source) {
                    indexSource = j;
                    break;
                }
            }

            for (var j = 0; j < indexNode.length; j++) {
                if (indexNode[j] == jsonEdges[i].target) {
                    indexTarget = j;
                    break;
                }
            }

            if (!isNaN(nodes[indexSource].weight) && !isNaN(nodes[indexTarget].weight)) { // Both have weigths
                inconsistency = nodes[indexSource].weight + nodes[indexTarget].weight;                
            } else if (!isNaN(nodes[indexSource].weight)) { // Only source has a weigth
                inconsistency = nodes[indexSource].weight;
            } else if (!isNaN(nodes[indexTarget].weight)) { // Only index has a weigth
                inconsistency = nodes[indexTarget].weight;
            }

            if (!isNaN(inconsistency)) {
                total_inconsistency += inconsistency
            }

            if (jsonEdges[i].type != undefined) {
                edges.push({
                    source: nodes[indexSource],
                    target: nodes[indexTarget],
                    type: jsonEdges[i].type,
                    inconsistency: inconsistency,
                    in_budget: true,
                });
            } else {
                edges.push({
                    source: nodes[indexSource],
                    target: nodes[indexTarget],
                    type: "none",
                    inconsistency: inconsistency,
                    in_budget: true,
                });
            }
        }

        // Sort edges by inconsistency in ascending order.
        // If tied, untie by the weigth of the soruce.
        edges.sort(function(a, b) {
            if (Math.abs(a.inconsistency - b.inconsistency) < 0.00001) {
                // Compare source_weight if inconsistency is the same
                // b - a means descending order (a - b would be ascending)
                return b.source.weight - a.source.weight; 
            } else {
                // Compare inconsistency first
                // b - a means descending order (a - b would be ascending)
                return b.inconsistency - a.inconsistency; 
            }
        });

        var rangeInput = document.getElementById("inconsistencyRange");
        rangeInput.setAttribute("max", total_inconsistency);
        rangeInput.value = total_inconsistency;
        document.getElementById("total-inconsistency").innerHTML = String(total_inconsistency);
        document.getElementById("current-inconsistency").innerHTML = String(total_inconsistency);

        if (total_inconsistency == 0) {
            document.getElementById("inconsistency-div").style.display = "none";   
        } else {
            document.getElementById("inconsistency-div").style.display = "inline-block";            
        }
    }

    /** MAIN SVG **/
    var svg = d3
        .select("#left-side")
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .attr("id", "G")
        .attr(
            "viewBox",
            viewX + " " + viewY + " " + viewWidth + " " + viewHeight
        );
    var graph = new GraphCreator(svg, nodes, edges);
    graph.setIdCt(id);
    graph.updateGraph();

    var fontSize;
    var currentGraph = document.getElementById("featuresetgraph").value;
    var select = document.getElementById("featureset"),
        i = select.selectedIndex;

    if (i == -1) {
        return graph;
    }

    var currentFeatureset = select.options[i].text;
    for (var i = 0; i < graphs_.length; i++) {
        if (
            graphs_[i].featureset == currentFeatureset &&
            graphs_[i].name == currentGraph
        ) {
            var thisGraph = graph;
            thisGraph.circles.each(function (d) {
                thisGraph.setFontSize(
                    d3.select(this),
                    d.title,
                    graphs_[i].font_size
                );
            });
            break;
        }
    }

    return graph;
}

var graph = create(window.d3, window.saveAs, window.Blob);

d3.select("#featuresetgraph").on("change", function () {
    d3.event.preventDefault();
    d3.select("#G").remove();
    graph = create(window.d3, window.saveAs, window.Blob);
    document.getElementById("overallResults").innerHTML = "";
    document.querySelectorAll(".check1")[0].checked = false;
});

d3.select("#export").on("click", function () {
    d3.event.preventDefault();
    $("#modalExport").modal("show");
});

d3.select("#inconsistencyRange").on("change", function () {
    d3.event.preventDefault();
    var new_budget = document.getElementById("inconsistencyRange").value
    document.getElementById("current-inconsistency").innerHTML = new_budget;
    graph.updateInconsistencyBudget(new_budget);
});

d3.select("#exportFile").on("click", function () {
    d3.event.preventDefault();

    if (file_.size <= Papa.LocalChunkSize) {
        var checkboxes = document.getElementsByName("semanticsExport");
        var accrualCheckboxes = document.getElementsByName("accrualExport");
        var timeLimit = "1"; //document.getElementById("timeLimit").value;
        var semantics = ";";

        var accruals = "-";
        // loop over all accruals
        for (var i = 0; i < accrualCheckboxes.length; i++) {
            // And stick the checked ones onto an array...
            if (accrualCheckboxes[i].checked) {
                accruals += accrualCheckboxes[i].value + "*";
            }
        }

        accruals = accruals.slice(0, -1);

        // loop over them all
        for (var i = 0; i < checkboxes.length; i++) {
            // And stick the checked ones onto an array...
            if (checkboxes[i].checked) {
                if (checkboxes[i].value == "Expert System") {
                    semantics += "expert,";
                } else if (checkboxes[i].value == "Preferred") {
                    semantics += "preferred,";
                } else if (checkboxes[i].value == "Grounded") {
                    semantics += "grounded,";
                } else if (checkboxes[i].value == "Eager") {
                    semantics += "eager,";
                } else if (checkboxes[i].value == "Ideal") {
                    semantics += "ideal,";
                } else if (checkboxes[i].value == "Stable") {
                    semantics += "stable,";
                } else if (checkboxes[i].value == "Semi-stable") {
                    semantics += "semistable,";
                } else if (checkboxes[i].value == "Admissible") {
                    semantics += "admissible,";
                } else if (checkboxes[i].value == "Categoriser") {
                    semantics += "categoriser,";
                }
            }
        }

        var select = document.getElementById("featureset"),
            i = select.selectedIndex,
            currentFeatureset = select.options[i].text;

        // Remove last coma
        semantics = semantics.slice(0, -1);
        semantics += accruals;

        //final semantics example
        // ;expert,ideal,preferred-sum*average*median

        $("#modalExport").modal("hide");
        //graph.exportAll(currentFeatureset, semantics, timeLimit, true);
        //graph.exportAllQuick(currentFeatureset, semantics, timeLimit, true);
        graph.exportAllSql(currentFeatureset, semantics, timeLimit, true);
    } else {
        $("#modalExport").modal("hide");

        var firstRow;
        var size = 0;

        console.log(file_);

        var url = absoluteCall_ + "deleteCsv.php";
        var request = new XMLHttpRequest();
        request.open("GET", url);
        request.send();

        Papa.parse(file_, {
            header: true,
            skipEmptyLines: true,
            chunk: function (results, parser) {
                //console.log("Row data:", results.data);
                //console.log("Row errors:", results.errors);

                // Only first 25mb are imported for table
                allData_ = results.data;
                if (size <= Papa.LocalChunkSize) {
                    firstRow = allData_[0];
                } else {
                    allData_.unshift(firstRow);
                }

                parser.pause();

                size += Papa.LocalChunkSize;

                console.log(
                    size / 1000000 + "mb of " + file_.size / 1000000 + "mb."
                );

                var exportControl = document.getElementById("exportControl");
                exportControl.style.visibility = "visible";
                document.getElementById("exportControl").innerHTML =
                    "<br>" +
                    size / 1000000 +
                    "mb of " +
                    file_.size / 1000000 +
                    "mb being computed.";

                /*var exportControlPercentage = document.getElementById("exportControlPercentage");
                exportControlPercentage.style.visibility = 'visible';
                document.getElementById('exportControlPercentage').innerHTML = " (0%)";*/

                var select = document.getElementById("featureset"),
                    i = select.selectedIndex,
                    currentFeatureset = select.options[i].text;

                var ignoredColumns = "";
                var removeColumns = [];
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

                    for (var iAttr = 0; iAttr < attributes_.length; iAttr++) {
                        // Check whether header is a weight for an existing attribute
                        var column = key;
                        if (key.indexOf("Weight_") !== -1) {
                            column = key.slice(7);
                        }

                        // Check if header has a relative attribute in the featureset
                        var inFeatureset =
                            attributes_[iAttr].featureset ==
                                currentFeatureset &&
                            (attributes_[iAttr].attribute === column ||
                                key.toUpperCase() === "ID" ||
                                key === "GroundTruth");

                        if (inFeatureset) {
                            // Create json columns according to the required fields of the table plugin
                            columns.push({
                                sTitle: key,
                                mData: key,
                                aTargets: [nColumns],
                            });
                            nColumns++;
                            // Save which columns of the csv file will be imported
                            //iColumns.push(iHeader);
                            // Break since current colum header have already been saved
                            break;
                        }

                        if (iAttr == attributes_.length - 1) {
                            // If all attributes were checked and header didn't match any of them it will not be imported.
                            // Print list of not imported column
                            // TODO: is this really necessary? Maybe import everything and using only valid data
                            ignoredColumns += key + "\n";
                            removeColumns.push(key);
                        }
                    }
                }

                // Remove columns that do not have an associated feature
                for (var i = 0; i < removeColumns.length; i++) {
                    for (var row = 0; row < allData_.length; row++) {
                        delete allData_[row][removeColumns[i]];
                    }
                }

                var checkboxes = document.getElementsByName("semanticsExport");
                var accrualCheckboxes =
                    document.getElementsByName("accrualExport");
                var timeLimit = "1"; //document.getElementById("timeLimit").value;
                var semantics = ";";

                var accruals = "-";
                // loop over all accruals
                for (var i = 0; i < accrualCheckboxes.length; i++) {
                    // And stick the checked ones onto an array...
                    if (accrualCheckboxes[i].checked) {
                        accruals += accrualCheckboxes[i].value + "*";
                    }
                }

                accruals = accruals.slice(0, -1);

                // loop over them all
                for (var i = 0; i < checkboxes.length; i++) {
                    // And stick the checked ones onto an array...
                    if (checkboxes[i].checked) {
                        if (checkboxes[i].value == "Expert System") {
                            semantics += "expert,";
                        } else if (checkboxes[i].value == "Preferred") {
                            semantics += "preferred,";
                        } else if (checkboxes[i].value == "Grounded") {
                            semantics += "grounded,";
                        } else if (checkboxes[i].value == "Eager") {
                            semantics += "eager,";
                        } else if (checkboxes[i].value == "Ideal") {
                            semantics += "ideal,";
                        } else if (checkboxes[i].value == "Stable") {
                            semantics += "stable,";
                        } else if (checkboxes[i].value == "Semi-stable") {
                            semantics += "semistable,";
                        } else if (checkboxes[i].value == "Admissible") {
                            semantics += "admissible,";
                        } else if (checkboxes[i].value == "Categoriser") {
                            semantics += "categoriser,";
                        }
                    }
                }

                var select = document.getElementById("featureset"),
                    i = select.selectedIndex,
                    currentFeatureset = select.options[i].text;

                // Remove last coma
                semantics = semantics.slice(0, -1);
                semantics += accruals;

                graph.exportAllSql(
                    currentFeatureset,
                    semantics,
                    timeLimit,
                    true,
                    true,
                    parser,
                    size
                );

                //parser.resume();
                if (size >= file_.size) {
                    /*var d = new FormData();
                    d.append("data" , file_.name + "_result.csv");
                    var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new activeXObject("Microsoft.XMLHTTP");

                    xhr.onreadystatechange = function() {
                        var link = document.createElement("a");
                        link.setAttribute("href", "http://localhost/Expert/" + file_.name + "_result.csv");
                        link.setAttribute("download", "my_data.csv");
                        document.body.appendChild(link); // Required for FF
                        $('#modalExport').modal('hide');
                        link.click();
                    };

                    xhr.open('post', 'http://localhost/Expert/joinCsv.php', true );
                    xhr.send(d);*/
                    console.log("Files on server...");

                    /*document.getElementById('exportControl').innerHTML = "Preparing files to download..."
                    document.getElementById('exportControlPercentage').innerHTML = "";

                    setTimeout(function(){ 
                        document.getElementById("downloadExports").disabled = false;
                        var extensionDiv = document.getElementById("downloadExports");
                        extensionDiv.style.visibility = 'visible';
                        document.getElementById('downloadExports').innerHTML = "<a href=\"/Expert/joinCsv.php\" class=\"btn btn-primary\" role=\"button\"><span class=\"glyphicon glyphicon-download-alt\"></span>&nbsp Download</span></a>"
                        document.getElementById('exportControl').innerHTML = "Click on download button..."

                    }, 30000);*/
                }
            },
        });
    }
});

d3.select("#overallCheckBox").on("change", function () {
    // This option is implemented for the hardcoded semantics, which are almost always the used ones.
    // To call other semantics it will be necessary to redo the output.

    if (!$(this).is(":checked")) {
        return;
    }

    var timeLimit = "1";
    var semantics = ";";

    semantics += "preferred,";
    semantics += "grounded,";
    semantics += "categoriser,";

    var select = document.getElementById("featureset"),
        i = select.selectedIndex,
        currentFeatureset = select.options[i].text;

    semantics = semantics.slice(0, -1);
    var hasTruth = false;
    for (var key in allData_[0]) {
        if (key == "GroundTruth") {
            hasTruth = true;
            graph.overallMatches(currentFeatureset, semantics, timeLimit);
            break;
        }
    }

    if (!hasTruth) {
        document.getElementById("overallResults").innerHTML =
            "<b>No GroundTruth column</b>";
    }
});

d3.select("#strengthCheckBox").on("change", function () {
    if (document.getElementById("row_input").value != "") {
        row = JSON.parse(document.getElementById("row_input").value);
        var hasWeight = false;
        for (key in row) {
            if (key.indexOf("Weight_") !== -1) {
                hasWeight = true;
                break;
            }
        }

        if (hasWeight) {
            var event = new Event("change");
            document.getElementById("row_input").dispatchEvent(event);
        } else {
            window.alert(
                'No weights imported for any column. Strength of arguments can\'t be used. Import colum "Weigh_<Column>" to define feature strenghts.',
                "No weights error."
            );
            document.getElementById("strengthCheckBox").checked = false;
        }
    }
});

d3.select("#strengthCheckBoxExport").on("change", function () {
    var hasWeight = false;
    for (key in allData_[0]) {
        if (key.indexOf("Weight_") !== -1) {
            hasWeight = true;
            break;
        }
    }

    if (!hasWeight) {
        window.alert(
            'No weights imported for any column. Strength of arguments can\'t be used. Import colum "Weigh_<Column>" to define feature strenghts.',
            "No weights error."
        );
        document.getElementById("strengthCheckBoxExport").checked = false;
    }
});

// Call semantics in selection table row input
d3.select("#row_input").on("change", function () {
    var row = JSON.parse(this.value);

    var select = document.getElementById("semanticsVisualization"),
        i = select.selectedIndex,
        semantic = select.options[i].text;

    // Active all nodes, compute its degree and define initial css class
    // based on the current row.

    var select = document.getElementById("featureset"),
        i = select.selectedIndex,
        currentFeatureset = select.options[i].text;

    graph.activeAll(row, currentFeatureset, true, false);

    var api;
    if (semantic == "Grounded") {
        api = "grounded";
    } else if (semantic == "Preferred") {
        api = "preferred";
    } else if (semantic == "Expert System") {
        api = "expert";
    } else if (semantic == "Admissible") {
        api = "admissible";
    } else if (semantic == "Stable") {
        api = "stable";
    } else if (semantic == "Semi-stable") {
        api = "semistable";
    } else if (semantic == "Eager") {
        api = "eager";
    } else if (semantic == "Ideal") {
        api = "ideal";
    } else if (semantic == "Activated") {
        api = "activated";
    } else if (semantic == "Rank based: Categoriser") {
        api = "categoriser";
    }

    console.log(api);

    // Include the first extension in the extension list.
    var extensionDiv = document.getElementById("extensions");
    extensionDiv.style.visibility = "visible";
    var extensionList = document.getElementById("extensionNumber");

    // Remove previous options in the list
    if (extensionList.options.length > 0) {
        for (var i = extensionList.options.length - 1; i >= 0; i--) {
            extensionList.remove(i);
        }
    }

    var option = document.createElement("option");
    option.text = "1";
    extensionList.add(option);

    var extensionTotal = document.getElementById("nExtensions");

    var graphString = "" + graph.getStringGraph();

    if (graphString.length > 0) {
        if (api == "activated") {
            //console.log(graph.getActivatedJson());
            var jsonExtension = graph.getActivatedJson();
            extensionTotal.innerHTML = "of <b>" + 1 + "</b>";
            graph.semanticsPerRow(jsonExtension, false, true);
        } else {
            getExtension(addressCall_ + api + "/" + graph.getStringGraph(), api, callsemantics);
        }
    } else {
        extensionTotal.innerHTML = "of <b>" + 1 + "</b>";
        graph.semanticsPerRow("");
    }

    function callsemantics(extension) {

        if (extension.search("Maximum execution time") != -1) {
            alert("Maximum execution time exceeded!");
            return;
        }

        if (extension.search("Allowed memory size") != -1) {
            alert("Maximum allocated memory exceeded!");
            return;
        }

        var jsonExtension = JSON.parse(extension);
        //console.log(jsonExtension);

        if (api == "preferred" || api == "admissible" || api == "stable" || api == "semistable") {
            var nExtensions = jsonExtension.length;

            // Add the extension options
            extensionTotal.innerHTML = "of <b>" + nExtensions + "</b>";

            if (nExtensions > 1) {
                for (var i = 2; i <= nExtensions; i++) {
                    var option = document.createElement("option");
                    option.text = i;
                    extensionList.add(option);
                }
            }

            // Getting only the first preferred extension.
            // The other exensions are computed through the extensionList
            // options
            graph.semanticsPerRow(jsonExtension[0]);
        } else if ( api == "grounded" || api == "expert" || api == "eager" || api == "ideal") {
            // Grounded semantics only have 1 extension
            extensionTotal.innerHTML = "of <b>" + 1 + "</b>";
            graph.semanticsPerRow(jsonExtension);
        } else if (api == "categoriser") {
            extensionTotal.innerHTML = "of <b>" + 1 + "</b>";
            // To file false, only activate false, rank based true;
            graph.semanticsPerRow(jsonExtension[0], false, false, true);
        }
    }
});

// Call semantics in the extension list option
d3.select("#extensionNumber").on("change", function () {
    var select = document.getElementById("semanticsVisualization"),
        i = select.selectedIndex,
        semantic = select.options[i].text;

    var api;
    if (semantic == "Grounded") {
        api = "grounded";
    } else if (semantic == "Preferred") {
        api = "preferred";
    } else if (semantic == "Expert System") {
        api = "expert";
    } else if (semantic == "Admissible") {
        api = "admissible";
    } else if (semantic == "Stable") {
        api = "stable";
    } else if (semantic == "Semi-stable") {
        api = "semistable";
    } else if (semantic == "Eager") {
        api = "eager";
    } else if (semantic == "Ideal") {
        api = "ideal";
    }

    //graph.activeAll(row, currentFeatureset);

    var graphString = "" + graph.getStringGraph();

    if (graphString.length > 0) {
        getExtension(
            addressCall_ + api + "/" + graph.getStringGraph(),
            api,
            callsemantics
        );
    } else {
        graph.semanticsPerRow("");
    }

    function callsemantics(extension, api) {
        var jsonExtension = JSON.parse(extension);

        if (
            api == "preferred" ||
            api == "admissible" ||
            api == "stable" ||
            api == "semistable"
        ) {
            var extensionList = document.getElementById("extensionNumber");
            // Getting only the first preferred extension.
            // The other exensions are computed through the extensionList
            // options
            graph.semanticsPerRow(jsonExtension[extensionList.selectedIndex]);
        } else if (
            api == "grounded" ||
            api == "expert" ||
            api == "eager" ||
            api == "ideal"
        ) {
            // Grounded semantics only have 1 extension
            extensionTotal.innerHTML = "of <b>" + 1 + "</b>";
            graph.semanticsPerRow(jsonExtension);
        }
    }
});

function getExtension(url, api, callback) {
    // How can I use this callback?
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            return callback(request.responseText, api); // Another callback here
        }
    };

    request.open("GET", url);
    request.send();
}

d3.select("#zoomin").on("click", function (event) {
    var zoom = d3.behavior.zoom().on("zoom", function () {
        graph.zoomed.call(graph);
    });

    graph.svg.call(zoom);

    graph.zoomScale *= 1.1;
    zoom.scale(graph.zoomScale);
    zoom.event(graph.svg);
});

d3.select("#zoomout").on("click", function (event) {
    var zoom = d3.behavior.zoom().on("zoom", function () {
        graph.zoomed.call(graph);
    });

    graph.svg.call(zoom);

    graph.zoomScale *= 0.9;
    zoom.scale(graph.zoomScale);
    zoom.event(graph.svg);
});
