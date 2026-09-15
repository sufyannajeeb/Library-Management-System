<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="icon" sizes="16x16" href="images/l.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Raleway:wght@400;700&display=swap" rel="stylesheet">
     <style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }
    .navbar .navbar-brand {
        color: white;
    }
    .navbar .nav-link {
        color: white;
    }
    .navbar-toggler {
        border: none;
    }
    .navbar-toggler-icon {
        color: white;
    }
    .header-content {
        text-align: center;
        padding: 100px 0;
        background-size: cover;
        color: white;
        animation: fadeIn 2s ease-in-out;
    }
    .header-content h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        animation: slideInDown 1.5s;
    }
    .header-content p {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.8);
        animation: slideInUp 1.5s;
    }


.featured-content {
    position: relative; /* Position relative for absolute positioning of video */
    padding: 50px 0;
    overflow: hidden; /* Hide any overflow from video */
}

.featured-content .background-video {
    position: absolute; /* Position video absolutely */
    top: 0; /* Align to top */
    left: 0; /* Align to left */
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    object-fit: cover; /* Cover the entire area */
    z-index: -1; /* Send video behind content */
}

.featured-content .container {
    text-align: center;
    position: relative; /* Keep the container in the stacking context */
    z-index: 1; /* Bring container above the video */
}

.feature-card {
    margin: 20px;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    background-color: rgba(255, 255, 255, 0.8); /* Optional: semi-transparent background */
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.feature-card h3 {
    color: black;
    font-size: 2rem;
    margin-bottom: 10px;
}

.feature-card p {
    font-size: 1.2rem;
    color: #6c757d;
}

/* Optional: Media Queries for responsiveness */
@media (max-width: 768px) {
    .feature-card {
        margin: 10px;
        padding: 15px;
    }
    
    .feature-card h3 {
        font-size: 1.5rem; /* Smaller heading on mobile */
    }

    .feature-card p {
        font-size: 1rem; /* Smaller paragraph on mobile */
    }
}



    .gallery {
    padding: 50px 0;
    background: #f8f9fa;
    text-align: center;
}

.gallery-item {
    opacity: 0; /* Initially hidden */
    transform: translateY(20px); /* Slide in effect */
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.gallery-item.visible {
    opacity: 1; /* Fade in */
    transform: translateY(0); /* Slide to original position */
}

.gallery img {
    max-width: 100%;
    max-height: 300px;
    margin: 10px;
    border-radius: 10px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.gallery img:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .gallery-item {
        flex-basis: 100%; /* Full width on small screens */
    }
}

    .about-section {
        background-image: url(images/stars.png);
        padding: 50px 0;
        text-align: center;
    }
    .about-section h2 {
        color: black;
        font-size: 2.5rem;
        margin-bottom: 20px;
        animation: fadeInLeft 1.5s;
    }
    .about-section p {
        font-size: 1.2rem;
        color: white;
        animation: fadeInRight 1.5s;
    }
    .cta-section {
        padding: 50px 0;
        background: #f8f9fa;
        text-align: center;
    }
    .cta-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        animation: bounceIn 1.5s;
    }
    .cta-section p {
        font-size: 1.2rem;
        color: #6c757d;
        animation: bounceIn 1.5s;
    }
    .main-content {
        flex: 1;
    }
    /* Footer Styling */
.footer {
    background-color: #f8f9fa;
    padding: 40px 0;
    font-family: Arial, sans-serif;
    color: #333;
    border-top: 1px solid #ddd;
}

.footer .container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.footer-section {
    flex: 1;
    margin: 15px;
    min-width: 200px; /* Ensures sections have a minimum width */
}

.footer-section h4, .footer-section h5 {
    font-size: 1.2rem;
    margin-bottom: 15px;
    font-weight: bold;
    color: #2a2a2a;
}

.footer-section p, .footer-section li {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 10px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section ul li a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: #0056b3;
}

.footer-copyright {
    text-align: center;
    font-size: 0.9rem;
    color: #666;
    padding: 15px 0;
    border-top: 1px solid #ddd;
}

/* Mobile Responsiveness */
@media screen and (max-width: 768px) {
    .footer .container {
        flex-direction: column;
        align-items: center; /* Centers the sections on small screens */
    }

    .footer-section {
        margin-bottom: 30px;
        text-align: center; /* Center aligns text for better readability */
    }
}


    @keyframes scrollGallery {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }
    .bg {
    position: relative;
    width: 100%;
    height: 50vh;
    overflow: hidden;
    
}

.bg video {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: translate(-50%, -50%);
    z-index: -1;
}

.video-hidden {
    display: none;
}

/* Arrow Bounce Animation */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.arrow-bounce {
    text-align: center;
    margin-top: 20px;
    cursor: pointer;
    animation: bounce 2s infinite;
}

.arrow-bounce i {
    font-size: 30px;
    color: #000;
}

/* Hidden Content Styling */
.hidden-content {
    display: none;
    margin-top: 20px;
    animation: slideDown 0.5s forwards;
}

.hidden-content p {
    color: black;
}

@keyframes slideDown {
    from {
        max-height: 0;
        opacity: 0;
    }
    to {
        max-height: 500px;
        opacity: 1;
    }
}

 /* Chatbot Icon styles */
#chat-icon {
    width: 60px;
    height: 60px;
    background-color: #007bff; /* Background color for the icon */
    border-radius: 50%; /* Circular icon */
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 24px; /* Adjust size as needed */
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Shadow effect */
    z-index: 1000; /* Ensure it appears on top */
}

/* Notification badge styles */
#notification-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 15px;
    height: 15px;
    background: red;
    border-radius: 50%;
    display: block;
}

/* Chat window styles */
#chat-window {
    display: none; /* Initially hidden */
    position: fixed;
    bottom: 80px; /* Adjusted to match JavaScript code */
    right: 20px;
    width: 300px;
    height: 400px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000; /* Ensure it is on top of other elements */
}

/* Close button styles */
#close-chat {
    background: none; /* No background */
    border: none; /* No border */
    font-size: 18px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    color: black; /* Change text color to black */
}


/* Output area styles */
#output {
    height: 350px;
    overflow-y: scroll;
    padding: 10px;
}

@keyframes rgb-glow {
   
    
    75% {
        box-shadow: 0 0 5px green, 0 0 10px green, 0 0 15px #ffeb3b, 0 0 20px #ffeb3b; /* Yellow */
    }
    100% {
        box-shadow: 0 0 5px #000, 0 0 10px #000, 0 0 15px #000, 0 0 20px #000; /* Orange Red */
    }
}

#input-area {
    display: flex;
    border-top: 1px solid transparent; /* Remove the solid border */
    animation: rgb-glow 5s infinite alternate; /* Apply the animation with slower duration */
}




#user-input {
    width: 80%;
    padding: 10px;
    border: none;
    outline: none;
}

button {
    width: 20%;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}
/* Styling for the Library Management System section */
.my-5 h2 {
    font-size: 2.5rem;
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 30px;
}

.my-5 p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #34495e;
    margin-bottom: 20px;
}

.my-5 {
    padding: 50px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.my-5 p:first-letter {
    font-size: 1.5rem;
    font-weight: 600;
    color: #e74c3c;
}

.my-5 p:last-child {
    margin-bottom: 0; /* Removing bottom margin for the last paragraph */
}

.my-5 h2.text-center {
    text-align: center;
    font-family: 'Roboto', sans-serif;
    letter-spacing: 1.5px;
}

/* Responsive adjustments */
@media (max-width: 500px) {
    .my-5 {
        padding: 20px;
    }

    .my-5 h2 {
        font-size: 2rem;
    }

    .my-5 p {
        font-size: 1rem;
    }
}

/* CSS for the scroll content section */
.scroll-content {
    max-height: 0; /* Start hidden */
    overflow: hidden; /* Prevent overflow when hidden */
    transition: max-height 0.5s ease, opacity 0.5s ease; /* Smooth transition for height and opacity */
    opacity: 0; /* Start with opacity 0 for fade effect */
}

.scroll-content.visible {
    max-height: 500px; /* Adjust height as needed for visible content */
    opacity: 1; /* Fade in effect */
}

/* Additional styles for paragraphs inside the scroll content */
.scroll-content p {
    padding: 10px 0; /* Add vertical spacing between paragraphs */
    font-size: 16px; /* Font size for better isible
    readability */
    color: #333; /* Text color */
    line-height: 1.5; /* Line height for better readability */
}

/* Style for the arrow container */
#arrow-container {
    display: flex; /* Flex display for centering */
    justify-content: center; /* Center the arrow icon */
    margin: 20px 0; /* Space above and below the arrow */
    cursor: pointer; /* Change cursor on hover */
}

#arrow-container:hover {
    transform: scale(1.1); /* Slightly increase size on hover for effect */
}

/* CSS for individual scroll items */
.scroll-item {
    margin: 20px 0; /* Spacing between items */
    padding: 10px; /* Padding around the content */
    border: 1px solid #ddd; /* Optional border for each item */
    border-radius: 5px; /* Rounded corners */
    background-color: #fff; /* Background color */
}

/* CSS for headings inside scroll items */
.scroll-heading {
    font-size: 18px; /* Font size for headings */
    font-weight: bold; /* Bold headings */
    color: #007BFF; /* Color for headings */
    display: flex; /* Flexbox for icon and text alignment */
    align-items: center; /* Center items vertically */
}

/* CSS for icon placeholders */
.icon-placeholder {
    margin-right: 10px; /* Space between icon and text */
    font-size: 20px; /* Size for the icon */
    color: #555; /* Color for the icon */
}


</style>

</head>
<body>
<div class="bg">
    <video id="video1" autoplay muted>
        <source src="videos/854417-uhd_3840_2160_25fps.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <video id="video2" class="video-hidden" muted>
        <source src="videos/4865900-uhd_2160_4096_25fps.mp4" type="video/mp4 ">
        Your browser does not support the video tag.
    </video>

    <video id="video3" class="video-hidden" muted>
        <source src="videos/6334054-uhd_2160_4096_25fps.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                        <a class="nav-link" href="shelf.php">Book Shelf</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/index.php">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="most_used_books.php">Frequent Books</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="header-content">
        <div class="container">
            <h1 class="animate_animated animate_fadeInDown">Welcome to the Library Management System</h1>
            <p class="animate_animated animate_fadeInUp">Explore a world of books with our intuitive system.</p>
        </div>
    </div>
</div>


</nav>

<div class="featured-content">
    <video autoplay muted loop class="background-video">
        <source src="videos/ebook.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card animate_animated animate_fadeInLeft">
                    <h3>E - Book Catalog</h3>
                    <p>Browse our extensive collection of E - books.</p>
                    <a href="ebook.php" class="btn btn-primary">Explore E-Books</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card animate_animated animate_fadeInUp">
                    <h3>Audio-Books</h3>
                    <p>Discover the world of books through sound</p>
                    <a href="audiobook.php" class="btn btn-primary">Explore AudioBooks</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card animate_animated animate_fadeInRight">
                    <h3>Events & News</h3>
                    <p>Stay updated with our latest events and news.</p>
                    <a href="event_news.php" class="btn btn-primary">View Events</a>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="my-5">
    <h2 class="text-center">About Our Library Management System</h2>
    <p>
    <strong>B</strong>ooks serve a critical purpose in society by operating with enhanced efficiency and significantly reduced costs, ultimately benefiting both the institution and its users. They are not just collections of books; they provide essential resources that contribute to educational growth of individuals and the community at large. By embracing modern technologies and practices, libraries can streamline operations, ensuring they remain relevant in the digital age.
</p>

<p>
    <strong>O</strong>n an entirely automated environment, the library management system streamlines various operational tasks that were once time-consuming and tedious. This automation encompasses everything from user registrations to managing book inventories. By integrating advanced technologies, libraries can manage their resources effectively, providing a smoother experience for users and staff alike.
</p>

<p>
    <strong>O</strong>ffice purchasing, cataloging, indexing, circulation recording, and stock checking are all efficiently handled by the software, eliminating the complexities that come with manual processes. The library management system allows librarians to track new acquisitions and categorize them swiftly, ensuring all titles are accurately listed in the database for easy retrieval. This system also supports better inventory management, helping libraries identify popular titles and manage their collections accordingly.
</p>

<p>
    <strong>K</strong>eeping repetitive manual work to a minimum significantly lowers the likelihood of errors that can occur during data entry and management tasks. This reduction in human error enhances the reliability of the information within the library’s system but also builds trust with users, who can confidently rely on the accuracy of the data presented. As a result, the overall efficiency of the library's operations improves, creating a more productive environment.
</p>

<p>
    <strong>S</strong>aving operational expenses is another benefit, as managing a library manually can be labor-intensive and involves excessive paperwork that consumes time and resources. By shifting to an automated approach, libraries can reallocate their budgets to more impactful areas, such as acquiring new materials or investing in community programs. This financial efficiency allows libraries to serve larger audiences while maintaining high standards of service.
</p>

</div>


<div class="gallery">
    <div class="container">
        <h2 class="animate_animated animate_zoomIn">Library Gallery</h2>
        <div class="row">
            <div class="col-md-4 gallery-item">
                <img src="images/4.jpg" alt="Library Image">
            </div>
            <div class="col-md-4 gallery-item">
                <img src="images/5.jpg" alt="Library Image">
            </div>
            <div class="col-md-4 gallery-item">
                <img src="images/6.jpg" alt="Library Image">
            </div>
            <div class="col-md-4 gallery-item">
                <img src="images/7.jpg" alt="Library Image">
            </div>
            <div class="col-md-4 gallery-item">
                <img src="images/10.jpg" alt="Library Image">
            </div>
            <div class="col-md-4 gallery-item">
                <img src="images/9.jpg" alt="Library Image">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const galleryItems = document.querySelectorAll('.gallery-item');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible'); // Add class to trigger animation
                observer.unobserve(entry.target); // Stop observing after it's visible
            }
        });
    });

    galleryItems.forEach(item => {
        observer.observe(item); // Observe each gallery item
    });
});

    </script>


<div class="about-section">
    <div class="container">
        <h2 class="animate_animated animate_fadeInLeft">Learn More</h2>
        <p class="animate_animated animate_fadeInRight">Learn about our mission to provide excellent library services to our community.</p>

        <!-- Arrow Icon -->
        <div id="arrow-container" class="arrow-bounce">
            <i class="fas fa-chevron-down"></i>
        </div>

        <!-- Hidden Content Section -->
        <div id="hidden-section" class="hidden-content">
            <p>Our library is more than just a place to borrow books; it is a vibrant community hub dedicated to fostering a love for reading and learning. We are committed to providing a diverse collection of resources that cater to all ages and interests, from classic literature to the latest bestsellers, academic journals, and multimedia resources. Our mission is to create a welcoming and inclusive environment where everyone can explore, discover, and grow. Whether you're a student seeking information for a research project, a parent looking for children's books, or a lifelong learner pursuing new knowledge, our library is here to support you on your journey. We believe that access to information and education is a fundamental right, and we strive to make our services accessible to all members of our community. Join us in our mission to inspire, educate, and empower through the power of knowledge and imagination.</p>
        </div>

        <!-- New Scrolling Content Section -->
<div id="scroll-section" class="scroll-content">
    <div class="scroll-item">
        <h3 class="scroll-heading">
            <span class="icon-placeholder">&#128196;</span> <!-- Placeholder for icon -->
            Access Data Anytime, Anywhere
        </h3>
        <p>Student records right from the time of admission till they pass out in a centralized location for easy access.</p>
    </div>
    
    <div class="scroll-item">
        <h3 class="scroll-heading">
            <span class="icon-placeholder">&#128204;</span> <!-- Placeholder for icon -->
            Eliminate Laborious Paperwork
        </h3>
        <p>Eliminate the headache of managing, storing and retrieving paper documents. All documents organized and stored in a secure database.</p>
    </div>
    
    <div class="scroll-item">
        <h3 class="scroll-heading">
            <span class="icon-placeholder">&#128187;</span> <!-- Placeholder for icon -->
            Smarter Data-Backed Decisions
        </h3>
        <p>Get the accurate, up-to-date information you need in real-time for smarter, quicker decisions.</p>
    </div>
</div>

    </div>
</div>

<style>
    .scroll-content {
        max-height: 0; /* Start hidden */
        overflow: hidden;
        transition: max-height 0.5s ease; /* Smooth transition */
    }

    .scroll-content.visible {
        max-height: 500px; /* Adjust height as needed */
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scrollSection = document.getElementById('scroll-section');
        const arrowContainer = document.getElementById('arrow-container');

        // Show scroll content on arrow click
        arrowContainer.addEventListener('click', function() {
            scrollSection.classList.toggle('visible');
        });

        // Hide scroll content on scroll up
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop < lastScrollTop) {
                scrollSection.classList.remove('visible'); // Hide on scroll up
            } else {
                scrollSection.classList.add('visible'); // Show on scroll down
            }
            lastScrollTop = scrollTop;
        });
    });
</script>


<!-- Chatbot Icon (Lower right corner) -->
<div id="chat-bot">
    <div id="chat-icon" style="position: fixed; bottom: 20px; right: 20px; cursor: pointer; background-color: #007bff; border-radius: 50%; width: 60px; height: 60px; display: flex; justify-content: center; align-items: center; color: white; font-size: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <i class="fas fa-comment-dots"></i> <!-- Font Awesome Chat Icon -->
        <div id="notification-badge" style="position: absolute; top: -10px; right: -10px; width: 15px; height: 15px; background: red; border-radius: 50%; display: block;"></div>
    </div>

    <!-- Chat Window -->
    <div id="chat-window" style="position: fixed; bottom: 80px; right: 20px; width: 300px; height: 400px; background: #fff; border: 1px solid #ccc; display: none; border-radius: 10px;">
        <div id="chat-header" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #ccc;">
            <div>Chatbot</div>
            <button id="close-chat" style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
        </div>
        <div id="output" style="height: 350px; overflow-y: scroll; padding: 10px;"></div>
        <div id="input-area" style="display: flex; border-top: 1px solid #ccc;">
            <input id="user-input" type="text" placeholder="Type here..." style="width: 80%; padding: 10px; border: none; outline: none;">
            <button onclick="sendMessage()" style="width: 20%; background-color: #007bff; color: white; border: none; cursor: pointer;">Send</button>
        </div>
    </div>
</div>

<script>
// Function to close chat window
function closeChatWindow() {
    document.getElementById('chat-window').style.display = 'none';
}

// Toggle chat window visibility
document.getElementById('chat-icon').addEventListener('click', function() {
    var chatWindow = document.getElementById('chat-window');
    chatWindow.style.display = (chatWindow.style.display === 'none' || chatWindow.style.display === '') ? 'block' : 'none';
});

// Close chat window when clicking outside
document.addEventListener('click', function(event) {
    var chatWindow = document.getElementById('chat-window');
    var chatIcon = document.getElementById('chat-icon');
    var isClickInside = chatWindow.contains(event.target) || chatIcon.contains(event.target);
    if (!isClickInside) {
        closeChatWindow();
    }
});

// Close chat window on close button click
document.getElementById('close-chat').addEventListener('click', closeChatWindow);

// Show chatbot window and send auto-greeting
document.getElementById('chat-icon').addEventListener('click', function () {
    document.getElementById('chat-window').style.display = 'block';
    document.getElementById('notification-badge').style.display = 'none';
    
    // Send greeting if it's the first time
    if (!document.getElementById('output').innerHTML) {
        appendBotMessage("Hello! How can I assist you today? You can ask me for book recommendations by genre!");
    }
});

// Append bot message to chat window
function appendBotMessage(message) {
    const chatContent = document.getElementById('output');
    const botMessage = document.createElement('div');
    botMessage.style.backgroundColor = '#f1f1f1';
    botMessage.style.padding = '10px';
    botMessage.style.margin = '10px 0';
    botMessage.style.borderRadius = '10px';
    botMessage.textContent = message;
    chatContent.appendChild(botMessage);
    chatContent.scrollTop = chatContent.scrollHeight;
}

// Append user message to chat window
function appendUserMessage(message) {
    const chatContent = document.getElementById('output');
    const userMessage = document.createElement('div');
    userMessage.style.backgroundColor = '#d1f1d1';
    userMessage.style.padding = '10px';
    userMessage.style.margin = '10px 0';
    userMessage.style.borderRadius = '10px';
    userMessage.textContent = message;
    chatContent.appendChild(userMessage);
    chatContent.scrollTop = chatContent.scrollHeight;
}

// Handle user input
document.getElementById('user-input').addEventListener('keypress', function (event) {
    if (event.key === 'Enter') {
        sendMessage(); // Call the sendMessage function
    }
});

// Fetch bot response via AJAX
function getBotResponse(userMessage) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'chatbot_response.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            appendBotMessage(xhr.responseText);
        } else {
            appendBotMessage('Sorry, something went wrong.');
        }
    };
    xhr.send('message=' + encodeURIComponent(userMessage));
}

// Function to handle sending user message
function sendMessage() {
    const userInput = document.getElementById('user-input');
    const userMessage = userInput.value.trim();
    if (userMessage) {
        appendUserMessage(userMessage);
        getBotResponse(userMessage); // Send user message for processing
        userInput.value = ''; // Clear input field
    }
}
</script>




<footer class="footer">
    <div class="container">
        <!-- Company Information Section -->
        <div class="footer-section company-info">
            <h4>Library Management System</h4>
            <p>We provide efficient and user-friendly solutions for managing libraries, enhancing the learning experience for all users.</p>
        </div>

        <!-- Solutions Section -->
        <div class="footer-section solutions">
            <h5>Solutions</h5>
            <ul>
                <li><a href="#">Classroom Solutions</a></li>
                <li><a href="#">Home Learning Solutions</a></li>
                <li><a href="#">School Management Solutions</a></li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="footer-section contact">
            <h5>Contact</h5>
            <ul>
                <li>Phone: +91-89 21 70 43 51</li>
                <li>Phone: +91-809 568 347</li>
                <li>Email: <a href="mailto:librarymanagementsystem24@gmail.com">librarymanagementsystem24@gmail.com</a></li>
                <li>Website: <a href="http://localhost/lmsf/index.php">www.lmsf.com</a></li>
            </ul>
        </div>

        <!-- Download Section -->
        <div class="footer-section download">
            <h5>Download</h5>
            <ul>
                <li><a href="#">Android App</a></li>
                <li><a href="#">iOS App</a></li>
            </ul>
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="footer-copyright">
        <p>&copy; 2024 Library Management System. All rights reserved.</p>
    </div>
</footer>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const videos = [
        document.getElementById('video1'),
        document.getElementById('video2'),
        document.getElementById('video3')
    ];
    let currentIndex = 0;

    const playNextVideo = () => {
        videos[currentIndex].classList.add('video-hidden');
        currentIndex = (currentIndex + 1) % videos.length; // Loop back to the first video after the last one
        videos[currentIndex].classList.remove('video-hidden');
        videos[currentIndex].play();
    };

    videos.forEach((video, index) => {
        video.addEventListener('ended', playNextVideo);
    });
});


</script>
<script>
document.getElementById("arrow-container").addEventListener("click", function() {
    const hiddenSection = document.getElementById("hidden-section");

    if (hiddenSection.style.display === "none" || hiddenSection.style.display === "") {
        hiddenSection.style.display = "block";
    } else {
        hiddenSection.style.display = "none";
    }
});
</script>

</body>
</html>