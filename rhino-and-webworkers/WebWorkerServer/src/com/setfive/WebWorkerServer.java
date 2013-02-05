package com.setfive;

import java.net.*;
import java.sql.Date;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Hashtable;
import java.io.*;

import javax.script.ScriptEngine;
import javax.script.ScriptEngineManager;
import javax.script.ScriptException;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.*;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.BasicHttpContext;
import org.apache.http.protocol.HttpContext;

import com.google.gson.Gson;

public class WebWorkerServer extends Thread implements WebWorkerMessageListener {

	private static ServerSocket socket;
	private Socket clientSocket;
	private PrintWriter outputStream;
	private Hashtable<Integer, WebWorkerJS> jsWorkers;
	
	public WebWorkerServer(Socket clientSocket){
		this.clientSocket = clientSocket;
		jsWorkers = new Hashtable<Integer, WebWorkerJS>();
	}
	
	public static String fetchJS(String fileUrl) throws Exception {
		HttpResponse response;
		DefaultHttpClient httpclient = new DefaultHttpClient();
		HttpContext localContext = new BasicHttpContext();
		HttpGet httpget = new HttpGet( fileUrl );
		BufferedReader urlDataStream;
		String buffer = null, jsFile = "";
		
		try {
			response = httpclient.execute(httpget, localContext);
		} catch (Exception e) {
			e.printStackTrace();
			return "";
		}
		
	    HttpEntity entity = response.getEntity();
	    if (entity != null) {
	        
	    	try {
	    		urlDataStream = new BufferedReader( new InputStreamReader(entity.getContent()) ); 
				
	    		do {
	    			buffer = urlDataStream.readLine();
	    			jsFile += buffer + "\n";
	    		}while( buffer != null );
	    		
			} catch (IOException e) {
				e.printStackTrace();
				return "";
			}
	    }
	    
	    return jsFile;
	}
	
	public static String getDate(){
		DateFormat dateFormat = new SimpleDateFormat ("yyyy/MM/dd HH:mm:ss");
        java.util.Date date = new java.util.Date ();
        String dateStr = dateFormat.format (date);
        return dateStr;
	}
	
	public void run(){
		
		BufferedReader in;
		String buffer = "", jsFile = "";
		WebWorkerJS js = null;
		
		System.out.println( getDate() + " accepted client." );
		
		try {
			
			outputStream = new PrintWriter(clientSocket.getOutputStream(), true);
			in = new BufferedReader( new InputStreamReader(clientSocket.getInputStream()) );
			
			do {
				
				buffer = in.readLine();
				
				if( buffer == null 
						|| buffer.length() == 0 ){ continue; }

				buffer = buffer.trim();
				
				System.out.println( buffer + "\n\n" );
				
				Gson gson = new Gson();
				WebWorkerMessage msg = gson.fromJson(buffer, WebWorkerMessage.class);
				
				switch( msg.message_type ){
				case 1:
					try {

						System.out.println(getDate() + " fetching " + msg.message);
						jsFile = WebWorkerServer.fetchJS( msg.message );
						System.out.println( getDate() + " downloaded " + msg.message );

						js = new WebWorkerJS(msg.id, jsFile);
						jsWorkers.put(msg.id, js);
						
						js.addOnMessageHandler(this);
						js.start();
						
					} catch (Exception e) {
						e.printStackTrace();
						return;
					}
					break;
				case 2:
					js = jsWorkers.get( msg.id );
					js.postJSMessage( msg.message );
				break;
				default: break;
				}
				
			}while( js.getIsAlive() );
			
		} catch (IOException e) {
			jsWorkers.clear();
			jsWorkers = null;

			e.printStackTrace();
			return;
		}
		
	}
	
	/**
	 * @param args
	 */
	public static void main(String[] args) {

		int port = Integer.parseInt(args[0]);
		
		try {
			socket = new ServerSocket(port);
		} catch (IOException e) {
			e.printStackTrace();
		}
		
		System.out.println(getDate() + " Server accepting on port " + args[0]);
		
		while ( true ){
			try {
				new WebWorkerServer( socket.accept() ).start();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		
	}

	@Override
	public void processMessage(String data, int id) {
		
		/*
		try {
			int res = clientSocket.getInputStream().read();
		} catch (IOException e) {
			// the socket closed to clean things up and bail
			WebWorkerJS js = jsWorkers.get( id );
			js.killWorker();
			js = null;
			e.printStackTrace();
			return;
		}
		*/
		
		System.out.println(data);
		
		Gson gs = new Gson();
		outputStream.println( data );
		outputStream.flush();
		
	}

}
