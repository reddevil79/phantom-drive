# Image Classification: Dogs vs. Cats

A comparative study of three deep learning architectures for binary image classification, evaluating how model complexity and transfer learning affect performance on the same dataset.

## Overview

The project builds and benchmarks three separate models on a 25,000-image dogs-vs-cats dataset to answer a practical question: how much does architecture choice actually matter, and what does transfer learning buy you over training from scratch?

## Models Compared

| Model | Approach | Validation AUC |
|---|---|---|
| FCNN | Fully-connected network on flattened pixel input | 0.68 |
| CNN | Custom convolutional architecture | 0.93 |
| VGG16 (Transfer Learning) | Pretrained ImageNet weights, fine-tuned | ~1.0 |

## Pipeline

1. **Data loading & exploration** — inspecting class balance and sample images.
2. **Preprocessing & augmentation** — resizing, normalization, and augmentation to improve generalization.
3. **Model training** — training all three architectures under comparable conditions (same train/validation split, same evaluation metric).
4. **Evaluation** — comparing models using ROC curves and AUC rather than raw accuracy, to get a threshold-independent view of performance.

## Tech Stack

Python, TensorFlow, Keras, NumPy, Matplotlib, Google Colab (GPU training)

## Key Takeaway

Fine-tuning a pretrained VGG16 backbone dramatically outperformed both a plain FCNN and a custom CNN trained from scratch, illustrating the practical value of transfer learning when working with limited training data and compute.
