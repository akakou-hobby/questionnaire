axios.interceptors.request.use(async (request) => {
  if (request.url != "api/answers/create.php") {
    request.headers["Authorization"] = `Bearer ${firebaseUserIdToken}`;
  }
  return request;
});
