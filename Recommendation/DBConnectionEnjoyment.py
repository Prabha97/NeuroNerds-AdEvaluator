
import mysql.connector
import MySQLdb
import pandas as pd

class DBConnectionEnjoy:
    
    def connectMysql(self, enjoy, notEnjoy, status):
        
        print("Connected DB")
        
        #myconn = MySQLdb.connect(host='localhost', user='root', passwd='', database='ad_evaluator')
        myconn = MySQLdb.connect("localhost","root","","ad_evaluator")
        
        print("Enjoy",enjoy)
        print("Not Enjoy",notEnjoy)
        print("Status",status)
        
        print(type(enjoy))
        print(type(notEnjoy))
        print(type(status))
        
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

        insertRec.execute("INSERT INTO enjoyment_data (username, movie_name, enjoyment, non_enjoyment , status) VALUES (%s, %s, %s, %s, %s)", (str1, str2, enjoy, notEnjoy, status))
        
        print('x')
        
        myconn.commit()
        
        print("Enjoyment Inserted")
        print("========================================================")
        
        myconn.close()