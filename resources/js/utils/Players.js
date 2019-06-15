export function getPlayers() {
  return [{"_id":"N3DzqTfzuTYbzsv","displayName":"Bniess"},{"_id":"lMmGJ4r3KqjZa3G","displayName":"cguenier"},{"_id":"nMqBpbL8p0huS1T","displayName":"aroche"},{"_id":"B5AxOh8f3haroq1","displayName":"slepinard"}];
}

export function getPlayerFromID(ID) {
  const players = getPlayers();
  if (null === players) {
    return null;
  }

  const player = players.find(player => player._id === ID).displayName;
  if (player === null) {
    return null;
  }

  return player;
}

export function getGamePlayers(teams) {
  return {
    p1: getPlayerFromID(teams.a.players.p1),
    p2: getPlayerFromID(teams.a.players.p2),
    p3: getPlayerFromID(teams.b.players.p3),
    p4: getPlayerFromID(teams.b.players.p4)
  };
}
