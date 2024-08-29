import React from "react";
import "../App.css";
import Header from "./Header";
import { Link } from "react-router-dom";

const Home = () => {
  return (
    <>
      <Header />

      <main className="hero">
        <div>
          <h1>This is Tamwood Hotel</h1>
          <h2>Jun, Jisun, Robinson, Adrian</h2>
          <div className="heroButtons">
            <Link to="roomListing">Browse Rooms</Link>
          </div>
        </div>
      </main>
    </>
  );
};

export default Home;
