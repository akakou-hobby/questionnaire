import init, { hello } from "../wasm/signature.js";

(async () => {
  await init();
  console.log(hello("hello"));
})();
