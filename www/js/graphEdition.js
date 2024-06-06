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
function createEditionGraph(d3, saveAs, Blob) {
    "use strict";

    // define graphcreator object
    var GraphCreator = function(svg, nodes, edges){
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
            selectedText: null
        };

        // define arrow markers for graph links
        var defs = svg.append('svg:defs');
        defs.append('svg:marker')
            .attr('id', 'end-arrow')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', "32")
            .attr('markerWidth', 3.5)
            .attr('markerHeight', 3.5)
            .attr('orient', 'auto')
            .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5');

        // Undercut
        defs.append('svg:marker')
            .attr('id', 'end-arrow-red')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', "32")
            .attr('markerWidth', 3.5)
            .attr('markerHeight', 3.5)
            .attr('orient', 'auto')
            .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr("fill", "#A3C493");

        // Undermine
        defs.append('svg:marker')
            .attr('id', 'end-arrow-blue')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', "32")
            .attr('markerWidth', 3.5)
            .attr('markerHeight', 3.5)
            .attr('orient', 'auto')
            .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr("fill", "#FFC300");

        // Rebuttal
        defs.append('svg:marker')
            .attr('id', 'end-arrow-green')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', "32")
            .attr('markerWidth', 3.5)
            .attr('markerHeight', 3.5)
            .attr('orient', 'auto')
            .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr("fill", "#FF5733");

        // Hovered
        defs.append('svg:marker')
            .attr('id', 'end-arrow-hover')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', "32")
            .attr('markerWidth', 3.5)
            .attr('markerHeight', 3.5)
            .attr('orient', 'auto')
            .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr("fill", "#5EC4CC");

        // Selected
        defs.append('svg:marker')
            .attr('id', 'end-arrow-selected')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', "32")
            .attr('markerWidth', 3.5)
            .attr('markerHeight', 3.5)
            .attr('orient', 'auto')
            .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr("fill", "#E5ACF7");

        // define arrow markers for leading arrow
        defs.append('svg:marker')
            .attr('id', 'mark-end-arrow')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', 7)
            .attr('markerWidth', 3.5)
            .attr('markerHeight', 3.5)
            .attr('orient', 'auto')
            .append('svg:path')
            .attr('d', 'M0,-5L10,0L0,5');

        thisGraph.svg = svg;
        thisGraph.svgG = svg.append("g")
            .classed(thisGraph.consts.graphClass, true);
        var svgG = thisGraph.svgG;

        // displayed when dragging between nodes
        thisGraph.dragLine = svgG.append('svg:path')
            .attr('class', 'link dragline hidden')
            .attr('d', 'M0,0L0,0')
            .style('marker-end', 'url(#mark-end-arrow)');

        // svg nodes and edges 
        thisGraph.paths = svgG.append("g").selectAll("g");
        thisGraph.circles = svgG.append("g").selectAll("g");

        thisGraph.drag = d3.behavior.drag()
            .origin(function(d){
                return {x: d.x, y: d.y};
            })
            .on("drag", function(args){
                thisGraph.state.justDragged = true;
                thisGraph.dragmove.call(thisGraph, args);
            })
            .on("dragend", function() {
                // todo check if edge-mode is selected
            });

        // listen for key events
        d3.select(window).on("keydown", function(){
            thisGraph.svgKeyDown.call(thisGraph);
        })
        .on("keyup", function(){
            thisGraph.svgKeyUp.call(thisGraph);
        });

        svg.on("mousedown", function(d){thisGraph.svgMouseDown.call(thisGraph, d);});
        svg.on("mouseup", function(d){thisGraph.svgMouseUp.call(thisGraph, d);});

        // listen for dragging
        var dragSvg = d3.behavior.zoom()
            .on("zoom", function(){
                if (d3.event.sourceEvent.shiftKey){
                // TODO  the internal d3 state is still changing
                return false;
                } else{
                thisGraph.zoomed.call(thisGraph);
                }
                return true;
            })
            .on("zoomstart", function(){
                var ael = d3.select("#" + thisGraph.consts.activeEditId).node();
                if (ael){
                ael.blur();
                }
                if (!d3.event.sourceEvent.shiftKey) d3.select('body').style("cursor", "move");
            })
            .on("zoomend", function(){
                d3.select('body').style("cursor", "auto");
            });

        svg.call(dragSvg).on("dblclick.zoom", null);

        // listen for resize
        window.onresize = function(){thisGraph.updateWindow(svg);};

        d3.select("#node-input").on("click", function(){
            d3.select("#modalTitle").html("Add new node");
            document.getElementById('warningArgument').innerHTML = '';
            document.getElementById('warningArgument').hidden = true;
            document.getElementById("editAddArgument").style.visibility='hidden';
            document.getElementById("editNewArgument").style.visibility='visible';
            document.getElementById("editDeleteNode").style.visibility='hidden';
            $('#myModal').modal('show');
        });


        d3.select("#help-graph").on("click", function(){
            $('#modalHelp').modal('show');
        });

        d3.select("#copy-graph").on("click", function(){
            $('#modalCopyGraph').modal('show');
        });

        d3.select("#upload-graph").on("click", function(){
            $('#modalUploadGraph').modal('show');
        });

        // handle download data
        d3.select("#download-input").on("click", function(){
            var saveEdges = [];
            thisGraph.edges.forEach(function(val, i){
                saveEdges.push({source: val.source.id, target: val.target.id, type: val.type});
            });

            var blob = new Blob([window.JSON.stringify({"nodes": thisGraph.nodes, "edges": saveEdges})], {type: "text/plain;charset=utf-8"});
                saveAs(blob, "mydag.json");
        });

        // handle uploaded data
        d3.select("#upload-input").on("click", function(){
            document.getElementById("hidden-file-upload").click();
        });

        d3.select("#hidden-file-upload").on("change", function(){
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                var uploadFile = this.files[0];
                var filereader = new window.FileReader();

                filereader.onload = function(){
                    var txtRes = filereader.result;
                    // TODO better error handling
                    try{
                        var jsonObj = JSON.parse(txtRes);
                        thisGraph.deleteGraph(true);
                        thisGraph.nodes = jsonObj.nodes;
                        thisGraph.setIdCt(jsonObj.nodes.length + 1);
                        var newEdges = jsonObj.edges;
                        newEdges.forEach(function(e, i){
                            newEdges[i] = {source: thisGraph.nodes.filter(function(n){return n.id == e.source;})[0],
                                        target: thisGraph.nodes.filter(function(n){return n.id == e.target;})[0]};
                        });
                        thisGraph.edges = newEdges;
                        thisGraph.updateGraph();
                    }catch(err){
                        window.alert("Error parsing uploaded file\nerror message: " + err.message, "Parsing error");
                        return;
                    }
                };
                filereader.readAsText(uploadFile);
            } else {
                alert("Your browser won't let you save this graph -- try upgrading your browser to IE 10+ or Chrome or Firefox.");
            }
        });

        // handle delete graph
        d3.select("#delete-graph").on("click", function(){
            thisGraph.deleteGraph(false);
        });
    };

    GraphCreator.prototype.setIdCt = function(idct){
        this.idct = idct;
    };

    GraphCreator.prototype.consts =  {
        selectedClass: "selected",
        connectClass: "connect-node",
        circleGClass: "conceptG",
        acceptedGClass: "acceptedG",
        deniedGClass: "deniedG",
        graphClass: "graph",
        activeEditId: "active-editing",
        //BACKSPACE_KEY: 8,
        DELETE_KEY: 46,
        ENTER_KEY: 13,
        nodeRadius: 50,
        blue: "#0066FF",
        red: "#CC0000",
        green: "#336600",
        black: "#333",
        blueRed: "#64347D",
        blueGreen: "#1E667D",
        blueBlack: "#2A3C73",
        redGreen: "#7C3200",
        redBlack: "#5C2A2A",
        greenBlack: "#334224"
    };

    /* PROTOTYPE FUNCTIONS */

    GraphCreator.prototype.dragmove = function(d) {
        var thisGraph = this;
        if (thisGraph.state.shiftNodeDrag){
            thisGraph.dragLine.attr('d', 'M' + d.x + ',' + d.y + 'L' + d3.mouse(thisGraph.svgG.node())[0] + ',' + d3.mouse(this.svgG.node())[1]);
        } else{
            d.x += d3.event.dx;
            d.y +=  d3.event.dy;
            thisGraph.updateGraph();
        }
    };

    GraphCreator.prototype.deleteGraph = function(skipPrompt){
        var thisGraph = this;
            
        if (skipPrompt) {
            thisGraph.nodes = [];
            thisGraph.edges = [];
            thisGraph.updateGraph();
            return;
        }
        
        confirm("Are you sure you want to delete this graph?", "Confirm deletion.", "OK", function (result) {
            if (! result) {
                return;
            }
            
            thisGraph.nodes = [];
            thisGraph.edges = [];
            thisGraph.updateGraph();
        });
    };

    /* select all text in element: taken from http://stackoverflow.com/questions/6139107/programatically-select-text-in-a-contenteditable-html-element */
    GraphCreator.prototype.selectElementContents = function(el) {
        var range = document.createRange();
        range.selectNodeContents(el);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    };


    /* insert svg line breaks: taken from http://stackoverflow.com/questions/13241475/how-do-i-include-newlines-in-labels-in-d3-charts */
    GraphCreator.prototype.insertTitleLinebreaks = function (gEl, title) {
        var words = title.split(/\s+/g),
            nwords = words.length;
        var el = gEl.append("text")
            .attr("text-anchor","middle")
            .attr("dy", "-" + (nwords-1)*7.5);

        for (var i = 0; i < words.length; i++) {
            var tspan = el.append('tspan').text(words[i]);
            if (i > 0) {
                tspan.attr('x', 0).attr('dy', '15');
            }
        }
    };

    GraphCreator.prototype.styleTitle = function (gEl, title) {
        var words = title.split(/\s+/g),
            nwords = words.length;

        var el = gEl.append("text")
            .attr("text-anchor","middle")
            .attr("dy", "-" + (nwords-1)*7.5);

        for (var i = 0; i < words.length; i++) {
            var tspan = el.append('tspan').text(words[i]);
            if (i > 0) {
                tspan.attr('x', 0).attr('dy', '15');
            } else {
                tspan.attr('font-weight', "bold");
                tspan.attr('font-size', "30px");
            }
        }
    };

    GraphCreator.prototype.setFontSize = function (gEl, title, fontSize) {
        gEl.select("tspan").attr('font-size', fontSize + "px");
    };

    GraphCreator.prototype.styleTooltip = function (gEl, tooltip) {

        var words = tooltip.split("] ");

        var el = gEl.selectAll("text")
            .attr("text-anchor","middle")
            .attr("dy", "0");

        var tspan = el.append('tspan').text(words[0]);
        tspan.attr('font-weight', "bold");

        /*tspan = el.append('tspan').text(words[1]);
        tspan.attr('x', 0).attr('dy', '15');*/

        /*
        for (var i = 0; i < words.length; i++) {
            var tspan = el.append('tspan').text(words[i]);
            if (i > 0) {
                tspan.attr('x', 0).attr('dy', '15');
            } else {
                tspan.attr('font-weight', "bold");
            }
        }*/
    };

    // remove edges associated with a node
    GraphCreator.prototype.spliceLinksForNode = function(node) {
        var thisGraph = this,
            toSplice = thisGraph.edges.filter(function(l) {
        return (l.source === node || l.target === node);
        });
        toSplice.map(function(l) {
        thisGraph.edges.splice(thisGraph.edges.indexOf(l), 1);
        });
    };

    GraphCreator.prototype.replaceSelectEdge = function(d3Path, edgeData){
        var thisGraph = this;
        d3Path.classed(thisGraph.consts.selectedClass, true);

        $('#modalAttackType').modal('show');

        if (edgeData.type == "undermine") {
            document.querySelector("input[value='undermine']").checked = true;
        } else if (edgeData.type == "undercut") {
            document.querySelector("input[value='undercut']").checked = true;
        } else if (edgeData.type == "rebuttal") {
            document.querySelector("input[value='rebuttal']").checked = true;
        } else {
            document.querySelector("input[value='none']").checked = true;
        }

        // Set current selected edge so it is possible to update it
        // outside the graph object
        //console.log(edgeData);
        document.getElementById('attackLabel').value = String(edgeData.source.title) + "," + String(edgeData.target.title);
        document.getElementById('attackID').value = String(edgeData.source.id) + "," + String(edgeData.target.id);

        if (thisGraph.state.selectedEdge){
        thisGraph.removeSelectFromEdge();
        }
        thisGraph.state.selectedEdge = edgeData;

        d3Path.style('marker-end', "url(#end-arrow-selected)");
    };

    GraphCreator.prototype.replaceSelectNode = function(d3Node, nodeData){
        var thisGraph = this;
        d3Node.classed(this.consts.selectedClass, true);
        if (thisGraph.state.selectedNode){
        thisGraph.removeSelectFromNode();
        }
        thisGraph.state.selectedNode = nodeData;
    };

    GraphCreator.prototype.removeSelectFromNode = function(){
        var thisGraph = this;
        if (thisGraph.state.selectedNode != null) {
            thisGraph.circles.filter(function(cd){
            return cd.id === thisGraph.state.selectedNode.id;
            }).classed(thisGraph.consts.selectedClass, false);
        }
        thisGraph.state.selectedNode = null;
    };

    GraphCreator.prototype.removeSelectFromEdge = function(){
        var thisGraph = this;
        thisGraph.paths.filter(function(cd){
        return cd === thisGraph.state.selectedEdge;
        }).classed(thisGraph.consts.selectedClass, false);
        thisGraph.state.selectedEdge = null;
    };

    GraphCreator.prototype.pathMouseDown = function(d3path, d){
        var thisGraph = this,
            state = thisGraph.state;
        d3.event.stopPropagation();
        state.mouseDownLink = d;

        if (state.selectedNode){
        thisGraph.removeSelectFromNode();
        }

        var prevEdge = state.selectedEdge;  
        if (!prevEdge || prevEdge !== d){
        thisGraph.replaceSelectEdge(d3path, d);
        } else{
        thisGraph.removeSelectFromEdge();
        }
    };

    // mousedown on node
    GraphCreator.prototype.circleMouseDown = function(d3node, d){
        var thisGraph = this,
            state = thisGraph.state;
        d3.event.stopPropagation();
        state.mouseDownNode = d;
        if (d3.event.shiftKey){
            state.shiftNodeDrag = d3.event.shiftKey;
            // reposition dragged directed edge
            thisGraph.dragLine.classed('hidden', false)
                .attr('d', 'M' + d.x + ',' + d.y + 'L' + d.x + ',' + d.y);
            return;
        }
    };

    /* place editable text on node in place of svg text */
    GraphCreator.prototype.changeTextOfNode = function(d3node, d){
        var thisGraph= this,
            consts = thisGraph.consts,
            htmlEl = d3node.node();
        d3node.selectAll("text").remove();
        var nodeBCR = htmlEl.getBoundingClientRect(),
            curScale = nodeBCR.width/consts.nodeRadius,
            placePad  =  5*curScale,
            useHW = curScale > 1 ? nodeBCR.width*0.71 : consts.nodeRadius*1.42;
        // replace with editableconent text
        var d3txt = thisGraph.svg.selectAll("foreignObject")
            .data([d])
            .enter()
            .append("foreignObject")
            .attr("x", nodeBCR.left + placePad )
            .attr("y", nodeBCR.top + placePad)
            .attr("height", 2*useHW)
            .attr("width", useHW)
            .append("xhtml:p")
            .attr("id", consts.activeEditId)
            .attr("contentEditable", "true")
            .text(d.title)
            .on("mousedown", function(d){
                d3.event.stopPropagation();
            })
            .on("keydown", function(d){
                d3.event.stopPropagation();
                if (d3.event.keyCode == consts.ENTER_KEY && !d3.event.shiftKey){
                this.blur();
                }
            })
            .on("blur", function(d){
                d.title = this.textContent;
                thisGraph.insertTitleLinebreaks(d3node, d.title);
                d3.select(this.parentElement).remove();
            });
        return d3txt;
    };

    GraphCreator.prototype.addJSONGraph = function(graphParse) {

        var thisGraph = this;
        thisGraph.nodes = graphParse.nodes;
        //thisGraph.setIdCt(graphParse.nodes.length + 1);
        var newEdges = graphParse.edges;

        // New edges sources and targets need to be nodes. The loop replace the ids by the actual nodes
        newEdges.forEach(function(e, i){
            newEdges[i] = {source: thisGraph.nodes.filter(function(n){return n.id == e.source;})[0],
                           target: thisGraph.nodes.filter(function(n){return n.id == e.target;})[0],
                           type: e.type};
        });
        //console.log(newEdges);
        thisGraph.edges = newEdges;
        thisGraph.updateGraph();
    }

    GraphCreator.prototype.addArgument = function() {

        var thisGraph = this;

        var title = document.getElementById('editLabel').value;

        if (thisGraph.nodes.filter(function(n){return n.title == title;}).length > 0) {
            // return false if label is already being used
            return false;
        }

        var docEl = document.documentElement,
        bodyEl = document.getElementsByTagName('body')[0];
        var x = window.innerWidth || docEl.clientWidth || bodyEl.clientWidth;
        var y = window.innerHeight|| docEl.clientHeight|| bodyEl.clientHeight;

        var argument = document.getElementById('editCurrentArgument').value;

        var weight = document.getElementById('editWeight').value;

        if (weight == "None") {
            weight = null;
        }

        var xycoords = d3.mouse(thisGraph.svgG.node()),
            d = {id: thisGraph.idct++, title: title, x: x / 2.0, y: y / 2.0, tooltip: argument, weight: weight};
        thisGraph.nodes.push(d);

        thisGraph.updateGraph();

        return true;
    }
    
    GraphCreator.prototype.addRebuttals = function() {

        var thisGraph = this;
        thisGraph.circles.each(function(td) {
            thisGraph.circles.each(function(sd) {
                
                var insertRebuttal = true;
                
                if (td.id == sd.id) {
                    insertRebuttal = false;
                }
                
                var premiseAndConclusionTarget = String(td.tooltip).split(" -> ");
                var premiseAndConclusionSource = String(sd.tooltip).split(" -> ");
                // Only arguments with a conclusion will have a value
                var hasConclusionTarget = premiseAndConclusionTarget.length == 2 && premiseAndConclusionTarget[1] != "NULL";
                var hasConclusionSource = premiseAndConclusionSource.length == 2 && premiseAndConclusionSource[1] != "NULL";
                
                // No automatic rebuttals among arguments without conclusions
                if (! hasConclusionTarget || ! hasConclusionSource) {
                    insertRebuttal = false;
                }
                
                // No automatic rebuttals among arguments with same conclusion
                if (premiseAndConclusionTarget[1] == premiseAndConclusionSource[1]) {
                    insertRebuttal = false;
                }
                
                
                // No automatic rebuttals among arguments that are already interacting
                if (insertRebuttal) {
                    edges.forEach(function(val, i){
                        if (val.target.id == td.id && val.source.id == sd.id) {
                            insertRebuttal = false;
                        }
                        
                        if (val.target.id == sd.id && val.source.id == td.id) {
                            insertRebuttal = false;
                        }
                    });
                }
                    
                if (insertRebuttal) {        
                    var newEdge = {source: sd, target: td, type: "rebuttal"};
                    thisGraph.edges.push(newEdge);
                    //thisGraph.updateGraph();
                    
                    var newEdge = {source: td, target: sd, type: "rebuttal"};
                    thisGraph.edges.push(newEdge);
                    //thisGraph.updateGraph();
                }
            }); 
        });
        
        thisGraph.updateGraph();
        
        return true;
    }

    // mouseup on nodes
    GraphCreator.prototype.circleMouseUp = function(d3node, d){

        var thisGraph = this,
            state = thisGraph.state,
            consts = thisGraph.consts;
        // reset the states
        state.shiftNodeDrag = false;
        d3node.classed(consts.connectClass, false);

        var mouseDownNode = state.mouseDownNode;

        if (!mouseDownNode) return;

        thisGraph.dragLine.classed("hidden", true);

        if (mouseDownNode !== d){
            // we're in a different node: create new edge for mousedown edge and add to graph
            var newEdge = {source: mouseDownNode, target: d, type: "none"};
            var filtRes = thisGraph.paths.filter(function(d){
                /*if (d.source === newEdge.target && d.target === newEdge.source){
                thisGraph.edges.splice(thisGraph.edges.indexOf(d), 1);
                }*/
                return d.source === newEdge.source && d.target === newEdge.target;
            });
            if (!filtRes[0].length){
                thisGraph.edges.push(newEdge);
                thisGraph.updateGraph();
            }
        } else{

            /*
            on("click", function() {
                d3.event.preventDefault();

                var select = document.getElementById('semantics'),
                    i = select.selectedIndex,
                    semantic = select.options[i].text;

                if (semantic == "Expert System") {
                    graph.expertSystem();
                }
            });*/

            // we're in the same node
            if (state.justDragged) {
                // dragged, not clicked
                state.justDragged = false;
            } else{

                if (state.selectedEdge){
                    thisGraph.removeSelectFromEdge();
                }

                var prevNode = state.selectedNode;

                if (!prevNode || prevNode.id !== d.id){
                    thisGraph.replaceSelectNode(d3node, d);

                    var currentArgument = document.getElementById('editCurrentArgument');
                    currentArgument.value = d.tooltip;

                    var currentLabel = document.getElementById('editLabel');
                    currentLabel.value = d.title;

                    // After selecting a node it is possible to edit
                    document.getElementById("editAddArgument").disabled = false;

                    document.getElementById("editDeletePremise").disabled = false;

                    document.getElementById("editNewArgument").disabled = true;


                    // Check if there is a conclusion so we can put the conclusion
                    // list in the right option
                    var conclusionsList = document.getElementById('editConclusions');
                    var premiseAndConclusion = String(d.tooltip).split(" -> ");

                    var weight = "None";
                    if (d.weight != null) {
                        weight = d.weight;
                    }

                    document.getElementById("editWeight").value = weight;
                    
                    validateArgument(premiseAndConclusion[0]);

                    if (premiseAndConclusion.length == 2) {
                        // Check if there is a premise before adding any conclusion

                        var conclusionLabel = "";
                        var from = "";
                        var to = "";

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
                            from += premiseAndConclusion[1][i];
                        }

                        begin = end + 3; //Begin of "to";
                        end = begin + 1;

                        while (premiseAndConclusion[1][end] != "]") {
                            end++;
                        }

                        end--; // End of "to";

                        for (var i = begin; i <= end; i++) {
                            to += premiseAndConclusion[1][i];
                        }

                        for (var i = 0; i < conclusionsList.options.length; i++) {
                            if (conclusionsList.options[i].text == conclusionLabel) {
                                conclusionsList.selectedIndex = i;
                                break;
                            }
                        }

                        document.getElementById("editConclusionFrom").innerHTML = from;
                        document.getElementById("editConclusionTo").innerHTML = to;
                        document.getElementById('editInvertRange').disabled = false;

                    } else {
                        // Check if there is a premise before adding any conclusion
                        for (var i = 0; i < conclusionsList.options.length; i++) {
                            if (conclusionsList.options[i].text == "None") {
                                conclusionsList.selectedIndex = i;
                                break;
                            }
                        }
                    }

                    d3.select("#modalTitle").html("Edit node (" + d.title + ")");

                    document.getElementById("editAddArgument").style.visibility='visible';
                    document.getElementById("editNewArgument").style.visibility='hidden';
                    document.getElementById("editDeleteNode").style.visibility='visible';

                    $('#myModal').modal('show');

                    $('#myModal').on('hidden.bs.modal', function () {
                        thisGraph.removeSelectFromNode();

                        document.getElementById('editCurrentArgument').value = "";

                        document.getElementById('editLabel').value = "";

                        document.getElementById("editWeight").value = "None";

                        document.getElementById("editAddArgument").disabled = true;

                        document.getElementById("editDeletePremise").disabled = true;

                        document.getElementById("editNewArgument").disabled = false;

                        var conclusionsList = document.getElementById('editConclusions');
                        // Check if there is a premise before adding any conclusion
                        for (var i = 0; i < conclusionsList.options.length; i++) {
                            if (conclusionsList.options[i].text == "None") {
                                conclusionsList.selectedIndex = i;
                                break;
                            }
                        }
                    })
                }
            }
        }

        state.mouseDownNode = null;
        return;

    }; // end of circles mouseup

    // mousedown on main svg
    GraphCreator.prototype.svgMouseDown = function(){
        this.state.graphMouseDown = true;
    };

    // mouseup on main svg
    GraphCreator.prototype.svgMouseUp = function(){
        var thisGraph = this,
            state = thisGraph.state;
        if (state.justScaleTransGraph) {
            // dragged not clicked
            state.justScaleTransGraph = false;
        } else if (state.shiftNodeDrag){
            // dragged from node
            state.shiftNodeDrag = false;
            thisGraph.dragLine.classed("hidden", true);
        }

        state.graphMouseDown = false;
    };

    // keydown on main svg
    GraphCreator.prototype.svgKeyDown = function() {
        var thisGraph = this,
            state = thisGraph.state,
            consts = thisGraph.consts;

        if (state.selectedEdge == null){
            return;
        }
        // make sure repeated key presses don't register for each keydown
        if(state.lastKeyDown !== -1) return;

        state.lastKeyDown = d3.event.keyCode;
        var selectedNode = state.selectedNode,
            selectedEdge = state.selectedEdge;

        switch(d3.event.keyCode) {
        //case consts.BACKSPACE_KEY:
        case consts.DELETE_KEY:
        d3.event.preventDefault();
        if (selectedEdge){
            thisGraph.edges.splice(thisGraph.edges.indexOf(selectedEdge), 1);
            state.selectedEdge = null;
            thisGraph.updateGraph();
        }
        break;
        }
    };

    GraphCreator.prototype.svgKeyUp = function() {
        this.state.lastKeyDown = -1;
    };

    GraphCreator.prototype.setActivation = function(node, row) {
    }

    GraphCreator.prototype.get_from_to = function(level, attribute) {
        // Given a level-attribute pair, find the corresponding numeric range
        // of the level for the current feature set selected.
        var select = document.getElementById('editFeatureset'),
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

    GraphCreator.prototype.upSideDown = function (aString){
        var last = aString.length - 1;
        //Thanks to Brook Monroe for the
        //suggestion to use Array.join
        var result = new Array(aString.length)
        for (var i = last; i >= 0; --i)
        {
            var c = aString.charAt(i)
            var r = flipTable[c]
            result[last - i] = r != undefined ? r : c
        }
        return result.join('')
    }


    GraphCreator.prototype.updateEdge = function (){

        var thisGraph = this,
            consts = thisGraph.consts;

        var source = document.getElementById("attackID").value.split(",")[0];
        var target = document.getElementById("attackID").value.split(",")[1];

        var sourceLabel = document.getElementById("attackLabel").value.split(",")[0];
        var targetLabel = document.getElementById("attackLabel").value.split(",")[1];

        var id = String(source) + "," + String(target);

        var originalPath = thisGraph.paths.filter(function(d){
                        return d.source.id == source &&
                                d.target.id == target;
        });

        edges.forEach(function(val, i){
            if (val.source.title == sourceLabel && val.target.title == targetLabel) {
                if (document.getElementById("undercut").checked) {
                    val.type = "undercut";
                    originalPath.style('marker-end','url(#end-arrow-red)')
                    //originalPath.style("stroke", "#CC0000");
                } else if (document.getElementById("undermine").checked) {
                    val.type = "undermine";
                    originalPath.style('marker-end','url(#end-arrow-blue)')
                    //originalPath.style("stroke", "#0066FF");
                } else if (document.getElementById("rebuttal").checked) {
                    val.type = "rebuttal";
                    originalPath.style('marker-end','url(#end-arrow-green)')
                    //originalPath.style("stroke", "#336600");
                } else {
                    val.type = "none";
                    originalPath.style('marker-end','url(#end-arrow)')
                }

                originalPath.classed("linkBlue", function(d){
                                return d.type == "undermine";
                            })
                            .classed("linkRed", function(d){
                                return d.type == "undercut";
                            })
                            .classed("linkGreen", function(d){
                                return d.type == "rebuttal";
                            })
                            .classed("link", function(d){
                                return d.type == "none";
                            });
            }
        });


        thisGraph.removeSelectFromEdge();

        thisGraph.updateGraph();

        $('#modalAttackType').modal('hide');
    }

    // call to propagate changes to graph
    GraphCreator.prototype.updateGraph = function(firstCall = false){
        var thisGraph = this,
            consts = thisGraph.consts,
            state = thisGraph.state;

        thisGraph.paths = thisGraph.paths.data(thisGraph.edges, function(d){
            return String(d.source.id) + "+" + String(d.target.id);
        });

        var paths = thisGraph.paths;

        // update existing paths
        paths.style('marker-end', function(d){
            if (d.type == "undermine") {
               return 'url(#end-arrow-blue)';
            } else if (d.type == "undercut") {
                return 'url(#end-arrow-red)';
            } else if (d.type == "rebuttal") {
                return 'url(#end-arrow-green)';
            } else {
                return 'url(#end-arrow)';
            }
        })
        .classed(consts.selectedClass, function(d){
            return d === state.selectedEdge;
        })
        .attr("d", function(d){
            return "M" + d.source.x + "," + d.source.y + "L" + d.target.x + "," + d.target.y;
        });

        var edges = thisGraph.edges;

        // add new paths
        paths.enter()
        .append("path")
        .style('marker-end', function(d){
            if (d.type == "undermine") {
               return 'url(#end-arrow-blue)';
            } else if (d.type == "undercut") {
                return 'url(#end-arrow-red)';
            } else if (d.type == "rebuttal") {
                return 'url(#end-arrow-green)';
            } else {
                return 'url(#end-arrow)';
            }
        })
        .on("mouseover", function(d) {
            d3.select(this).style('marker-end', "url(#end-arrow-hover)");
        })
        .on("mouseout", function(d) {
            if (thisGraph.state.selectedEdge == d) {
                d3.select(this).style('marker-end', "url(#end-arrow-selected)");
            } else if (d.type == "undermine") {
                d3.select(this).style('marker-end', "url(#end-arrow-blue)");
            } else if (d.type == "undercut") {
                d3.select(this).style('marker-end', "url(#end-arrow-red)");
            } else if (d.type == "rebuttal") {
                d3.select(this).style('marker-end', "url(#end-arrow-green)");
            } else {
                d3.select(this).style('marker-end', "url(#end-arrow)");
            }
        })
        .classed("linkBlue", function(d){
            return d.type == "undermine";
        })
        .classed("linkRed", function(d){
            return d.type == "undercut";
        })
        .classed("linkGreen", function(d){
            return d.type == "rebuttal";
        })
        .classed("link", function(d){
            return d.type == "none";
        })
        .attr("d", function(d){
            return "M" + d.source.x + "," +  d.source.y + "L" + d.target.x + "," + d.target.y;
        })
        .on("mousedown", function(d){
            thisGraph.pathMouseDown.call(thisGraph, d3.select(this), d);
        })
        .on("mouseup", function(d){
            state.mouseDownLink = null;
        })
        .call(function(d){
        });

        // remove old links
        paths.exit().remove();

        // Separate edges in case there is a two direction attack
        edges.forEach(function(val, i){

            var originalPath = thisGraph.paths.filter(function(d){
                    return d.source.id == val.source.id &&
                            d.target.id == val.target.id;
            });

            var reversePath = thisGraph.paths.filter(function(d){
                    return d.source.id == val.target.id &&
                            d.target.id == val.source.id;
            });

            if (reversePath[0].length > 0) {
                originalPath.attr("d", function(d){
                    var upSource = d.source.y + 20;
                    var upTarget = d.target.y;
                    return "M" + d.source.x + "," + upSource + "L" + d.target.x + "," + upTarget;
                })
                .style('marker-end', function(d){
                    if (d.type == "undermine") {
                        return 'url(#end-arrow-blue)';
                    } else if (d.type == "undercut") {
                        return 'url(#end-arrow-red)';
                    } else if (d.type == "rebuttal") {
                        return 'url(#end-arrow-green)';
                    } else {
                        return 'url(#end-arrow)';
                    }
                });
                reversePath.attr("d", function(d){
                    var downSource = d.source.y - 20;
                    var downTarget = d.target.y;
                    return "M" + d.source.x + "," + downSource + "L" + d.target.x + "," + downTarget;
                })
                .style('marker-end',function(d){
                    if (d.type == "undermine") {
                        return 'url(#end-arrow-blue)';
                    } else if (d.type == "undercut") {
                        return 'url(#end-arrow-red)';
                    } else if (d.type == "rebuttal") {
                        return 'url(#end-arrow-green)';
                    } else {
                        return 'url(#end-arrow)';
                    }
                });
            }
        });
        
        thisGraph.paths.each(function(d) {
            edges.forEach(function(val, i){
                // There is an attack in the other direction so this is a
                // rebuttal
                if (val.target.id == d.source.id && val.source.id == d.target.id) {
                    // Find nodes that are target and source at the same time
                    thisGraph.paths.each(function(d) {
                         if (d.target.id == val.target.id && val.source.id == d.source.id) {
//                             d3.select(this).style("stroke", "darkred");
//                             d3.select(this).style('marker-end','url(#end-arrow-red)');
                               //d3.select(this).style("opacity", 0.2);
                         }
                    });
                }
            });
        });

        // update existing nodes
        thisGraph.circles = thisGraph.circles.data(thisGraph.nodes, function(d){ return d.id;});
        thisGraph.circles.attr("transform", function(d){return "translate(" + d.x + "," + d.y + ")";})
        .style("stroke-dasharray", function(d){
            var premiseAndConclusion = String(d.tooltip).split(" -> ");
            if (premiseAndConclusion.length != 2) {
                return "10 10";
            } else {
               return "0 0"
            }
        });

        var edges = thisGraph.edges;

        // add new nodes
        var newGs= thisGraph.circles.enter()
            .append("g");

        var tooltip = d3.select("body")
                            .append("div")
                            .style("position", "absolute")
                            .style("z-index", "10")
                            .style("background-color", "white")
                            .style("border", "1px solid black")
                            .style("border-radius", "4px")
                            .style("opacity", "0.85")
                            .style("visibility", "hidden")
                            .style("padding", "4px");

        newGs.classed(consts.circleGClass, true)
        .attr("transform", function(d){return "translate(" + d.x + "," + d.y + ")";})
        .style("stroke-dasharray", function(d){
            var premiseAndConclusion = String(d.tooltip).split(" -> ");
            if (premiseAndConclusion.length != 2) {
                return "10 10";
            } else {
               return "0 0"
            }
        })
        .on("mouseover", function(d){
            if (state.shiftNodeDrag){
                d3.select(this).classed(consts.connectClass, true);
            }

            var source = 0;
            var target = 0;
            edges.forEach(function(val, i){
                if (val.target.id == d.id) {
                    target++;
                }

                if (val.source.id == d.id) {
                    source++;
                }
            });


            tooltip.selectAll("text").remove();
            tooltip.append("text");

            var levelDescription = thisGraph.getLevelsDescription(d.tooltip);

            var weight = "None"
            if (d.weight != null) {
                weight = d.weight;
            }

            if (levelDescription.length < 30) { //Greater than <br><b>Attributes used<b><br>
                tooltip.selectAll("text").html("<b>" + d.title + "</b>: " + d.tooltip + "<br/>Weight: " + weight + "<br/><b>Source attacks:</b> " + String(source) + "<br /><b>Target attacks:</b> " + String(target));
            } else {
                tooltip.selectAll("text").html("<b>" + d.title + "</b>: " + d.tooltip  + "<br/>Weight: " + weight + "<br>" + levelDescription + "<br><b>Source attacks:</b> " + String(source) + "<br /><b>Target attacks:</b> " + String(target));
            }

            tooltip.style("visibility", "visible");

        })
        .on("mousemove", function(d){
            return tooltip.style("top", (event.pageY-10)+"px").style("left",(event.pageX+10)+"px");
        })
        .on("mouseout", function(d){
            d3.select(this).classed(consts.connectClass, false);
            return tooltip.style("visibility", "hidden");
        })
        .on("mousedown", function(d){
            thisGraph.circleMouseDown.call(thisGraph, d3.select(this), d);
        })
        .on("mouseup", function(d){
            thisGraph.circleMouseUp.call(thisGraph, d3.select(this), d);
        })
        .call(thisGraph.drag);

        newGs.append("circle")
        .attr("r", String(consts.nodeRadius));

        newGs.each(function(d){
            //thisGraph.insertTitleLinebreaks(d3.select(this), d.title);
            thisGraph.styleTitle(d3.select(this), d.title);
        });

        // remove old nodes
        thisGraph.circles.exit().remove();

        if (firstCall) {
            return;
        }

        //if (document.getElementById("automaticallysave").checked == false) {
            document.getElementById('warningsaved').innerHTML = '<font color=\"red\">* Graph not saved</font> &nbsp';
            document.getElementById('save-graph').hidden = false;
        //}
    };


    // Add node using Add button
    GraphCreator.prototype.saveGraph = function(getCurrentName = true) {

        // Before saving the graph it is necessary to update the edge form with
        // the edges created by the user
        var thisGraph = this;
        var edges = thisGraph.edges;
        var nodes = thisGraph.nodes;

        // Save current name in case it needs to be recovered
        var currentName = document.getElementById('editGraphName').value;
        document.getElementById("editGraphForm").reset();

        // Remove controls edit to create a new empty one.
        // Not sure how to reset it because it might have children appended
        // and vector inputs.
        $('.controlsEdit').remove();

        var e = document.createElement('div');
        e.classList.add('controlsEdit');

        var htmlData = "<input type=\"hidden\" maxlength=\"2000\" name=\"editFeaturesetName\" id=\"editFeaturesetName\">" +
                       "<input type=\"hidden\" maxlength=\"2000\" name=\"oldGraphName\" id=\"oldGraphName\">" +
                       "<input type=\"hidden\" maxlength=\"2000\" name=\"fontsize\" id=\"fontsize\">" + 
                       "<div class=\"edit-form-arguments\">" +
                       "<input type=\"hidden\" maxlength=\"2000\" name=\"editArgument[]\">" +
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editLabel[]\">" +
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editConclusion[]\">" +
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editWeight[]\">" +
                       "</div>" +
                       "<div class=\"edit-form-graph\">" +
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editSourceLabel[]\">" +
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editTargetLabel[]\">" + 
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editTypeLabel[]\">" + 
                       "</div>" +
                       "<div class=\"edit-form-position\">" +
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editX[]\">" +
                       "<input type=\"hidden\" maxlength=\"40\" name=\"editY[]\">" +
                       "</div>";
        e.innerHTML = htmlData;

        document.getElementById("editGraphForm").append(e);

        edges.forEach(function(d) {
            // Update form with new edges. Not sure how to access new edges outside here
            $('input[name^="editSourceLabel"]').last().attr("value", d.source.title);
            $('input[name^="editTargetLabel"]').last().attr("value", d.target.title);
            $('input[name^="editTypeLabel"]').last().attr("value", d.type);

            var controlForm = $('.controlsEdit'),
            currentEntry = $('.edit-form-graph:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input').val('');
        });

        nodes.forEach(function(d) {
            $('input[name^="editX"]').last().attr("value", d.x);
            $('input[name^="editY"]').last().attr("value", d.y);

            var premiseAndConclusion = String(d.tooltip).split(" -> ");

            $('input[name^="editArgument"]').last().attr("value", premiseAndConclusion[0]);
            if (premiseAndConclusion.length == 2) {
                $('input[name^="editConclusion"]').last().attr("value", premiseAndConclusion[1]);
            } else {
                $('input[name^="editConclusion"]').last().attr("value", "NULL");
            }
            $('input[name^="editLabel"]').last().attr("value", d.title);

            $('input[name^="editWeight"]').last().attr("value", d.weight);

            var controlForm = $('.controlsEdit'),
            currentEntry = $('.edit-form-position:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input').val('');

            currentEntry = $('.edit-form-arguments:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input').val('');
        });

        var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex,
        featureset = select.options[i].text;

        document.getElementById('editFeaturesetName').value = featureset;

        var select = document.getElementById('editFeaturesetgraph'),
        i = select.selectedIndex;
        var oldGraphName = select.options[i].text;

        document.getElementById('oldGraphName').value = oldGraphName;

        if (getCurrentName) {
            var select = document.getElementById('editFeaturesetgraph'),
            i = select.selectedIndex;
            currentGraph = select.options[i].text;

            document.getElementById('editGraphName').value = currentGraph;
        } else {
            document.getElementById('editGraphName').value = currentName;
        }

        var fontSize;
        for (var i = 0; i < graphs_.length; i++) {
            if (graphs_[i].featureset == featureset && graphs_[i].name == document.getElementById('editFeaturesetgraph').value) {
                document.getElementById('fontsize').value = String(graphs_[i].font_size);
                break;
            }
        }
    }

    // Add node using Add button
    GraphCreator.prototype.addEditionArgument = function() {

        var thisGraph = this;
        thisGraph.circles.each(function(d) {

            if (thisGraph.state.selectedNode != null) {
                var newLabel = document.getElementById('editLabel').value;
                var newToolTip = document.getElementById('editCurrentArgument').value;
                var newWeight = document.getElementById('editWeight').value;

                if(d.id == thisGraph.state.selectedNode.id) {
                    d.title = newLabel;
                    d.tooltip = newToolTip;
                    d.weight = newWeight;

                    thisGraph.styleTitle(d3.select(this), d.title);
                    d3.select(this).classed(thisGraph.consts.selectedClass, false);

                    d3.select(this).selectAll("text").remove();
                    d3.select(this).append("text");

                    thisGraph.styleTitle(d3.select(this), d.title);

                    thisGraph.state.selectedNode = null;
                    thisGraph.updateGraph();
                }
            }
        });

        document.getElementById("editAddArgument").disabled = true;

        document.getElementById("editNewArgument").disabled = false;
    }

    GraphCreator.prototype.zoomed = function(){
        this.state.justScaleTransGraph = true;
        d3.select("." + this.consts.graphClass)
        .attr("transform", "translate(" + d3.event.translate + ") scale(" + d3.event.scale + ")");

        this.zoomScale = d3.event.scale;
    };

    GraphCreator.prototype.updateGraphDataFromServer = function() {

        var xhrLevels = new XMLHttpRequest();
        xhrLevels.onreadystatechange = function() {
            if (xhrLevels.readyState == XMLHttpRequest.DONE) {
                levels_ = JSON.parse(xhrLevels.responseText);
            }
        }
        xhrLevels.open('GET', 'index.php/levels', true);
        xhrLevels.send(null);

        var xhrGraphs = new XMLHttpRequest();
        xhrGraphs.onreadystatechange = function() {
            if (xhrGraphs.readyState == XMLHttpRequest.DONE) {
                graphs_ = JSON.parse(xhrGraphs.responseText);
            }
        }
        xhrGraphs.open('GET', 'index.php/featuresetGraphs', true);
        xhrGraphs.send(null);

        var xhrArgs = new XMLHttpRequest();
        xhrArgs.onreadystatechange = function() {
            if (xhrArgs.readyState == XMLHttpRequest.DONE) {
                args_ = JSON.parse(xhrArgs.responseText);
            }
        }
        xhrArgs.open('GET', 'index.php/featuresetArguments', true);
        xhrArgs.send(null);

        var xhrConclusions = new XMLHttpRequest();
        xhrConclusions.onreadystatechange = function() {
            if (xhrConclusions.readyState == XMLHttpRequest.DONE) {
                conclusions_ = JSON.parse(xhrConclusions.responseText);
            }
        }
        xhrConclusions.open('GET', 'index.php/conclusionsByFeatureset', true);
        xhrConclusions.send(null);

        var xhrAttributesByFeatureset = new XMLHttpRequest();
        xhrAttributesByFeatureset.onreadystatechange = function() {
            if (xhrAttributesByFeatureset.readyState == XMLHttpRequest.DONE) {
                attributesByFeatureset_ = JSON.parse(xhrAttributesByFeatureset.responseText);
            }
        }
        xhrAttributesByFeatureset.open('GET', 'index.php/attributesByFeatureset', true);
        xhrAttributesByFeatureset.send(null);

        conclusionsByFeatureset_ = conclusions_;
    }

    GraphCreator.prototype.updateWindow = function(svg){
        var docEl = document.documentElement,
            bodyEl = document.getElementById('left-side');
        var x = bodyEl.offsetWidth * 0.98; //Size of div column
        var y = window.innerHeight|| docEl.clientHeight|| bodyEl.clientHeight;
        y = y * 0.85;
        svg.attr("width", x).attr("height", y);
    };

    /**** MAIN ****/

    var docEl = document.documentElement,
        bodyEl = document.getElementById('left-side');

    var zoomScale = 1;

    var width = bodyEl.offsetWidth * 0.98, //Size of div column
        height =  window.innerHeight || docEl.clientHeight|| bodyEl.clientHeight;

    height = height * 0.85;

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

    var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex;

    var currentFeatureset;
    if (i != -1) {
        currentFeatureset = select.options[i].text;
    }

    var select = document.getElementById('editFeaturesetgraph'),
        i = select.selectedIndex;

    var mostLeftNode,
        mostRightNode,
        mostUpNode,
        mostBottomNode;

    if (i != -1) {
        var currentGraph = select.options[i].text;

        // Get semantic and edges
        for (var i = 0; i < graphs_.length; i++) {
            if (graphs_[i].featureset == currentFeatureset && graphs_[i].name == currentGraph) {
                jsonEdges = JSON.parse(graphs_[i].edges);
                semantic = graphs_[i].semantic;
            }
        }

        var empty = true;
        for (var i = 0; i < args_.length; i++) {
            if (args_[i].featureset == currentFeatureset && args_[i].graph == currentGraph) {
                empty = false;
                var tooltip;
                if (args_[i].conclusion && args_[i].conclusion != "NULL") {
                    tooltip = args_[i].argument + " -> " + args_[i].conclusion;
                } else {
                    tooltip = args_[i].argument;
                }
                
                nodes.push({id: id, title: args_[i].label, x: parseFloat(args_[i].x), y: parseFloat(args_[i].y), tooltip: tooltip, weight: args_[i].weight})

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

        if (! empty) {
            viewX = mostLeftNode - 100;
            viewY = mostBottomNode - 100;

            viewWidth = (mostRightNode - mostLeftNode) + 200;
            viewHeight = (mostUpNode - mostBottomNode) + 400;
        }

        for (var i = 0; i < jsonEdges.length; i++) {
            //FIXME: find a more intelligent way of getting the indexes by the labels
            var indexSource = 0,
                indexTarget = 0;

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

            if (jsonEdges[i].type != undefined) {
                edges.push({source: nodes[indexSource], target: nodes[indexTarget], type: jsonEdges[i].type});
            } else {
                edges.push({source: nodes[indexSource], target: nodes[indexTarget], type: "none"});
            }
        }
    }
    /** MAIN SVG **/
    var svg = d3.select("#left-side").append("svg")
            .attr("width", width)
            .attr("height", height)
            .attr("id", "G")
            .attr('viewBox', viewX + ' ' + viewY + ' ' + viewWidth + ' ' + viewHeight);
    var graph = new GraphCreator(svg, nodes, edges);
    graph.setIdCt(id);
    // True indicates first call
    graph.updateGraph(true);

    var fontSize;
    var currentGraph = document.getElementById('editFeaturesetgraph').value;
    var select = document.getElementById('editFeatureset'),
    i = select.selectedIndex;

    if (i != -1) {
        currentFeatureset = select.options[i].text;
        for (var i = 0; i < graphs_.length; i++) {
            if (graphs_[i].featureset == currentFeatureset && graphs_[i].name == currentGraph) {
                var thisGraph = graph;
                thisGraph.circles.each(function(d) {
                    thisGraph.setFontSize(d3.select(this), d.title, graphs_[i].font_size);
                });
                break;
            }
        }
    }

    // It is only possible to edit after selecting a node
    document.getElementById("editAddArgument").disabled = true;

    return graph;
}

var graph;
var pathID = 1;
// Method to create new graph if featureset or graph changes.
function newEditionGrah() {
    d3.select("#G").remove();
    graph = createEditionGraph(window.d3, window.saveAs, window.Blob);

    var select = document.getElementById('editFeaturesetgraph'),
    i = select.selectedIndex;

    if (i != -1) {
        currentGraph = select.options[i].text;
        document.getElementById('editGraphName').value = currentGraph;
    } else {
        document.getElementById('editGraphName').value = "";
    }
}

d3.select("#editAddArgument").on("click", function() {
    d3.event.preventDefault();

    if (document.getElementById('editLabel').value == "") {
        window.alert("Please fill the label field.", "Label error");
    } else if (document.getElementById('editCurrentArgument').value == "") {
        window.alert("Please add at least one premise.", "Premise error");
    } else {
        graph.addEditionArgument();
        document.getElementById('editCurrentArgument').value = "";
        document.getElementById('editLabel').value = "";

        var conclusionsList = document.getElementById('editConclusions');
        // Check if there is a premise before adding any conclusion
        for (var i = 0; i < conclusionsList.options.length; i++) {
            if (conclusionsList.options[i].text == "None") {
                conclusionsList.selectedIndex = i;
                document.getElementById("editConclusionFrom").innerHTML = "none";
                document.getElementById("editConclusionTo").innerHTML = "none";
                document.getElementById('editInvertRange').disabled = true;
                break;
            }
        }

        document.getElementById('warningArgument').innerHTML = '';
        document.getElementById('warningArgument').hidden = true;
    }

    $('#myModal').modal('hide');
});

$("#modalAttackType").on("hidden.bs.modal", function () {
    graph.removeSelectFromEdge();
    graph.updateGraph();
});

        
d3.select("#rebuttals-input").on("click", function(){
    d3.event.preventDefault();
    graph.addRebuttals();
});

d3.select("#editNewArgument").on("click", function() {
    d3.event.preventDefault();

    if (document.getElementById('editLabel').value == "") {
        window.alert("Please fill the label field.", "Label error.");
    } else if (document.getElementById('editCurrentArgument').value == "") {
        window.alert("Please add at least one premise.", "Premise error.");
    // Call graph.addArgument. It might return false in case there is a
    // node with the same name.
    } else if (graph.addArgument()) {
        document.getElementById('editCurrentArgument').value = "";
        document.getElementById('editLabel').value = "";

        var conclusionsList = document.getElementById('editConclusions');
        // Check if there is a premise before adding any conclusion
        for (var i = 0; i < conclusionsList.options.length; i++) {
            if (conclusionsList.options[i].text == "None") {
                conclusionsList.selectedIndex = i;
                break;
            }
        }

        $('#myModal').modal('hide');
        document.getElementById('warningArgument').innerHTML = '';
        document.getElementById('warningArgument').hidden = true;
    } else {
        window.alert("Label already used. Please change label.", "Label error.");
    }
});

// Save new graph version
d3.select("#editRenameGraph").on("click", function() {
    d3.event.preventDefault();
    graph.saveGraph(false);

    var select = document.getElementById('editFeatureset'),
    i = select.selectedIndex,
    featureset = select.options[i].text;

    document.getElementById('editFeaturesetName').value = featureset;

    var select = document.getElementById('editFeaturesetgraph'),
    i = select.selectedIndex;
    var oldGraphName = select.options[i].text;

    document.getElementById('oldGraphName').value = oldGraphName;

    var fontSize;
    for (var i = 0; i < graphs_.length; i++) {
        if (graphs_[i].featureset == featureset && graphs_[i].name == document.getElementById('editFeaturesetgraph').value) {
            document.getElementById('fontsize').value = String(graphs_[i].font_size);
            break;
        }
    }

    if (document.getElementById('editGraphName').value == "") {
        window.alert("Please fill the graph name before saving.", "Graph name error.");
    } else if (graph.nodes.length == 0) {
        window.alert("Please add at lease one argument before saving.", "No arguments error.");
    } else {
        // Submit instead of assynchronous request because it is necessary
        // to update graph list
        document.getElementById("editGraphForm").submit();
    }
});


d3.select('#uploadNewJSONGraph').on("click", function() {
    d3.event.preventDefault();

    var jsonCode = document.getElementById('jsoncodegraph').value;

    if (graph.nodes.length > 0) {
        window.alert("You can only upload JSON code for empty graphs. Greate a new graph and upload the code.");
        return
    }

    if(jsonCode.length == 0) {
        window.alert("Please enter some code in the text area.", "Empty code error.");
        return;
    }
    
    var graphParse;
    try {
        graphParse = JSON.parse(jsonCode);
    } catch(err) {
        window.alert("Syntax error in imported code. Please check your JSON syntax.", "Syntax error.");
        return;
    }

    if (! graphParse.hasOwnProperty('nodes') || ! graphParse.hasOwnProperty('edges')) {
        window.alert("Your JSON needs a nodes array and an edges array. Please check example.", "Syntax error.");
        return;
    }

    graph.addJSONGraph(graphParse);

    $('#modalUploadGraph').modal('hide');
    document.getElementById('jsoncodegraph').value = "";

});

// Save new graph version
d3.select("#editCopyGraph").on("click", function() {
    d3.event.preventDefault();
    // Check if copy name already exists
    var currentGraph = document.getElementById('copyNameGraph').value;

    var select = document.getElementById('editFeatureset'),
    i = select.selectedIndex,
    featureset = select.options[i].text;

    for (var i = 0; i < graphs_.length; i++) {
        if (graphs_[i].featureset == featureset && graphs_[i].name == currentGraph) {
            window.alert("Graph name for current featureset already exists. Please choose another graph name.", "Duplicate name error.")
            document.getElementById('newGraph').value = "";
            return;
        }
    }

    graph.saveGraph();
    // Save graph will reset editGraphForm so it is necessary to recover the
    // copy name.
    document.getElementById('copyNameGraph').value = currentGraph;

    document.getElementById('editFeaturesetName').value = featureset;

    // Empty old graph name means it is a copy
    document.getElementById('oldGraphName').value = "";

    if (document.getElementById('copyNameGraph').value == "") {
        window.alert("Please fill the graph name before saving.", "Empty name error.");
    } else if (graph.nodes.length == 0) {
        window.alert("Please add at lease on argument before saving.", "No arguments error.");
    } else {
        document.getElementById("editGraphForm").submit();
    }
});

d3.select('#editDeleteNode').on("click", function() {
    d3.event.preventDefault();
    
    confirm("Are you sure you want to delete this node?", "Confirm deletion.", "OK", function (result) {
        if (! result) {
            return;
        }
        
        var state = graph.state;
        var selectedNode = state.selectedNode;
        graph.nodes.splice(graph.nodes.indexOf(selectedNode), 1);
        graph.spliceLinksForNode(selectedNode);
        state.selectedNode = null;
        graph.updateGraph();
        $('#myModal').modal('hide');
    });
});


d3.select('#editDeleteEdge').on("click", function() {

    graph.edges.splice(graph.edges.indexOf(graph.state.selectedEdge), 1);
    graph.state.selectedEdge = null;
    graph.updateGraph();

    $('#modalAttackType').modal('hide');
});

d3.select("#editDeletGraph").on("click", function() {
    d3.event.preventDefault();

    var select = document.getElementById('editFeaturesetgraph'),
    i = select.selectedIndex;
    var graphName = select.options[i].text;
    
    
    confirm("Are you sure you want to delete " + graphName + " from the server?", "Confirm deletion.", "OK", function (result) {
        if (! result) {
            return;
        }
        
        var select = document.getElementById('editFeatureset'),
        i = select.selectedIndex,
        featureset = select.options[i].text;

        document.getElementById('featuresetName').value = featureset;
        document.getElementById('graphName').value = graphName;

        document.getElementById("deleteGraphForm").submit();
    });
});

d3.select("#new-empty-graph").on("click", function(event){
    $('#modalNewGraph').modal('show');
});

d3.select("#rename-graph").on("click", function(event){
    $('#modalUpdateGraph').modal('show');
});

d3.select("#exapand-graph").on("click", function(event){

    d3.event.preventDefault();
    expand = ! expand;

    if (expand) {
        $("#editFeatureset").fadeOut(500);// hide("slide", { direction: "right" }, 1000);
        $("#editFeaturesetgraph").fadeOut(500);
        $("#editFeaturesetLabel").fadeOut(500);// hide("slide", { direction: "right" }, 1000);
        $("#editFeaturesetgraphLabel").fadeOut(500);
        setTimeout(expandGraph, 501);
    } else {
        $("#editFeatureset").fadeIn(500);// hide("slide", { direction: "right" }, 1000);
        $("#editFeaturesetgraph").fadeIn(500);
        $("#editFeaturesetLabel").fadeIn(500);// hide("slide", { direction: "right" }, 1000);
        $("#editFeaturesetgraphLabel").fadeIn(500);
        document.getElementById("exapand-graph").src = "right.png";
    }

    function expandGraph() {
            document.getElementById("exapand-graph").src = "left.png";
    }
});

d3.select("#zoomin").on("click", function(event){

    var zoom = d3.behavior.zoom()
    .on('zoom', function(){
        graph.zoomed.call(graph);
    });

    graph.svg.call(zoom);

    graph.zoomScale *= 1.1;
    zoom.scale(graph.zoomScale);
    zoom.event(graph.svg);
});

d3.select("#zoomout").on("click", function(event){

    var zoom = d3.behavior.zoom()
    .on('zoom', function(){
        graph.zoomed.call(graph);
    });

    graph.svg.call(zoom);

    graph.zoomScale *= 0.9;
    zoom.scale(graph.zoomScale);
    zoom.event(graph.svg);
});

d3.select("#aplus").on("click", function(event){
    var fontSize;
    var currentGraph = document.getElementById('editFeaturesetgraph').value;
    var select = document.getElementById('editFeatureset'),
    i = select.selectedIndex,
    currentFeatureset = select.options[i].text;
    for (var i = 0; i < graphs_.length; i++) {
        if (graphs_[i].featureset == currentFeatureset && graphs_[i].name == currentGraph) {
            graphs_[i].font_size = String(parseInt(graphs_[i].font_size) + 2);
            fontSize = graphs_[i].font_size;
            break;
        }
    }

    var thisGraph = graph;
    thisGraph.circles.each(function(d) {
        thisGraph.setFontSize(d3.select(this), d.title, fontSize);
    });
});

d3.select("#aminus").on("click", function(event){
    var fontSize;
    var currentGraph = document.getElementById('editFeaturesetgraph').value;
    var select = document.getElementById('editFeatureset'),
    i = select.selectedIndex,
    currentFeatureset = select.options[i].text;
    for (var i = 0; i < graphs_.length; i++) {
        if (graphs_[i].featureset == currentFeatureset && graphs_[i].name == currentGraph) {
            if (parseInt(graphs_[i].font_size) - 2 >= 2){
                graphs_[i].font_size = String(parseInt(graphs_[i].font_size) - 2);
                fontSize = graphs_[i].font_size;
            }
            break;
        }
    }

    var thisGraph = graph;
    thisGraph.circles.each(function(d) {
        thisGraph.setFontSize(d3.select(this), d.title, fontSize);
    });
});

var refreshIntervalId;
$("#automaticallysave").change(function(event) {
    var checkbox = event.target;
    if (checkbox.checked) {
        timeInterval = 1000;
        refreshIntervalId = setInterval(function(){ 
            graph.saveGraph();

            var oReq = new XMLHttpRequest();
            oReq.open("POST", "index.php?action=update", true);

            // Couldn't implement a error handler. I believe after the response
            // being received the page taked time to return status 200, which
            // fires this error multiple times before getting status 200.
            oReq.onreadystatechange = function() {
                if (oReq.readyState == 4 && oReq.status == 200) {
                    graph.updateGraphDataFromServer();
                }
            };

            oReq.send(new FormData(document.getElementById("editGraphForm")));

            document.getElementById('warningsaved').innerHTML = "";
            document.getElementById('save-graph').hidden = true;
        }, timeInterval);
        //code goes here that will be run every 5 seconds.    
    } else {
        clearInterval(refreshIntervalId);
    }
});

$("#save-graph").on("click", function(){
    graph.saveGraph();

    var oReq = new XMLHttpRequest();
    oReq.open("POST", "index.php?action=update", true);

    oReq.onreadystatechange = function() {
        if (oReq.readyState == 4 && oReq.status == 200) {
            graph.updateGraphDataFromServer();
        }
    };

    var formData = new FormData(document.getElementById("editGraphForm"));
    //for(var pair of formData.entries()) {
    //console.log(pair[0]+ ', '+ pair[1]); 
    //}
    oReq.send(formData);

    document.getElementById('warningsaved').innerHTML = "";
    document.getElementById('save-graph').hidden = true;
});


document.getElementById("updateAttack").addEventListener("click", function(event){
    event.preventDefault();
    graph.updateEdge();
});










