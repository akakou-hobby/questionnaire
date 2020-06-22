import init, { blind, unblind } from "../wasm/signature.js";

setTimeout(async () => {
  await init();
  const res = await axios.get("../static/pub.pem");

  const dataStr = blind("hello", res.data);
  const data = JSON.parse(dataStr);

  const params = new URLSearchParams();
  params.append("blind_digest", data.blinded_digest);

  const _res = await axios.post("/sign", params);

  _res.data = _res.data.slice(0, -1);
  const result = unblind(_res.data, res.data, data.unblinder);
  console.log("signature:", result);
}, 3000);
