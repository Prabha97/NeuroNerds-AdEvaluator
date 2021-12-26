
import pandas as pd
import numpy as np

def enjoyment_evaluation(csv):
    
    subject_data=pd.read_csv(csv)
    print("CSV")
    print(csv)
    
    subject_data=subject_data.drop(['TimeStamp','Elements','Battery','HeadBandOn','Gyro_X','Gyro_Y','Gyro_Z','Accelerometer_Z',
                    'Accelerometer_Y','Accelerometer_X','HSI_TP9','HSI_TP10','HSI_AF7','HSI_AF8','RAW_TP9','RAW_TP10',
                    'RAW_AF7','RAW_AF8','AUX_RIGHT','Theta_AF7','Theta_AF8','Theta_TP9','Theta_TP10','Delta_AF7','Delta_AF8','Delta_TP9','Delta_TP10',
                    'Alpha_AF7','Alpha_AF8','Alpha_TP9','Alpha_TP10'], axis=1)
    subject_data=subject_data.dropna()
    subject_data['beta_median']=subject_data[['Beta_AF7','Beta_AF8','Beta_TP9','Beta_TP10']].median(axis=1)
    subject_data['gamma_median']=subject_data[['Gamma_AF7','Gamma_AF8','Gamma_TP9','Gamma_TP10']].median(axis=1)
    subject_data['Grade']=""
    #mu_beta, sd_beta, mu_gamma, sd_gamma= 0.506,0.129994,0.085,0.0118294
    subject_data[['beta_median', 'gamma_median', 'Grade']] = subject_data[['beta_median', 'gamma_median', 'Grade']].apply(pd.to_numeric)
    #subject_data=subject_data.dropna()
    subject_data['beta_median']=subject_data['beta_median'].round(3)
    subject_data['gamma_median']=subject_data['gamma_median'].round(3)
    
    for index, row in subject_data.iterrows():
        if(0.484 <=row["beta_median"]) & (row["beta_median"]<= 0.528):
            subject_data.at[index, 'Grade'] = 1
        elif( 0.067 <= row["gamma_median"]) & (row["gamma_median"] <= 0.103):
            subject_data.at[index, 'Grade'] = 1
        else:
            subject_data.at[index, 'Grade'] = 2
            
    result=subject_data['Grade'].value_counts(normalize=True) * 100
    result=result.round(3)
    #result=subject_data['Grade'].to_list()
    return result.to_list()
