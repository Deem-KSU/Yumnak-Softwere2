<!DOCTYPE html>
<html lang="ar" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yumnak | Assistant Management</title>
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

        <main class="main-content">

            <div class="headline">
                <h1>Assistant Management</h1>
                <p>Manage assistants, Monitor their availability.</p>
            </div>

            <div class="action-bar">
                <div class="search-container">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search assistant by name or specialization">
                </div>
                <button class="btn-add" id="addAssistantBtn"><i class="fa-solid fa-plus"></i> Add Assistant</button>
            </div>

            <div class="table-container">
                <div class="table-header-title">All Assistants</div>
                <table>
                    <thead>
                        <tr>
                            <th>ASSISTANT</th>
                            <th>CONTACT</th>
                            <th>SPECIALIZATION</th>
                            <th>DELETE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Mary+Usama&background=random"
                                        alt="Avatar">
                                    <div>
                                        <div class="name">Maryam Usama</div>
                                        <div class="id">ID: AS001</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <span>+966 50 022 651</span>
                                    <span class="email">Maryu1123@yumnak.com</span>
                                </div>
                            </td>
                            <td>Wheelchair Assistance</td>
                            <td><button class="btn-delete"><i class="fa-solid fa-trash-can"></i></button></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Lat+Jas&background=random" alt="Avatar">
                                    <div>
                                        <div class="name">Latifah jassir</div>
                                        <div class="id">ID: AS002</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <span>+966 55 867 221</span>
                                    <span class="email">Tetobe1@yumnak.com</span>
                                </div>
                            </td>
                            <td>Language Support</td>
                            <td><button class="btn-delete"><i class="fa-solid fa-trash-can"></i></button></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Khalid+Altmimi&background=random"
                                        alt="Avatar">
                                    <div>
                                        <div class="name">Khalid Altmimi</div>
                                        <div class="id">ID: AS003</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <span>+966 53 222 145</span>
                                    <span class="email"> Khalid@yumnak.com</span>
                                </div>
                            </td>
                            <td>Navigation Guide</td>
                            <td><button class="btn-delete"><i class="fa-solid fa-trash-can"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
                <p>Your companion for a world without barriers, making every journey at the airport easier and more
                    inclusive.</p>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; 2026 Yumnak Platform. All rights reserved.
        </div>
    </footer>
    <script>
        document.getElementById('addAssistantBtn').onclick = function () {
            window.location.href = 'Add_Assistant.html';
        };
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.onclick = function () {
                const confirmDelete = confirm("Are You Sure You Want To Delete This Assistant?");

                if (confirmDelete) {
                    const row = this.closest('tr');
                    row.remove();
                    alert("The Assistant Deleted");
                }
            };
        });
    </script>
</body>

</html>