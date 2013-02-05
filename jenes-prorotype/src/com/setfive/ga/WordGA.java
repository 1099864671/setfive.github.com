package com.setfive.ga;

import java.util.ArrayList;
import java.util.Hashtable;

import jenes.GenerationEventListener;
import jenes.GeneticAlgorithm;
import jenes.chromosome.BitwiseChromosome;
import jenes.population.Individual;
import jenes.population.Population;
import jenes.stage.AbstractStage;
import jenes.stage.operator.common.OnePointCrossover;
import jenes.stage.operator.common.SimpleMutator;
import jenes.stage.operator.common.TournamentSelector;

public class WordGA extends GeneticAlgorithm<BitwiseChromosome> implements GenerationEventListener {

	public class WordGAResult {
		public int generation;
		public String result;
	}
		
	private ArrayList<Integer> wordArray;
	private String TARGET_WORD = "I THINK THEREFORE I AM";
	private Hashtable<Integer, String> results;
	private String finalWord;
	private long runTime;
	private int runGenerations;
	
	public WordGA(Population<BitwiseChromosome> pop, int genlimit) {
		super(pop, genlimit);
	}

	@Override
	protected boolean end(){
		Population.Statistics stat = this.getCurrentPopulation().getStatistics();
		return (stat.getLegalHighestScore() == TARGET_WORD.length());
	}
	
	@Override
	protected void evaluateIndividual(Individual<BitwiseChromosome> individual) {
		BitwiseChromosome pc = individual.getChromosome();
		int l = pc.getSize();
		int score = 0;
		for(int i=0; i < l; i++){
			if(pc.getValueAt(i) == wordArray.get(i)){
				score += 1;
			}
		}
		
		individual.setScore(score);
	}

	public void onGeneration(GeneticAlgorithm ga, long time) {
		int currGen = ga.getGeneration();
		
		if( currGen % 50 == 0 ){
			BitwiseChromosome oc = (BitwiseChromosome) ga.getCurrentPopulation().getStatistics()
			.getLegalHighestIndividual().getChromosome();
			
			char chx;
			String term = new String();
			WordGAResult res = new WordGAResult();
			res.generation = currGen;
			
			for(int i=0; i < oc.getSize(); i++){
				chx = (char) Integer.parseInt( oc.getValueAt(i).toString() );
				term += chx;
			}

			results.put(currGen, term);
		}
	}
	
	public void setTargetWord(String word){
		int ch;
		
		TARGET_WORD = word;
		results = new Hashtable<Integer, String>();
		wordArray = new ArrayList<Integer>();
		for(int i=0; i < TARGET_WORD.length(); i++){
			ch = TARGET_WORD.charAt(i);
			wordArray.add( ch );
		}
	}
	
	public Hashtable<Integer, String> getGenerationResults(){
		return results;
	}
	
	public long getRunTime(){
		Population.Statistics stats = getCurrentPopulation().getStatistics();
		GeneticAlgorithm.Statistics algostats = getStatistics();
		return algostats.getExecutionTime();
	}
	
	public int getRunGenerations(){
		Population.Statistics stats = getCurrentPopulation().getStatistics();
		GeneticAlgorithm.Statistics algostats = getStatistics();
		return algostats.getGenerations();
	}
	
	public String getEvolvedWord(){
		Population.Statistics stats = getCurrentPopulation().getStatistics();
		BitwiseChromosome oc = (BitwiseChromosome) stats.getLegalHighestIndividual().getChromosome();
		
		char chx;
		String term = new String();
		
		for(int i=0; i < oc.getSize(); i++){
			chx = (char) Integer.parseInt(oc.getValueAt(i).toString());
			term += chx;
		}
		
		return term;
	}
	
	/**
	 * @param args
	 */
	public static void main(String[] args) {
		
	}
}
