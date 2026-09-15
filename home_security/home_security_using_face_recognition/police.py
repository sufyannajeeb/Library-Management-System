import uuid
from django.http import JsonResponse
from flask import *
from database import *

police=Blueprint ('police',__name__)

@police.route('/police_home')
def police_home():
    return render_template('police/police_home.html')



@police.route('/police_view_report',methods=['get','post'])
def police_view_report():
    data={} 

    ko="SELECT * FROM report INNER JOIN users USING(user_id)"
    data['report']=select(ko)

    if 'action' in request.args:
        action=request.args['action']
        reportid=request.args['report']
    else:
        action=None

    if action=='takeaction':
        u="update report set status='Action taken' where report_id='%s'"%(reportid)
        update(u)
        return redirect(url_for('police.police_view_report'))

    return render_template('police/police_view_report.html',data=data)

@police.route('/police_send_report_reply',methods=['get','post'])
def police_send_report_reply():
    data={}

    if 'report' in request.args:
        reportid=request.args['report']
    else:
        action=None

    if 'submit' in request.form:
        reply=request.form['reply']
        po="update report set reply='%s' where report_id='%s'"%(reply,reportid)
        update(po)
        return redirect(url_for('police.police_view_report'))

    return render_template('police/police_send_report_reply.html',data=data)

@police.route('/police_view_complaint',methods=['get','post'])
def police_view_complaint():
    data={}

    ko="SELECT * FROM complaint INNER JOIN users USING(user_id)"
    data['complaint']=select(ko)

    return render_template('police/police_view_complaint.html',data=data)

@police.route('/police_send_complaint_reply',methods=['get','post'])
def police_send_complaint_reply():
    data={}

    if 'complaint' in request.args:
        complaintid=request.args['complaint']
    else:
        action=None

    if 'submit' in request.form:
        reply=request.form['reply']
        po="update complaint set reply='%s' where complaint_id='%s'"%(reply,complaintid)
        update(po)
        return redirect(url_for('police.police_view_complaint'))

    return render_template('police/police_send_complaint_reply.html',data=data)