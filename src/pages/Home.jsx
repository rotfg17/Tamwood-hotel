import axios from "axios";
import React, { useState } from "react";
import "../App.css";
import logo from "../assets/png/logo.png";

const Home = () => {
  const [modal, setModal] = useState(false);
  const [register, setRegister] = useState(false);
  const [formData, setFormData] = useState({
    username: "",
    email: "",
    password_hash: "",
  });
  const [error, setError] = useState(""); // State for the error message
  const [success, setSuccess] = useState(""); // State for the success message

  const handleInputChange = (event) => {
    const { name, value } = event.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    const data = new FormData();
  
    // Reset error and success messages when submitting the form
    setError("");
    setSuccess("");
  
    // Ensure that email and password are provided
    if (!formData.email || !formData.password_hash) {
      setError("Email and password are required");
      return;
    }
  
    const url = register
      ? "http://localhost/Tamwood-hotel/api/register"
      : "http://localhost/Tamwood-hotel/api/login";
  
    const payload = register
      ? {
          name: formData.username,
          email: formData.email,
          password: formData.password_hash,
          role: "c",  // Role as 'customer'
        }
      : { email: formData.email, password: formData.password_hash };
  
    for (const key in payload) {
      data.append(key, payload[key]);
    }
  
    try {
      const response = await axios.post(url, data);
      const result = response.data;

      if (result.error) {
        // Asignar el mensaje de error a setError para mostrarlo en la interfaz
        setError(result.error);
      } else if (result.data && result.data.sid) {
        const sid = result.data.sid;
        const user = result.data.user;

        sessionStorage.setItem("sid", sid);
        sessionStorage.setItem("user", user);

        setSuccess("Login successful");
        // Redirigir al dashboard según el contexto
        if (register) {
          window.location.href = "/customer-dashboard";
        } else {
          window.location.href = "/dashboard";
        }
      } else {
        setError("Authentication failed. Please try again.");
        setFormData({ username: "", email: "", password_hash: "" });
      }
    } catch (error) {
      console.log("Caught an error:", error);
      setError("Email or password incorrect. Please try again.");
      setFormData({ username: "", email: "", password_hash: "" });
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
              {error && <div className="error-message">{error}</div>}
              {success && <div className="success-message">{success}</div>}
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
                  required
                />
              </div>
              <div>
                <label htmlFor="password">Password</label>
                <input
                  type="password"
                  name="password_hash"
                  id="password_hash"
                  value={formData.password_hash}
                  onChange={handleInputChange}
                  required
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
