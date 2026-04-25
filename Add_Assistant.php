<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yumnak | Add Assistant</title>
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
    <a href="Assistant_Management.html" class="sidebar-item active">
        <i class="fa-solid fa-users"></i> Assistant Management
    </a>
    <a href="view-requests.html" class="sidebar-item">
        <i class="fa-solid fa-clock-rotate-left"></i> View Requests
    </a>
    <a href="Weekly_Performance.html" class="sidebar-item">
        <i class="fa-solid fa-file-lines"></i> Weekly Performance Report
    </a>
</aside>

        
    <main class="main-contentAdd">
        <div class="headlineAdd">
            <h1>Add New Assistant</h1>
            <p>Create a new assistant profile to handle airport assistance requests.</p>
        </div>

        <form class="form-container">
            <div class="form-section">
                <div class="section-title">
                    <i class="fa-solid fa-user"></i> Personal Information
                </div>
                
                <div class="input-group">
                    <label>Full Name <span>*</span></label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" placeholder="Enter full name" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Phone Number <span>*</span></label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" placeholder="+966 50 000 000" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Email Address <span>*</span></label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="assistant@yumnak.com" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <i class="fa-solid fa-briefcase"></i> Professional Details
                </div>
                <div class="input-group">
                    <label>Specialization <span>*</span></label>
                    <div class="input-with-icon">
                        <i class="fa-solid fa-star"></i>
                        <select required>
                             <option value="Wheelchair Assistance">Wheelchair Assistance</option>
                             <option value="Mobility Assistance">Mobility Assistance</option>
                             <option value="Visual Impairment Assistance">Visual Impairment Assistance</option>
                             <option value="Hearing Impairment Assistance">Hearing Impairment Assistance</option>
                             <option value="Cognitive Assistance">Cognitive Assistance</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
        <i class="fa-solid fa-camera"></i> Profile Photo
    </div>
    <div class="upload-area">
        <div class="photo-preview" id="photoPreview">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="upload-controls">
            <input type="file" id="imageInput" accept="image/*">
            <button type="button" class="btn-upload" onclick="document.getElementById('imageInput').click()">
                <i class="fa-solid fa-upload"></i> Upload Photo
            </button>
            <p>Recommended: Square image, at least 400x400px</p>
        </div>
    </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="window.location.href='Assistant_Management.html'"><i class="fa-solid fa-xmark"></i> Cancel</button>
                <button type="submit" class="btn-submit"><i class="fa-solid fa-plus"></i> Add Assistant</button>
            </div>
        </form>
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
    const imageInput = document.getElementById('imageInput');
    const photoPreview = document.getElementById('photoPreview');
    const form = document.querySelector('.form-container');

    if (imageInput) {
        imageInput.onchange = function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">`;
                    photoPreview.style.border = "2px solid #8B6B4A";
                };
                reader.readAsDataURL(file);
            }
        };
    }

    if (form) {
        form.onsubmit = function(e) {
            e.preventDefault(); 
            
            alert("Success! The new assistant has been added to Yumnak system.");
            window.location.href = 'Assistant_Management.html';
        };
    }
</script>
</body>
</html>