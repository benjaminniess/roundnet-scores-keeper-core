import React, { Component } from "react";
import { getPointsTypes } from "../../../utils/Api";
import Stopwatch from "../StopWatch";
import ActionButtons from "../ActionButtons";
import Cookies from 'js-cookie';

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

    if (game.id === undefined) {
      return "";
    }

    return (
      <main role="main" className="flex-shrink-0">
        <div className="container">
          <div className="row">
            <div className="col timer_block bg-warning text-center">
              <Stopwatch start={game.start_date} />
            </div>
          </div>
          <div className="row">
            <div className="col p-0 text-right team">Team 1</div>
            <div className="col-6 align-middle text-center scores p-0">
              {game.score.team1} - {game.score.team2}
            </div>
            <div className="col p-0 team">Team 2</div>
          </div>

          <div className="row">
            <div className="col text-left p-0">
              <p className="player">
                  { game.teams.a.players.p1.name }
                {game.teams.a.players.p1.id === game.current_server && (
                  <span className="service">[S]</span>
                )}
              </p>

              <ActionButtons player="p1" onUpdate={this.handleUpdate} />
              <hr />

              <p className="player">
				  { game.teams.a.players.p2.name }
                {game.teams.a.players.p2.id === game.current_server && (
                  <span className="service">[S]</span>
                )}
              </p>
              <ActionButtons player="p2" onUpdate={this.handleUpdate} />
            </div>
            <div className="col text-right p-0">
              <p className="player">
				  { game.teams.b.players.p3.name }
                {game.teams.b.players.p3.id === game.current_server && (
                  <span className="service">[S]</span>
                )}
              </p>

              <ActionButtons player="p3" onUpdate={this.handleUpdate} />
              <hr />
              <p className="player">
				  { game.teams.b.players.p4.name }
                {game.teams.b.players.p4.id === game.current_server && (
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
  		let game, pointsTypes;

        fetch("/api/games/live", {
          headers: {
              'Accept': 'application/json',
              'Content-Type': ' application/json',
              'Authorization': 'Bearer ' + Cookies.get('user_access_token')
          }
            })
          .then(res => res.json())
          .then(
              (result) => {
                  if ( result.success === true ) {
                  	game = result.data;
					  fetch("/api/games/actions", {
						  headers: {
							  'Accept': 'application/json',
							  'Content-Type': ' application/json',
							  'Authorization': 'Bearer ' + Cookies.get('user_access_token')
						  }
					  })
						  .then(res => res.json())
						  .then(
							  (result) => {
								  if ( result.success === true ) {
									  pointsTypes = result.data;
									  this.setState({ game, pointsTypes });
								  }
							  },
							  (error) => {
								  console.log(error);
							  }
						  )
                  }
              },
              (error) => {
                  console.log(error);
              }
          )
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
    const { currentPlayer, buttonsTypes, game } = this.state;
	let player_id;

    if ( 'p1' === currentPlayer || 'p2' === currentPlayer ) {
    	player_id = game.teams.a.players[currentPlayer].id;
	} else {
		player_id = game.teams.b.players[currentPlayer].id;
	}

	  fetch("/api/games/" + game.id + "/points", {
			headers: {
			  'Accept': 'application/json',
			  'Content-Type': ' application/json',
			  'Authorization': 'Bearer ' + Cookies.get('user_access_token')
			},
			method: 'POST',
		    body : JSON.stringify({
				'player_id' : player_id,
				'action_type' : 1
		   })
	  })
		  .then(res => res.json())
		  .then(
			  (result) => {
			  	console.log(result, 'ok');
				  if ( result.success === true ) {
					  this.setState({ game: result.data });
				  }
			  },
			  (error) => {
				  console.log(error);
			  }
		  )

    if (null === game) {
      this.props.history.push("/games");
    } else {
      this.setState({ game, buttonsTypes: null });
    }
  }
}

export default GamesPlay;
