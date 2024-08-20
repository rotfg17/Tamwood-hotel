import React from "react";
import "../App.css";
import logo from "../assets/png/logo.png";

const Home = () => {
  return (
    <>
      <header className="header">
        <div>
          <img src={logo} alt="Tamwood Hotel Logo" />
          <div className="headerButtons">
            <a href="#">Rooms</a>
            <a href="#">Login</a>
          </div>
        </div>
      </header>
    </>
  );
};

export default Home;
