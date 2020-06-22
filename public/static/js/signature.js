import init, { blind } from "../wasm/signature.js";

setTimeout(async () => {
  await init();
  const res = await axios.get("../static/pub.pem");

  const dataStr = blind("hello", res.data);
  const data = JSON.parse(dataStr);

  localStorage.unblinder = data.unblinder;

  console.log(data.blinded_digest, data.unblinder);

  const params = new URLSearchParams();
  params.append("blind_digest", data.blinded_digest);

  const _res = await axios.post("/sign", params);
  console.log(_res.data);
}, 3000);
