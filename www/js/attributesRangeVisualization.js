function drawBars(position, attribute, min, max) {
    "use strict";

    var margin = 10;

    var bodyEl = document.getElementById(String(position));

    var width = bodyEl.offsetWidth;

    var range = max - min;

    var height = 25;
    var rate = (width - margin) / (max - min);

    // Create svg box
    var svg = d3.select("#" + position).append("svg")
            .attr("width", width - margin)
            .attr("height", height + 2);

    // Order each attribute by range from
    // The attribute has the form "a1,a2,a3,b1,b2,b3...". All variables 1 are
    // attribute levels. All variables 2 are attributes range from. All variables
    // 3 are attributes range to.
    var aux;

    // -2 to remove the last range from in the array
    for (var i = 1; i < attribute.length - 2; i = i + 3)
    {
        var min = i;
        // +3 is the next range from in the array
        for (var j = i + 3; j < attribute.length; j = j + 3) {
            if(parseFloat(attribute[j]) < parseFloat(attribute[min])) {
                min = j;
            }
        }

        // Move a hole attribute in the array
        if (i != min) {
            aux = attribute[i];
            attribute[i] = attribute[min];
            attribute[min] = aux;

            aux = attribute[i - 1];
            attribute[i - 1] = attribute[min - 1];
            attribute[min - 1] = aux;

            aux = attribute[i + 1];
            attribute[i + 1] = attribute[min + 1];
            attribute[min + 1] = aux;
        }
    }

    // Sequence of colors to be applied for each range. If there are more
    // ranges than colors the index will start to be reduced by one.
    //var colors = ["#fcfbfd","#efedf5","#dadaeb","#bcbddc","#9e9ac8","#807dba","#6a51a3","#54278f","#3f007d"];
    var colors = ["#9e9ac8","#807dba","#6a51a3","#54278f","#3f007d"];

    // Data used to draw svg rectangles
    var data = "[";
    // Index of color applied for each bar
    var iColor = 0;
    // Flog to indicate whether index is going up or down
    var colorUp = true;

    for (var level = 0; level < attribute.length; level = level + 3) {
        data += "{\"level\": \"" + attribute[level] +
                 "\", \"from\":\"" + attribute[level + 1] + 
                 "\", \"to\":\"" + attribute[level + 2] + 
                 "\", \"color\":\"" +  colors[iColor] + "\"}";

        if (level + 3 < attribute.length) {
            data += ",";
        }

        if (colorUp) {
            if (iColor < colors.length - 1) {
                iColor++;
            } else {
                iColor--;
                colorUp = false;
            }
        } else {
            if (iColor > 0) {
                iColor--;
            } else {
                iColor++;
                colorUp = true;
            }
        }
    }

    data += "]";

    data = JSON.parse(data);

    // Div for the tooltip of each bar
    var div = d3.select("body").append("div")
        .attr("class", "tooltipBar")
        .style("opacity", 0);

    // Group for rectangles and texts
    var bars = svg.selectAll("g")
                .data(data)
                .enter()
                .append("g");

    bars.append("rect")
        .attr("class", "bar")
        .attr("x", function(d) {
            return d.from * rate;
        })
        .attr("width", function(d) {
            return (d.to - d.from) * rate;
        })
        .attr("height", height)
        .on("mouseover", function(d) {
            div.transition()
                .duration(200)
                .style("opacity", .9);
            div.html(d.level)
                .style("left", (d3.event.pageX) + "px")
                .style("top", (d3.event.pageY - 28) + "px");

            d3.select(this).style("fill", "brown");
        })
        .on("mouseout", function(d) {
            div.transition()
                .duration(500)
                .style("opacity", 0);

            d3.select(this).style("fill", function(d) {
                return d.color;
            });
        })
        .style("fill", function(d) {
            return d.color;
        });

    bars.append("text")
        .attr("x", function(d) { 
            if (d.from < 10) {
                return d.from * rate + 9; 
            } else {
                return d.from * rate + 15; 
            }
        })
        .attr("y", height / 2)
        .attr("class", "bar-text")
        .attr("dy", ".35em")
        .text(function(d) { return d.from; });

    bars.append("text")
        .attr("x", function(d) { 
            if (d.to < 10) {
                return d.to * rate - 4; 
            } else {
                return d.to * rate - 4; 
            }
        })
        .attr("y", height / 2)
        .attr("class", "bar-text")
        .attr("dy", ".35em")
        .text(function(d) { return d.to; });
}








