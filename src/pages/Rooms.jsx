// import { useEffect, useState } from 'react';
// import { format } from 'date-fns';

// const Rooms = () => {
//     const [rooms, setRooms] = useState([]);

//     const fetchRooms = async () => {
//         try {
//             const response = await fetch('http://localhost:3006/api/rooms');
//             if (!response.ok) {
//                 throw new Error('Error al obtener los datos del servidor');
//             }
//             const data = await response.json();
//             setRooms(data);
//         } catch (error) {
//             console.error('Error al obtener las rooms:', error);
//         }
//     };

//     useEffect(() => {
//         fetchRooms();
//     }, []);

//     return (
//         <div className="Rooms">
//             <h1>Rooms</h1>

//             <select className="custom-select">
//                 <option value="" disabled>Select room</option>
//                 {rooms.map((room, index) => (
//                     <option key={index} value={room.room_type}>{room.room_type}</option>
//                 ))}
//             </select><br /><br />

//             <table className="table table-striped table-bordered">
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
//         {rooms.map((room, index) => (
//     <tr key={`${room.room_number}-${index}`}>
//       <td>{room.room_number}</td>
//       <td>{room.room_type}</td>
//       <td>${room.price_per_night}</td>
//       <td>{room.description}</td>
//       <td style={{ color: room.status === 'Available' ? 'green' : 'red' }}>
//         {room.status}</td>
//         <td>{format(new Date(room.created_at), 'PPP')}</td>
//       {/* <td>{room.updated_at}</td> */}
//     </tr>
//   ))}
//         </tbody>
//       </table>
//         </div>
//     );
// };

// export default Rooms;
import React, { useState } from 'react';
// import PostService from '../../Back-end/index';
import axios from 'axios';

const RoomForm = () => {
  const [roomNumber, setRoomNumber] = useState('');
  const [roomType, setRoomType] = useState('');
  const [pricePerNight, setPricePerNight] = useState('');
  const [description, setDescription] = useState('');
  const [status, setStatus] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    const roomData = {
      room_number: roomNumber,
      room_type: roomType,
      price_per_night: parseFloat(pricePerNight),
      description,
      status
    };

    try {
      const response = await axios.post('http://localhost:3006/api/rooms', roomData);
      alert(response.data.message); // Mostrar el mensaje del servidor
//ROBINSON
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
    <form onSubmit={handleSubmit} className="room-form">
      <label>
        Room Number:
        <input
          type="text"
          value={roomNumber}
          onChange={(e) => setRoomNumber(e.target.value)}
          required
        />
      </label>
      <label>
        Room Type:
        <input
          type="text"
          value={roomType}
          onChange={(e) => setRoomType(e.target.value)}
          required
        />
      </label>
      <label>
        Price per Night:
        <input
          type="number"
          value={pricePerNight}
          onChange={(e) => setPricePerNight(e.target.value)}
          required
        />
      </label>
      <label>
        Description:
        <textarea
          value={description}
          onChange={(e) => setDescription(e.target.value)}
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
      <button type="submit">Submit</button>
    </form>
  );
};

export default RoomForm;