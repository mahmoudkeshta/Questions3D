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
  <title>DigCompEdu Framework</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #b18f5b;
      color: #fff;
      text-align: center;
      margin: 0;
      padding: 20px;
      overflow: hidden;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      opacity: 0;
      animation: fadeIn 2s forwards;
    }

    .bars {
      width: 35%;
      padding: 10px;
      margin: 0;
      text-align: left;
    }

    .bar {
      width: 100%;
      margin-bottom: 15px;
      animation: slideIn 10s ease-out forwards;
    }

    .bar span {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
      color: #fff;
    }

    .progress {
      background-color: #ffffff;
      height: 20px;
      width: 0%; /* Initially 0% width */
      border-radius: 5px;
      position: relative;
      overflow: hidden;
      transition: width 2s ease-in-out; /* Smooth transition for width */
    }

    .progress::after {
      content: "";
      display: block;
      height: 100%;
      width: 100%;
      position: absolute;
      top: 0;
      left: 0;
      background: linear-gradient(90deg, #ff9800, #4caf50, #2196f3);
    }

    .progress-value {
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      font-size: 12px;
      font-weight: bold;
      color: #fff;
      line-height: 20px;
      z-index: 1;
    }

    @keyframes fadeIn {
      0% { opacity: 0; }
      100% { opacity: 1; }
    }

    @keyframes slideIn {
      0% { transform: translateX(-50%); opacity: 0; }
      100% { transform: translateX(0); opacity: 1; }
    }

    #radarChart {
      width: 60%;
      height: auto;
      
      max-width: 500px;
      opacity: 0;
      animation: radarChartAnimation 2s ease-in-out forwards;
    }

    @keyframes radarChartAnimation {
      0% { opacity: 0; transform: scale(0); }
      100% { opacity: 1; transform: scale(1); }
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        align-items: center;
      }

      .bars {
        width: 90%;
        
        margin-bottom: 20px;
      }

      #radarChart {
        width: 100%;
        max-width: 400px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="bars">
      <div class="bar">
        <span>Professional Engagement</span>
        <div class="progress" id="bar1" data-progress="79">
          <div class="progress-value">79%</div>
        </div>
      </div>
      <div class="bar">
        <span>Digital Resources</span>
        <div class="progress" id="bar2" data-progress="88">
          <div class="progress-value">88%</div>
        </div>
      </div>
      <div class="bar">
        <span>Teaching and Learning</span>
        <div class="progress" id="bar3" data-progress="31">
          <div class="progress-value">31%</div>
        </div>
      </div>
      <div class="bar">
        <span>Assessment</span>
        <div class="progress" id="bar4" data-progress="3">
          <div class="progress-value">3%</div>
        </div>
      </div>
      <div class="bar">
        <span>Empowering Learners</span>
        <div class="progress" id="bar5" data-progress="76">
          <div class="progress-value">76%</div>
        </div>
      </div>
    </div>

    <canvas id="radarChart" width="300" height="300"></canvas>
  </div>

  <script>
    // Function to animate the progress bars
    function animateProgressBars() {
      const bars = document.querySelectorAll('.progress');
      bars.forEach(bar => {
        const progress = bar.getAttribute('data-progress');
        bar.style.width = `${progress}%`; // Set the width based on data-progress
      });
    }

    // Trigger the animation after 10 seconds
    setTimeout(animateProgressBars, 6000); // 10 seconds delay

    // Radar chart code
    const ctx = document.getElementById("radarChart").getContext("2d");

    const data = {
      labels: [
        "Digital Communication",
        "Digital Content Creation",
        "Responsible Use",
        "Digital Problem Solving",
        "Organisational Communication",
        "Professional Collaboration",
        "Reflective Practice",
        "Continuous Professional Development",
        "Selecting Digital Resources",
        "Creating and Modifying Digital Resources",
      ],
      datasets: [
        {
          label: "Competence Levels",
          data: [93, 81, 67, 74, 35, 17, 32, 40, 100, 93],
          backgroundColor: "rgba(255, 99, 132, 0.2)",
          borderColor: "rgba(255, 99, 132, 1)",
          borderWidth: 1,
          
          
        },
      ],
    };

    const options = {
      scales: {
        r: {
          angleLines: { display: false },
          suggestedMin: 0,
          suggestedMax: 100,
          
          
        },
      },
    };

    const chart = new Chart(ctx, {
      type: "radar",
      data: data,
      options: options,
    });

    function changeRadarColor() {
      chart.data.datasets[0].borderColor = getRandomColor();
      chart.update();
    }

    function getRandomColor() {
      return `hsl(${Math.floor(Math.random() * 360)}, 100%, 50%)`;
    }

    setInterval(changeRadarColor, 100);
  </script>
</body>
</html>
