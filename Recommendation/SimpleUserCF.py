
from MovieLens import MovieLens
from surprise import KNNBasic
import heapq
from collections import defaultdict
from operator import itemgetter
        
# =============================================================================
# testSubject = '30'
# k = 10
# =============================================================================

class CollaborativeFilteringUser:
    
    def SimpleUserCF(testSubject,k):
        
        # convert the num into string
        testSubject = str(testSubject)
        
        print(type(testSubject))
        print(k)
        # Load our data set and compute the user similarity matrix
        ml = MovieLens()
        data = ml.loadMovieLensLatestSmall()
        
        trainSet = data.build_full_trainset()
        
        sim_options = {'name': 'cosine',
                       'user_based': True
                       }
        
        print("++++++ call knn basic +++++++++++++++++++++++")
        model = KNNBasic(sim_options=sim_options)
        model.fit(trainSet)
        simsMatrix = model.compute_similarities()
        print("++++++ after calculating sim matrix +++++++++++++++++++++++")
        
        # Get top N similar users to our test subject
        # (Alternate approach would be to select users up to some similarity threshold - try it!)
        testUserInnerID = trainSet.to_inner_uid(testSubject)
        similarityRow = simsMatrix[testUserInnerID]
        print("++++++ after calculating similarity row +++++++++++++++++++++++")
        
        similarUsers = []
        for innerID, score in enumerate(similarityRow):
            if (innerID != testUserInnerID):
                similarUsers.append( (innerID, score) )
        
        print("++++++ before knn neighbors +++++++++++++++++++++++")
        kNeighbors = heapq.nlargest(k, similarUsers, key=lambda t: t[1])
        print("++++++ after knn neighbors +++++++++++++++++++++++")
        
        # Get the stuff they rated, and add up ratings for each item, weighted by user similarity
        candidates = defaultdict(float)
        for similarUser in kNeighbors:
            innerID = similarUser[0]
            userSimilarityScore = similarUser[1]
            theirRatings = trainSet.ur[innerID]
            for rating in theirRatings:
                candidates[rating[0]] += (rating[1] / 5.0) * userSimilarityScore
            
        print("++++++ after similarUser for loop +++++++++++++++++++++++")
        
        # Build a dictionary of stuff the user has already seen
        watched = {}
        for itemID, rating in trainSet.ur[testUserInnerID]:
            watched[itemID] = 1
            
        print("++++++ after watched for loop +++++++++++++++++++++++") 
        
        # Get top-rated items from similar users:
        pos = 0
        recomm = list()
        for itemID, ratingSum in sorted(candidates.items(), key=itemgetter(1), reverse=True):
            if not itemID in watched:
                movieID = trainSet.to_raw_iid(itemID)
                #print(ml.getMovieName(int(movieID)), ratingSum)
                x = ml.getMovieName(int(movieID))
                recomm.append(x)
                print(recomm)
                pos += 1
                if (pos >= k):
                    break
                
        print("++++++ after final for loop +++++++++++++++++++++++") 
        return recomm

#print(CollaborativeFilteringUser.SimpleUserCF('22',2))