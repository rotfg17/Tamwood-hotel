import { Outlet, Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUser, faWallet, faBed, faCalendarCheck, faConciergeBell, faComments, faFileAlt, faClock } from '@fortawesome/free-solid-svg-icons';

import '../App.css';


const Dashboard = () => {
  return (
    <div className="d-flex">
      <div className="dashboard">
        <h2>Dashboard</h2>
        <nav>
          <ul>
            <li><Link to="/Users"><FontAwesomeIcon icon={faUser} /> Users</Link></li>
            <li><Link to="/Wallet"><FontAwesomeIcon icon={faWallet} /> Wallet</Link></li>
            <li><Link to="/Rooms"><FontAwesomeIcon icon={faBed} /> Rooms</Link></li>
            <li><Link to="/Bookings"><FontAwesomeIcon icon={faCalendarCheck} /> Bookings</Link></li>
            <li><Link to="/Services"><FontAwesomeIcon icon={faConciergeBell} /> Services</Link></li>
            <li><Link to="/Comments"><FontAwesomeIcon icon={faComments} /> Comments</Link></li>
            <li><Link to="/Audit_Logs"><FontAwesomeIcon icon={faFileAlt} /> Audit Logs</Link></li>
            <li><Link to="/Sessions"><FontAwesomeIcon icon={faClock} /> Sessions</Link></li>
          </ul>
        </nav>
        <Outlet />
      </div>
    </div>
  );
};

export default Dashboard;
