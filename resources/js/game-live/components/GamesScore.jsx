import React, { Component } from "react";
import { getPlayerFromID } from "../../utils/Players";

class GamesScores extends Component {
  render() {
    const { game } = this.props;
    if (!game._id) {
      return "<p>Loading</p>";
    }
    return (
      <table className="table">
        <thead>
          <tr>
            <th scope="col" className="text-right">
              Team 1
            </th>
            <th scope="col" className="text-center">
              Score
            </th>
            <th scope="col">Team 2</th>
          </tr>
        </thead>
        <tbody>
          <tr key={game._id}>
            <td className="text-right">
              {getPlayerFromID(game.teams.a.players.p1)}
              <hr />
              {getPlayerFromID(game.teams.a.players.p2)}
            </td>
            <td className="align-middle text-center scores">
              {game.teams.a.score} - {game.teams.b.score}
            </td>
            <td>
              {getPlayerFromID(game.teams.b.players.p3)}
              <hr />
              {getPlayerFromID(game.teams.b.players.p4)}
            </td>
          </tr>
        </tbody>
      </table>
    );
  }
}

export default GamesScores;
