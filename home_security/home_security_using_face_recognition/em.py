import os
from flask import session
import tensorflow as tf
import keras
import cv2
from keras.models import model_from_json
from keras.preprocessing import image
from keras.preprocessing.image import ImageDataGenerator

import numpy as np


import face_recognition
import pickle
from datetime import datetime
from core import rec_face_image
from database import *
import tensorflow as tf
from keras.models import Sequential

# Build and train your Keras model

# OpenCV-related code


model = model_from_json(open(r"model\facial_expression_model_structure.json", "r").read())
model.load_weights(r'model\facial_expression_model_weights.h5')  # load weights



face_cascade = cv2.CascadeClassifier(r'model\haarcascade_frontalface_default.xml')

# ... add layers to the model



# Clear the Keras session


cap = cv2.VideoCapture(0)



emotions = ('angry', 'disgust', 'fear', 'happy', 'sad', 'surprise', 'neutral')

def camclick(sem_id):
   
    # i=0
    cap = cv2.VideoCapture(0)

    emotions = ('angry', 'disgust', 'fear', 'happy', 'sad', 'surprise', 'neutral')

    model = model_from_json(open(r"model\facial_expression_model_structure.json", "r").read())
    model.load_weights(r'model\facial_expression_model_weights.h5')

    face_cascade = cv2.CascadeClassifier(r'model\haarcascade_frontalface_default.xml')

    while True:
        ret, img = cap.read()

        if not ret or img is None:
            print("Error: Unable to capture frame.")
            break

        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

        faces = face_cascade.detectMultiScale(gray, 1.3, 5)

        for (x, y, w, h) in faces:
            cv2.rectangle(img, (x, y), (x+w, y+h), (255, 0, 0), 2)

            detected_face = img[int(y):int(y+h), int(x):int(x+w)]
            detected_face = cv2.cvtColor(detected_face, cv2.COLOR_BGR2GRAY)
            detected_face = cv2.resize(detected_face, (48, 48))
            img_pixels = image.img_to_array(detected_face)
            img_pixels = np.expand_dims(img_pixels, axis=0)
            img_pixels /= 255

            predictions = model.predict(img_pixels)
            max_index = np.argmax(predictions[0])
            emotion = emotions[max_index]
            cv2.putText(img, emotion, (x, y-5), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 2)

            FaceFileName = "static/test.jpg"
            cv2.imwrite(FaceFileName, detected_face)

            val = rec_face_image(FaceFileName,img)
            print("VAL............", val)
            print("user", val)
            str1 = ""
            
            for ele in val:
                str1 = ele
                print(str1)
                val = str1.replace("'", "")
                print("val : ", val)
                
                for i in val:
                    
                    qry="insert into visitor_log values(null,'%s',curdate(),curtime())"%(i)
                    insert(qry)
                    # q = "select * from student where studentid='%s'" % (i)
                    # res = select(q)
                    # print("////////////////////////////////", res)
                    
                    # if res:
                    #     q2 = "select * from attendance where studentid='%s' and datetime=curdate()" % (i)
                    #     print(q2)
                    #     res2 = select(q2)
                        
                    #     if  res2:

                    #         return """<script>alert('vbshdcbsc');window.location="/adminhome"</script>"""
                    #     else:     
                    #         qb = "insert into attendance values(NULL,'%s',curdate(),'%s')" % (i,sem_id)
                    #         insert(qb)
        cv2.imshow('img', img)

        # Check for the 'q' key press
        key = cv2.waitKey(1) & 0xFF
        if key == ord('q'):
            break

    # Release video capture resources
    cap.release()
    cv2.destroyAllWindows()

    # Clear Keras session
    tf.keras.backend.clear_session()




# /////////////////////////////////////////
# recognize face image
import time
                                                                                      
                                                                                            
def rec_face_image(imagepath,img):
    
    last_insert_time = session.get('last_insert_time', 0)
    current_time = time.time()
    
    
    print("hy...........",imagepath,last_insert_time,current_time)

    data = pickle.loads(open('faces.pickles', "rb").read())
    # print("DATA : ",data)

    # load the input image and convert it from BGR to RGB
    image = cv2.imread(imagepath)
    # print("image : ", image)
    h,w,ch=image.shape
    # print("CH : ",ch)
    rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    # print("RGB : ",rgb)

    # detect the (x, y)-coordinates of the bounding boxes corresponding
    # to each face in the input image, then compute the facial embeddings
    # for each face
    print("[INFO] recognizing faces...")
    boxes = face_recognition.face_locations(rgb,
        model='hog')
    encodings = face_recognition.face_encodings(rgb, boxes)
    # print("encodings : ",encodings)

    # initialize the list of names for each face detected
    names = []
    if current_time - last_insert_time >= 100:
    # loop over the facial embeddings
        for encoding in encodings:
            # attempt to match each face in the input image to our known
            # encodings
            matches = face_recognition.compare_faces(data["encodings"],
                encoding,tolerance=0.4)
            print("matches : ",matches)
            name = "Unknown"

            # check to see if we have found a match
            if True in matches:
                # find the indexes of all matched faces then initialize a
                # dictionary to count the total number of times each face
                # was matched
                matchedIdxs = [i for (i, b) in enumerate(matches) if b]
                counts = {}

                # loop over the matched indexes and maintain a count for
                # each recognized face face
                for i in matchedIdxs:

                    name = data["names"][i]
                    counts[name] = counts.get(name, 0) + 1
                print(counts, " rount ")
                # determine the recognized face with the largest number of
                # votes (note: in the event of an unlikely tie Python will
                # select first entry in the dictionary)
                if len(counts) == 1:
                    name = max(counts, key=counts.get)
                else:
                    name = "-1"
            # update the list of names
            # if name not in names:
            if name != "Unknown":
                names.append(name)
            else:
                b=session['user_id']
                frame_name = f'frame_{b}_{int(time.time())}.jpg'
                frame_path = os.path.join("static/", frame_name)
                cv2.imwrite(frame_path,img)
                
                pa=""
                qry="insert into alert values(null,'%s','%s',curdate(),curtime())"%(session['user_id'],frame_path)
                insert(qry)
        session['last_insert_time'] = current_time

    tf.keras.backend.clear_session()
    
    return names


    # ////////////////////////////////////////////////////////


# from flask import session
# import tensorflow as tf
# import keras
# import cv2
# from keras.models import model_from_json
# from keras.preprocessing import image
# from keras.preprocessing.image import ImageDataGenerator
# import numpy as np
# import face_recognition
# import pickle
# from datetime import datetime
# from core import rec_face_image
# from database import *

# # Load face recognition data
# data = pickle.loads(open('faces.pickles', 'rb').read())

# # Load Keras model
# model = model_from_json(open(r"model\facial_expression_model_structure.json", "r").read())
# model.load_weights(r'model\facial_expression_model_weights.h5')

# # Load Haarcascades for face detection
# face_cascade = cv2.CascadeClassifier(r'model\haarcascade_frontalface_default.xml')

# # Initialize video capture
# cap = cv2.VideoCapture(0)

# emotions = ('angry', 'disgust', 'fear', 'happy', 'sad', 'surprise', 'neutral')

# def camclick():
#     while True:
#         # Capture frame
#         ret, img = cap.read()

#         # Check if frame is valid
#         if not ret or img is None:
#             print("Error: Unable to capture frame.")
#             break

#         # Convert frame to grayscale
#         gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

#         # Detect faces in the frame
#         faces = face_cascade.detectMultiScale(gray, 1.3, 5)

#         for (x, y, w, h) in faces:
#             cv2.rectangle(img, (x, y), (x+w, y+h), (255, 0, 0), 2)

#             # Extract and preprocess the detected face
#             detected_face = img[int(y):int(y+h), int(x):int(x+w)]
#             detected_face = cv2.cvtColor(detected_face, cv2.COLOR_BGR2GRAY)
#             detected_face = cv2.resize(detected_face, (48, 48))
#             img_pixels = image.img_to_array(detected_face)
#             img_pixels = np.expand_dims(img_pixels, axis=0)
#             img_pixels /= 255

#             # Predict emotion using the loaded Keras model
#             predictions = model.predict(img_pixels)
#             max_index = np.argmax(predictions[0])
#             emotion = emotions[max_index]
#             cv2.putText(img, emotion, (x, y-5), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 2)

#             # Save the detected face image
#             FaceFileName = "static/test.jpg"
#             cv2.imwrite(FaceFileName, detected_face)

#             # Recognize face using face_recognition module
#             val = rec_face_image(FaceFileName)
#             print("VAL............", val)
#             print("user", val)
#             str1 = ""
            
#             for ele in val:
#                 str1 = ele
#                 print(str1)
#                 val = str1.replace("'", "")
#                 print("val : ", val)
                
#                 for i in val:
#                     q = "select * from students where student_id='%s'" % (i)
#                     res = select(q)
#                     print("////////////////////////////////", res)
                    
#                     if res:
#                         q2 = "select * from attendance where student_id='%s' and datetime=curdate()" % (i)
#                         print(q2)
#                         res2 = select(q2)
                        
#                         if res2:
#                             qa = "update attendance set datetime=curdate() where student_id='%s'" % (i)
#                             update(qa)
#                         else:
#                             qb = "insert into attendance values(NULL,'%s',curdate())" % (i)
#                             insert(qb)

#         cv2.imshow('img', img)
#         if cv2.waitKey(1) & 0xFF == ord('q'):
#             break

#     # Release video capture and close OpenCV windows
#     cap.release()
#     cv2.destroyAllWindows()

# # Recognize face image using face_recognition module
# def rec_face_image(imagepath):
#     print("hy...........", imagepath)
#     image = cv2.imread(imagepath)
    
#     if image is None:
#         print("Error: Unable to read the image.")
#         return []

#     rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
#     boxes = face_recognition.face_locations(rgb, model='hog')
#     encodings = face_recognition.face_encodings(rgb, boxes)
#     names = []

#     for encoding in encodings:
#         matches = face_recognition.compare_faces(data["encodings"], encoding, tolerance=0.4)
#         name = "Unknown"

#         if True in matches:
#             matchedIdxs = [i for (i, b) in enumerate(matches) if b]
#             counts = {}

#             for i in matchedIdxs:
#                 name = data["names"][i]
#                 counts[name] = counts.get(name, 0) + 1

#             if len(counts) == 1:
#                 name = max(counts, key=counts.get)
#             else:
#                 name = "-1"

#         if name != "Unknown":
#             names.append(name)

#     return names

# # Call the main function
# if __name__ == "__main__":
#     camclick()
