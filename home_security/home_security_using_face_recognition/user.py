import os
import shutil
import uuid
from flask import *
from database import *
import smtplib
from email.mime.text import MIMEText
from flask_mail import Mail
import random

import tensorflow as tf

from core import *

user=Blueprint('user',__name__)

@user.route('/user_home')
def user_home():
    return render_template('user_home.html')



@user.route("/start_cam")
def start_cam():
    sem_id=session['user_id']


    from em import camclick
    
    camclick(sem_id)
    cv2.destroyAllWindows()
    

    tf.keras.backend.clear_session()
    return redirect(url_for('teacher.teacherhome'))


@user.route('/login1',methods=['post'])
def login1():
    username = request.form['username']
    password = request.form['password']

    q="select * from login where username='%s' and password='%s'"%(username,password)
    res=select(q)
    print(res)
    if res:
        
        if res[0]['usertype']=='user':
            login_id=res[0]['login_id'] 
            print(login_id)
            q="select * from users where login_id='%s'"%(login_id)
            result=select(q)
            if result:
                user_id=result[0]['user_id']
                return jsonify(status="ok",lid=login_id,uid=user_id)
            else:
                return jsonify(status="no")
        else:
            return jsonify(status="no")
    else:
            return jsonify(status="no")  
        


@user.route('/register',methods=['post'])
def register():
  
    name= request.form['name']
    email = request.form['email'] 
    phone = request.form['phone']
    place = request.form['place']
    district = request.form['district']
    username = request.form['username']
    password = request.form['password']

    img = request.files['photo']
    path = "static/images/"+str(uuid.uuid4())+img.filename
    img.save(path) 

    qs = "insert into login values(null,'%s','%s','user')"%(username,password)  
    cred = insert(qs)
    
    q = "insert into users values(null,'%s','%s','%s','%s','%s','%s','%s')"%(cred,name,email,phone,place,district,path) 
    insert(q)

    return jsonify(status="ok") 

@user.route('/manage_family',methods=['post'])
def manage_family():
  
    name= request.form['name']
    email = request.form['email'] 
    phone = request.form['phone']
    place = request.form['place']
    district = request.form['district']
    type = request.form['type']
    uid = request.form['uid']

    img = request.files['photo']
    path = "static/images/"+str(uuid.uuid4())+img.filename
    img.save(path) 

    q = "insert into family values(null,'%s','%s','%s','%s','%s','%s','%s','%s')"%(uid,name,email,phone,place,district,type,path) 
    bid=insert(q)
    
    pid = str(bid)
    train_images_folder = os.path.join("static", "trainimages", pid)
    
    if not os.path.isdir(train_images_folder):
            os.makedirs(train_images_folder)

        # Copy the image to the trainimages folder three times with different names
    for i in range(1, 4):  # Loop three times
        destination_image_path = os.path.join(train_images_folder, f"{name}_{i}.jpg")
        shutil.copy(path, destination_image_path)
        
    enf("static/trainimages/")

    return jsonify(status="ok") 

@user.route('/report_to_policestation',methods=['post'])
def report_to_policestation():
  
    description= request.form['description']
    uid = request.form['uid']

    q = "insert into report values(null,'%s','%s','pending','pending')"%(uid,description) 
    insert(q)

    return jsonify(status="ok") 

import datetime
@user.route('/user_send_complaint',methods=['post'])
def user_send_complaint():
  
    description= request.form['complaint']
    date_time = datetime.datetime.now()
    uid = request.form['uid']

    q = "insert into complaint values(null,'%s','%s','pending','%s')"%(uid,description,date_time) 
    insert(q)

    return jsonify(status="ok") 

@user.route('/view_complaint_reply',methods=['post'])
def view_complaint_reply():
    data={}
    uid=request.form['uid']
    q = "select * from complaint where user_id='%s'"%(uid)
    c=select(q)
    data['status']='ok'
    data['q']=c
    return jsonify(data)




@user.route('/view_response',methods=['post'])
def view_response():
    data={}
    uid=request.form['uid']
    q = "select * from report where user_id='%s'"%(uid)
    c=select(q)
    data['status']='ok'
    data['q']=c
    return jsonify(data)






@user.route('/view_visitorlog',methods=['post'])
def view_visitorlog():
    data={}
    uid=request.form['uid']
    q = "select * from visitor_log inner join family using(family_id) where user_id='%s'"%(uid)
    c=select(q)
    data['status']='ok'
    data['q']=c
    return jsonify(data)




@user.route('/view_unknown_alert',methods=['post'])
def view_unknown_alert():
    data={}
    uid=request.form['uid']
    q = "select * from alert where user_id='%s'"%(uid)
    c=select(q)
    data['status']='ok'
    data['q']=c
    return jsonify(data)