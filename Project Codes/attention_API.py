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

######Flask API#########
app = Flask(__name__)
cors = CORS(app)
############Web App##############
@app.route('/score',methods=['GET'])
def index():
    new_path = 'F:/NeuroNerds-AdEvaluator/Project Data/thilina_EEG.csv'
    model_path = 'F:/NeuroNerds-AdEvaluator/Project Codes/PredictiveModels/svc_model_attention_model.pkl'

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
        return output.to_csv('E:/4th Year/EEG_RESEARCH_2021/2021-114/Project Data/final_predicted_data.csv',index= False) 

    attention_predict()
    predicted_df= pd.read_csv(r'F:/NeuroNerds-AdEvaluator/Project Data/final_predicted_data1.csv')

    #Change timestamp format for furuthe calculation
    predicted_df['TimeStamp']= pd.to_datetime(predicted_df['TimeStamp'])
    predicted_df['TimeStamp'] = predicted_df['TimeStamp'].apply(lambda t: t.replace(second=0))
    predicted_df['TimeStamp'] = predicted_df['TimeStamp'].dt.time

    Score_model_df = predicted_df.filter(['TimeStamp','Prediction_Class'])
    Score_model_df['TimeStamp'][0]
    score = {}

    def score_model():
        
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
        #statement = ''
        
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

    
        #attention_score[min] = (High_attention_score[min] + Medium_attention_score[min] + Low_attention_score[min])
        #print('Overall Row count : ', count_rows)

        #print("_____________________________________________________________")

        #print('High Attention Row count:', count_high_attention)
        #print('Medium Attention Row count:', count_medium_attention)
        #print('Low Attention Row count:', count_low_attention)

        #print("_____________________________________________________________")

        #print('High Attention overall percentage : ', each_min_high_attention_score,"%")
        #print('Medium Attention overall percentage : ', each_min_medium_attention_score,"%")
        #print('Low Attention overall percentage : ', each_min_low_attention_score,"%")

    
    score_model()
    
    return score

if __name__ == "__main__":
        #app.run(host='0.0.0.0', port = 3019, debug=False)
        app.run(host='0.0.0.0', port = 3019, debug=False)