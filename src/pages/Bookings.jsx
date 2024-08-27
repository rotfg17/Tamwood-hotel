import React, { useState, useEffect } from 'react';
import axios from 'axios';
import '../App.css'; // Importa el archivo CSS

const Booking = () => {
  const [rooms, setRooms] = useState([]);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [checkInDate, setCheckInDate] = useState('');
  const [checkOutDate, setCheckOutDate] = useState('');
  const [error, setError] = useState(null);

  useEffect(() => {
    if (checkInDate && checkOutDate) {
      fetchRooms();
    }
  }, [checkInDate, checkOutDate]);

  const fetchRooms = async () => {
    try {
        const response = await axios.get('http://localhost/Tamwood-hotel/api/available-rooms', {
            params: {
                checkInDate,
                checkOutDate,
            },
        });
        if (response.data.rooms && response.data.rooms.length > 0) {
            setRooms(response.data.rooms);
        } else {
            setRooms([]);
            setError('No rooms available for the selected dates.');
        }
    } catch (error) {
        console.error('Error fetching rooms:', error);
        setError('Error fetching rooms. Please try again later.');
    }
};


  const handleRoomSelection = (room) => {
    setSelectedRoom(room);
    setError(null); // Clear any previous errors
    console.log('Room selected:', room);
  };

  const handleBooking = async () => {
    if (!selectedRoom || !checkInDate || !checkOutDate) {
      setError('Please select a room and provide check-in and check-out dates.');
      return;
    }

    try {
      const response = await axios.post('http://localhost/Tamwood-hotel/api/create-booking', {
        user_id: 1, // Ajusta esto según sea necesario
        room_id: selectedRoom.room_id,
        check_in_date: checkInDate,
        check_out_date: checkOutDate,
        status: 'Booked',
      });

      if (response.data.success) {
        setSelectedRoom(null);
        setCheckInDate('');
        setCheckOutDate('');
        setError(null);
      } else {
        setError('Failed to book room. Please try again.');
      }
    } catch (error) {
      console.error('Error booking room:', error);
      setError('An error occurred. Please try again later.');
    }
  };

  return (
    <div className="booking-container">
      <h2>Book a Room</h2>
      {error && <p style={{ color: 'red' }}>{error}</p>}
      <div className="booking-form">
        <div className="form-row">
          <label>
            Check-In Date:
            <input 
              type="date" 
              value={checkInDate} 
              onChange={(e) => setCheckInDate(e.target.value)} 
              required
            />
          </label>
          <label>
            Check-Out Date:
            <input 
              type="date" 
              value={checkOutDate} 
              onChange={(e) => setCheckOutDate(e.target.value)} 
              required
            />
          </label>
        </div>
      </div>
      <div className="available-rooms">
        <h3>Available Rooms</h3>
        <ul>
          {rooms.length > 0 ? (
            rooms.map(room => (
              <li key={room.room_id}>
                {room.room_number} - {room.room_type} - ${room.price_per_night}
                <button onClick={() => handleRoomSelection(room)}>Select</button>
              </li>
            ))
          ) : (
            <p>No rooms available for the selected dates.</p>
          )}
        </ul>
      </div>
      {selectedRoom && (
        <div>
          <p>Room Selected: {selectedRoom.room_number}</p>
        </div>
      )}
      <button className="booking-button" onClick={handleBooking}>Book Room</button>
    </div>
  );
};

export default Booking;
