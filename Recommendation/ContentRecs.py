

from MovieLens import MovieLens
from ContentKNNAlgorithm import ContentKNNAlgorithm
from Evaluator import Evaluator
from surprise import NormalPredictor

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


class ContentRecs:

    def ContentBased(test_subject,k):
        
        print("Inside the ContentBases function")
        # Load up common data set for the recommender algorithms
        (ml, evaluationData, rankings) = LoadMovieLensData()
        
        # Construct an Evaluator to, you know, evaluate them
        #evaluator = Evaluator(evaluationData, rankings)
        evaluator = Evaluator(evaluationData, rankings)
        
        
        #evaluator.Evaluator1(evaluationData, rankings)
        
        contentKNN = ContentKNNAlgorithm()
        evaluator.AddAlgorithm(contentKNN, "ContentKNN")
        
        # Just make random recommendations
        #Random = NormalPredictor()
        #evaluator.AddAlgorithm(Random, "Random")
        
        evaluator.Evaluate(True)
        recomm = []
        recomm = evaluator.SampleTopNRecs(ml,test_subject,k)
        return recomm