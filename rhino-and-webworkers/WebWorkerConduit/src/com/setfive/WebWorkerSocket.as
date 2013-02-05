package com.setfive
{
	import mx.utils.StringUtil;
	import flash.events.Event;
	import flash.events.ProgressEvent;
	import flash.events.SecurityErrorEvent;
	import flash.events.TimerEvent;
	import flash.net.Socket;
	import flash.utils.Timer;
	import flash.events.EventDispatcher;
	
	public class WebWorkerSocket extends EventDispatcher
	{
		
		private var sSocket:Socket;
		private var mutex:Boolean;
		private var jsurl:String;
		private var recievedDataStack:Array;
		
		public function WebWorkerSocket(server:String, port:Number)
		{
			
			this.jsurl = jsurl;
			this.recievedDataStack = new Array();
			
			sSocket = new Socket(server, port);
			sSocket.addEventListener(SecurityErrorEvent.SECURITY_ERROR, onSocketError);
			sSocket.addEventListener(Event.CONNECT, onSocketConnect);
			sSocket.addEventListener(ProgressEvent.SOCKET_DATA, onSocketData);
			sSocket.addEventListener(Event.CLOSE, onSocketClose);
		}

		public function sendData(data:String):void{
			sSocket.writeMultiByte(data + "\n", "us-ascii");
			sSocket.flush();
		}

		private function onSocketConnect(event:Event):void{
			
		}

		private function onSocketError(msg:SecurityErrorEvent):void{
			trace("balls");
		}

		private function onSocketClose(event:Event):void{
			trace("socket closed");
			this.mutex = true;
		}

		private function onSocketData(event:ProgressEvent):void{
			
			var recievedData:String = "";
			
			try{
				recievedData = sSocket.readUTFBytes(event.bytesLoaded);
			}catch(ex:Error){
				trace(ex.message);
			}
			
			recievedData = mx.utils.StringUtil.trim( recievedData ); 
			recievedDataStack.push( recievedData );
			
			this.dispatchEvent( new Event("onData") );
		}

		public function getData():String{
			var data:String = recievedDataStack.shift().toString();
			return data;
		}

	}
}