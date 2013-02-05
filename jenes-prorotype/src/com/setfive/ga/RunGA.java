package com.setfive.ga;

import java.util.ArrayList;
import java.util.Hashtable;

import jenes.GeneticAlgorithm;
import jenes.chromosome.BitwiseChromosome;
import jenes.population.Individual;
import jenes.population.Population;
import jenes.stage.AbstractStage;
import jenes.stage.operator.common.OnePointCrossover;
import jenes.stage.operator.common.SimpleMutator;
import jenes.stage.operator.common.TournamentSelector;

import com.setfive.ga.WordGA.WordGAResult;

public class RunGA {

	public class RunGAResultSet { 
		WordGAResult[] generations;
		long runtime;
		int numGenerations;
		String finalWord;
	}
	
	private static int POPULATION_SIZE   = 300;
	private static int GENERATION_LIMIT  = 50000;
	private static int CHROMOSOME_SIZE;
	
	private WordGA ga;
	
	public void runEvolution(String target){
		
		int ch;
		CHROMOSOME_SIZE = target.length();
		
		Individual<BitwiseChromosome> sample = new Individual<BitwiseChromosome>(
				new BitwiseChromosome(CHROMOSOME_SIZE, new AlphaCoding()
				));
		Population<BitwiseChromosome> pop = new Population<BitwiseChromosome>(sample, POPULATION_SIZE);

		AbstractStage<BitwiseChromosome> selection = new TournamentSelector<BitwiseChromosome>(3);
		AbstractStage<BitwiseChromosome> crossover = new OnePointCrossover<BitwiseChromosome>(0.8);
		AbstractStage<BitwiseChromosome> mutation = new SimpleMutator<BitwiseChromosome>(0.2);
		
		ga = new WordGA(pop, GENERATION_LIMIT);
		ga.setTargetWord(target);
		ga.addStage(selection);
		ga.addStage(crossover);
		ga.addStage(mutation);
		ga.addGenerationEventListener(ga);
		ga.setBiggerIsBetter(true);
		ga.evolve();
		
	}
	
	public Hashtable getResults(){
		
		Hashtable hs = new Hashtable();
		hs.put("runningTime", getRunTime());
		hs.put("numberOfGenerations", getGenerations());
		hs.put("intermediates", ga.getGenerationResults());
		hs.put("finalWord", getEvolvedWord());
		return hs;
	}
	
	public long getRunTime(){
		return ga.getRunTime();
	}
	
	public int getGenerations(){
		return ga.getRunGenerations();
	}
	
	public String getEvolvedWord(){
		return ga.getEvolvedWord();
	}
}
