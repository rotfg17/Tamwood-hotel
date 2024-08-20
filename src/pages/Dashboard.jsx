import { useState } from 'react';
import { Outlet, Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUser, faWallet, faBed, faCalendarCheck, faConciergeBell, faComments, faFileAlt, faClock, faBars, faCog, faBell, faUserCircle } from '@fortawesome/free-solid-svg-icons';
import '../App.css'; // Asegúrate de importar el archivo CSS correcto

const Dashboard = () => {
  const [isSettingsMenuVisible, setSettingsMenuVisible] = useState(false);
  const [isNotificationsMenuVisible, setNotificationsMenuVisible] = useState(false);
  const [isProfileMenuVisible, setProfileMenuVisible] = useState(false);

  const handleHamburgerClick = () => {
    const sidebar = document.querySelector('.dashboard');
    const topBar = document.querySelector('.top-bar');
    const isCollapsed = sidebar.classList.toggle('collapsed');

    // Adjust top-bar width and position based on sidebar state
    if (isCollapsed) {
      topBar.style.left = '100px'; // Ajustar según el ancho del sidebar colapsado
      topBar.style.width = 'calc(100% - 120px)'; // Ajustar el ancho para que se ajuste
    } else {
      topBar.style.left = '230px'; // Ancho original del sidebar
      topBar.style.width = 'calc(100% - 250px)'; // Ajustar el ancho para que se ajuste
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
                <li><Link to="/notifications/alerts">Alerts</Link></li>
                <li><Link to="/notifications/messages">Messages</Link></li>
              </ul>
            </div>
          )}
        </div>
        <div className="settings" onClick={handleSettingsClick}>
          <FontAwesomeIcon icon={faCog} />
          {isSettingsMenuVisible && (
            <div className="settings-menu">
              <ul>
                <li><Link to="/settings/profile">Profile</Link></li>
                <li><Link to="/settings/account">Account</Link></li>
              </ul>
            </div>
          )}
        </div>
        <div className="profile" onClick={handleProfileClick}>
          <FontAwesomeIcon icon={faUserCircle} />
          {isProfileMenuVisible && (
            <div className="profile-menu">
              <ul>
                <li><Link to="/profile/view">View Profile</Link></li>
                <li><Link to="/profile/edit">Log Out</Link></li>
              </ul>
            </div>
          )}
        </div>
      </div>
      <div className="dashboard">
        <nav>
          <ul>
            <li><Link to="users"><FontAwesomeIcon icon={faUser} /> <span className="menu-text">Users</span></Link></li>
            <li><Link to="wallet"><FontAwesomeIcon icon={faWallet} /> <span className="menu-text">Wallet</span></Link></li>
            <li><Link to="rooms"><FontAwesomeIcon icon={faBed} /> <span className="menu-text">Rooms</span></Link></li>
            <li><Link to="bookings"><FontAwesomeIcon icon={faCalendarCheck} /> <span className="menu-text">Bookings</span></Link></li>
            <li><Link to="services"><FontAwesomeIcon icon={faConciergeBell} /> <span className="menu-text">Services</span></Link></li>
            <li><Link to="comments"><FontAwesomeIcon icon={faComments} /> <span className="menu-text">Comments</span></Link></li>
            <li><Link to="audit_logs"><FontAwesomeIcon icon={faFileAlt} /> <span className="menu-text">Audit Logs</span></Link></li>
            <li><Link to="sessions"><FontAwesomeIcon icon={faClock} /> <span className="menu-text">Sessions</span></Link></li>
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
