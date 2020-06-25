import init, { blind, unblind } from "../wasm/signature.js";

(async () => {
  await init();
})();

const calc_signature = async (data) => {
  const pubkeyRes = await axios.get("../static/pub.pem");
  const pubkey = pubkeyRes.data;

  const blindPairStr = blind(data, pubkey);
  const blindPair = JSON.parse(blindPairStr);

  const params = new URLSearchParams();
  params.append("blinded_digest", blindPair.blinded_digest);
  const signedRes = await axios.post("api/sign.php", params);

  const signedBlindedSignature = signedRes.data.slice(0, -1);
  const result = unblind(signedBlindedSignature, pubkey, blindPair.unblinder);
  console.log("signature:", result);

  return result;
};

export { calc_signature };
