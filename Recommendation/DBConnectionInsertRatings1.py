
import mysql.connector
import MySQLdb
import pandas as pd
import functools

class DBConnectionRat:
    
    def connectMysql(self,highest_score,emotion_score,highestEjoy):
        
        print("DBConnectionRat DB")
        
        #myconn = MySQLdb.connect(host='localhost', user='root', passwd='', database='ad_evaluator')
        myconn = MySQLdb.connect("localhost","root","","ad_evaluator")
        
        ##################################### SELECT LATEST USERNAME RECORD ##################################################################
        
        selectLatest = myconn.cursor()

        selectLatest.execute("SELECT username FROM atten_emo_enjoy ORDER BY id DESC LIMIT 1")
        
        results = selectLatest.fetchall()
        
        def convertTuple(tup):
                # initialize an empty string
            str1 = ''
            for item in tup:
                str1 = str1 + item
            return str1
        
        
        for x in results:
            print (x)
            print(type(x))
            str1 = convertTuple(x)
    
        print("Username str", str1)
        
        ##############################################################################################################################
        
         ##################################### SELECT LATEST MOVIE NAME RECORD ##################################################################
        
        selectLatestM = myconn.cursor()

        selectLatestM.execute("SELECT movie_name FROM atten_emo_enjoy ORDER BY id DESC LIMIT 1")
        
        results2 = selectLatestM.fetchall()
        
        def convertTuple1(tup):
                # initialize an empty string
            str2 = ''
            for item in tup:
                str2 = str2 + item
            return str2
        
        
        for x in results2:
            print (x)
            print(type(x))
            str2 = convertTuple1(x)
    
        print("Movie Name str", str2)
        
        ##############################################################################################################################
        
        ##################################### SELECT USER ID OF THE USERNANE ##################################################################
        
        selectuserid = myconn.cursor()
        
        query = """SELECT user_id FROM users WHERE username = %s"""
        tuple1 = (results)
        selectuserid.execute(query, tuple1)
        
        results1 = selectuserid.fetchall()
        
        for x in results1:
            print (x)
            print(type(x))
            tupleV = x
            
        print(tupleV)
        idd = tupleV[0]
        print("user idd: ",idd)
        print(type(idd))
        
        ###################################################################################################################################
        
        ##################################### SELECT MOVIE ID OF THE USERNANE ##################################################################
        
        selectmovieid = myconn.cursor()
        
        query = """SELECT movieId FROM movies WHERE movie_name = %s"""
        tuple2 = (results2)
        selectmovieid.execute(query, tuple2)
        
        results3 = selectmovieid.fetchall()
        
        for x in results3:
            print (x)
            print(type(x))
            tupleV = x
            
        print(tupleV)
        idd1 = tupleV[0]
        print("user idd: ",idd1)
        print(type(idd))
        
# =============================================================================
#         for x in results3:
#             print (x)
#             print(type(x))
#             tupleV1 = x
#             
#         print(tupleV1)
#         idd1 = tupleV1[0]
#         print("movie idd: ",idd1)
#         print(type(idd1))
# =============================================================================
        
        ###################################################################################################################################
        r_min = 0;
        r_max = 100;
    
        t_min = 1;
        t_max = 5;
    
        m_tot = (highest_score + emotion_score + highestEjoy);
    
        m = m_tot/3;
    
        final_score = ((m - r_min) / (r_max - r_min)) * (t_max - t_min) + t_min;
        
        insertRec1 = myconn.cursor()
        
        insertRec1.execute("INSERT INTO ratings1 (userId, movieId, rating) VALUES (%s, %s, %s)", (idd, idd1, final_score))
        
        
        
        
        print('x')
        
        myconn.commit()
        
        print("Ratings1 Inserted")
        print("========================================================")
        
        myconn.close()