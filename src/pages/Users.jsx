import React, {useEffect, useState } from "react";
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
  const [findUser, setFindUser] = useState("");
  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = async () => {
    try {
        await axios.get('http://localhost/Tamwood-hotel/api/user-list?currentPage='+currentPage+'&searchType='+searchType+'&searchString='+searchString)
        .then((response)=>{
          const data = response.data;
          setUsers(data.data.result);
          setPaging(data.data.pagination);
        })
        .then((error)=>{
          console.log(error);
        });
    } catch (error) {
        setError(error.message);
    }
};

  const handleUnlock = async (event,id) => {
    event.preventDefault();
    const formData = new FormData();
    formData.append('uid', id);

    try {
      const response = await axios.post('http://localhost/Tamwood-hotel/api/init-locked', formData,{
      headers: {
        'user-sid':id
      }
    }).then(function (response) {
      const result = response.data;
      if(result.data) {
        setSuccess(true);
      }
    })
    .catch(function (error) {
      console.log(error);
    });
    } catch (error) {
      console.log("Caught an error:", error);
      setError("Fail to Filling the wallet");
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
      const response = await axios.post('http://localhost/Tamwood-hotel/api/create-transaction', formData,{
      headers: {
        'user-sid':sid
      }
    }).then(function (response) {
      const result = response.data;
      if(result.data) {
        setSuccess(true);
      }
    })
    .catch(function (error) {
      console.log(error);
    });
    } catch (error) {
      console.log("Caught an error:", error);
      setError("Fail to Filling the wallet");
    }
  };
    return (
      <>
        {user.role == 'customer' &&
        <div className="user-container">
          <h2>Users</h2>
          <label>Filling Wallet</label>
          <input type="text" 
                value={amount} 
                onChange={(e) => setAmount(e.target.value)} />
          <button type="submit" onClick={handleSubmit}>charging</button>  
          {success && <div className="success-message">successfully added</div>}
          {error && <div className="error-message">{error}</div>}    
        </div>
        }
        {user.role == 'admin' &&
        <div className="user-container">
          {/* <div>
            <input type="text" 
                value={searchString} 
                onChange={(e) => setAmount(e.target.value)}/>
            <button>Search</button>
          </div> */}
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
              </tr>
            </thead>
            <tbody>
            {users.length === 0 ? (
                        <tr>
                            <td colSpan="6">No users available</td>
                        </tr>
                    ) : (
                        users.map((user_row, index) => (
                            <tr key={`${user_row.user_id}-${index}`}>
                                <td>{user_row.username}</td>
                                <td>{user_row.email}</td>
                                <td>${user_row.role}</td>
                                <td>{user_row.is_locked==1?<button onClick={()=>handleUnlock()}>Unlock</button>:'-'}</td>
                                <td>{user_row.failed_login_attempts}</td>
                                <td>{user_row.wallet_balance}</td>
                                <td>{user_row.created_at}</td>
                            </tr>
                        ))
                    )}
            </tbody>
          </table>
          {/* <div className="pagination" dangerouslySetInnerHTML={{ __html: paging }} /> */}
        </div>
        }
      </>
    );
  };
  
  export default Users;
