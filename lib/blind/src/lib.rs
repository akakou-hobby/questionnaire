extern crate wasm_bindgen;
use wasm_bindgen::prelude::*;

use rsa_fdh;
use rsa_fdh::blind;
use rsa::RSAPublicKey;
use sha2::Sha256;

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

    let pubkey = match base64::decode(pubkey) {
        Ok(result) => result,
        Err(_) => {
            return "[error] decode pubkey".to_string()
        }  
    };
    
    let signer_pub_key = match pem::parse(pubkey) {
        Ok(result) => result,
        Err(err) => {
            return format!("[error] parase pem\n{}", err.description())
        }  
    };

    let signer_pub_key = match RSAPublicKey::from_pkcs8(&signer_pub_key.contents) {
        Ok(result) => result,
        Err(_) => {
            return "[error] parase pkcs".to_string()
        }  
    };

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
    let pubkey = match base64::decode(pubkey) {
        Ok(result) => result,
        Err(_) => {
            return "[error] decode pubkey".to_string()
        }  
    };

    let signer_pub_key = match pem::parse(pubkey) {
        Ok(result) => result,
        Err(err) => {
            return format!("[error] parse pem\n{}", err.description())
        }  
    };

    let signer_pub_key = match RSAPublicKey::from_pkcs8(&signer_pub_key.contents) {
        Ok(result) => result,
        Err(_) => {
            return "[error] parse pkcs".to_string()
        }  
    };

    let blind_signature = match base64::decode(blind_signature) {
        Ok(result) => result,
        Err(_) => {
            return "[error] decode signature".to_string()
        }  
    };
    
    let unblinder = match base64::decode(unblinder) {
        Ok(result) => result,
        Err(_) => {
            return "[error] decode unblinder".to_string()
        }  
    };

    let signature = blind::unblind(&signer_pub_key, &blind_signature, &unblinder);
    base64::encode(signature)
}


#[test]
fn test_blind() {
    let pubkey = "-----BEGIN RSA PUBLIC KEY-----MIIBCgKCAQEAnKvWNuBvC/t7AwqAMMpd8EAVRvJ1vvXuwsTzK7qLzL+9ynzKapwi/U+4gnCNOG66VFvlQQJrLr03g3/AirvfnaXwyrACgL5JG0McRrHlZg4h7EbP3FbzSDUpx5kAEkqH57bz+Q4XM8/0lyLEqv9twSwz/H6w/Ky1q4ZgNQ1772Z4PVVd+kP1LVZc+ZFch6WxAt5aIsSRz3hp/Glg8MS/g7ryMSM0VJNGNywNLUvJ0ZHifHGl6mAI5kHoF7h7r+qgyvg9uCSO4rR9IA0JH88FupzYjw72H6ubcgDnX57CMlVtWAV1WSUE3xr/+Rm9AkMRVAOW54DcwsuH0CQXXQKOHQIDAQAB-----END RSA PUBLIC KEY-----";

    let res = blind("aaaaa", pubkey);
    println!("----- blind -----\n{}", res);
}


#[test]
fn test_unblinder() {
    let pubkey = "-----BEGIN RSA PUBLIC KEY-----MIIBCgKCAQEAnKvWNuBvC/t7AwqAMMpd8EAVRvJ1vvXuwsTzK7qLzL+9ynzKapwi/U+4gnCNOG66VFvlQQJrLr03g3/AirvfnaXwyrACgL5JG0McRrHlZg4h7EbP3FbzSDUpx5kAEkqH57bz+Q4XM8/0lyLEqv9twSwz/H6w/Ky1q4ZgNQ1772Z4PVVd+kP1LVZc+ZFch6WxAt5aIsSRz3hp/Glg8MS/g7ryMSM0VJNGNywNLUvJ0ZHifHGl6mAI5kHoF7h7r+qgyvg9uCSO4rR9IA0JH88FupzYjw72H6ubcgDnX57CMlVtWAV1WSUE3xr/+Rm9AkMRVAOW54DcwsuH0CQXXQKOHQIDAQAB-----END RSA PUBLIC KEY-----";

    let blinded_signature = "EfjSYZqSWYJ6pHGXpgUTuYqdS/kdHEw2XbEGGBr5qAldcR+xVY5I0lp0mAG0MVqvdd8d54pMMC93RESSwXWbjUxtrwylDY3ZXyOwlnCmlgK2Ybegdt8HT2zKo/GXE084zMh32kCk2eELzH6o7a5iwCPeYkfOUOGcH3gqmfqijIDJmPpYJWflIRhoShbCa0RvjLekSkmoD6lYpZvSxhjn6X1exQqUj5DkOTvYHiaFk4C+IW5sWAcf0AjQzwDQIDw6oiSCWHT6zIlZv5MWQxPPLMC35MpW5Dpn8hv1OLehzblhvlaCcQIfzZizN79CDp4Cn/J94E3ZT1zyIAZ//a+DqA==";

    let unblinder = "mYw9HF1Aej31EJNvDGcrPy2H6rM0ko5ucgPmMYSGeYPvkoYB9+s9tW01LwVcshO70Tur+z+0mcn3wcD18rArWYm3pHzubH20AoAC/ZykGZn7oYGLaJBkN7zc1gD0iT2HpqGoIz9lOTY7/UKr4C5bAsJuUVYgnsr0dNVwuYh+dF5qF9V9bZIOPPK8tJ6kNV2PBfBX7PmRbgirlnvX0wsqqxip8QR4cz2S8Z/z096PsgHz47Kh4MaOw6/MEfMgplUYu/lFOoryR/i/K189m+leps8GwJJOPKkEWM3KKdbCrrVYcJ6kHAnXEdh80tzHzLzmenmZdG1kzn6mZwAJ63FldA==";
    
    let res = unblind(blinded_signature, pubkey, unblinder);
    println!("----- unblind -----\n{}", res);
}
