
import pickle
import pandas as pd
import os
import json
import numpy as np

from flask import Flask, jsonify, request
from flask import render_template, redirect, url_for
from flask_cors import CORS, cross_origin
import configparser
from flask import request
from enjoymentScoring import enjoyment_evaluation

import glob
import os.path

#from DBConnection3 import DBConnection

from DBConnectionScores import DBConnectionSco
from DBConnectionAttention import DBConnectionAtten
from DBConnectionEnjoyment import DBConnectionEnjoy
from DBConnectionEmotion import DBConnectionEmo
from DBConnectionInsertRatings1 import DBConnectionRat


######Flask API#########
app = Flask(__name__)
cors = CORS(app)
############Web App##############
@app.route('/score',methods=['GET'])
def index():
    
    
    folder_path = r'C:\Users\Lenovo\Dropbox'
    file_type = '\*.csv'
    files = glob.glob(folder_path + file_type)
    max_file = max(files, key=os.path.getctime)
    max_file = max_file.replace("\\","/")
    print (max_file)
    
    data= pd.read_csv(max_file)
    data = data.drop('Elements', 1)
    cleanedFile = data.dropna()
    
    cleanedFile.to_csv (r'C:\Users\Lenovo\Documents\Uvini Wijesinghe\Research Project\EEG Based Movie Recommendation System\GITLAB\1. V1\2021-114\Recomm\cleaned data\cleanedFile.csv', index = False) # place 'r' before the path name to avoid any errors in the path
    
    new_path = 'C:/Users/Lenovo/Documents/Uvini Wijesinghe/Research Project/EEG Based Movie Recommendation System/GITLAB/1. V1/2021-114/Recomm/cleaned data/cleanedFile.csv'
    model_path = 'C:/Users/Lenovo/Documents/Uvini Wijesinghe/Research Project/EEG Based Movie Recommendation System/GITLAB/1. V1/2021-114/Recomm/models/svm_model_attention_model.pkl'
    model_path_emotion = 'C:/Users/Lenovo/Documents/Uvini Wijesinghe/Research Project/EEG Based Movie Recommendation System/GITLAB/1. V1/2021-114/Recomm/models/model1_random_forest.pkl'

    ############################# ATTENTION MODEL ###############################################################################################
    
    #Function for classify the data as attention or distraction 
    def attention_predict():

        data= pd.read_csv(new_path) #Load data file need to be predict 
        data
        
        model = pickle.load(open(model_path, 'rb')) #Load attention ML model
        predict_df= data.filter(['Gamma_TP10','Beta_TP10','Gamma_AF8', 'Gamma_TP9','Delta_TP9','Delta_AF8','Delta_TP10','Beta_TP9','Beta_AF8','Beta_AF7','Gamma_AF7','Theta_TP9','Theta_TP10','Theta_AF7','Delta_AF7','Alpha_TP10','Alpha_AF7'])

        predict= model.predict(predict_df)
        output= pd.DataFrame({'TimeStamp':data.TimeStamp,'Gamma_TP10':data.Gamma_TP10,'Beta_TP10':predict_df.Beta_TP10,'Gamma_AF8':predict_df.Gamma_AF8,'Gamma_TP9':predict_df.Gamma_TP9,
                            'Delta_TP9':predict_df.Delta_TP9,'Delta_AF8':predict_df.Delta_AF8,'Delta_TP10':predict_df.Delta_TP10,'Beta_TP9':predict_df.Beta_TP9,'Beta_AF8':predict_df.Beta_AF8,'Beta_AF7':predict_df.Beta_AF7,
                            'Gamma_AF7':predict_df.Gamma_AF7,'Theta_TP9':data.Theta_TP9,'Theta_TP10':data.Theta_TP10,'Theta_AF7':predict_df.Theta_AF7,
                            'Delta_AF7':data.Delta_AF7,'Alpha_TP10':predict_df.Alpha_TP10,'Alpha_AF7':predict_df.Alpha_AF7,'Prediction_Class': predict})

        # save predicted attention data file in attention_data Folder
        return output.to_csv('C:/Users/Lenovo/Documents/Uvini Wijesinghe/Research Project/EEG Based Movie Recommendation System/GITLAB/1. V1/2021-114/Recomm/models/final_predicted_data1.csv',index= False) 

    attention_predict()
    predicted_df= pd.read_csv(r'C:/Users/Lenovo/Documents/Uvini Wijesinghe/Research Project/EEG Based Movie Recommendation System/GITLAB/1. V1/2021-114/Recomm/models/final_predicted_data1.csv')

    #Change timestamp format for furuthe calculation
    predicted_df['TimeStamp']= pd.to_datetime(predicted_df['TimeStamp'])
    predicted_df['TimeStamp'] = predicted_df['TimeStamp'].apply(lambda t: t.replace(second=0))
    predicted_df['TimeStamp'] = predicted_df['TimeStamp'].dt.time

    Score_model_df = predicted_df.filter(['TimeStamp','Prediction_Class'])
    Score_model_df['TimeStamp'][0]
    score= {}


    ############################# END OF ATTENTION MODEL ###############################################################################################
    

    ############################# EMOTION MODEL ###############################################################################################
    
    #Function for classify the data as Emaotion categoriesn
    def predict_emotion():
    
        data = pd.read_csv(new_path)
    
        model_emotion = pickle.load(open(model_path_emotion, 'rb'))
        data_sel = data.filter(['Delta_AF8','Beta_AF8','Gamma_AF8','Gamma_TP9','Gamma_TP10'])
        predict= model_emotion.predict(data_sel)
        output = pd.DataFrame({'Delta_AF8':data_sel.Delta_AF8,'Beta_AF8':data_sel.Beta_AF8,'Gamma_AF8':data_sel.Gamma_AF8,'Gamma_TP9':data_sel.Gamma_TP9,'Gamma_TP10':data_sel.Gamma_TP10,'prediction':predict})
        return output.to_csv('C:/Users/Lenovo/Documents/Uvini Wijesinghe/Research Project/EEG Based Movie Recommendation System/GITLAB/1. V1/2021-114/Recomm/models/emotion_predicted.csv',index=False)

    predict_emotion()
    
    predictedEmotion_df= pd.read_csv(r'C:/Users/Lenovo/Documents/Uvini Wijesinghe/Research Project/EEG Based Movie Recommendation System/GITLAB/1. V1/2021-114/Recomm/models/emotion_predicted.csv')
    
    ############################# END OF EMOTION MODEL ###############################################################################################
    
    
    def score_model():
        
        ############################# ATTENTION SCORE MODEL ###############################################################################################
        
        count_rows = predicted_df.shape[0]
        count_high_attention = len(predicted_df[predicted_df["Prediction_Class"]==2])
        count_medium_attention = len(predicted_df[predicted_df["Prediction_Class"]==1])
        count_low_attention = len(predicted_df[predicted_df["Prediction_Class"]==0])

        each_min_high_attention_score = (count_high_attention / count_rows) * 100
        each_min_medium_attention_score = (count_medium_attention / count_rows) * 100
        each_min_low_attention_score = (count_low_attention / count_rows) * 100

        highest_statement = 0
        highest_score_val = 0
        lowest_score_val = 0
        
        if (each_min_high_attention_score > each_min_medium_attention_score) and (each_min_high_attention_score > each_min_low_attention_score):
            highest_score_val = each_min_high_attention_score
            highest_statement = 'Priority in High Attentiveness'
            if each_min_medium_attention_score < each_min_low_attention_score:
                lowest_score_val = each_min_medium_attention_score
            else:
                lowest_score_val = each_min_low_attention_score
 
                
        elif (each_min_high_attention_score < each_min_medium_attention_score) and (each_min_medium_attention_score > each_min_low_attention_score):
            highest_score_val = each_min_medium_attention_score
            highest_statement= 'Priority in Neutral Attentiveness'
            if each_min_high_attention_score < each_min_low_attention_score:
                lowest_score_val = each_min_high_attention_score
            else:
                lowest_score_val = each_min_low_attention_score
 
                
        elif (each_min_high_attention_score < each_min_low_attention_score) and (each_min_medium_attention_score < each_min_low_attention_score):
            highest_score_val = each_min_low_attention_score
            highest_statement= 'Priority in Low Attentiveness'
            if each_min_high_attention_score < each_min_medium_attention_score:
                lowest_score_val = each_min_high_attention_score
            else:
                lowest_score_val = each_min_medium_attention_score
 

        average_score = (highest_score_val - lowest_score_val) / 2
 
        score[1] = each_min_high_attention_score
        score[2] = each_min_low_attention_score
        score[3] = each_min_medium_attention_score
        score[4] = average_score
        score[5] = highest_statement #append ovrall score data\
 
        score_model.each_min_high_attention_score = each_min_high_attention_score
        score_model.each_min_low_attention_score = each_min_low_attention_score
        score_model.each_min_medium_attention_score = each_min_medium_attention_score
        score_model.average_score = average_score
        score_model.highest_statement = highest_statement
        
        ############################# END OF ATTENTION SCORE MODEL ###############################################################################################
        
        
    
        ############################# EMOTION SCORE MODEL ###############################################################################################

        rows = predictedEmotion_df.shape[0]

        happy = len(predictedEmotion_df[predictedEmotion_df["prediction"]==0])
        neutral = len(predictedEmotion_df[predictedEmotion_df["prediction"]==1])
        sad = len(predictedEmotion_df[predictedEmotion_df["prediction"]==2])
        satisfying = len(predictedEmotion_df[predictedEmotion_df["prediction"]==3])
        
        ###########Emotion Score##############
        happy_score = (happy/rows)*100
        neutral_score = (neutral/rows)*100
        sad_score = (sad/rows)*100
        satisfying_score = (satisfying/rows)*100

        high_score = 0
        score_value = 0
        
        #Lable emotion categories as 'Happy = 100,'Neutral =200, Sad= 300,'Satisfying =400, high_score= 500  }
        #append the emotion score to socre dictonary
        
        #EMOTION FINAL OUTPUT

        score[100]= happy_score
        score[200]= neutral_score
        score[300]= sad_score
        score[400]= satisfying_score
        
        print("================")
        print("PRINT DICTIONARY")
        print("100: ", happy_score)
        print("200: ", neutral_score)
        print("300: ", sad_score)
        print("400: ", satisfying_score)
        
        
        if happy_score>neutral_score and happy_score>sad_score and happy_score>satisfying_score:
            high_score = 'Humour'
        elif neutral_score>happy_score and neutral_score>sad_score and neutral_score>satisfying_score:
            high_score = 'Neutral'
        elif sad_score>happy_score and sad_score>neutral_score and sad_score>satisfying_score:
            high_score = 'Sad'
        else:
            high_score = 'Calm'
            
            
            
        if happy_score>neutral_score and happy_score>sad_score and happy_score>satisfying_score:
            score_value = happy_score
        elif neutral_score>happy_score and neutral_score>sad_score and neutral_score>satisfying_score:
            score_value = neutral_score
        elif sad_score>happy_score and sad_score>neutral_score and sad_score>satisfying_score:
            score_value = neutral_score
        else:
            score_value = satisfying_score
        
        out = {'Happy Score': happy_score,'Neutral Score':neutral_score, 'Sad Score': sad_score,'Satisfying Score': satisfying_score,'Category': high_score}
        print(high_score)
        print(score_value)
               
        highest_score = 0
        
        score[500]= highest_score
        score[600]= high_score
        
        print("=============")
        print("PRINT EMOTION")
        print("score_value:",score_value)
        print("status:",high_score)
        
        
        score_model.happy_score = happy_score
        score_model.neutral_score = neutral_score
        score_model.sad_score = sad_score
        score_model.satisfying_score = satisfying_score
        
        score_model.high_score = high_score
        score_model.score_value = score_value
        
        ############################# END OF EMOTION SCORE MODEL ###############################################################################################


    ############################# ENJOYMENT SCORE MODEL ###############################################################################################
    #this is not a machine learning model predction this is statitical approch 
    
    f = max_file
    enjoymentData=enjoyment_evaluation(f) #pass csv file to function and get predicted result
    enjoy= enjoymentData[0]
    notenjoy= enjoymentData[1]

    #Lable enjoyment categories as 'enjoy score = 900,'notenjoy score =1000 }
     #append the emotion score to socre dictonary
    score[900]= enjoy
    score[1000]= notenjoy
    
    ej_score = notenjoy
    
    if(ej_score> 50.00):
        ej_status= "Excellent";
    if(ej_score< 20.00):
        ej_status= "Poor";
    else:
        ej_status= "Average";
                    
    
    notEnjoy = enjoy
    enjoyy = notenjoy
    
    #ENJOYMENT FINAL OUTPUT
    print("Enjoy",enjoyy)
    print("Not Enjoy",notEnjoy)
    print("Status",ej_status)
    
    if enjoyy > notEnjoy:
        highestEjoy = enjoyy;
    else:
        highestEjoy = notEnjoy
        
    print("Highest Enjoy:", highestEjoy)
    
    ############################# END OF ENJOYMENT SCORE MODEL ###############################################################################################

    score_model()
    
    ############################# DB CONNECTION ###############################################################################################

    high_attention_score = score_model.each_min_high_attention_score
    low_attention_score = score_model.each_min_low_attention_score
    medium_attention_score = score_model.each_min_medium_attention_score
    highest_score_atten = score_model.average_score
    highest_state = score_model.highest_statement
    
    
    
    neutral = score_model.happy_score
    happy = score_model.neutral_score
    sad = score_model.sad_score
    satisfy = score_model.satisfying_score
        
    
    highest_emo_status = score_model.high_score    
    high_score_emo = score_model.score_value
    float(high_score_emo)
    
    print("====================")
    print("DB VALUES FOR EMOTION")
    print('happy', happy)
    print('neutral', neutral)
    print('sad', sad)
    print('satisfy', satisfy)
    print('high_score', high_score_emo)
    print('highest_emo', highest_emo_status)
    
    db1 = DBConnectionSco()
    db2 = DBConnectionAtten()
    db3 = DBConnectionEnjoy()
    db4 = DBConnectionEmo()
    
    db5 = DBConnectionRat()
    
    dbCon1 = db1.connectMysql(highest_score_atten,high_score_emo,highestEjoy) 
    dbCon2 = db2.connectMysql(high_attention_score,low_attention_score,medium_attention_score, highest_score_atten, highest_state) 
    dbCon3 = db3.connectMysql(enjoyy,notEnjoy,ej_status) 
    dbCon4 = db4.connectMysql(neutral, happy, sad, satisfy, high_score_emo, highest_emo_status )
    
    dbCon5 = db5.connectMysql(highest_score_atten,high_score_emo,highestEjoy) 
    
    
    return score #return calculated scores dictonary to api port


if __name__ == "__main__":
        app.run(host='0.0.0.0', port = 3019, debug=False)
        # serve(app, port = 3018)