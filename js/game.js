// DOM Elements
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const startScreen = document.getElementById('startScreen');
const gameOverScreen = document.getElementById('gameOverScreen');
const scoreDisplay = document.getElementById('score');
const levelDisplay = document.getElementById('level');
const staminaFill = document.getElementById('staminaFill');
const finalScoreDisplay = document.getElementById('finalScore');
const finalLevelDisplay = document.getElementById('finalLevel');
const nameEntrySection = document.getElementById('nameEntrySection');
const leaderboardSection = document.getElementById('leaderboardSection');
const gameArea = document.getElementById('gameArea');
const instructionsScreen = document.getElementById('instructionsScreen');
const gameContainer = document.getElementById('gameContainer');

// Game state
let gameRunning = false;
let score = 0;
let stamina = 100;
let items = [];
let collectedSuggestions = [];
let poofs = [];
let splats = [];
let lastSpawn = 0;
let cartShake = 0;
let currentLevel = 1;
let levelUpTimer = 0;

// Level configuration: score threshold, spawn rate, item speed, bomb chance
const levels = [
    { score: 0,    spawnRate: 1800, itemSpeed: 2.0, badChance: 0.20 },
    { score: 100,  spawnRate: 1600, itemSpeed: 2.3, badChance: 0.25 },
    { score: 250,  spawnRate: 1400, itemSpeed: 2.6, badChance: 0.30 },
    { score: 450,  spawnRate: 1200, itemSpeed: 3.0, badChance: 0.35 },
    { score: 750,  spawnRate: 1000, itemSpeed: 3.4, badChance: 0.40 },
    { score: 1150, spawnRate: 800,  itemSpeed: 3.8, badChance: 0.45 },
    { score: 1650, spawnRate: 650,  itemSpeed: 4.2, badChance: 0.50 },
];

function getLevelConfig() {
    return levels[Math.min(currentLevel - 1, levels.length - 1)];
}

function checkLevelUp() {
    // Find what level we should be at based on score
    let newLevel = 1;
    for (let i = 0; i < levels.length; i++) {
        if (score >= levels[i].score) {
            newLevel = i + 1;
        }
    }
    // If we leveled up, trigger animation
    if (newLevel > currentLevel) {
        currentLevel = newLevel;
        levelUpTimer = 120;
    }
}

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
    { text: "Hot Dog", emoji: "🌭", points: 10 },
    { text: "Pregnant", emoji: "🤰", points: 15 },
    { text: "Poop", emoji: "💩", points: 10 },
];

const badSuggestions = [
    { text: "Bomb", emoji: "💣", points: -1 },
];

// Input handling
let keys = { left: false, right: false };

document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
        keys.left = true;
        if (gameRunning) e.preventDefault();
    }
    if (e.key === 'ArrowRight') {
        keys.right = true;
        if (gameRunning) e.preventDefault();
    }
    if (e.key === ' ' && !gameRunning && !startScreen.classList.contains('hidden')) startGame();
});

document.addEventListener('keyup', (e) => {
    if (e.key === 'ArrowLeft') keys.left = false;
    if (e.key === 'ArrowRight') keys.right = false;
});

// Mobile controls
const leftBtn = document.getElementById('leftBtn');
const rightBtn = document.getElementById('rightBtn');

leftBtn.addEventListener('touchstart', (e) => { e.preventDefault(); keys.left = true; });
leftBtn.addEventListener('touchend', (e) => { e.preventDefault(); keys.left = false; });
rightBtn.addEventListener('touchstart', (e) => { e.preventDefault(); keys.right = true; });
rightBtn.addEventListener('touchend', (e) => { e.preventDefault(); keys.right = false; });

// Game functions
function startGame() {
    // Fade out and show instructions
    startScreen.classList.add('fade-out');

    setTimeout(() => {
        // Remove splash mode after fade completes
        gameContainer.classList.remove('splash-mode');
        startScreen.classList.add('hidden');
        startScreen.classList.remove('fade-out');
        instructionsScreen.classList.remove('hidden');
    }, 500);
}

function beginGame() {
    score = 0;
    stamina = 100;
    items = [];
    poofs = [];
    splats = [];
    collectedSuggestions = [];
    currentLevel = 1;
    levelUpTimer = 0;
    lastSpawn = 0;
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
    NameEntry.hide();
    score = 0;
    stamina = 100;
    items = [];
    poofs = [];
    splats = [];
    collectedSuggestions = [];
    currentLevel = 1;
    levelUpTimer = 0;
    lastSpawn = 0;
    player.x = 250;

    gameOverScreen.classList.add('hidden');
    gameRunning = true;
    updateUI();
    requestAnimationFrame(gameLoop);
}

function endGame() {
    gameRunning = false;
    finalScoreDisplay.textContent = score;
    finalLevelDisplay.textContent = currentLevel;

    // Clear previous state
    nameEntrySection.innerHTML = '';
    leaderboardSection.innerHTML = '';

    gameOverScreen.classList.remove('hidden');

    // Fetch latest leaderboard and show name entry if qualified
    Leaderboard.fetch().then(() => {
        if (Leaderboard.qualifiesForLeaderboard(score)) {
            NameEntry.show(nameEntrySection, (name) => {
                NameEntry.hide();
                nameEntrySection.innerHTML = '<p>Saving...</p>';
                Leaderboard.submit(name, score, currentLevel).then(() => {
                    nameEntrySection.innerHTML = '';
                    Leaderboard.render(leaderboardSection, score);
                }).catch(() => {
                    nameEntrySection.innerHTML = '';
                    Leaderboard.render(leaderboardSection);
                });
            });
        } else {
            Leaderboard.render(leaderboardSection);
        }
    }).catch(() => {
        // Leaderboard unavailable, just show the game over screen
        leaderboardSection.innerHTML = '<p>Leaderboard unavailable</p>';
    });
}

function updateUI() {
    scoreDisplay.textContent = score;
    levelDisplay.textContent = currentLevel;
    staminaFill.style.width = stamina + '%';
    // Change color based on stamina level
    if (stamina > 50) {
        staminaFill.style.background = '#4CAF50';
    } else if (stamina > 25) {
        staminaFill.style.background = '#FFD700';
    } else {
        staminaFill.style.background = '#CC0000';
    }
}

function spawnItem() {
    const config = getLevelConfig();
    let suggestion;

    if (Math.random() < config.badChance) {
        suggestion = badSuggestions[Math.floor(Math.random() * badSuggestions.length)];
    } else {
        suggestion = goodSuggestions[Math.floor(Math.random() * goodSuggestions.length)];
    }

    // Add some random variation to speed (+/- 10%)
    const speedVariation = config.itemSpeed * (0.9 + Math.random() * 0.2);

    items.push({
        x: Math.random() * (canvas.width - 120) + 60,
        y: -40,
        suggestion: suggestion,
        speed: speedVariation,
        width: 0
    });
}

function drawCart(x, y) {
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

    if (isBad) {
        cartShake = 15;
    }
}

function updateAndDrawPoofs() {
    for (let i = poofs.length - 1; i >= 0; i--) {
        const poof = poofs[i];

        poof.life -= 0.02;
        poof.y -= 1.5;
        poof.opacity = poof.life;

        if (poof.life <= 0) {
            poofs.splice(i, 1);
            continue;
        }

        ctx.save();
        ctx.globalAlpha = poof.opacity;

        const particleCount = 8;
        const burstRadius = (1 - poof.life) * 30;
        ctx.fillStyle = poof.isBad ? '#FF4444' : '#FFD700';
        for (let j = 0; j < particleCount; j++) {
            const angle = (j / particleCount) * Math.PI * 2;
            const px = poof.x + Math.cos(angle) * burstRadius;
            const py = poof.y + Math.sin(angle) * burstRadius;
            ctx.fillRect(px - 3, py - 3, 6, 6);
        }

        ctx.font = 'bold 18px Courier New';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 4;
        ctx.strokeText(poof.text, poof.x, poof.y);

        ctx.fillStyle = poof.isBad ? '#FF4444' : '#FFFFFF';
        ctx.fillText(poof.text, poof.x, poof.y);

        ctx.restore();
    }
}

function createSplat(x) {
    // Create multiple particles bursting up from stage
    for (let i = 0; i < 8; i++) {
        splats.push({
            x: x,
            y: canvas.height - 65,
            vx: (Math.random() - 0.5) * 6,
            vy: -Math.random() * 8 - 2,
            life: 1.0
        });
    }
}

function updateAndDrawSplats() {
    for (let i = splats.length - 1; i >= 0; i--) {
        const p = splats[i];

        p.life -= 0.03;
        p.x += p.vx;
        p.y += p.vy;
        p.vy += 0.4; // gravity

        if (p.life <= 0) {
            splats.splice(i, 1);
            continue;
        }

        ctx.save();
        ctx.globalAlpha = p.life;
        ctx.fillStyle = '#8B4513';
        ctx.beginPath();
        ctx.arc(p.x, p.y, 3, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }
}

function drawItem(item) {
    const isBad = item.suggestion.points === -1;
    const size = 50;
    item.width = size;

    ctx.fillStyle = isBad ? '#FF4444' : '#FFFFFF';
    ctx.fillRect(item.x - size/2, item.y - size/2, size, size);

    ctx.strokeStyle = isBad ? '#CC0000' : '#FFD700';
    ctx.lineWidth = 4;
    ctx.strokeRect(item.x - size/2, item.y - size/2, size, size);

    ctx.font = '32px serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(item.suggestion.emoji, item.x, item.y);
}

function drawCloud(x, y, size) {
    ctx.fillStyle = '#FFFFFF';
    const blockSize = 8 * size;
    ctx.fillRect(x + blockSize, y - blockSize, blockSize * 3, blockSize);
    ctx.fillRect(x, y, blockSize * 5, blockSize);
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

function drawLevelUp() {
    if (levelUpTimer <= 0) return;

    levelUpTimer--;
    const alpha = Math.min(1, levelUpTimer / 30);
    const scale = 1 + (120 - levelUpTimer) * 0.005;
    const isMaxLevel = currentLevel >= levels.length;
    const text = isMaxLevel ? "CART BLANCHE!" : `LEVEL ${currentLevel}!`;

    ctx.save();
    ctx.globalAlpha = alpha;
    ctx.font = `bold ${48 * scale}px Courier New`;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 6;
    ctx.strokeText(text, canvas.width / 2, canvas.height / 2 - 50);

    ctx.fillStyle = isMaxLevel ? '#FF00FF' : '#FFD700';
    ctx.fillText(text, canvas.width / 2, canvas.height / 2 - 50);
    ctx.restore();
}

function drawStage() {
    ctx.fillStyle = '#555555';
    ctx.fillRect(0, canvas.height - 60, canvas.width, 60);

    ctx.fillStyle = '#444444';
    for (let x = 0; x < canvas.width; x += 16) {
        for (let y = canvas.height - 60; y < canvas.height; y += 16) {
            if ((x + y) % 32 === 0) {
                ctx.fillRect(x, y, 8, 8);
            }
        }
    }

    ctx.fillStyle = '#FFD700';
    for (let i = 0; i < canvas.width; i += 80) {
        ctx.fillRect(i + 20, canvas.height - 55, 6, 50);
    }
}

function animateBackground() {
    if (!gameRunning) {
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

    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    gradient.addColorStop(0, '#87CEEB');
    gradient.addColorStop(1, '#E0F4FF');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    updateClouds();
    drawClouds();
    drawStage();

    const config = getLevelConfig();
    if (timestamp - lastSpawn > config.spawnRate) {
        spawnItem();
        lastSpawn = timestamp;
    }

    if (keys.left) player.x -= player.speed;
    if (keys.right) player.x += player.speed;

    player.x = Math.max(0, Math.min(canvas.width - player.width, player.x));

    for (let i = items.length - 1; i >= 0; i--) {
        const item = items[i];
        item.y += item.speed;

        if (item.y + 15 > player.y &&
            item.y - 15 < player.y + player.height &&
            item.x > player.x &&
            item.x < player.x + player.width) {

            const poofText = item.suggestion.points === -1 ? "BOOM!" : item.suggestion.text;
            createPoof(item.x, item.y, poofText, item.suggestion.points === -1);

            if (item.suggestion.points === -1) {
                stamina -= 25; // Bomb = big stamina hit
                ctx.fillStyle = 'rgba(255,0,0,0.4)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            } else {
                score += item.suggestion.points;
                collectedSuggestions.push(item.suggestion.emoji);
                checkLevelUp();
            }
            items.splice(i, 1);
            updateUI();

            if (stamina <= 0) {
                endGame();
                return;
            }
            continue;
        }

        // Good suggestions hit the stage floor
        if (item.suggestion.points !== -1 && item.y > canvas.height - 60) {
            // Missed a good suggestion = small stamina drain + splat
            createSplat(item.x);
            stamina -= 5;
            updateUI();
            if (stamina <= 0) {
                endGame();
                return;
            }
            items.splice(i, 1);
            continue;
        }

        // Bombs fall off the screen
        if (item.y > canvas.height) {
            items.splice(i, 1);
            continue;
        }

        drawItem(item);
    }

    drawCart(player.x, player.y);
    updateAndDrawPoofs();
    updateAndDrawSplats();
    drawLevelUp();
    requestAnimationFrame(gameLoop);
}
