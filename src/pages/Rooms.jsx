// import { useEffect, useState } from "react";
// // import RoomController from '../../Back-end/controller/RoomController.php';

// const Rooms = () => {
//   const [rooms, setRooms] = useState([]);

//   const fetchRooms = async () => {
//     try {
//       const response = await fetch(
//         "http://localhost/Tamwood-hotel/api/room-type"
//       );
//       if (!response.ok) {
//         throw new Error("Error al obtener los datos del servidor");
//       }
//       const data = await response.json();
//       setRooms(data);
//     } catch (error) {
//       console.error("Error al obtener las rooms:", error);
//     }
//   };

//   useEffect(() => {
//     fetchRooms();
//   }, []);

//   return (
//     <div className="Rooms">
//       <h1>Rooms</h1>

//       <select className="custom-select">
//         <option value="" disabled>
//           Select room
//         </option>
//         {rooms.map((room, index) => (
//           <option key={index} value={room.room_type}>
//             {room.room_type}
//           </option>
//         ))}
//       </select>
//       <br />
//       <br />

//       <table className="table table-striped table-bordered">
//         <thead>
//           <tr>
//             <th>Room Number</th>
//             <th>Room Types</th>
//             <th>Price</th>
//             <th>Description</th>
//             <th>Status</th>
//             <th>created_at</th>
//           </tr>
//         </thead>
//         <tbody>
//           {/* {rooms.map((room, index) => (
//             <tr key={`${room.room_number}-${index}`}>
//               <td>{room.room_number}</td>
//               <td>{room.room_type}</td>
//               <td>${room.price_per_night}</td>
//               <td>{room.description}</td>
//               <td
//                 style={{ color: room.status === "Available" ? "green" : "red" }}
//               >
//                 {room.status}
//               </td>
//               <td>{format(new Date(room.created_at), "PPP")}</td> */}
//           {/* <td>{room.updated_at}</td> */}
//           {/* </tr>
//           ))} */}
//         </tbody>
//       </table>
//     </div>
//   );
// };

// export default Rooms;
import { useEffect, useState } from "react";
import { format } from 'date-fns';
import axios from 'axios';

const RoomForm = () => {
  const [room, setRoom] = useState('');
  const [room_number, setRoomNumber] = useState('');
  const [room_type, setRoomType] = useState('');
  const [price_per_night, setPricePerNight] = useState('');
  const [description, setDescription] = useState('');
  const [status, setStatus] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    const roomData = {
      room_number,
      room_type,
      price_per_night: parseFloat(price_per_night),
      description,
      status,
    };

    try {
      const response = await axios.post('http://localhost/Tamwood-hotel/api/room-type', roomData);
      alert(response.data.message);

      // Limpiar el formulario después de la sumisión exitosa
      setRoomNumber('');
      setRoomType('');
      setPricePerNight('');
      setDescription('');
      setStatus('');
    } catch (error) {
      console.error('Error adding room:', error);
      alert('Failed to add room!');
    }
  };

  return (
    <>
      <form onSubmit={handleSubmit} className="room-form">
        <div className="form-row">
          <label>
            Room Number:
            <input
              type="text"
              value={room_number}
              onChange={(e) => setRoomNumber(e.target.value)}
              required
            />
          </label>
          <label>
            Room Type:
            <input
              type="text"
              value={room_type}
              onChange={(e) => setRoomType(e.target.value)}
              required
            />
          </label>
        </div>
        <div className="form-row">
          <label>
            Price per Night:
            <input
              type="text"
              value={price_per_night}
              onChange={(e) => setPricePerNight(e.target.value)}
              required
            />
          </label>
          <label>
            Status:
            <select value={status} onChange={(e) => setStatus(e.target.value)} required>
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
            value={description}
            onChange={(e) => setDescription(e.target.value)}
            required
          />
        </label>
        <button type="submit">Add Room</button>
      </form>

      <table className="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Room Number</th>
            <th>Room Type</th>
            <th>Price</th>
            <th>Description</th>
            <th>Status</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          {/* {room.map((rooms, index) => (
            <tr key={`${rooms.room_number}-${index}`}>
              <td>{rooms.room_number}</td>
              <td>{rooms.room_type}</td>
              <td>${rooms.price_per_night}</td>
              <td>{rooms.description}</td>
              <td
                style={{ color: room.status === "Available" ? "green" : "red" }}
              >
                {room.status}
              </td>
              <td>{format(new Date(room.created_at), "PPP")}</td>
            </tr>
          ))} */}
        </tbody>
      </table>
    </>
  );
};

export default RoomForm;

