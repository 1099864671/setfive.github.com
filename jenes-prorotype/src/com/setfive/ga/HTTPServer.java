package com.setfive.ga;

import java.io.IOException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.mortbay.jetty.Connector;
import org.mortbay.jetty.Handler;
import org.mortbay.jetty.HttpConnection;
import org.mortbay.jetty.Request;
import org.mortbay.jetty.Server;
import org.mortbay.jetty.bio.SocketConnector;
import org.mortbay.jetty.handler.AbstractHandler;
import org.mortbay.util.ajax.JSON;

public class HTTPServer {

	private static int SERVER_PORT = 9999;
	private static int RETRIES = -1;
	/**
	 * @param args
	 */
	public static void main(String[] args) throws Exception {
		
        Server server = new Server();
        int numFail = 0;
        Connector connector = new SocketConnector();
        connector.setPort(SERVER_PORT);
        server.setConnectors( new Connector[]{connector} );
        
        Handler handler = new WordGAHandler();
        server.setHandler(handler);
        
        while(RETRIES < 0 || numFail < RETRIES){
        	try{
        		server.start();
        		server.join();
        	}catch(Exception ex){
        		System.err.println(ex.getMessage());
        		server = new Server();
        		server.setConnectors( new Connector[]{connector} );
        	}
        	numFail += 1;
        }
        
       System.out.println("Exited after server container threw exception!");
	}

    public static class WordGAHandler extends AbstractHandler
    {
        public void handle(String target, HttpServletRequest request, 
        					HttpServletResponse response, int dispatch) 
        		throws IOException, ServletException
        {
            Request base_request = (request instanceof Request) ? (Request)request : 
            							HttpConnection.getCurrentConnection().getRequest();
            JSON js = new JSON();
            base_request.setHandled(true);
            String scriptTop = "<script type='text/javascript'>";
            String q = request.getParameter("q");
            String callback = request.getParameter("c");
            
            response.setContentType("text/html");
            response.setStatus(HttpServletResponse.SC_OK);
            
            if(q == null || q.length() == 0 || q.length() > 128){
            	response.getWriter().println("[]");
            	return;
            }
            
            if(callback == null || callback.length() == 0){
            	callback = "parent.loadEVO";
            }
            
            RunGA ga = new RunGA();
            ga.runEvolution(q);
            response.getWriter().println( js.toJSON(ga.getResults()) );
        }
    }
	
}
