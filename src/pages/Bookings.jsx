import React, { useState, useEffect } from 'react';
import axios from 'axios';
import '../App.css'; // Importa el archivo CSS

const Booking = () => {
  const [rooms, setRooms] = useState([]);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [checkInDate, setCheckInDate] = useState('');
  const [checkOutDate, setCheckOutDate] = useState('');
  const [totalPrice, setTotalPrice] = useState(0);
  const [error, setError] = useState(null);
  const [successMessage, setSuccessMessage] = useState('');

  useEffect(() => {
    fetchRooms();
  }, [checkInDate, checkOutDate]);

  const fetchRooms = async () => {
    try {
      const response = await axios.get('http://localhost/Tamwood-hotel/api/api/booking-info', {
        params: {
          checkInDate,
          checkOutDate,
        },
      });
      setRooms(response.data.rooms || []);
    } catch (error) {
      console.error('Error fetching rooms:', error);
      setError('Error fetching rooms. Please try again later.');
    }
  };

  const calculateTotalPrice = (room) => {
    const checkIn = new Date(checkInDate);
    const checkOut = new Date(checkOutDate);
    const timeDiff = Math.abs(checkOut - checkIn);
    const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
    return room.price_per_night * diffDays;
  };

  const handleBooking = async () => {
    if (!selectedRoom || !checkInDate || !checkOutDate) {
      setError('Please select a room and provide check-in and check-out dates.');
      return;
    }

    try {
      const calculatedTotalPrice = calculateTotalPrice(selectedRoom);
      setTotalPrice(calculatedTotalPrice);

      const response = await axios.post('http://localhost/Tamwood-hotel/api/create-booking', {
        user_id: 1, // Assuming a logged-in user with ID 1, adjust this as needed
        room_id: selectedRoom.room_id,
        check_in_date: checkInDate,
        check_out_date: checkOutDate,
        total_price: calculatedTotalPrice,
        status: 'Booked',
      });

      if (response.data.success) {
        setSuccessMessage('Room booked successfully!');
        setError(null);
        setSelectedRoom(null);
        setCheckInDate('');
        setCheckOutDate('');
      } else {
        setError('Failed to book room. Please try again.');
      }
    } catch (error) {
      console.error('Error booking room:', error);
      setError('An error occurred. Please try again later.');
      setSuccessMessage('');
    }
  };

  return (
    <div className="booking-container">
      <h2>Book a Room</h2>
      {error && <p style={{ color: 'red' }}>{error}</p>}
      {successMessage && <p style={{ color: 'green' }}>{successMessage}</p>}
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
                <button onClick={() => setSelectedRoom(room)}>Select</button>
              </li>
            ))
          ) : (
            <p>No rooms available for the selected dates.</p>
          )}
        </ul>
      </div>
      {selectedRoom && (
        <div>
          <p>Total Price: ${calculateTotalPrice(selectedRoom)}</p>
        </div>
      )}
      <button className="booking-button" onClick={handleBooking}>Book Room</button>
    </div>
  );
};

export default Booking;
