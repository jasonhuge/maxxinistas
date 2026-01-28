<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Maxxinistas | Improv Comedy</title>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .persistent-contact {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 100;
        }

        .contact-btn {
            display: block;
            padding: 12px 20px;
            background: #CC0000;
            color: white;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            font-weight: bold;
            border: 3px solid #FFD700;
            cursor: pointer;
            text-transform: uppercase;
        }

        .contact-btn:hover {
            background: #FF0000;
        }

        /* Booking Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 200;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: #1a1a2e;
            border: 6px solid #FFD700;
            box-shadow: 0 0 0 6px #CC0000;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 30px;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            color: #FFD700;
            font-size: 2rem;
            cursor: pointer;
            font-family: 'Courier New', monospace;
        }

        .modal-close:hover {
            color: #FF0000;
        }

        .modal-content h2 {
            font-size: 1.8rem;
            color: #FFD700;
            text-shadow: 3px 3px 0 #CC0000;
            text-align: center;
            margin-bottom: 20px;
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            color: #FFD700;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px;
            background: #2a2a3e;
            border: 3px solid #FFD700;
            color: white;
            font-family: 'Courier New', monospace;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #FF0000;
            background: #3a3a4e;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group select {
            cursor: pointer;
        }

        .submit-btn {
            margin-top: 10px;
        }

        .form-message {
            text-align: center;
            padding: 15px;
            margin-top: 10px;
            display: none;
        }

        .form-message.success {
            display: block;
            background: rgba(76, 175, 80, 0.3);
            border: 2px solid #4CAF50;
            color: #4CAF50;
        }

        .form-message.error {
            display: block;
            background: rgba(244, 67, 54, 0.3);
            border: 2px solid #f44336;
            color: #f44336;
        }

        @media (max-width: 600px) {
            .modal-content {
                padding: 20px;
            }
            .modal-content h2 {
                font-size: 1.4rem;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: #1a1a2e;
            font-family: 'Courier New', monospace;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            overflow-x: hidden;
        }

        .game-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            padding: 20px;
            padding-top: 60px;
        }

        .game-container.splash-mode {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .title {
            font-size: 2rem;
            color: #FFD700;
            text-shadow: 3px 3px 0 #CC0000;
        }

        .tagline {
            color: #FFD700;
            font-size: 1rem;
            margin-top: 5px;
            font-style: italic;
        }

        .score-board {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            background: #CC0000;
            border: 4px solid #FFD700;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        #gameCanvas {
            display: block;
            background: linear-gradient(180deg, #87CEEB 0%, #E0F4FF 100%);
            border: 6px solid #FFD700;
            box-shadow: 0 0 0 6px #CC0000;
            image-rendering: pixelated;
            width: 100%;
            cursor: none;
        }

        .start-screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: #1a1a2e;
            padding: 30px 20px;
            border: 6px solid #FFD700;
            box-shadow: 0 0 0 6px #CC0000;
            transition: opacity 0.5s ease-out;
            max-height: calc(100vh - 80px);
            max-height: calc(100dvh - 80px);
            min-height: 400px;
        }

        .start-screen.fade-out {
            opacity: 0;
        }

        #gameArea {
            transition: opacity 0.3s ease-in;
        }

        .instructions-screen {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: #1a1a2e;
            padding: 40px 20px;
            border: 6px solid #FFD700;
            box-shadow: 0 0 0 6px #CC0000;
        }

        .instructions-screen h2 {
            font-size: 2rem;
            color: #FFD700;
            text-shadow: 3px 3px 0 #CC0000;
            margin-bottom: 25px;
        }

        .instructions-content {
            margin-bottom: 25px;
        }

        .instructions-content p {
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .instructions-items {
            margin: 20px 0;
        }

        .instruction-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin: 15px 0;
            padding: 10px 20px;
            font-size: 1.1rem;
        }

        .instruction-row.good {
            background: rgba(76, 175, 80, 0.3);
            border: 2px solid #4CAF50;
        }

        .instruction-row.bad {
            background: rgba(244, 67, 54, 0.3);
            border: 2px solid #f44336;
        }

        .emoji-examples {
            font-size: 1.3rem;
            letter-spacing: 5px;
        }

        .controls-info {
            color: #FFD700;
            font-size: 1rem;
        }

        .game-over-screen {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            background: rgba(0,0,0,0.95);
            padding: 30px;
            border: 6px solid #FFD700;
            box-shadow: 0 0 0 6px #CC0000;
            z-index: 10;
            max-width: 95%;
        }

        .start-screen h2 {
            font-size: 2.2rem;
            color: #FFD700;
            text-shadow: 3px 3px 0 #CC0000;
            margin: 10px 0 5px;
        }

        .game-over-screen h2 {
            font-size: 1.8rem;
            color: #FFD700;
            margin-bottom: 15px;
        }

        .start-screen p, .game-over-screen p {
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .hero-image {
            width: 100%;
            max-width: 280px;
            margin: 15px auto;
            image-rendering: pixelated;
            image-rendering: crisp-edges;
            border: 4px solid #FFD700;
            animation: float 3s ease-in-out infinite;
        }

        .hero-image-large {
            width: auto;
            height: auto;
            max-width: 320px;
            max-height: max(35vh, 140px);
            margin: 0 auto 10px;
            image-rendering: pixelated;
            image-rendering: crisp-edges;
            border: 4px solid #FFD700;
            box-shadow: 0 0 0 4px #CC0000;
        }

        .tagline-splash {
            font-size: 1.3rem;
            color: #FFD700;
            font-style: italic;
            margin: 5px 0 10px;
        }

        .description {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .play-btn {
            font-size: 1.5rem;
            padding: 18px 50px;
        }

        .pixel-btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            background: #CC0000;
            color: white;
            text-decoration: none;
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: bold;
            border: 4px solid #FFD700;
            cursor: pointer;
            box-shadow: 4px 4px 0 #990000;
            transition: all 0.1s;
            text-transform: uppercase;
        }

        .pixel-btn:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 #990000;
        }

        .pixel-btn:active {
            transform: translate(4px, 4px);
            box-shadow: none;
        }

        .controls-hint {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-top: 10px;
        }

        .controls {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #FFD700;
        }

        .final-score {
            font-size: 3rem;
            color: #FFD700;
            animation: pulse 0.5s infinite;
        }

        .suggestions-collected {
            margin: 15px 0;
            padding: 10px;
            background: rgba(255,215,0,0.1);
            border: 2px solid #FFD700;
            font-size: 0.9rem;
        }

        .suggestions-collected span {
            display: inline-block;
            margin: 3px;
            padding: 3px 8px;
            background: #CC0000;
            border-radius: 3px;
        }

        .hidden {
            display: none !important;
        }

        .lives {
            color: #FF6B6B;
        }

        .mobile-controls {
            display: none;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .mobile-btn {
            width: 80px;
            height: 80px;
            font-size: 2rem;
            background: #CC0000;
            border: 4px solid #FFD700;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .mobile-btn:active {
            background: #990000;
            transform: scale(0.95);
        }

        @media (pointer: coarse) {
            .mobile-controls {
                display: flex;
            }
            .controls {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .title { font-size: 1.5rem; }
            .score-board { font-size: 1rem; padding: 8px 15px; }
            .game-over-screen { padding: 20px; }
            .start-screen h2 { font-size: 1.8rem; }
            .game-over-screen h2 { font-size: 1.3rem; }
            .hero-image { max-width: 200px; }
            .hero-image-large { max-width: 240px; }
            .tagline-splash { font-size: 1.1rem; }
            .description { font-size: 0.85rem; margin-bottom: 10px; }
            .play-btn { font-size: 1.2rem; padding: 15px 40px; }
            .instructions-screen h2 { font-size: 1.5rem; }
            .instructions-content p { font-size: 1rem; }
            .instruction-row { flex-direction: column; gap: 8px; padding: 8px 15px; }
            .emoji-examples { font-size: 1.1rem; letter-spacing: 3px; }
            .persistent-contact { top: 10px; right: 10px; }
            .contact-btn { padding: 8px 12px; font-size: 0.8rem; }
            .start-screen { padding: 20px 15px; }
        }

        @media (max-height: 700px) {
            .hero-image-large { max-height: max(30vh, 120px); }
            .start-screen h2 { font-size: 1.6rem; margin: 5px 0; }
            .tagline-splash { margin: 3px 0 8px; }
            .description { margin-bottom: 8px; }
            .play-btn { padding: 12px 35px; font-size: 1.1rem; }
            .controls-hint { margin-top: 5px; }
        }

        @media (max-height: 550px) {
            .hero-image-large { max-height: max(25vh, 100px); }
            .start-screen { padding: 15px 10px; }
            .start-screen h2 { font-size: 1.4rem; }
            .tagline-splash { font-size: 1rem; }
            .description { font-size: 0.8rem; }
        }

        /* Lock everything at 400px - no more shrinking below this */
        @media (max-height: 400px) {
            .hero-image-large { max-height: 100px; }
            .start-screen {
                padding: 15px 10px;
                min-height: 400px;
            }
            .start-screen h2 { font-size: 1.4rem; }
            .tagline-splash { font-size: 1rem; }
            .description { font-size: 0.8rem; }
            .play-btn { padding: 12px 35px; font-size: 1.1rem; }
        }
    </style>
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
            <img src="hero.png" alt="Maxxinistas" class="hero-image-large">
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

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const startScreen = document.getElementById('startScreen');
        const gameOverScreen = document.getElementById('gameOverScreen');
        const scoreDisplay = document.getElementById('score');
        const livesDisplay = document.getElementById('lives');
        const finalScoreDisplay = document.getElementById('finalScore');
        const suggestionsCollected = document.getElementById('suggestionsCollected');
        const bookBtn = document.getElementById('bookBtn');

        // Game state
        let gameRunning = false;
        let score = 0;
        let lives = 3;
        let items = [];
        let collectedSuggestions = [];
        let poofs = [];
        let lastSpawn = 0;
        let spawnRate = 1800;
        let difficulty = 1;

        // Parallax clouds - larger 8-bit style
        const clouds = [
            { x: 50, y: 50, size: 2.5, speed: 0.2 },
            { x: 250, y: 30, size: 3.0, speed: 0.15 },
            { x: 450, y: 70, size: 2.0, speed: 0.25 },
            { x: 150, y: 100, size: 1.8, speed: 0.1 },
            { x: 380, y: 45, size: 2.2, speed: 0.18 },
        ];

        // Player (shopping cart)
        const player = {
            x: 250,
            y: 310,
            width: 70,
            height: 55,
            speed: 8
        };

        // Audience suggestions with emoji icons
        const goodSuggestions = [
            // Occupations
            { text: "Dentist", emoji: "🦷", points: 10 },
            { text: "Doctor", emoji: "👨‍⚕️", points: 10 },
            { text: "Chef", emoji: "👨‍🍳", points: 10 },
            { text: "Pilot", emoji: "✈️", points: 15 },
            { text: "Farmer", emoji: "🧑‍🌾", points: 10 },
            { text: "Teacher", emoji: "📚", points: 10 },
            { text: "Plumber", emoji: "🔧", points: 10 },
            { text: "Firefighter", emoji: "🚒", points: 15 },
            { text: "Detective", emoji: "🔍", points: 15 },
            { text: "Astronaut", emoji: "👨‍🚀", points: 20 },
            // Emotions
            { text: "Jealousy", emoji: "💚", points: 10 },
            { text: "Awkward", emoji: "😬", points: 10 },
            { text: "Confused", emoji: "😕", points: 10 },
            { text: "Hangry", emoji: "😤", points: 10 },
            { text: "Excited", emoji: "🤩", points: 10 },
            { text: "Nervous", emoji: "😰", points: 10 },
            { text: "Proud", emoji: "🦚", points: 15 },
            { text: "Guilty", emoji: "😳", points: 15 },
            { text: "Lovesick", emoji: "😍", points: 10 },
            { text: "Grumpy", emoji: "😠", points: 10 },
            // Locations
            { text: "Moon", emoji: "🌙", points: 15 },
            { text: "Ocean", emoji: "🌊", points: 10 },
            { text: "Space", emoji: "🚀", points: 15 },
            { text: "DMV", emoji: "🚗", points: 15 },
            { text: "Hospital", emoji: "🏥", points: 10 },
            { text: "Beach", emoji: "🏖️", points: 10 },
            { text: "Castle", emoji: "🏰", points: 15 },
            { text: "Jungle", emoji: "🌴", points: 15 },
            { text: "Prison", emoji: "🔒", points: 15 },
            { text: "Circus", emoji: "🎪", points: 15 },
            { text: "Library", emoji: "📖", points: 10 },
            { text: "Gym", emoji: "💪", points: 10 },
            // Events
            { text: "Wedding", emoji: "💍", points: 15 },
            { text: "Funeral", emoji: "⚰️", points: 20 },
            { text: "Birthday", emoji: "🎂", points: 10 },
            { text: "Breakup", emoji: "💔", points: 10 },
            { text: "Prom", emoji: "💃", points: 15 },
            { text: "Interview", emoji: "💼", points: 15 },
            { text: "First Date", emoji: "🌹", points: 15 },
            { text: "Reunion", emoji: "🤗", points: 10 },
            // Characters
            { text: "Grandma", emoji: "👵", points: 10 },
            { text: "Pirate", emoji: "🏴‍☠️", points: 15 },
            { text: "Robot", emoji: "🤖", points: 15 },
            { text: "Wizard", emoji: "🧙", points: 20 },
            { text: "Cowboy", emoji: "🤠", points: 15 },
            { text: "Ghost", emoji: "👻", points: 15 },
            { text: "Ninja", emoji: "🥷", points: 20 },
            { text: "Vampire", emoji: "🧛", points: 20 },
            { text: "Alien", emoji: "👽", points: 20 },
            { text: "Mermaid", emoji: "🧜‍♀️", points: 20 },
            { text: "Clown", emoji: "🤡", points: 15 },
            { text: "Princess", emoji: "👸", points: 15 },
            { text: "Superhero", emoji: "🦸", points: 20 },
            { text: "Baby", emoji: "👶", points: 10 },
            { text: "Caveman", emoji: "🦴", points: 15 },
            // Time periods
            { text: "80s", emoji: "📼", points: 15 },
            { text: "Future", emoji: "🔮", points: 15 },
            { text: "Medieval", emoji: "⚔️", points: 15 },
            { text: "Prehistoric", emoji: "🦕", points: 20 },
            { text: "Wild West", emoji: "🌵", points: 15 },
            // Actions/Themes
            { text: "Betrayal", emoji: "🗡️", points: 20 },
            { text: "Revenge", emoji: "😈", points: 20 },
            { text: "Secret", emoji: "🤫", points: 15 },
            { text: "Disco", emoji: "🕺", points: 15 },
            { text: "Laundry", emoji: "🧺", points: 10 },
            { text: "Sleepy", emoji: "😴", points: 10 },
            // Objects/Random
            { text: "Pizza", emoji: "🍕", points: 10 },
            { text: "Banana", emoji: "🍌", points: 10 },
            { text: "Taco", emoji: "🌮", points: 10 },
            { text: "Toilet", emoji: "🚽", points: 10 },
            { text: "Unicorn", emoji: "🦄", points: 20 },
            { text: "Diamond", emoji: "💎", points: 20 },
            { text: "Cactus", emoji: "🌵", points: 10 },
            { text: "Pickle", emoji: "🥒", points: 10 },
            { text: "Monkey", emoji: "🐒", points: 15 },
            { text: "Shark", emoji: "🦈", points: 15 },
            { text: "Dragon", emoji: "🐉", points: 20 },
            { text: "Octopus", emoji: "🐙", points: 15 },
            { text: "Penguin", emoji: "🐧", points: 10 },
            { text: "Sloth", emoji: "🦥", points: 15 },
            { text: "Llama", emoji: "🦙", points: 15 },
            { text: "Donut", emoji: "🍩", points: 10 },
            { text: "Tractor", emoji: "🚜", points: 10 },
            { text: "Volcano", emoji: "🌋", points: 20 },
            { text: "Rainbow", emoji: "🌈", points: 15 },
            { text: "Thunder", emoji: "⛈️", points: 15 },
        ];

        const badSuggestions = [
            // Inappropriate suggestions audiences yell
            { text: "Poop", emoji: "💩", points: -1 },
            { text: "Eggplant", emoji: "🍆", points: -1 },
            { text: "Peach", emoji: "🍑", points: -1 },
            { text: "Dildo", emoji: "🌭", points: -1 },
            { text: "Balls", emoji: "🎾", points: -1 },
            { text: "Proctologist", emoji: "🩺", points: -1 },
            { text: "Gynecologist", emoji: "👩‍⚕️", points: -1 },
            { text: "Your Mom", emoji: "🤰", points: -1 },
            { text: "Orgy", emoji: "🛏️", points: -1 },
            { text: "Fart", emoji: "💨", points: -1 },
        ];

        // Input handling
        let keys = { left: false, right: false };
        let mouseX = null;

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') keys.left = true;
            if (e.key === 'ArrowRight') keys.right = true;
            if (e.key === ' ' && !gameRunning && !startScreen.classList.contains('hidden')) startGame();
        });

        document.addEventListener('keyup', (e) => {
            if (e.key === 'ArrowLeft') keys.left = false;
            if (e.key === 'ArrowRight') keys.right = false;
        });

        canvas.addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            mouseX = (e.clientX - rect.left) * scaleX;
        });

        canvas.addEventListener('mouseleave', () => {
            mouseX = null;
        });

        // Mobile controls
        const leftBtn = document.getElementById('leftBtn');
        const rightBtn = document.getElementById('rightBtn');

        leftBtn.addEventListener('touchstart', (e) => { e.preventDefault(); keys.left = true; });
        leftBtn.addEventListener('touchend', (e) => { e.preventDefault(); keys.left = false; });
        rightBtn.addEventListener('touchstart', (e) => { e.preventDefault(); keys.right = true; });
        rightBtn.addEventListener('touchend', (e) => { e.preventDefault(); keys.right = false; });

        const gameArea = document.getElementById('gameArea');
        const instructionsScreen = document.getElementById('instructionsScreen');

        const gameContainer = document.getElementById('gameContainer');

        function startGame() {
            // Remove splash mode for normal layout
            gameContainer.classList.remove('splash-mode');

            // Show instructions screen
            startScreen.classList.add('fade-out');

            setTimeout(() => {
                startScreen.classList.add('hidden');
                startScreen.classList.remove('fade-out');
                instructionsScreen.classList.remove('hidden');
            }, 500);
        }

        function beginGame() {
            score = 0;
            lives = 3;
            items = [];
            poofs = [];
            collectedSuggestions = [];
            difficulty = 1;
            spawnRate = 1800;
            player.x = 250;

            // Hide instructions, show game
            instructionsScreen.classList.add('hidden');
            gameOverScreen.classList.add('hidden');
            gameArea.classList.remove('hidden');
            gameRunning = true;
            updateUI();
            requestAnimationFrame(gameLoop);
        }

        function restartGame() {
            score = 0;
            lives = 3;
            items = [];
            poofs = [];
            collectedSuggestions = [];
            difficulty = 1;
            spawnRate = 1800;
            player.x = 250;

            gameOverScreen.classList.add('hidden');
            gameRunning = true;
            updateUI();
            requestAnimationFrame(gameLoop);
        }

        function endGame() {
            gameRunning = false;
            finalScoreDisplay.textContent = score;

            // Show collected suggestions
            let html = '<p style="margin-bottom: 8px;">You collected:</p>';
            const toShow = collectedSuggestions.slice(-8);
            toShow.forEach(s => {
                html += `<span>${s}</span>`;
            });
            if (collectedSuggestions.length === 0) {
                html += '<span>Nothing!</span>';
            }
            suggestionsCollected.innerHTML = html;

            // Update book button with score
            const baseHref = "mailto:book@maxxinistas.com?subject=Booking%20Inquiry%20-%20I%20scored%20" + score + "!&body=Hi%20Maxxinistas!%0A%0AI%20just%20played%20your%20game%20and%20scored%20" + score + "%20points!%0A%0AI'd%20like%20to%20book%20you%20for%20an%20event.%0A%0AEvent%20Type:%0ADate:%0ALocation:%0A%0AThanks!";
            bookBtn.setAttribute('href', baseHref);
            gameOverScreen.classList.remove('hidden');
        }

        function updateUI() {
            scoreDisplay.textContent = score;
            livesDisplay.textContent = '❤️'.repeat(lives) + '🖤'.repeat(3 - lives);
        }

        function spawnItem() {
            let suggestion;
            // Bad suggestion chance increases with difficulty (starts 15%, maxes at 40%)
            const badChance = Math.min(0.15 + (difficulty - 1) * 0.05, 0.40);

            if (Math.random() < badChance) {
                // Bad suggestion
                suggestion = badSuggestions[Math.floor(Math.random() * badSuggestions.length)];
            } else {
                // Good suggestion
                suggestion = goodSuggestions[Math.floor(Math.random() * goodSuggestions.length)];
            }

            items.push({
                x: Math.random() * (canvas.width - 120) + 60,
                y: -40,
                suggestion: suggestion,
                speed: 2 + difficulty * 0.6,
                width: 0 // Will be calculated
            });
        }

        function drawCart(x, y) {
            // Apply shake offset if shaking
            let shakeX = 0;
            let shakeY = 0;
            if (cartShake > 0) {
                shakeX = (Math.random() - 0.5) * cartShake;
                shakeY = (Math.random() - 0.5) * cartShake * 0.5;
                cartShake -= 1;
            }

            ctx.font = '55px serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('🛒', x + 35 + shakeX, y + 30 + shakeY);
        }

        function createPoof(x, y, text, isBad) {
            poofs.push({
                x: x,
                y: y,
                text: text,
                isBad: isBad,
                opacity: 1,
                life: 1.0
            });

            // Shake cart if bad
            if (isBad) {
                cartShake = 15;
            }
        }

        let cartShake = 0;

        function updateAndDrawPoofs() {
            for (let i = poofs.length - 1; i >= 0; i--) {
                const poof = poofs[i];

                // Update
                poof.life -= 0.02;
                poof.y -= 1.5;
                poof.opacity = poof.life;

                // Remove if dead
                if (poof.life <= 0) {
                    poofs.splice(i, 1);
                    continue;
                }

                // Draw poof particles
                ctx.save();
                ctx.globalAlpha = poof.opacity;

                // Draw burst particles
                const particleCount = 8;
                const burstRadius = (1 - poof.life) * 30;
                ctx.fillStyle = poof.isBad ? '#FF4444' : '#FFD700';
                for (let j = 0; j < particleCount; j++) {
                    const angle = (j / particleCount) * Math.PI * 2;
                    const px = poof.x + Math.cos(angle) * burstRadius;
                    const py = poof.y + Math.sin(angle) * burstRadius;
                    ctx.fillRect(px - 3, py - 3, 6, 6);
                }

                // Draw text - fixed size, no scaling
                ctx.font = 'bold 18px Courier New';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                // Text outline for readability
                ctx.strokeStyle = poof.isBad ? '#000000' : '#000000';
                ctx.lineWidth = 4;
                ctx.strokeText(poof.text, poof.x, poof.y);

                // Text fill
                ctx.fillStyle = poof.isBad ? '#FF4444' : '#FFFFFF';
                ctx.fillText(poof.text, poof.x, poof.y);

                ctx.restore();
            }
        }

        function drawItem(item) {
            const isBad = item.suggestion.points === -1;
            const size = 50;
            item.width = size;

            // Draw pixelated box behind emoji
            ctx.fillStyle = isBad ? '#FF4444' : '#FFFFFF';
            ctx.fillRect(item.x - size/2, item.y - size/2, size, size);

            // Pixelated border (no rounded corners for 8-bit look)
            ctx.strokeStyle = isBad ? '#CC0000' : '#FFD700';
            ctx.lineWidth = 4;
            ctx.strokeRect(item.x - size/2, item.y - size/2, size, size);

            // Draw emoji
            ctx.font = '32px serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(item.suggestion.emoji, item.x, item.y);
        }

        function drawCloud(x, y, size) {
            ctx.fillStyle = '#FFFFFF';
            const blockSize = 8 * size;
            // 8-bit blocky cloud shape
            // Top row
            ctx.fillRect(x + blockSize, y - blockSize, blockSize * 3, blockSize);
            // Middle row
            ctx.fillRect(x, y, blockSize * 5, blockSize);
            // Bottom row
            ctx.fillRect(x + blockSize, y + blockSize, blockSize * 3, blockSize);
        }

        function updateClouds() {
            for (const cloud of clouds) {
                cloud.x += cloud.speed;
                if (cloud.x > canvas.width + 80) {
                    cloud.x = -80;
                    cloud.y = 30 + Math.random() * 60;
                }
            }
        }

        function drawClouds() {
            for (const cloud of clouds) {
                drawCloud(cloud.x, cloud.y, cloud.size);
            }
        }

        function drawStage() {
            // Ground - 8-bit style with pixel blocks
            ctx.fillStyle = '#555555';
            ctx.fillRect(0, canvas.height - 60, canvas.width, 60);

            // Pixel texture on ground
            ctx.fillStyle = '#444444';
            for (let x = 0; x < canvas.width; x += 16) {
                for (let y = canvas.height - 60; y < canvas.height; y += 16) {
                    if ((x + y) % 32 === 0) {
                        ctx.fillRect(x, y, 8, 8);
                    }
                }
            }

            // Parking lines - blocky
            ctx.fillStyle = '#FFD700';
            for (let i = 0; i < canvas.width; i += 80) {
                ctx.fillRect(i + 20, canvas.height - 55, 6, 50);
            }
        }

        // Animate clouds even when game is not running
        function animateBackground() {
            if (!gameRunning) {
                // Sky gradient
                const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
                gradient.addColorStop(0, '#87CEEB');
                gradient.addColorStop(1, '#E0F4FF');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                updateClouds();
                drawClouds();
                drawStage();
                requestAnimationFrame(animateBackground);
            }
        }

        // Polyfill for roundRect
        if (!ctx.roundRect) {
            ctx.roundRect = function(x, y, w, h, r) {
                if (w < 2 * r) r = w / 2;
                if (h < 2 * r) r = h / 2;
                this.beginPath();
                this.moveTo(x + r, y);
                this.arcTo(x + w, y, x + w, y + h, r);
                this.arcTo(x + w, y + h, x, y + h, r);
                this.arcTo(x, y + h, x, y, r);
                this.arcTo(x, y, x + w, y, r);
                this.closePath();
                return this;
            };
        }

        let lastTime = 0;
        function gameLoop(timestamp) {
            if (!gameRunning) return;

            const deltaTime = timestamp - lastTime;
            lastTime = timestamp;

            // Sky gradient background
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, '#87CEEB');
            gradient.addColorStop(1, '#E0F4FF');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Update and draw parallax clouds
            updateClouds();
            drawClouds();

            // Draw ground
            drawStage();

            // Spawn items
            if (timestamp - lastSpawn > spawnRate) {
                spawnItem();
                lastSpawn = timestamp;
                // Increase difficulty - items spawn faster and fall faster over time
                if (spawnRate > 500) spawnRate -= 25;
                difficulty += 0.05;
            }

            // Update player position
            if (mouseX !== null) {
                player.x += (mouseX - player.x - player.width/2) * 0.15;
            } else {
                if (keys.left) player.x -= player.speed;
                if (keys.right) player.x += player.speed;
            }

            // Keep player in bounds
            player.x = Math.max(0, Math.min(canvas.width - player.width, player.x));

            // Update and draw items
            for (let i = items.length - 1; i >= 0; i--) {
                const item = items[i];
                item.y += item.speed;

                // Check collision with player
                if (item.y + 15 > player.y &&
                    item.y - 15 < player.y + player.height &&
                    item.x > player.x &&
                    item.x < player.x + player.width) {

                    // Create poof animation
                    createPoof(item.x, item.y, item.suggestion.text, item.suggestion.points === -1);

                    if (item.suggestion.points === -1) {
                        lives--;
                        // Flash screen red
                        ctx.fillStyle = 'rgba(255,0,0,0.4)';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                    } else {
                        score += item.suggestion.points;
                        collectedSuggestions.push(item.suggestion.emoji);
                    }
                    items.splice(i, 1);
                    updateUI();

                    if (lives <= 0) {
                        endGame();
                        return;
                    }
                    continue;
                }

                // Remove if off screen
                if (item.y > canvas.height) {
                    items.splice(i, 1);
                    continue;
                }

                drawItem(item);
            }

            // Draw player
            drawCart(player.x, player.y);

            // Draw poof animations
            updateAndDrawPoofs();

            requestAnimationFrame(gameLoop);
        }

        // Background animation will start when game starts

        // Booking modal functions
        function openBookingModal() {
            document.getElementById('bookingModal').classList.add('active');
            document.getElementById('gameScoreField').value = '';
        }

        function openBookingWithScore() {
            document.getElementById('bookingModal').classList.add('active');
            document.getElementById('gameScoreField').value = score;
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.remove('active');
            document.getElementById('formMessage').className = 'form-message';
            document.getElementById('formMessage').textContent = '';
        }

        // Close modal on overlay click
        document.getElementById('bookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBookingModal();
            }
        });

        // Form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('.submit-btn');
            const formMessage = document.getElementById('formMessage');

            submitBtn.disabled = true;
            submitBtn.textContent = 'SENDING...';

            fetch('booking-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formMessage.className = 'form-message success';
                    formMessage.textContent = 'Thanks! We\'ll be in touch soon.';
                    this.reset();
                } else {
                    formMessage.className = 'form-message error';
                    formMessage.textContent = data.message || 'Something went wrong. Please try again.';
                }
            })
            .catch(error => {
                formMessage.className = 'form-message error';
                formMessage.textContent = 'Something went wrong. Please try again.';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'SEND INQUIRY';
            });
        });
    </script>
</body>
</html>
