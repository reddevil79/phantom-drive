# Text Classification: Amazon Review Sentiment Analysis

An LSTM-based sentiment classifier trained on Amazon Kindle product review text, predicting whether a review is positive or negative directly from its raw text content.

## Overview

Customer review text is noisy and unstructured — this project builds a full NLP pipeline to clean that text and train a recurrent neural network capable of picking up on sentiment patterns that simple keyword-matching would miss.

## Pipeline

1. **Text preprocessing** — stopword removal, lemmatization, and frequency-distribution analysis to understand and clean the raw review text.
2. **Tokenization & sequence preparation** — converting cleaned text into padded numerical sequences suitable for a recurrent model.
3. **Model architecture** — an LSTM network designed to capture sequential dependencies in review text (word order and context, not just word presence).
4. **Training & evaluation** — trained on a held-out split and evaluated using AUC to get a robust measure of classification quality.

## Results

Achieved a **0.89 AUC** on held-out test data.

## Tech Stack

Python, TensorFlow, Keras, NLTK, NumPy, Pandas
