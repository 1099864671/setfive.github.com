package com.setfive;

import java.util.EventListener;

public interface WebWorkerMessageListener extends EventListener {
	public void processMessage(String data, int id);
}
