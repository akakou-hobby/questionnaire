import init, { blind } from "../wasm/signature.js";

setTimeout(async () => {
  await init();
  const res = await axios.get("../static/pub.pem").data;

  console.log(blind("hello", res));
}, 3000);
