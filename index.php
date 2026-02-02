<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Maxxinistas | Improv Comedy</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="persistent-contact">
        <a href="https://instagram.com/maxxinistaspdx" target="_blank" class="social-btn" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
        </a>
        <button class="contact-btn" onclick="openBookingModal()">Book Us</button>
    </div>

    <!-- Booking Modal -->
    <div class="modal-overlay" id="bookingModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeBookingModal()">&times;</button>
            <h2>BOOK US!</h2>
            <form class="booking-form" id="bookingForm" action="booking-handler.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" placeholder="Tell us about your event..." required></textarea>
                </div>
                <input type="hidden" name="gameScore" id="gameScoreField" value="">
                <button type="submit" class="pixel-btn submit-btn">SEND INQUIRY</button>
            </form>
            <div class="form-message" id="formMessage"></div>
        </div>
    </div>

    <div class="game-container splash-mode" id="gameContainer">
        <!-- Splash Screen -->
        <div class="start-screen" id="startScreen">
            <img src="images/hero.png" alt="Maxxinistas" class="hero-image-large">
            <h2>MAXXINISTAS</h2>
            <p class="tagline-splash">We love a deal.</p>
            <p class="description">Portland's bargain-hunting improv troupe</p>
            <button class="pixel-btn play-btn" onclick="startGame()">▶ PLAY</button>
            <p class="controls-hint">Catch suggestions, avoid hecklers!</p>
        </div>

        <!-- Instructions Screen -->
        <div class="instructions-screen hidden" id="instructionsScreen">
            <h2>HOW TO PLAY</h2>
            <div class="instructions-content">
                <p>Catch audience suggestions in your cart!</p>
                <div class="instructions-items">
                    <div class="instruction-row good">
                        <span class="emoji-examples">🦷 🌙 🤖 🚀 👻 💩</span>
                        <span>= Points!</span>
                    </div>
                    <div class="instruction-row bad">
                        <span class="emoji-examples">💣</span>
                        <span>= Lose a life!</span>
                    </div>
                </div>
                <p class="controls-info">Use ← → arrow keys to move</p>
            </div>
            <button class="pixel-btn" onclick="beginGame()">START</button>
        </div>

        <!-- Game Area (hidden initially) -->
        <div id="gameArea" class="hidden">
            <div class="header">
                <h1 class="title">MAXXINISTAS</h1>
                <p class="tagline">We like a bargain.</p>
            </div>

            <div class="score-board">
                <span>LEVEL: <span id="level">1</span></span>
                <span>SCORE: <span id="score">0</span></span>
                <span class="lives">LIVES: <span id="lives">❤️❤️❤️</span></span>
            </div>

            <div style="position: relative;">
                <canvas id="gameCanvas" width="560" height="400"></canvas>

                <div class="game-over-screen hidden" id="gameOverScreen">
                    <h2>SCENE!</h2>
                    <p>Final Score:</p>
                    <div class="final-score" id="finalScore">0</div>
                    <div class="suggestions-collected" id="suggestionsCollected">
                        <p style="margin-bottom: 8px;">You collected:</p>
                    </div>
                    <button class="pixel-btn" onclick="restartGame()">PLAY AGAIN</button>
                    <p style="margin-top: 20px;">Want the real thing?</p>
                    <button class="pixel-btn" id="bookBtn" style="background: #FFD700; color: #000;" onclick="openBookingWithScore()">
                        BOOK US!
                    </button>
                </div>
            </div>

            <div class="controls">
                ← → Arrow keys to move
            </div>

            <div class="mobile-controls">
                <button class="mobile-btn" id="leftBtn">◀</button>
                <button class="mobile-btn" id="rightBtn">▶</button>
            </div>
        </div>
    </div>

    <script src="js/game.js"></script>
    <script src="js/booking.js"></script>
</body>
</html>
