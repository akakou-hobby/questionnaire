axios.interceptors.request.use(async (request) => {
  const idToken = await firebase.auth().currentUser.getIdToken(true);
  request.headers["Authorization"] = `Bearer ${idToken}`;
  return request;
});
