package com.setfive;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.ArrayList;
import java.util.Hashtable;
import java.util.Stack;

import javax.script.Bindings;
import javax.script.Compilable;
import javax.script.CompiledScript;
import javax.script.Invocable;
import javax.script.ScriptEngine;
import javax.script.ScriptEngineManager;
import javax.script.ScriptException;
import javax.swing.event.EventListenerList;

import com.google.gson.Gson;

public class WebWorkerJS extends Thread implements WebWorkerMessageListener {

	private EventListenerList  _eventHandlers = new EventListenerList();
	private ScriptEngine jsEngine;
	private String jsFile = "", jsonFile = "";
	private int id;
	private Stack<String> messages;
	private boolean isAlive;
	
	/**
	 * @param args
	 */
	public static void main(String[] args) {
		
		String jsFile = "";
		try {
			jsFile = WebWorkerServer.fetchJS( "http://192.168.1.102:8001/worker.js" );
		} catch (Exception e) {
			e.printStackTrace();
			return;
		}
		
		WebWorkerJS js;
		Hashtable<Integer, WebWorkerJS> table = new Hashtable<Integer, WebWorkerJS>();
		for( int i=0; i < 10; i++ ){
			js = new WebWorkerJS(i, jsFile);
			js.addOnMessageHandler( js );
			js.start();
			table.put(i, js);
		}
		
		for( int i=0; i < 10; i++ ){
			js = table.get(i);
			js.postJSMessage("{\"data\":\"1249c4b7f578204f10798c0269f8488280fb9981 builders,cvs,browser,adjoin,venema,xkcd,atbash,cucumber,bell,biham,ulysses,colocation 34\"}");
		}
		
		System.out.println( "JS exec() complete" );
	}
	
	public boolean getIsAlive(){
		return this.isAlive;
	}
	
	public void killWorker(){
		this.isAlive = false;
		jsEngine = null;
	}
	
	public WebWorkerJS(int id, String jsFile){
		
		this.jsFile = jsFile;
		this.id = id;
		this.messages = new Stack<String>();
		this.isAlive = true;
		
		ClassLoader classLoader = getClass().getClassLoader();
		URL scriptLocation = classLoader.getResource("js/json.js");
		String buffer = "";
		
		try {
			BufferedReader scriptReader = 
				new BufferedReader(new InputStreamReader(scriptLocation.openStream()));
			do{
				buffer = scriptReader.readLine();
				this.jsonFile += buffer + "\n";
			}while( buffer != null);
			
		} catch (IOException e) {
			e.printStackTrace();
			return;
		}
		
	}
	
	public void run(){
		executeScript( jsFile );
		
		Invocable invocableEngine;
		
		while(this.isAlive){
			
			if( messages.size() > 0 ){
				
				invocableEngine = (Invocable) jsEngine;
				
				try {
					jsEngine.eval("onmessage(" + messages.pop() + ")");
				} catch (Exception e) {
					e.printStackTrace();
					return;
				}	
			}
			
		}
		
	}
	
	public void pushMessage(String data){
		
		Object[] ls = _eventHandlers.getListenerList();
		
		for(int i = 0; i < ls.length; i+=2){
			if( ls[i] == WebWorkerMessageListener.class ){
				((WebWorkerMessageListener)ls[i+1]).processMessage( data, this.id );
			}
		}
		
	}
	
	public void addOnMessageHandler(WebWorkerMessageListener ls){
		_eventHandlers.add(WebWorkerMessageListener.class, ls);
	}
	
	public void removeOnMessageHandler(WebWorkerMessageListener ls){
		_eventHandlers.remove(WebWorkerMessageListener.class, ls);
	}
	
	public void executeScript(String js){
		
		ScriptEngineManager mgr = new ScriptEngineManager();
		jsEngine = mgr.getEngineByName("JavaScript");
		Compilable compilable = (Compilable) jsEngine;
		
		try {
			CompiledScript script = compilable.compile(jsonFile + "\n" + js );
			jsEngine.put("postedMessagesStack", this);
			jsEngine.put("sfWebWorkerId", this.id);
			script.eval();
		} catch (ScriptException ex) {
			ex.printStackTrace();
			return;
		}
		
	}

	public void postJSMessage(String data){
		messages.push(data);
	}
	
	@Override
	public void processMessage(String data, int id) {
		System.out.println( id + " - " + data );
	}
	
}
