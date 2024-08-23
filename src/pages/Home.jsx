import React, { useState } from "react";
import axios from "axios";
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

  const handleLogin = async () => {
    const url = "http://localhost/Tamwood-hotel/api/login";
    const payload = {
      email: formData.email,
      password: formData.password,
    };

    console.log("Login payload being sent: ", payload);

    try {
      const response = await axios.post(url, payload);
      const result = response.data;

      if (result.sid) {
        sessionStorage.setItem("user_id", result.sid);
        window.location.href = "/dashboard";
      } else {
        alert(result.error || "Authentication failed. Please try again.");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    }
  };

  const handleRegister = async () => {
    const url = "http://localhost/Tamwood-hotel/api/register";
    const payload = {
      username: formData.username,
      email: formData.email,
      password_hash: formData.password, // Utiliza 'password_hash' como clave
      role: "c", // Asumiendo que estás usando un valor fijo para 'role'
    };

    console.log("Register payload being sent: ", payload);

    try {
      const response = await axios.post(url, payload);
      const result = response.data;

      if (result.sid) {
        sessionStorage.setItem("user_id", result.sid);
        window.location.href = "/dashboard";
      } else {
        alert(result.error || "Registration failed. Please try again.");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    }
  };

  const handleSubmit = (event) => {
    event.preventDefault();

    if (!formData.email || !formData.password) {
      alert("Email and password are required");
      return;
    }

    if (register) {
      handleRegister();
    } else {
      handleLogin();
    }
  };

  const handleModal = () => {
    setModal(!modal);
  };

  return (
    <>
      {modal && (
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
                    required
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
                  required
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
                  required
                />
              </div>
              <p>
                {!register ? (
                  <>
                    Don't have an account?{" "}
                    <a href="#" onClick={() => setRegister(true)}>
                      Register here
                    </a>
                  </>
                ) : (
                  <>
                    Already have an account?{" "}
                    <a href="#" onClick={() => setRegister(false)}>
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
      )}
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
