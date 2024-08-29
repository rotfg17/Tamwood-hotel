import axios from "axios";
import { useEffect, useState } from "react";
import { useSession } from "../hooks/store/session";

const Comments = () => {
  const { sid } = useSession();

  const [comments, setComments] = useState([]);

  useEffect(() => {
    const callData = async () => {
      const response = await axios.get(
        "http://localhost/Tamwood-hotel/api/comments",
        {
          headers: {
            "user-sid": sid,
          },
        }
      );

      setComments(response.data.data);
    };

    callData();
  }, [sid]);

  return (
    <div className="transaction-container">
      <h2>Comment</h2>
      <table className="table transaction-table table-striped table-bordered">
        <thead>
          <tr>
            <th>Username</th>
            <th>Room Number</th>
            <th>Comment</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          {comments.map((comment) => {
            return (
              <tr key={comment.comment_id}>
                <td>{comment.username}</td>
                <td>{comment.room_number}</td>
                <td>{comment.comment_text}</td>
                <td>{comment.created_at}</td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
};

export default Comments;
