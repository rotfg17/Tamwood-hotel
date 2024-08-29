import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
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

// Protected route component to check for SID
const ProtectedRoute = ({ element }) => {
  const sid = sessionStorage.getItem("sid");
  return sid ? element : <Navigate to="/" />;
};

function App() {
  return (
    <div className="grid-container">
      {/* <Header/>
      <Dashboard/>
      <Home/> */}

      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Home />} />

          {/* Protect all dashboard-related routes */}
          <Route
            path="/dashboard"
            element={<ProtectedRoute element={<Dashboard />} />}
          >
            <Route
              path="users"
              element={<ProtectedRoute element={<Users />} />}
            />
            <Route
              path="wallet"
              element={<ProtectedRoute element={<Wallet />} />}
            />
            <Route
              path="rooms"
              element={<ProtectedRoute element={<Rooms />} />}
            />
            <Route
              path="roomList"
              element={<ProtectedRoute element={<RoomList />} />}
            />
            <Route
              path="bookings"
              element={<ProtectedRoute element={<Bookings />} />}
            />
            <Route
              path="services"
              element={<ProtectedRoute element={<Services />} />}
            />
            <Route
              path="comments"
              element={<ProtectedRoute element={<Comments />} />}
            />
            <Route
              path="audit_logs"
              element={<ProtectedRoute element={<Audit_Logs />} />}
            />
            <Route
              path="sessions"
              element={<ProtectedRoute element={<Sessions />} />}
            />
          </Route>

          {/* Catch-all route for undefined paths */}
          <Route path="*" element={<NoPage />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
