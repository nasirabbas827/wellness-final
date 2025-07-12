<?php
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Tracker</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }
        .jumbotron {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/wellness-bg.jpg');
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 150px 20px;
            color: whitesmoke;
        }
        .jumbotron h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            color: white;
        }
        .jumbotron p {
            font-size: 1.5em;
            margin-bottom: 30px;
        }
        .jumbotron .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            transition: background-color 0.3s ease;
        }
        .jumbotron .cta-button:hover {
            background-color: #218838;
        }
        section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: auto;
        }
        .features, .testimonials, .how-it-works, .wellness-tips {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .feature-card, .testimonial-card, .step-card, .tip-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
        }
        .feature-card h3, .testimonial-card h3, .step-card h3, .tip-card h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .feature-card p, .testimonial-card p, .step-card p, .tip-card p {
            color: #666;
        }
        .testimonial-card .user-name {
            font-style: italic;
            color: #007bff;
            margin-top: 10px;
        }
        .step-card .step-number {
            font-size: 2em;
            color: #28a745;
            margin-bottom: 10px;
        }
        .contact-form {
            display: grid;
            gap: 15px;
            max-width: 600px;
            margin: auto;
        }
        .contact-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .contact-form button {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .contact-form button:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: #28a745;
            text-align: center;
            margin-bottom: 20px;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }
        @media (min-width: 768px) {
            .features, .testimonials, .how-it-works, .wellness-tips {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <div class="jumbotron">
        <h1>Welcome to Wellness Tracker</h1>
        <p>Track your hydration, sleep, exercise, and mood to live a healthier life.</p>
        <a href="login.php" class="cta-button">Get Started</a>
    </div>

    <section>
        <h2>Why Wellness Tracker?</h2>
        <div class="features">
            <div class="feature-card">
                <h3>Log Your Activities</h3>
                <p>Easily record your daily water intake, sleep hours, exercise routines, and mood to stay on top of your wellness goals.</p>
            </div>
            <div class="feature-card">
                <h3>Visualize Progress</h3>
                <p>View insightful charts to track your wellness trends over time and make informed decisions for a healthier lifestyle.</p>
            </div>
            <div class="feature-card">
                <h3>Stay on Track</h3>
                <p>Set daily reminders to log your activities and receive notifications to keep your wellness routine consistent.</p>
            </div>
        </div>
    </section>

    <section>
        <h2>How It Works</h2>
        <div class="how-it-works">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Sign Up</h3>
                <p>Create an account to start your wellness journey with Wellness Tracker.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Log Activities</h3>
                <p>Record your daily water intake, sleep, exercise, and mood in a few clicks.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Track Progress</h3>
                <p>Monitor your trends with charts and stay motivated with reminders.</p>
            </div>
        </div>
    </section>

    <section>
        <h2>User Testimonials</h2>
        <div class="testimonials">
            <div class="testimonial-card">
                <h3>Life-Changing App!</h3>
                <p>Wellness Tracker helped me stay consistent with my hydration and sleep goals. The charts are super motivating!</p>
                <p class="user-name">- Sarah M.</p>
            </div>
            <div class="testimonial-card">
                <h3>Easy to Use</h3>
                <p>I love how simple it is to log my exercise and mood. The reminders keep me on track every day.</p>
                <p class="user-name">- Ahmed K.</p>
            </div>
            <div class="testimonial-card">
                <h3>Better Wellness</h3>
                <p>Tracking my activities has made me more aware of my habits. I feel healthier already!</p>
                <p class="user-name">- Fatima Z.</p>
            </div>
        </div>
    </section>

    <section>
        <h2>Wellness Tips</h2>
        <div class="wellness-tips">
            <div class="tip-card">
                <h3>Stay Hydrated</h3>
                <p>Drink at least 8 glasses of water daily to boost energy and improve focus.</p>
            </div>
            <div class="tip-card">
                <h3>Prioritize Sleep</h3>
                <p>Aim for 7-8 hours of quality sleep to enhance mood and productivity.</p>
            </div>
            <div class="tip-card">
                <h3>Move Daily</h3>
                <p>Even a 20-minute walk can improve your physical and mental well-being.</p>
            </div>
        </div>
    </section>

    <section>
        <h2>Contact Us</h2>
        <p>Have questions or feedback? Reach out to us!</p>
        <?php if (isset($contact_success) && $contact_success): ?>
            <div class="success-message">Thank you for your message! We'll get back to you soon.</div>
        <?php endif; ?>
        <form action="index.php" method="POST" class="contact-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </section>

    <footer>
        <p>© 2025 Wellness Tracker. All rights reserved.</p>
    </footer>
</body>
</html>