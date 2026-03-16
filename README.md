# career guidance system
Overview

The Career Guidance System is a web-based application designed to help secondary school students identify suitable career paths based on their personality traits.

The system applies the RIASEC Model, which classifies personality into six categories: Realistic, Investigative, Artistic, Social, Enterprising, and Conventional.

Students complete a questionnaire-based assessment, and the system analyzes their responses to generate personality results and recommend suitable career paths.

The platform also includes a teacher dashboard that allows educators or counselors to manage assessments and monitor student results.


Key Features:


Student Module


-Register and log in to the system

-Take career personality assessments

-View personality type results

-Receive career recommendations based on responses


Teacher / Admin Module


-Manage career assessment questions

-Monitor student responses

-Analyze results through visual charts

-View aggregated career trends among students



System Architecture



The system follows a basic client–server architecture:

-Frontend: User interface for students and teachers

-Backend: Handles logic, data processing, and result calculation

-Database: Stores student responses, test questions, and results


Technologies Used


Component	: Technology

Frontend	: HTML, CSS, JavaScript

Backend	: PHP

Database	: MySQL

Charts	: JavaScript Chart Libraries


Installation Guide

1. Clone the Repository

git clone https://github.com/ZersssR/career-guidance-system.git

2. Move Project to Local Server

Place the project folder in your local server directory.

Example:

XAMPP:

htdocs/careerguidancesystem

Laragon:

www/careerguidancesystem

3. Import Database

Open phpMyAdmin

Create a new database (example: career_guidance_system)

Import the provided SQL file

4. Run the System

Start your local server and open:

http://localhost/careerguidancesystem

Screenshots


Future Improvements

Add more career datasets and recommendation rules

Improve UI/UX design

Implement responsive design for mobile devices

Integrate machine learning for smarter career prediction

Add analytics dashboard for counselors

Author

Syu
Bachelor of Computer Science (Software Development)

License

This project is developed for educational purposes.

