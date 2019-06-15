import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import GamesPlay from "./views/GamesPlay";

class App extends Component {
    render () {
        return (
            <div>
                <GamesPlay/>
            </div>
        )
    }
}

ReactDOM.render(<App />, document.getElementById('app'))
