<?php
// Start session to store the user's answers for further processing
session_start();

// Function to check if all form questions are answered
function allQuestionsAnswered($answers) {
    for ($i = 1; $i <= 15; $i++) {
        if (!isset($answers[$i])) {
            return false;
        }
    }
    return true;
}

// Check if form data has been posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store the answers from the form in an array
    $answers = array(
        1 => $_POST['q1'] ?? null,
        2 => $_POST['q2'] ?? null,
        3 => $_POST['q3'] ?? null,
        4 => $_POST['q4'] ?? null,
        5 => $_POST['q5'] ?? null,
        6 => $_POST['q6'] ?? null,
        7 => $_POST['q7'] ?? null,
        8 => $_POST['q8'] ?? null,
        9 => $_POST['q9'] ?? null,
        10 => $_POST['q10'] ?? null,
        11 => $_POST['q11'] ?? null,
        12 => $_POST['q12'] ?? null,
        13 => $_POST['q13'] ?? null,
        14 => $_POST['q14'] ?? null,
        15 => $_POST['q15'] ?? null
    );

    // Check if all questions were answered
    if (allQuestionsAnswered($answers)) {
        // Function to calculate the learning style based on answers
        function calculateLearningStyle($answers) {
            $visualScore = 0;
            $auditoryScore = 0;
            $kinestheticScore = 0;

            $visualQuestions = [1, 2, 3, 4, 5];
            $auditoryQuestions = [6, 7, 8, 9, 10];
            $kinestheticQuestions = [11, 12, 13, 14, 15];

            // Calculate scores for each learning style
            foreach ($visualQuestions as $q) {
                if ($answers[$q] == 1) {
                    $visualScore++;
                }
            }

            foreach ($auditoryQuestions as $q) {
                if ($answers[$q] == 1) {
                    $auditoryScore++;
                }
            }

            foreach ($kinestheticQuestions as $q) {
                if ($answers[$q] == 1) {
                    $kinestheticScore++;
                }
            }

            // Determine the highest learning style(s)
            $learningStyles = [];
            $maxScore = max($visualScore, $auditoryScore, $kinestheticScore);

            if ($visualScore == $maxScore) {
                $learningStyles[] = 'Visual';
            }
            if ($auditoryScore == $maxScore) {
                $learningStyles[] = 'Auditory';
            }
            if ($kinestheticScore == $maxScore) {
                $learningStyles[] = 'Kinesthetic';
            }

            return $learningStyles;
        }

        // Store the calculated learning style in session
        $_SESSION['learningStyle'] = calculateLearningStyle($answers);
    } else {
        $_SESSION['learningStyle'] = null;
        $errorMessage = "Please answer all questions before submitting the quiz.";
    }
}

// Check if the learning style is set in the session
$learningStyle = $_SESSION['learningStyle'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Content</title>
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-edu-meeting.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">

    <style>
        /* General Styles */
        .resources {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .resource-box {
    width: 48%; /* This will make two items per row */
    background-color: #f7f7f7;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
    cursor: pointer;
    margin-bottom: 20px; /* Add some space between boxes */
}

/* Hover effect: Increase size and change shadow */
.resource-box:hover {
    transform: translateY(-5px); /* Slight upward movement */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Deeper shadow */
    background-color: #e0e0e0; /* Change background color slightly */
}

/* Make the layout responsive on smaller screens */
@media (max-width: 768px) {
    .resource-box {
        width: 100%; /* Stack items vertically on smaller screens */
    }
}

/* Additional custom styling for images inside the resource box */
.resource-box img {
    max-width: 100%; /* Ensure images are responsive */
    height: auto;
    border-radius: 8px; /* Rounded corners for images */
}

/* Additional custom styling for text inside resource box */
.resource-box p {
    font-size: 16px;
    margin-top: 10px;
}

.resource-box a {
    color: #3498db;
    text-decoration: none;
    font-weight: bold;
}

.resource-box a:hover {
    text-decoration: underline;
}


        /* Container for learning style message */
        .learning-style-container {
            background-color: #3498db;
            color: white;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        /* Large Box for Contents */
        .content-box {
            border: 2px solid #3498db;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <section class="learning-content">
        <div class="container">
            <?php
            if ($learningStyle !== null) {
                // Display "You are" message
                echo "<div class='learning-style-container'>";
                echo "<h2>You are " . implode(' and ', $learningStyle) . " learner(s).</h2>";
                echo "</div>";

                // Display message for suitable content
                echo "<div class='content-box'>";
                echo "<h3>Here are learning contents suitable for your learning style(s):</h3>";

                // Display resources based on the learning style
                echo "<div class='resources'>";
                // Show resources for Visual Learners
                if (in_array('Visual', $learningStyle)) {
                    echo "<div class='resource-box'>
                            <img src='assets/images/visualcontent1.png' alt='Complete Coding for Beginners Course'>
                            <p><a href='https://www.udemy.com/course/the-complete-coding-for-beginners-course/?couponCode=NEWYEARCAREER'>The Complete Coding for Beginners Course</a></p>
                        </div>
                        <div class='resource-box'>
                            <img src='assets/images/visualcontent2.png' alt='Data Visualization Slides'>
                            <p><a href='https://www.slideshare.net/slideshow/data-visualizationpptx/251731745'>Data Visualization Slides</a></p>
                        </div>";
                }
                // Show resources for Auditory Learners
                if (in_array('Auditory', $learningStyle)) {
                    echo "<div class='resource-box'>
                            <img src='assets/images/auditorycontent1.png' alt='Podcast on Auditory Learning Techniques'>
                            <p><a href='https://www.audible.com/pd/The-AI-in-Business-Advantage-Audiobook/B0DQ65S2Y5?ref_pageloadid=not_applicable&pf_rd_p=e63e091a-e929-418b-b37b-5d25c3d42d1c&pf_rd_r=W62P3VFVYG9TPNF5SMAT&plink=TZIzeVJT7xBPlocG&pageLoadId=yeFnJaF2dKq1TZmW&creativeId=d33290c7-f2c9-40a2-9923-710849ce8f7e&ref=a_cat_Compu_c11_adblp13nnrpa_1_4'>The AI in Business Advantage Audiobook</a></p>
                        </div>
                        <div class='resource-box'>
                            <img src='assets/images/auditorycontent2.png' alt='Data Analytics Audio Book'>
                            <p><a href='https://www.audible.com/pd/Data-Analytics-Data-Visualization-Communicating-Data-3-books-in-1-Audiobook/B0BPD38KDP'>Data Analytics Audiobook</a></p>
                        </div>";
                }
                // Show resources for Kinesthetic Learners
                if (in_array('Kinesthetic', $learningStyle)) {
                    echo "<div class='resource-box'>
                            <img src='assets/images/kinestheticcontent1.png' alt='Practice Coding'>
                            <p><a href='https://codefinity.com/start/cpp?utm_source=google&utm_medium=cpc&utm_campaign=21743132563&utm_content=168389653872&utm_term=practice%20coding&dki=Practice%20Coding&gad_source=1&gclid=Cj0KCQiA7NO7BhDsARIsADg_hIZJsRb2RiD_zd5h2bR62MuBgIZvWnOHhbUMGFdU6pl-PFEVpZA78W4aAs5REALw_wcB'>Practice Coding on Codefinity</a></p>
                        </div>
                        <div class='resource-box'>
                            <img src='assets/images/kinestheticcontent2.png' alt='Learn Python Programming'>
                            <p><a href='https://www.codechef.com/learn/course/python'>Learn Python Programming on CodeChef</a></p>
                        </div>";
                }
                echo "</div>"; // Close resources div
                echo "</div>"; // Close content-box
            }
            ?>
        </div>
    </section>
</body>
</html>





