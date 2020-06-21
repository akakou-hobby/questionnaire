extern crate wasm_bindgen;

use wasm_bindgen::prelude::*;

#[wasm_bindgen]
pub fn hello(name: &str) -> String {
    String::from(name) + "!!!!"
}