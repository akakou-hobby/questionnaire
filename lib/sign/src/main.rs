use std::io;
use std::env;
use std::fs;
use pem;


use rsa_fdh;
use rsa_fdh::blind;
use rsa::{RSAPrivateKey, RSAPublicKey};
use sha2::{Sha256, Digest};

use rand;
use base64;

use serde::{Serialize, Deserialize};
use serde_json;


fn sign() {
    let mut rng = rand::thread_rng();

    let mut encoded_digest = String::new();
    io::stdin().read_line(&mut encoded_digest).unwrap();
    encoded_digest.truncate(encoded_digest.len() - 1);

    let signer_priv_key = fs::read_to_string("/root/pri.pem").unwrap();
    let signer_priv_key = pem::parse(signer_priv_key).unwrap();
    let signer_priv_key = RSAPrivateKey::from_pkcs1(&signer_priv_key.contents).unwrap();

    let blinded_digest = base64::decode(encoded_digest).unwrap();
    let blind_signature = blind::sign(&mut rng, &signer_priv_key, &blinded_digest).unwrap();
    let blind_signature = base64::encode(blind_signature);

    println!("{}", blind_signature);
}


fn verify() {
    let mut message = String::new();
    io::stdin().read_line(&mut message).unwrap();
    message.truncate(message.len() - 1);

    let mut signature = String::new();
    io::stdin().read_line(&mut signature).unwrap();
    signature.truncate(signature.len() - 1);
    let signature = base64::decode(signature).unwrap();
    
    let mut signer_pub_key = String::new();
    io::stdin().read_line(&mut signer_pub_key).unwrap();
    signer_pub_key.truncate(signer_pub_key.len() - 1);
    let signer_pub_key = base64::decode(signer_pub_key).unwrap();
    let signer_pub_key = pem::parse(signer_pub_key).unwrap();
    let signer_pub_key = RSAPublicKey::from_pkcs1(&signer_pub_key.contents).unwrap();

    let digest = blind::hash_message::<Sha256, _>(&signer_pub_key, message.as_bytes()).unwrap();

    match blind::verify(&signer_pub_key, &digest, &signature) {
        Ok(_) => { print!("verified") },
        Err(e) => { print!("{}", e) },
    };
}



pub fn main() {
    for (index, arg) in env::args().enumerate() {
        if index == 1 {
            match arg.as_ref() {
                "sign" => { sign() },
                "verify" => { verify() },
                _ => {}
            }
        }
    }
}
