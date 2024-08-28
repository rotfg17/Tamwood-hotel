import axios from "axios";
import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useSession } from "../hooks/store/session";

const RoomList = () => {
  const { user } = useSession();

  const [rooms, setRooms] = useState([]);
  const [error, setError] = useState(null);
  const [successMessage, setSuccessMessage] = useState("");
  const [showEditModal, setShowEditModal] = useState(false);
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [editData, setEditData] = useState({
    room_id: "",
    room_number: "",
    room_type: "",
    price_per_night: "",
    description: "",
    status: "",
  });
  const navigate = useNavigate();

  useEffect(() => {
    const sid = sessionStorage.getItem("sid");
    if (!sid) {
      // Redirect to login page if no SID is found
      navigate("/");
      return;
    }

    fetchRooms();
  }, []);

  const fetchRooms = async () => {
    try {
      const response = await fetch(
        "http://localhost/Tamwood-hotel/api/rooms?status=available"
      );
      if (!response.ok) throw new Error("Error fetching rooms");
      const data = await response.json();
      if (data.error) throw new Error(data.error);
      setRooms(data.data);
    } catch (error) {
      setError(error.message);
    }
  };

  const handleEditClick = (room) => {
    setSelectedRoom(room);
    setEditData({
      room_id: room.room_id || "",
      room_number: room.room_number || "",
      room_type: room.room_type || "",
      price_per_night: room.price_per_night || "",
      description: room.description || "",
      status: room.status || "",
    });
    setShowEditModal(true);
  };

  const handleDeleteClick = (room) => {
    setSelectedRoom(room);
    setShowDeleteModal(true);
  };

  const handleEditSave = async (e) => {
    e.preventDefault();

    const data = new FormData();
    data.append("room_id", editData.room_id);
    data.append("room_number", editData.room_number);
    data.append("room_type", editData.room_type);
    data.append("price_per_night", editData.price_per_night);
    data.append("description", editData.description);
    data.append("status", editData.status);

    try {
      const response = await axios.post(
        "http://localhost/Tamwood-hotel/api/update-room",
        data,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );

      if (response.status !== 200) throw new Error("Error updating room");
      const resultData = response.data;
      if (resultData.error) throw new Error(resultData.error);

      setShowEditModal(false);
      setSuccessMessage("Room updated successfully!");
      fetchRooms();
    } catch (error) {
      setError(error.message);
    }
  };

  const handleDeleteConfirm = async () => {
    if (!selectedRoom || !selectedRoom.room_id) {
      setError("Room ID is missing.");
      return;
    }

    const del = new FormData();
    del.append("room_id", selectedRoom.room_id);

    try {
      const response = await axios.post(
        "http://localhost/Tamwood-hotel/api/delete-room",
        del,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );

      if (response.status !== 200) throw new Error("Error deleting room");
      const resultData = response.data;
      if (resultData.error) throw new Error(resultData.error);

      setShowDeleteModal(false);
      setSuccessMessage("Room deleted successfully!");
      fetchRooms(); // Refresh room list after deletion
    } catch (error) {
      setError(error.message);
    }
  };

  return (
    <div className="transaction-container">
      <h2>Rooms List</h2>
      {error && <p style={{ color: "red" }}>{error}</p>}
      {successMessage && <p style={{ color: "green" }}>{successMessage}</p>}
      <table className="table transaction-table table-striped table-bordered">
        <thead>
          <tr>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Price</th>
            <th>Description</th>
            <th>Status</th>
            {user?.role === "staff" && <th>Action</th>}
          </tr>
        </thead>
        <tbody>
          {rooms.length === 0 ? (
            <tr>
              <td colSpan="6">No rooms available</td>
            </tr>
          ) : (
            rooms.map((room, index) => (
              <tr key={`${room.room_number}-${index}`}>
                <td>{room.room_number}</td>
                <td>{room.room_type}</td>
                <td>${room.price_per_night}</td>
                <td>{room.description}</td>
                <td className={`status-${room.status.toLowerCase()}`}>
                  {room.status}
                </td>
                {user?.role === "staff" && (
                  <td>
                    <button onClick={() => handleEditClick(room)}>Edit</button>
                    <button onClick={() => handleDeleteClick(room)}>
                      Delete
                    </button>
                  </td>
                )}
              </tr>
            ))
          )}
        </tbody>
      </table>

      {showEditModal && (
        <div className="modal">
          <div className="modal-content">
            <span className="close" onClick={() => setShowEditModal(false)}>
              &times;
            </span>
            <h2>Edit Room</h2>
            <form onSubmit={handleEditSave}>
              <div>
                <label>Room Number:</label>
                <input
                  type="text"
                  value={editData.room_number}
                  onChange={(e) =>
                    setEditData({ ...editData, room_number: e.target.value })
                  }
                  disabled
                />
              </div>
              <div>
                <label>Room Type:</label>
                <input
                  type="text"
                  value={editData.room_type}
                  onChange={(e) =>
                    setEditData({ ...editData, room_type: e.target.value })
                  }
                />
              </div>
              <div>
                <label>Price per Night:</label>
                <input
                  type="text"
                  value={editData.price_per_night}
                  onChange={(e) =>
                    setEditData({
                      ...editData,
                      price_per_night: e.target.value,
                    })
                  }
                />
              </div>
              <div>
                <label>Description:</label>
                <textarea
                  value={editData.description}
                  onChange={(e) =>
                    setEditData({ ...editData, description: e.target.value })
                  }
                />
              </div>
              <div>
                <label>Status:</label>
                <select
                  value={editData.status}
                  onChange={(e) =>
                    setEditData({ ...editData, status: e.target.value })
                  }
                  required
                >
                  <option value="">Select Status</option>
                  <option value="Available">Available</option>
                  <option value="Occupied">Occupied</option>
                  <option value="Maintenance">Maintenance</option>
                </select>
              </div>
              <button type="submit">Save Changes</button>
              <button type="button" onClick={() => setShowEditModal(false)}>
                Cancel
              </button>
            </form>
          </div>
        </div>
      )}

      {showDeleteModal && (
        <div className="modal">
          <div className="modal-content">
            <span className="close" onClick={() => setShowDeleteModal(false)}>
              &times;
            </span>
            <h2>Delete Room</h2>
            <p>
              Are you sure you want to delete room {selectedRoom?.room_number}?
            </p>
            <button onClick={handleDeleteConfirm}>Confirm</button>
            <button onClick={() => setShowDeleteModal(false)}>Cancel</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default RoomList;
