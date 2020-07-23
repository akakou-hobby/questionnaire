import init, { blind, unblind } from "../wasm/signature.js";

(async () => {
  await init();
})();

const calcSignature = async (data, userToken, token, pubkey) => {
  const blindPairStr = blind(data, pubkey);
  const blindPair = JSON.parse(blindPairStr);

  const params = new URLSearchParams();
  params.append("blinded_digest", blindPair.blinded_digest);
  params.append("user_token", userToken);
  params.append("token", token);

  const signedRes = await axios.post(`api/sign.php`, params);

  if (signedRes.data.slice(0, 7) == "[error]") {
    alert(signedRes.data);
    throw Error(signedRes.data);
  }

  const signedBlindedSignature = signedRes.data.slice(0, -1);
  const result = unblind(signedBlindedSignature, pubkey, blindPair.unblinder);
  console.log("signature:", result);

  return result;
};

export { calcSignature };
