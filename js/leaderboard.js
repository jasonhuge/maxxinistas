// Leaderboard functionality
const Leaderboard = {
    data: [],
    minScoreForEntry: 0,

    async fetch() {
        try {
            const response = await fetch('leaderboard-handler.php');
            const result = await response.json();
            if (result.success) {
                this.data = result.leaderboard;
                this.minScoreForEntry = this.data.length < 10 ? 0 :
                    (this.data[this.data.length - 1]?.score || 0);
            }
        } catch (e) {
            console.error('Failed to fetch leaderboard:', e);
        }
    },

    async submit(name, score, level) {
        try {
            const response = await fetch('leaderboard-handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, score, level })
            });
            const result = await response.json();
            if (result.success) {
                this.data = result.leaderboard;
                return result.rank;
            }
        } catch (e) {
            console.error('Failed to submit score:', e);
        }
        return -1;
    },

    qualifiesForLeaderboard(score) {
        return this.data.length < 10 || score > this.minScoreForEntry;
    },

    render(container, highlightScore = null) {
        if (this.data.length === 0) {
            container.innerHTML = '<p>No scores yet. Be the first!</p>';
            return;
        }

        let html = '<div class="leaderboard-list">';
        this.data.forEach((entry, i) => {
            const isHighlighted = highlightScore !== null &&
                entry.score === highlightScore;
            const highlightClass = isHighlighted ? 'highlight' : '';
            html += `<div class="leaderboard-entry ${highlightClass}">
                <span class="rank">${i + 1}.</span>
                <span class="name">${entry.name}</span>
                <span class="score">${entry.score}</span>
                <span class="level">L${entry.level}</span>
            </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
    }
};

// Name entry component
const NameEntry = {
    onSubmit: null,

    show(container, onSubmit) {
        this.onSubmit = onSubmit;

        container.innerHTML = `
            <div class="name-entry">
                <p>NEW HIGH SCORE!</p>
                <p>Enter your initials:</p>
                <input type="text" class="name-input" maxlength="3" placeholder="AAA" autocomplete="off" autocapitalize="characters">
                <button class="pixel-btn submit-name-btn">OK</button>
            </div>
        `;

        const input = container.querySelector('.name-input');
        const submitBtn = container.querySelector('.submit-name-btn');

        // Auto-uppercase and filter to letters only
        input.addEventListener('input', () => {
            input.value = input.value.toUpperCase().replace(/[^A-Z]/g, '');
        });

        // Submit on enter or button click
        const submit = () => {
            let name = input.value.toUpperCase().replace(/[^A-Z]/g, '');
            if (name.length === 0) name = 'AAA';
            while (name.length < 3) name += 'A';
            if (this.onSubmit) this.onSubmit(name.substring(0, 3));
        };

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submit();
            }
        });

        submitBtn.addEventListener('click', submit);

        // Focus the input
        setTimeout(() => input.focus(), 100);
    },

    hide() {
        // Nothing to clean up
    }
};

// Fetch leaderboard on page load
Leaderboard.fetch();
