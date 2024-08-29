import axios from "axios";
import { useEffect, useState } from "react";
import { useSession } from "../hooks/store/session";

const WalletTransaction = () => {
  const { user, sid } = useSession();
  const [transactions, setTransactions] = useState([]);

  useEffect(() => {
    if (user && user.role === "customer") {
      fetchTransactions();
    }
  }, [user]);

  const fetchTransactions = async () => {
    try {
      const response = await axios.get(
        `http://localhost/Tamwood-hotel/api/transactions?user_id=${user.id}`,
        {
          headers: {
            "user-sid": sid,
          },
        }
      );

      const data = response.data;
      setTransactions(data.data);
    } catch (error) {
      console.log(error);
    }
  };
  console.log(transactions);

  return (
    <div className="transaction-container">
      <h2>Transaction List</h2>
      <table className="transaction-table">
        <thead>
          <tr>
            <th>Type</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          {transactions.map((transaction) => (
            <tr key={transaction.transaction_id}>
              <td>{transaction.transaction_type}</td>
              <td>${transaction.amount}</td>
              <td>{transaction.transaction_date}</td>
              <td>{transaction.description}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default WalletTransaction;
