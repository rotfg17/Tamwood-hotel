import React, { useState } from "react";
import "../App.css";
import logo from "../assets/png/logo.png";

const Home = () => {
  const [modal, setModal] = useState(false);
  const [register, setRegister] = useState(false);

  function handleModal() {
    setModal(!modal);
  }

  return (
    <>
      {modal ? (
        <div className="modal">
          <div>
            <span>{!register ? "Login" : "Register"}</span>
            <form action="#">
              {!register ? null : (
                <div>
                  <label htmlFor="username">Username</label>
                  <input type="text" name="username" id="username" />
                </div>
              )}
              <div>
                <label htmlFor="email">Email</label>
                <input type="mail" name="email" id="email" />
              </div>
              <div>
                <label htmlFor="password">Password</label>
                <input type="password" name="password" id="password" />
              </div>
              <p>
                {!register ? (
                  <>
                    Don't have an account?{" "}
                    <a
                      href="#"
                      onClick={() => {
                        setRegister(true);
                      }}
                    >
                      Register here
                    </a>
                  </>
                ) : (
                  <>
                    Already have an account?{" "}
                    <a
                      href="#"
                      onClick={() => {
                        setRegister(false);
                      }}
                    >
                      Login here
                    </a>
                  </>
                )}
              </p>
              <div className="formButtons">
                <button onClick={handleModal}>Close</button>
                <input type="submit" />
              </div>
            </form>
          </div>
        </div>
      ) : null}

      <header className="header">
        <div>
          <img src={logo} alt="Tamwood Hotel Logo" />
          <div className="headerButtons">
            <a href="#" onClick={handleModal}>
              Login
            </a>
          </div>
        </div>
      </header>

      <main className="hero">
        <div>
          <h1>This is Tamwood Hotel</h1>
          <h2>Jun, Jisun, Robinson, Adrian</h2>
          <div className="heroButtons">
            <a href="#">Browse Rooms</a>
          </div>
        </div>
      </main>
    </>
  );
};

export default Home;
