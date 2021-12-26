
import mysql.connector
import MySQLdb
import pandas as pd

class DBConnectionAtten:
    
    def connectMysql(self, avg_highattention_score, avg_lowattention_score, avg_mediumattention_score, final_attention_score, final_statement):
        
        print("Connected DB")
        
        #myconn = MySQLdb.connect(host='localhost', user='root', passwd='', database='ad_evaluator')
        myconn = MySQLdb.connect("localhost","root","","ad_evaluator")
        print("h_attention",avg_highattention_score)
        print(type(avg_highattention_score))
        print(type(avg_lowattention_score))
        print(type(avg_mediumattention_score))
        print(type(final_attention_score))
        print(type(final_statement))
        #cursor = myconn.cursor()
        print("l_attention",avg_lowattention_score)
        
        print("m_attention",avg_mediumattention_score)

        
        print("val_attention",final_attention_score)
        
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
        
        
        
        insertRec = myconn.cursor()
        
        #insertrecQry = "INSERT INTO attention1 (avg_highattention_score, avg_lowattention_score, avg_mediumattention_score, final_attention_score, final_statement) VALUES (avg_highattention_score, avg_lowattention_score, avg_mediumattention_score, final_attention_score, final_statement);"

        insertRec.execute("INSERT INTO attention_data (username, movie_name, avg_high_atten_score, avg_medium_atten_score, avg_low_atten_score, final_atten_score, final_statement) VALUES (%s, %s, %s, %s, %s, %s, %s)", (str1, str2, avg_highattention_score, avg_mediumattention_score, avg_lowattention_score, final_attention_score, final_statement))
        
        print('x')
        
        myconn.commit()
        
        print("Attention Inserted")
        print("========================================================")
        
        myconn.close()