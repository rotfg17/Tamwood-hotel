import React from "react";
import "../App.css";
import Header from "./Header";
import { Link } from "react-router-dom";

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
          role: "c", // Role as 'customer'
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
        const user = result.data.user; // Asumiendo que el backend devuelve el usuario autenticado

        sessionStorage.setItem("sid", sid);
        sessionStorage.setItem("user", JSON.stringify(user));

        setSuccess("Login successful");

        // Redirigir al dashboard basado en el rol del usuario
        if (user.role === "admin") {
          window.location.href = "/dashboard";
        } else {
          window.location.href = "/customer-dashboard";
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
