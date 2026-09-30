#!/usr/bin/env python3
import sys
import json
from sklearn.tree import DecisionTreeClassifier

def main():
    # Expect score and study hours from PHP command line arguments
    if len(sys.argv) < 3:
        print(json.dumps({"error": "Missing arguments"}))
        sys.exit(1)

    try:
        input_score = float(sys.argv[1])
        input_hours = float(sys.argv[2])
    except ValueError:
        print(json.dumps({"error": "Invalid numeric input"}))
        sys.exit(1)

    # Expanded training dataset: [Score, Study Hours]
    X_train = [
        [30, 2], [45, 3], [50, 4],    # Beginner profiles
        [65, 5], [70, 6], [75, 5],    # Intermediate profiles
        [85, 8], [90, 9], [95, 10]    # Advanced profiles
    ]
    
    # Corresponding target performance tiers
    y_train = [
        "Beginner", "Beginner", "Beginner",
        "Intermediate", "Intermediate", "Intermediate",
        "Advanced", "Advanced", "Advanced"
    ]

    # Initialize and train the Scikit-Learn Decision Tree Classifier
    clf = DecisionTreeClassifier(random_state=42)
    clf.fit(X_train, y_train)

    # Predict the tier for the incoming student data
    prediction = clf.predict([[input_score, input_hours]])

    # Structure the response payload for PHP
    response = {
        "status": "success",
        "engine": "scikit-learn DecisionTreeClassifier",
        "received_score": input_score,
        "received_study_hours": input_hours,
        "predicted_tier": prediction[0]
    }

    print(json.dumps(response))

if __name__ == "__main__":
    main()

