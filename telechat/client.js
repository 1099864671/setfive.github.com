var fs = require("fs");
var telechat = require("./telechat.js"); 
var sys = require("sys");
var colors = require("colors");

var routerUri = JSON.parse(fs.readFileSync("/home/ubuntu/uri.json"));
var tc = new telechat({keys: "/home/ubuntu/clientKeys.json", name: "Goose", avatar: ""});

function startChatLoop(){
    var stdin = process.openStdin();
    
    console.log("Keyed and connected. Say something:".bgGreen.bold);
    
    stdin.addListener("data", function(d) {
        var val = d.toString().substring(0, d.length - 1);
        tc.sendMessage(val);
    });
}

tc.on("message", function(payload){       
    
    switch(payload.data.type){
    
        case "announce":
            var str = payload.from + " joined the chat.";
            console.log(str.bgWhite.black);
        break;
    
        case "message":
            var str = payload.from + ": " + payload.data.message;
            console.log(str.green);
        break;
        
        default: break;
    }
    
});

tc.initKeys()
  .then( tc.connectMesh(routerUri) )
  .then( startChatLoop );