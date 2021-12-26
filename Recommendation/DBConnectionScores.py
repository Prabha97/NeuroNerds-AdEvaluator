

import mysql.connector
import MySQLdb
import pandas as pd

class DBConnectionSco:
    
    def connectMysql(self, highest_score,emotion_score,highestEjoy):
        
        print("Inside connect MYSQL function")
        
        db = MySQLdb.connect("localhost","root","","ad_evaluator")
        
        #print("Attention", attention)
        #print("Emotion", emotion)
        print("Enjoyment inside DBConnection", highestEjoy)
        print("Emotion inside DBConnection", emotion_score)
        print("Attention inside DBConnection", highest_score)
        
        
        ##################################### SELECT LATEST USER RECORD ##################################################################
        
# =============================================================================
#         selectLatest = db.cursor()
# 
#         selectLatest.execute("SELECT username FROM atten_emo_enjoy ORDER BY id DESC LIMIT 1")
#         
#         results = selectLatest.fetchall()
#         
#         def convertTuple(tup):
#                 # initialize an empty string
#             str1 = ''
#             for item in tup:
#                 str1 = str1 + item
#             return str1
#         
#         
#         for x in results:
#             print (x)
#             print(type(x))
#             str1 = convertTuple(x)
#     
#         print("Username str", str1)
# =============================================================================
        
        ##################################### SELECT LATEST MOVIE NAME RECORD ##################################################################
        
# =============================================================================
#         selectLatestMovie = db.cursor()
# 
#         selectLatestMovie.execute("SELECT movie_name FROM atten_emo_enjoy ORDER BY id DESC LIMIT 1")
#         
#         results = selectLatestMovie.fetchall()
#         
#         def convertTuple2(tup):
#                 # initialize an empty string
#             str2 = ''
#             for item in tup:
#                 str2 = str2 + item
#             return str2
#         
#         
#         for x in results:
#             print (x)
#             print(type(x))
#             str2 = convertTuple2(x)
#     
#         print("Movie Name str", str2)
# =============================================================================
        
        ##################################### SELECT LATEST MOVIE NAME RECORD ##################################################################
        
# =============================================================================
#         selectLatestM = db.cursor()
# 
#         selectLatestM.execute("SELECT id FROM atten_emo_enjoy ORDER BY id DESC LIMIT 1")
#         
#         results2 = selectLatestM.fetchall()
#         
#         def convertTuple1(tup):
#                 # initialize an empty string
#             str2 = ''
#             for item in tup:
#                 str2 = str2 + item
#             return str2
#         
#         
#         for x in results2:
#             print (x)
#             print(type(x))
#             str2 = convertTuple1(x)
#     
#         print("Movie Name str", str2)
# =============================================================================
        
        ##############################################################################################################################
        
        ##################################### SELECT MOVIE ID OF THE USERNANE ##################################################################
        
        selectuserid = db.cursor()
        
        query = """SELECT id FROM atten_emo_enjoy ORDER BY id DESC LIMIT 1"""
        #tuple2 = (results2)
        selectuserid.execute(query)
        
        results3 = selectuserid.fetchall()
        
        for x in results3:
            print (x)
            print(type(x))
            tupleV = x
            
        print(tupleV)
        idd = tupleV[0]
        print("user idd: ",idd)
        print(type(idd))
        
        print("DbConection Scores user id:", idd)
        
# =============================================================================
#         print(tupleV)
#         idd1 = tupleV[0]
#         print("user idd: ",idd1)
#         print(type(idd))
#         
# =============================================================================
        
        ##################################### SELECT USER ID FROM USERSCORES ##################################################################
        
        selectautouserid = db.cursor()
        
        query1 = """SELECT auto_id FROM userscores ORDER BY auto_id DESC LIMIT 1"""
        #tuple2 = (results2)
        selectautouserid.execute(query1)
        
        results4 = selectautouserid.fetchall()
        
        for y in results4:
            print (y)
            print(type(y))
            tupleV = y
            
        print(tupleV)
        idd1 = tupleV[0]
        print("user idd: ",idd1)
        print(type(idd1))
        
        print("DbConection Scores auto_user_id:", idd1)
        
        
        ##################################### UPDATE LATEST RECORD ##################################################################
        
        
        
        insertrec = db.cursor()
          
        insertrec.execute("UPDATE atten_emo_enjoy SET attention = %s, emotion = %s, enjoyment = %s  WHERE id = %s ",(highest_score, emotion_score, highestEjoy,idd))
        
        insertscore = db.cursor()
          
        insertscore.execute("UPDATE userscores SET attention = %s, emotion = %s, enjoyment = %s  WHERE auto_id = %s ",(highest_score, emotion_score, highestEjoy,idd1))

        
        db.commit()
        print("Record Saved Successfully")
        
        db.close()