import { EMessageType, useAuthStore, useNotificationStore } from "~/stores";

const onRequest = (config) => {
  if (
    config.headers === undefined ||
    config.url === undefined ||
    config.url.includes("/api/login_check")
  ) {
    return config;
  }

  // console.log(config);
  // //fix for symfony
  // if (config.method === "put" && config.data !== undefined) {
  //   config.headers.Accept =  "application/json, text/plain, */*";
  //   config.data.append('_method', 'PUT');
  // }

  const { user } = useAuthStore();
  const isLoggedIn = !!user?.token;
  const isApiUrl = config.url.startsWith(import.meta.env.VITE_API_URL);
  if (isLoggedIn && isApiUrl) {
    config.headers.Authorization = `Bearer ${user.token}`;
  }
  return config;
};

const onRequestError = (error) => {
  return Promise.reject(error);
};

const onResponse = async (response) => {
  if (!(response && response.status === 200 && response.statusText === "OK")) {
    const { user, logout } = useAuthStore();
    if ([401, 403].includes(response.status) && user) {
      // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
      await logout();
    }

    // get error message from body or default to response status
    const error = (response.data && response.data.message) || response.status;
    throw new Error(error);
  }

  if (response.data.notify && response.data.notify.length > 0) {
    const { processReturn } = useNotificationStore();
    processReturn(response.data.notify);
  }

  return response;
};

const onResponseError = async (error) => {
  const { logout } = useAuthStore();
  const { addMessage } = useNotificationStore();
  if (error.response === undefined) {
    addMessage({ text: "API Connection Error.", type: EMessageType.Danger });
    return Promise.reject(error);
  }
  if (error.response.status === 500) {
    addMessage({ text: "Error in the API.", type: EMessageType.Danger });
    return Promise.reject(error);
  }
  if ([401, 403].includes(error.response.status)) {
    // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
    await logout();
  }
  return Promise.reject(error);
};

export function setupInterceptorsTo(axiosInstance) {
  axiosInstance.interceptors.request.use(onRequest, onRequestError);
  axiosInstance.interceptors.response.use(onResponse, onResponseError);
  return axiosInstance;
}
