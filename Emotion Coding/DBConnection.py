# -*- coding: utf-8 -*-
"""
Created on Sun Dec 26 18:35:28 2021

@author: Saumya
"""

import mysql.connector
import MySQLdb
import pandas as pd

class DBConnectionEmo:
    
    def connectMysql(self, neutral, happy, sad, satisfy, high_score_emo, highest_emo_status):
        
        print("Connected DB")
        
        myconn = MySQLdb.connect("localhost","root","","ad_evaluator")
        
        print("neutral",neutral)
        print("happy",happy)
        
        print(type(high_score_emo))
        
        
        insertRec = myconn.cursor()
        
        insertRec.execute("INSERT INTO emotion_data (neutral_score, happy_score, sad_score, satisfy_score, highest_score, highest_score_status) VALUES (%s, %s, %s, %s, %s, %s)", (neutral, happy, sad, satisfy, high_score_emo, highest_emo_status))
        
        print('x')
        
        myconn.commit()
        
        print("Emotion Data Inserted")
        print("========================================================")
        
        myconn.close()