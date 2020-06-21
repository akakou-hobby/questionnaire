import init, { blind } from "../wasm/signature.js";

(async () => {
  await init();
  console.log(blind("hello"));
})();
