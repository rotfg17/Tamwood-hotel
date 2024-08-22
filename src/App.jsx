import { BrowserRouter, Routes, Route } from "react-router-dom";
import "@fontsource/poppins"; // Defaults to weight 400

import Dashboard from "./pages/Dashboard";
import Users from "./pages/Users";
import Wallet from "./pages/Wallet";
import Rooms from "./pages/Rooms";
import Bookings from "./pages/Bookings";
import Services from "./pages/Services";
import Comments from "./pages/Comments";
import Audit_Logs from "./pages/Audit_Logs";
import Home from "./pages/Home";
import RoomList from "./pages/Room_list";
import Sessions from "./pages/Sessions";
import NoPage from "./pages/NoPage";
import "./App.css";

function App() {
  return (
    <div className="grid-container">
      {/* <Header/>
      <Dashboard/>
      <Home/> */}

      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/dashboard" element={<Dashboard />}>
            <Route path="users" element={<Users />} />
            <Route path="wallet" element={<Wallet />} />
            <Route path="rooms" element={<Rooms />} />
            <Route path="roomList" element={<RoomList />} />
            <Route path="bookings" element={<Bookings />} />
            <Route path="services" element={<Services />} />
            <Route path="comments" element={<Comments />} />
            <Route path="audit_logs" element={<Audit_Logs />} />
            <Route path="sessions" element={<Sessions />} />
          </Route>
          <Route path="*" element={<NoPage />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
