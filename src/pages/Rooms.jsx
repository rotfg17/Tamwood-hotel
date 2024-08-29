import axios from "axios";
import { useEffect, useState } from "react";
import Room from "../components/Room";
import { useSession } from "../hooks/store/session";

const RoomForm = () => {
  const { sid, user } = useSession();

  const [rooms, setRooms] = useState([]);
  const [error, setError] = useState(null);
  const [successMessage, setSuccessMessage] = useState("");
  const [formData, setFormData] = useState({
    room_number: "",
    room_type: "",
    price_per_night: "",
    description: "",
    status: "",
    image: null,
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleImageChange = (e) => {
    setFormData((prev) => ({ ...prev, image: e.target.files[0] }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const data = new FormData();

    for (const key in formData) {
      data.append(key, formData[key]);
    }

    try {
      const response = await axios.post(
        "http://localhost/Tamwood-hotel/api/create-room",
        data,
        {
          //   headers: {
          //     'session-key': sessionKey,  // Verifica que la session-key es la correcta
          //     'Content-Type': 'multipart/form-data'
          // }
        }
      );

      if (response.status === 200) {
        setSuccessMessage("Room registered successfully!");
        setError(null);
        setFormData({
          room_number: "",
          room_type: "",
          price_per_night: "",
          description: "",
          status: "",
          image: null,
        });
      } else {
        setError("Failed to register room. Please try again.");
      }
    } catch (error) {
      setError(error.response?.data?.error || "Error adding room");
      setSuccessMessage("");
    }
  };

  useEffect(() => {
    if (user) {
      const callData = async () => {
        const [bookingResponse] = await Promise.all([
          axios.get(
            `http://localhost/Tamwood-hotel/api/booking-info?userId=${user.id}`,
            {
              headers: {
                "user-sid": sid,
              },
            }
          ),
        ]);

        setRooms(bookingResponse.data.data);
      };

      callData();
    }
  }, [sid, user]);

  return (
    <>
      {(user?.role === "staff" || user?.role === "admin") && (
        <form onSubmit={handleSubmit} className="room-form">
          {error && <p style={{ color: "red" }}>{error}</p>}
          {successMessage && <p style={{ color: "green" }}>{successMessage}</p>}
          <div className="form-row">
            <label>
              Room Number:
              <input
                type="text"
                id="room_number"
                name="room_number"
                value={formData.room_number}
                onChange={handleChange}
                required
              />
            </label>
            <label>
              Room Type:
              <input
                type="text"
                id="room_type"
                name="room_type"
                value={formData.room_type}
                onChange={handleChange}
                required
              />
            </label>
          </div>
          <div className="form-row">
            <label>
              Price per Night:
              <input
                type="text"
                id="price_per_night"
                name="price_per_night"
                value={formData.price_per_night}
                onChange={handleChange}
                required
              />
            </label>
            <label>
              Status:
              <select
                id="status"
                name="status"
                value={formData.status}
                onChange={handleChange}
                required
              >
                <option value="">Select Status</option>
                <option value="Available">Available</option>
                <option value="Occupied">Occupied</option>
                <option value="Maintenance">Maintenance</option>
              </select>
            </label>
          </div>
          <label>
            Description:
            <textarea
              id="description"
              name="description"
              value={formData.description}
              onChange={handleChange}
              required
            />
          </label>
          <label>
            Image:
            <input
              type="file"
              id="image"
              name="image"
              accept="image/*"
              onChange={handleImageChange}
              required
            />
          </label>
          <button type="submit">Add Room</button>
        </form>
      )}
      {user?.role === "customer" && (
        <div className="transaction-container">
          <h2>Reservation Room List</h2>
          <table className="transaction-table">
            <thead>
              <tr>
                <th>Room Number</th>
                <th>Status</th>
                <th>Price</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Comment</th>
              </tr>
            </thead>
            <tbody>
              {rooms.map((room) => {
                return <Room key={room.booking_id} room={room} />;
              })}
            </tbody>
          </table>
        </div>
      )}
    </>
  );
};

export default RoomForm;
