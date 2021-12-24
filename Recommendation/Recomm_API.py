
from flask import Flask,request,jsonify

from DBConnection import DBConnection

from HybridTest import HybridTest
from RBMBakeOff import RBMBakeOff
from ContentRecs import ContentRecs
from SimpleUserCF import CollaborativeFilteringUser
from SimpleItemCF import CollaborativeFilteringItem
import json 
from flask_cors import CORS,cross_origin
from KNNBakeOff import KNNBakeOff


app = Flask(__name__)
cors = CORS(app)



#HYBRID ALGORITHM =============================================================
final_rec_dict = {}
@app.route('/recomme',methods =['POST'])
def recomme():
    reques_data = request.get_json()
    user_id = int(reques_data['id']) 
    count = int(reques_data['number'])
    db1 = DBConnection()
    dbCon = db1.connectMysql()
    final_rec = HybridTest.recomm(user_id,count)
    final_rec_dict.clear()
    final_rec_dict['key1']=final_rec
    return final_rec_dict

#RBM ALGORITHM =============================================================
# =============================================================================
# final_rec_dict = {}
# @app.route('/recomme',methods =['POST'])
# def recomme():
#     reques_data = request.get_json()
#     user_id = int(reques_data['id']) 
#     count = int(reques_data['number'])
#     db1 = DBConnection()
#     dbCon = db1.connectMysql()
#     final_rec = RBMBakeOff.rbmBakeOff(user_id,count)
#     final_rec_dict.clear()
#     final_rec_dict['key1']=final_rec
#     return final_rec_dict
# =============================================================================

#CONTENT BASED ALGORITHM ======================================================
# =============================================================================
# final_rec_dict = {}
# @app.route('/recomme',methods =['POST'])
# def recomme():
#     reques_data = request.get_json()
#     user_id = int(reques_data['id']) 
#     count = int(reques_data['number'])
#     #final_rec = HybridTest.recomm(user_id,count)
#     #print("Call ContentRecs.py")
#     final_rec = ContentRecs.ContentBased(user_id,count)
#     #print("After ContentBased function")
#     final_rec_dict.clear()
#     final_rec_dict['key1']=final_rec
#     print("final_rec_dict")
#     #print(final_rec_dict)
#     return final_rec_dict
# 
# =============================================================================

#COLLABORATIVE FILTERING USER =================================================
# =============================================================================
# final_rec_dict = {}
# @app.route('/recomme',methods =['POST'])
# def recomme():
#     reques_data = request.get_json()
#     user_id = int(reques_data['id']) 
#     count = int(reques_data['number'])
#     #final_rec = HybridTest.recomm(user_id,count)
#     #print("Call ContentRecs.py")
#     final_rec = CollaborativeFilteringUser.SimpleUserCF(user_id,count)
#     #print("After ContentBased function")
#     final_rec_dict.clear()
#     final_rec_dict['key1']=final_rec
#     return final_rec_dict
# =============================================================================


#COLLABORATIVE FILTERING ITEM =================================================
# =============================================================================
# final_rec_dict = {}
# @app.route('/recomme',methods =['POST'])
# def recomme():
#     reques_data = request.get_json()
#     user_id = int(reques_data['id']) 
#     count = int(reques_data['number'])
#     #final_rec = HybridTest.recomm(user_id,count)
#     print("Call ContentRecs.py")
#     final_rec = CollaborativeFilteringItem.SimpleItemCF(user_id,count)
#     print("After ContentBased function")
#     final_rec_dict.clear()
#     final_rec_dict['key1']=final_rec
#     return final_rec_dict  
# 
# =============================================================================

# KNN BAKE OFF (COLLABORATIVE FILTERING) ======================================
# =============================================================================
# final_rec_dict = {}
# @app.route('/recomme',methods =['POST'])
# def recomme():
#     reques_data = request.get_json()
#     user_id = int(reques_data['id']) 
#     count = int(reques_data['number'])
#     #final_rec = HybridTest.recomm(user_id,count)
#     print("Call ContentRecs.py")
#     final_rec = KNNBakeOff.knnBakeOff(user_id,count)
#     print("After ContentBased function")
#     final_rec_dict.clear()
#     final_rec_dict['key1']=final_rec
#     return final_rec_dict  
# =============================================================================



@app.route('/getrecomme',methods =['GET'])
def getrecomme():
    return final_rec_dict




if __name__ == '__main__':
	app.run(debug=False,use_reloader=False)