import React, { useEffect, useState } from "react";
import axios from 'axios';
import { useSession } from "../hooks/store/session";

const Users = () => {
  const [modal, setModal] = useState(false);
  const { user, sid } = useSession();
  const [users, setUsers] = useState([]);
  const [paging, setPaging] = useState(null);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [searchString, setSearchString] = useState("");
  const [searchType, setSearchType] = useState("");

  // Estado para el formulario de agregar usuario
  const [newUsername, setNewUsername] = useState("");
  const [newEmail, setNewEmail] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [newRole, setNewRole] = useState("customer");

  useEffect(() => {
    fetchUsers();
  }, [currentPage, searchType, searchString]);

  const fetchUsers = async () => {
    try {
      const response = await axios.get(`http://localhost/Tamwood-hotel/api/user-list?currentPage=${currentPage}&searchType=${searchType}&searchString=${searchString}`, {
        headers: {
          'user-sid': sid
        }
      });
      const data = response.data;
      setUsers(data.data.result);
      setPaging(data.data.pagination);
    } catch (error) {
      console.log(error);
      setError("Failed to fetch users.");
    }
  };

  const handleUnlock = async (event, id) => {
    event.preventDefault();
    const formData = new FormData();
    formData.append('uid', id);

    try {
      const response = await axios.post('http://localhost/Tamwood-hotel/api/init-locked', formData, {
        headers: {
          'user-sid': sid
        }
      });
      if (response.data && response.data.message) {
        setSuccess(response.data.message);
        fetchUsers(); // Refrescar la lista de usuarios
      } else {
        setError("Failed to unlock the user.");
      }
    } catch (error) {
      console.log(error);
      setError("An error occurred while unlocking the user.");
    }
  };

  const handleModal = () => {
    setModal(!modal);
  };

  const handleDelete = async (id) => {
    // Aquí debes implementar la lógica para eliminar un usuario
    console.log(`Delete user with ID: ${id}`);
    // Recuerda llamar a fetchUsers() después de eliminar el usuario
  };

  const handleAddUser = async (event) => {
    event.preventDefault();
    const formData = {
      username: newUsername,
      email: newEmail,
      password: newPassword,
      role: newRole
    };

    try {
      const response = await axios.post('http://localhost/Tamwood-hotel/api/add-user', formData, {
        headers: {
          'user-sid': sid,
          'Content-Type': 'application/json'
        }
      });

      if (response.data && response.data.message) {
        setSuccess(response.data.message);
        setModal(false);
        fetchUsers(); // Refrescar la lista de usuarios
      } else {
        setError("Failed to add new user.");
      }
    } catch (error) {
      console.log("Caught an error:", error);
      setError("Failed to add new user.");
    }
  };

  return (
    <>
      {user?.role === 'customer' &&
        <div className="user-container">
          <h2>Users</h2>
          <label>Filling Wallet</label>
          <input
            type="text"
            value={amount}
            onChange={(e) => setAmount(e.target.value)}
          />
          <button type="submit" onClick={handleSubmit}>Charging</button>
          {success && <div className="success-message">{success}</div>}
          {error && <div className="error-message">{error}</div>}
        </div>
      }
      {user?.role === 'admin' &&
        <div className="user-container">
          <div className="user-controls">
            <input 
              type="text" 
              value={searchString} 
              onChange={(e) => setSearchString(e.target.value)} 
              placeholder="Search users"
              className="search-input"
            />
            <button onClick={fetchUsers} className="search-button">Search</button>
            <button onClick={handleModal} className="add-user-button">Add User</button>
          </div>
          <table className="user-table">
            <thead>
              <tr>
                <th>name</th>
                <th>email</th>
                <th>role</th>
                <th>locked</th>
                <th>failed login attempts</th>
                <th>wallet balance</th>
                <th>join date</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              {users.length === 0 ? (
                <tr>
                  <td colSpan="8">No users available</td>
                </tr>
              ) : (
                users.map((user_row, index) => (
                  <tr key={`${user_row.user_id}-${index}`}>
                    <td>{user_row.username}</td>
                    <td>{user_row.email}</td>
                    <td>{user_row.role}</td>
                    <td>{user_row.is_locked === 1 ? <button onClick={(e) => handleUnlock(e, user_row.user_id)}>Unlock</button> : '-'}</td>
                    <td>{user_row.failed_login_attempts}</td>
                    <td>{user_row.wallet_balance}</td>
                    <td>{user_row.created_at}</td>
                    <td>{user_row.username.includes('__DELETED') ? '-' : <button onClick={() => handleDelete(user_row.user_id)}>Delete</button>}</td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
          {paging && <div className="paging">{paging}</div>}
        </div>
      }

      {modal && (
        <div className="modal">
          <div className="modal-content">
            <h2>Add New User</h2>
            <form onSubmit={handleAddUser}>
              <label>Username</label>
              <input 
                type="text" 
                value={newUsername} 
                onChange={(e) => setNewUsername(e.target.value)} 
                required
              />
              <label>Email</label>
              <input 
                type="email" 
                value={newEmail} 
                onChange={(e) => setNewEmail(e.target.value)} 
                required
              />
              <label>Password</label>
              <input 
                type="password" 
                value={newPassword} 
                onChange={(e) => setNewPassword(e.target.value)} 
                required
              />
              <label>Role</label>
              <select 
                value={newRole} 
                onChange={(e) => setNewRole(e.target.value)}
                required
              >
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
                {/* Agrega más roles según sea necesario */}
              </select>
              <button type="submit" className="submit-button">Add User</button>
            </form>
            <button onClick={handleModal} className="close-button">Close</button>
          </div>
        </div>
      )}
    </>
  );
};

export default Users;
