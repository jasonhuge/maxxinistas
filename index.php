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
                        <span class="emoji-examples">🦷 🌙 🤖 🚀 👻 🍕</span>
                        <span>= Points!</span>
                    </div>
                    <div class="instruction-row bad">
                        <span class="emoji-examples">📱 📸 🍺 🚶 🙄 💩</span>
                        <span>= Lose a life!</span>
                    </div>
                </div>
                <p class="controls-info">Use ← → arrow keys or mouse to move</p>
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
                ← → Arrow keys or mouse to move
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
