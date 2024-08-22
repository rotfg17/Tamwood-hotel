import React, { useState } from "react";
import "../App.css";
import logo from "../assets/png/logo.png";

const Home = () => {
  const [modal, setModal] = useState(false);
  const [register, setRegister] = useState(false);
  const [formData, setFormData] = useState({
    username: "",
    email: "",
    password: "",
  });

  const handleInputChange = (event) => {
    const { name, value } = event.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    const url = register
      ? "http://localhost/Tamwood-hotel/api/register"
      : "http://localhost/Tamwood-hotel/api/login";

    const payload = register
      ? {
          name: formData.username,
          email: formData.email,
          password: formData.password,
          role: "c",
        }
      : { email: formData.email, password: formData.password };

    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (result) {
        // Navigate to the dashboard
        window.location.href = "/dashboard";
      } else {
        alert("Authentication failed. Please try again.");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    }
  };

  function handleModal() {
    setModal(!modal);
  }

  return (
    <>
      {modal ? (
        <div className="modal">
          <div>
            <span>{!register ? "Login" : "Register"}</span>
            <form onSubmit={handleSubmit}>
              {register && (
                <div>
                  <label htmlFor="username">Username</label>
                  <input
                    type="text"
                    name="username"
                    id="username"
                    value={formData.username}
                    onChange={handleInputChange}
                  />
                </div>
              )}
              <div>
                <label htmlFor="email">Email</label>
                <input
                  type="email"
                  name="email"
                  id="email"
                  value={formData.email}
                  onChange={handleInputChange}
                />
              </div>
              <div>
                <label htmlFor="password">Password</label>
                <input
                  type="password"
                  name="password"
                  id="password"
                  value={formData.password}
                  onChange={handleInputChange}
                />
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
                <button type="button" onClick={() => setModal(false)}>
                  Close
                </button>
                <input type="submit" value={register ? "Register" : "Login"} />
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
