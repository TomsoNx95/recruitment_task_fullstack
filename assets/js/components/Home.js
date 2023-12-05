// ./assets/js/components/Home.js

import React, { Component } from 'react';
import { Route, Redirect, Switch, Link } from 'react-router-dom';
import SetupCheck from "./SetupCheck";
import ExchangeRates from "./ExchangeRates";

class Home extends Component {

  render() {
    return (
      <div>
        <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
          <Link className={"navbar-brand"} to={"#"}> Telemedi Zadanko </Link>
          <div id="navbarText">
            <ul className="navbar-nav mr-auto">
              <li className="nav-item">
                <Link className={"nav-link"} to={"/setup-check"}> React Setup Check </Link>
              </li>
              <li className="nav-item">
                {/* Dodaj parametr date z aktualną datą */}
                <Link className={"nav-link"} to={`/exchange-rates?date=${getCurrentDate()}`}> Exchange Rates </Link>
              </li>
            </ul>
          </div>
        </nav>
        <Switch>
          <Redirect exact from="/" to="/setup-check" />
          <Route path="/setup-check" component={SetupCheck} />
          <Route path="/exchange-rates" component={ExchangeRates} />
        </Switch>
      </div>
    )
  }
}

const getCurrentDate = () => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

export default Home;