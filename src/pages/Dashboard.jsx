import {
  faBars,
  faBed,
  faBell,
  faCalendarCheck,
  faClock,
  faCog,
  faComments,
  faConciergeBell,
  faFileAlt,
  faUser,
  faUserCircle,
  faWallet,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useState } from "react";
import { Link, Outlet, useNavigate } from "react-router-dom";
import "../App.css"; // Asegúrate de importar el archivo CSS correcto
import { useSession } from "../hooks/store/user";

const Dashboard = () => {
  const { user, sid } = useSession();
  console.log(user, sid);

  const [isSettingsMenuVisible, setSettingsMenuVisible] = useState(false);
  const [isNotificationsMenuVisible, setNotificationsMenuVisible] =
    useState(false);
  const [isProfileMenuVisible, setProfileMenuVisible] = useState(false);

  const navigate = useNavigate(); // Hook para redireccionar al usuario

  const handleHamburgerClick = () => {
    const sidebar = document.querySelector(".dashboard");
    const topBar = document.querySelector(".top-bar");
    const isCollapsed = sidebar.classList.toggle("collapsed");

    // Ajustar la barra superior con base en el estado del sidebar
    if (isCollapsed) {
      topBar.style.left = "100px"; // Ajuste al ancho de la barra lateral colapsada
      topBar.style.width = "calc(100% - 120px)"; // Ajustar ancho
    } else {
      topBar.style.left = "230px"; // Ancho original de la barra lateral
      topBar.style.width = "calc(100% - 250px)"; // Ajustar ancho
    }
  };

  const handleSettingsClick = () => {
    setSettingsMenuVisible(!isSettingsMenuVisible);
    setNotificationsMenuVisible(false);
    setProfileMenuVisible(false);
  };

  const handleNotificationsClick = () => {
    setNotificationsMenuVisible(!isNotificationsMenuVisible);
    setSettingsMenuVisible(false);
    setProfileMenuVisible(false);
  };

  const handleProfileClick = () => {
    setProfileMenuVisible(!isProfileMenuVisible);
    setSettingsMenuVisible(false);
    setNotificationsMenuVisible(false);
  };

  const handleLogout = () => {
    // Aquí puedes limpiar cualquier token de autenticación o datos de sesión
    sessionStorage.clear(); // Ejemplo para limpiar sessionStorage, ajusta según tu implementación
    navigate("/"); // Redirige a la página de inicio de sesión
  };

  return (
    <div className="dashboard-container">
      <div className="top-bar">
        <button className="hamburger" onClick={handleHamburgerClick}>
          <FontAwesomeIcon icon={faBars} />
        </button>
        <h2 className="dashboard-title">Dashboard</h2>
        <input type="text" className="search-bar" placeholder="Search..." />
        <div className="notifications" onClick={handleNotificationsClick}>
          <FontAwesomeIcon icon={faBell} />
          {isNotificationsMenuVisible && (
            <div className="notifications-menu">
              <ul>
                <li>
                  <Link to="/notifications/alerts">Alerts</Link>
                </li>
                <li>
                  <Link to="/notifications/messages">Messages</Link>
                </li>
              </ul>
            </div>
          )}
        </div>
        <div className="settings" onClick={handleSettingsClick}>
          <FontAwesomeIcon icon={faCog} />
          {isSettingsMenuVisible && (
            <div className="settings-menu">
              <ul>
                <li>
                  <Link to="/settings/profile">Profile</Link>
                </li>
                <li>
                  <Link to="/settings/account">Account</Link>
                </li>
              </ul>
            </div>
          )}
        </div>
        <div className="profile" onClick={handleProfileClick}>
          <FontAwesomeIcon icon={faUserCircle} />
          {isProfileMenuVisible && (
            <div className="profile-menu">
              <ul>
                <li>
                  <Link to="/profile/view">View Profile</Link>
                </li>
                <li onClick={handleLogout}>Log Out</li>{" "}
                {/* Logout usando la función */}
              </ul>
            </div>
          )}
        </div>
      </div>
      <div className="dashboard">
        <nav>
          <ul>
            <li>
              <Link to="users">
                <FontAwesomeIcon icon={faUser} />{" "}
                <span className="menu-text">Users</span>
              </Link>
            </li>
            <li>
              <Link to="wallet">
                <FontAwesomeIcon icon={faWallet} />{" "}
                <span className="menu-text">Wallet</span>
              </Link>
            </li>
            <li>
              <Link to="rooms">
                <FontAwesomeIcon icon={faBed} />{" "}
                <span className="menu-text">Rooms</span>
              </Link>
            </li>
            <li>
              <Link to="roomList">
                <FontAwesomeIcon icon={faBed} />{" "}
                <span className="menu-text">Rooms List</span>
              </Link>
            </li>
            <li>
              <Link to="bookings">
                <FontAwesomeIcon icon={faCalendarCheck} />{" "}
                <span className="menu-text">Bookings</span>
              </Link>
            </li>
            <li>
              <Link to="services">
                <FontAwesomeIcon icon={faConciergeBell} />{" "}
                <span className="menu-text">Services</span>
              </Link>
            </li>
            <li>
              <Link to="comments">
                <FontAwesomeIcon icon={faComments} />{" "}
                <span className="menu-text">Comments</span>
              </Link>
            </li>
            <li>
              <Link to="audit_logs">
                <FontAwesomeIcon icon={faFileAlt} />{" "}
                <span className="menu-text">Audit Logs</span>
              </Link>
            </li>
            <li>
              <Link to="sessions">
                <FontAwesomeIcon icon={faClock} />{" "}
                <span className="menu-text">Sessions</span>
              </Link>
            </li>
          </ul>
        </nav>
      </div>
      <div className="main-content">
        <Outlet />
      </div>
    </div>
  );
};

export default Dashboard;
