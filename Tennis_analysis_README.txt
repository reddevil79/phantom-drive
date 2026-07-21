# Tennis Analysis

Computer vision system that analyzes tennis match footage to detect players and the ball, extract court geometry, and compute real-time performance statistics such as player speed and shot speed.

## Features

- **Player detection & tracking** using YOLOv8, with persistent player IDs across frames.
- **Ball detection** using a custom fine-tuned YOLO model (`yolo5_last.pt`) trained specifically on tennis ball footage, since the ball is too small and fast for a general-purpose detector.
- **Court keypoint extraction** using a CNN (`keypoints_model.pth`) trained to regress the pixel coordinates of the court's line intersections, enabling perspective mapping between video pixels and real-world court coordinates.
- **Mini-court visualization**: projects player and ball positions onto a top-down mini-court overlay for easier spatial interpretation.
- **Speed & shot statistics**: computes per-player movement speed and shot speed in km/h, plus running averages, displayed as a live stats panel on the output video.
- **Cached tracking**: stores intermediate detection/tracking results in `tracker_stubs` to avoid recomputing expensive inference on repeated runs.

## Project Structure

```
Tennis_analysis/
├── analysis/              # Speed, shot count, and statistics computation
├── constants/              # Court dimensions and fixed reference values
├── court_line_detector/    # CNN-based court keypoint detection
├── input_videos/           # Raw match footage
├── output_videos/          # Rendered videos with detection overlays
├── mini_court/              # Top-down court coordinate mapping
├── models/                  # Model loading utilities
├── trackers/                 # Player and ball tracking logic
├── tracker_stubs/            # Cached detection/tracking results (pickle)
├── training/                  # Notebooks for training the ball detector and keypoint model
├── utils/                      # Video I/O and helper functions
├── runs/                        # YOLO training/inference run artifacts
├── yolo_inference.py             # Standalone YOLO inference script
├── keypoints_model.pth            # Trained court keypoint CNN weights
├── yolo5_last.pt                   # Fine-tuned YOLO ball detection weights
└── main.py                          # Entry point: runs the full pipeline end-to-end
```

## Models Used

| Task | Model |
|---|---|
| Player detection | YOLOv8 (pretrained) |
| Ball detection | Fine-tuned YOLO (custom-trained, `yolo5_last.pt`) |
| Court keypoint extraction | CNN, trained in PyTorch (`keypoints_model.pth`) |

## Tech Stack

Python, Ultralytics YOLOv8, PyTorch, OpenCV, NumPy, Pandas

## Requirements

```
python3.8
ultralytics
pytorch
pandas
numpy
opencv-python
```

## Usage

1. Place the input match video in `input_videos/`.
2. Run the pipeline:
   ```bash
   python main.py
   ```
3. The annotated output video, with player/ball IDs and live speed statistics, is written to `output_videos/`.

## Training

- Ball detector: `training/tennis_ball_detector_training.ipynb`
- Court keypoint model: `training/tennis_court_keypoints_training.ipynb`
