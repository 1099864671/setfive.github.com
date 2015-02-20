var telechat = require("./telechat.js");

var config = {port: 42424, router: true, v: true, 
              uri: "/home/ubuntu/uri.json", id: "/home/ubuntu/router.json", links: "/home/ubuntu/links.json"};

var tc = new telechat(config);
tc.router();