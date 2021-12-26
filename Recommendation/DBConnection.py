

import mysql.connector
import pandas as pd

class DBConnection:
    
    def connectMysql(self):
        
        print("Inside connect MYSQL function")
        
        myconn = mysql.connector.connect(host='localhost', user='root', passwd='', database='ad_evaluator')
        cursor = myconn.cursor()
        query = "SELECT * FROM ratings1"
        
        sql_query = pd.read_sql_query(''' 
                                      select * from ratings1
                                      '''
                                      ,myconn) # here, the 'conn' is the variable that contains your database connection information from step 2
        
        sql_query2 = pd.read_sql_query(''' 
                                      select * from userscores
                                      '''
                                      ,myconn) # here, the 'conn' is the variable that contains your database connection information from step 2
        
        
        try:
            cursor.execute(query)
            results = cursor.fetchall()
            #print(results)
        except:
            myconn.rollback()
            
        #for x in cursor:
        #    print(x)
            
        
        df = pd.DataFrame(sql_query)
        del df['id']
        df.to_csv (r'C:\Users\Lenovo\Documents\Uvini Wijesinghe\Research Project\EEG Based Movie Recommendation System\GITLAB\1. V1\2021-114\ml-datasets\RatingsDB.csv', index = False) # place 'r' before the path name to avoid any errors in the path
            
        df2 = pd.DataFrame(sql_query2)
        del df2['auto_id']
        df2.to_csv (r'C:\Users\Lenovo\Documents\Uvini Wijesinghe\Research Project\EEG Based Movie Recommendation System\GITLAB\1. V1\2021-114\datasets\RatingsDB.csv', index = False) # place 'r' before the path name to avoid any errors in the path
            
        
        print("CSV file generated")
        
        myconn.close()

        