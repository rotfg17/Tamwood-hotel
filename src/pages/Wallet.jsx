import React from 'react';

const WalletTransaction = () => {
  const transactions = [

  ];

  return (
    <div className="transaction-container">
      <h2>Transaction List</h2>
      <table className="transaction-table">
        <thead>
          <tr>
            <th>Transaction ID</th>
            <th>User ID</th>
            <th>Transaction Type</th>
            <th>Amount</th>
            <th>Transaction Date</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          {transactions.map((transaction, index) => (
            <tr key={index}>
              <td>{transaction.transaction_id}</td>
              <td>{transaction.user_id}</td>
              <td>{transaction.transaction_type}</td>
              <td>${transaction.amount.toFixed(2)}</td>
              <td>{new Date(transaction.transaction_date).toLocaleDateString()}</td>
              <td>{transaction.description}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default WalletTransaction;
