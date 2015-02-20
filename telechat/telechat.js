var events = require('events');
var util = require("util");
var es = require('event-stream');
var telehash = require('telehash');
var fs = require("fs");
var Q = require("q");
var _ = require("underscore");

var TeleChat = function(config){
    this._config = config;    
    this._ev = new events.EventEmitter();
    
    _.bindAll(this, "_connectMesh", "connectMesh", "onMeshInit", "connectLink", "onLinkStatus",
                    "onRouterInit", "onInitKeys", "onMeshAccept", "onMeshStream", "onRouterLinkData", "onLinkData");    
};

TeleChat.prototype = {
   _ev: null,
   _config: null,
   _mesh: null,
   _endpoint: null,
   _messages: [],   
   
  router: function(config){
      config = _.extend({}, this._config, config);
      telehash.load(config, this.onRouterInit);
  },
  
  initKeys: function(){
      var tc = this;
      var df = Q.defer();      
      var onInitKeys = this.onInitKeys;      
      var keyFile = this._config.keys;      
      
      /*
       * The router seems to ignore links if you drop and try to re-connect 
       * with the same hashname.
       * 
      if( fs.existsSync(keyFile) ){
          var endpoint = JSON.parse(fs.readFileSync(keyFile));
          
          onInitKeys(null, endpoint);
          df.resolve(tc);
          
          return df.promise;
      }
      */
      
      telehash.generate(function(err, endpoint){
          if(err){
              console.err("Could not create endpoint: " + err);
              df.reject(new Error(err));
          }
                    
          onInitKeys(err, endpoint);
          df.resolve(tc);
          
          fs.writeFile(keyFile, JSON.stringify(endpoint, null, 2));
      });
      
      return df.promise;
  },
  
  broadcast: function(data, fromLink){
      
      if(!data){
          return;
      }
      
      fromLink = fromLink ? fromLink : null;
      
      for(var i = 0; i < this._mesh.links.length; i++){
          
          if(this._mesh.links[i] == fromLink){
              continue;
          }
          
          console.log("Sending: " + data + " to " + this._mesh.links[i].hashname);
          this._mesh.links[i].stream().write(data);
      }
      
  },
  
  sendMessage: function(message){
      this._link.stream().write( JSON.stringify({type: "message", message: message}) );
  },
  
  sendHello: function(){      
      this._link.stream().write( JSON.stringify({type: "announce", name: this._config.name, avatar: this._config.avatar}) );
  },
  
  connectMesh: function(linkParams){
      return _.partial(this._connectMesh, linkParams);
  },
  
  _connectMesh: function(linkParams){
      var df = Q.defer();
      var lf = Q.defer();
      
      telehash.mesh({id: this._endpoint}, _.partial(this.onMeshInit, df));
      
      df.promise.then( _.partial(this.connectLink, lf, linkParams) );
      
      return Q.all([df.promise, lf.promise]);
  },
  
  onMeshInit: function(df, err, mesh){
      if(err){
          console.err("Could not create mesh: " + err);
          return false;
      }
      
      this._mesh = mesh;
      df.resolve(mesh);
  },
  
  connectLink: function(df, linkParams) {      
      this._link = this._mesh.link(linkParams);      
      this._link.status(_.partial(this.onLinkStatus, df));
      
      var onMeshStream = this.onMeshStream;
      var cb = this.onLinkData;
      
      this._mesh.stream(function(link, req, accept){          
          onMeshStream(link, req, accept, cb);
      });
  },
  
  onLinkStatus: function(df, err){
      if(err){
          df.reject(new Error(err));
          return;
      }
      
      this.sendHello();
      df.resolve(true);      
  },
  
  onInitKeys: function(err, endpoint){
      this._endpoint = endpoint;
      console.log( "Loaded: " + endpoint.hashname );
  },
  
  onLinkData: function(link, data){
      // this.log(link.hashname + ": " + data);
      data = JSON.parse(data);
      this._ev.emit("message", {from: link.hashname, data: data});
      this._messages.push({link: link, data: data});      
  },  
  
  onRouterInit: function(err, mesh){
      
      // This doesn't seem to get called ever
      // Trying to start on a privileged port throws an error to the console but not here
      if(err){
          console.err("Initialization error: " + err);
          console.err("Aborting...");
          process.exit(1);
      }
      
      this._mesh = mesh;
      
      mesh.router(true);
      mesh.discover(true);
      mesh.accept = this.onMeshAccept;
            
      var onMeshStream = this.onMeshStream;
      var cb = this.onRouterLinkData;
      
      mesh.stream(function(link, req, accept){          
          onMeshStream(link, req, accept, cb);
      });
      
      var uri = JSON.stringify(mesh.json(), null, 2);
      
      fs.writeFile(this._config.uri, uri);
      
      this.log("Mesh started at: " + mesh.hashname);
      this.log("Connection info: ");
      this.log(uri);
      this.log("\n");
      
  },
  
  onMeshStream: function(link, req, accept, cb){  
      accept()
        .pipe(es.through())
        .pipe(es.mapSync(function(data){
            cb(link, data);
        }));
  },  
  
  onRouterLinkData: function(link, data){
      this.log(link.hashname + ": " + data);      
      this._messages.push({link: link, data: data});
      
      this.broadcast(data, link);      
  },
  
  onMeshAccept: function(from){
      this.log("Incoming: " + from.hashname);
      this._mesh.link(from);
  },
  
  log: function(message){
      console.log(message);
  },
  
  on: function(event, cb){
      this._ev.on(event, cb);
  }
};

module.exports = TeleChat;