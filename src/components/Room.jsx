import axios from "axios";
import { useState } from "react";
import { useSession } from "../hooks/store/session";

const Room = ({ room }) => {
  console.log(room);
  const { user, sid } = useSession();

  const [modal, setModal] = useState(false);
  const [comment, setComment] = useState("");
  const [rating, setRating] = useState(0);

  const leaveCommentSubmit = async (e) => {
    e.preventDefault();
    const data = new FormData();

    data.append("user_id", user.id);
    data.append("room_id", room.room_id);
    data.append("comment_text", comment);
    data.append("rating", rating);

    try {
      const response = await axios.post(
        "http://localhost/Tamwood-hotel/api/add-comment",
        data,
        {
          headers: {
            "Content-Type": "multipart/form-data",
            "user-sid": sid,
          },
        }
      );

      if (response.status === 200) {
        setComment("");
        setRating(0);
        setModal(false);
      }
    } catch (error) {
      console.error(error);
    }
  };

  console.log(room);
  return (
    <>
      <tr>
        <td>{room.room_number}</td>
        <td>{room.status}</td>
        <td>{room.total_price}</td>
        <td>{room.check_in_date}</td>
        <td>{room.check_out_date}</td>
        {room.status === "approved" && (
          <td>
            <button type="button" onClick={() => setModal(true)}>
              Leave Comment
            </button>
          </td>
        )}
      </tr>
      {modal && (
        <div className="modal">
          <div>
            <span>Leave Comment</span>
            <form onSubmit={leaveCommentSubmit}>
              <div>
                <label htmlFor="comment">Comment</label>
                <input
                  name="comment"
                  value={comment}
                  onChange={(e) => setComment(e.target.value)}
                />
              </div>
              <div>
                <label htmlFor="rating">Rating</label>
                <input
                  name="rating"
                  value={rating}
                  onChange={(e) => setRating(e.target.value)}
                />
              </div>
              <div className="formButtons">
                <button type="button" onClick={() => setModal(false)}>
                  Close
                </button>
                <input type="submit" value="good" />
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
};

export default Room;
