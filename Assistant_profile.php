<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Maryam Usama</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="StyleM.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="Image/Yumnak-Logo.png" alt="Yumnak Logo">
    </div>
    <div class="logout">
        <button onclick="window.location.href='LogIn.html'">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </div>
</header>
<div class="container">
            <aside class="sidebar">
    <a href="Admin-Dashboard.html" class="sidebar-item">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>
    <a href="request-management.html" class="sidebar-item">
        <i class="fa-solid fa-clipboard-list"></i> Request Management
    </a>
    <a href="Assistant_Management.html" class="sidebar-item">
        <i class="fa-solid fa-users"></i> Assistant Management
    </a>
    <a href="view-requests.html" class="sidebar-item">
        <i class="fa-solid fa-clock-rotate-left"></i> View Requests
    </a>
    <a href="Weekly_Performance.html" class="sidebar-item active">
        <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
    </a>
</aside>
<main class="main-content">
    <div class="headline">
        <h1>Assistant Details</h1>
        <p>View assistant information, performance, and user feedback.</p>
    </div>

    <div class="profile-header-card">
        <img src="https://ui-avatars.com/api/?name=M+U&background=8B6B4A&color=fff" alt="Profile" class="main-avatar">
        <div class="profile-info-grid">
            <div class="name-section">
                <h2>Maryam Usama</h2>
                <span class="id-tag">Assistant ID: AS001</span>
            </div>
            <div class="contact-details-row">
                <div class="detail-item">
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        <label>Phone Number</label>
                        <p>+966 50 022 651</p>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <label>Email Address</label>
                        <p>Maryu1123@yumnak.com</p>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-briefcase"></i>
                    <div>
                        <label>Specialization</label>
                        <p>Wheelchair Assistance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-icon rating-bg"><i class="fa-solid fa-star"></i></div>
            <h3>4.9</h3>
            <p>Average Rating</p>
        </div>
        <div class="stat-box">
            <div class="stat-icon completed-bg"><i class="fa-solid fa-check-double"></i></div>
            <h3>127</h3>
            <p>Total Completed Requests</p>
        </div>
        <div class="stat-box">
            <div class="stat-icon assigned-bg"><i class="fa-solid fa-clock"></i></div>
            <h3>14</h3>
            <p>This Week Assigned Requests</p>
        </div>
    </div>
   <div class="reviews-section">
    <h3>Ratings & Reviews</h3>
    <div class="slider-wrapper">
        <button class="nav-btn prev" onclick="moveSlider(-1)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="reviews-slider-container">
            <div class="reviews-track" id="reviewsTrack">
                <div class="review-card">
                    <img src="https://ui-avatars.com/api/?name=Ahmed+Al-Sayed" class="reviewer-img">
                    <div class="review-content">
                        <div class="review-header">
                            <h4>Ahmed Alsayed <span>(REQ-1832)</span></h4>
                            <span class="time-ago">2 days ago</span>
                        </div>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> 5.0</div>
                        <p>"Very helpful and professional. Made my airport experience smooth."</p>
                    </div>
                </div>
                <div class="review-card">
                    <img src="https://ui-avatars.com/api/?name=Sara+Al-suad" class="reviewer-img">
                    <div class="review-content">
                        <div class="review-header">
                            <h4>Sara Almtiri <span>(REQ-1955)</span></h4>
                            <span class="time-ago">5 days ago</span>
                        </div>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> 5.0</div>
                        <p>"Outstanding service! Maryam was patient and extremely helpful."</p>
                    </div>
                </div>
                <div class="review-card">
                    <img src="https://ui-avatars.com/api/?name=Khalid+M" class="reviewer-img">
                    <div class="review-content">
                        <div class="review-header">
                            <h4>Mohammed Alotibi<span> (REQ-1765)</span></h4>
                            <span class="time-ago">1 day ago</span>
                        </div>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i> 5.0</div>
                        <p>"Professional and punctual. Highly recommended!"</p>
                    </div>
                </div>
            </div>
        </div>

        <button class="nav-btn next" onclick="moveSlider(1)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
</div>
    <div class="table-container-details">
        <h3>Assigned Requests</h3>
        <table class="req-tab">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Airport</th>
                    <th>Assistance Type</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>REQ-1945</td>
                    <td>King Khalid Int'l</td>
                    <td>Wheelchair Assistance</td>
                    <td>May 28, 2026</td>
                    <td><span class="status-badge accepted">Accepted</span></td>
                </tr>
                <tr>
                    <td>REQ-1932</td>
                    <td>King Abdulaziz Int'l</td>
                    <td>Navigation Guide</td>
                    <td>May 27, 2026</td>
                    <td><span class="status-badge completed">Completed</span></td>
                </tr>
                 <tr>
                    <td>REQ-1955</td>
                    <td>King Khalid Int'l</td>
                    <td>Wheelchair Assistance</td>
                    <td>Apr 1, 2026</td>
                    <td><span class="status-badge accepted">Accepted</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <a href="Weekly_Performance.html" class="btn-back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>
</main>
</div>
<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4>Contact Us</h4>
            <p><i class="fas fa-envelope"></i> support@yumnak.com</p>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li><a href="#">Sitemap</a></li>
                <li><a href="Admin-Dashboard.html">Dashboard</a></li>
            </ul>
        </div>

        <div class="footer-section about-yumnak">
            <h4>About Yumnak</h4>
            <p>Your companion for a world without barriers, making every journey at the airport easier and more inclusive.</p>
        </div>
    </div>
    
    <div class="footer-bottom">
        &copy; 2026 Yumnak Platform. All rights reserved.
    </div>
</footer>
<script>
    let currentIndex = 0;
    const track = document.getElementById('reviewsTrack');
    const cards = document.querySelectorAll('.review-card');

    function moveSlider(direction) {
        currentIndex += direction;

        
        if (currentIndex < 0) {
            currentIndex = cards.length - 1;
        } else if (currentIndex >= cards.length) {
            currentIndex = 0;
        }

        const offset = currentIndex * -100;
        track.style.transform = `translateX(${offset}%)`;
    }
</script>
</body>
</html>