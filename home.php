<?php
session_start(); // بدء الجلسة

if (isset($_SESSION['user_id'])){
  $user_id = $_SESSION['user_id']; 

}else{
    header('Location: ./index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionnaire</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #b18f5b;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            perspective: 1500px; /* 3D effect */
            overflow: hidden; /* Hide overflow during animation */
        }

        .container {
            width: 90%;
            max-width: 400px;
            background-color: #252c36;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
            text-align: center;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 1s ease-in-out; /* Smooth transition for the container */
        }

        .title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .question-section {
            margin-top: 20px;
            display: none;
            opacity: 0;
            transform: rotateY(90deg); /* Effect when question starts */
            transition: transform 1s, opacity 1s;
        }

        .question-section.active {
            display: block;
            opacity: 1;
            transform: rotateY(0deg); /* Effect when question is displayed */
        }

        .question-title {
            background-color: #333;
            padding: 10px;
            font-size: 18px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .question-title:hover {
            background-color: #b18f5b;
        }

        .answer {
            margin-top: 10px;
            padding: 10px;
            background-color: #555;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .answer:hover {
            background-color: #b18f5b;
            transform: scale(1.1);
        }

        .answer.clicked {
            background-color: #a07c4e; /* Color after the first click */
        }

        .answer.double-clicked {
            background-color: #f2a900; /* Color after double-click */
        }

        .button {
            padding: 10px 20px;
            border: none;
            background-color: #b18f5b;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .button:hover {
            background-color: #a07c4e;
            transform: translateY(-5px);
        }
        
        .button-container {
            margin-top: 20px; /* Space between questions and the button */
        }

    </style>
</head>
<body>

    <!-- Welcome Screen -->
    <div class="container" id="welcome-screen">
        <div class="title">WELCOME</div>
        <div class="content">
            <p>This self-assessment tool is based on the European Digital Competence Framework for Educators (DigCompEdu).</p>
            <p>Click continue to proceed to the next step.</p>
        </div>
        <button class="button" onclick="showQuestions()">CONTINUE</button>
    </div>

    <!-- Questions Section -->
    <div id="questions-container" class="container" style="display: none;">
        <!-- Questions will be added here dynamically -->
    </div>

    <script>
        let questions = []; // Array to hold the questions fetched from API
        let currentQuestionIndex = 0;

        // Fetch questions from the API
        function fetchQuestions() {
            fetch('https://aletharsociety.com/edu/view.php') // Replace with your API endpoint
                .then(response => response.json())
                .then(data => {
                    questions = data.data; // Assuming the API returns a list of questions in a 'data' property
                    // Show the questions section after fetching
                    showQuestions();
                })
                .catch(error => {
                    console.error('Error fetching questions:', error);
                });
        }

        // Show the questions container and the first question
        function showQuestions() {
            if (questions.length === 0) {
                alert("No questions available.");
                return;
            }

            document.getElementById('welcome-screen').style.display = 'none';
            const questionsContainer = document.getElementById('questions-container');
            questionsContainer.style.display = 'block';
            showQuestion(currentQuestionIndex);
        }

        // Show the current question
        function showQuestion(index) {
    const question = questions[index];
    const questionSection = document.createElement('div');
    questionSection.classList.add('question-section');
    questionSection.classList.add('active');

    // Create the domain name and description
    const domainTitle = document.createElement('div');
    domainTitle.classList.add('title');
    domainTitle.textContent = question.name_dome; // Domain name
    questionSection.appendChild(domainTitle);

    const domainDescription = document.createElement('div');
    domainDescription.classList.add('description');
    domainDescription.textContent = question.name; // Domain description
    questionSection.appendChild(domainDescription);

    // Create the question title
    const questionTitle = document.createElement('div');
    questionTitle.classList.add('question-title');
    questionTitle.textContent = question.question_text;
    questionSection.appendChild(questionTitle);

    // Create the answer buttons
    for (let i = 1; i <= 4; i++) {
        const answerDiv = document.createElement('div');
        answerDiv.classList.add('answer');
        answerDiv.textContent = question['answer_' + i];
        answerDiv.onclick = function() { handleAnswerClick(answerDiv, question, i) };
        questionSection.appendChild(answerDiv);
    }

    // Create the "Next Question" and "Back" buttons
    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('button-container');

    const backButton = document.createElement('button');
    backButton.classList.add('button');
    backButton.textContent = 'Back';
    backButton.onclick = previousQuestion;
    buttonContainer.appendChild(backButton);

    const nextButton = document.createElement('button');
    nextButton.classList.add('button');
    nextButton.textContent = 'Next Question';
    nextButton.onclick = nextQuestion;
    buttonContainer.appendChild(nextButton);

    // Append the button container
    questionSection.appendChild(buttonContainer);

    // Add question section to container
    const questionsContainer = document.getElementById('questions-container');
    questionsContainer.appendChild(questionSection);
}

     let domainScores = {}; // كائن لتخزين العلامات لكل دومين

function handleAnswerClick(answerDiv, question, answerIndex) {
    if (!answerDiv.classList.contains('clicked')) {
        answerDiv.classList.add('clicked'); // First click
    } else if (!answerDiv.classList.contains('double-clicked')) {
        answerDiv.classList.add('double-clicked'); // Double click
    }

    // حساب العلامة والنسبة
    const markValue = answerIndex;
    const percentageValue = calculatePercentage(answerIndex);

    // إضافة العلامة إلى الدومين الحالي
    if (!domainScores[question.dome_id]) {
        domainScores[question.dome_id] = { totalMarks: 0, questionsCount: 0 };
    }
    domainScores[question.dome_id].totalMarks += markValue;
    domainScores[question.dome_id].questionsCount++;

    console.log("Current domain scores:", domainScores);

    // إرسال البيانات إلى marks.php (اختياري في كل إجابة أو عند الانتهاء)
    sendScoresToServer(question.dome_id, markValue, percentageValue);
}

function calculatePercentage(answerIndex) {
    // يمكنك تخصيص هذه العملية حسب متطلباتك
    return answerIndex * 25; // على افتراض أن الإجابة 1 = 25%، الإجابة 2 = 50% وهكذا
}

function sendScoresToServer(domeId, markValue, percentageValue) {
    const data = {
        teachers_id: <?php echo $user_id; ?>, // معرف المعلم
        dome_id: domeId, // معرف الدومين
        mark_value: markValue, // العلامة
        percentage_value: percentageValue // النسبة المئوية
    };

    fetch('marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(data)
    })
    .then(response => response.json())
    .then(result => {
        console.log('Response from marks.php:', result);
    })
    .catch(error => {
        console.error('Error sending data to marks.php:', error);
    });
}

// إرسال العلامات النهائية عند انتهاء الأسئلة
function sendFinalScores() {
    for (const domeId in domainScores) {
        const totalMarks = domainScores[domeId].totalMarks;
        const questionsCount = domainScores[domeId].questionsCount;
        const percentageValue = (totalMarks / (questionsCount * 4)) * 100; // حساب النسبة المئوية الإجمالية

        sendScoresToServer(domeId, totalMarks, percentageValue);
    }
    alert("تم تخزين جميع العلامات!");
}


        // Show the next question with a 3D flip effect under the Next Question button
  function nextQuestion() {
    if (currentQuestionIndex < questions.length) {
        const container = document.getElementById('questions-container');
        container.style.transform = 'rotateY(180deg)'; // Rotate the container

        // Wait for the rotation effect, then update the question
        setTimeout(() => {
            const previousQuestion = document.querySelectorAll('.question-section')[currentQuestionIndex];
            previousQuestion.classList.remove('active'); // Hide current question

            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                showQuestion(currentQuestionIndex); // Show next question
            } else {
               // alert("You've completed the questions!");
                // Redirect to another page after completing the questions
                const userId = <?php echo json_encode($user_id); ?>;

        // التنقل إلى صفحة chat.php مع تمرير user_id في Query String
        window.location.replace(`chat.php?user_id=${userId}`);// Replace with your desired page URL
            }

            container.style.transform = 'rotateY(0deg)'; // Reset the container's rotation
        }, 500); // Duration of the rotation before updating the question
    }
}

        // Show the previous question with a 3D flip effect under the Back button
     function previousQuestion() {
    // Disable the Back button if on the first question or after completion
    if (currentQuestionIndex <= 0 || currentQuestionIndex === questions.length) {
        return;
    }

    const container = document.getElementById('questions-container');
    container.style.transform = 'rotateY(-180deg)'; // Rotate the container back

    // Wait for the rotation effect, then update the question
    setTimeout(() => {
        const previousQuestion = document.querySelectorAll('.question-section')[currentQuestionIndex];
        previousQuestion.classList.remove('active'); // Hide current question

        currentQuestionIndex--;
        showQuestion(currentQuestionIndex); // Show previous question

        container.style.transform = 'rotateY(0deg)'; // Reset the container's rotation
    }, 500); // Duration of the rotation before updating the question
}


        // Fetch questions when the page loads
        window.onload = function() {
            fetchQuestions();
        }
    </script>

</body>
</html>