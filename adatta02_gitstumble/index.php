<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>StumbleHub</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">

<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="underscore-min.js"></script>
<script type="text/javascript" src="backbone-min.js"></script>
</head>

<body>

<div class="container" id="templateContainer">

</div>
 
<script type="text/template" id="repositoryTemplate">
<div class="repository">
	<h3><a href='<%= url %>' data-action='repo-link'><%= name %></a></h3>
	<ul class="listless inline">
		<li><i class="icon-eye-open"></i> <%= watchers %></li>
		<li> | </li>
		<li><i class="icon-random"></i> <%= forks %></li>
		<li> | </li>
		<li> <%= language %></li>
		<%= visited ? "<li> | </li><li><i class='icon-check'></i> </li>" : "" %>
	</ul>
</div>
</script>

<script type="text/template" id="appViewTemplate">
<div class="row">
	<div class="span3 main-sidebar">
        <div class="main-sidebar-inner">
		   <div class="banner centered"><h3>Repositories (<%= repositoryList.getUnvisitedCount() %>)</h3></div>
           <div class="alert alert-notice">
				<a class='btn' href='#' data-action='settings'><i class='icon-cog'></i></a>
				Press <strong>R</strong> for random! 
		   </div>
		   <div class="repository-list"></div>
       </div>		
	</div>
	
	<div class="span9">
		<div class="main-window">
			<% if( this.selectedRepo ){ %>
				<h3><a target='_blank' href='https://github.com<%= this.selectedRepo.get('url') %>'>
						<%= this.selectedRepo.get('owner') %> / <%= this.selectedRepo.get('name') %></a></h3>
				<p><%= this.selectedRepo.get('description') %></p>
				<hr />
				<div class='repo-readme'><%= this.selectedRepo.get('readme') %></div>
			<% } %>
		</div>
	</div>
</div>
</script>

<script type="text/template" id="loadingTemplate">
<div class="row">
    <div class="span6 offset3 fancy-box home-container">
    	<div class="centered banner"><h1>Loading...</h1></div>
		<div class="inner centered">
			<img src="loader.gif" />
		</div>
	</div>
</div>
</script>

<script type="text/template" id="stumbleSearchTemplate">

<div class="row">
    <div class="span6 offset3 fancy-box home-container">
    <div class="centered banner">
    <h1>StumbleHub</h1>
    </div>
    <div class="inner">

    <div class="alert alert-success centered">Stumble around GitHub like a drunken sailor!</div>    

<form class="form-horizontal search-form">
<div class="control-group">
  <label class="control-label">Filter By Language</label>
  <div class="controls">
  <select name="language">
    <option value="">Any Language</option>
    <optgroup label="Popular">
      <option value="ActionScript">ActionScript</option>
      <option value="C">C</option>
      <option value="C#">C#</option>
      <option value="C++">C++</option>
      <option value="Common Lisp">Common Lisp</option>
      <option value="CSS">CSS</option>
      <option value="Diff">Diff</option>
      <option value="Emacs Lisp">Emacs Lisp</option>
      <option value="Erlang">Erlang</option>
      <option value="Haskell">Haskell</option>
      <option value="HTML">HTML</option>
      <option value="Java">Java</option>
      <option value="JavaScript">JavaScript</option>
      <option value="Lua">Lua</option>
      <option value="Objective-C">Objective-C</option>
      <option value="Perl">Perl</option>
      <option value="PHP">PHP</option>
      <option value="Python">Python</option>
      <option value="Ruby">Ruby</option>
      <option value="Scala">Scala</option>
      <option value="Scheme">Scheme</option>
      <option value="TeX">TeX</option>
      <option value="XML">XML</option>
    </optgroup>
    <optgroup label="Everything else">
      <option value="Ada">Ada</option>
      <option value="Apex">Apex</option>
      <option value="AppleScript">AppleScript</option>
      <option value="Arc">Arc</option>
      <option value="Arduino">Arduino</option>
      <option value="ASP">ASP</option>
      <option value="Assembly">Assembly</option>
      <option value="Augeas">Augeas</option>
      <option value="AutoHotkey">AutoHotkey</option>
      <option value="Batchfile">Batchfile</option>
      <option value="Befunge">Befunge</option>
      <option value="BlitzMax">BlitzMax</option>
      <option value="Boo">Boo</option>
      <option value="Brainfuck">Brainfuck</option>
      <option value="Bro">Bro</option>
      <option value="C-ObjDump">C-ObjDump</option>
      <option value="C2hs Haskell">C2hs Haskell</option>
      <option value="Ceylon">Ceylon</option>
      <option value="ChucK">ChucK</option>
      <option value="Clojure">Clojure</option>
      <option value="CMake">CMake</option>
      <option value="CoffeeScript">CoffeeScript</option>
      <option value="ColdFusion">ColdFusion</option>
      <option value="Coq">Coq</option>
      <option value="Cpp-ObjDump">Cpp-ObjDump</option>
      <option value="Cucumber">Cucumber</option>
      <option value="Cython">Cython</option>
      <option value="D">D</option>
      <option value="D-ObjDump">D-ObjDump</option>
      <option value="Darcs Patch">Darcs Patch</option>
      <option value="Dart">Dart</option>
      <option value="DCPU-16 ASM">DCPU-16 ASM</option>
      <option value="Delphi">Delphi</option>
      <option value="Dylan">Dylan</option>
      <option value="eC">eC</option>
      <option value="Ecere Projects">Ecere Projects</option>
      <option value="Eiffel">Eiffel</option>
      <option value="Elixir">Elixir</option>
      <option value="F#">F#</option>
      <option value="Factor">Factor</option>
      <option value="Fancy">Fancy</option>
      <option value="Fantom">Fantom</option>
      <option value="FORTRAN">FORTRAN</option>
      <option value="GAS">GAS</option>
      <option value="Genshi">Genshi</option>
      <option value="Gentoo Ebuild">Gentoo Ebuild</option>
      <option value="Gentoo Eclass">Gentoo Eclass</option>
      <option value="Go">Go</option>
      <option value="Gosu">Gosu</option>
      <option value="Groff">Groff</option>
      <option value="Groovy">Groovy</option>
      <option value="Groovy Server Pages">Groovy Server Pages</option>
      <option value="Haml">Haml</option>
      <option value="HaXe">HaXe</option>
      <option value="HTML+Django">HTML+Django</option>
      <option value="HTML+ERB">HTML+ERB</option>
      <option value="HTML+PHP">HTML+PHP</option>
      <option value="INI">INI</option>
      <option value="Io">Io</option>
      <option value="Ioke">Ioke</option>
      <option value="IRC log">IRC log</option>
      <option value="Java Server Pages">Java Server Pages</option>
      <option value="Julia">Julia</option>
      <option value="Kotlin">Kotlin</option>
      <option value="LilyPond">LilyPond</option>
      <option value="Literate Haskell">Literate Haskell</option>
      <option value="LLVM">LLVM</option>
      <option value="Logtalk">Logtalk</option>
      <option value="Makefile">Makefile</option>
      <option value="Mako">Mako</option>
      <option value="Markdown">Markdown</option>
      <option value="Matlab">Matlab</option>
      <option value="Max/MSP">Max/MSP</option>
      <option value="Mirah">Mirah</option>
      <option value="Moocode">Moocode</option>
      <option value="mupad">mupad</option>
      <option value="Myghty">Myghty</option>
      <option value="Nemerle">Nemerle</option>
      <option value="Nimrod">Nimrod</option>
      <option value="Nu">Nu</option>
      <option value="NumPy">NumPy</option>
      <option value="ObjDump">ObjDump</option>
      <option value="Objective-J">Objective-J</option>
      <option value="OCaml">OCaml</option>
      <option value="ooc">ooc</option>
      <option value="Opa">Opa</option>
      <option value="OpenCL">OpenCL</option>
      <option value="OpenEdge ABL">OpenEdge ABL</option>
      <option value="Parrot">Parrot</option>
      <option value="Parrot Assembly">Parrot Assembly</option>
      <option value="Parrot Internal Representation">Parrot Internal
      Representation</option>
      <option value="PowerShell">PowerShell</option>
      <option value="Prolog">Prolog</option>
      <option value="Puppet">Puppet</option>
      <option value="Pure Data">Pure Data</option>
      <option value="R">R</option>
      <option value="Racket">Racket</option>
      <option value="Raw token data">Raw token data</option>
      <option value="Rebol">Rebol</option>
      <option value="Redcode">Redcode</option>
      <option value="reStructuredText">reStructuredText</option>
      <option value="RHTML">RHTML</option>
      <option value="Rust">Rust</option>
      <option value="Sage">Sage</option>
      <option value="Sass">Sass</option>
      <option value="Scilab">Scilab</option>
      <option value="SCSS">SCSS</option>
      <option value="Self">Self</option>
      <option value="Shell">Shell</option>
      <option value="Smalltalk">Smalltalk</option>
      <option value="Smarty">Smarty</option>
      <option value="Standard ML">Standard ML</option>
      <option value="SuperCollider">SuperCollider</option>
      <option value="Tcl">Tcl</option>
      <option value="Tcsh">Tcsh</option>
      <option value="Tea">Tea</option>
      <option value="Text">Text</option>
      <option value="Textile">Textile</option>
      <option value="Turing">Turing</option>
      <option value="Twig">Twig</option>
      <option value="Vala">Vala</option>
      <option value="Verilog">Verilog</option>
      <option value="VHDL">VHDL</option>
      <option value="VimL">VimL</option>
      <option value="Visual Basic">Visual Basic</option>
      <option value="XQuery">XQuery</option>
      <option value="XS">XS</option>
      <option value="XSLT">XSLT</option>
      <option value="YAML">YAML</option>
    </optgroup>
  </select></div>
</div>

<div class="control-group">
  <label class="control-label"># Forks</label>
  <div class="controls">
    <input type="text" class="input-small" name="forks_min" value="<%= forks_min %>" />
    to
    <input type="text" class="input-small" name="forks_max" value="<%= forks_max %>" />
  </div>
</div>

<div class="control-group">
  <label class="control-label"># Followers</label>
  <div class="controls">
    <input type="text" class="input-small" name="followers_min" value="<%= followers_min %>" />
    to
    <input type="text" class="input-small" name="followers_max" value="<%= followers_max %>" />
  </div>
</div>

<div class="control-group">
  <label class="control-label">Last Push</label>
  <div class="controls">
    <input type="text" class="input-small" name="push_min" value="<%= push_min %>" placeholder="YYY-MM-DD" />
    to
    <input type="text" class="input-small" name="push_max" value="<%= push_max %>" placeholder="YYY-MM-DD" />
  </div>
</div>

<div class="control-group">
  <div class="controls">
    <ul class="listless inline auto-time">
      <li><a href="#" data-action="date-link" data-date="day">Last Day</a></li>
      <li> | </li>
      <li><a href="#" data-action="date-link" data-date="week">Last Week</a></li>
      <li> | </li>
      <li><a href="#" data-action="date-link" data-date="month">Last Month</a></li>
    </ul>
  </div>
</div>

<div class="control-group">
  <div class="controls">
    <a href="#" data-action="submit" class="btn btn-large">Stumble <i class="icon-chevron-right"></i></a>  
  </div>
</div>

</form>
   </div>
  </div>
</div>
</script>

<script type="text/javascript">

var app;

$(document).ready( function(){

    var SearchOptions = Backbone.Model.extend({
        defaults: function() {
          return {
            language: "",
            forks_min: "10",
            forks_max: "*",
            followers_min: "10",
            followers_max: "*",
            push_min: "",
            push_max: "",
            lastPage: "1"
          };
        }
    });

    var Repository = Backbone.Model.extend({
        defaults: function(){
            return {
                owner: "",
                name: "",
                description: "",
                readme: "",
                language: "",
                size: "",
                forks: "",
                watchers: "",
                last_push: "",
                visited: false,
                selected: false,
                rating: 0,
            };
        }
    });

    var RepositoryList = Backbone.Collection.extend({
        model: Repository,
        initialize: function(){
            _.bindAll(this);
        },
        getUnvisitedCount: function(){
            return this.filter(function(el){ return !el.get("visited");}).length;
        },
        lazySave: function(){
            var noReadme = this.map( function(model){ model.set("readme", ""); return model.toJSON(); } );
            window.localStorage["repositoryList"] = JSON.stringify(noReadme);
        }
    });
    
    var SearchOptionView = Backbone.View.extend({

        template: _.template( $("#stumbleSearchTemplate").text() ),

        initialize: function() {
            
            _.bindAll(this);            

            if( window.localStorage["searchOptions"] ){
                var md = JSON.parse( window.localStorage["searchOptions"] );
                this.model = new SearchOptions( md );                
            }else{
                this.model = new SearchOptions();
            }

            this.render();
        },

        render: function(){
            this.$el.html( this.template(this.model.toJSON()) );
        },

        serializeAndSubmitForm: function(){

            var model = this.model;
            $.each( this.$el.find("form").serializeArray(), function(i, obj){
                model.set( obj.name, obj.value );
            });

            window.localStorage["searchOptions"] = JSON.stringify(this.model.toJSON());
            
            this.render();
            this.model.trigger("update_repositories", this.model);                                               
        },
        
        events: {
          "submit form": function(e){
             this.serializeAndSubmitForm();
             return false;
          },
          "click [data-action='submit']": function(e){
             this.serializeAndSubmitForm();
             return false;
          },
          "click [data-action='date-link']": function(e){

              function pad(n){
                  return n<10 ? '0' + n : n;
              };
        	  var len = $(e.target).data("date");
              var fn = {
                      "month": function(){
                        var d = new Date();
                        d.setDate( d.getDate() - 30 );
                        return pad(d.getMonth()+1) + "-" + pad(d.getDate()) + "-" + d.getFullYear();
                      },
                      "week": function(){
                          var d = new Date();
                          d.setDate( d.getDate() - 7 );                          
                          return pad(d.getMonth()+1) + "-" + pad(d.getDate()) + "-" + d.getFullYear();
                      },
                      "day": function(){
                        var d = new Date();
                        d.setDate( d.getDate() - 1 );
                        return pad(d.getMonth()+1) + "-" + pad(d.getDate()) + "-" + d.getFullYear();
                      },                      
              };
              var d = fn[len].call();
              this.$el.find("[name='push_min']").val( d );
              this.$el.find("[name='push_max']").val( "*" );
              
              return false;
          }
        }
        
    });

    var App = Backbone.View.extend({

        loadingTemplate: _.template( $("#loadingTemplate").text() ),
        template: _.template( $("#appViewTemplate").text() ),
        repositoryTemplate: _.template( $("#repositoryTemplate").text() ),
        
        initialize: function() {

            _.bindAll(this);

            $(document).on("keydown", this.onKeyDown);
            
            this.searchOptionView = new SearchOptionView( {el: this.el} );
            this.searchOptionView.render();              
            this.searchOptionView.model.on("update_repositories", this.updateCollection);

            this.repositoryList = new RepositoryList();
            if( window.localStorage["repositoryList"] ){
                this.repositoryList.add( JSON.parse(window.localStorage["repositoryList"]) );
                this.render();                
            }
        },

        updateCollection: function(searchOptions, noLoading){
            
            var myView = this;            

            if( myView.isLoading ){
                return;
            }
            
            if( !noLoading ){
              myView.$el.html( myView.loadingTemplate() );
            }

            myView.isLoading = true;
            searchOptions.set("lastPage", parseInt(searchOptions.get("lastPage")) + 3);
            jQuery.getJSON("ajax.php?action=search", searchOptions.toJSON(), function(repositoryJSON){

                myView.repositoryList.add( repositoryJSON );
                myView.repositoryList.lazySave();
                
                myView.render();
                myView.isLoading = false;
            });            
            
        },

        render: function(){ 

            this.selectedRepo = this.repositoryList
                                    .find(function(el){ return el.get("selected") ? true : false;});
            
            this.$el.html( this.template(this) );
            
            this.$el.find("a").attr("target", "_blank");
            this.$el.find(".main-sidebar-inner").css("height", window.innerHeight - 45 + "px");
            
            this.$el.find(".repository-list").empty();
            var repolistEl = this.$el.find(".repository-list");
            var template = this.repositoryTemplate;
            
            this.repositoryList.each( function(model){
                var html = $( template(model.toJSON()) );
                $(html).find("a").data("model", model);
                repolistEl.append( html );
            });

        },

        selectRandomRepository: function(){

            var unvisitedCount = this.repositoryList.getUnvisitedCount();
            if( unvisitedCount == 0 ){
                alert("Woha! Looks like you've visited all the repositories.");
                return;
            }
            
            if( unvisitedCount < 20 ){
                this.updateCollection(this.searchOptionView.model, true);
            }
            
            var len = $("[data-action='repo-link']").length - 1;
            var randIndex, el, model;
            
            do {
                randIndex = Math.floor(Math.random() * len) + 1;
                el = $("[data-action='repo-link']:eq(" + randIndex + ")");
                model = el.data("model");                
            }while( model.get("visited") );

            $(el).click();
        },

        onKeyDown: function(e){            
            if( e.keyCode == 82 && $("[data-action='repo-link']").length ){
                this.selectRandomRepository();
            }
        },
        
        events: {

            "click [data-action='settings']": function(e){
                this.searchOptionView.render();
                return false;
            },
            
            "click [data-action='repo-link']": function(e){

                var myModel = $(e.target).data("model");
                var el = $(e.target);
                
                this.repositoryList.invoke("set", "selected", false);                
                myModel.set("visited", true);

                var myView = this;
                $(".main-window").addClass("main-loading");
                $.post("ajax.php?action=view", myModel.toJSON(), function(data){
                    myModel.set("selected", true);
                    myModel.set("readme", data.content);
                    
                    $(".main-window").removeClass("main-loading");
                    myView.render();
                }, "json");
                
                return false;
            }
        }
        
    });
    
    app = new App( {el: $("#templateContainer")} );
});

</script>

</body>

</html>