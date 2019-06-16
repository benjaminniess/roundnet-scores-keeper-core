import React, { Component } from "react";

class ActionButtons extends Component {
  render() {
    const { player, onUpdate } = this.props;

    return (
      <div className="action-buttons">
        <button
          className="btn btn-danger btn-lg"
          onClick={() => onUpdate(player, "negative")}
        >
          -
        </button>
        <button
          className="btn btn-warning btn-lg"
          onClick={() => onUpdate(player, "neutral")}
        >
          ?
        </button>
        <button
          className="btn btn-primary btn-lg"
          onClick={() => onUpdate(player, "positive")}
        >
          +
        </button>
      </div>
    );
  }
}

export default ActionButtons;
