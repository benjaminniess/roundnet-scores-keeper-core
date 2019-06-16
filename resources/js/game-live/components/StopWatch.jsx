import React, { Component } from "react";

const formattedSeconds = sec =>
  Math.floor(sec / 60) + ":" + ("0" + (sec % 60)).slice(-2);

class Stopwatch extends Component {
  constructor(props) {
    super(props);
    const { start } = props;
    this.state = {
      secondsElapsed: Math.round((Date.now() - start) / 1000),
      lastClearedIncrementer: null
    };

    this.incrementer = null;

    this.handleStartClick();
  }

  handleStartClick() {
    this.incrementer = setInterval(
      () =>
        this.setState({
          secondsElapsed: this.state.secondsElapsed + 1
        }),
      1000
    );
  }

  handleStopClick() {
    clearInterval(this.incrementer);
    this.setState({
      lastClearedIncrementer: this.incrementer
    });
  }

  handleLabClick() {
    this.setState({
      laps: this.state.laps.concat([this.state.secondsElapsed])
    });
  }

  render() {
    return (
      <div className="stopwatch">
        <h1 className="stopwatch-timer">
          {formattedSeconds(this.state.secondsElapsed)}
        </h1>
      </div>
    );
  }
}

export default Stopwatch;
