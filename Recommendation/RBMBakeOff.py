

from MovieLens import MovieLens
from RBMAlgorithm import RBMAlgorithm
from surprise import NormalPredictor
from Evaluator import Evaluator

import random
import numpy as np

def LoadMovieLensData():
    ml = MovieLens()
    print("Loading movie ratings...")
    data = ml.loadMovieLensLatestSmall()
    print("\nComputing movie popularity ranks so we can measure novelty later...")
    rankings = ml.getPopularityRanks()
    return (ml, data, rankings)

np.random.seed(0)
random.seed(0)

class RBMBakeOff:
    
    def rbmBakeOff(test_subject,k):
        
        # Load up common data set for the recommender algorithms
        (ml, evaluationData, rankings) = LoadMovieLensData()
        
        # Construct an Evaluator to, you know, evaluate them
        evaluator = Evaluator(evaluationData, rankings)
        
        #RBM
        RBM = RBMAlgorithm(epochs=20)
        evaluator.AddAlgorithm(RBM, "RBM")
        
        # Just make random recommendations
        #Random = NormalPredictor()
        #evaluator.AddAlgorithm(Random, "Random")
        
        # Fight!
        evaluator.Evaluate(True)
        recomm = []
        recomm = evaluator.SampleTopNRecs(ml,test_subject,k)
        return recomm