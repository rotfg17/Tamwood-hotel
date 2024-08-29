import { useEffect, useState } from "react";

const Bookings = () => {
  const [rooms, setRooms] = useState([]);
  const [services, setServices] = useState([]);
  const [error, setError] = useState(null);
  const [checkedServices, setCheckedServices] = useState({});
  const [formData, setFormData] = useState({
    room_id: "",
    check_in_date: "",
    check_out_date: "",
    services: [],
  });

  const handleCheckboxChange = (serviceName) => {
    setCheckedServices((prev) => ({
      ...prev,
      [serviceName]: !prev[serviceName],
    }));
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleServiceQuantityChange = (serviceName, quantity) => {
    setFormData((prev) => {
      const existingServiceIndex = prev.services.findIndex(
        (service) => service.service_name === serviceName
      );

      if (existingServiceIndex !== -1) {
        const updatedServices = [...prev.services];
        updatedServices[existingServiceIndex].quantity = quantity;
        return { ...prev, services: updatedServices };
      } else {
        return {
          ...prev,
          services: [
            ...prev.services,
            { service_name: serviceName, quantity: quantity },
          ],
        };
      }
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const sid = sessionStorage.getItem("sid");

    const service_ids = formData.services.map(
      (service) => service.service_name
    );
    const quantities = formData.services.map((service) => service.quantity);

    const requestBody = {
      user_id: sid,
      room_id: formData.room_id,
      check_in_date: formData.check_in_date,
      check_out_date: formData.check_out_date,
      service: {
        service_id: service_ids,
        quantity: quantities,
      },
    };

    try {
      const response = await fetch(
        "http://localhost/Tamwood-hotel/api/create-booking",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(requestBody),
        }
      );

      if (!response.ok) throw new Error("Error creating booking");
      const data = await response.json();
      if (data.error) throw new Error(data.error);

      // Handle success (e.g., show success message, redirect, etc.)
      console.log("Booking created successfully:", data);
    } catch (error) {
      setError(error.message);
    }
  };

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

  const fetchServices = async () => {
    try {
      const response = await fetch(
        "http://localhost/Tamwood-hotel/api/services"
      );
      if (!response.ok) throw new Error("Error fetching services");
      const data = await response.json();
      if (data.error) throw new Error(data.error);
      setServices(data.data);
    } catch (error) {
      setError(error.message);
    }
  };

  useEffect(() => {
    const sid = sessionStorage.getItem("sid");
    if (!sid) {
      // Redirect to login page if no SID is found
      navigate("/");
      return;
    }

    fetchRooms();
    fetchServices();
  }, []);

  return (
    <div className="Bookings">
      <h1>Bookings</h1>
      <form onSubmit={handleSubmit}>
        <label htmlFor="rooms">Room</label>
        <select
          name="room_id"
          id="rooms"
          value={formData.room_id}
          onChange={handleInputChange}
          required
        >
          <option value="" disabled>
            Select a room
          </option>
          {rooms.map((room) => (
            <option key={room.room_number} value={room.room_number}>
              {room.room_number}
            </option>
          ))}
        </select>

        <label htmlFor="startDate">Start date:</label>
        <input
          type="date"
          id="startDate"
          name="check_in_date"
          value={formData.check_in_date}
          onChange={handleInputChange}
          required
        />

        <label htmlFor="leavingDate">Leaving date:</label>
        <input
          type="date"
          id="leavingDate"
          name="check_out_date"
          value={formData.check_out_date}
          onChange={handleInputChange}
          required
        />

<div className="services-header">Select Additional Services:</div>
    <div className="services">
      {services.map((service) => (
        <div className="service" key={service.service_name}>
          <input
            type="checkbox"
            id={service.service_name}
            name={service.service_name}
            checked={!!checkedServices[service.service_name]}
            onChange={() => handleCheckboxChange(service.service_name)}
          />
          <label htmlFor={service.service_name}>{service.service_name}</label>
          {checkedServices[service.service_name] && (
            <input
              type="number"
              name={`${service.service_name}_amount`}
              min="1"
              placeholder="Qty"
              onChange={(e) =>
                handleServiceQuantityChange(service.service_name, e.target.value)
              }
            />
          )}
        </div>
      ))}
    </div>
        <button type="submit">Book room</button>
      </form>
      {error && <div className="error-message">{error}</div>}
    </div>
  );
};

export default Bookings;
