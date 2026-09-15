from flask import *
from database import *
import smtplib
from email.mime.text import MIMEText
from flask_mail import Mail
import random

public=Blueprint('public',__name__)

@public.route('/')
def public_home():
    return render_template('public/public_home.html')

@public.route('/login',methods=['get','post'])
def login():
    if 'submit' in request.form:
        usernamee=request.form['username']
        passwordd=request.form['Password']
        fu="select * from login where username ='%s' and password='%s'"%(usernamee,passwordd)
        res=select(fu)
        if res:
            session['login_id']=res[0]['login_id']
            if res[0]['usertype']=='admin':
                return redirect(url_for('admin.admin_home'))
            elif res[0]['usertype']=='police':
                gt="select * from police where login_id='%s'"%(session['login_id'])
                tes=select(gt)
                if tes:
                    session['police_id']=tes[0]['police_id']
                return redirect(url_for('police.police_home'))
            elif res[0]['usertype']=='user':
                pt="select * from users where login_id='%s'"%(session['login_id'])
                kis=select(pt)
                if kis:
                    session['user_id']=kis[0]['user_id']
                return redirect(url_for('user.user_home'))
            return '''<script>alert('invalid username or password');window.location='/login';</script>'''            
    return render_template('public/login.html')   