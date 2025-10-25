<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Boowal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, p {
            font-family: 'Montserrat', sans-serif;
            font-weight: 100;
            letter-spacing: 3px;
            color: #fff;
            line-height: 1.7;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;
            letter-spacing: 3px;
            font-weight: 400;
            margin-bottom: 15px;
            color: #fff;
        }

        .homepage-section {
            /* NOTE: Background image URL is a placeholder and may not load */
            background: url('bg7.png'); 
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 80px; 
            font-size: 14px;
            letter-spacing: 4px;
            text-transform: uppercase;
            background: rgb(0,0,0);
            z-index: 1000;
        }

        body {
            color: #fff;
            font-family: 'Oswald', sans-serif;
            overflow-x: hidden;
            background-size: cover;
            padding-top: 65px; 
        }

        header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 400;
            font-size: 20px;
            color: #fff;
        }

        header .logo img {
            height: 60px; 
            width: auto;
            border-radius: 4px;
        }

        header nav a {
            color: #bbb;
            text-decoration: none;
            margin-left: 40px;
            position: relative;
            transition: color 0.3s;
        }

        header nav a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background-color: #c9110d;
            transition: width 0.3s ease-in-out;
        }

        header nav a:hover {
            color: #c9110d;
        }

        header nav a:hover::after {
            width: 100%;
        }

        model-viewer {
            width: 100%;
            height: 700px;
            display: block;
            --poster-color: transparent;
            background: black;
            position: relative;
        }

        model-viewer::after {
            content: "";
            position: absolute;
            bottom: -40%;
            left: 0;
            width: 100%;
            height: 40%;
            background: inherit;
            transform: scaleY(-1);
            opacity: 0.2;
            filter: blur(4px);
        }

        .car-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 25px;
            margin: 30px 0;
        }

        .explore-btn {
            padding: 12px 40px;
            background: none;
            border: 1px solid #fff;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
            letter-spacing: 3px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .explore-btn:hover {
            background: #fff;
            color: #000;
        }

        .arrow {
            font-size: 20px;
            color: #fff;
            font-weight: bold;
        }

        .brand-logos {
            display: flex;
            gap: 20px;
        }

        /* FIX: Changed width to auto to maintain aspect ratio and prevent squishing */
        .brand-logos img {
            height: 60px; /* Keeps the vertical size consistent */
            width: auto;   /* Ensures the width scales proportionally */
            opacity: 0.8;
            transition: transform 0.3s, opacity 0.3s;
            cursor: pointer;
        }

        .brand-logos img:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .about-section {
            background: #000;
            color: #fff;
            padding: 80px 60px;
            text-align: center;
        }

        .about-title {
            font-size: 48px;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 3px;
        }
        .about-title span { color: #c9110d; }

        .since {
            color: #bbb;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-red {
            display: inline-block;
            padding: 12px 30px;
            background: #c9110d;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            letter-spacing: 2px;
            transition: background 0.3s;
        }
        .btn-red:hover { background: #a10c0a; }

        .about-perks {
            margin-top: 80px;
        }
        .about-perks h2 {
            font-size: 36px;
            margin-bottom: 20px;
            letter-spacing: 3px;
        }
        .about-perks h2 span { color: #c9110d; }
        .perks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }
        .perk-box {
            border: 1px solid #333;
            padding: 20px;
            border-radius: 8px;
            background: rgba(20,20,20,0.8);
            transition: transform 0.3s;
        }
        .perk-box:hover { transform: translateY(-5px); border-color: #c9110d; }

        .our-story {
            margin-top: 100px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .our-story h2 {
            font-size: 36px;
            margin-bottom: 20px;
            letter-spacing: 3px;
        }
        .our-story span { color: #c9110d; }
        .our-story p { line-height: 1.8; color: #aaa; }

        .choose-us {
            margin-top: 100px;
        }
        .choose-us h2 {
            font-size: 36px;
            margin-bottom: 40px;
            letter-spacing: 3px;
        }
        .choose-us span { color: #c9110d; }
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }
        .testimonial {
            padding: 20px;
            border: 1px solid #333;
            border-radius: 8px;
            background: rgba(20,20,20,0.8);
            font-style: italic;
        }
        
        .contact-section {
            background: #000;
            color: #fff;
            padding: 80px 60px;
            text-align: center;
        }

        .contact-title {
            font-size: 48px;
            margin-bottom: 10px;
            letter-spacing: 3px;
        }
        .contact-title span { color: #c9110d; }

        .contact-subtitle {
            text-align: center;
            margin-bottom: 60px;
            color: #bbb;
            font-size: 16px;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: start;
        }


        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
        }

        .contact-form h2 {
            color: #fff;
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #333;
            border-radius: 5px;
            background: #111;
            color: #fff;
            font-family: 'Oswald', sans-serif;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            outline: none;
            border-color: #c9110d;
        }

        .contact-form label {
            font-size: 14px;
            color: #bbb;
            margin-top: 10px;
            letter-spacing: 2px;
        }

        .contact-form button {
            background: #c9110d;
            border: none;
            color: #fff;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            letter-spacing: 2px;
            transition: background 0.3s;
        }
        .contact-form button:hover { background: #a10c0a; }


        .contact-info {
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-info p {
            color: #bbb;
            line-height: 1.6;
        }

        .info-box h3 {
            color: #c9110d;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .info-box p {
            color: #bbb;
            margin: 0;
        }

        .map {
            margin-top: 20px;
            border: 2px solid #222;
            border-radius: 8px;
            overflow: hidden;
        }


        @media (max-width: 900px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
            .contact-form, .contact-info {
                text-align: center;
            }
            .contact-form h2 {
                text-align: center;
            }
        }


        .footer {
            background: #000000;
            color: #bbb;
            padding: 60px 60px 20px;
            margin-top: 1px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
            margin-bottom: 30px;
        }

        /* Updated CSS for the footer logo container, though the image sizing is inline for precision */
        .footer-logo {
            margin-bottom: 15px;
            /* Ensure the image is centered if the container is wider, though unnecessary here */
            display: inline-block; 
        }

        .footer-box h3 {
            color: #fff;
            margin-bottom: 15px;
            font-size: 18px;
            letter-spacing: 2px;
        }

        .footer-box ul {
            list-style: none;
        }

        .footer-box ul li {
            margin-bottom: 10px;
        }

        .footer-box ul li a {
            color: #bbb;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-box ul li a:hover {
            color: #c9110d;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icons img {
            width: 30px;
            height: 30px;
            opacity: 0.7;
            transition: opacity 0.3s, transform 0.3s;
            cursor: pointer;
        }

        .social-icons img:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .footer-bottom {
            border-top: 1px solid #222;
            text-align: center;
            padding-top: 15px;
            color: #666;
            font-size: 14px;
        }

    </style>
</head>
<body>

    <div class="homepage-section">
        
        <header>
            <div class="logo">
                <img src="garageboowal.png" alt="Paint Garage Logo">
                
            </div>
            <nav>
                <a href="#">HOME</a>
                <a href="#about">SERVICES</a> 
                <a href="scratchanddent.php">SCRATCH & DENT FIX</a>
                <a href="#contact">CONTACT</a>
            </nav>
        </header>

        <!-- Model Viewer for 3D Car Visualization -->
        <model-viewer src="audi.glb"
            auto-rotate
            camera-controls
            camera-orbit="90deg 90deg 1m"
            background-color="#000000"
            shadow-intensity="1"
            shadow-softness="0.9"
            environment-image="neutral">
        </model-viewer>

        
        <section class="car-actions">
            <button class="explore-btn">EXPLORE</button>
            <span class="arrow">→</span>
            <div class="brand-logos">
                <!-- Image URLs are placeholders and may not load -->
                <a href="suzuki.php" target="_blank">
                    <img src="suzuki1.png" alt="suzuki">
                </a>
                <a href="toyota.php" target="_blank">
                    <img src="toyota.png" alt="Toyota">
                </a>
                <a href="honda.php" target="_blank">
                    <img src="honda1.png" alt="Toyota">
                </a>
            </div>
        </section>
    </div>
    
    <section class="about-section" id="about">
        <div class="about-container">
            <h1 class="about-title">About <span>Us</span></h1>
            <p class="since">Our Mission

Garage Boowal was founded on a simple belief: the color of your car should be as unique as you are. Tired of the guesswork involved in choosing a custom paint job, we built a digital solution. Our goal is to eliminate the uncertainty and put the power of design directly into your hands.

The Experience

We utilize advanced 3D modeling technology to provide a real-time visualization of your vehicle. From deep metallic hues to subtle matte finishes, you can experiment with every detail. What you configure on screen is precisely what our certified paint specialists deliver in the garage. This seamless transition from virtual design to physical finish guarantees satisfaction.

Our Promise

We commit to using only premium, long-lasting paints and materials. Whether you're driving a classic Toyota or a rugged Suzuki, we ensure your vehicle leaves our garage with a showroom-quality finish that commands attention. Configure it. We'll perfect it.</p>
        </div>

        <div class="about-perks">
            <h2>All the <span>Perks</span></h2>
            <p>We specialize in complete car painting, polishing, scratch removal, dent repair, and custom finishes. Choose from multiple car brands, colors, and finishes with instant price estimates.</p>
            <div class="perks-grid">
                <div class="perk-box">
                    <h3>Car Brand Selection</h3>
                    <p>Pick your car brand and explore available paint options tailored to your model.</p>
                </div>
                <div class="perk-box">
                    <h3>Custom Colors</h3>
                    <p>Browse a wide palette of paints, metallic finishes, and premium coatings with live price estimates.</p>
                </div>
                <div class="perk-box">
                    <h3>Scratch & Dent Fix</h3>
                    <p>Send us photos of damaged parts for accurate repair quotes before booking.</p>
                </div>
                <div class="perk-box">
                    <h3>Professional Finish</h3>
                    <p>Full car polish, detailing, and protective coatings for a showroom-ready look.</p>
                </div>
            </div>
        </div>

        <div class="our-story">
            <h2>Our <span>Story</span></h2>
            <p>At Garage Boowal, our passion for cars drives our commitment to perfection. Established in 1995, we've blended traditional craftsmanship with modern technology to deliver stunning results on every vehicle. We believe every car deserves a finish that turns heads and lasts for years.</p>
        </div>

        <div class="choose-us">
            <h2> <span>Reviews</span></h2>
            <div class="testimonial-grid">
                <div class="testimonial">“Professional, fast, and the finish was flawless.”</div>
                <div class="testimonial">“The price estimate was accurate and the results exceeded my expectations.”</div>
                <div class="testimonial">“I love the custom color options – my car looks brand new.”</div>
            </div>
        </div>
    </section>

    <section class="contact-section" id="contact">
        <h1 class="contact-title">Contact <span>Us</span></h1>
        <p class="contact-subtitle">We’re here to help – book an appointment, request a paint estimate, or ask any questions.</p>

        <div class="contact-container">
            
            <div class="contact-form">
                <h2>Book an Appointment</h2>
               <form id="appointmentForm" action="save_contact.php" method="POST">
  <input type="text" name="name" placeholder="Your Name" required>
  <input type="email" name="email" placeholder="Your Email" required>
  <input type="text" name="subject" placeholder="Subject">
  <input type="date" name="appointment_date" required>
  <input type="time" name="appointment_time" required>
  <textarea name="message" placeholder="Message" required></textarea>
  <button type="submit">Send Now</button>
</form>

<!-- Message will appear here -->
<p id="formMessage" style="color:white; margin-top:10px;"></p>

<script>
document.getElementById('appointmentForm').addEventListener('submit', async function(e) {
  e.preventDefault(); // stop normal form submit

  const form = e.target;
  const formData = new FormData(form);
  const messageBox = document.getElementById('formMessage');

  try {
    const res = await fetch('save_contact.php', {
      method: 'POST',
      body: formData
    });
    const text = await res.text();

    // Show response message in white
    messageBox.textContent = text;
    messageBox.style.color = 'white';

    // Optionally clear form
    form.reset();

  } catch (err) {
    messageBox.textContent = 'Something went wrong, please try again.';
    messageBox.style.color = 'red';
  }
});
</script>

            </div>

            
            <div class="contact-info">
                <p>Since 1995, Paint Garage has been serving customers with top-quality car paint and repair solutions. Reach us anytime for quotes and bookings.</p>

                <div class="info-box">
                    <h3>Phone Number</h3>
                    <p>+230 57940077</p>
                </div>

                <div class="info-box">
                    <h3>Email Address</h3>
                    <p>Garageboowal@gmail.com</p>
                </div>

                <div class="info-box">
                    <h3>Our Office</h3>
                    <p>Co-operative Road, La Flora</p>
                </div>

                
                <div class="map">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3739.262556038373!2d57.55615107481698!3d-20.41327388108646!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x217c60ce7f0047f1%3A0x53909859287a68b3!2sCooperative%20Road%2C%20La%20Flora!5e0!3m2!1sen!2smu!4v1758050365578!5m2!1sen!2smu" 
                        width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="footer-container">
            
            <div class="footer-box">
                <div class="footer-logo">
                    <img src="garageboowal.png" alt="Garage Boowal Logo" style="height: 60px; width: auto; border-radius: 4px;">
                </div>
                <p>Premium Car Painting & Care since 1995. Excellence in every detail.</p>
            </div>

            <div class="footer-box">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li> <a href="scratchanddent.php">SCRATCH & DENT FIX</a></li>
                    <li> <a href="#contact">CONTACT</a></li>
                    <li><a href="#about">SERVICES</a></li> 
                
                </ul>
            </div>

            
            <div class="footer-box">
                <h3>Contact</h3>
                <p>+230 57940077</p>
                <p>Garageboowal@gmail.com</p>
                <p>Co-operative Road, La Flora</p>
            </div>

            
            <div class="footer-box">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="facebook.png"><img src="facebook.png" alt="Facebook"></a>
                    <a href="instagram.png"><img src="instagram.png" alt="Instagram"></a>
                    
                </div>
            </div>
        </div>


        <div class="footer-bottom">
            <p>© 2025 Paint Garage. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
