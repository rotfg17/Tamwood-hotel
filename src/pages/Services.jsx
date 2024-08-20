import { useEffect, useState } from 'react';
import '../App'; // Asegúrate de importar tu archivo CSS

const Services = () => {
    const [services, setServices] = useState([]);
    

    const fetchServices = async () => {
        try {
            const response = await fetch('/api/services');
            if (!response.ok) {
                throw new Error('Error al obtener los datos del servidor');
            }
            const data = await response.json();
            setServices(data);
        } catch (error) {
            console.error('Error al obtener los servicios:', error);
        }
    };

    useEffect(() => {
        fetchServices();
    }, []);

    

    return (
        <div className="Services">
            <h1>Services</h1>
            <select className="custom-select">
                <option value="" disabled>Select Services</option>
                {services.map((service, index) => (
                    <option key={index} value={service.service_name}>
                        {service.service_name}
                    </option>
                ))}
            </select>
        </div>
    );
};

export default Services;
