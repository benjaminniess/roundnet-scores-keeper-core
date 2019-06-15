import React, { Component } from "react";
import { Link } from "react-router-dom";

class LinkButton extends Component {
  render() {
    const { to, label } = this.props;

    return (
      <Link to={to}>
        <button className="btn btn-primary btn-lg">{label}</button>
      </Link>
    );
  }
}

export default LinkButton;
