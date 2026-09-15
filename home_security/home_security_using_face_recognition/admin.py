import uuid
from django.http import JsonResponse
from flask import *
from database import *

admin=Blueprint ('admin',__name__)

@admin.route('/admin_home')
def admin_home():
    return render_template('admin/admin_home.html')  


@admin.route('/admin_manage_police',methods=['get','post'])
def admin_manage_police():
    data={}
    if 'submit' in request.form:
        name=request.form['name']
        address=request.form['address']
        email=request.form['email']
        phone=request.form['phone']
        place=request.form['place']
        pin=request.form['pin']
        image=request.files['image']
        path="static/"+str(uuid.uuid4())+image.filename
        image.save(path)
        username=request.form['username']
        password=request.form['password']

        j="insert into login values(null,'%s','%s','police')"%(username,password)
        l=insert(j)
        p="insert into police values(null,'%s','%s','%s','%s','%s','%s','%s','%s')"%(l,name,phone,email,address,place,pin,path)
        insert(p)   
        return redirect(url_for('admin.admin_manage_police'))

    x="select * from police"
    data['police']=select(x)

    if 'action' in request.args:
        action=request.args['action']
        loginid=request.args['logid']
    else:
        action=None
    
    
    if action=='update':
        u="select * from police where login_id='%s'"%(loginid)
        data['updatess']=select(u)

    if 'update' in request.form:
        name=request.form['name']
        address=request.form['address']
        email=request.form['email']
        phone=request.form['phone']
        place=request.form['place']
        pin=request.form['pin']
        image=request.files['image']
        path="static/"+str(uuid.uuid4())+image.filename
        image.save(path)
        y="update police set name='%s',phone='%s',email='%s',address='%s',place='%s',pin='%s',photo='%s' where login_id='%s'"%(name,phone,email,address,place,pin,path,loginid)
        update(y )
        return redirect(url_for('admin.admin_manage_police'))
    
    if action=='delete':
        p="delete from police where login_id='%s'"%(loginid)
        delete(p)
        o="delete from login where login_id='%s'"%(loginid)
        return redirect(url_for('admin.admin_manage_police'))

    return render_template('admin/admin_manage_police.html',data=data)


@admin.route('/admin_view_complaints',methods=['get','post'])
def admin_view_complaints():
    data={}

    ko="SELECT * FROM complaint INNER JOIN users USING(user_id)"
    data['complaint']=select(ko)

    return render_template('admin/admin_view_complaints.html',data=data)

@admin.route('/admin_send_complaint_reply',methods=['get','post'])
def admin_send_complaint_reply():
    data={}

    if 'complaint' in request.args:
        complaintid=request.args['complaint']
    else:
        action=None

    if 'submit' in request.form:
        reply=request.form['reply']
        po="update complaint set reply='%s' where complaint_id='%s'"%(reply,complaintid)
        update(po)
        return redirect(url_for('admin.admin_view_complaints'))

    return render_template('admin/admin_send_complaint_reply.html',data=data)


@admin.route('/admin_view_users',methods=['get','post'])
def admin_view_users():
    data={}

    u="select * from users"
    data['users']=select(u)

    return render_template('admin/admin_view_users.html',data=data)