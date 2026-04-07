<?php
include 'connect_db.php';
$talents = [];
if(isset($conn) && !$conn->connect_error) {
    // Suppress error if table doesn't exist yet gracefully
    $result = @$conn->query("SELECT * FROM talents ORDER BY created_at DESC");
    if($result) {
        while($row = $result->fetch_assoc()) {
            $talents[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Talents - Poise Sports</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .social-icons { display: flex; align-items: center; gap: 15px; }
        .social-icons a { text-decoration: none; color: #fff; font-size: 22px; display: flex; align-items: center; transition: 0.3s ease; }
        .social-icons a:hover { transform: scale(1.1); color: #1a3151; }
        .tm-icon svg { width: 22px; height: 22px; fill: #fff; transition: fill 0.3s ease; }
        .tm-icon:hover svg { fill: #1a3151; }

        /* General page layout */
        .page-wrapper { padding: 120px 20px 50px; background: #000; color: #fff; text-align: center; }

        /* Dynamic SLIDER for Players */
        .dynamic-gallery {
            position: relative;
            max-width: 900px;
            height: 600px;
            margin: 0 auto 80px auto;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.8);
            background: #111;
        }

        .slider-wrapper {
            display: flex;
            height: 100%;
            width: 100%;
            transition: transform 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .player-container {
            min-width: 100%;
            height: 100%;
            position: relative;
        }

        .player-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .player-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.5) 70%, transparent 100%);
            padding: 40px 20px 20px;
            text-align: center;
            border-bottom: 3px solid #ffd700;
        }
        
        .player-overlay h3 { margin-bottom: 5px; font-size: 2.2rem; color: #fff; text-transform: uppercase; letter-spacing: 2px;}
        .player-overlay h4 { color: #ffd700; font-size: 1.2rem; margin-bottom: 10px; text-transform: uppercase; font-weight: bold; }

        /* NEWS GRID SECTION - Matches the requested photo style */
        .news-section {
            max-width: 1200px;
            margin: 0 auto 60px auto;
            text-align: left;
        }
        .news-section h2 {
            font-family: 'Cinzel', serif; 
            text-align: center; 
            font-size: 2.5rem; 
            letter-spacing: 3px; 
            margin-bottom: 50px;
            color: #fff;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 35px;
        }

        .news-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.2);
        }
        .news-img-wrapper {
            position: relative;
            height: 240px;
        }
        .news-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .news-badge {
            position: absolute;
            bottom: 0px;
            left: 20px;
            background: #e60000;
            color: #fff;
            font-weight: bold;
            font-size: 0.85rem;
            padding: 6px 14px;
            text-transform: uppercase;
        }

        .news-content {
            padding: 25px 20px;
        }
        .news-title {
            color: #111;
            font-size: 1.4rem;
            font-weight: bold;
            line-height: 1.4;
            margin-bottom: 15px;
            font-family: 'Lato', sans-serif;
        }
        .news-meta {
            font-size: 0.9rem;
            color: #888;
            font-weight: 500;
        }
        .news-meta span {
            color: #e60000;
            font-weight: bold;
        }

        /* MODAL STYLES (For reading more info) */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 2000; 
            left: 0; top: 0; 
            width: 100%; height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.85);
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto; 
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            color: #111;
            position: relative;
            box-shadow: 0 15px 50px rgba(0,0,0,0.5);
            animation: modalFadeIn 0.3s ease-out;
            overflow: hidden;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .close-btn {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            transition: 0.3s;
        }
        .close-btn:hover { color: #ffd700; transform: scale(1.1); }
        
        .modal-img-container {
            position: relative;
            width: 100%;
            height: 400px;
        }
        .modal-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .modal-body {
            padding: 40px;
            text-align: left;
        }
        .modal-body h2 {
            font-size: 2.5rem;
            margin-bottom: 5px;
            color: #111;
            font-family: 'Cinzel', serif;
        }
        .modal-body h4 {
            color: #e60000;
            font-size: 1.2rem;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .modal-body p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
        }

        /* Instagram Section */
        .ig-section { background: #fff; color: #111; padding: 60px 20px; text-align: center; }
        .ig-section h2 { font-family: 'Cinzel', serif; letter-spacing: 2px; }
        .line-separator { width: 60px; height: 3px; background: #111; margin: 15px auto; }
        .hashtag { font-weight: bold; letter-spacing: 1px; margin-bottom: 30px; }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo">Poise Sports</div>
        <div class="menu-toggle" id="mobile-menu"><span>&#9776;</span></div>
        <nav>
            <ul id="nav-list">
                <li><a href="home.html">HOME</a></li>
                <li><a href="about.html">ABOUT</a></li>
                <li><a href="services.html">SERVICES</a></li>
                <li><a href="new.php" style="color: #ffd700;">NEWS & TALENTS</a></li>
                <li><a href="contact.html">CONTACT</a></li>
            </ul>
        </nav>
        <div class="social-icons">
            <a href="https://www.instagram.com/p/C6147P0SdXT/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://www.facebook.com/share/1A8c7JEUCP/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://www.transfermarkt.com/poise-entertainment-group/beraterfirma/berater/15775" target="_blank" class="tm-icon" title="Transfermarkt"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 2C5.98 2 1.5 6.48 1.5 12s4.48 10 10 10 10-4.48 10-10S17.02 2 11.5 2zm3.95 14.59h-1.83l-.58-3.08h-3.14l-.58 3.08H7.49l1.88-8.91h2.24l.32 1.76h.12l.32-1.76h2.24l1.84 8.91z"/><path d="M10.3 11.8h1.4l-.4-2.1h-.1l-.9 2.1z"/></svg></a>
            <a href="mailto:info@poiseentertainmentgroup.com"><i class="fas fa-envelope"></i></a>
        </div>
    </header>

    <div class="page-wrapper">
        <h1 style="font-family: 'Cinzel', serif; font-size: 2.5rem; letter-spacing: 5px; margin-bottom: 50px;">LATEST NEWS</h1>
        
        <!-- Slider Area -->
        <div class="dynamic-gallery">
            <div class="slider-wrapper" id="slider">
                <?php if (count($talents) > 0): ?>
                    <?php foreach($talents as $talent): ?>
                    <div class="player-container">
                        <img src="<?php echo htmlspecialchars($talent['image_path']); ?>" alt="<?php echo htmlspecialchars($talent['name']); ?>" class="player-image">
                        <div class="player-overlay">
                            <h3><?php echo htmlspecialchars($talent['name']); ?></h3>
                            <h4><?php echo htmlspecialchars($talent['position']); ?></h4>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="player-container">
                        <img src="Nelsson.jpg" alt="Player" class="player-image">
                        <div class="player-overlay">
                            <h3>Nelson Laomie</h3>
                            <h4>Featured Player</h4>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- News Card Session -->
        <div class="news-section">
            <h2>Player News & Profiles</h2>
            <div class="news-grid">
                <?php if (count($talents) > 0): ?>
                    <?php foreach($talents as $index => $talent): ?>
                    <div class="news-card" onclick="openModal(<?php echo $index; ?>)">
                        <div class="news-img-wrapper">
                            <img src="<?php echo htmlspecialchars($talent['image_path']); ?>" alt="<?php echo htmlspecialchars($talent['name']); ?>">
                            <!-- The red tag -->
                            <span class="news-badge"><?php echo htmlspecialchars($talent['position']); ?></span>
                        </div>
                        <div class="news-content">
                            <!-- News headline structure matching the user's reference -->
                            <h3 class="news-title"><?php echo htmlspecialchars($talent['name']); ?>: Learn More About This Rising Talent</h3>
                            <p class="news-meta">
                                <span>NEWS</span> &mdash; <?php echo date("F j, Y", strtotime($talent['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default fallback card -->
                    <div class="news-card">
                        <div class="news-img-wrapper">
                            <img src="Nelsson.jpg" alt="Nelson Laomie">
                            <span class="news-badge">FORWARD</span>
                        </div>
                        <div class="news-content">
                            <h3 class="news-title">Nelson Laomie: Featured Talent Overview & Stats</h3>
                            <p class="news-meta">
                                <span>NEWS</span> &mdash; <?php echo date("F j, Y"); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Hidden Modal for Popups -->
    <div id="playerModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div class="modal-img-container">
                <img id="modal-img" src="" alt="Player Full Image">
            </div>
            <div class="modal-body">
                <h2 id="modal-title">Player Name</h2>
                <h4 id="modal-position">Position</h4>
                <p id="modal-desc">Details will appear here...</p>
            </div>
        </div>
    </div>

    <div class="ig-section">
        <h2>FOLLOW US ON SOCIAL MEDIA</h2>
        <div class="line-separator"></div>
        <p class="hashtag">poise-sports ON INSTAGRAM</p>
        <div class="sk-instagram-feed" data-embed-id="25638670"></div>
        <script src="https://widgets.sociablekit.com/instagram-feed/widget.js" defer></script>
    </div>

    <footer class="site-footer">
        <div class="footer-top">
            <div class="footer-column brand-info">
                <h2 class="footer-logo">POISE</h2>
                <p class="brand-sub">SPORTS MANAGEMENT</p>
                <p class="copyright">© 2026 POISE SPORTS MANAGEMENT</p>
            </div>
            <div class="footer-column">
                <h3>PAGES</h3>
                <hr class="footer-line">
                <ul>
                    <li><a href="home.html">HOME</a></li>
                    <li><a href="about.html">ABOUT</a></li>
                    <li><a href="services.html">SERVICES</a></li>
                    <li><a href="new.php">NEWS</a></li>
                    <li><a href="contact.html">CONTACT</a></li>
                    <li><a href="admin.php" style="color: #666; font-weight: bold; font-size: 0.8rem; margin-top: 20px; display: inline-block;"><i class="fas fa-lock"></i> ADMIN LOGIN</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE JAVASCRIPT -->
    <script>
        // --- 1. SLIDER LOGIC ---
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('slider');
            const slides = document.querySelectorAll('.player-container');
            
            if (slides.length > 1) {
                let currentSlide = 0;
                setInterval(() => {
                    currentSlide = (currentSlide + 1) % slides.length;
                    wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
                }, 3000); 
            }
        });

        // --- 2. MODAL POPUP LOGIC ---
        const talentsData = <?php echo json_encode($talents); ?>;
        const modal = document.getElementById("playerModal");

        function openModal(index) {
            const player = talentsData[index];
            if(!player) return;

            document.getElementById("modal-title").innerText = player.name;
            document.getElementById("modal-position").innerText = player.position;
            
            let desc = player.description ? player.description.replace(/\n/g, "<br>") : "No additional background information provided.";
            document.getElementById("modal-desc").innerHTML = desc;
            
            document.getElementById("modal-img").src = player.image_path;
            
            modal.style.display = "block";
            document.body.style.overflow = "hidden"; // Prevents scrolling screen while pop-up is open
        }

        function closeModal() {
            modal.style.display = "none";
            document.body.style.overflow = "auto"; // Re-enable scroll
        }

        // Close if the user clicks anywhere outside the white box
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
    <script src="design.js"></script>
    <script src="news.js"></script>
</body>
</html>
