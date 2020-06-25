axios.interceptors.request.use(async (request) => {
  request.headers["Authorization"] = `Bearer ${firebaseUserIdToken}`;
  return request;
});
