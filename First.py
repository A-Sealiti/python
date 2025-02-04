import numpy as np
import matplotlib.pyplot as plt
from sklearn.linear_model import LinearRegression

# Data
studie_uren = np.array([1, 2, 3, 4, 5]).reshape(-1, 1)
oefentoetsen = np.array([2, 3, 3, 4, 5]).reshape(-1, 1)
cijfers = np.array([3, 4.5, 5, 6.5, 8])

x = np.hstack((studie_uren, oefentoetsen))
model = LinearRegression()
model.fit(x, cijfers)

predictions = model.predict(x)

print("Intercept:", model.intercept_)
print("Coefficients:", model.coef_) # b1 en b2
print("Voorspelde cijfers:", predictions)

fig, ax = plt.subplots()
ax.scatter(range(len(cijfers)), cijfers, color='blue', label='Werkelijke cijfers')
ax.plot(range(len(cijfers)), predictions, color='red', label='Voorspelde cijfers')
plt.xlabel('Studenten (volgorde)')
plt.ylabel('Cijfers')
plt.legend()
plt.show()