"""
Created on Wed Jul 24 10:34:02 2024

@author: hadel
"""


import pandas as pd
import numpy as np
import tensorflow as tf

print("Hello world")

# part 1 - Data prreprocessing
#Import Dataset
dataset = pd.read_csv("C:\Hadeel\MachineLearning\Churn_Modelling\Churn_Modelling.csv")


# data.columns = data.columns.str.strip()

# x -> input , y-> output

x = dataset.iloc[:, 3:-1].values # all the rows that mach the col that we want to contain from col=3 to the last one
y = dataset.iloc[:, -1].values # [:,  -> from the first to the end

#Data check 
#TBD...





#Encoding Data -> 
# we want to have all the data to be numbers (like the gender and the geography fild) 
# lable Encoding

from sklearn.preprocessing import LabelEncoder

le = LabelEncoder()

x[:,2] = le.fit_transform(x[:,2]) #just for the second column

#one hot encoder - "geography"
# here we use diff function for this fild because the valuse dose not have any conection between the value
# we can use the also for the gender 

from sklearn.compose import ColumnTransformer
from sklearn.preprocessing import OneHotEncoder

ct = ColumnTransformer(
    transformers=[
        ('encoder', OneHotEncoder(), [1])
        ],
          remainder='passthrough')

#France -> [1,0,0]
#spain  -> [0,1,0]
#Germany-> [0,0,1]

x = np.array(ct.fit_transform(x))

#####the method is to do the training 80% from the database and for the test we use 20% from the database

# Splitting the dataset into the Training set and Test set

from sklearn.model_selection import train_test_split
x_train, x_test, y_train, y_test = train_test_split(x, y, test_size = 0.2 , random_state = 0)

# Feature Scaling
# Rescaling for the big number to be small number
from sklearn.preprocessing import StandardScaler
sc = StandardScaler()
x_train = sc.fit_transform(x_train)
x_test = sc.fit_transform(x_test)


# Building the ANN
# we want to build the graph
ann = tf.keras.models.Sequential()
ann.add(tf.keras.layers.Dense(6,activation = 'relu'))
ann.add(tf.keras.layers.Dense(6,activation = 'relu'))

#adding out put layer
ann.add(tf.keras.layers.Dense(1,activation = 'sigmoid'))



# Training the ANN
ann.compile(optimizer = 'adam', loss = 'binary_crossentropy', metrics= ['accuracy']) # accuracy -> if ther 90 true from 100 so the accuracy=90% or accuracy = 0.9
ann.fit(x_train , y_train, batch_size = 32, epochs=100) # we deside the batch_size so we try diff value each time, the epochs is for how much time it repit the trainning



# Predicting the Test set results
y_pred = ann.predict(x_test)
y_pred = (y_pred > 0.5)


# Making the confusion Matrix
# true_posetive true_vegtive false_posetive false_negtive
from sklearn.metrics import confusion_matrix, accuracy_score
cm = confusion_matrix(y_test, y_pred)
print(cm)
accuracy_score(y_test, y_pred)


