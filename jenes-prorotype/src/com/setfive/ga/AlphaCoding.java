package com.setfive.ga;

import jenes.chromosome.BitwiseChromosome.BitCoding;
import jenes.chromosome.BitwiseChromosome.BitSize;
import jenes.chromosome.codings.ByteCoding;

public class AlphaCoding extends BitCoding<Integer> {

	private static int AlphaSize = 26; 
	
	protected AlphaCoding() {
		super(BitSize.BIT8);
	}

	@Override
	public Integer decode(int bits) {
		return bits;
	}

	@Override
	public int encode(Integer obj) {
		return obj;
	}
	
}
