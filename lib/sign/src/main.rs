use std::io;
use std::env;

use pem;


use rsa_fdh;
use rsa_fdh::blind;
use rsa::{RSAPrivateKey, RSAPublicKey};
use sha2::{Sha256};

use rand;
use base64;

use std::error::Error;


fn sign() -> Result<(), Box<dyn Error>>  {
    let mut rng = rand::thread_rng();

    let mut encoded_digest = String::new();
    io::stdin().read_line(&mut encoded_digest)?;
    encoded_digest.truncate(encoded_digest.len() - 1);

    let mut signer_priv_key = String::new();
    io::stdin().read_line(&mut signer_priv_key)?;
    signer_priv_key.truncate(signer_priv_key.len() - 1);

    let signer_priv_key = match base64::decode(signer_priv_key) {
        Ok(result) => result,
        Err(err) => {
            eprintln!("[error] decode pubkey");
            return Err(Box::new(err));
        }  
    };

    let signer_priv_key = match pem::parse(signer_priv_key) {
        Ok(res) => res,
        Err(err) => {
            eprintln!("[error] parse pem");
            return Err(Box::new(err));
        }
    };

    let signer_priv_key = match RSAPrivateKey::from_pkcs8(&signer_priv_key.contents) {
        Ok(res) => res,
        Err(err) => {
            eprintln!("[error] parse pkcs");
            return Err(Box::new(err));
        }
    };

    let blinded_digest = match base64::decode(encoded_digest) {
        Ok(res) => res,
        Err(err) => {
            eprintln!("[error] decode blinded_digest");
            return Err(Box::new(err));
        }
    };
    
    let blind_signature = blind::sign(&mut rng, &signer_priv_key, &blinded_digest).unwrap();
    let blind_signature = base64::encode(blind_signature);

    println!("{}", blind_signature);

    Ok(())
}


fn verify() -> Result<(), Box<dyn Error>>{
    let mut message = String::new();
    io::stdin().read_line(&mut message)?;
    message.truncate(message.len() - 1);
    
    let message = match base64::decode(message) { 
        Ok(res) => res,
        Err(err) => {
            eprintln!("[error] decode message");
            return Err(Box::new(err));
        }
    };

    let message = String::from_utf8(message)?;

    let mut signature = String::new();
    io::stdin().read_line(&mut signature)?;
    signature.truncate(signature.len() - 1);
    let signature = match base64::decode(signature) {
        Ok(res) => res,
        Err(err) => {
            eprintln!("[error] decode signature");
            return Err(Box::new(err));
        }
    };

    let mut signer_pub_key = String::new();
    io::stdin().read_line(&mut signer_pub_key)?;
    signer_pub_key.truncate(signer_pub_key.len() - 1);
    
    let signer_pub_key = match base64::decode(signer_pub_key) {
        Ok(result) => result,
        Err(err) => {
            eprintln!("[error] decode pubkey");
            return Err(Box::new(err));
        }  
    };

    let signer_pub_key = match pem::parse(signer_pub_key) {
        Ok(res) => res,
        Err(err) => {
            eprintln!("[error] parse pem");
            return Err(Box::new(err));
        }
    };

    let signer_pub_key = match RSAPublicKey::from_pkcs8(&signer_pub_key.contents) {
        Ok(res) => res,
        Err(err) => {
            eprintln!("[error] parse pkcs1");
            return Err(Box::new(err));
        }
    };

    let digest = blind::hash_message::<Sha256, _>(&signer_pub_key, message.as_bytes()).unwrap();

    match blind::verify(&signer_pub_key, &digest, &signature) {
        Ok(_) => { print!("verifyed") },
        Err(e) => { print!("{}", e) },
    };

    Ok(())
}


pub fn main() {
    for (index, arg) in env::args().enumerate() {
        if index != 1 {
            continue;
        }

        let result = match arg.as_ref() {
            "sign" => sign(),
            "verify" => verify(),
            _ => Ok(())
        };

        match result {
            Ok(_) => {}
            Err(err) => {
                eprintln!("{}", err.to_string())
            }
        }
    }
}
