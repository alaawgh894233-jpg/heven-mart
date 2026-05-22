import pandas as pd
import matplotlib.pyplot as plt

df = pd.read_csv("benchmark.csv")

plt.plot(df["Request"], df["Sync_ms"], label="Sync")
plt.plot(df["Request"], df["Queue_ms"], label="Queue")

plt.title("Sync vs Queue Performance")
plt.xlabel("Request")
plt.ylabel("Time (ms)")
plt.legend()

plt.show()
