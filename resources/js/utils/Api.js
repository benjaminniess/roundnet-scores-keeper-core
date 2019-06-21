/**
 * Retrieve the current ongoing game from local storage
 */
export function getCurrentGame() {
    return {"teams":{"a":{"players":{"p1":"lMmGJ4r3KqjZa3G","p2":"nMqBpbL8p0huS1T"},"score":0},"b":{"players":{"p3":"B5AxOh8f3haroq1","p4":"N3DzqTfzuTYbzsv"},"score":0}},"enableTurns":"1","currentServer":"p1","pointsToWin":"5","_id":"IgTHbLqQtZJ3avI","date":1560613796030,"history":[]};
}

/**
 * Save a given game as the current game in local storage
 *
 * @param {object} game
 */
export function updateCurrentGame(game) {
    localStorage.setItem("currentGame", JSON.stringify(game));
}

/**
 * Set a given game object as the current ongoing game
 *
 * @param {object} game
 */
export function startGame(game) {
    game._id = generateID();
    game.date = Date.now();
    game.history = [];
    updateCurrentGame(game);
}

/**
 * Ends the given game and save it to the local storage history
 *
 * @param {object} game
 */
export function closeGame(game) {
    let games = getGames();
    game.duration = Math.round((Date.now() - game.date) / 1000);
    games.push(game);
    localStorage.setItem("games", JSON.stringify(games));
    localStorage.removeItem("currentGame");
}


/**
 * Save a new action to a given game and manage points depending on action type
 *
 * @param {object} game: The game object
 * @param {string} player: p1, p2, p3 or p4
 * @param {string} type: The point type key
 * @param {string} category: negative, positive or neutral
 */
export function addAction(game, player, type, category) {
    const team = player === "p1" || player === "p2" ? "a" : "b";
    const { currentServer } = game;

    if (
        ("a" === team && "positive" === category) ||
        ("b" === team && "negative" === category)
    ) {
        game.teams.a.score++;
        if ("p3" === currentServer) {
            game.currentServer = "p1";
        } else if ("p4" === currentServer) {
            game.currentServer = "p2";
        }
    } else if (
        ("b" === team && "positive" === category) ||
        ("a" === team && "negative" === category)
    ) {
        game.teams.b.score++;

        if ("p1" === currentServer) {
            game.currentServer = "p4";
        } else if ("p2" === currentServer) {
            game.currentServer = "p3";
        }
    }
    game.history.push({
        type: type,
        time: Date.now(),
        "score-a": game.teams.a.score,
        "score-b": game.teams.b.score,
        server: game.currentServer,
        actionPlayer: player,
        category
    });

    if (
        game.pointsToWin - game.teams.a.score < 1 ||
        game.pointsToWin - game.teams.b.score < 1
    ) {
        closeGame(game);
        return null;
    }

    updateCurrentGame(game);

    return game;
}


/**
 * Get all points types ordered by categories
 * negative: error points that give a point to the opponent team
 * positive: success points that give a point to the player's team
 * neutral: don't add points. just for stats
 */
export function getPointsTypes() {
    return {
        negative: [
            {
                key: "miss",
                name: "Miss the point",
                color: "#C33825"
            },
            {
                key: "serve-rim",
                name: "Rimmer on service",
                color: "#C33825"
            },
            {
                key: "serve-faults",
                name: "2 faults on service",
                color: "#D65400"
            },
            {
                key: "rim",
                name: "Rimmer",
                color: "#E87E04"
            }
        ],
        positive: [
            {
                key: "point",
                name: "Point",
                color: "#003840"
            },
            {
                key: "smash",
                name: "Smash",
                color: "#005A5B"
            },
            {
                key: "ace",
                name: "Ace",
                color: "#007369"
            },
            {
                key: "drop",
                name: "Drop",
                color: "#008C72"
            },
            {
                key: "pocket",
                name: "Pocket",
                color: "#02A676"
            },
            {
                key: "rollup",
                name: "Roll-up",
                color: "#03D195"
            }
        ],
        neutral: [
            {
                key: "replay",
                name: "Re-play rally",
                color: "#FFCE56"
            },
            {
                key: "serve-fault",
                name: "Second service",
                color: "#FFCE56"
            },
            {
                key: "irritation-gesture",
                name: "Irritation gesture",
                color: "#FFCE56"
            }
        ]
    };
}

function getToken() {
    return document.cookie;
}
