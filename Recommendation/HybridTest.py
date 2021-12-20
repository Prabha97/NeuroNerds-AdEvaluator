
from MovieLens import MovieLens
#from RBMAlgorithm import RBMAlgorithm
from ContentKNNAlgorithm import ContentKNNAlgorithm
from HybridAlgorithm import HybridAlgorithm
from Evaluator import Evaluator
from surprise import KNNBasic
from surprise import SVDpp

import random
import numpy as np

def LoadMovieLensData():
        ml = MovieLens()
        print("ml Type: ",type(ml))
        print("Loading movie ratings...")
        data = ml.loadMovieLensLatestSmall()
        print("data Type: ",type(data))
        print("\nComputing movie popularity ranks so we can measure novelty later...")
        rankings = ml.getPopularityRanks()
        print("rankings Type: ",type(rankings))
        print(rankings)
        return (ml, data, rankings)

class HybridTest:
   

    def recomm(test_subject,k):
    

        # Load up common data set for the recommender algorithms
        (ml, evaluationData, rankings) = LoadMovieLensData()
    
        # Construct an Evaluator to, you know, evaluate them
        evaluator = Evaluator(evaluationData, rankings)
    
        #Simple RBM
        #SimpleRBM = RBMAlgorithm(epochs=40)
        #Content
        ContentKNN = ContentKNNAlgorithm()
        #ItemKNN = KNNBasic(sim_options = {'name': 'cosine', 'user_based': False})
        SVDPlusPlus = SVDpp()
    
    
    
        #Combine them
        Hybrid = HybridAlgorithm([SVDPlusPlus,ContentKNN], [0.5,0.5])
    
        #evaluator.AddAlgorithm(SimpleRBM, "RBM")
        #evaluator.AddAlgorithm(ItemKNN, "ItemKNN")
        #evaluator.AddAlgorithm(ContentKNN, "ContentKNN")
        #evaluator.AddAlgorithm(SVDPlusPlus, "SVD++")
        evaluator.AddAlgorithm(Hybrid, "Hybrid")
    
        # Fight!
        evaluator.Evaluate(False)
        recomm = []
        recomm = evaluator.SampleTopNRecs(ml,test_subject,k)
        return recomm
