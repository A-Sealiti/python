import numpy as np
import matplotlib.pyplot as plt 
from sklearn.linear_model import LinearRegression

# Data 
studie_uren = np.array([ 1 , 2 , 3 , 4 , 5 , 6]).reshape( -1 , 1 )
oefentoetsen = np.array([ 2 , 3 , 3 , 4 , 5 , 6 ]).reshape( -1 , 1 )
cijfers = np.array([ 3 , 4.5 , 5 , 6.5 , 8 , 8.5])
# Combineer de twee variabelen tot een matrix van inputvariabelen
X = np.hstack((studie_uren, oefentoetsen))

# Model aanmaken en trainen
model = LinearRegression()
model.fit(X, cijfers)

# Voorspellingen maken
predictions = model.predict(X)

# Coëfficiënten en intercept
print("Intercept:", model.intercept_)
print("Coefficients:", model.coef_)

# Voorspellingen weergeven
print("Voorspelde cijfers:", predictions)

# Plotten van voorspellingen en werkelijke gegevens
fig, ax = plt.subplots()
ax.scatter(range(len(cijfers)), cijfers, color='blue', label='Werkelijke cijfers')
ax.plot(range(len(cijfers)), predictions, color='red', label='Voorspelde cijfers')
plt.xlabel('Studenten (volgorde)')
plt.ylabel('Cijfers')
plt.legend()
plt.show()
