extern crate wasm_bindgen;
use wasm_bindgen::prelude::*;

use rsa_fdh;
use rsa_fdh::blind;
use rsa::{RSAPrivateKey, RSAPublicKey};
use sha2::{Sha256, Digest};

use pem;
use rand;
use base64;

use serde::{Serialize, Deserialize};
use serde_json;

#[derive(Serialize, Deserialize)]
struct BlindPair {
    blinded_digest: String,
    unblinder: String
}

#[wasm_bindgen]
pub fn blind(message: &str, pubkey: &str) -> String {    
    let mut rng = rand::thread_rng();

    let signer_pub_key = pem::parse(pubkey).unwrap();
    let signer_pub_key = RSAPublicKey::from_pkcs1(&signer_pub_key.contents).unwrap();

    let digest = blind::hash_message::<Sha256, _>(&signer_pub_key, message.as_bytes()).unwrap();
    let (blinded_digest, unblinder) = blind::blind(&mut rng, &signer_pub_key, &digest);

    let blind_pair = BlindPair {
        blinded_digest: base64::encode(blinded_digest),
        unblinder: base64::encode(unblinder)
    };

    serde_json::to_string(&blind_pair).unwrap()
}

#[wasm_bindgen]
pub fn unblind(blind_signature: &str, pubkey: &str, unblinder: &str) -> String {
    let signer_pub_key = pem::parse(pubkey).unwrap();
    let signer_pub_key = RSAPublicKey::from_pkcs1(&signer_pub_key.contents).unwrap();
    
    let blind_signature = base64::decode(blind_signature).unwrap();
    let unblinder = base64::decode(unblinder).unwrap();

    let signature = blind::unblind(&signer_pub_key, &blind_signature, &unblinder);
    base64::encode(signature)
}
