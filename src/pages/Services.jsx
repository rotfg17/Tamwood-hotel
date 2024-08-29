import axios from "axios";
import { useEffect, useState } from "react";
import "../App.css"; // Asegúrate de que el archivo .css esté correctamente ubicado
import { useSession } from "../hooks/store/session";

const Services = () => {
  const { sid } = useSession();

  const [services, setServices] = useState([]);
  const [newService, setNewService] = useState({
    service_name: "",
    service_description: "",
    price: "",
  });
  const [editService, setEditService] = useState({
    service_id: null,
    service_name: "",
    service_description: "",
    price: "",
  });
  const [deleteServiceId, setDeleteServiceId] = useState(null); // Estado para manejar la eliminación
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const [showDeleteModal, setShowDeleteModal] = useState(false); // Estado para controlar el modal

  // Función para obtener la lista de servicios
  const fetchServices = async () => {
    try {
      const response = await fetch(
        "http://localhost/Tamwood-hotel/api/services"
      );
      if (!response.ok) {
        throw new Error("Error getting data from server");
      }
      const data = await response.json();
      setServices(data.data); // Verifica que data.data contenga la lista de servicios
    } catch (error) {
      console.error("Error getting services:", error);
      setError("Failed to fetch services. Please try again later.");
    }
  };

  // Función para agregar un nuevo servicio
  const handleAddService = async () => {
    const data = new FormData();
    data.append("service_name", newService.service_name);
    data.append("service_description", newService.service_description);
    data.append("price", newService.price);

    try {
      await axios.post(
        "http://localhost/Tamwood-hotel/api/create-service",
        data,
        {
          headers: {
            "user-sid": sid,
          },
        }
      );

      setSuccess("Service added successfully");
      setNewService({ service_name: "", service_description: "", price: "" });
      fetchServices(); // Refrescar la lista de servicios después de agregar
    } catch (error) {
      console.error("Error creating service:", error);
      setError("Failed to add service. Please try again later.");
    }
  };

  // Función para editar un servicio existente
  const handleEditService = async () => {
    const data = new FormData();
    data.append("service_id", editService.service_id);
    data.append("service_name", editService.service_name);
    data.append("service_description", editService.service_description);
    data.append("price", editService.price);

    try {
      await axios.post(
        "http://localhost/Tamwood-hotel/api/update-service",
        data,
        {
          headers: {
            "user-sid": sid,
          },
        }
      );

      setSuccess("Service updated successfully");
      setEditService({
        service_id: null,
        service_name: "",
        service_description: "",
        price: "",
      });
      fetchServices(); // Refrescar la lista de servicios después de editar
    } catch (error) {
      console.error("Error updating service:", error);
      setError("Failed to update service. Please try again later.");
    }
  };

  // Función para abrir el modal de confirmación de eliminación
  const handleDeleteClick = (service_id) => {
    setDeleteServiceId(service_id);
    setShowDeleteModal(true);
  };

  // Función para eliminar un servicio
  const handleDeleteService = async () => {
    const data = new FormData();
    data.append("service_id", deleteServiceId);

    try {
      await axios.post(
        "http://localhost/Tamwood-hotel/api/delete-service",
        data,
        {
          headers: {
            "user-sid": sid,
          },
        }
      );

      setSuccess("Service deleted successfully");
      fetchServices(); // Refrescar la lista de servicios después de eliminar
      setShowDeleteModal(false); // Cerrar el modal
    } catch (error) {
      console.error("Error deleting service:", error);
      setError("Failed to delete service. Please try again later.");
      setShowDeleteModal(false); // Cerrar el modal incluso en caso de error
    }
  };

  useEffect(() => {
    fetchServices(); // Obtener los servicios cuando el componente se monta
  }, []);

  return (
    <div className="services">
      <h1>Services</h1>
      {error && <div className="error">{error}</div>}
      {success && <div className="success">{success}</div>}

      <div className="service-form">
        <input
          type="text"
          value={newService.service_name}
          onChange={(e) =>
            setNewService({ ...newService, service_name: e.target.value })
          }
          placeholder="Service Name"
        />
        <input
          type="text"
          value={newService.service_description}
          onChange={(e) =>
            setNewService({
              ...newService,
              service_description: e.target.value,
            })
          }
          placeholder="Service Description"
        />
        <input
          type="number"
          value={newService.price}
          onChange={(e) =>
            setNewService({ ...newService, price: e.target.value })
          }
          placeholder="Price"
        />
        <button onClick={handleAddService}>Add Service</button>
      </div>

      {editService.service_id && (
        <div className="service-form">
          <input
            type="text"
            value={editService.service_name}
            onChange={(e) =>
              setEditService({ ...editService, service_name: e.target.value })
            }
            placeholder="Edit Service Name"
          />
          <input
            type="text"
            value={editService.service_description}
            onChange={(e) =>
              setEditService({
                ...editService,
                service_description: e.target.value,
              })
            }
            placeholder="Edit Service Description"
          />
          <input
            type="number"
            value={editService.price}
            onChange={(e) =>
              setEditService({ ...editService, price: e.target.value })
            }
            placeholder="Edit Price"
          />
          <button onClick={handleEditService}>Update Service</button>
          <button
            onClick={() =>
              setEditService({
                service_id: null,
                service_name: "",
                service_description: "",
                price: "",
              })
            }
          >
            Cancel
          </button>
        </div>
      )}

      <table className="service-table">
        <thead>
          <tr>
            <th>Service Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {services.length > 0 ? (
            services.map((service) => (
              <tr key={service.service_id}>
                <td>{service.service_name}</td>
                <td>{service.service_description}</td>
                <td>{service.price}</td>
                <td>
                  <button
                    onClick={() =>
                      setEditService({
                        service_id: service.service_id,
                        service_name: service.service_name,
                        service_description: service.service_description,
                        price: service.price,
                      })
                    }
                  >
                    Edit
                  </button>
                  <button onClick={() => handleDeleteClick(service.service_id)}>
                    Delete
                  </button>
                </td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="4">No services available</td>
            </tr>
          )}
        </tbody>
      </table>

      {/* Modal de confirmación de eliminación */}
      {showDeleteModal && (
        <div className="modal">
          <div className="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this service?</p>
            <button onClick={handleDeleteService}>Yes, Delete</button>
            <button onClick={() => setShowDeleteModal(false)}>Cancel</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default Services;
