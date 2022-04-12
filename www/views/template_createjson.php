<div class="container">

  <div class="page-header">
    <h1>Create feature set &nbsp &nbsp
    <button type="submit" form="form1" class="btn btn-primary"> <span class="glyphicon glyphicon glyphicon-floppy-disk"></span>&nbsp Save feature set</button>
    </h1>
  </div>
  <br>

   <form class="form-horizontal" action="index.php?action=insertjson" id="form1" method="post" role="form">
    <div class="form-group">
      <label for="comment">JSON code:</label>
      <textarea class="form-control" rows="10" name="jsonfile" id="jsonfile"></textarea>
    </div>
  </form>
  
      <div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
        </button>
        <b>Create your data set using a json file.<br></b>
           <ul> 
           <li>You can add as many attributes as you want.</li>
           <li>For each attribute you can add as many levels as you want.</li>
           <li>Each level has a range.</li>
           <li>Ranges of the same attribute can not overlap.</li>
           <li>Levels of the same attribute can not have the same name.</li>
           </ul>
        <br>
        A small example is given below:<br>
        <pre>
        {"featureset":"aaaqw",
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
            "conclusions":[[{"category":"underload"},
                            {"from":"0"},
                            {"to":"30"}],
                           [{"category":"fitting"},
                            {"from":"30"},
                            {"to":"60"}],
                           [{"category":"overload"},
                            {"from":"60"},
                            {"to":"100"}]]
        }</pre>
        The code above is equivelent to the feature set below:<br>
        <img src="/lucas/views/dataset.jpg" class="img-responsive img-rounded" alt="Example featureset">
    </div>
</div>