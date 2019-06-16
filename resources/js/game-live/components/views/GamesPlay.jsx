import React, { Component } from "react";
import { getCurrentGame, addAction, getPointsTypes } from "../../../utils/Games";
import Stopwatch from "../StopWatch";
import { getPlayerFromID } from "../../../utils/Players";
import ActionButtons from "../ActionButtons";

class GamesPlay extends Component {
  constructor(props) {
    super(props);

    this.state = {
      game: {},
      pointsTypes: [],
      buttonsTypes: null,
      currentPlayer: null
    };

    this.handleUpdate = this.handleUpdate.bind(this);
  }

  render() {
    const { game, pointsTypes, buttonsTypes } = this.state;

    if (game._id === undefined) {
      return "";
    }

    return (
      <main role="main" className="flex-shrink-0">
        <div className="container">
          <div className="row">
            <div className="col timer_block bg-warning text-center">
              <Stopwatch start={game.date} />
            </div>
          </div>
          <div className="row">
            <div className="col p-0 text-right team">Team 1</div>
            <div className="col-6 align-middle text-center scores p-0">
              {game.teams.a.score} - {game.teams.b.score}
            </div>
            <div className="col p-0 team">Team 2</div>
          </div>

          <div className="row">
            <div className="col text-left p-0">
              <p className="player">
                {getPlayerFromID(game.teams.a.players.p1)}
                {"p1" === game.currentServer && (
                  <span className="service">[S]</span>
                )}
              </p>

              <ActionButtons player="p1" onUpdate={this.handleUpdate} />
              <hr />

              <p className="player">
                {getPlayerFromID(game.teams.a.players.p2)}
                {"p2" === game.currentServer && (
                  <span className="service">[S]</span>
                )}
              </p>
              <ActionButtons player="p2" onUpdate={this.handleUpdate} />
            </div>
            <div className="col text-right p-0">
              <p className="player">
                {getPlayerFromID(game.teams.b.players.p3)}
                {"p3" === game.currentServer && (
                  <span className="service">[S]</span>
                )}
              </p>

              <ActionButtons player="p3" onUpdate={this.handleUpdate} />
              <hr />
              <p className="player">
                {getPlayerFromID(game.teams.b.players.p4)}
                {"p4" === game.currentServer && (
                  <span className="service">[S]</span>
                )}
              </p>

              <ActionButtons player="p4" onUpdate={this.handleUpdate} />
            </div>
          </div>
          {buttonsTypes === "positive" && (
            <div className="row actions-list">
              <div className="col text-center">
                {pointsTypes.positive.map((type, i) => {
                  return (
                    <button
                      key={i}
                      className="btn btn-primary btn-lg"
                      onClick={() => this.handleAction(type.key)}
                    >
                      {type.name}
                    </button>
                  );
                })}
              </div>
            </div>
          )}

          {buttonsTypes === "negative" && (
            <div className="row actions-list">
              <div className="col text-center">
                {pointsTypes.negative.map((type, i) => {
                  return (
                    <button
                      key={i}
                      className="btn btn-danger btn-lg"
                      onClick={() => this.handleAction(type.key)}
                    >
                      {type.name}
                    </button>
                  );
                })}
              </div>
            </div>
          )}

          {buttonsTypes === "neutral" && (
            <div className="row actions-list">
              <div className="col text-center">
                {pointsTypes.neutral.map((type, i) => {
                  return (
                    <button
                      key={i}
                      className="btn btn-warning btn-lg"
                      onClick={() => this.handleAction(type.key)}
                    >
                      {type.name}
                    </button>
                  );
                })}
              </div>
            </div>
          )}
        </div>
      </main>
    );
  }

  componentDidMount() {
    const game = getCurrentGame();
    const pointsTypes = getPointsTypes();
    this.setState({ game, pointsTypes });

    if (null === game) {
      this.props.history.push("/");
    }
  }

  handleUpdate(currentPlayer, buttonsTypes) {
    if (
      this.state.buttonsTypes === buttonsTypes &&
      currentPlayer === this.state.currentPlayer
    ) {
      buttonsTypes = null;
    }
    this.setState({ buttonsTypes, currentPlayer });
  }

  handleAction(type) {
    const { currentPlayer, buttonsTypes } = this.state;

    let game = addAction(this.state.game, currentPlayer, type, buttonsTypes);
    if (null === game) {
      this.props.history.push("/games");
    } else {
      this.setState({ game, buttonsTypes: null });
    }
  }
}

export default GamesPlay;
