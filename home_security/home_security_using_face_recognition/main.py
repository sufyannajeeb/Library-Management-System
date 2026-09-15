from flask import *
from database import *
from public import public
from admin import admin
from police import police
from user import user



app=Flask(__name__)
app.register_blueprint(public)
app.register_blueprint(admin)
app.register_blueprint(police)
app.register_blueprint(user)

app.secret_key='hloo'

app.run(debug=True,port=5004,host="0.0.0.0") 