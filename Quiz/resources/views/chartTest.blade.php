<!DOCtype html>
<!--THIS IS A TEST/EXAMPLE VIEW FOR D3 GRAPHS -->
<html>
<head>
  <meta charset="utf-8">
  <script type="text/javascript" src="d3/d3.min.js"></script>
  <style>
  .bar{
    fill:CornflowerBlue;
  }
  .bar:hover{
    fill:yellow;
  }
  .axis--x path{
    display: none;
  }
  .tooltip {
    position: absolute;
    width: 200px;
    height: auto;
    display: none;
    padding: 10px;
    background-color: #eee;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    -webkit-box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.4);
    -mox-box-shadow: 4px 4px 4px 10px rgba(0, 0, 0, 0.4);
    box-shadow: 4px 4px 10px rbga(0, 0, 0, 0.4) pointer-events: none;
    z-index: 10;
  }
  #barChart{
    position: relative;
  }
  </style>
</head>
<body>
  <div id="barChart">
    <svg width="800" height="500"></svg>
  </div>
</body>
<script>
(function (d3){
  'use strict';
  var barSvg = d3.select("svg"),
      margin = {top: 20, right: 20, bottom: 30, left: 40},
      width = +barSvg.attr("width") - margin.left - margin.right,
      height = +barSvg.attr("height") - margin.top - margin.bottom;

  var x = d3.scaleBand().rangeRound([0, width]).padding(0.1),
      y = d3.scaleLinear().rangeRound([height, 0]);

  var g = barSvg.append('g')
      .attr("transform","translate(" + margin.left + "," + margin.top + ")");

      //tooltip
      var toolTip = d3.select('#barChart')
      .append('div')
      .attr('class','tooltip');

      toolTip.append('div')
      .attr('class','IntakeName');

      toolTip.append('div')
      .attr('class','count');


  /*d3.json("intakes.json",function(d){
    d.count = +d.count;
    return d;
  },*/
  d3.json("/intakesd3", function(error, data){
    if (error) throw error;

    x.domain(data.map(function(d){return d.IntakeName;})); //IntakeName
    y.domain([0,d3.max(data, function(d){return d.count;})]); //count

    g.append("g")
       .attr("class", "axis_axis--x")
       .attr("transform","translate(0," + height + ")")
       .call(d3.axisBottom(x));

    g.append("g")
       .attr("class", "axis axis--y")
       .call(d3.axisLeft(y).ticks(5)) //removed %
       .append("text")
       .attr("transform","rotate(-90)")
       .attr("y",6)
       .attr("dy","0.72em")
       .attr("text-anchor","end")
       .text("Average Mark");

    var bars = g.selectAll(".bar")
    .data(data)
    .enter()
    .append("rect")
    .attr("class","bar")
    .attr("x", function(d) {return x(d.IntakeName);})
    .attr("y",function(d){return y(d.count);})
    .attr("width",x.bandwidth())
    .attr("height",function(d){return height - y(d.count);});

    //mouseover function
    bars.on('mouseover', function(d) {
      var total = d3.sum(data.map(function(d){
        return d.count;  // updated from return d.count;
      }));
      //added tooltip position variables
      var x = d3.event.layerX;
      var y = d3.event.layerY;
      //...and tooltip positioning
      toolTip.style("left",x +"px")
      .style("top",y+"px");

      toolTip.select('.IntakeName').html(d.IntakeName);
      toolTip.select('.count').html(d.count +" Students Enrolled");
      toolTip.style('display', 'block');
            });

    bars.on('mouseout', function(d) {
     toolTip.style('display','none');
            });

    bars.on('mousemove', function(d) {
           tooltip.style('top', (d3.event.layerY + 10) + 'px')
             .style('left', (d3.event.layerX + 10) + 'px');
         });

  });
})(window.d3);

</script>
</html>
