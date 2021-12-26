# -*- coding: utf-8 -*-
"""
Created on Sun Dec 26 18:24:15 2021

@author: Saumya
"""

import pickle
import pandas as pd
import os
import json
import dateutil.parser
from dateutil.relativedelta import relativedelta

from flask import Flask, jsonify, request
from flask import render_template, redirect, url_for


model1 = pickle.load(open('D:/Research/Intellihack2.0/NeuroNerds-AdEvaluator/Emotion Coding/model1_random_forest.pkl', 'rb'))
model2 = pickle.load(open('D:/Research/Intellihack2.0/NeuroNerds-AdEvaluator/Emotion Coding/model2_gradient_boost.pkl', 'rb'))
model3 = pickle.load(open('D:/Research/Intellihack2.0/NeuroNerds-AdEvaluator/Emotion Coding/model3_xgboost.pkl', 'rb'))

####Saving data to CSV####
def predict():
    
    data = pd.read_csv('D:/Research/Intellihack2.0/NeuroNerds-AdEvaluator/Emotion Datasets/test.csv')

    data_sel = data.filter(['Delta_AF8','Beta_AF8','Gamma_AF8','Gamma_TP9','Gamma_TP10'])
    predct = model1.predict(data_sel)
    output = pd.DataFrame({'Delta_AF8':data_sel.Delta_AF8,'Beta_AF8':data_sel.Beta_AF8,'Gamma_AF8':data_sel.Gamma_AF8,'Gamma_TP9':data_sel.Gamma_TP9,'Gamma_TP10':data_sel.Gamma_TP10,'prediction':predct})
    return output.to_csv('predicted.csv',index=False)

predict()

######Flask API#########
app = Flask(__name__)

@app.route('/score',methods=['GET'])
def score_model():
    data = pd.read_csv(r'D:/Research/Intellihack2.0/NeuroNerds-AdEvaluator/Emotion Datasets/sad.csv')

    data_sel = data.filter(['Delta_AF8','Beta_AF8','Gamma_AF8','Gamma_TP9','Gamma_TP10'])
    predct = model1.predict(data_sel)
    output = pd.DataFrame({'Delta_AF8':data_sel.Delta_AF8,'Beta_AF8':data_sel.Beta_AF8,'Gamma_AF8':data_sel.Gamma_AF8,'Gamma_TP9':data_sel.Gamma_TP9,'Gamma_TP10':data_sel.Gamma_TP10,'prediction':predct})

    ##########Counting number of rows###########
    rows = output.shape[0]
    score= {}
    
    happy = len(output[output["prediction"]==0])
    neutral = len(output[output["prediction"]==1])
    sad = len(output[output["prediction"]==2])
    satisfying = len(output[output["prediction"]==3])

    ###########Emotion Score##############
    happy_score = (happy/rows)*100
    neutral_score = (neutral/rows)*100
    sad_score = (sad/rows)*100
    satisfying_score = (satisfying/rows)*100

    high_score = 0
    score_value = 0
        
    #Lable emotion categories as 'Happy = 100,'Neutral =200, Sad= 300,'Satisfying =400, high_score= 500  }
    #append the emotion score to socre dictonary
        
        
    score[100]= happy_score
    score[200]= neutral_score
    score[300]= sad_score
    score[400]= satisfying_score
                
    print("####################")
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
        
    print("###############")
    print("EMOTION")        
    print("score_value:",score_value)
    print("status:",high_score)
               
    score_model.happy_score = happy_score
    score_model.neutral_score = neutral_score
    score_model.sad_score = sad_score
    score_model.satisfying_score = satisfying_score
                
    score_model.high_score = high_score
    score_model.score_value = score_value
    
if __name__ == "__main__":
        app.run(host='0.0.0.0', port = 3019, debug=False)
        # serve(app, port = 3018)