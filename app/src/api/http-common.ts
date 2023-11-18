import axios, { type AxiosInstance } from "axios";

const apiEndpoint = import.meta.env.VITE_API_ENDPOINT;

const apiClient: AxiosInstance = axios.create({
  baseURL: apiEndpoint,
  headers: {
    "Content-type": "application/json",
  },
});

export default apiClient;
