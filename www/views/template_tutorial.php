<div class="container">
    <div class="page-header">
        <h2>Tutorial<!-- <small> with a reasoning example</small>--></h2>
    </div>

    <p>This argumentation framework has been proposed as a tool to perform automated reasoning with numerical data. It is able to use boolean logic for the creation of if-then rules and attacking rules. In turn, these
    rules can be activated by data, have their attacks solved, and finally aggregated in different fashions in order to produce a prediction (a number). This process works in the following order:
    <ul>
    <li> <b>(1)</b> feature set creation; </li>
    <li> <b>(2)</b> creation of rules and attacks employing the features created in (1), which results in an <i>argumentation graph</i>; </li>
    <li> <b>(3)</b> instantiation of graph(s) from (2) with numerical data and computation of predictions. </li> 
    </ul>
    Each step is detailed below. 
    
  <!--  A toy example wil be used to exemplify this process. In this example some features and rules are designed in order to make an approximate prediction of the cognitive cost of an idividual performing a web-task. The creation of features and rules, followed by intermediare steps until the achievement of a prediction is called here a <mark><i>reasoning process</i></mark>, and it is documented below.-->
    
    </p>
    
    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#menu0">Feature set</a></li>
    <li><a data-toggle="tab" href="#menu1">Graphs</a></li>
    <li><a data-toggle="tab" href="#menu2">Compute graphs</a></li>
  </ul>

  <div class="tab-content">
    <div id="menu0" class="tab-pane fade in active">
      <h3>Feature set</h3>
      <p><!--The first requirement for using this framework is to have some numerical data which you would like to use to perform some kind of prediction. It is the same as an   
      <a href="#" data-toggle="popover" data-content="<a href='https://en.wikipedia.org/wiki/Analytical_base_table' target='_blank'>https://en.wikipedia.org/wiki/Analytical_base_table</a>">Analytical Base Table</a> employed in machine learning. In our running example, we have the following data:
      
      <div class="table-responsive">          
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Editions</th>
                <th>Comments Ratio</th>
                <th>Bytes</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>Anna</td>
                <td>1</td>
                <td>1</td>
                <td>4000</td>
            </tr>
            <tr>
                <td>2</td>
                <td>John</td>
                <td>80</td>
                <td>0.02</td>
                <td>100</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Mike</td>
                <td>300</td>
                <td>0.7</td>
                <td>8000</td>
            </tr>
            </tbody>
        </table>
    </div>
    
    It exemplifies three Wikipedia editors and three features related to their past activities. <mark><i>Editions</i></mark> is the number of editions performed by each of them; <mark><i>Comments ratio</i></mark> is the ratio of editions that included a comment for other editors (a number between 0 and 1); and <mark><i>Bytes</i></mark> is the sum of bytes added by all their editions. -->
    
      </p>
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Graphs</h3>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Compute graphs</h3>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
  </div>
</div>


<script>
$(document).ready(function(){
  $('[data-toggle="popover"]').popover({html:true});
});
</script>
