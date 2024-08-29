import axios from "axios";
import React, { useState } from "react";
import logo from "../assets/png/logo.png";
import { Link } from "react-router-dom";

const Header = () => {
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
          role: "c",
        }
      : { email: formData.email, password: formData.password_hash };

    for (const key in payload) {
      data.append(key, payload[key]);
    }

    try {
      const response = await axios.post(url, data);
      const result = await response.data;

      if (result.data && result.data.sid) {
        const sid = result.data.sid;
        sessionStorage.setItem("sid", sid);
        if (register) {
          setSuccess("Registration successful! You can now log in.");
          setTimeout(() => {
            window.location.href = "/client-dashboard";
          }, 1000);
        } else {
          setSuccess("Login successful");
          // Navigate to the dashboard on successful login
          window.location.href = "/dashboard";
        }
      } else {
        setError(result.error || "Authentication failed. Please try again.");
        // Clear the form in case of error
        setFormData({ username: "", email: "", password_hash: "" });
      }
    } catch (error) {
      setError(
        "Invalid email or password. Please check your credentials and try again."
      );
      // Clear the form in case of error
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
          <Link to="/">
            <img src={logo} alt="Tamwood Hotel Logo" />
          </Link>
          <div className="headerButtons">
            <a href="#" onClick={handleModal}>
              Login
            </a>
          </div>
        </div>
      </header>
    </>
  );
};

export default Header;
