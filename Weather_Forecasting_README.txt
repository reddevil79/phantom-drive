# Weather Condition Forecasting with PySpark

A distributed machine learning pipeline built on Apache Spark to classify weather conditions from historical hourly meteorological data across multiple cities.

## Overview

Working with the [historical hourly weather dataset](https://github.com/Lucifer7779/Weather_forcasting), this project joins several raw measurement sources into a single dataset and trains classifiers to predict weather condition categories (e.g. sunny, cloudy, rainy, snowy, foggy, thunderstorm) from numerical weather readings.

## Pipeline

1. **Data ingestion** — loads separate CSVs for humidity, pressure, temperature, wind direction/speed, weather description, and city metadata.
2. **Data unification** — joins all measurement types per city into a single Spark DataFrame keyed on datetime and city.
3. **Label aggregation** — collapses 54 raw, overlapping weather-condition labels down to 6 clean classes (thunderstorm, rainy, snowy, cloudy, foggy, sunny) for a cleaner classification target.
4. **Class balancing** — applies undersampling to correct for class imbalance before training.
5. **Feature encoding** — builds a Spark ML `Pipeline` combining a `StringIndexer` and `StandardScaler` to prepare numerical and categorical features.
6. **Model training** — trains and k-fold cross-validates two classifiers:
   - Random Forest
   - Logistic Regression
7. **Evaluation** — assesses both models using normalized confusion matrices to see class-by-class performance, not just overall accuracy.

## Tech Stack

Python, Apache Spark (PySpark), Spark MLlib, Hadoop, Matplotlib

## Notes

Built and run on a distributed Spark environment (Databricks-compatible), demonstrating handling of a dataset too large or awkward to process comfortably with single-machine pandas workflows.
