// ActionScript file
import com.setfive.WebWorkerSocket;
import flash.external.*;

private var ww:WebWorkerSocket;
private var wwId:String;

public function main():void{
	
	var server:String = Application.application.parameters.server;
	var port:Number = Application.application.parameters.port;
		
	ExternalInterface.call("sfWebWorkersSWFReady", true);
	
	trace( server + " : " + port );
	
	ww = new WebWorkerSocket(server, port);
	ExternalInterface.addCallback("sendDataToFlash", getDataFromJS);
	ww.addEventListener("onData", onDataHandler);	
}

public function getDataFromJS(str:String):void {
	ww.sendData(str);
}

public function onDataHandler(event:Event):void{
	ExternalInterface.call("sfWebWorkersRecieveData", ww.getData());
}