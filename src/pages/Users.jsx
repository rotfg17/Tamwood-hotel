import React, { useEffect, useState } from "react";
import axios from 'axios';
import { useSession } from "../hooks/store/session";

const Users = () => {
  const { user, sid } = useSession();
  const [users, setUsers] = useState([]);
  const [paging, setPaging] = useState(null);
  const [error, setError] = useState(null);
  const [amount, setAmount] = useState(0);
  const [success, setSuccess] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [searchString, setSearchString] = useState("");
  const [searchType, setSearchType] = useState("");
  
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


  const handleSubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData();
    formData.append('user_id', user.id);
    formData.append('transaction_type', 'deposit');
    formData.append('amount', amount);
    formData.append('description', 'test');
    try {
      const response = await axios.post('http://localhost/Tamwood-hotel/api/create-transaction', formData, {
        headers: {
          'user-sid': sid
        }
      });
      if (response.data && response.data.message) {
        setSuccess(response.data.message);
      } else {
        setError("Failed to add amount to the wallet.");
      }
    } catch (error) {
      console.log("Caught an error:", error);
      setError("Failed to add amount to the wallet.");
    }
  };

  if (!user) {
    return <div>Loading...</div>; // Puedes ajustar este mensaje o hacer un redireccionamiento si es necesario
  }

  return (
    <>
      {user.role === 'customer' &&
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
      {user.role === 'admin' &&
        <div className="user-container">
          <table className="user-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Locked</th>
                <th>Failed Login Attempts</th>
                <th>Wallet Balance</th>
                <th>Join Date</th>
              </tr>
            </thead>
            <tbody>
              {users.length === 0 ? (
                <tr>
                  <td colSpan="7">No users available</td>
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
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      }
    </>
  );
};

export default Users;

