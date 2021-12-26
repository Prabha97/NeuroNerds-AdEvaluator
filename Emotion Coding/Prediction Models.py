# -*- coding: utf-8 -*-
"""
Created on Sun Dec 26 18:08:34 2021

@author: Saumya
"""

import pandas as pd
import matplotlib.pyplot as plt
import statsmodels.api as sm
import pickle

from sklearn.model_selection import train_test_split
####Label encoder
from sklearn import preprocessing
from sklearn.ensemble import RandomForestClassifier
from sklearn.ensemble import GradientBoostingClassifier
from xgboost import XGBClassifier

####For Feature Selection 4
from sklearn.feature_selection import RFE
from sklearn.linear_model import LogisticRegression

import warnings
warnings.filterwarnings('ignore')

data = pd.read_csv("D:/Research/Intellihack2.0/NeuroNerds-AdEvaluator/Emotion Datasets/Filtered_EEG_Data.csv")
#data.head()
#data.info()

#remove null values 
for col in data.columns:
    data= data.dropna(subset=[col],axis=0)
data.isnull().sum().sort_values(ascending=False)

df = data.filter(['Delta_TP9','Delta_AF7','Delta_AF8','Delta_TP10','Theta_TP9','Theta_AF7','Theta_AF8','Theta_TP10','Alpha_TP9','Alpha_AF7','Alpha_AF8','Alpha_TP10','Beta_TP9','Beta_AF7','Beta_AF8','Beta_TP10','Gamma_TP9','Gamma_AF7','Gamma_AF8','Gamma_TP10','RAW_TP9','RAW_AF7','RAW_AF8','RAW_TP10','emotion'])
#df.head()

# label_encoder object knows how to understand word labels. 
label_encoder = preprocessing.LabelEncoder() 
df['emotion']= label_encoder.fit_transform(df['emotion']) 
#df.head()

data.shape

########### Beta and Gamma should outperform other waves when emotion recognising #################

########## METHOD 1: filtering features based on pearson correlations and correlation matrix###########
##################Checking highest correlated features######################
corr = df.corr()
corr_y = abs(corr["emotion"])
highest_corr = corr_y[corr_y >0.4]
highest_corr.sort_values(ascending=True)

######Selecting the higher related signals#######
######Selecting the higher related signals#######
eeg_sel = df.filter(['Theta_TP10','Beta_TP9','Beta_TP10','Gamma_TP9','Gamma_TP10','emotion'])
corr = eeg_sel.corr()
sm.graphics.plot_corr(corr, xnames=list(corr.columns))
#plt.show()

Sel_X = df.iloc[:,0:20]
Sel_Y = df.iloc[:,-1]

model_f = XGBClassifier()
model_f.fit(Sel_X,Sel_Y)
#print(model_f.feature_importances_) #using inbuilt class feature_importances of tree based classifiers

model_l = LogisticRegression()
rfe = RFE(model_l, 5)
fit = rfe.fit(Sel_X, Sel_Y)

eeg_sel
eeg_sel2 = df.filter(['Delta_AF8','Beta_AF8','Gamma_AF8','Gamma_TP9','Gamma_TP10','emotion'])
X = eeg_sel2.drop(['emotion'],axis=1) 
Y = eeg_sel2['emotion']

X_Train, X_Test, Y_Train, Y_Test = train_test_split(X, Y, test_size = 0.30,random_state = 0)

############# MODEL1 ################
############Random Forest#############
model1= RandomForestClassifier(n_estimators=20,random_state=0)
model1.fit(X_Train, Y_Train)
pred1 = model1.predict(X_Test)
model1.score(X_Test,Y_Test)

#########ACCURACY CHECK ###############
# Confusion Matrix
from sklearn.metrics import confusion_matrix
print(confusion_matrix(Y_Test,pred1) )

# Accuracy
from sklearn.metrics import accuracy_score
print("accuracy score :", accuracy_score(Y_Test, pred1))

# Recall
from sklearn.metrics import recall_score
print("recall score :", recall_score(Y_Test, pred1, average=None))

# Precision
from sklearn.metrics import precision_score
print("precision score :",precision_score(Y_Test, pred1, average=None))

####F1 Score####
from sklearn.metrics import f1_score
print("F1 Score :", f1_score(Y_Test, pred1, average=None))

from sklearn.metrics import classification_report
print(classification_report(Y_Test, pred1, labels=[0,1,2,3]))

#Generate pickle file
pickle.dump(model1, open('model1_random_forest.pkl','wb'))

############## MODEL2 ###################
##########GRADIENT BOOST ###############
model2= GradientBoostingClassifier(learning_rate=0.01,random_state=1)
model2.fit(X_Train, Y_Train)
pred2 = model2.predict(X_Test)
model2.score(X_Test,Y_Test)

#########ACCURACY CHECK ###############
# Confusion Matrix
from sklearn.metrics import confusion_matrix
confusion_matrix(Y_Test,pred2) 

# Accuracy
from sklearn.metrics import accuracy_score
print("accuracy score:", accuracy_score(Y_Test, pred2))

# Recall
from sklearn.metrics import recall_score
print("recall score :", recall_score(Y_Test, pred2, average=None))

# Precision
from sklearn.metrics import precision_score
print("precision score :", precision_score(Y_Test, pred2, average=None))

####F1 Score####
from sklearn.metrics import f1_score
print("F1 score :", f1_score(Y_Test, pred2, average=None))

from sklearn.metrics import classification_report
print(classification_report(Y_Test, pred2, labels=[0,1,2,3]))

#Generate pickle file
pickle.dump(model2, open('model2_gradient_boost.pkl','wb'))

############# MODEL3 ##################
############XGBOOST##################
model3 = XGBClassifier()
model3.fit(X_Train, Y_Train)
pred3 = model3.predict(X_Test)
model3.score(X_Test,Y_Test)

#########ACCURACY CHECK ###############
# Confusion Matrix
from sklearn.metrics import confusion_matrix
confusion_matrix(Y_Test,pred3)

# Accuracy
from sklearn.metrics import accuracy_score
print("accuracy score :", accuracy_score(Y_Test, pred3))

# Recall
from sklearn.metrics import recall_score
print("recall score :", recall_score(Y_Test, pred3, average=None))

# Precision
from sklearn.metrics import precision_score
print("precision score :", precision_score(Y_Test, pred3, average=None))

####F1 Score####
from sklearn.metrics import f1_score
print("F1 score :", f1_score(Y_Test, pred3, average=None))

from sklearn.metrics import classification_report
print(classification_report(Y_Test, pred3, labels=[0,1,2,3]))

#Genetate pickle file
pickle.dump(model3, open('model3_xgboost.pkl','wb'))